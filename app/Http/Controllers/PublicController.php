<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Slider;
use App\Models\Agenda;
use App\Models\ManagementDesk;
use App\Models\CommitteeMember;
use App\Models\Timeline;
use App\Models\Event;
use App\Models\Update;
use App\Models\Gallery;
use App\Models\BusinessCategory;
use App\Models\Business;
use App\Models\User;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class PublicController extends Controller
{
    /**
     * Set Application Locale
     */
    public function setLocale($lang)
    {
        if (in_array($lang, ['en', 'gu'])) {
            session()->put('locale', $lang);
        }
        return redirect()->back();
    }

    /**
     * Home Page
     */
    public function index()
    {
        $sliders = Slider::where('status', true)->orderBy('display_order')->get();
        $agendas = Agenda::orderBy('display_order')->get();
        $managementDesk = ManagementDesk::where('status', true)->orderBy('display_order')->get();
        $upcomingEvents = Event::where('status', 'published')
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->take(3)
            ->get();
        $latestUpdates = Update::where('status', 'published')
            ->orderBy('publish_date', 'desc')
            ->take(3)
            ->get();
        $featuredBusinesses = Business::active()
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
        $galleryPreview = Gallery::orderBy('display_order')->take(6)->get();
        if ($galleryPreview->isEmpty()) {
            $galleryPreview = Gallery::latest()->take(6)->get();
        }

        // Statistics counts
        $stats = [
            'total_members' => User::role('Member')->where('status', 'approved')->count(),
            'total_businesses' => Business::active()->count(),
            'total_events' => Event::where('status', 'published')->count(),
            'gallery_images' => Gallery::whereNull('event_id')->count(),
        ];

        return view('public.home', compact(
            'sliders',
            'agendas',
            'managementDesk',
            'upcomingEvents',
            'latestUpdates',
            'featuredBusinesses',
            'galleryPreview',
            'stats'
        ));
    }

    /**
     * About Us Page
     */
    public function about()
    {
        $committee = CommitteeMember::where('status', true)->orderBy('display_order')->get();
        $timeline = Timeline::orderBy('display_order')->get();
        
        $mission = Setting::get('about_mission', 'To bring unity, support, and professional growth to all community members.');
        $vision = Setting::get('about_vision', 'An empowered, educated, and well-connected community built on shared trust and values.');
        $history = Setting::get('about_history', 'Formed in 1995, our community has grown from a handful of dedicated families to a vibrant network supporting thousands of members.');
        $objectives = Setting::get('about_objectives', '1. Build strong integration among members.<br>2. Facilitate academic recognition and career growth.<br>3. Establish business directories to support local commerce.');

        return view('public.about', compact('committee', 'timeline', 'mission', 'vision', 'history', 'objectives'));
    }

    /**
     * Management Desk Page
     */
    public function managementDesk()
    {
        $members = ManagementDesk::where('status', true)->orderBy('display_order')->get();
        return view('public.management_desk', compact('members'));
    }

    /**
     * Events Listing
     */
    public function events(Request $request)
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
        $events = $query->orderBy('date', 'desc')->paginate(6)->withQueryString();
        return view('public.events', compact('events'));
    }

    /**
     * Event Details Page
     */
    public function eventDetails($id)
    {
        $event = Event::where('status', 'published')->findOrFail($id);
        $gallery = Gallery::where('event_id', $event->id)->get();
        
        $registration = null;
        if (auth()->check()) {
            $registration = EventRegistration::where('event_id', $event->id)
                ->where('user_id', auth()->id())
                ->first();
        }

        return view('public.event_details', compact('event', 'gallery', 'registration'));
    }

    /**
     * Register for an Event
     */
    public function registerEvent(Request $request, $id)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('warning', 'Please login to register for events.');
        }

        $user = auth()->user();
        if ($user->status !== 'approved') {
            return redirect()->back()->with('error', 'Your account must be approved to register.');
        }

        $event = Event::where('status', 'published')->findOrFail($id);
        if (!($event->has_registration_form ?? $event->registration_option)) {
            return redirect()->back()->with('error', 'Registration is not required for this event.');
        }

        // Capture form data depending on event type
        $formData = [];
        if ($event->event_type === 'inam_vitaran') {
            $profile = $user->memberProfile;

            // Handle Marksheet File Upload
            $marksheetUrl = null;
            if ($request->hasFile('marksheet_file')) {
                $file = $request->file('marksheet_file');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('marksheets', $filename, 'public');
                $marksheetUrl = asset('storage/' . $path);
            }

            $formData = [
                'member_id' => sprintf('#%05d', $user->id),
                'parent_name' => $user->name,
                'email' => $user->email,
                'mobile' => $profile->phone ?? '',
                'address' => $profile->address ?? '',
                'area' => $profile->area ?? $profile->city ?? '',
                'student_name' => $request->input('student_name'),
                'education' => $request->input('education', $request->input('standard')),
                'total_marks' => $request->input('total_marks'),
                'received_marks' => $request->input('received_marks'),
                'percentage' => $request->input('percentage'),
                'marksheet_url' => $marksheetUrl,
                'school_college' => $request->input('school_college'),
                'submission_date' => now()->format('d-M-Y h:i A'),
                'remarks' => $request->input('remarks'),
            ];
        } elseif ($event->event_type === 'yuva_melo') {
            $formData = $request->only([
                'state', 'district', 'association', 'surname', 'first_name', 'gender',
                'father_name', 'grandfather_name', 'address', 'mobile_no', 'whatsapp',
                'birth_date', 'age', 'height', 'weight', 'qualification', 'occupation',
                'occupation_address', 'monthly_income', 'elder_brother', 'elder_brother_married', 'retired',
                'younger_brother', 'younger_brother_married', 'elder_sister', 'elder_sister_married',
                'younger_sister', 'younger_sister_married', 'father_occupation', 'father_occupation_address', 'father_mobile', 'father_age',
                'father_income', 'native_place', 'mother_name', 'mother_occupation',
                'maternal_uncle_name', 'maternal_grandfather_name', 'maternal_grandfather_address', 'maternal_grandfather_occupation',
                'business', 'house', 'own_house', 'vehicle', 'divorce', 'special_need',
                'physical_disability', 'disability_duration', 'special_info', 'other_info',
                'member_number', 'payment_number'
            ]);

            // Handle Yuva Melo file uploads
            $fileFields = ['member_photo', 'aadhaar_photo', 'selfie', 'whatsapp_image', 'payment_image'];
            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('yuva_melo/' . $field, $filename, 'public');
                    $formData[$field . '_url'] = asset('storage/' . $path);
                }
            }

            $formData['full_name'] = trim(($formData['first_name'] ?? '') . ' ' . ($formData['surname'] ?? ''));
            if (empty($formData['full_name'])) {
                $formData['full_name'] = auth()->user()->name;
            }
            $formData['contact_number'] = $formData['mobile_no'] ?? '';
            $formData['submission_date'] = now()->format('d-M-Y h:i A');
        } else {
            $formData = $request->only(['remarks', 'full_name', 'contact_number']);
            $formData['submission_date'] = now()->format('d-M-Y h:i A');
        }

        // Filter out null or empty string fields
        $formData = array_filter($formData, fn($value) => !is_null($value) && $value !== '');

        if (isset($formData['contact_number'])) {
            $formData['contact_number'] = substr(preg_replace('/[^0-9]/', '', $formData['contact_number']), 0, 10);
        }

        $redirectTarget = (request()->routeIs('member.*') || str_contains(url()->previous(), '/member/events')) 
            ? redirect()->route('member.events.register_form', $event->id) 
            : redirect()->back();

        // Check if matching registration exists for this specific student/participant
        $existingRegistration = null;
        if (!empty($formData['student_name'])) {
            $registrations = EventRegistration::where('event_id', $event->id)
                ->where('user_id', $user->id)
                ->get();
            foreach ($registrations as $r) {
                if (isset($r->form_data['student_name']) && trim(mb_strtolower($r->form_data['student_name'])) === trim(mb_strtolower($formData['student_name']))) {
                    $existingRegistration = $r;
                    break;
                }
            }
        } elseif (!empty($formData['full_name'])) {
            $registrations = EventRegistration::where('event_id', $event->id)
                ->where('user_id', $user->id)
                ->get();
            foreach ($registrations as $r) {
                if (isset($r->form_data['full_name']) && trim(mb_strtolower($r->form_data['full_name'])) === trim(mb_strtolower($formData['full_name']))) {
                    $existingRegistration = $r;
                    break;
                }
            }
        }

        if ($existingRegistration) {
            // Preserve old marksheet_url if a new file wasn't uploaded during update
            if ($event->event_type === 'inam_vitaran' && empty($formData['marksheet_url'])) {
                if (!empty($existingRegistration->form_data['marksheet_url'])) {
                    $formData['marksheet_url'] = $existingRegistration->form_data['marksheet_url'];
                }
            }

            // Preserve old files for Yuva Melo if new ones weren't uploaded
            if ($event->event_type === 'yuva_melo') {
                foreach (['member_photo_url', 'aadhaar_photo_url', 'selfie_url', 'whatsapp_image_url', 'payment_image_url'] as $fileUrlField) {
                    if (empty($formData[$fileUrlField]) && !empty($existingRegistration->form_data[$fileUrlField])) {
                        $formData[$fileUrlField] = $existingRegistration->form_data[$fileUrlField];
                    }
                }
            }

            $existingRegistration->update([
                'form_data' => array_merge($existingRegistration->form_data ?? [], $formData),
            ]);
            return $redirectTarget->with('success', 'Registration updated successfully.');
        }

        // Check capacity
        if ($event->max_participants) {
            $currentCount = EventRegistration::where('event_id', $event->id)->count();
            if ($currentCount >= $event->max_participants) {
                return $redirectTarget->with('error', 'Sorry, registration limit reached for this event.');
            }
        }

        EventRegistration::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => 'approved',
            'form_data' => $formData,
        ]);

        return $redirectTarget->with('success', 'Registration submitted successfully.');
    }

    /**
     * Updates/Announcements Listing
     */
    public function updates(Request $request)
    {
        $query = Update::where('status', 'published');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        $updates = $query->orderBy('publish_date', 'desc')->paginate(6)->withQueryString();
        return view('public.updates', compact('updates'));
    }

    /**
     * Update Details Page
     */
    public function updateDetails($id)
    {
        $update = Update::where('status', 'published')->findOrFail($id);
        return view('public.update_details', compact('update'));
    }

    public function gallery(Request $request)
    {
        $generalQuery = Gallery::whereNull('event_id');
        $eventQuery = Event::whereHas('galleries')->with('galleries');

        if ($request->filled('search')) {
            $search = $request->search;
            $generalQuery->where('caption', 'like', "%{$search}%");
            $eventQuery->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%");
            });
        }

        $generalImages = $generalQuery->orderBy('display_order')
            ->paginate(15, ['*'], 'general_page')
            ->withQueryString();

        $eventsWithGallery = $eventQuery->orderBy('date', 'desc')
            ->paginate(10, ['*'], 'events_page')
            ->withQueryString();

        return view('public.gallery', compact('generalImages', 'eventsWithGallery'));
    }

    /**
     * Business Directory
     */
    public function businessDirectory(Request $request)
    {
        $categories = BusinessCategory::withCount(['businesses' => function ($query) {
            $query->where('status', 'approved');
        }])->get();

        $query = Business::where('status', 'approved');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('business_name', 'like', '%' . $request->search . '%')
                  ->orWhere('owner_name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $category = BusinessCategory::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        $businesses = $query->orderBy('created_at', 'desc')->paginate(9);

        return view('public.business_directory', compact('businesses', 'categories'));
    }

    /**
     * Business Details Page
     */
    public function businessDetails($id)
    {
        $business = Business::where('status', 'approved')->findOrFail($id);
        return view('public.business_details', compact('business'));
    }

    /**
     * Contact Us Page
     */
    public function contact()
    {
        $settings = [
            'address' => Setting::get('contact_address'),
            'email' => Setting::get('contact_email'),
            'phone' => Setting::get('contact_phone'),
            'map' => Setting::get('contact_map_iframe'),
        ];
        return view('public.contact', compact('settings'));
    }

    /**
     * Submit Contact Form
     */
    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Get the admin/contact email from settings, fallback to env
        $toEmail = Setting::get('contact_email', config('mail.from.address'));

        try {
            \Illuminate\Support\Facades\Mail::to($toEmail)
                ->send(new \App\Mail\ContactInquiryMail(
                    $request->input('name'),
                    $request->input('email'),
                    $request->input('subject'),
                    $request->input('message')
                ));

            return redirect()->back()->with('success', 'તમારો સંદેશ સફળતાપૂર્વક મોકલાઈ ગયો! અમે જલ્દી જ સંપર્ક કરીશું. ✅');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Contact form mail failed: ' . $e->getMessage());
            return redirect()->back()->with('success', 'તમારો સંદેશ સફળતાપૂર્વક મળ્યો! ✅');
        }
    }
}

