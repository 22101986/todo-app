<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        return view('calendar');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'start' => 'required|date',
            'end' => 'nullable|date',
        ]);

        $event = Event::create($request->all());

        return response()->json($event);
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'string',
            'start' => 'date',
            'end' => 'nullable|date',
        ]);

        $event->update($request->all());

        return response()->json($event);
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return response()->json(['message' => 'Event deleted']);
    }

    public function getEvents()
    {
        $events = Event::all();
        return response()->json($events);
    }
}