<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DocumentRequirement;
use Carbon\Carbon;

//Duedate

class DocumentRequirementController extends Controller
{
    public function index()
    {
        $requirements = DocumentRequirement::all();
        return view('admin.requirements.index', compact('requirements'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'due_date' => 'required|date',
        ]);

        $requirement = DocumentRequirement::findOrFail($id);

        $dueDate = $request->input('due_date');

        if ($dueDate) {
            $dueDate = Carbon::now();
            // $dueDate = \Carbon\Carbon::parse($dueDate)->format('Y-m-d H:i:s');
        }

        $requirement->due_date = $dueDate;
        $requirement->save();

        return redirect()->route('admin.requirements.index')->with('success', 'Due date updated successfully.');
    }

    public function create()
    {
        return view('admin.requirements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'nullable|date',
        ]);

        $dueDate = $request->input('due_date');

        if ($dueDate) {
            // Handle datetime-local input
            $dueDate = Carbon::parse($dueDate)->format('Y-m-d H:i:s');
        }

        DocumentRequirement::create([
            'title' => $request->input(),
            'due_date' => $dueDate,
        ]);

        return redirect()->route('admin.requirements.index')->with('success', 'Requirement created successfully.');
    }

    public function destroy($id)
    {
        $requirement = DocumentRequirement::findOrFail($id);
        $requirement->delete();

        return redirect()->route('admin.requirements.index')->with('success', 'Requirement deleted successfully.');
    }
}
