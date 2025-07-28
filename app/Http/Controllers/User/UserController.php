<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentRequirement;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $upcomingEvents = Event::where('user_id', $user->id)
            ->where('start', '>=', Carbon::now())
            ->orderBy('start')
            ->take(5)
            ->get();

        $registration = Registration::where('student_id', $user->id)->latest()->first();

        $supervisor = $user->supervisor;

        $totalDocuments = DocumentRequirement::count();
        $submittedDocuments = Document::where('user_id', $user->id)->count();
        $lastSubmission = Document::where('user_id', $user->id)->latest('submitted_at')->first();
        $documentRequirements = DocumentRequirement::select('title', 'due_date')->get();

        return view('user.dashboard', [
            'upcomingEvents' => $upcomingEvents,
            'registration' => $registration,
            'supervisor' => $supervisor,
            'totalDocuments' => $totalDocuments,
            'submittedDocuments' => $submittedDocuments,
            'lastUpdated' => optional($lastSubmission)->submitted_at,
            'documentRequirements' => $documentRequirements,
        ]);
    }
}
