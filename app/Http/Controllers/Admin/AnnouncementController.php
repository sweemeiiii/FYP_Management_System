<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->get();
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $wordCount = str_word_count($value);
                    if ($wordCount > 1500) {
                        $fail("The $attribute must not exceed 1500 words. Currently: $wordCount words.");
                    }
                },
            ],
            'document' => 'nullable|file|mimes:pdf,doc,docx,jpg,png',
        ]);

        $path = null;
        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('announcements', 'public');
        }

        Announcement::create([
            'title' => $request->input('title'),
            'message' => $request->input('message'),
            'document_path' => $path,
            'sent_at' => now(),
        ]);

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement posted successfully!');
    }
}
