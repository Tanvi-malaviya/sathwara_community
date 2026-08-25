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
use App\Services\EventSequenceService;
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
        $upcomingEvents = Event::published()
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
            'total_events' => Event::published()->count(),
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
        $locale = app()->getLocale();
        $isGu = ($locale === 'gu');

        // Mission Title & Text
        $missionTitle = Setting::get($isGu ? 'about_mission_title_gu' : 'about_mission_title_en') 
            ?: Setting::get($isGu ? 'about_mission_title_en' : 'about_mission_title_gu') 
            ?: __('messages.empowering_people');

        $mission = Setting::get($isGu ? 'about_mission_gu' : 'about_mission_en') 
            ?: Setting::get($isGu ? 'about_mission_en' : 'about_mission_gu') 
            ?: Setting::get('about_mission', 'To bring unity, support, and professional growth to all community members.');

        // Vision Title & Text
        $visionTitle = Setting::get($isGu ? 'about_vision_title_gu' : 'about_vision_title_en') 
            ?: Setting::get($isGu ? 'about_vision_title_en' : 'about_vision_title_gu') 
            ?: __('messages.future_prosperity');

        $vision = Setting::get($isGu ? 'about_vision_gu' : 'about_vision_en') 
            ?: Setting::get($isGu ? 'about_vision_en' : 'about_vision_gu') 
            ?: Setting::get('about_vision', 'An empowered, educated, and well-connected community built on shared trust and values.');

        // Objectives Title & Text
        $objectivesTitle = Setting::get($isGu ? 'about_objectives_title_gu' : 'about_objectives_title_en') 
            ?: Setting::get($isGu ? 'about_objectives_title_en' : 'about_objectives_title_gu') 
            ?: __('messages.strategic_goals');

        $objectives = Setting::get($isGu ? 'about_objectives_gu' : 'about_objectives_en') 
            ?: Setting::get($isGu ? 'about_objectives_en' : 'about_objectives_gu') 
            ?: Setting::get('about_objectives', '1. Build strong integration among members.<br>2. Facilitate academic recognition and career growth.<br>3. Establish business directories to support local commerce.');

        // History Title & Text
        $historyTitle = Setting::get($isGu ? 'about_history_title_gu' : 'about_history_title_en') 
            ?: Setting::get($isGu ? 'about_history_title_en' : 'about_history_title_gu') 
            ?: __('messages.heritage_journey');

        $history = Setting::get($isGu ? 'about_history_gu' : 'about_history_en') 
            ?: Setting::get($isGu ? 'about_history_en' : 'about_history_gu') 
            ?: Setting::get('about_history', 'Formed in 1995, our community has grown from a handful of dedicated families to a vibrant network supporting thousands of members.');

        $committee = CommitteeMember::where('status', true)->orderBy('display_order')->get();
        $timeline = Timeline::orderBy('display_order')->get();

        return view('public.about', compact('committee', 'timeline', 'missionTitle', 'mission', 'visionTitle', 'vision', 'objectivesTitle', 'objectives', 'historyTitle', 'history'));
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
        $query = Event::published();
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
        $event = Event::published()->findOrFail($id);
        $gallery = Gallery::where('event_id', $event->id)->get();
        
        $registration = null;
        if (auth()->check()) {
            $registration = EventRegistration::where('event_id', $event->id)
                ->where('user_id', auth()->id())
                ->where(function($q) use ($event) {
                    if ($event->event_type === 'inam_vitaran') {
                        $q->whereNull('form_data->student_name');
                    } elseif ($event->event_type === 'yuva_melo') {
                        $q->whereNull('form_data->surname')
                          ->whereNull('form_data->qualification');
                    }
                })
                ->latest()
                ->first();
        }

        return view('public.event_details', compact('event', 'gallery', 'registration'));
    }

    /**
     * Public Registration Form Page
     */
    public function showPublicRegistrationForm($id)
    {
        $event = Event::published()->findOrFail($id);

        if ($event->event_type !== 'normal' && !($event->has_registration_form || $event->registration_option)) {
            return redirect()->route('event.details', $event->id)->with('warning', 'Registration form is not enabled for this event.');
        }
        
        if ($event->event_type !== 'yuva_melo' && !auth()->check()) {
            return redirect()->route('login')->with('warning', 'Please login to fill up this form.');
        }

        $user = auth()->user();
        $allUserRegistrations = $user ? $user->eventRegistrations()->where('event_id', $id)->orderBy('created_at', 'desc')->get() : collect();

        // Check if user has registered / purchased a pass for this event
        $hasEventPass = $allUserRegistrations->isNotEmpty();

        // Filter registrations to show in the submitted cards list
        $registrations = $allUserRegistrations->filter(function($r) use ($event) {
            if ($event->event_type === 'inam_vitaran') {
                return !empty($r->form_data['student_name']);
            }
            if ($event->event_type === 'yuva_melo') {
                return !empty($r->form_data['surname']) || !empty($r->form_data['first_name']) || !empty($r->form_data['qualification']);
            }
            return true;
        });

        $registration = $registrations->first();
        $familyMembers = $user ? $user->familyMembers()->orderBy('name')->get() : collect();
        $areas = \App\Models\Area::orderBy('name')->get();

        return view('member.event.register', compact('event', 'registration', 'registrations', 'familyMembers', 'areas', 'hasEventPass'));
    }

    /**
     * Register for an Event
     */
    public function registerEvent(Request $request, $id)
    {
        $event = Event::published()->findOrFail($id);

        if ($event->event_type !== 'yuva_melo') {
            if (!auth()->check()) {
                return redirect()->route('login')->with('warning', 'Please login to register for events.');
            }

            $user = auth()->user();
            if ($user->status !== 'approved') {
                return redirect()->back()->with('error', 'Your account must be approved to register.');
            }
        } else {
            $user = auth()->user();
        }

        if ($event->event_type !== 'normal' && !($event->has_registration_form || $event->registration_option)) {
            return redirect()->back()->with('error', 'Registration is not required for this event.');
        }

        if (!empty($event->registration_end_date) && now()->toDateString() > \Carbon\Carbon::parse($event->registration_end_date)->toDateString()) {
            return redirect()->back()->with('error', 'Registration for this event closed on ' . date('d-M-Y', strtotime($event->registration_end_date)) . '.');
        }

        $isStudentForm = ($event->event_type === 'inam_vitaran' && $request->filled('student_name'));
        $isYuvaMeloCandidateForm = ($event->event_type === 'yuva_melo' && ($request->filled('surname') || $request->filled('qualification') || $request->filled('first_name')));

        // Capture form data depending on event type
        $formData = [];
        if ($isStudentForm) {
            $profile = $user ? $user->memberProfile : null;

            // Handle Marksheet File Upload
            $marksheetUrl = null;
            if ($request->hasFile('marksheet_file')) {
                $file = $request->file('marksheet_file');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('marksheets', $filename, 'public');
                $marksheetUrl = asset('storage/' . $path);
            }

            $formData = [
                'member_id' => $user ? sprintf('#%05d', $user->id) : ($request->input('member_number') ?? ''),
                'parent_name' => $user ? $user->name : '',
                'full_name' => $request->input('full_name', $user ? $user->name : 'Participant'),
                'email' => $user ? $user->email : '',
                'mobile' => $profile->phone ?? '',
                'address' => $profile->address ?? '',
                'area' => $profile->area ?? $profile->city ?? '',
                'student_name' => $request->input('student_name'),
                'education_type' => $request->input('education_type'),
                'education' => $request->input('education', $request->input('standard')),
                'total_marks' => $request->input('total_marks'),
                'received_marks' => $request->input('received_marks'),
                'percentage' => $request->input('percentage'),
                'marksheet_url' => $marksheetUrl,
                'school_college' => $request->input('school_college'),
                'person_count' => max(1, (int)$request->input('person_count', 1)),
                'submission_date' => now()->format('d-M-Y h:i A'),
                'remarks' => $request->input('remarks'),
            ];
        } elseif ($isYuvaMeloCandidateForm) {
            $formData = $request->only([
                'state', 'district', 'area_id', 'association', 'surname', 'first_name', 'gender',
                'father_name', 'grandfather_name', 'father_gyanti', 'address', 'mobile_no', 'whatsapp',
                'birth_date', 'age', 'height', 'weight', 'qualification', 'occupation',
                'occupation_address', 'monthly_income', 'elder_brother', 'elder_brother_married', 'retired',
                'younger_brother', 'younger_brother_married', 'elder_sister', 'elder_sister_married',
                'younger_sister', 'younger_sister_married', 'siblings_json', 'father_occupation', 'father_occupation_address', 'father_mobile', 'father_age',
                'father_income', 'native_place', 'mother_name', 'mother_gyanti', 'mother_occupation',
                'maternal_uncle_name', 'maternal_grandfather_name', 'maternal_grandfather_address', 'maternal_grandfather_occupation',
                'business', 'house', 'own_house', 'vehicle', 'divorce', 'special_need',
                'physical_disability', 'disability_duration', 'special_info', 'other_info',
                'member_number', 'payment_number'
            ]);

            if ($request->filled('area_id')) {
                $areaObj = \App\Models\Area::find($request->area_id);
                if ($areaObj) {
                    $formData['area'] = $areaObj->name;
                }
            }

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
                $formData['full_name'] = $user ? $user->name : 'Participant';
            }
            $formData['person_count'] = max(1, (int)$request->input('person_count', 1));
            $formData['contact_number'] = $formData['mobile_no'] ?? '';
            $formData['submission_date'] = now()->format('d-M-Y h:i A');
        } else {
            // General Event Pass Registration
            $personCount = max(1, (int)$request->input('person_count', 1));
            $formData = [
                'full_name' => $request->input('full_name', $user ? $user->name : 'Participant'),
                'contact_number' => $request->input('contact_number', ($user && $user->memberProfile) ? $user->memberProfile->phone : ''),
                'person_count' => $personCount,
                'remarks' => $request->input('remarks'),
                'submission_date' => now()->format('d-M-Y h:i A'),
            ];
        }

        // Filter out null or empty string fields
        $formData = array_filter($formData, fn($value) => !is_null($value) && $value !== '');

        if (isset($formData['contact_number'])) {
            $formData['contact_number'] = substr(preg_replace('/[^0-9]/', '', $formData['contact_number']), 0, 10);
        }

        $redirectTarget = redirect()->route('event.details', $event->id);

        // Check if matching registration exists for this specific student/participant or user
        $existingRegistration = null;
        if ($request->filled('registration_id')) {
            $existingRegistration = EventRegistration::where('event_id', $event->id)->find($request->input('registration_id'));
        }
        if (!$existingRegistration && $user) {
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
            } elseif ($isYuvaMeloCandidateForm) {
                $existingRegistration = EventRegistration::where('event_id', $event->id)
                    ->where('user_id', $user->id)
                    ->whereNotNull('form_data->surname')
                    ->first();
            } else {
                $existingRegistration = EventRegistration::where('event_id', $event->id)
                    ->where('user_id', $user->id)
                    ->whereNull('form_data->student_name')
                    ->whereNull('form_data->surname')
                    ->first();
            }
        }

        if ($isYuvaMeloCandidateForm) {
            $totalAmount = (float)($event->form_fee ?? 0);
        } elseif ($isStudentForm) {
            $totalAmount = 0;
        } else {
            $passFee = (float)($event->pass_fee ?? 0);
            $personCount = max(1, (int)($formData['person_count'] ?? 1));
            $totalAmount = $passFee * $personCount;
        }

        $paymentId = $request->input('razorpay_payment_id');
        $paymentStatus = (!empty($paymentId) || $totalAmount <= 0) ? 'paid' : 'unpaid';

        if ($existingRegistration) {
            if (!empty($existingRegistration->form_data['registration_no'])) {
                $formData['registration_no'] = $existingRegistration->form_data['registration_no'];
            }

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

            if ($isStudentForm) {
                $existingRegistration->update([
                    'form_data' => array_merge($existingRegistration->form_data ?? [], $formData),
                    'status' => 'approved',
                ]);
                return $redirectTarget->with('success', 'Student registration details updated successfully.');
            }

            if ($isYuvaMeloCandidateForm) {
                $existingRegistration->update([
                    'form_data' => array_merge($existingRegistration->form_data ?? [], $formData),
                    'payment_id' => $paymentId ?: $existingRegistration->payment_id,
                    'payment_status' => ($paymentStatus === 'paid' || $existingRegistration->payment_status === 'paid') ? 'paid' : 'unpaid',
                    'payment_amount' => $totalAmount > 0 ? $totalAmount : $existingRegistration->payment_amount,
                    'status' => 'approved',
                ]);
                return $redirectTarget->with('success', 'Yuva Melo registration details updated successfully.');
            }

            // Accumulate person count and payment amount when buying passes
            $currentPersons = (int)($existingRegistration->form_data['person_count'] ?? 1);
            $newTotalPersons = $currentPersons + $personCount;
            $formData['person_count'] = $newTotalPersons;

            $prevPaidAmount = (float)($existingRegistration->payment_amount ?? 0);
            $newTotalAmount = $prevPaidAmount + $totalAmount;

            $existingRegistration->update([
                'form_data' => array_merge($existingRegistration->form_data ?? [], $formData),
                'payment_id' => $paymentId ?: $existingRegistration->payment_id,
                'payment_status' => ($paymentStatus === 'paid' || $existingRegistration->payment_status === 'paid') ? 'paid' : 'unpaid',
                'payment_amount' => $newTotalAmount > 0 ? $newTotalAmount : $existingRegistration->payment_amount,
            ]);

            // Dispatch Pass Email for All Passes
            $recipientEmail = $formData['email'] ?? ($user ? $user->email : null);
            if (!empty($recipientEmail)) {
                $passes = [];
                for ($i = 1; $i <= $newTotalPersons; $i++) {
                    $passes[] = sprintf('%03d', $i);
                }
                try {
                    \Illuminate\Support\Facades\Mail::to($recipientEmail)->send(new \App\Mail\EventPassPurchasedMail($event, $existingRegistration, $user, $passes, $newTotalPersons));
                } catch (\Throwable $th) {
                    \Illuminate\Support\Facades\Log::error('Event Pass Mail Error: ' . $th->getMessage());
                }
            }

            return $redirectTarget->with('success', 'Pass purchased successfully! Total registered persons: ' . $newTotalPersons . '. Pass details sent to your email.');
        }

        // Check capacity
        if ($event->max_participants) {
            $currentCount = EventRegistration::where('event_id', $event->id)->count();
            if ($currentCount >= $event->max_participants) {
                return $redirectTarget->with('error', 'Sorry, registration limit reached for this event.');
            }
        }

        // Assign event-wise sequential reference number starting from 1
        $passNumber = null;
        $inamNumber = null;
        $yuvaMeloNumber = null;

        if ($isStudentForm) {
            $inamNumber = EventSequenceService::nextInamNumber($event->id);
            $regType = 'inam_vitran';
            $formData['registration_no'] = $inamNumber;
        } elseif ($isYuvaMeloCandidateForm) {
            $yuvaMeloNumber = EventSequenceService::nextYuvaMeloNumber($event->id);
            $regType = 'yuva_melo';
            $formData['registration_no'] = $yuvaMeloNumber;
        } else {
            $passNumber = EventSequenceService::nextPassNumber($event->id);
            $regType = 'pass';
            $formData['registration_no'] = $passNumber;
        }

        $newRegistration = EventRegistration::create([
            'event_id' => $event->id,
            'pass_number' => $passNumber,
            'inam_number' => $inamNumber,
            'yuva_melo_number' => $yuvaMeloNumber,
            'registration_type' => $regType,
            'user_id' => $user ? $user->id : null,
            'status' => 'approved',
            'form_data' => $formData,
            'payment_id' => $paymentId,
            'payment_status' => $paymentStatus,
            'payment_amount' => $totalAmount,
        ]);

        // Dispatch Pass Email for General Pass Registration
        if (!$isStudentForm && !$isYuvaMeloCandidateForm) {
            $recipientEmail = $formData['email'] ?? ($user ? $user->email : null);
            if (!empty($recipientEmail)) {
                $finalPersons = max(1, (int)($formData['person_count'] ?? 1));
                $passes = [];
                for ($i = 1; $i <= $finalPersons; $i++) {
                    $passes[] = sprintf('%03d', $i);
                }
                try {
                    \Illuminate\Support\Facades\Mail::to($recipientEmail)->send(new \App\Mail\EventPassPurchasedMail($event, $newRegistration, $user, $passes, $finalPersons));
                } catch (\Throwable $th) {
                    \Illuminate\Support\Facades\Log::error('Event Pass Mail Error: ' . $th->getMessage());
                }
            }
        }

        return $redirectTarget->with('success', 'Registration submitted successfully! Entry passes have been generated.');
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

        $businesses = $query->orderBy('created_at', 'desc')->paginate(9)->withQueryString();

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

    /**
     * Delete Event Registration by Member before last date
     */
    public function deleteRegistration($id)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('warning', 'Please login to manage registrations.');
        }

        $registration = EventRegistration::where('user_id', auth()->id())->findOrFail($id);
        $event = $registration->event;

        if (!empty($event->registration_end_date) && now()->toDateString() > \Carbon\Carbon::parse($event->registration_end_date)->toDateString()) {
            return redirect()->back()->with('error', 'The last date for registration (' . date('d-M-Y', strtotime($event->registration_end_date)) . ') has passed. You cannot delete this registration.');
        }

        $registration->delete();

        return redirect()->route('event.details', $event->id)->with('success', 'Registration deleted successfully.');
    }
}

