@extends('layouts.admin')

@php
    $isGu = (app()->getLocale() === 'gu');
    $fd = $registration->form_data ?? [];
    
    // Parse existing siblings
    $initialSiblingsArr = [];
    if (!empty($fd['siblings_json'])) {
        if (is_array($fd['siblings_json'])) {
            $initialSiblingsArr = $fd['siblings_json'];
        } else {
            $decoded = json_decode($fd['siblings_json'], true);
            if (is_array($decoded)) {
                $initialSiblingsArr = $decoded;
            }
        }
    }
    if (empty($initialSiblingsArr)) {
        if (!empty($fd['elder_brother']))
            $initialSiblingsArr[] = ['relation' => 'Elder Brother', 'details' => $fd['elder_brother'], 'married' => $fd['elder_brother_married'] ?? 'No', 'occupation' => ''];
        if (!empty($fd['younger_brother']))
            $initialSiblingsArr[] = ['relation' => 'Younger Brother', 'details' => $fd['younger_brother'], 'married' => $fd['younger_brother_married'] ?? 'No', 'occupation' => ''];
        if (!empty($fd['elder_sister']))
            $initialSiblingsArr[] = ['relation' => 'Elder Sister', 'details' => $fd['elder_sister'], 'married' => $fd['elder_sister_married'] ?? 'No', 'occupation' => ''];
        if (!empty($fd['younger_sister']))
            $initialSiblingsArr[] = ['relation' => 'Younger Sister', 'details' => $fd['younger_sister'], 'married' => $fd['younger_sister_married'] ?? 'No', 'occupation' => ''];
    }
@endphp

@section('page_title', $isGu ? 'ઉમેદવાર રજીસ્ટ્રેશન એડિટ - ' . $event->title : 'Edit Candidate Registration - ' . $event->title)

