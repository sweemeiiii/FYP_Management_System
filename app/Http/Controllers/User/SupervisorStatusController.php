<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SupervisorStatusController extends Controller
{
    public function index()
    {
        $studentId = Auth::id();
        $registration = Registration::with('supervisor')
                            ->where('student_id', $studentId)
                            ->latest()
                            ->get();

        return view('user.supervisor.status', compact('registration'));
    }
}
