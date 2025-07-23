<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DocumentRequirement;
use App\Models\User;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'userCount' => User::count(),
            'studentCount' => User::where('usertype', 'user')->count(),
            'supervisorCount' => User::where('usertype', 'supervisor')->count(),
            'upcomingDocuments' => DocumentRequirement::where('due_date', '>=', Carbon::now())->orderBy('due_date')->take(5)->get(),
        ]);
    }
}
