<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;

class ReportController extends Controller
{
    public function filterForm()
    {
        $years = Registration::select('year')->distinct()->pluck('year');
        $supervisors = User::where('usertype', 'supervisor')->get();

        return view('admin.reports.filter', compact('years', 'supervisors'));
    }
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required',
            'semester' => 'required',
            'supervisor_id' => 'nullable',
        ]);

        $query = Registration::with(['student', 'supervisor'])
            ->where('year', $request->year)
            ->where('semester', $request->semester);

        if ($request->supervisor_id) {
            $query->where('supervisor_id', $request->supervisor_id);
        }
        // Get all registrations with related data
        $registrations = $query->get()->groupBy('supervisor_id');

        $pdf = Pdf::loadView('admin.reports.pdf', [
            'registrations' => $registrations,
            'year' => $request->year,
            'semester' => $request->semester,
        ]); 

        return $pdf->download("Report_{$request->year}_{$request->semester}.pdf");
    }
}
