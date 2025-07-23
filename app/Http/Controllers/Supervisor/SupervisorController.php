<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Registration;
use App\Models\DocumentRequirement;
use App\Models\User;
use Carbon\Carbon;

class SupervisorController extends Controller
{
    public function index()
    {
        $supervisorId = Auth::id();

        return view('supervisor.dashboard', [
            // Count only students who are approved and assigned to this supervisor
            'studentCount' => Registration::where('supervisor_id', $supervisorId)
                            ->where('status', 'approved')
                            ->count(),

            // Get upcoming documents
            'upcomingDocuments' => DocumentRequirement::where('due_date', '>=', Carbon::now())
                                    ->orderBy('due_date')
                                    ->take(5)
                                    ->get(),
        ]);
    }


    // Show all pending registrations for the logged-in supervisor
    public function showPendingRegistrations()
    {
        $supervisorId = Auth::id();

        $registrations = Registration::where('supervisor_id', $supervisorId)
                                     ->where('status', 'pending')
                                     ->with('student') // Make sure the student relationship exists
                                     ->get();

        return view('supervisor.approval', compact('registrations'));
    }

    // Handle approval or rejection of a registration
    public function updateRegistration(Request $request, $id)
    {
        $registration = Registration::where('id', $id)
                                    ->where('supervisor_id', Auth::id())
                                    ->firstOrFail();

        $action = $request->input('action');

        if ($action === 'approve') {
            // Check if already 5 students are approved for this semester
            $approvedCount = Registration::where('supervisor_id', Auth::id())
                ->where('year', $registration->input('year'))
                ->where('semester', $registration->input('semester'))
                ->where('status', 'approved')
                ->count();

            if ($approvedCount >= 5) {
                return redirect()->back()->with('error', 'You have already approved 5 students for this semester.');
            }

            $registration->status = 'approved';
        }
    }

    public function approval(Request $request)
    {
        $supervisorId = Auth::id();
        $year = $request->input('year');
        $semester = $request->input('semester');

        $query = Registration::with('student')
            ->where('supervisor_id', $supervisorId);

        if ($year) {
            $query->where('year', $year);
        }

        if ($semester) {
            $query->where('semester', $semester);
        }

        $registrations = (clone $query)->where('status', 'pending')->get();
        $approvedRegistrations = (clone $query)->where('status', 'approved')->get();
        $rejectedRegistrations = (clone $query)->where('status', 'rejected')->get();

        $availableYears = Registration::select('year')->distinct()->pluck('year');
        $availableSemesters = Registration::select('semester')->distinct()->pluck('semester');

        return view('supervisor.approval', compact(
            'registrations',
            'approvedRegistrations',
            'rejectedRegistrations',
            'availableYears',
            'availableSemesters',
            'year',
            'semester'
        ));
    } 
}
