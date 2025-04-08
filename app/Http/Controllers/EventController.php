<?php

namespace App\Http\Controllers;

use App\Models\Event; // Make sure to include the Event model
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        // Fetch events where status is 'Published'
        $events = Event::where('status', 'Published')->get();

        return view('events.index', compact('events'));
    }

    public function show($id)
    {
        // Find the event by ID
        $event = Event::findOrFail($id);

        // Return a view for the event details
        return view('events.show', compact('event'));
    }
}
