<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Supervisor\SupervisorController;
use App\Http\Controllers\Admin\ManageStudentController;
use App\Http\Controllers\Admin\ManageSupervisorController;
use App\Http\Controllers\Admin\DocumentRequirementController;
use App\Http\Controllers\User\CalendarController;
use App\Http\Controllers\User\DocumentController;
use App\Http\Controllers\User\SelectSupervisorController;
use App\Http\Controllers\User\SupervisorStatusController;
use App\Http\Controllers\User\NotificationStudentController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\User\UserController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->usertype === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->usertype === 'supervisor') {
        return redirect()->route('supervisor.dashboard');
    } else {
        return redirect()->route('user.dashboard'); // Or just 'home' if user has no specific dashboard
    }
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('admin/dashboard',[HomeController::class,'index'])->
    middleware(['auth', 'admin']);

Route::get('supervisor/dashboard', [SupervisorController::class, 'index'])
    ->middleware(['auth', 'supervisor'])
    ->name('supervisor.dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('admin/student/index', [ManageStudentController::class, 'index'])->name('admin.student.index');
    Route::get('admin/student/create', [ManageStudentController::class, 'create'])->name('admin.student.create');
    Route::post('admin/student/import',[ManageStudentController::class,'import'])->name('admin.student.import');
    Route::post('admin/student/index', [ManageStudentController::class, 'store'])->name('admin.student.store');
    Route::get('admin/student/edit/{id}', [ManageStudentController::class, 'edit'])->name('admin.student.edit');
    Route::put('admin/student/{id}', [ManageStudentController::class, 'update'])->name('admin.student.update');
    Route::delete('admin/student/{id}', [ManageStudentController::class, 'destroy'])->name('admin.student.destroy');
    Route::post('/admin/student/{id}/archive', [ManageStudentController::class, 'archive'])->name('admin.student.archive');
    Route::post('admin/student/{id}/graduate', [ManageStudentController::class, 'graduate'])->name('admin.student.graduate');
    Route::post('admin/students/multiple-select', [ManageStudentController::class, 'multipleSelect'])->name('admin.student.multipleSelect');
});

