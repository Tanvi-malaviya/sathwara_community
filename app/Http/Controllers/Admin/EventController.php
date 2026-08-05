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

        $user = auth()->user();
        if ($user->hasRole('Sub Admin') && !$user->permissions->pluck('name')->contains('events_manage')) {
            $allowedEventIds = $user->permissions->pluck('name')
                ->filter(fn($p) => str_starts_with($p, 'event_'))
                ->map(function($p) {
                    return (int) str_replace(['event_manage_', 'event_view_', 'event_edit_', 'event_create_'], '', $p);
                })->filter()->unique()->toArray();

            $query->whereIn('id', $allowedEventIds);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
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
            'google_map_link' => 'nullable|string',
            'date' => 'required|date',
            'time' => 'required',
            'published_date' => 'nullable|date',
            'registration_end_date' => 'nullable|date',
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
            'google_map_link' => $request->google_map_link,
            'date' => $request->date,
            'time' => $request->time,
            'published_date' => $request->published_date,
            'registration_end_date' => $request->registration_end_date,
            'banner_path' => $bannerPath ?? '',
            'registration_option' => $request->has_registration_form,
            'has_registration_form' => $request->has_registration_form,
            'pass_fee' => $request->pass_fee ?? 0.00,
            'max_participants' => $request->max_participants,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
    }

    private function checkEditPermission($eventId)
    {
        $user = auth()->user();
        if ($user->hasRole('Administrator')) {
            return;
        }
        $userPerms = $user->permissions->pluck('name');
        if ($userPerms->contains('events_manage') || $userPerms->contains('event_manage_' . $eventId) || $userPerms->contains('event_edit_' . $eventId)) {
            return;
        }
        abort(403, 'You do not have permission to edit or modify this event.');
    }

    /**
     * Edit Form
     */
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $this->checkEditPermission($event->id);
        return view('admin.events.edit', compact('event'));
    }

    /**
     * Update Event
     */
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $this->checkEditPermission($event->id);

        $request->validate([
            'title' => 'required|string|max:255',
            'event_type' => 'required|in:normal,inam_vitaran,yuva_melo',
            'description' => 'required|string',
            'venue' => 'required|string|max:255',
            'google_map_link' => 'nullable|string',
            'date' => 'required|date',
            'time' => 'required',
            'published_date' => 'nullable|date',
            'registration_end_date' => 'nullable|date',
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
            'google_map_link' => $request->google_map_link,
            'date' => $request->date,
            'time' => $request->time,
            'published_date' => $request->published_date,
            'registration_end_date' => $request->registration_end_date,
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
        $this->checkEditPermission($event->id);
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
        $this->checkEditPermission($registration->event_id);
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
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=events_export_" . date('Y-m-d') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $query = Event::withCount('registrations');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('venue', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }
        $events = $query->orderBy('date', 'desc')->get();

        $callback = function () use ($events) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, [
                __('messages.csv_id'),
                __('messages.csv_event_title'),
                __('messages.csv_event_type'),
                __('messages.csv_date'),
                __('messages.csv_time'),
                __('messages.csv_venue'),
                __('messages.csv_pass_fee'),
                __('messages.csv_registrations_count'),
                __('messages.csv_status')
            ]);

            foreach ($events as $e) {
                $statusKey = strtolower($e->status ?? 'active');
                fputcsv($file, [
                    $e->id,
                    $e->title,
                    $e->event_type ?? 'normal',
                    $e->date,
                    $e->time,
                    $e->venue,
                    $e->pass_fee ?? 0,
                    $e->registrations_count ?? 0,
                    __('messages.' . $statusKey) != 'messages.' . $statusKey ? __('messages.' . $statusKey) : ucfirst($e->status ?? 'active'),
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
        $registrations = EventRegistration::where('event_id', $event->id)->with('user')->orderBy('created_at', 'asc')->get();

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=registrations_event_" . $event->id . "_" . date('Y-m-d') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($registrations, $event) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            if ($event->event_type === 'inam_vitaran') {
                fputcsv($file, ['Reg ID', 'Member ID', 'Member Name', 'Student Name', 'Education', 'School/College', 'Total Marks', 'Obtained Marks', 'Percentage', 'Marksheet File URL', 'Submission Date', 'Remarks']);
                foreach ($registrations as $index => $r) {
                    $fd = $r->form_data ?? [];
                    fputcsv($file, [
                        $fd['registration_no'] ?? ($index + 1),
                        $fd['member_id'] ?? ($r->user ? '#' . sprintf('%05d', $r->user->id) : ''),
                        $fd['parent_name'] ?? ($r->user ? $r->user->name : ''),
                        $fd['student_name'] ?? ($r->user ? $r->user->name : ''),
                        $fd['education'] ?? '',
                        $fd['school_college'] ?? '',
                        $fd['total_marks'] ?? '',
                        $fd['received_marks'] ?? '',
                        !empty($fd['percentage']) ? (str_contains($fd['percentage'], '%') ? $fd['percentage'] : $fd['percentage'] . '%') : '',
                        $fd['marksheet_url'] ?? '',
                        $fd['submission_date'] ?? ($r->created_at ? $r->created_at->format('d-M-Y h:i A') : ''),
                        $fd['remarks'] ?? '',
                    ]);
                }
            } elseif ($event->event_type === 'yuva_melo') {
                fputcsv($file, [
                    'Id',
                    'Date',
                    'Status',
                    'Application Id',
                    'Name',
                    'Surname',
                    'Gender',
                    'Father Name',
                    'Grandpa Name',
                    'Address',
                    'Mobile Number 1',
                    'Whatsapp Number',
                    'Birth Date',
                    'Age',
                    'Height',
                    'Weight',
                    'Qualification',
                    'Occupation',
                    'Occupation Address',
                    'Monthly Income',
                    'Elder Brothers',
                    'Married Elder Brothers',
                    'Younger Brothers',
                    'Married Younger Brothers',
                    'Elder Sisters',
                    'Married Elder Sisters',
                    'Younger Sisters',
                    'Married Younger Sisters',
                    'Father Occupation',
                    'Father Occupation Address',
                    'Father Mobile',
                    'Father Age',
                    'Father Income',
                    'Native Place',
                    'Mother Name',
                    'Mother Occupation',
                    'Maternal Uncle Name',
                    'Maternal Grandfather Name',
                    'Maternal Grandfather Address',
                    'Maternal Grandfather Occupation',
                    'Business',
                    'House',
                    'Own House',
                    'Vehicle',
                    'Divorce',
                    'Special Need',
                    'Physical Disability',
                    'Disability Duration',
                    'Other Info',
                    'Special Info',
                    'Member Number',
                    'Payment Number',
                    'Photo URL',
                    'Aadhaar Photo URL',
                    'Selfie URL',
                    'WhatsApp Image URL',
                    'Payment Image URL'
                ]);
                foreach ($registrations as $index => $r) {
                    $fd = $r->form_data ?? [];
                    fputcsv($file, [
                        $fd['registration_no'] ?? ($index + 1),
                        $fd['submission_date'] ?? ($r->created_at ? $r->created_at->format('d-M-Y h:i A') : ''),
                        ucfirst($r->status ?? 'approved'),
                        $fd['member_number'] ?? ($r->user ? '#' . sprintf('%05d', $r->user->id) : ''),
                        $fd['first_name'] ?? ($r->user ? $r->user->name : ''),
                        $fd['surname'] ?? '',
                        $fd['gender'] ?? '',
                        $fd['father_name'] ?? '',
                        $fd['grandfather_name'] ?? '',
                        $fd['address'] ?? '',
                        $fd['mobile_no'] ?? '',
                        $fd['whatsapp'] ?? '',
                        $fd['birth_date'] ?? '',
                        $fd['age'] ?? '',
                        $fd['height'] ?? '',
                        $fd['weight'] ?? '',
                        $fd['qualification'] ?? '',
                        $fd['occupation'] ?? '',
                        $fd['occupation_address'] ?? '',
                        $fd['monthly_income'] ?? '',
                        $fd['elder_brother'] ?? '',
                        $fd['elder_brother_married'] ?? '',
                        $fd['younger_brother'] ?? '',
                        $fd['younger_brother_married'] ?? '',
                        $fd['elder_sister'] ?? '',
                        $fd['elder_sister_married'] ?? '',
                        $fd['younger_sister'] ?? '',
                        $fd['younger_sister_married'] ?? '',
                        $fd['father_occupation'] ?? '',
                        $fd['father_occupation_address'] ?? '',
                        $fd['father_mobile'] ?? '',
                        $fd['father_age'] ?? '',
                        $fd['father_income'] ?? '',
                        $fd['native_place'] ?? '',
                        $fd['mother_name'] ?? '',
                        $fd['mother_occupation'] ?? '',
                        $fd['maternal_uncle_name'] ?? '',
                        $fd['maternal_grandfather_name'] ?? '',
                        $fd['maternal_grandfather_address'] ?? '',
                        $fd['maternal_grandfather_occupation'] ?? '',
                        $fd['business'] ?? '',
                        $fd['house'] ?? '',
                        $fd['own_house'] ?? '',
                        $fd['vehicle'] ?? '',
                        $fd['divorce'] ?? '',
                        $fd['special_need'] ?? '',
                        $fd['physical_disability'] ?? '',
                        $fd['disability_duration'] ?? '',
                        $fd['other_info'] ?? '',
                        $fd['special_info'] ?? '',
                        $fd['member_number'] ?? '',
                        $fd['payment_number'] ?? '',
                        $fd['member_photo_url'] ?? '',
                        $fd['aadhaar_photo_url'] ?? '',
                        $fd['selfie_url'] ?? '',
                        $fd['whatsapp_image_url'] ?? '',
                        $fd['payment_image_url'] ?? '',
                    ]);
                }
            } else {
                fputcsv($file, ['Reg ID', 'Member Name', 'Participant Name', 'Contact Number', 'Remarks', 'Submission Date']);
                foreach ($registrations as $index => $r) {
                    $fd = $r->form_data ?? [];
                    fputcsv($file, [
                        $fd['registration_no'] ?? ($index + 1),
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

    /**
     * Approve Event Registration
     */
    public function approveRegistration($id)
    {
        $registration = EventRegistration::findOrFail($id);
        $registration->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Registration approved successfully.');
    }

    /**
     * Reject Event Registration
     */
    public function rejectRegistration($id)
    {
        $registration = EventRegistration::findOrFail($id);
        $registration->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Registration rejected.');
    }
}
