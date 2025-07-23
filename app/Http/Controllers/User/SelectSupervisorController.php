<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SelectSupervisorController extends Controller
{
    public function index()
    {
        $groupedSupervisors = User::where('usertype', 'supervisor')
            ->get()
            ->groupBy('department');

        return view('user.supervisor.index', compact('groupedSupervisors'));
    }

    public function showRegistrationForm($id)
    {
        $supervisor = User::findOrFail($id);

        return view('user.supervisor.register', compact('supervisor'));
    }
    

    public function register(Request $request)
    {
        $request->validate([
            'supervisor_id' => 'required|exists:users,id', 
            'year' => 'required|string',
        ]);

        $studentId = Auth::id();

        // Get the most recent registration
        $latest = Registration::where('student_id', $studentId)
            ->latest()
            ->first();

        if (!$latest) {
            $semester = 'Semester 1';
        }
        elseif ($latest->input('status') === 'rejected') {
            $semester = $latest->input('semester');
        }
        elseif ($latest->input('status') === 'approved' && $latest->input('semester') === 'Semester 1') {
            // Allow FYP2 only if 3 months have passed
            $approvedDate = $latest->input('updated_at');
            $cutoffDate = now()->subMonths(3);

            if ($approvedDate->gt($cutoffDate)) {
                $waitUntil = $approvedDate->copy()->addMonths(3)->format('d M Y');
                return back()->with('error', "You must wait at least 3 months after FYP1 finished. Please try again after $waitUntil.");
            }

            $semester = 'Semester 2';
        }
        elseif (in_array($latest->input('status'), ['pending', 'approved'])) {
            return back()->with('error', 'You already have a registration awaiting approval or already approved.');
        }
        elseif ($latest->input('semester') === 'Semester 2' && $latest->input('status') === 'approved') {
            return back()->with('error', 'You have already completed both FYP1 and FYP2.');
        }
        else {
            return back()->with('error', 'You are not eligible to register at this time.');
        }


        // set limit for supervisor register 
        $supervisorCount = Registration::where('supervisor_id', $request->input('supervisor_id'))
            ->where('year', $request->input('year'))
            ->where('semester', $semester)
            ->whereIn('status', ['pending', 'approved'])
            ->count();

        if ($supervisorCount >= 5) {
            return back()->with('error', 'This supervisor has already reached the limit of 5 students for this semester.');
        }

        // Register the student
        Registration::create([
            'student_id' => $studentId,
            'supervisor_id' => $request->input('supervisor_id'),
            'status' => 'pending',
            'year' => $request->input('year'),
            'semester' => $semester,
        ]);

        User::where('id', $studentId)->update(['supervisor_id' => $request->input('supervisor_id')]);

        return redirect()->route('supervisor.register', $request->input('supervisor_id'))
            ->with('success', "Registration for $semester submitted successfully.");
    }




}
