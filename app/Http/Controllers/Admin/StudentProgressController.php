<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Document;
use App\Models\DocumentRequirement;
use Illuminate\Support\Facades\Storage;

class StudentProgressController extends Controller
{    
    public function index()
    {
        return redirect()->route('admin.student_progress.fyp1');
    }

    public function fyp1(Request $request)
    {
        return $this->handleFYP($request, 'Semester 1');
    }

    public function fyp2(Request $request)
    {
        return $this->handleFYP($request, 'Semester 2');
    }

    private function handleFYP(Request $request, $semester)
    {
        $students = User::with(['documents', 'registration.supervisor'])
            ->where('usertype', 'user')
            ->where('active_status', 1)
            ->whereHas('registration', fn($q) => $q->where('semester', $semester));

        // Apply filters
        if ($request->filled('year')) {
            $students->whereHas('registration', fn($q) => $q->where('year', $request->year));
        }
        if ($request->filled('supervisor_id')) {
            $students->whereHas('registration', fn($q) => $q->where('supervisor_id', $request->supervisor_id));
        }

        // Sort and paginate
        $studentsPaginated = $students->orderBy('created_at', 'desc')->paginate(10);

        // Additional data
        $requiredDocuments = ['Proposal', 'SRS', 'Final Report', 'Presentation Slide'];
        $requirements = DocumentRequirement::all()->keyBy('title');
        $supervisors = User::where('usertype', 'supervisor')->get();
        $totalStudents = $studentsPaginated->total(); // total from paginator

        return view('admin.student_progress.index', compact(
            'studentsPaginated',
            'requiredDocuments',
            'requirements',
            'supervisors',
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