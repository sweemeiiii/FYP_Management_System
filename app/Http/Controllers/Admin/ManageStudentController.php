<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ManageStudentController extends Controller
{
    public function index()
    {
        $students = User::where('usertype', 'user')
                ->where('active_status', 1)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

        // hightlight the re-registrations students(archived)
        $archivedStudents = User::where('active_status', 2)->pluck('email')->toArray();

        return view('admin.student.index', compact('students', 'archivedStudents'));
    }

    public function create()
    {
        return view('admin.student.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'usertype' => 'required|in:user,supervisor',
            'student_id' => 'required|string|min:7|max:9|unique:users,student_id',
            'name' => 'required|string|min:1|max:50',
            'email' => [
                'required',
                'email',
                'unique:users,email',
                'regex:/^[A-Za-z0-9]+@student\.uow\.edu\.my$/',
                function ($attribute, $value, $fail) use ($request) {
                    $expected = $request->student_id . '@student.uow.edu.my';
                    if ($value !== $expected) {
                        $fail('The email must match the student ID exactly (e.g., '.$expected.').');
                    }
                }
            ],
            'department' => 'required|string|not_in:""',
            'password' => 'required|string|min:8|max:15',
        ]);

        // $request->validate([
        //     'usertype' => 'required|in:user,supervisor',
        //     'student_id' => 'required|unique:users,student_id',
        //     'name' => 'required',
        //     'email' => 'required|email|unique:users,email',
        //     'department' => 'required',
        //     'password' => 'required|min:6',
        // ]);

        
        User::create([
            'student_id' => $request->usertype === 'user' ? $request->student_id : null,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'usertype' => $request->usertype,  
            'department' => $request->department,
            'active_status' => 1,
        ]);

        return redirect()->route('admin.student.index')->with('success', 'Student created successfully.');
    }

    public function edit($id)
    {
        $student = User::findOrFail($id);
        return view('admin.student.edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $student = User::findOrFail($id);

        $request->validate([
            'student_id' => 'required|unique:users,student_id,' . $student->id,
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $student->id,
            'department' => 'required',
        ]);

        $student->student_id = $request->student_id;
        $student->name = $request->name;
        $student->email = $request->email;
        $student->department = $request->department;

        if ($request->password) {
            $student->password = Hash::make($request->password);
        }

        $student->save();

        return redirect()->route('admin.student.index')->with('success', 'Student updated successfully.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt'
        ]);

        $file = fopen($request->file('csv_file'), 'r');
        $header = fgetcsv($file); // First row (column headers)

        // Clean header keys by trimming extra spaces
        $header = array_map(fn($h) => strtolower(trim($h)), $header);


        $successCount = 0;
        $failCount = 0;

        while ($row = fgetcsv($file)) {
            if (count($row) !== count($header)) {
                Log::warning('Skipping row due to column mismatch', ['row' => $row]);
                $failCount++;
                continue;
            }

            $data = array_combine($header, $row);

            $validator = Validator::make($data, [
                'usertype' => 'required|in:user,supervisor',
                'student_id' => 'required|unique:users,student_id',
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'department' => 'required',
                'password' => 'required|min:6',
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed', ['data' => $data, 'errors' => $validator->errors()->toArray()]);
                $failCount++;
                continue;
            }

            User::create([
                'student_id' => $data['usertype'] === 'user' ? $data['student_id'] : null,
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'usertype' => $data['usertype'],
                'department' => $data['department'],
                'active_status' => 1,
            ]);

            $successCount++;
        }

        fclose($file);

        return redirect()->route('admin.student.index')
            ->with('success', "$successCount students imported, $failCount failed.");
    }

    
    public function archive($id)
    {
        $student = User::findOrFail($id);
        $student->active_status = 2; // Manual archive
        $student->save();

        return redirect()->route('admin.student.index')->with('success', 'Student archived successfully.');
    }

    public function graduate($id)
    {
        $student = User::findOrFail($id);
        $student->active_status = 3; // Graduated
        $student->save();

        return redirect()->route('admin.student.index')->with('success', 'Student marked as graduated.');
    }

    public function multipleSelect(Request $request)
    {
        $studentIds = $request->input('student_ids');
        $action = $request->input('action');

        if (!$studentIds || !is_array($studentIds)) {
            return back()->with('error', 'No students selected.');
        }

        foreach ($studentIds as $id) {
            $student = User::find($id);
            if (!$student) continue;

            if ($action === 'archive') {
                $student->active_status = 2;
            } elseif ($action === 'graduate') {
                $student->active_status = 3; 
            }

            $student->save();
        }

        return back()->with('success', 'Selected students updated successfully.');
    }

}
