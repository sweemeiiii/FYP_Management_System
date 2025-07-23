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
    public function index(Request $request)
    {
        $students = User::with(['documents', 'registration.supervisor'])
            ->where('usertype', 'user')
            ->where('active_status', 1) //only show active students 
            ->orderBy('created_at', 'desc')->get()
            ->filter(function ($student) use ($request) {
                $reg = $student->registration;

                // If no filter applied, include all
                if (!$request->filled('year') && !$request->filled('semester') && !$request->filled('supervisor_id')) {
                    return true;
                }

                // If no registration, exclude from filtered results
                if (!$reg) {
                    return false;
                }

                // Filter based on request
                $match = true;
                if ($request->filled('year')) {
                    $match = $match && $reg->year == $request->input('year');
                }
                if ($request->filled('semester')) {
                    $match = $match && $reg->semester == $request->input('semester');
                }
                if ($request->filled('supervisor_id')) {
                    $match = $match && $reg->supervisor_id == $request->input('supervisor_id');
                }

                return $match;
            });

        // Pagination manually
        $page = request()->get('page', 1);
        $perPage = 10;
        $studentsPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $students->forPage($page, $perPage),
            $students->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $requiredDocuments = ['Proposal', 'SRS', 'FYP1 Report','Presentation Slide'];
        $requirements = DocumentRequirement::all();
        $supervisors = User::where('usertype', 'supervisor')->get();
        $totalStudents = $students->count();

        return view('admin.student_progress.index', compact(
            'studentsPaginated',
            'requiredDocuments',
            'requirements',
            'supervisors',
            'totalStudents',
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
        $filename = $document->input('title') . '.' . $extension;

        return response()->download(storage_path("app/public/{$filePath}"), $filename);

    }
}