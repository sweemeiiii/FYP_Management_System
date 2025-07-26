<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Document;
use App\Models\Registration;
use App\Models\DocumentRequirement;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class StudentProgressController extends Controller
{
    public function index()
    {
        return redirect()->route('supervisor.student_progress.fyp1');
    }
    public function fyp1(Request $request)
    {
        return $this->handleFYP($request, 'Semester 1');
    }

    public function fyp2(Request $request)
    {
        return $this->handleFYP($request, 'Semester 2');
    }

    protected function handleFYP(Request $request, $semester)
    {
        $supervisorId = Auth::id();

        $students = User::with(['documents', 'registration', 'registration.supervisor'])
            ->where('usertype', 'user')
            ->where('active_status', 1)
            ->whereHas('registration', function($q) use ($supervisorId, $semester, $request) {
                $q->where('supervisor_id', $supervisorId)
                ->where('semester', $semester)
                ->where('status', 'approved');
                
                if ($request->filled('year')) {
                    $q->where('year', $request->year);
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            //->get();  // Using get() since supervisor has fewer students than admin

        $requiredDocuments = ['Proposal', 'SRS', 'Final Report', 'Presentation Slide'];
        $requirements = DocumentRequirement::all()->keyBy('title');
        $totalStudents = $students->count();
        
        return view('supervisor.student_progress.index', compact(
            'students',
            'requiredDocuments',
            'requirements',
            'totalStudents',
            'semester'
        ));
    }

    /**
     * Download a document file.
     *
     * @param \App\Models\Document $document
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download(Document $document)
    {
        $filePath = $document->file_path;

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found');
        }

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $filename = $document->title . '.' . $extension;

        return response()->download(storage_path("app/public/{$filePath}"), $filename);
    }
}
