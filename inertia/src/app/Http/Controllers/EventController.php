<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $all = $request->input('all');
        $currentFilter = 'upcoming';

        if ($all) {
            $events = Event::all();
            $currentFilter = 'all';
        } elseif ($startDate && $endDate) {
            $events = Event::dateRange($startDate, $endDate)->get();
            $currentFilter = 'date_range';
        } else {
            $events = Event::upcoming()->get();
        }

        return Inertia::render('Event/Index', [
            'events' => $events,
            'currentFilter' => $currentFilter
        ]);
    }


    public function create(): \Inertia\Response
    {
        return Inertia::render('Event/Create');
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Event::create($data);

        return redirect()->route('events.index');
    }

    public function show(Event $event): \Inertia\Response
    {
        return Inertia::render('Event/Show', ['event' => $event]);
    }

    public function edit(Event $event): \Inertia\Response
    {
        return Inertia::render('Event/Edit', ['event' => $event]);
    }

    public function update(Request $request, Event $event): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $event->update($data);

        return redirect()->route('events.index');
    }

    public function destroy(Event $event): \Illuminate\Http\RedirectResponse
    {
        $event->delete();
        return redirect()->route('events.index');
    }
}
