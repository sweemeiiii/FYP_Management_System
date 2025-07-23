<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;

class NotificationStudentController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->get();
        return view('user.notifications.index', compact('announcements'));
    }
}
