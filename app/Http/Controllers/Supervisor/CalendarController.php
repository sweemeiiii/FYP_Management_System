<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use App\Models\DocumentRequirement;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        $documentEvents = [];
        $requirements = DocumentRequirement::all();
        foreach ($requirements as $req) {
            $documentEvents[] = [
                'title' => $req->title . ' Due',
                'start' => Carbon::parse($req->due_date)->toDateString(),
                'end' => Carbon::parse($req->due_date)->toDateString(),
                'color' => '#f87171',
            ];
        }

        return view('supervisor.calendar.index', compact('documentEvents'));
    }


    public function fetchEvents()
    {
        // Only fetch events created by the current supervisor
        $events = Event::where('user_id', Auth::id())->get(['id', 'title', 'start', 'end']);
        return response()->json($events);

    }

    public function storeEvent(Request $request)
    {
        $event = Event::create([
            'title' => $request->title,
            'start' => $request->start,
            'end' => $request->end,
            'user_id' => Auth::id()
        ]);

        return response()->json($event);
    }

    public function deleteEvent($id)
    {
        $event = Event::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $event->delete();

        return response()->json(['success' => true]);
    }


    public function updateEvent(Request $request, $id)
    {
        try {
            $event = Event::where('id', $id)
                         ->where('user_id', Auth::id())
                         ->firstOrFail();

            // Convert ISO 8601 dates to MySQL format
            $startDate = Carbon::parse($request->start)->format('Y-m-d H:i:s');
            $endDate = $request->end ? Carbon::parse($request->end)->format('Y-m-d H:i:s') : $startDate;

            $event->update([
                'title' => $request->title,
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
}