Route::middleware(['auth'])->group(function () {
    Route::get('admin/supervisor/index', [ManageSupervisorController::class, 'index'])->name('admin.supervisor.index');
    Route::get('admin/supervisor/create', [ManageSupervisorController::class, 'create'])->name('admin.supervisor.create');
    Route::post('admin/supervisor/index', [ManageSupervisorController::class, 'store'])->name('admin.supervisor.store');
    Route::get('admin/supervisor/{id}/edit', [ManageSupervisorController::class, 'edit'])->name('admin.supervisor.edit');
    Route::put('admin/supervisor/{id}', [ManageSupervisorController::class, 'update'])->name('admin.supervisor.update');
    Route::delete('admin/supervisor/{id}', [ManageSupervisorController::class, 'destroy'])->name('admin.supervisor.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('admin/thesis/index', [\App\Http\Controllers\Admin\ThesisController::class, 'index'])->name('admin.thesis.index');
    Route::get('admin/thesis/create', [\App\Http\Controllers\Admin\ThesisController::class, 'create'])->name('admin.thesis.create');
    Route::post('admin/thesis', [\App\Http\Controllers\Admin\ThesisController::class, 'store'])->name('admin.thesis.store');
    Route::get('admin/thesis/{thesis}/edit', [\App\Http\Controllers\Admin\ThesisController::class, 'edit'])->name('admin.thesis.edit');
    Route::put('admin/thesis/{thesis}', [\App\Http\Controllers\Admin\ThesisController::class, 'update'])->name('admin.thesis.update');
    Route::delete('admin/thesis/{thesis}', [\App\Http\Controllers\Admin\ThesisController::class, 'destroy'])->name('admin.thesis.destroy');
});


Route::middleware(['auth'])->group(function () {
    Route::post('admin/reports/registration', [App\Http\Controllers\Admin\ReportController::class, 'generate'])->name('admin.reports.registration');
    Route::get('admin/reports/filter', [App\Http\Controllers\Admin\ReportController::class, 'filterForm'])->name('admin.reports.filter');
});

Route::middleware(['auth'])->group(function () {
    Route::get('admin/announcements', [AnnouncementController::class, 'index'])->name('admin.announcements.index');
    Route::get('admin/announcements/create', [AnnouncementController::class, 'create'])->name('admin.announcements.create');
    Route::post('admin/announcements', [AnnouncementController::class, 'store'])->name('admin.announcements.store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('admin/requirements/index', [DocumentRequirementController::class, 'index'])->name('admin.requirements.index');
    Route::get('admin/requirements/create', [DocumentRequirementController::class, 'create'])->name('admin.requirements.create');
    Route::post('admin/requirements/store', [DocumentRequirementController::class, 'store'])->name('admin.requirements.store');
    Route::put('admin/requirements/{id}', [DocumentRequirementController::class, 'update'])->name('admin.requirements.update');
    Route::delete('admin/requirements/{id}', [DocumentRequirementController::class, 'destroy'])->name('admin.requirements.destroy');
});

Route::get('user/thesis/index',[\App\Http\Controllers\Admin\ThesisController::class,'userIndex'])->name('user.thesis.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/documents/upload', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents/store', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{id}/edit', [DocumentController::class, 'edit'])->name('user.documents.edit');
    Route::put('/documents/{id}', [DocumentController::class, 'update'])->name('user.documents.update');
    Route::delete('/documents/{id}', [DocumentController::class, 'destroy'])->name('user.documents.destroy');
    Route::get('user/documents', [DocumentController::class, 'index'])->name('user.documents.index');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/user/calendar', [CalendarController::class, 'index'])->name('user.calendar.index');
    Route::get('/events', [CalendarController::class, 'fetchEvents'])->name('calendar.events');
    Route::post('/events', [CalendarController::class, 'storeEvent'])->name('calendar.store');
    Route::put('/events/{id}', [CalendarController::class, 'updateEvent'])->name('calendar.update');
    Route::delete('/events/{id}', [CalendarController::class, 'deleteEvent'])->name('calendar.delete');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/supervisor/calendar', [\App\Http\Controllers\Supervisor\CalendarController::class, 'index'])->name('supervisor.calendar.index');
    Route::get('/supervisor/events', [\App\Http\Controllers\Supervisor\CalendarController::class, 'fetchEvents'])->name('supervisor.calendar.events');
    Route::post('/supervisor/calendar', [\App\Http\Controllers\Supervisor\CalendarController::class, 'storeEvent'])->name('supervisor.calendar.store');
    Route::delete('/supervisor/calendar/{id}', [\App\Http\Controllers\Supervisor\CalendarController::class, 'deleteEvent'])->name('supervisor.calendar.delete');
    Route::put('/supervisor/calendar/{id}', [\App\Http\Controllers\Supervisor\CalendarController::class, 'updateEvent'])->name('supervisor.calendar.update');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/supervisor/student-progress', [\App\Http\Controllers\Supervisor\StudentProgressController::class, 'index'])->name('supervisor.student_progress.index');
    Route::get('/supervisor/download/{document}', [\App\Http\Controllers\Supervisor\StudentProgressController::class, 'download'])->name('supervisor.document.download');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/student-progress', [\App\Http\Controllers\Admin\StudentProgressController::class, 'index'])->name('admin.student_progress.index');
    Route::get('/admin/download/{document}', [\App\Http\Controllers\Admin\StudentProgressController::class, 'download'])->name('admin.document.download');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/user/supervisor', [SelectSupervisorController::class, 'index'])->name('user.supervisor.index');
    Route::get('/register-supervisor/{id}', [SelectSupervisorController::class, 'showRegistrationForm'])->name('supervisor.register');
    Route::post('/register-supervisor', [SelectSupervisorController::class, 'register'])->name('supervisor.register.store');
});

Route::get('/supervisor/status', [SupervisorStatusController::class, 'index'])->name('user.supervisor.status');
Route::get('/supervisor/approval', [SupervisorController::class, 'approval'])->name('supervisor.approval');
Route::put('/supervisor/approval/{id}', [SupervisorController::class, 'updateRegistration'])->name('supervisor.approval.update');

Route::middleware(['auth'])->group(function(){
    Route::get('/user/notifications',[NotificationStudentController::class,'index'])->name('user.notifications.index');
});

Route::middleware(['auth'])->group(function () {
    // Calendar routes
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/events', [CalendarController::class, 'fetchEvents'])->name('calendar.events');
    Route::post('/calendar', [CalendarController::class, 'storeEvent'])->name('calendar.store');
    Route::put('/calendar/{id}', [CalendarController::class, 'updateEvent'])->name('calendar.update');
    Route::delete('/calendar/{id}', [CalendarController::class, 'deleteEvent'])->name('calendar.delete');
});


Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});