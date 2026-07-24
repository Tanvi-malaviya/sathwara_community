<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * List Events
     */
    public function index(Request $request)
    {
        $query = Event::withCount('registrations');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        $events = $query->orderBy('date', 'desc')->paginate(15)->withQueryString();
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show Event Details
     */
    public function show($id)
    {
        $event = Event::withCount('registrations')->findOrFail($id);
        $gallery = Gallery::where('event_id', $event->id)->orderBy('display_order')->get();
        $registrations = EventRegistration::where('event_id', $event->id)
            ->with(['user.memberProfile'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.events.show', compact('event', 'gallery', 'registrations'));
    }

    /**
     * Create Form
     */
    public function create()
    {
        return view('admin.events.create');
    }

    /**
     * Store Event
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'event_type' => 'required|in:normal,inam_vitaran,yuva_melo',
            'description' => 'required|string',
            'venue' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required',
            'banner' => 'nullable|image|max:3072',
            'has_registration_form' => 'required|boolean',
            'pass_fee' => 'nullable|numeric|min:0',
            'max_participants' => 'nullable|integer|min:0',
            'status' => 'required|in:draft,published,cancelled',
        ]);

        $bannerPath = null;
        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('events/banners', 'public');
        }

        Event::create([
            'title' => $request->title,
            'event_type' => $request->event_type,
            'description' => $request->description,
            'venue' => $request->venue,
            'date' => $request->date,
            'time' => $request->time,
            'banner_path' => $bannerPath ?? '',
            'registration_option' => $request->has_registration_form,
            'has_registration_form' => $request->has_registration_form,
            'pass_fee' => $request->pass_fee ?? 0.00,
            'max_participants' => $request->max_participants,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
    }

    /**
     * Edit Form
     */
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('admin.events.edit', compact('event'));
    }

    /**
     * Update Event
     */
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'event_type' => 'required|in:normal,inam_vitaran,yuva_melo',
            'description' => 'required|string',
            'venue' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required',
            'banner' => 'nullable|image|max:3072',
            'has_registration_form' => 'required|boolean',
            'pass_fee' => 'nullable|numeric|min:0',
            'max_participants' => 'nullable|integer|min:0',
            'status' => 'required|in:draft,published,cancelled',
        ]);

        $bannerPath = $event->banner_path;
        if ($request->hasFile('banner')) {
            if (Storage::disk('public')->exists($event->banner_path) && !str_starts_with($event->banner_path, 'http')) {
                Storage::disk('public')->delete($event->banner_path);
            }
            $bannerPath = $request->file('banner')->store('events/banners', 'public');
        }

        $event->update([
            'title' => $request->title,
            'event_type' => $request->event_type,
            'description' => $request->description,
            'venue' => $request->venue,
            'date' => $request->date,
            'time' => $request->time,
            'banner_path' => $bannerPath ?? '',
            'registration_option' => $request->has_registration_form,
            'has_registration_form' => $request->has_registration_form,
            'pass_fee' => $request->pass_fee ?? 0.00,
            'max_participants' => $request->max_participants,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully.');
    }

    /**
     * Delete Event
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully.');
    }

    /**
     * View Registered Participants
     */
    public function registrations($id)
    {
        $event = Event::findOrFail($id);
        $registrations = EventRegistration::where('event_id', $event->id)
            ->with(['user.memberProfile'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.events.registrations', compact('event', 'registrations'));
    }

    /**
     * Toggle Selection for Event Registration
     */
    public function toggleSelectRegistration($id)
    {
        $registration = EventRegistration::findOrFail($id);
        $registration->update([
            'is_selected' => !$registration->is_selected
        ]);

        $message = $registration->is_selected ? 'Registration selected successfully.' : 'Registration unselected successfully.';
        return redirect()->back()->with('success', $message);
    }

    /**
     * Manage Event Gallery Photos
     */
    public function gallery($id)
    {
        $event = Event::findOrFail($id);
        $photos = Gallery::where('event_id', $event->id)->orderBy('display_order')->paginate(15);
        return view('admin.events.gallery', compact('event', 'photos'));
    }

    /**
     * Upload Event Gallery Photos
     */
    public function uploadGallery(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'images.*' => 'required|image|max:3072',
            'caption' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('events/gallery', 'public');
                Gallery::create([
                    'event_id' => $event->id,
                    'image_path' => $path,
                    'caption' => $request->caption ?? $event->title,
                    'display_order' => Gallery::where('event_id', $event->id)->max('display_order') + 1,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Gallery photos uploaded successfully.');
    }

    /**
     * Delete Event Gallery Photo
     */
    public function deleteGalleryPhoto($id)
    {
        $photo = Gallery::findOrFail($id);
        
        if (Storage::disk('public')->exists($photo->image_path) && !str_starts_with($photo->image_path, 'http')) {
            Storage::disk('public')->delete($photo->image_path);
        }

        $photo->delete();
        return redirect()->back()->with('success', 'Photo removed from gallery.');
    }

    /**
     * Export Events CSV / Excel
     */
    public function exportCsv(Request $request)
    {
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=events_export_" . date('Y-m-d') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $query = Event::withCount('registrations');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        $events = $query->orderBy('date', 'desc')->get();

        $callback = function() use ($events) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, ['ID', 'Event Title', 'Event Type', 'Date', 'Time', 'Venue', 'Pass Fee', 'Registrations Count', 'Status']);

            foreach ($events as $e) {
                fputcsv($file, [
                    $e->id,
                    $e->title,
                    $e->event_type ?? 'normal',
                    $e->date,
                    $e->time,
                    $e->venue,
                    $e->pass_fee ?? 0,
                    $e->registrations_count ?? 0,
                    ucfirst($e->status ?? 'active'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export Event Registrations CSV / Excel
     */
    public function exportRegistrationsCsv($id)
    {
        $event = Event::findOrFail($id);
        $registrations = EventRegistration::where('event_id', $event->id)->with('user')->orderBy('created_at', 'desc')->get();

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=registrations_event_" . $event->id . "_" . date('Y-m-d') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($registrations, $event) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            if ($event->event_type === 'inam_vitaran') {
                fputcsv($file, ['Reg ID', 'Member ID', 'Member Name', 'Student Name', 'Education', 'School/College', 'Total Marks', 'Obtained Marks', 'Percentage', 'Submission Date']);
                foreach ($registrations as $r) {
                    $fd = $r->form_data ?? [];
                    fputcsv($file, [
                        $r->id,
                        $fd['member_id'] ?? ($r->user ? '#'.sprintf('%05d', $r->user->id) : ''),
                        $fd['parent_name'] ?? ($r->user ? $r->user->name : ''),
                        $fd['student_name'] ?? ($r->user ? $r->user->name : ''),
                        $fd['education'] ?? '',
                        $fd['school_college'] ?? '',
                        $fd['total_marks'] ?? '',
                        $fd['received_marks'] ?? '',
                        ($fd['percentage'] ?? '') ? $fd['percentage'].'%' : '',
                        $fd['submission_date'] ?? ($r->created_at ? $r->created_at->format('d-M-Y h:i A') : ''),
                    ]);
                }
            } elseif ($event->event_type === 'yuva_melo') {
                fputcsv($file, [
                    'Reg ID', 'Member Name', 'Surname', 'First Name', 'Gender', 'Birth Date', 'Age', 'Height', 'Weight',
                    'State', 'District', 'Association', 'Address', 'Mobile No.', 'WhatsApp',
                    'Qualification', 'Occupation', 'Occupation Address', 'Monthly Income',
                    'Father Name', 'Grandfather Name', 'Father Age', 'Father Occupation', 'Father Income',
                    'Mother Name', 'Mother Occupation', 'Native Place',
                    'Elder Brother', 'Retired', 'Younger Brother', 'Younger Brother Married',
                    'Elder Sister', 'Elder Sister Married', 'Younger Sister', 'Younger Sister Married',
                    'Maternal Uncle Name', 'Maternal Grandfather Name',
                    'Business', 'House', 'Own House', 'Vehicle', 'Divorce', 'Special Need',
                    'Member Photo URL', 'Aadhaar Photo URL', 'Selfie URL', 'WhatsApp Image URL', 'Submission Date'
                ]);
                foreach ($registrations as $r) {
                    $fd = $r->form_data ?? [];
                    fputcsv($file, [
                        $r->id,
                        $r->user ? $r->user->name : '',
                        $fd['surname'] ?? '',
                        $fd['first_name'] ?? '',
                        $fd['gender'] ?? '',
                        $fd['birth_date'] ?? '',
                        $fd['age'] ?? '',
                        $fd['height'] ?? '',
                        $fd['weight'] ?? '',
                        $fd['state'] ?? '',
                        $fd['district'] ?? '',
                        $fd['association'] ?? '',
                        $fd['address'] ?? '',
                        $fd['mobile_no'] ?? '',
                        $fd['whatsapp'] ?? '',
                        $fd['qualification'] ?? '',
                        $fd['occupation'] ?? '',
                        $fd['occupation_address'] ?? '',
                        $fd['monthly_income'] ?? '',
                        $fd['father_name'] ?? '',
                        $fd['grandfather_name'] ?? '',
                        $fd['father_age'] ?? '',
                        $fd['father_occupation'] ?? '',
                        $fd['father_income'] ?? '',
                        $fd['mother_name'] ?? '',
                        $fd['mother_occupation'] ?? '',
                        $fd['native_place'] ?? '',
                        $fd['elder_brother'] ?? '',
                        $fd['retired'] ?? '',
                        $fd['younger_brother'] ?? '',
                        $fd['younger_brother_married'] ?? '',
                        $fd['elder_sister'] ?? '',
                        $fd['elder_sister_married'] ?? '',
                        $fd['younger_sister'] ?? '',
                        $fd['younger_sister_married'] ?? '',
                        $fd['maternal_uncle_name'] ?? '',
                        $fd['maternal_grandfather_name'] ?? '',
                        $fd['business'] ?? '',
                        $fd['house'] ?? '',
                        $fd['own_house'] ?? '',
                        $fd['vehicle'] ?? '',
                        $fd['divorce'] ?? '',
                        $fd['special_need'] ?? '',
                        $fd['member_photo_url'] ?? '',
                        $fd['aadhaar_photo_url'] ?? '',
                        $fd['selfie_url'] ?? '',
                        $fd['whatsapp_image_url'] ?? '',
                        $fd['submission_date'] ?? ($r->created_at ? $r->created_at->format('d-M-Y h:i A') : ''),
                    ]);
                }
            } else {
                fputcsv($file, ['Reg ID', 'Member Name', 'Participant Name', 'Contact Number', 'Remarks', 'Submission Date']);
                foreach ($registrations as $r) {
                    $fd = $r->form_data ?? [];
                    fputcsv($file, [
                        $r->id,
                        $r->user ? $r->user->name : '',
                        $fd['full_name'] ?? ($r->user ? $r->user->name : ''),
                        $fd['contact_number'] ?? '',
                        $fd['remarks'] ?? '',
                        $fd['submission_date'] ?? ($r->created_at ? $r->created_at->format('d-M-Y h:i A') : ''),
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
