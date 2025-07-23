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
    public function index(Request $request)
    {
        $supervisorId = Auth::id();
        $requiredDocuments = ['Proposal', 'SRS', 'Presentation Slide'];

        $requirements = DocumentRequirement::all()->keyBy('title');


        $today = now();

        // Base query: only get students under this supervisor
        $query = Registration::where('supervisor_id', $supervisorId)
                            ->where('status', 'approved')
                            ->with('student');

        // Apply filters
        if ($request->filled('year')) {
            $query->where('year', $request->input('year'));
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->input('semester'));  // Match string exactly, e.g., "Semester 1"
        }

        $registrations = $query->get();

        $progressData = [];

        foreach ($registrations as $registration) {
            $student = $registration->student;
            $submittedDocs = Document::where('user_id', $student->id)->get();
            $progress = [];

            foreach ($requiredDocuments as $doc) {
                $docKey = Str::lower(Str::replace(' ', '', $doc));
                $matchedDoc = null;

                foreach ($submittedDocs as $submittedDoc) {
                    $titleKey = Str::lower(Str::replace([' ', '_', '-'], '', $submittedDoc->title));
                    if (Str::contains($titleKey, $docKey)) {
                        $matchedDoc = $submittedDoc;
                        break;
                    }
                }

                $dueDate = optional($requirements[$doc])->input('due_date');

                $status = 'pending';
                if ($matchedDoc) {
                    $status = 'submitted';
                } elseif ($dueDate && $today->gt($dueDate)) {
                    $status = 'overdue';
                }

                $progress[$doc] = [
                    'document' => $matchedDoc,
                    'status' => $status,
                    'due' => $dueDate,
                ];
            }

            $progressData[] = [
                'student' => $student,
                'documents' => $progress,
            ];
        }

        return view('supervisor.student_progress.index', compact('progressData', 'requiredDocuments'));
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
        $filename = $document->input('title') . '.' . $extension;

        return response()->download(storage_path("app/public/{$filePath}"), $filename);
    }
}
