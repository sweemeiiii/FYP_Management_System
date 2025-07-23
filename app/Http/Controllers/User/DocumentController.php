<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\DocumentRequirement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::where('user_id', Auth::id())->get();
        $requirements = DocumentRequirement::orderBy('due_date')->get();
        return view('user.documents.index', compact('documents', 'requirements'));
    }

    public function create()
    {
        $requirement = DocumentRequirement::orderBy('due_date', 'desc')->first(); 
        $dueDate = $requirement ? $requirement->due_date : null;

        return view('user.documents.upload', compact('dueDate'));
    }

    public function edit($id)
    {
        $document = Document::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$document) {
            return redirect()->route('user.documents.index')->with('error', 'Document not found or unauthorized access!');
        }

        return view('user.documents.edit', compact('document'));
    }    

    public function update(Request $request, $id)
    {
        $request->validate([
            'documentTitle' => 'required|string|max:255',
            'documentFile' => 'nullable|file|mimes:pdf',
        ]);

        $document = Document::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$document) {
            return redirect()->route('user.documents.index')->with('error', 'Document not found or unauthorized access!');
        }

        // Update title
        $document->title = $request->input('documentTitle');

        // Check if a new file was uploaded
        if ($request->hasFile('documentFile')) {
            // Delete the old file from storage
            Storage::delete('public/' . $document->input('file_path'));

            // Store the new file and update the file path
            $document->file_path = $request->file('documentFile')->store('documents', 'public');
        }

        $document->save();

        return redirect()->route('user.documents.index')->with('success', 'Document updated successfully!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'documentTitle' => 'required|string|max:255',
            'documentFile' => 'required|file|mimes:pdf',
        ]);

        $requirement = DocumentRequirement::where('title', $request->input('documentTitle'))->first();
        $dueDate = $requirement ? $requirement->due_date : null;

        $now = now();
        $isLate = $dueDate && $now->greaterThan($dueDate);

        $path = $request->file('documentFile')->store('documents', 'public');

        Document::create([
            'user_id' => Auth::id(),
            'title' => $request->input('documentTitle'),
            'file_path' => $path,
            'submitted_at' => $now,
            'is_late' => $isLate,
        ]);

        return redirect()->route('user.documents.index')->with(
            'success',
            $isLate
                ? 'Document uploaded successfully, but it was submitted late.'
                : 'Document uploaded successfully!'
        );
    }

    public function destroy($id)
    {
        $document = Document::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$document) {
            return redirect()->route('user.documents.index')->with('error', 'Document not found or unauthorized access!');
        }

        // Delete the file from storage
        Storage::delete('public/' . $document->input('file_path'));

        // Delete the document from the database
        $document->delete();

        return redirect()->route('user.documents.index')->with('success', 'Document deleted successfully!');
    }
}
