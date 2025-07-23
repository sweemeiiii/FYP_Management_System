<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Thesis;
use Illuminate\Support\Facades\Storage;

class ThesisController extends Controller
{
    public function index()
    {
        $theses = Thesis::latest()->paginate(5);

        return view('admin.thesis.index', compact('theses'));
    }

    public function create()
    {
        return view('admin.thesis.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:40',
            'year' => 'required|numeric',
            'title' => 'required|string|max:255',
            // 'type' => 'required|string',
            // 'university' => 'required|string|max:255',
            'document' => 'required|mimes:pdf', 
        ]);

        // Store the file in storage/uploads
        $path = $request->file('document')->store('uploads', 'public');

        Thesis::create([
            'name' => $request->name,
            'year' => $request->year,
            'title' => $request->title,
            // 'type' => $request->type,
            // 'university' => $request->university,
            'document_path' => $path,
        ]);

        return redirect()->route('admin.thesis.index')->with('success', 'Thesis uploaded successfully.');
    }

    public function edit(Thesis $thesis)
    {
        return view('admin.thesis.edit', compact('thesis'));
    }

    public function update(Request $request, Thesis $thesis)
    {
        $request->validate([
            'name' => 'required|string|max:40',
            'year' => 'required|numeric',
            'title' => 'required|string|max:255',
            // 'type' => 'required|string',
            'document' => 'nullable|mimes:pdf', 
        ]);

        $data = $request->only('name', 'year', 'title', 'type');

        if ($request->hasFile('document')) {
            if ($thesis->document_path) {
                Storage::delete($thesis->document_path);
            }
            $data['document_path'] = $request->file('document')->store('uploads', 'public'); // fixed path
        }        

        $thesis->update($data);

        return redirect()->route('admin.thesis.index')->with('success', 'Thesis updated successfully.');
    }

    public function destroy(Thesis $thesis)
    {
        if ($thesis->document_path) {
            Storage::delete($thesis->document_path);
        }

        $thesis->delete();

        return redirect()->route('admin.thesis.index')->with('success', 'Thesis deleted successfully.');
    }

    public function userIndex(Request $request)
    {
        $query = Thesis::query();

        // Search by name or title
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('title', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by year
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Paginate
        $theses = $query->latest()->paginate(10)->appends($request->all());

        // For filter options
        $years = Thesis::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');
        $types = Thesis::select('type')->distinct()->pluck('type');

        return view('user.thesis.index', compact('theses', 'years', 'types'));
    }


}