@section('content')
<div class="max-w-6xl mx-auto space-y-6" x-data="adminCandidateEditData()">
    <!-- Top Navigation Header -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-xs flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.events.show', $event->id) }}" 
               class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold text-xs transition-colors flex items-center gap-1.5 shadow-2xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>{{ $isGu ? 'પાછા જાઓ' : 'Back to Event' }}</span>
            </a>
            <div>
                <h2 class="text-base font-black text-slate-900">
                    {{ $isGu ? '✏️ ઉમેદવાર રજીસ્ટ્રેશન સુધારો' : '✏️ Edit Candidate Registration' }}
                </h2>
                <p class="text-xs text-slate-500 font-medium">
                    {{ $event->title }} &bull; {{ $isGu ? 'ઉમેદવાર ક્રમાંક #' : 'Candidate #' }}{{ $fd['registration_no'] ?? $registration->id }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-black bg-purple-50 text-purple-700 border border-purple-200 shadow-2xs">
                <svg class="w-3.5 h-3.5 text-purple-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span>{{ $isGu ? 'યુવા મેળો ઉમેદવાર' : 'Yuva Melo Candidate' }}</span>
            </span>
        </div>
    </div>

    <!-- Main Edit Form -->
    <form method="POST" action="{{ route('admin.events.registrations.update', $registration->id) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- ========================================== -->
        <!-- SECTION 1: Personal & Contact Information -->
        <!-- ========================================== -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
            <div class="p-4 bg-slate-50/80 border-b border-slate-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">
                    {{ $isGu ? '૧. ઉમેદવારની અંગત માહિતી' : '1. Personal Information' }}
                </h3>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'ઉમેદવારનું નામ' : 'First Name' }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="first_name" value="{{ old('first_name', $fd['first_name'] ?? ($registration->user->name ?? '')) }}" required
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'અટક' : 'Surname' }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="surname" value="{{ old('surname', $fd['surname'] ?? '') }}" required
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'જાતિ' : 'Gender' }} <span class="text-rose-500">*</span>
                    </label>
                    <select name="gender" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                        <option value="Male" {{ old('gender', $fd['gender'] ?? '') === 'Male' ? 'selected' : '' }}>{{ $isGu ? 'ભાઈ' : 'Male' }}</option>
                        <option value="Female" {{ old('gender', $fd['gender'] ?? '') === 'Female' ? 'selected' : '' }}>{{ $isGu ? 'બહેન' : 'Female' }}</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'જન્મ તારીખ' : 'Birth Date' }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="birth_date" value="{{ old('birth_date', $fd['birth_date'] ?? '') }}" placeholder="DD-MM-YYYY" required
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'ઉંમર' : 'Age' }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="age" value="{{ old('age', $fd['age'] ?? '') }}" required
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'ઊંચાઈ' : 'Height' }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="height" value="{{ old('height', $fd['height'] ?? '') }}" placeholder="{{ $isGu ? 'દા.ત. 5\'6"' : 'e.g. 5\'6"' }}" required
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'વજન (Kg)' : 'Weight (Kg)' }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="weight" value="{{ old('weight', $fd['weight'] ?? '') }}" placeholder="{{ $isGu ? 'દા.ત. 62 kg' : 'e.g. 62 kg' }}" required
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'મોબાઇલ નંબર ૧' : 'Mobile Number 1' }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="mobile_no" value="{{ old('mobile_no', $fd['mobile_no'] ?? ($fd['contact_number'] ?? '')) }}" required maxlength="10"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'WhatsApp નંબર' : 'WhatsApp Number' }}
                    </label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp', $fd['whatsapp'] ?? '') }}" maxlength="10"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'મૂળ વતન / ગામ' : 'Native Place' }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="native_place" value="{{ old('native_place', $fd['native_place'] ?? '') }}" required
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'જિલ્લો' : 'District' }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="district" value="{{ old('district', $fd['district'] ?? '') }}" required
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'રાજ્ય' : 'State' }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="state" value="{{ old('state', $fd['state'] ?? 'Gujarat') }}" required
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'વિસ્તાર / મંડળ' : 'Area / Mandal' }} <span class="text-rose-500">*</span>
                    </label>
                    <select name="area_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                        <option value="">{{ $isGu ? '-- વિસ્તાર પસંદ કરો --' : '-- Select Area --' }}</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ old('area_id', $fd['area_id'] ?? '') == $area->id ? 'selected' : '' }}>
                                {{ $area->name }}{{ $area->pincode ? ' (' . $area->pincode . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'મંડળ / એસોસિએશન' : 'Association / Mandal' }}
                    </label>
                    <input type="text" name="association" value="{{ old('association', $fd['association'] ?? '') }}" 
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'પિતાની જ્ઞાતિ / શાખા' : 'Father\'s Gyanti' }}
                    </label>
                    <input type="text" name="father_gyanti" value="{{ old('father_gyanti', $fd['father_gyanti'] ?? '') }}" 
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                </div>

                <div class="sm:col-span-2 lg:col-span-3">
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'હાલનું પૂરું સરનામું' : 'Current Full Address' }} <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="address" rows="2" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">{{ old('address', $fd['address'] ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- SECTION 2: Education & Occupation -->
        <!-- ========================================== -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
            <div class="p-4 bg-slate-50/80 border-b border-slate-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">
                    {{ $isGu ? '૨. શિક્ષણ અને વ્યવસાય' : '2. Education & Occupation' }}
                </h3>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'શૈક્ષણિક લાયકાત (ડિગ્રી)' : 'Qualification / Degree' }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="qualification" value="{{ old('qualification', $fd['qualification'] ?? '') }}" required placeholder="{{ $isGu ? 'દા.ત. ગ્રેજ્યુએટ / B.E. / B.Com' : 'e.g. Graduate / B.E. / B.Com' }}"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'નોકરી / ધંધાની વિગત' : 'Occupation / Job Details' }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="occupation" value="{{ old('occupation', $fd['occupation'] ?? '') }}" required placeholder="{{ $isGu ? 'દા.ત. સોફ્ટવેર એન્જિનિયર / બિઝનેસ' : 'e.g. Software Engineer / Business' }}"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'માસિક આવક' : 'Monthly Income' }}
                    </label>
                    <input type="text" name="monthly_income" value="{{ old('monthly_income', $fd['monthly_income'] ?? '') }}" placeholder="{{ $isGu ? 'દા.ત. ₹ 35,000' : 'e.g. Rs. 35,000' }}"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'રિટાયર્ડ / સ્ટેટસ' : 'Retired / Status' }}
                    </label>
                    <input type="text" name="retired" value="{{ old('retired', $fd['retired'] ?? '') }}" placeholder="{{ $isGu ? 'દા.ત. ના / રિટાયર્ડ' : 'e.g. No / Retired' }}"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'નોકરી / ધંધાનું સરનામું' : 'Occupation Address' }}
                    </label>
                    <input type="text" name="occupation_address" value="{{ old('occupation_address', $fd['occupation_address'] ?? '') }}" placeholder="{{ $isGu ? 'ઓફિસ / કંપનીનું સરનામું' : 'Enter job/office address' }}"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- SECTION 3: Family & Parents Details -->
        <!-- ========================================== -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
            <div class="p-4 bg-slate-50/80 border-b border-slate-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">
                    {{ $isGu ? '૩. કુટુંબ અને માતા-પિતાની વિગત' : '3. Family & Parents Details' }}
                </h3>
            </div>
            <div class="p-5 space-y-4 text-xs">
                <!-- Father Details -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">
                            {{ $isGu ? 'પિતાનું પૂરું નામ' : 'Father\'s Full Name' }} <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="father_name" value="{{ old('father_name', $fd['father_name'] ?? '') }}" required
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">
                            {{ $isGu ? 'દાદાનું નામ' : 'Grandfather\'s Name' }} <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="grandfather_name" value="{{ old('grandfather_name', $fd['grandfather_name'] ?? '') }}" required
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">
                            {{ $isGu ? 'પિતાની ઉંમર' : 'Father\'s Age' }}
                        </label>
                        <input type="number" name="father_age" value="{{ old('father_age', $fd['father_age'] ?? '') }}" placeholder="{{ $isGu ? 'દા.ત. 52' : 'e.g. 52' }}"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">
                            {{ $isGu ? 'પિતાનો મોબાઇલ નંબર' : 'Father\'s Mobile' }}
                        </label>
                        <input type="text" name="father_mobile" value="{{ old('father_mobile', $fd['father_mobile'] ?? '') }}" maxlength="10"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">
                            {{ $isGu ? 'પિતાનો વ્યવસાય' : 'Father\'s Occupation' }}
                        </label>
                        <input type="text" name="father_occupation" value="{{ old('father_occupation', $fd['father_occupation'] ?? '') }}" placeholder="{{ $isGu ? 'દા.ત. ખેતી / વેપાર' : 'e.g. Business / Farming' }}"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">
                            {{ $isGu ? 'પિતાની આવક' : 'Father\'s Income' }}
                        </label>
                        <input type="text" name="father_income" value="{{ old('father_income', $fd['father_income'] ?? '') }}" placeholder="{{ $isGu ? 'વાર્ષિક કે માસિક આવક' : 'Annual or Monthly Income' }}"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1">
                            {{ $isGu ? 'પિતાના વ્યવસાયનું સરનામું' : 'Father\'s Occupation Address' }}
                        </label>
                        <input type="text" name="father_occupation_address" value="{{ old('father_occupation_address', $fd['father_occupation_address'] ?? '') }}" 
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                    </div>
                </div>

                <!-- Mother Details -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-3 border-t border-slate-100">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">
                            {{ $isGu ? 'માતાનું નામ' : 'Mother\'s Name' }} <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="mother_name" value="{{ old('mother_name', $fd['mother_name'] ?? '') }}" required
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">
                            {{ $isGu ? 'માતાની જ્ઞાતિ / મોસાળ શાખા' : 'Mother\'s Gyanti / Mosal' }}
                        </label>
                        <input type="text" name="mother_gyanti" value="{{ old('mother_gyanti', $fd['mother_gyanti'] ?? '') }}" 
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">
                            {{ $isGu ? 'માતાનો વ્યવસાય' : 'Mother\'s Occupation' }}
                        </label>
                        <input type="text" name="mother_occupation" value="{{ old('mother_occupation', $fd['mother_occupation'] ?? ($isGu ? 'ગૃહિણી' : 'Housewife')) }}" 
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- SECTION 4: Siblings (Interactive Alpine) -->
        <!-- ========================================== -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
            <div class="p-4 bg-slate-50/80 border-b border-slate-100 flex items-center justify-between flex-wrap gap-2">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">
                        {{ $isGu ? '૪. ભાઈ-બહેનની વિગત' : '4. Siblings Details' }}
                    </h3>
                </div>
                <button type="button" @click="showAddSiblingModal = true"
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-primary-600 hover:bg-primary-700 text-white font-bold text-xs rounded-xl shadow-xs transition-all cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>{{ $isGu ? '+ ભાઈ/બહેન ઉમેરો' : '+ Add Sibling' }}</span>
                </button>
            </div>
            
            <div class="p-5 space-y-3 text-xs">
                <!-- Sibling Cards Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3" x-show="siblings.length > 0">
                    <template x-for="(s, index) in siblings" :key="index">
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 flex items-start justify-between gap-2 shadow-2xs">
                            <div class="space-y-1 min-w-0">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="text-[9px] font-black px-2 py-0.5 rounded text-white"
                                          :class="s.relation.includes('Brother') ? 'bg-blue-600' : 'bg-pink-600'" 
                                          x-text="getRelationLabel(s.relation)"></span>
                                    <span class="font-bold text-slate-800 text-xs truncate" x-text="s.details || '1 Member'"></span>
                                </div>
                                <div class="text-[10.5px] text-slate-600 font-semibold space-x-2">
                                    <span class="px-1.5 py-0.2 rounded bg-white border border-slate-200" 
                                          x-text="s.married === 'Yes' ? '{{ $isGu ? 'પરણેલા' : 'Married' }}' : '{{ $isGu ? 'અપરણિત' : 'Unmarried' }}'"></span>
                                    <template x-if="s.occupation">
                                        <span class="text-slate-500 font-medium" x-text="'• ' + s.occupation"></span>
                                    </template>
                                </div>
                            </div>
                            <button type="button" @click="removeSibling(index)" 
                                    class="p-1 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors shrink-0 cursor-pointer" 
                                    title="Delete Sibling">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </template>
                </div>

                <!-- Empty state -->
                <div x-show="siblings.length === 0" class="p-4 border border-dashed border-slate-200 rounded-xl text-center text-slate-400 font-medium">
                    {{ $isGu ? 'હજુ કોઈ ભાઈ-બહેનની વિગત ઉમેરેલ નથી. ઉમેરવા માટે "+ ભાઈ/બહેન ઉમેરો" બટન દબાવો.' : 'No siblings added. Click "+ Add Sibling" to add brother or sister details.' }}
                </div>

                <!-- Hidden inputs synced to backend -->
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
        </div>

        <!-- ========================================== -->
        <!-- SECTION 5: Mosal (Maternal) Details -->
        <!-- ========================================== -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
            <div class="p-4 bg-slate-50/80 border-b border-slate-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">
                    {{ $isGu ? '૫. મોસાળ પક્ષની વિગત' : '5. Mosal Details' }}
                </h3>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'મામાનું નામ' : 'Maternal Uncle Name' }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="maternal_uncle_name" value="{{ old('maternal_uncle_name', $fd['maternal_uncle_name'] ?? '') }}" required
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'નાનાનું નામ' : 'Maternal Grandfather Name' }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="maternal_grandfather_name" value="{{ old('maternal_grandfather_name', $fd['maternal_grandfather_name'] ?? '') }}" required
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'મોસાળનું સરનામું' : 'Mosal Address' }}
                    </label>
                    <input type="text" name="maternal_grandfather_address" value="{{ old('maternal_grandfather_address', $fd['maternal_grandfather_address'] ?? '') }}" 
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'મોસાળનો વ્યવસાય' : 'Mosal Occupation' }}
                    </label>
                    <input type="text" name="maternal_grandfather_occupation" value="{{ old('maternal_grandfather_occupation', $fd['maternal_grandfather_occupation'] ?? '') }}" 
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- SECTION 6: Property, Vehicle & Special Info -->
        <!-- ========================================== -->
        <!-- ========================================== -->
        <!-- SECTION 6: Property, Vehicle & Special Info -->
        <!-- ========================================== -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
            <div class="p-4 bg-slate-50/80 border-b border-slate-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">
                    {{ $isGu ? '૬. મિલકત અને વિશેષ માહિતી' : '6. Property, Disability & Other Info' }}
                </h3>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'પરિવારનો ધંધો' : 'Family Business' }}
                    </label>
                    <input type="text" name="business" value="{{ old('business', $fd['business'] ?? '') }}" placeholder="{{ $isGu ? 'ધંધાની વિગત' : 'Business info' }}"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'મકાનનો પ્રકાર' : 'House Type' }}
                    </label>
                    <input type="text" name="house" value="{{ old('house', $fd['house'] ?? '') }}" placeholder="{{ $isGu ? 'દા.ત. ફ્લેટ / ટેનામેન્ટ' : 'e.g. Flat / Tenement' }}"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'પોતાનું મકાન?' : 'Own House?' }}
                    </label>
                    <select name="own_house" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                        <option value="Yes" {{ old('own_house', $fd['own_house'] ?? 'Yes') === 'Yes' ? 'selected' : '' }}>{{ $isGu ? 'હા (પોતાનું)' : 'Yes (Own)' }}</option>
                        <option value="No" {{ old('own_house', $fd['own_house'] ?? '') === 'No' ? 'selected' : '' }}>{{ $isGu ? 'ના (ભાડે)' : 'No (Rented)' }}</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'વાહનની વિગત' : 'Vehicle Details' }}
                    </label>
                    <input type="text" name="vehicle" value="{{ old('vehicle', $fd['vehicle'] ?? '') }}" placeholder="{{ $isGu ? 'દા.ત. ૨ વ્હીલર / કાર' : 'e.g. 2 Wheeler / Car' }}"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'છૂટાછેડા સ્ટેટસ' : 'Divorce Status' }} <span class="text-rose-500">*</span>
                    </label>
                    <select name="divorce" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                        <option value="No" {{ old('divorce', $fd['divorce'] ?? 'No') === 'No' ? 'selected' : '' }}>{{ $isGu ? 'ના' : 'No' }}</option>
                        <option value="Yes" {{ old('divorce', $fd['divorce'] ?? '') === 'Yes' ? 'selected' : '' }}>{{ $isGu ? 'હા' : 'Yes' }}</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'વિશેષ જરૂરિયાત' : 'Special Need' }}
                    </label>
                    <input type="text" name="special_need" value="{{ old('special_need', $fd['special_need'] ?? '') }}" placeholder="{{ $isGu ? 'વિગત અથવા નથી' : 'Details or None' }}"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'શારીરિક ખોડ-ખાંપણ' : 'Physical Disability' }}
                    </label>
                    <input type="text" name="physical_disability" value="{{ old('physical_disability', $fd['physical_disability'] ?? '') }}" placeholder="{{ $isGu ? 'નથી અથવા વિગત' : 'None or Details' }}"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'કેટલા સમયથી' : 'Disability Duration' }}
                    </label>
                    <input type="text" name="disability_duration" value="{{ old('disability_duration', $fd['disability_duration'] ?? '') }}" placeholder="{{ $isGu ? 'દા.ત. જન્મથી / N/A' : 'e.g. Since birth / N/A' }}"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'વિશેષ સિદ્ધિ / માહિતી' : 'Special Information' }}
                    </label>
                    <input type="text" name="special_info" value="{{ old('special_info', $fd['special_info'] ?? '') }}" placeholder="{{ $isGu ? 'વિશેષ સિદ્ધિ અથવા વિગત' : 'Special achievement or info' }}"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'અન્ય નોંધ' : 'Other Info / Remarks' }}
                    </label>
                    <input type="text" name="other_info" value="{{ old('other_info', $fd['other_info'] ?? '') }}" placeholder="{{ $isGu ? 'વધારાની નોંધ' : 'Additional notes' }}"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 font-semibold text-slate-900">
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- SECTION 7: Photos & Uploaded Documents -->
        <!-- ========================================== -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
            <div class="p-4 bg-slate-50/80 border-b border-slate-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">
                    {{ $isGu ? '૭. ઉમેદવારના ફોટોગ્રાફ્સ અને દસ્તાવેજો' : '7. Candidate Photos & Documents' }}
                </h3>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
                <!-- Member Photo -->
                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200 flex flex-col justify-between space-y-2.5 shadow-2xs">
                    <div>
                        <span class="block font-extrabold text-slate-800 text-xs">
                            {{ $isGu ? 'પાસપોર્ટ સાઇઝ ફોટો' : 'Passport / Member Photo' }}
                        </span>
                        <p class="text-[10px] text-slate-400 font-medium mt-0.5">{{ $isGu ? 'મુખ્ય ફોટોગ્રાફ' : 'Primary Candidate Photo' }}</p>
                    </div>

                    <div class="flex items-center justify-center bg-white rounded-xl border border-slate-200 p-2 min-h-[130px]">
                        @if(!empty($fd['member_photo_url']))
                            <div style="width: 100px; height: 120px; overflow: hidden;" class="rounded-lg border border-slate-300 shadow-2xs">
                                <img src="{{ $fd['member_photo_url'] }}" alt="Member Photo" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        @else
                            <div class="text-center text-slate-300 p-3">
                                <svg class="w-8 h-8 mx-auto text-slate-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span class="text-[9px] font-bold text-slate-400">{{ $isGu ? 'ફોટો નથી' : 'No Photo' }}</span>
                            </div>
                        @endif
                    </div>

                    <div>
                        <input type="file" name="member_photo" accept="image/*" 
                               class="w-full text-[10px] text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-purple-100 file:text-purple-800 hover:file:bg-purple-200 cursor-pointer">
                    </div>
                </div>

                <!-- Selfie Photo -->
                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200 flex flex-col justify-between space-y-2.5 shadow-2xs">
                    <div>
                        <span class="block font-extrabold text-slate-800 text-xs">
                            {{ $isGu ? 'સેલ્ફી ફોટો' : 'Selfie Photo' }}
                        </span>
                        <p class="text-[10px] text-slate-400 font-medium mt-0.5">{{ $isGu ? 'તાજેતરની સેલ્ફી' : 'Recent Candidate Selfie' }}</p>
                    </div>

                    <div class="flex items-center justify-center bg-white rounded-xl border border-slate-200 p-2 min-h-[130px]">
                        @if(!empty($fd['selfie_url']))
                            <div style="width: 100px; height: 120px; overflow: hidden;" class="rounded-lg border border-slate-300 shadow-2xs">
                                <img src="{{ $fd['selfie_url'] }}" alt="Selfie" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        @else
                            <div class="text-center text-slate-300 p-3">
                                <svg class="w-8 h-8 mx-auto text-slate-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span class="text-[9px] font-bold text-slate-400">{{ $isGu ? 'સેલ્ફી નથી' : 'No Selfie' }}</span>
                            </div>
                        @endif
                    </div>

                    <div>
                        <input type="file" name="selfie" accept="image/*" 
                               class="w-full text-[10px] text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-purple-100 file:text-purple-800 hover:file:bg-purple-200 cursor-pointer">
                    </div>
                </div>

                <!-- WhatsApp Image -->
                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200 flex flex-col justify-between space-y-2.5 shadow-2xs">
                    <div>
                        <span class="block font-extrabold text-slate-800 text-xs">
                            {{ $isGu ? 'WhatsApp / ફૂલ ફોટો' : 'WhatsApp / Full Photo' }}
                        </span>
                        <p class="text-[10px] text-slate-400 font-medium mt-0.5">{{ $isGu ? 'ફૂલ સાઈઝ ફોટોગ્રાફ' : 'Full Length Photo' }}</p>
                    </div>

                    <div class="flex items-center justify-center bg-white rounded-xl border border-slate-200 p-2 min-h-[130px]">
                        @if(!empty($fd['whatsapp_image_url']))
                            <div style="width: 100px; height: 120px; overflow: hidden;" class="rounded-lg border border-slate-300 shadow-2xs">
                                <img src="{{ $fd['whatsapp_image_url'] }}" alt="WhatsApp Photo" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        @else
                            <div class="text-center text-slate-300 p-3">
                                <svg class="w-8 h-8 mx-auto text-slate-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-[9px] font-bold text-slate-400">{{ $isGu ? 'ફોટો નથી' : 'No Photo' }}</span>
                            </div>
                        @endif
                    </div>

                    <div>
                        <input type="file" name="whatsapp_image" accept="image/*" 
                               class="w-full text-[10px] text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-purple-100 file:text-purple-800 hover:file:bg-purple-200 cursor-pointer">
                    </div>
                </div>

                <!-- Aadhaar Photo -->
                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200 flex flex-col justify-between space-y-2.5 shadow-2xs">
                    <div>
                        <span class="block font-extrabold text-slate-800 text-xs">
                            {{ $isGu ? 'આધાર કાર્ડ / દસ્તાવેજ' : 'Aadhaar Card / Document' }}
                        </span>
                        <p class="text-[10px] text-slate-400 font-medium mt-0.5">{{ $isGu ? 'ઓળખપત્ર' : 'Identity Proof' }}</p>
                    </div>

                    <div class="flex items-center justify-center bg-white rounded-xl border border-slate-200 p-2 min-h-[130px]">
                        @if(!empty($fd['aadhaar_photo_url']))
                            <div class="text-center p-2">
                                <svg class="w-8 h-8 mx-auto text-primary-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <a href="{{ $fd['aadhaar_photo_url'] }}" target="_blank" 
                                   class="inline-flex items-center gap-1 text-primary-600 hover:text-primary-800 font-extrabold text-[11px] underline">
                                    <span>{{ $isGu ? 'દસ્તાવેજ જુઓ ↗' : 'View Document ↗' }}</span>
                                </a>
                            </div>
                        @else
                            <div class="text-center text-slate-300 p-3">
                                <svg class="w-8 h-8 mx-auto text-slate-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span class="text-[9px] font-bold text-slate-400">{{ $isGu ? 'અપલોડ નથી' : 'Not Uploaded' }}</span>
                            </div>
                        @endif
                    </div>

                    <div>
                        <input type="file" name="aadhaar_photo" accept="image/*,.pdf" 
                               class="w-full text-[10px] text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-purple-100 file:text-purple-800 hover:file:bg-purple-200 cursor-pointer">
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit & Cancel Actions (Proper Clean Bottom Bar) -->
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-md flex items-center justify-end gap-3 sticky bottom-4 z-20">
            <a href="{{ route('admin.events.show', $event->id) }}" 
               class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all shadow-2xs">
                {{ $isGu ? 'રદ કરો (Cancel)' : 'Cancel' }}
            </a>
            <button type="submit" 
                    class="px-8 py-2.5 bg-primary-600 hover:bg-primary-700 active:scale-95 text-white font-black text-xs rounded-xl shadow-md transition-all flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                <span>{{ $isGu ? 'વિગત સેવ અને અપડેટ કરો' : 'Save & Update Candidate' }}</span>
            </button>
        </div>
    </form>

    <!-- Modal to Add Sibling -->
    <div x-show="showAddSiblingModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" x-cloak>
        <div @click.away="showAddSiblingModal = false" class="bg-white rounded-2xl border border-slate-200 shadow-xl max-w-md w-full p-5 space-y-4 text-xs">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-black text-slate-900 text-sm">
                    {{ $isGu ? 'ભાઈ-બહેનની વિગત ઉમેરો' : 'Add Sibling Details' }}
                </h3>
                <button type="button" @click="showAddSiblingModal = false" class="text-slate-400 hover:text-slate-700 font-bold text-base cursor-pointer">&times;</button>
            </div>

            <div class="space-y-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'સંબંધ' : 'Relation' }}
                    </label>
                    <select x-model="newSibling.relation" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-semibold text-slate-900">
                        <option value="Elder Brother">{{ $isGu ? 'મોટા ભાઈ' : 'Elder Brother' }}</option>
                        <option value="Younger Brother">{{ $isGu ? 'નાના ભાઈ' : 'Younger Brother' }}</option>
                        <option value="Elder Sister">{{ $isGu ? 'મોટી બહેન' : 'Elder Sister' }}</option>
                        <option value="Younger Sister">{{ $isGu ? 'નાની બહેન' : 'Younger Sister' }}</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'વિગત / નામ / અભ્યાસ' : 'Details / Name / Qualification' }}
                    </label>
                    <input type="text" x-model="newSibling.details" placeholder="{{ $isGu ? 'દા.ત. B.Tech / અમદાવાદમાં નોકરી' : 'e.g. B.Tech / Job in Ahmedabad' }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-semibold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'વ્યવસાય' : 'Occupation' }}
                    </label>
                    <input type="text" x-model="newSibling.occupation" placeholder="{{ $isGu ? 'દા.ત. બિઝનેસ / એન્જિનિયર' : 'e.g. Business / Engineer' }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-semibold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        {{ $isGu ? 'પરણેલા છે?' : 'Married?' }}
                    </label>
                    <select x-model="newSibling.married" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-semibold text-slate-900">
                        <option value="No">{{ $isGu ? 'અપરણિત (No)' : 'No (Unmarried)' }}</option>
                        <option value="Yes">{{ $isGu ? 'પરણેલા (Yes)' : 'Yes (Married)' }}</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" @click="showAddSiblingModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition-colors">
                    {{ $isGu ? 'રદ કરો' : 'Cancel' }}
                </button>
                <button type="button" @click="addSibling()" class="px-5 py-2 bg-primary-600 hover:bg-primary-700 text-white font-extrabold rounded-xl shadow-xs transition-colors cursor-pointer">
                    {{ $isGu ? 'ઉમેરો' : 'Add' }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function adminCandidateEditData() {
    return {
        showAddSiblingModal: false,
        isGu: {{ $isGu ? 'true' : 'false' }},
        siblings: @json($initialSiblingsArr),
        newSibling: {
            relation: 'Elder Brother',
            details: '',
            occupation: '',
            married: 'No'
        },
        getRelationLabel(rel) {
            if (!this.isGu) return rel;
            if (rel === 'Elder Brother') return 'મોટા ભાઈ';
            if (rel === 'Younger Brother') return 'નાના ભાઈ';
            if (rel === 'Elder Sister') return 'મોટી બહેન';
            if (rel === 'Younger Sister') return 'નાની બહેન';
            return rel;
        },
        addSibling() {
            if (!this.newSibling.details && !this.newSibling.relation) return;
            this.siblings.push({
                relation: this.newSibling.relation,
                details: this.newSibling.details || '1 Member',
                occupation: this.newSibling.occupation || '',
                married: this.newSibling.married || 'No'
            });
            this.newSibling = { relation: 'Elder Brother', details: '', occupation: '', married: 'No' };
            this.showAddSiblingModal = false;
        },
        removeSibling(index) {
            this.siblings.splice(index, 1);
        },
        get legacyElderB() {
            const list = this.siblings.filter(s => s.relation === 'Elder Brother');
            return list.map(s => s.details).join(', ');
        },
        get legacyElderBM() {
            const list = this.siblings.filter(s => s.relation === 'Elder Brother');
            return list.some(s => s.married === 'Yes') ? 'Yes' : 'No';
        },
        get legacyYoungerB() {
            const list = this.siblings.filter(s => s.relation === 'Younger Brother');
            return list.map(s => s.details).join(', ');
        },
        get legacyYoungerBM() {
            const list = this.siblings.filter(s => s.relation === 'Younger Brother');
            return list.some(s => s.married === 'Yes') ? 'Yes' : 'No';
        },
        get legacyElderS() {
            const list = this.siblings.filter(s => s.relation === 'Elder Sister');
            return list.map(s => s.details).join(', ');
        },
        get legacyElderSM() {
            const list = this.siblings.filter(s => s.relation === 'Elder Sister');
            return list.some(s => s.married === 'Yes') ? 'Yes' : 'No';
        },
        get legacyYoungerS() {
            const list = this.siblings.filter(s => s.relation === 'Younger Sister');
            return list.map(s => s.details).join(', ');
        },
        get legacyYoungerSM() {
            const list = this.siblings.filter(s => s.relation === 'Younger Sister');
            return list.some(s => s.married === 'Yes') ? 'Yes' : 'No';
        }
    };
}
</script>
@endsection
