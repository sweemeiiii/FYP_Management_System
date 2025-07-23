<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\DocumentRequirement;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class CalendarController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Find user's registration info
        $registration = $user->registration;

        $year = $registration?->input('year');
        $semester = $registration?->input('semester');

        $documentEvents = [];

        if ($year && $semester) {
            $requirements = DocumentRequirement::all();

            foreach ($requirements as $req) {
                $documentEvents[] = [
                    'title' => $req->title . ' Due',
                    'start' => Carbon::parse($req->due_date)->toDateString(),
                    'end' => Carbon::parse($req->due_date)->toDateString(),
                    'color' => '#f87171', // red
                ];
            }
        }

        return view('user.calendar.index', compact('documentEvents'));
    }

    public function fetchEvents()
    {
        $events = Event::where('user_id', Auth::id())->get(['id', 'title', 'start', 'end']);
        return response()->json($events);
    }

    public function storeEvent(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:40',
            'start' => 'required|date',
            'end' => 'nullable|date',
        ], [
            'title.required' => 'Event title is required.',
            'title.max' => 'Event title cannot exceed 40 characters.',
        ]);
       
        $event = Event::create([
            'user_id' => Auth::id(),
            'title' => $request->input('title'),
            'start' => $request->input('start'),
            'end' => $request->input('end'),
        ]);

        return response()->json($event);
    }

    public function updateEvent(Request $request, $id)
    {
        try {
            $event = Event::where('id', $id)
                         ->where('user_id', Auth::id())
                         ->firstOrFail();

            // Convert ISO 8601 dates to MySQL format
            $startDate = Carbon::parse($request->input('start'))->format('Y-m-d H:i:s');
            $endDate = $request->input('end') ? Carbon::parse($request->input('end'))->format('Y-m-d H:i:s') : $startDate;

            $event->update([
                'title' => $request->input('title'),
                'start' => $startDate,
                'end' => $endDate,
            ]);

            return response()->json([
                'success' => true,
                'event' => $event->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update event: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteEvent(Request $request)
    {
        $event = Event::where('id', $request->input('id'))->where('user_id', Auth::id())->firstOrFail();
        $event->delete();

        return response()->json(['success' => true]);
    }

}
