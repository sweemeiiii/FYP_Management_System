<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ManageSupervisorController extends Controller
{
    public function index()
    {
        $supervisors = User::where('usertype', 'supervisor')->paginate(10);
        return view('admin.supervisor.index', compact('supervisors'));
    }

    public function create()
    {
        return view('admin.supervisor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'usertype' => 'required|in:user,supervisor',
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'department' => 'required',
        ]);

        User::create([
            'usertype' => $request->usertype,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'department' => $request->department,
        ]);

        return redirect()->route('admin.supervisor.index')->with('success', 'Supervisor created successfully.');
    }

    public function edit($id)
    {
        $supervisor = User::findOrFail($id);
        return view('admin.supervisor.edit', compact('supervisor'));
    }

    public function update(Request $request, $id)
    {
        $supervisor = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $supervisor->id,
            'department' => 'required',
        ]);

        $supervisor->name = $request->name;
        $supervisor->email = $request->email;
        $supervisor->department = $request->department;

        if ($request->password) {
            $supervisor->password = Hash::make($request->password);
        }

        $supervisor->save();

        return redirect()->route('admin.supervisor.index')->with('success', 'Supervisor updated successfully.');
    }

    public function destroy($id)
    {
        $supervisor = User::findOrFail($id);
        $supervisor->delete();

        return redirect()->route('admin.supervisor.index')->with('success', 'Supervisor deleted successfully.');
    }
}
