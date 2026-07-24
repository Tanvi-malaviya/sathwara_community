<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the events.
     */
    public function index(Request $request)
    {
        $query = Event::where('status', 'published');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        $events = $query->orderBy('date', 'desc')->paginate(10)->withQueryString();
        $user = auth()->user();
        $registrations = $user->eventRegistrations()->pluck('status', 'event_id')->toArray();

        return view('member.event.index', compact('events', 'registrations'));
    }

    /**
     * Display the specified event.
     */
    public function show($id)
    {
        $event = Event::where('status', 'published')->findOrFail($id);
        $user = auth()->user();
        $registration = $user->eventRegistrations()->where('event_id', $id)->first();
        $gallery = $event->galleries()->get();

        return view('member.event.show', compact('event', 'registration', 'gallery'));
    }

    /**
     * Display the dedicated registration form page for the specified event.
     */
    public function showRegistrationForm($id)
    {
        $event = Event::where('status', 'published')->findOrFail($id);
        $user = auth()->user();
        $registrations = $user->eventRegistrations()->where('event_id', $id)->orderBy('created_at', 'desc')->get();
        $registration = $registrations->first();
        $familyMembers = $user->familyMembers()->orderBy('name')->get();

        return view('member.event.register', compact('event', 'registration', 'registrations', 'familyMembers'));
    }
}
