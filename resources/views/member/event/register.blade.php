@extends(auth()->check() ? 'layouts.member' : 'layouts.public')

@section('page_title', $event->title . ' Registration')

@section('content')
    @if(!auth()->check())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-4">
    @endif
        @php
            $initialSiblingsArr = [];
            $existingSiblings = old('siblings_json');
            if (empty($existingSiblings) && isset($registration) && !empty($registration->form_data['siblings_json'])) {
                $existingSiblings = is_string($registration->form_data['siblings_json']) ? $registration->form_data['siblings_json'] : json_encode($registration->form_data['siblings_json']);
            }
            if (!empty($existingSiblings)) {
                if (is_array($existingSiblings)) {
                    $initialSiblingsArr = $existingSiblings;
                } else {
                    $decoded = json_decode($existingSiblings, true);
                    if (is_array($decoded)) {
                        $initialSiblingsArr = $decoded;
                    }
                }
            }
            if (empty($initialSiblingsArr) && isset($registration) && !empty($registration->form_data)) {
                $fd = $registration->form_data;
                if (!empty($fd['elder_brother']))
                    $initialSiblingsArr[] = ['relation' => 'Elder Brother', 'details' => $fd['elder_brother'], 'married' => $fd['elder_brother_married'] ?? 'No'];
                if (!empty($fd['younger_brother']))
                    $initialSiblingsArr[] = ['relation' => 'Younger Brother', 'details' => $fd['younger_brother'], 'married' => $fd['younger_brother_married'] ?? 'No'];
                if (!empty($fd['elder_sister']))
                    $initialSiblingsArr[] = ['relation' => 'Elder Sister', 'details' => $fd['elder_sister'], 'married' => $fd['elder_sister_married'] ?? 'No'];
                if (!empty($fd['younger_sister']))
                    $initialSiblingsArr[] = ['relation' => 'Younger Sister', 'details' => $fd['younger_sister'], 'married' => $fd['younger_sister_married'] ?? 'No'];
            }
        @endphp

        <script>
            function eventRegistrationData() {
                return {
                    selectedStudent: @json(old('student_name', '')),
                    educationType: @json(old('education_type', '')),
                    education: @json(old('education', '')),
                    coursesMap: {
                        'Primary': [
                            '1st Standard', '2nd Standard', '3rd Standard', '4th Standard',
                            '5th Standard', '6th Standard', '7th Standard', '8th Standard'
                        ],
                        'Secondary': [
                            '9th Standard', '10th Standard (SSC / GSEB)', '10th Standard (CBSE)', '10th Standard (ICSE)'
                        ],
                        'Higher Secondary': [
                            '11th Science (GSEB)', '11th Commerce (GSEB)', '11th Arts (GSEB)',
                            '12th Science - PCM (GSEB)', '12th Science - PCB (GSEB)', '12th Science - PCMB (GSEB)',
                            '12th Commerce (GSEB)', '12th Arts (GSEB)',
                            '11th Science (CBSE)', '11th Commerce (CBSE)', '11th Arts (CBSE)',
                            '12th Science (CBSE)', '12th Commerce (CBSE)', '12th Arts (CBSE)'
                        ],
                        'College (UG / PG / Professional)': [
                            'BA – Bachelor of Arts',
                            'MA – Master of Arts',
                            'BCom – Bachelor of Commerce',
                            'MCom – Master of Commerce',
                            'BSc – Bachelor of Science',
                            'MSc – Master of Science',
                            'BCA – Bachelor of Computer Applications',
                            'MCA – Master of Computer Applications',
                            'BBA – Bachelor of Business Administration',
                            'MBA – Master of Business Administration',
                            'BBM – Bachelor of Business Management',
                            'BMS – Bachelor of Management Studies',
                            'BAF – Bachelor of Accounting and Finance',
                            'BBI – Bachelor of Banking and Insurance',
                            'BFM – Bachelor of Financial Markets',
                            'BCom (Honours) – Bachelor of Commerce (Honours)',
                            'BE – Bachelor of Engineering',
                            'ME – Master of Engineering',
                            'BTech – Bachelor of Technology',
                            'MTech – Master of Technology',
                            'MBBS – Bachelor of Medicine, Bachelor of Surgery',
                            'BDS – Bachelor of Dental Surgery',
                            'BAMS – Bachelor of Ayurvedic Medicine and Surgery',
                            'BHMS – Bachelor of Homeopathic Medicine and Surgery',
                            'BUMS – Bachelor of Unani Medicine and Surgery',
                            'BNYS – Bachelor of Naturopathy and Yogic Sciences',
                            'BPT – Bachelor of Physiotherapy',
                            'MPT – Master of Physiotherapy',
                            'BOT – Bachelor of Occupational Therapy',
                            'BSc Nursing – Bachelor of Science in Nursing',
                            'GNM – General Nursing and Midwifery',
                            'ANM – Auxiliary Nurse Midwifery',
                            'BPharm – Bachelor of Pharmacy',
                            'MPharm – Master of Pharmacy',
                            'Pharm.D – Doctor of Pharmacy',
                            'DPharm – Diploma in Pharmacy',
                            'BMLT – Bachelor of Medical Laboratory Technology',
                            'MMLT – Master of Medical Laboratory Technology',
                            'BSc Biotechnology – Bachelor of Science in Biotechnology',
                            'BSc Microbiology – Bachelor of Science in Microbiology',
                            'BSc Biochemistry – Bachelor of Science in Biochemistry',
                            'BSc Genetics – Bachelor of Science in Genetics',
                            'BSc Botany – Bachelor of Science in Botany',
                            'BSc Zoology – Bachelor of Science in Zoology',
                            'BSc Chemistry – Bachelor of Science in Chemistry',
                            'BSc Physics – Bachelor of Science in Physics',
                            'BSc Mathematics – Bachelor of Science in Mathematics',
                            'BSc Statistics – Bachelor of Science in Statistics',
                            'BSc Computer Science – Bachelor of Science in Computer Science',
                            'BSc Information Technology – Bachelor of Science in Information Technology',
                            'BSc Data Science – Bachelor of Science in Data Science',
                            'BSc Artificial Intelligence – Bachelor of Science in Artificial Intelligence',
                            'BSc Cyber Security – Bachelor of Science in Cyber Security',
                            'BSc Agriculture – Bachelor of Science in Agriculture',
                            'BSc Forestry – Bachelor of Science in Forestry',
                            'BSc Horticulture – Bachelor of Science in Horticulture',
                            'BSc Fisheries – Bachelor of Science in Fisheries',
                            'BVSc & AH – Bachelor of Veterinary Science and Animal Husbandry',
                            'BSW – Bachelor of Social Work',
                            'MSW – Master of Social Work',
                            'BJMC – Bachelor of Journalism and Mass Communication',
                            'MJMC – Master of Journalism and Mass Communication',
                            'BFA – Bachelor of Fine Arts',
                            'MFA – Master of Fine Arts',
                            'BPA – Bachelor of Performing Arts',
                            'MPA – Master of Performing Arts',
                            'BEd – Bachelor of Education',
                            'MEd – Master of Education',
                            'LLB – Bachelor of Laws',
                            'LLM – Master of Laws',
                            'BA LLB – Bachelor of Arts and Bachelor of Laws (Integrated)',
                            'BLib – Bachelor of Library Science',
                            'MLib – Master of Library Science',
                            'BDes – Bachelor of Design',
                            'MDes – Master of Design',
                            'BFD – Bachelor of Fashion Design',
                            'BID – Bachelor of Interior Design',
                            'BArch – Bachelor of Architecture',
                            'BHM – Bachelor of Hotel Management',
                            'BTTM – Bachelor of Tourism and Travel Management',
                            'CA – Chartered Accountancy',
                            'CS – Company Secretary',
                            'CMA – Cost and Management Accountant',
                            'CFA – Chartered Financial Analyst',
                            'Other'
                        ],
                        'Diploma / Polytechnic': [
                            'Diploma in Computer Engineering',
                            'Diploma in Information Technology',
                            'Diploma in Civil Engineering',
                            'Diploma in Mechanical Engineering',
                            'Diploma in Electrical Engineering',
                            'Diploma in Electronics & Communication Engineering',
                            'Diploma in Electronics Engineering',
                            'Diploma in Automobile Engineering',
                            'Diploma in Chemical Engineering',
                            'Diploma in Textile Engineering',
                            'Diploma in Plastic Engineering',
                            'Diploma in Production Engineering',
                            'Diploma in Metallurgy Engineering',
                            'Diploma in Mechatronics Engineering',
                            'Diploma in Instrumentation & Control Engineering',
                            'Diploma in Power Electronics Engineering',
                            'Diploma in Mining Engineering',
                            'Diploma in Environmental Engineering',
                            'Diploma in Architecture',
                            'Diploma in Interior Design',
                            'Diploma in Fashion Design',
                            'Diploma in Graphic Design',
                            'Diploma in Printing Technology',
                            'Diploma in Ceramic Technology',
                            'Diploma in Marine Engineering',
                            'Diploma in Aeronautical Engineering',
                            'Diploma in Petroleum Engineering',
                            'Diploma in Robotics & Automation',
                            'Diploma in Artificial Intelligence',
                            'Diploma in Machine Learning',
                            'Diploma in Data Science',
                            'Diploma in Cyber Security',
                            'Diploma in Renewable Energy',
                            'Diploma in Fire & Safety Engineering',
                            'Diploma in Hotel Management',
                            'Diploma in Travel & Tourism',
                            'Diploma in Pharmacy (D.Pharm)',
                            'Diploma in Medical Laboratory Technology (DMLT)',
                            'Diploma in Radiology',
                            'Diploma in Operation Theatre Technology',
                            'Diploma in Optometry',
                            'Diploma in Nursing',
                            'Diploma in Physiotherapy',
                            'Other'
                        ],
                        'ITI Trades': [
                            'COPA (Computer Operator & Programming Assistant)',
                            'ICTSM (Information Communication Technology System Maintenance)',
                            'Electrician',
                            'Wireman',
                            'Fitter',
                            'Turner',
                            'Machinist',
                            'Welder',
                            'Plumber',
                            'Carpenter',
                            'Mason (Building Constructor)',
                            'Painter (General)',
                            'Draughtsman Civil',
                            'Draughtsman Mechanical',
                            'Surveyor',
                            'Mechanic Motor Vehicle (MMV)',
                            'Diesel Mechanic',
                            'Tractor Mechanic',
                            'Mechanic Auto Electrical & Electronics',
                            'Mechanic Machine Tool Maintenance',
                            'Mechanic Industrial Electronics',
                            'Mechanic Computer Hardware',
                            'Electronics Mechanic',
                            'Instrument Mechanic',
                            'Refrigeration & Air Conditioning Technician',
                            'Tool & Die Maker',
                            'Foundryman',
                            'Sheet Metal Worker',
                            'Pump Operator cum Mechanic',
                            'Sewing Technology',
                            'Dress Making',
                            'Fashion Design Technology',
                            'Cutting & Sewing',
                            'Food Production',
                            'Baker & Confectioner',
                            'Front Office Assistant',
                            'Housekeeper',
                            'Health Sanitary Inspector',
                            'Dental Laboratory Technician',
                            'Fruit & Vegetable Processor',
                            'Agri Machinery Mechanic',
                            'Solar Technician',
                            'Lift & Escalator Mechanic',
                            'Stenographer (English)',
                            'Stenographer (Hindi)',
                            'Secretarial Practice',
                            'Library & Information Science Assistant',
                            'Desktop Publishing Operator (DTP)',
                            'Data Entry Operator',
                            'Cosmetology',
                            'Spa Therapy',
                            'Hair & Skin Care',
                            'Other'
                        ],
                        'Other': [
                            'PhD (Doctor of Philosophy)', 'D.Sc. (Doctor of Science)',
                            'D.Litt (Doctor of Letters)', 'Post Doctoral Research',
                            'Professional Certificate Course', 'Other'
                        ]
                    },
                    get coursesList() {
                        return this.educationType && this.coursesMap[this.educationType] ? this.coursesMap[this.educationType] : [];
                    },
                    courseDropdownOpen: false,
                    courseSearch: '',
                    get filteredCoursesList() {
                        const list = this.coursesList;
                        if (!this.courseSearch.trim()) return list;
                        const q = this.courseSearch.toLowerCase();
                        return list.filter(c => c.toLowerCase().includes(q));
                    },
                    schoolCollege: @json(old('school_college', '')),
                    totalMarks: @json(old('total_marks', '')),
                    receivedMarks: @json(old('received_marks', '')),
                    percentage: @json(old('percentage', '')),
                    remarks: @json(old('remarks', '')),
                    isEditing: false,
                    marksheetUrl: '',
                    yuvaTab: 1,
                    showDetailsModal: false,
                    showSiblingModal: false,
                    siblings: @json($initialSiblingsArr),
                    legacyElderB: '',
                    legacyElderBM: '',
                    legacyYoungerB: '',
                    legacyYoungerBM: '',
                    legacyElderS: '',
                    legacyElderSM: '',
                    legacyYoungerS: '',
                    legacyYoungerSM: '',
                    newSibling: {
                        relation: 'Elder Brother',
                        details: '',
                        married: 'No',
                        occupation: ''
                    },
                    init() {
                        this.syncSiblingFields();
                        this.calcPercentage();
                    },
                    editRegistration(reg) {
                        let fd = reg.form_data || {};
                        this.selectedStudent = fd.student_name || '';
                        this.educationType = fd.education_type || '';
                        this.education = fd.education || '';
                        this.schoolCollege = fd.school_college || '';
                        this.totalMarks = fd.total_marks || '';
                        this.receivedMarks = fd.received_marks || '';
                        this.percentage = fd.percentage || '';
                        this.remarks = fd.remarks || '';
                        this.marksheetUrl = fd.marksheet_url || '';
                        this.isEditing = true;
                        this.calcPercentage();
                        const elem = document.getElementById('registrationFormCard');
                        if (elem) {
                            elem.scrollIntoView({ behavior: 'smooth' });
                        }
                    },
                    cancelEdit() {
                        this.isEditing = false;
                        this.selectedStudent = '';
                        this.educationType = '';
                        this.education = '';
                        this.schoolCollege = '';
                        this.totalMarks = '';
                        this.receivedMarks = '';
                        this.percentage = '';
                        this.remarks = '';
                        this.marksheetUrl = '';
                    },
                    addSibling() {
                        if (!this.newSibling.relation) return;
                        this.siblings.push({ ...this.newSibling });
                        this.newSibling = { relation: 'Elder Brother', details: '', married: 'No', occupation: '' };
                        this.showSiblingModal = false;
                        this.syncSiblingFields();
                    },
                    removeSibling(index) {
                        this.siblings.splice(index, 1);
                        this.syncSiblingFields();
                    },
                    syncSiblingFields() {
                        this.legacyElderB = this.siblings.filter(s => s.relation === 'Elder Brother').map(s => s.details || '1').join(', ');
                        this.legacyElderBM = this.siblings.some(s => s.relation === 'Elder Brother' && s.married === 'Yes') ? 'Yes' : (this.siblings.some(s => s.relation === 'Elder Brother') ? 'No' : '');

                        this.legacyYoungerB = this.siblings.filter(s => s.relation === 'Younger Brother').map(s => s.details || '1').join(', ');
                        this.legacyYoungerBM = this.siblings.some(s => s.relation === 'Younger Brother' && s.married === 'Yes') ? 'Yes' : (this.siblings.some(s => s.relation === 'Younger Brother') ? 'No' : '');

                        this.legacyElderS = this.siblings.filter(s => s.relation === 'Elder Sister').map(s => s.details || '1').join(', ');
                        this.legacyElderSM = this.siblings.some(s => s.relation === 'Elder Sister' && s.married === 'Yes') ? 'Yes' : (this.siblings.some(s => s.relation === 'Elder Sister') ? 'No' : '');

                        this.legacyYoungerS = this.siblings.filter(s => s.relation === 'Younger Sister').map(s => s.details || '1').join(', ');
                        this.legacyYoungerSM = this.siblings.some(s => s.relation === 'Younger Sister' && s.married === 'Yes') ? 'Yes' : (this.siblings.some(s => s.relation === 'Younger Sister') ? 'No' : '');
                    },
                    selectedRegistration: {},
                    calcPercentage() {
                        let t = parseFloat(this.totalMarks);
                        let r = parseFloat(this.receivedMarks);
                        if (!isNaN(t) && !isNaN(r) && t > 0) {
                            let pct = (r / t) * 100;
                            this.percentage = pct.toFixed(2) + '%';
                        } else {
                            this.percentage = '';
                        }
                    }
                };
            }
        </script>

        <script type="module" src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs"></script>

        <div class="space-y-3 w-full" x-data="eventRegistrationData()">

            <!-- Top Navigation -->
            @if(auth()->check())
                <div class="flex items-center justify-between">
                    <a href="{{ route('member.events.index') }}"
                        class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-slate-900 bg-white px-3.5 py-2 rounded-xl border border-slate-200 shadow-2xs transition-all hover:shadow-xs">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        <span>Back to Events</span>
                    </a>
                </div>
            @endif

            @php
                $isRegistrationClosed = !empty($event->registration_end_date) && now()->toDateString() > \Carbon\Carbon::parse($event->registration_end_date)->toDateString();
            @endphp

            <!-- DEFAULT / CLOSED NOTICE BANNER -->
            @if($isRegistrationClosed)
                <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-800 flex items-center gap-2.5 font-bold shadow-2xs">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Notice: The last date to fill up this form ({{ date('d-M-Y', strtotime($event->registration_end_date)) }}) has passed. Registration is now closed.</span>
                </div>
            @else
                <div class="p-3.5 sm:p-4 bg-amber-50/90 border border-amber-200/90 rounded-2xl text-xs text-amber-900 flex items-start gap-3 shadow-2xs">
                    <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="space-y-1 flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2 flex-wrap">
                            <span class="font-extrabold text-amber-900 text-xs">Notice / Instructions:</span>
                            @if(!empty($event->registration_end_date))
                                <span class="text-[10px] font-extrabold text-amber-900 bg-amber-100/90 px-2.5 py-0.5 rounded-lg border border-amber-200/80">
                                    Last Date: ⏳ {{ date('d-M-Y', strtotime($event->registration_end_date)) }}
                                </span>
                            @endif
                        </div>
                        <div class="text-[11px] font-medium text-amber-800 leading-relaxed">
                            @if(!empty($event->description))
                                {!! $event->description !!}
                            @else
                                Please select student/candidate details and fill in all mandatory fields carefully before submitting.
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if($isRegistrationClosed)
                <!-- REGISTRATION CLOSED CARD -->
                <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 text-center shadow-2xs space-y-4 flex flex-col items-center justify-center relative overflow-hidden">
                    <!-- Ambient background glow -->
                    <div class="absolute -top-20 -right-20 w-48 h-48 bg-rose-50 rounded-full blur-2xl opacity-70 pointer-events-none"></div>
                    <div class="absolute -bottom-20 -left-20 w-48 h-48 bg-amber-50 rounded-full blur-2xl opacity-70 pointer-events-none"></div>

                    <!-- Vector Graphic Badge -->
                    <div class="relative flex items-center justify-center z-10 py-1">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-3xl bg-rose-50 border border-rose-200/80 flex items-center justify-center text-rose-600 shadow-2xs relative group">
                            <div class="absolute -inset-1 rounded-3xl bg-rose-500/10 animate-ping opacity-75"></div>
                            
                            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-rose-500 relative z-10 transform group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="max-w-md space-y-1.5 z-10">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-100/90 border border-rose-200/90 text-rose-800 font-extrabold text-[10px] uppercase tracking-wider">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-600 animate-pulse"></span>
                            <span>Registration Closed</span>
                        </div>

                        <h3 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">Form Fill-up Is Closed</h3>

                        <p class="text-xs text-slate-500 font-medium leading-relaxed">
                            The deadline for submitting registrations for <strong class="text-slate-800 font-bold">{{ $event->title }}</strong> passed on 
                            <span class="font-bold text-rose-700 bg-rose-50 px-2 py-0.5 rounded border border-rose-200/80 inline-block">{{ date('d-M-Y', strtotime($event->registration_end_date)) }}</span>. Form fill-up is disabled for this event.
                        </p>
                    </div>

                    @if(auth()->check())
                        <div class="pt-1 z-10">
                            <a href="{{ route('member.events.index') }}" 
                                class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-2xs hover:shadow transition-all inline-flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                <span>Back to Events List</span>
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <!-- MAIN FORM CARD CONTAINER -->
                <div id="registrationFormCard"
                    class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden w-full transition-all">
                <div class="p-4 sm:p-6 space-y-4">

                    <!-- Edit Mode Banner -->
                    <div x-show="isEditing"
                        class="bg-amber-50 border border-amber-200 p-3 rounded-xl flex items-center justify-between gap-3 text-xs"
                        x-cloak>
                        <div class="flex items-center gap-2">
                            <span class="text-amber-600 font-extrabold">✏️ Editing Registration for:</span>
                            <span class="font-black text-amber-900" x-text="selectedStudent"></span>
                        </div>
                        <button type="button" @click="cancelEdit()"
                            class="px-3 py-1 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-lg border border-slate-200 shadow-2xs">
                            ✕ Cancel Edit
                        </button>
                    </div>

                    <!-- Registration Form -->
                    <form method="POST" action="{{ route('events.public_register', $event->id) }}"
                        enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        @if(($event->event_type ?? 'normal') === 'inam_vitaran')
                            <!-- Inam Vitaran Academic & Marksheet Form Fields -->
                            <div class="space-y-4 text-xs">
                                @php
                                    $familyMembers = $familyMembers ?? (auth()->check() ? auth()->user()->familyMembers : collect());
                                @endphp
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 flex items-center justify-between">
                                        <span>Student / Candidate Full Name <span class="text-rose-500">*</span></span>
                                        <span class="text-[10px] font-normal text-slate-400">(Select student from family
                                            members)</span>
                                    </label>
                                    @if($familyMembers->count() > 0)
                                        <select name="student_name" x-model="selectedStudent" required
                                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:bg-white focus:border-primary-500 transition-all outline-none">
                                            <option value="">-- Select Student / Child --</option>
                                            @foreach($familyMembers as $fm)
                                                <option value="{{ $fm->name }}">{{ $fm->name }} ({{ $fm->relationship }})</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <div
                                            class="p-3.5 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800 space-y-2">
                                            <p class="font-bold">No family members found on your profile.</p>
                                            <p class="text-[11px]">Please add your children / student under Family Members first to
                                                register for Inam Vitaran.</p>
                                            <a href="{{ route('member.family.index') }}"
                                                class="inline-block px-3.5 py-1.5 bg-amber-600 text-white font-bold rounded-lg text-[11px]">
                                                + Add Family Member
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                <template x-if="selectedStudent !== ''">
                                    <div class="space-y-4 pt-1">
                                        <!-- STEP 1: Education Type Dropdown -->
                                        <div class="space-y-1">
                                            <label class="text-[11px] font-bold text-slate-700 flex items-center gap-1">
                                                Education Type <span class="text-rose-500">*</span>
                                            </label>
                                            <select name="education_type" x-model="educationType" required
                                                @change="education = ''"
                                                class="w-full px-3.5 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all outline-none">
                                                <option value="">-- Select Education Type --</option>
                                                <option value="Primary">Primary (1st to 8th Standard)</option>
                                                <option value="Secondary">Secondary (9th - 10th Standard)</option>
                                                <option value="Higher Secondary">Higher Secondary (11th - 12th)</option>
                                                <option value="College (UG / PG / Professional)">College (UG / PG / Professional Degree)</option>
                                                <option value="Diploma / Polytechnic">Diploma / Polytechnic</option>
                                                <option value="ITI Trades">ITI Trades</option>
                                                <option value="Other">Other (PhD / D.Sc. / Research)</option>
                                            </select>
                                        </div>

                                        <!-- STEP 2: Custom Searchable Course Dropdown -->
                                        <div class="space-y-1"
                                            x-show="educationType !== ''"
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 -translate-y-1"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            x-transition:leave="transition ease-in duration-150"
                                            x-transition:leave-start="opacity-100 translate-y-0"
                                            x-transition:leave-end="opacity-0 -translate-y-1"
                                            style="display:none">
                                            <label class="text-[11px] font-bold text-slate-700 flex items-center gap-1">
                                                Standard / Course / Degree <span class="text-rose-500">*</span>
                                            </label>

                                            <!-- Hidden input for form submission -->
                                            <input type="hidden" name="education" :value="education">

                                            <!-- Custom Dropdown Trigger -->
                                            <div class="relative" @click.away="courseDropdownOpen = false">
                                                <button type="button"
                                                    @click="courseDropdownOpen = !courseDropdownOpen; if(courseDropdownOpen) $nextTick(() => $refs.courseSearchInput.focus())"
                                                    class="w-full px-3.5 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-left flex items-center justify-between gap-2 focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all outline-none"
                                                    :class="courseDropdownOpen ? 'border-primary-500 ring-2 ring-primary-100 bg-white' : ''">
                                                    <span :class="education ? 'text-slate-800' : 'text-slate-400'"
                                                        x-text="education || '-- Select Course / Standard --'"></span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 shrink-0 transition-transform duration-200" :class="courseDropdownOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                    </svg>
                                                </button>

                                                <!-- Dropdown Panel -->
                                                <div x-show="courseDropdownOpen"
                                                    x-transition:enter="transition ease-out duration-150"
                                                    x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                                    x-transition:leave="transition ease-in duration-100"
                                                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                                    x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                                                    style="display:none"
                                                    class="absolute z-[999] left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden">

                                                    <!-- Search Box -->
                                                    <div class="p-2 border-b border-slate-100 bg-slate-50">
                                                        <div class="flex items-center gap-2 px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                                                            </svg>
                                                            <input type="text" x-ref="courseSearchInput"
                                                                x-model="courseSearch"
                                                                placeholder="Search course..."
                                                                class="flex-1 text-xs font-medium text-slate-700 bg-transparent outline-none placeholder-slate-400">
                                                            <button type="button" x-show="courseSearch" @click="courseSearch = ''" class="text-slate-300 hover:text-slate-500">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <!-- Course List -->
                                                    <div class="overflow-y-auto" style="max-height: 200px;">
                                                        <template x-if="filteredCoursesList.length === 0">
                                                            <div class="px-4 py-3 text-xs text-slate-400 text-center">No course found</div>
                                                        </template>
                                                        <template x-for="course in filteredCoursesList" :key="course">
                                                            <div @click="education = course; courseDropdownOpen = false; courseSearch = ''"
                                                                class="px-3.5 py-2 text-xs font-medium cursor-pointer transition-colors"
                                                                :class="education === course ? 'bg-primary-50 text-primary-600 font-bold' : 'text-slate-700 hover:bg-slate-50'"
                                                                x-text="course">
                                                            </div>
                                                        </template>
                                                    </div>

                                                    <!-- Footer count -->
                                                    <div class="px-3 py-1.5 border-t border-slate-100 bg-slate-50 text-[10px] text-slate-400 font-medium">
                                                        <span x-text="filteredCoursesList.length + ' course(s)'"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="space-y-1">
                                            <label class="text-[11px] font-bold text-slate-700 flex items-center gap-1">
                                                School / College / Institute Name <span class="text-rose-500">*</span>
                                            </label>
                                            <input type="text" name="school_college" x-model="schoolCollege" required
                                                placeholder="Enter school or university name"
                                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all outline-none">
                                        </div>

                                        <!-- Single Unified Box Container for Total Marks, Obtained Marks, & Percentage -->
                                        <div class="bg-slate-50/90 border border-slate-200/80 rounded-xl p-3.5 space-y-2">
                                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                                <div class="space-y-1">
                                                    <label class="text-[11px] font-bold text-slate-700 flex items-center gap-1">
                                                        Total Marks <span class="text-rose-500">*</span>
                                                    </label>
                                                    <input type="number" step="any" name="total_marks" x-model="totalMarks"
                                                        @input="calcPercentage()" @change="calcPercentage()" required
                                                        placeholder="e.g. 600"
                                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs font-bold focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all outline-none">
                                                </div>

                                                <div class="space-y-1">
                                                    <label class="text-[11px] font-bold text-slate-700 flex items-center gap-1">
                                                        Obtained Marks <span class="text-rose-500">*</span>
                                                    </label>
                                                    <input type="number" step="any" name="received_marks"
                                                        x-model="receivedMarks" @input="calcPercentage()"
                                                        @change="calcPercentage()" required placeholder="e.g. 520"
                                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs font-bold focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all outline-none">
                                                </div>

                                                <div class="space-y-1">
                                                    <label
                                                        class="text-[11px] font-bold text-slate-700 flex items-center justify-between">
                                                        <span>Percentage (%) <span class="text-rose-500">*</span></span>
                                                        <span
                                                            class="text-[9px] text-emerald-600 font-bold bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200/80">Auto
                                                            Calculated</span>
                                                    </label>
                                                    <input type="text" name="percentage" x-model="percentage" readonly
                                                        tabindex="-1" required placeholder="Auto-calculated (e.g. 86.67%)"
                                                        class="w-full px-3 py-2 bg-slate-100/90 border border-slate-200 rounded-lg text-xs font-black text-slate-800 cursor-not-allowed outline-none select-none focus:ring-0">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Marksheet File Upload Field -->
                                        <div class="space-y-1">
                                            <label
                                                class="text-[11px] font-bold text-slate-700 flex items-center justify-between">
                                                <span>Upload Marksheet File (Image / PDF) <span class="text-rose-500"
                                                        x-show="!isEditing || !marksheetUrl">*</span></span>
                                                <span class="text-[10px] text-slate-400 font-medium">Supported: JPG, PNG, PDF
                                                    (Max
                                                    5MB)</span>
                                            </label>

                                            <template x-if="isEditing && marksheetUrl">
                                                <div
                                                    class="text-[11px] font-semibold text-slate-700 bg-amber-50 p-2 rounded-lg border border-amber-200 flex items-center justify-between">
                                                    <span>Current File: <a :href="marksheetUrl" target="_blank"
                                                            class="font-extrabold text-primary-600 hover:underline">View
                                                            Uploaded Marksheet ↗</a></span>
                                                    <span class="text-[10px] text-slate-400 font-normal">(Upload new file below
                                                        to replace)</span>
                                                </div>
                                            </template>

                                            <div class="flex items-center gap-2">
                                                <input type="file" name="marksheet_file" accept="image/*,.pdf"
                                                    :required="!isEditing && !marksheetUrl"
                                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-600 file:mr-3 file:py-1.5 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-primary-500 file:text-white hover:file:bg-primary-600 cursor-pointer">
                                            </div>
                                        </div>

                                        <div class="space-y-1">
                                            <label class="text-[11px] font-bold text-slate-700">Special Achievement / Remarks
                                                (Optional)</label>
                                            <textarea name="remarks" x-model="remarks" rows="2"
                                                placeholder="Mention rank, board awards, sports honors or extra accomplishments"
                                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all outline-none"></textarea>
                                        </div>
                                    </div>
                                </template>
                            </div>

                        @elseif(($event->event_type ?? 'normal') === 'yuva_melo')
                            <!-- Yuva Melo Step Tabs Navigation -->
                            <div class="flex border-b border-slate-200/80 mb-2.5 gap-2 overflow-x-auto pb-1">
                                <button type="button" @click="yuvaTab = 1"
                                    :class="yuvaTab === 1 ? 'border-primary-500 text-primary-600 font-extrabold bg-primary-50/50' : 'border-transparent text-slate-500 hover:text-slate-700'"
                                    class="px-4 py-2 border-b-2 text-xs transition-all whitespace-nowrap rounded-t-lg cursor-pointer">
                                    {{ __('messages.yuva_tab_1') }}
                                </button>
                                <button type="button" @click="yuvaTab = 2"
                                    :class="yuvaTab === 2 ? 'border-primary-500 text-primary-600 font-extrabold bg-primary-50/50' : 'border-transparent text-slate-500 hover:text-slate-700'"
                                    class="px-4 py-2 border-b-2 text-xs transition-all whitespace-nowrap rounded-t-lg cursor-pointer">
                                    {{ __('messages.yuva_tab_2') }}
                                </button>
                                <button type="button" @click="yuvaTab = 3"
                                    :class="yuvaTab === 3 ? 'border-primary-500 text-primary-600 font-extrabold bg-primary-50/50' : 'border-transparent text-slate-500 hover:text-slate-700'"
                                    class="px-4 py-2 border-b-2 text-xs transition-all whitespace-nowrap rounded-t-lg cursor-pointer">
                                    {{ __('messages.yuva_tab_3') }}
                                </button>
                            </div>

                            <!-- STEP 1: Candidate's Info -->
                            <div x-show="yuvaTab === 1" class="space-y-2.5 text-xs">
                                <div
                                    class="bg-slate-50/80 px-3 py-2 rounded-xl border border-slate-100 font-bold text-primary-800 text-[11px]">
                                    {{ __('messages.yuva_sec_1') }}
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-bold text-slate-700">{{ __('messages.surname') }} <span
                                                class="text-rose-500">*</span></label>
                                        <input type="text" name="surname" value="{{ old('surname') }}" required
                                            placeholder="Enter surname"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-bold text-slate-700">{{ __('messages.first_name') }}
                                            <span class="text-rose-500">*</span></label>
                                        <input type="text" name="first_name" value="{{ old('first_name') }}" required
                                            placeholder="Enter first name"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-bold text-slate-700">{{ __('messages.gender') }} <span
                                                class="text-rose-500">*</span></label>
                                        <select name="gender" required
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                            <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select Gender
                                            </option>
                                            <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female
                                            </option>
                                            <option value="Other" {{ old('gender') === 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-4 gap-3.5">
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-bold text-slate-700">{{ __('messages.birth_date') }}
                                            <span class="text-rose-500">*</span></label>
                                        <input type="date" name="birth_date" value="{{ old('birth_date') }}" required
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-bold text-slate-700">{{ __('messages.age') }} <span
                                                class="text-rose-500">*</span></label>
                                        <input type="number" name="age" value="{{ old('age') }}" required placeholder="e.g. 25"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-bold text-slate-700">{{ __('messages.height') }}</label>
                                        <input type="text" name="height" value="{{ old('height') }}"
                                            placeholder="e.g. 5'6\"" class=" w-full px-3 py-2 bg-slate-50 border
                                            border-slate-200 rounded-lg text-xs font-semibold focus:bg-white
                                            focus:border-primary-500 outline-none">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-bold text-slate-700">{{ __('messages.weight') }}</label>
                                        <input type="text" name="weight" value="{{ old('weight') }}" placeholder="e.g. 60 kg"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700">{{ __('messages.address') }} <span
                                            class="text-rose-500">*</span></label>
                                    <textarea name="address" rows="2" required placeholder="Enter full address"
                                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">{{ old('address') }}</textarea>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3.5">
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-bold text-slate-700">{{ __('messages.state') }} <span
                                                class="text-rose-500">*</span></label>
                                        <input type="text" name="state" value="{{ old('state', 'Gujarat') }}" required
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-bold text-slate-700">{{ __('messages.district') }} <span
                                                class="text-rose-500">*</span></label>
                                        <input type="text" name="district" value="{{ old('district') }}" required
                                            placeholder="Enter district"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-bold text-slate-700">Area <span
                                                class="text-rose-500">*</span></label>
                                        <select name="area_id" required
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                            <option value="">-- Select Area --</option>
                                            @if(isset($areas))
                                                @foreach($areas as $areaItem)
                                                    <option value="{{ $areaItem->id }}" {{ old('area_id') == $areaItem->id ? 'selected' : '' }}>
                                                        {{ $areaItem->name }}{{ $areaItem->pincode ? ' (' . $areaItem->pincode . ')' : '' }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="space-y-1">
                                        <label
                                            class="text-[11px] font-bold text-slate-700">{{ __('messages.association') }}</label>
                                        <input type="text" name="association" value="{{ old('association') }}"
                                            placeholder="Enter mandal/association"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-bold text-slate-700">{{ __('messages.mobile') }} <span
                                                class="text-rose-500">*</span></label>
                                        <input type="text" name="mobile_no"
                                            value="{{ old('mobile_no', auth()->user()->memberProfile->phone ?? '') }}" required
                                            maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                                            placeholder="10-digit number"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    <div class="space-y-1">
                                        <label
                                            class="text-[11px] font-bold text-slate-700">{{ __('messages.whatsapp_no') }}</label>
                                        <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" maxlength="10"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                                            placeholder="10-digit whatsapp number"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-bold text-slate-700">{{ __('messages.qualification') }}
                                            <span class="text-rose-500">*</span></label>
                                        <input type="text" name="qualification" value="{{ old('qualification') }}" required
                                            placeholder="e.g. Graduate / B.E."
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-bold text-slate-700">{{ __('messages.occupation') }}
                                            <span class="text-rose-500">*</span></label>
                                        <input type="text" name="occupation" value="{{ old('occupation') }}" required
                                            placeholder="e.g. Job / Business"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    <div class="space-y-1">
                                        <label
                                            class="text-[11px] font-bold text-slate-700">{{ __('messages.monthly_income') }}</label>
                                        <input type="text" name="monthly_income" value="{{ old('monthly_income') }}"
                                            placeholder="e.g. Rs. 25,000"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <label
                                        class="text-[11px] font-bold text-slate-700">{{ __('messages.occupation_address') }}</label>
                                    <input type="text" name="occupation_address" value="{{ old('occupation_address') }}"
                                        placeholder="Enter job/business address"
                                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-bold text-slate-700">{{ __('messages.divorce_status') }}
                                            <span class="text-rose-500">*</span></label>
                                        <select name="divorce" required
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                            <option value="No" {{ old('divorce', 'No') === 'No' ? 'selected' : '' }}>No (ના)
                                            </option>
                                            <option value="Yes" {{ old('divorce') === 'Yes' ? 'selected' : '' }}>Yes (હા)</option>
                                        </select>
                                    </div>
                                    <div class="space-y-1">
                                        <label
                                            class="text-[11px] font-bold text-slate-700">{{ __('messages.special_need') }}</label>
                                        <input type="text" name="special_need" value="{{ old('special_need') }}"
                                            placeholder="e.g. None / Physical Disability details"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                                    <div class="space-y-1">
                                        <label
                                            class="text-[11px] font-bold text-slate-700">{{ __('messages.physical_disability') }}</label>
                                        <input type="text" name="physical_disability" value="{{ old('physical_disability') }}"
                                            placeholder="e.g. None / Details"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    <div class="space-y-1">
                                        <label
                                            class="text-[11px] font-bold text-slate-700">{{ __('messages.disability_duration') }}</label>
                                        <input type="text" name="disability_duration" value="{{ old('disability_duration') }}"
                                            placeholder="e.g. Since birth / N/A"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                                    <div class="space-y-1">
                                        <label
                                            class="text-[11px] font-bold text-slate-700">{{ __('messages.special_info') }}</label>
                                        <input type="text" name="special_info" value="{{ old('special_info') }}"
                                            placeholder="Any special achievement or information"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    <div class="space-y-1">
                                        <label
                                            class="text-[11px] font-bold text-slate-700">{{ __('messages.other_info') }}</label>
                                        <input type="text" name="other_info" value="{{ old('other_info') }}"
                                            placeholder="Additional notes or remarks"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                </div>

                                <!-- Document Upload Grid -->
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                    <div class="bg-slate-50/50 p-2.5 rounded-xl border border-slate-200/60 space-y-1">
                                        <label
                                            class="text-[10px] font-bold text-slate-600 block">{{ __('messages.member_photo') }}</label>
                                        <input type="file" name="member_photo" accept="image/*"
                                            class="w-full text-[10px] text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer">
                                    </div>
                                    <div class="bg-slate-50/50 p-2.5 rounded-xl border border-slate-200/60 space-y-1">
                                        <label
                                            class="text-[10px] font-bold text-slate-600 block">{{ __('messages.aadhaar_photo') }}</label>
                                        <input type="file" name="aadhaar_photo" accept="image/*"
                                            class="w-full text-[10px] text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer">
                                    </div>
                                    <div class="bg-slate-50/50 p-2.5 rounded-xl border border-slate-200/60 space-y-1">
                                        <label
                                            class="text-[10px] font-bold text-slate-600 block">{{ __('messages.selfie') }}</label>
                                        <input type="file" name="selfie" accept="image/*"
                                            class="w-full text-[10px] text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer">
                                    </div>
                                    <div class="bg-slate-50/50 p-2.5 rounded-xl border border-slate-200/60 space-y-1">
                                        <label
                                            class="text-[10px] font-bold text-slate-600 block">{{ __('messages.whatsapp_image') }}</label>
                                        <input type="file" name="whatsapp_image" accept="image/*"
                                            class="w-full text-[10px] text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer">
                                    </div>
                                </div>

                                <!-- Tab 1 Next Button -->
                                <div class="flex justify-end pt-2">
                                    <button type="button" @click="yuvaTab = 2"
                                        class="px-5 py-2 bg-primary-500 text-white hover:bg-primary-600 font-bold text-xs rounded-xl shadow-xs transition-colors cursor-pointer">
                                        {!! __('messages.next_step') !!}
                                    </button>
                                </div>
                            </div>

                            <!-- STEP 2: Father & Family Info -->
                            <div x-show="yuvaTab === 2" class="space-y-2.5 text-xs" x-cloak>
                                <div
                                    class="bg-slate-50/80 px-3 py-2 rounded-xl border border-slate-100 font-bold text-primary-800 text-[11px]">
                                    {{ __('messages.yuva_sec_2') }}
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-bold text-slate-700">{{ __('messages.father_name') }}
                                            <span class="text-rose-500">*</span></label>
                                        <input type="text" name="father_name" value="{{ old('father_name') }}" required
                                            placeholder="Enter father's full name"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    <div class="space-y-1">
                                        <label
                                            class="text-[11px] font-bold text-slate-700">{{ __('messages.grandfather_name') }}
                                            <span class="text-rose-500">*</span></label>
                                        <input type="text" name="grandfather_name" value="{{ old('grandfather_name') }}"
                                            required placeholder="Enter grandfather's full name"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    {{--
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-bold text-slate-700">Father's Gyanti (Gnati)</label>
                                        <input type="text" name="father_gyanti" value="{{ old('father_gyanti') }}"
                                            placeholder="e.g. Sathwara / Patel"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    --}}
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                                    <div class="space-y-1">
                                        <label
                                            class="text-[11px] font-bold text-slate-700">{{ __('messages.father_age') }}</label>
                                        <input type="number" name="father_age" value="{{ old('father_age') }}"
                                            placeholder="e.g. 52"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    <div class="space-y-1">
                                        <label
                                            class="text-[11px] font-bold text-slate-700">{{ __('messages.father_occupation') }}</label>
                                        <input type="text" name="father_occupation" value="{{ old('father_occupation') }}"
                                            placeholder="e.g. Farming / Business"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    <div class="space-y-1">
                                        <label
                                            class="text-[11px] font-bold text-slate-700">{{ __('messages.father_income') }}</label>
                                        <input type="text" name="father_income" value="{{ old('father_income') }}"
                                            placeholder="Annual or Monthly Income"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                                    <div class="space-y-1">
                                        <label
                                            class="text-[11px] font-bold text-slate-700">{{ __('messages.father_occupation_address') }}</label>
                                        <input type="text" name="father_occupation_address"
                                            value="{{ old('father_occupation_address') }}"
                                            placeholder="Enter father's job/business address"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    <div class="space-y-1">
                                        <label
                                            class="text-[11px] font-bold text-slate-700">{{ __('messages.father_mobile') }}</label>
                                        <input type="text" name="father_mobile" value="{{ old('father_mobile') }}"
                                            maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                                            placeholder="10-digit mobile number"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-bold text-slate-700">{{ __('messages.mother_name') }}
                                            <span class="text-rose-500">*</span></label>
                                        <input type="text" name="mother_name" value="{{ old('mother_name') }}" required
                                            placeholder="Enter mother's name"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    {{--
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-bold text-slate-700">Mother's Gyanti (Gnati)</label>
                                        <input type="text" name="mother_gyanti" value="{{ old('mother_gyanti') }}"
                                            placeholder="e.g. Sathwara / Patel"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    --}}
                                    <div class="space-y-1">
                                        <label
                                            class="text-[11px] font-bold text-slate-700">{{ __('messages.mother_occupation') }}</label>
                                        <input type="text" name="mother_occupation"
                                            value="{{ old('mother_occupation', 'Housewife') }}" placeholder="e.g. Housewife"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-bold text-slate-700">{{ __('messages.native_place') }}
                                            <span class="text-rose-500">*</span></label>
                                        <input type="text" name="native_place" value="{{ old('native_place') }}" required
                                            placeholder="Enter native village/city"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                </div>

                                <!-- Brother and Sister Details (Siblings Section with Modal + Cards) -->
                                <div class="bg-slate-50/80 p-3.5 rounded-2xl border border-slate-200/80 space-y-2.5">
                                    <div class="flex items-center justify-between flex-wrap gap-2">
                                        <div>
                                            <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider">
                                                {{ __('messages.siblings_details') }}
                                            </h4>
                                            <p class="text-[10px] text-slate-400 mt-0.5">Add brother(s) and sister(s) details
                                                using
                                                the button</p>
                                        </div>
                                        <button type="button" @click="showSiblingModal = true"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary-600 hover:bg-primary-700 text-white font-bold text-xs rounded-xl shadow-xs transition-all cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            <span>Add Sibling</span>
                                        </button>
                                    </div>

                                    <!-- Sibling Cards List (Compact Small Cards) -->
                                    <div class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-4 gap-2 pt-1"
                                        x-show="siblings.length > 0">
                                        <template x-for="(s, index) in siblings" :key="index">
                                            <div
                                                class="bg-white p-2 rounded-lg border border-slate-200 shadow-2xs flex items-center justify-between min-w-0">
                                                <div class="min-w-0 space-y-0.5">
                                                    <div class="flex items-center gap-1.5 truncate">
                                                        <span
                                                            class="text-[9px] font-black px-1.5 py-0.5 rounded-md text-white tracking-tight shrink-0"
                                                            :class="{
                                                                          'bg-blue-600': s.relation.includes('Brother'),
                                                                          'bg-pink-600': s.relation.includes('Sister')
                                                                      }" x-text="s.relation"></span>
                                                        <span class="text-[11px] font-extrabold text-slate-800 truncate"
                                                            x-text="s.details || '1 Member'"></span>
                                                    </div>
                                                    <div
                                                        class="flex items-center gap-1 text-[10px] text-slate-500 font-medium truncate">
                                                        <span x-text="s.married === 'Yes' ? 'Married' : 'Unmarried'"></span>
                                                        <template x-if="s.occupation">
                                                            <span class="truncate" x-text="'• ' + s.occupation"></span>
                                                        </template>
                                                    </div>
                                                </div>
                                                <button type="button" @click="removeSibling(index)"
                                                    class="p-1 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded transition-colors shrink-0 ml-1"
                                                    title="Remove Sibling">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Empty State -->
                                    <div x-show="siblings.length === 0"
                                        class="p-3 border border-dashed border-slate-200 rounded-xl text-center text-xs text-slate-400 font-semibold bg-white/60">
                                        No siblings added yet. Click <strong class="text-primary-600">"+ Add Sibling"</strong>
                                        to
                                        add details.
                                    </div>

                                    <!-- Hidden Sync Fields -->
                                    <input type="hidden" name="siblings_json" :value="JSON.stringify(siblings)">
                                    <input type="hidden" name="elder_brother" :value="legacyElderB">
                                    <input type="hidden" name="elder_brother_married" :value="legacyElderBM">
                                    <input type="hidden" name="younger_brother" :value="legacyYoungerB">
                                    <input type="hidden" name="younger_brother_married" :value="legacyYoungerBM">
                                    <input type="hidden" name="elder_sister" :value="legacyElderS">
                                    <input type="hidden" name="elder_sister_married" :value="legacyElderSM">
                                    <input type="hidden" name="younger_sister" :value="legacyYoungerS">
                                    <input type="hidden" name="younger_sister_married" :value="legacyYoungerSM">
                                </div>

                                <!-- Family Business, Property, Vehicle Info -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                                    <div class="space-y-1">
                                        <label
                                            class="text-[11px] font-bold text-slate-700">{{ __('messages.business_details') }}</label>
                                        <input type="text" name="business" value="{{ old('business') }}"
                                            placeholder="Family business info"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    <div class="space-y-1">
                                        <label
                                            class="text-[11px] font-bold text-slate-700">{{ __('messages.house_type') }}</label>
                                        <input type="text" name="house" value="{{ old('house') }}"
                                            placeholder="e.g. Flat / Tenement"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                                    <div class="space-y-1">
                                        <label
                                            class="text-[11px] font-bold text-slate-700">{{ __('messages.own_house') }}</label>
                                        <select name="own_house"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                            <option value="Yes" {{ old('own_house') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                            <option value="No" {{ old('own_house', 'No') === 'No' ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                    <div class="space-y-1">
                                        <label
                                            class="text-[11px] font-bold text-slate-700">{{ __('messages.vehicle_details') }}</label>
                                        <input type="text" name="vehicle" value="{{ old('vehicle') }}"
                                            placeholder="e.g. Two Wheeler / Car model"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                </div>

                                <!-- Tab 2 Next/Prev Buttons -->
                                <div class="flex justify-between pt-2">
                                    <button type="button" @click="yuvaTab = 1"
                                        class="px-4 py-2 border border-slate-200 hover:bg-slate-50 font-bold text-xs rounded-xl transition-colors cursor-pointer">
                                        {!! __('messages.prev_step') !!}
                                    </button>
                                    <button type="button" @click="yuvaTab = 3"
                                        class="px-5 py-2 bg-primary-500 text-white hover:bg-primary-600 font-bold text-xs rounded-xl shadow-xs transition-colors cursor-pointer">
                                        {!! __('messages.next_step') !!}
                                    </button>
                                </div>
                            </div>

                            <!-- STEP 3: Maternal Info -->
                            <div x-show="yuvaTab === 3" class="space-y-2.5 text-xs" x-cloak>
                                <div
                                    class="bg-slate-50/80 px-3 py-2 rounded-xl border border-slate-100 font-bold text-primary-800 text-[11px]">
                                    {{ __('messages.yuva_sec_3') }}
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                                    <div class="space-y-1">
                                        <label
                                            class="text-[11px] font-bold text-slate-700">{{ __('messages.maternal_uncle_name') }}
                                            <span class="text-rose-500">*</span></label>
                                        <input type="text" name="maternal_uncle_name" value="{{ old('maternal_uncle_name') }}"
                                            required placeholder="Enter maternal uncle's name"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    <div class="space-y-1">
                                        <label
                                            class="text-[11px] font-bold text-slate-700">{{ __('messages.maternal_grandfather_name') }}
                                            <span class="text-rose-500">*</span></label>
                                        <input type="text" name="maternal_grandfather_name"
                                            value="{{ old('maternal_grandfather_name') }}" required
                                            placeholder="Enter maternal grandfather's name"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                                    <div class="space-y-1">
                                        <label
                                            class="text-[11px] font-bold text-slate-700">{{ __('messages.maternal_grandfather_address') }}</label>
                                        <input type="text" name="maternal_grandfather_address"
                                            value="{{ old('maternal_grandfather_address') }}"
                                            placeholder="Enter maternal grandfather address"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    <div class="space-y-1">
                                        <label
                                            class="text-[11px] font-bold text-slate-700">{{ __('messages.maternal_grandfather_occupation') }}</label>
                                        <input type="text" name="maternal_grandfather_occupation"
                                            value="{{ old('maternal_grandfather_occupation') }}"
                                            placeholder="e.g. Farming / Retired"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    </div>
                                </div>

                                <!-- Member & Payment Verification Section -->
                                <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80 space-y-3">
                                    <h4 class="font-extrabold text-[11px] text-slate-600 uppercase tracking-wider">Member ID &
                                        Payment Details</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                                        <div class="space-y-1">
                                            <label
                                                class="text-[11px] font-bold text-slate-700">{{ __('messages.member_number') }}</label>
                                            <input type="text" name="member_number"
                                                value="{{ old('member_number', auth()->check() ? '#' . sprintf('%05d', auth()->user()->id) : '') }}"
                                                placeholder="e.g. #00005"
                                                class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs font-semibold focus:border-primary-500 outline-none">
                                        </div>
                                        <div class="space-y-1">
                                            <label
                                                class="text-[11px] font-bold text-slate-700">{{ __('messages.payment_number') }}</label>
                                            <input type="text" name="payment_number" value="{{ old('payment_number') }}"
                                                placeholder="e.g. UTR / UPI Ref / Transaction No."
                                                class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs font-semibold focus:border-primary-500 outline-none">
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <label
                                            class="text-[11px] font-bold text-slate-700 block">{{ __('messages.payment_image') }}</label>
                                        <input type="file" name="payment_image" accept="image/*"
                                            class="w-full text-[10px] text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer">
                                    </div>
                                </div>

                                <!-- Tab 3 Prev Button -->
                                <div class="flex justify-start pt-2">
                                    <button type="button" @click="yuvaTab = 2"
                                        class="px-4 py-2 border border-slate-200 hover:bg-slate-50 font-bold text-xs rounded-xl transition-colors cursor-pointer">
                                        {!! __('messages.prev_step') !!}
                                    </button>
                                </div>
                            </div>

                        @else
                            <!-- Normal Event Form Fields -->
                            <div class="space-y-3.5 text-xs">
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 flex items-center gap-1">
                                        Participant Full Name <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="full_name"
                                        value="{{ old('full_name', auth()->check() ? auth()->user()->name : '') }}" required
                                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all outline-none">
                                </div>

                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 flex items-center gap-1">
                                        Contact Number <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="contact_number"
                                        value="{{ old('contact_number', auth()->check() ? (auth()->user()->memberProfile->phone ?? '') : '') }}"
                                        required maxlength="10"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                                        placeholder="Enter 10-digit mobile number"
                                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all outline-none">
                                </div>

                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700">Special Notes / Remarks
                                        (Optional)</label>
                                    <textarea name="remarks" rows="2"
                                        placeholder="Any special seating or assistance requirements"
                                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all outline-none">{{ old('remarks') }}</textarea>
                                </div>
                            </div>
                        @endif

                        <!-- ACTION BUTTONS -->
                        <div x-show="(('{{ $event->event_type ?? 'normal' }}' !== 'inam_vitaran') || selectedStudent !== '') && ('{{ $event->event_type ?? 'normal' }}' !== 'yuva_melo' || yuvaTab === 3)"
                            class="pt-3 border-t border-slate-100 flex items-center justify-end gap-3">
                            @if(auth()->check())
                                <a href="{{ route('member.events.index') }}"
                                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors">
                                    Cancel
                                </a>
                            @endif
                            <button type="submit"
                                class="px-6 py-2.5 bg-primary-500 hover:bg-primary-600 text-white font-extrabold text-xs rounded-xl shadow-md hover:shadow-lg transition-all transform active:scale-95 flex items-center gap-2 uppercase tracking-wider cursor-pointer">
                                <span>Submit Registration</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            @if(isset($registrations) && $registrations->count() > 0)
                <!-- COMPACT CARD-STYLE SUBMITTED DETAILS LIST (CLICKABLE FOR FULL MODAL VIEW) -->
                <div class="space-y-3 pt-2">
                    <div class="flex items-center justify-between px-1">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider">
                                Submitted Registration Details ({{ $registrations->count() }})
                            </h3>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Click card to view full
                            submitted details</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($registrations as $index => $reg)
                            @if(!empty($reg->form_data))
                                    @php 
                                        $fd = $reg->form_data; 
                                        $cardIndex = $registrations->count() - $index;
                                        $regNo = $fd['registration_no'] ?? $cardIndex;
                                    @endphp
                                    <div @click="selectedRegistration = {{ json_encode([
                                    'id' => $reg->id,
                                    'index' => $cardIndex,
                                    'reg_no' => $regNo,
                                    'date' => $fd['submission_date'] ?? ($reg->created_at ? $reg->created_at->format('d-M-Y h:i A') : '-'),
                                    'status' => $reg->status,
                                    'form_data' => $fd
                                ]) }}; showDetailsModal = true"
                                        class="bg-white border border-slate-200/90 rounded-xl p-4 space-y-3 shadow-xs hover:shadow-md hover:border-primary-400 transition-all cursor-pointer group">

                                        <!-- Card Header: #Index & Candidate Name -->
                                        <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                                            <div class="flex items-center gap-1.5 min-w-0">
                                                <span
                                                    class="text-xs font-black text-slate-400">#{{ $cardIndex }}</span>
                                                <h4
                                                    class="text-xs font-black text-slate-900 truncate group-hover:text-primary-600 transition-colors">
                                                    {{ $fd['full_name'] ?? $fd['student_name'] ?? 'Registration' }}
                                                </h4>
                                            </div>
                                            <span
                                                class="text-[9px] font-extrabold px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">
                                                Submitted
                                            </span>
                                        </div>

                                        <!-- Card Body Snippet -->
                                        <div class="space-y-2 text-[11px] text-slate-600">
                                            @if(($event->event_type ?? 'normal') === 'inam_vitaran')
                                                <div>
                                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Student
                                                        Name</span>
                                                    <span
                                                        class="font-extrabold text-slate-900 text-xs block">{{ $fd['student_name'] ?? '-' }}</span>
                                                </div>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <div>
                                                        <span class="text-[9px] font-bold text-slate-400 uppercase block">Education</span>
                                                        <span class="font-bold text-slate-800">{{ $fd['education'] ?? '-' }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-[9px] font-bold text-slate-400 uppercase block">Percentage</span>
                                                        <span
                                                            class="font-black text-amber-700 bg-amber-50 px-1 py-0.5 rounded border border-amber-200 inline-block text-[10px]">{{ $fd['percentage'] ?? '-' }}</span>
                                                    </div>
                                                </div>
                                            @elseif(($event->event_type ?? 'normal') === 'yuva_melo')
                                                <div>
                                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Full
                                                        Name</span>
                                                    <span
                                                        class="font-extrabold text-slate-900 text-xs block">{{ $fd['full_name'] ?? '-' }}</span>
                                                </div>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <div>
                                                        <span class="text-[9px] font-bold text-slate-400 uppercase block">Age / Gender</span>
                                                        <span class="font-bold text-slate-800">{{ $fd['age'] ?? '-' }} Yrs
                                                            ({{ $fd['gender'] ?? '-' }})</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-[9px] font-bold text-slate-400 uppercase block">Mobile</span>
                                                        <span class="font-bold text-slate-800">{{ $fd['mobile_no'] ?? '-' }}</span>
                                                    </div>
                                                </div>
                                            @else
                                                <div>
                                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Participant
                                                        Name</span>
                                                    <span
                                                        class="font-extrabold text-slate-900 text-xs block">{{ $fd['full_name'] ?? '-' }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Card Footer Action Actions (Details, Edit, Delete till last date) -->
                                        @php
                                            $isDeadlinePassed = !empty($event->registration_end_date) && now()->toDateString() > \Carbon\Carbon::parse($event->registration_end_date)->toDateString();
                                        @endphp
                                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between gap-2 text-xs">
                                            <button type="button" @click.stop="selectedRegistration = {{ json_encode([
                                    'id' => $reg->id,
                                    'index' => $registrations->count() - $index,
                                    'date' => $fd['submission_date'] ?? ($reg->created_at ? $reg->created_at->format('d-M-Y h:i A') : '-'),
                                    'status' => $reg->status,
                                    'form_data' => $fd
                                ]) }}; showDetailsModal = true"
                                                class="text-primary-600 hover:text-primary-700 font-extrabold flex items-center gap-1">
                                                <span>🔍 Details</span>
                                            </button>

                                            @if(!$isDeadlinePassed)
                                                <div class="flex items-center gap-1.5 shrink-0" @click.stop>
                                                    <button type="button"
                                                        @click="editRegistration({{ json_encode(['id' => $reg->id, 'form_data' => $fd]) }})"
                                                        class="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-800 font-extrabold text-[11px] rounded-lg border border-amber-200/80 transition-colors flex items-center gap-1 cursor-pointer">
                                                        ✏️ Edit
                                                    </button>

                                                    <form method="POST" action="{{ route('member.events.registrations.destroy', $reg->id) }}"
                                                        onsubmit="return confirm('Are you sure you want to delete registration for {{ $fd['student_name'] ?? $fd['full_name'] ?? 'this candidate' }}?');"
                                                        class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="px-2 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 font-extrabold text-[11px] rounded-lg border border-rose-200/80 transition-colors flex items-center gap-1 cursor-pointer">
                                                            🗑️ Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <span
                                                    class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded border border-slate-200"
                                                    title="Editing period closed after last date">
                                                    🔒 Closed
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- FULL REGISTRATION DETAILS MODAL POPUP (COMPACT SPACING & HIGH DENSITY) -->
            <template x-teleport="body">
                <div x-show="showDetailsModal"
                    class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-sm"
                    x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>
                    <div @click.away="showDetailsModal = false"
                        class="bg-white rounded-2xl border border-slate-100 shadow-2xl max-w-4xl w-full max-h-[85vh] flex flex-col overflow-hidden relative">

                        <!-- Modal Header -->
                        <div class="px-4 py-3 bg-slate-900 text-white flex items-center justify-between shrink-0">
                            <div>
                                <h3 class="text-xs font-extrabold flex items-center gap-2">
                                    <span>Submitted Registration Details</span>
                                    <span class="px-2 py-0.5 rounded bg-primary-500 text-white text-[10px]"
                                        x-text="'#' + selectedRegistration.index"></span>
                                </h3>
                                <p class="text-[10px] text-slate-400 font-medium mt-0.5"
                                    x-text="'Submitted on: ' + (selectedRegistration.date || '')"></p>
                            </div>
                            <button type="button" @click="showDetailsModal = false"
                                class="w-6 h-6 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors text-xs">
                                ✕
                            </button>
                        </div>

                        <!-- Modal Body (Scrollable Compact Padding) -->
                        <div class="p-3.5 space-y-3 overflow-y-auto text-xs">

                            <!-- Uploaded Documents & Photos Compact Strip -->
                            <template
                                x-if="Object.keys(selectedRegistration.form_data || {}).some(k => k.endsWith('_url'))">
                                <div class="space-y-1">
                                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Uploaded
                                        Documents & Photos</h4>
                                    <div
                                        class="flex flex-wrap items-center gap-2 bg-slate-50 p-2 rounded-xl border border-slate-100">
                                        <template x-for="(val, key) in (selectedRegistration.form_data || {})" :key="key">
                                            <template x-if="key.endsWith('_url') && val">
                                                <div
                                                    class="flex items-center gap-2 bg-white px-2 py-1 rounded-lg border border-slate-200 shadow-2xs">
                                                    <a :href="val" target="_blank"
                                                        class="block w-8 h-8 shrink-0 overflow-hidden rounded bg-slate-100 border border-slate-200">
                                                        <img :src="val" class="w-full h-full object-cover">
                                                    </a>
                                                    <div class="min-w-0">
                                                        <span
                                                            class="text-[9px] font-bold text-slate-700 uppercase block truncate max-w-[100px]"
                                                            x-text="key.replace('_url', '').replace(/_/g, ' ')"></span>
                                                        <a :href="val" target="_blank"
                                                            class="text-[9px] font-bold text-primary-600 hover:underline">View
                                                            File ↗</a>
                                                    </div>
                                                </div>
                                            </template>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <!-- Complete Form Data Grid - Compact 3 to 4 Columns -->
                            <div class="space-y-1.5">
                                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Submitted Form
                                    Fields
                                </h4>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-1.5">
                                    <template x-for="(val, key) in (selectedRegistration.form_data || {})" :key="key">
                                        <template x-if="!key.endsWith('_url') && key !== 'submission_date'">
                                            <div
                                                class="bg-slate-50/90 px-2.5 py-1.5 rounded-lg border border-slate-100 hover:bg-slate-100/80 transition-colors">
                                                <span
                                                    class="text-[8px] font-black text-slate-400 uppercase tracking-wider block truncate"
                                                    x-text="key.replace(/_/g, ' ')"></span>
                                                <span
                                                    class="font-bold text-slate-900 text-[11px] block break-words leading-tight mt-0.5"
                                                    x-text="val || '-'"></span>
                                            </div>
                                        </template>
                                    </template>
                                </div>
                            </div>

                        </div>

                        <!-- Modal Footer -->
                        <div class="px-4 py-2 bg-slate-50 border-t border-slate-100 flex items-center justify-end shrink-0">
                            <button type="button" @click="showDetailsModal = false"
                                class="px-4 py-1.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition-colors cursor-pointer">
                                Close Details
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            <!-- SIBLING ADD POPUP MODAL -->
            <template x-teleport="body">
                <div x-show="showSiblingModal"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>

                    <div class="bg-white rounded-2xl border border-slate-100 shadow-2xl w-full max-w-md overflow-hidden"
                        @click.away="showSiblingModal = false">

                        <!-- Modal Header -->
                        <div class="px-5 py-4 bg-slate-900 text-white flex items-center justify-between">
                            <h3 class="font-extrabold text-xs uppercase tracking-wider">
                                + Add Sibling Details
                            </h3>
                            <button type="button" @click="showSiblingModal = false"
                                class="text-slate-400 hover:text-white font-bold text-lg leading-none">
                                &times;
                            </button>
                        </div>

                        <!-- Modal Body -->
                        <div class="p-5 space-y-4 text-xs">
                            <!-- Relation Dropdown -->
                            <div class="space-y-1">
                                <label class="font-bold text-slate-700 block text-[11px]">
                                    Relation <span class="text-rose-500">*</span>
                                </label>
                                <select x-model="newSibling.relation"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    <option value="Elder Brother">Elder Brother</option>
                                    <option value="Younger Brother">Younger Brother</option>
                                    <option value="Elder Sister">Elder Sister</option>
                                    <option value="Younger Sister">Younger Sister</option>
                                </select>
                            </div>

                            <!-- Name / Details -->
                            <div class="space-y-1">
                                <label class="font-bold text-slate-700 block text-[11px]">
                                    Name / Count / Details
                                </label>
                                <input type="text" x-model="newSibling.details" placeholder="e.g. Ramesh Bhai / 1 Brother"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                            </div>

                            <!-- Marital Status -->
                            <div class="space-y-1">
                                <label class="font-bold text-slate-700 block text-[11px]">
                                    Marital Status <span class="text-rose-500">*</span>
                                </label>
                                <select x-model="newSibling.married"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    <option value="No">Unmarried</option>
                                    <option value="Yes">Married</option>
                                </select>
                            </div>

                            <!-- Occupation / Notes -->
                            <div class="space-y-1">
                                <label class="font-bold text-slate-700 block text-[11px]">
                                    Occupation / Notes (Optional)
                                </label>
                                <input type="text" x-model="newSibling.occupation" placeholder="e.g. Job in IT / Student"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
                            <button type="button" @click="showSiblingModal = false"
                                class="px-4 py-2 bg-slate-200 hover:bg-slate-300 font-bold text-slate-700 rounded-xl transition-colors text-xs cursor-pointer">
                                Cancel
                            </button>
                            <button type="button" @click="addSibling()"
                                class="px-4 py-2 bg-primary-600 hover:bg-primary-700 font-bold text-white rounded-xl shadow-xs transition-colors text-xs cursor-pointer">
                                + Add Sibling
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        @if(!auth()->check())
            </div>
        @endif
@endsection