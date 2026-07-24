@extends('layouts.member')

@section('page_title', $event->title . ' Registration')

@section('content')
<div class="space-y-4 w-full">
    
    <!-- Top Navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('member.events.index') }}" 
           class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-slate-900 bg-white px-3.5 py-2 rounded-xl border border-slate-200 shadow-2xs transition-all hover:shadow-xs">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span>Back to Events</span>
        </a>
    </div>

    <!-- MAIN FORM CARD CONTAINER -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden w-full">
        <div class="p-4 sm:p-6 space-y-4">

            <!-- Registration Form (ALWAYS VISIBLE) -->
            <form method="POST" action="{{ route('member.events.register', $event->id) }}" enctype="multipart/form-data" class="space-y-4"
                  x-data="{ 
                      selectedStudent: '{{ old('student_name', '') }}',
                      totalMarks: '{{ old('total_marks', '') }}', 
                      receivedMarks: '{{ old('received_marks', '') }}', 
                      percentage: '{{ old('percentage', '') }}',
                      yuvaTab: 1,
                      calcPercentage() {
                          let t = parseFloat(this.totalMarks);
                          let r = parseFloat(this.receivedMarks);
                          if (!isNaN(t) && !isNaN(r) && t > 0) {
                              this.percentage = ((r / t) * 100).toFixed(2) + '%';
                          }
                      }
                  }">
                @csrf

                @if(($event->event_type ?? 'normal') === 'inam_vitaran')
                    <!-- Inam Vitaran Academic & Marksheet Form Fields -->
                    <div class="space-y-4 text-xs">
                        @php
                            $familyMembers = $familyMembers ?? (auth()->user()->familyMembers ?? collect());
                        @endphp
                        <div class="space-y-1">
                            <label class="text-[11px] font-bold text-slate-700 flex items-center justify-between">
                                <span>Student / Candidate Full Name <span class="text-rose-500">*</span></span>
                                <span class="text-[10px] font-normal text-slate-400">(Select student from family members)</span>
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
                                <div class="p-3.5 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800 space-y-2">
                                    <p class="font-bold">No family members found on your profile.</p>
                                    <p class="text-[11px]">Please add your children / student under Family Members first to register for Inam Vitaran.</p>
                                    <a href="{{ route('member.family.index') }}" class="inline-block px-3.5 py-1.5 bg-amber-600 text-white font-bold rounded-lg text-[11px]">
                                        + Add Family Member
                                    </a>
                                </div>
                            @endif
                        </div>

                        <template x-if="selectedStudent !== ''">
                            <div class="space-y-4 pt-1">
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 flex items-center gap-1">
                                        Standard / Course / Degree (Education) <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="education" value="{{ old('education') }}" required
                                           placeholder="e.g. 10th Standard / 12th / B.Tech / B.Com / M.Com"
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all outline-none">
                                </div>

                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 flex items-center gap-1">
                                        School / College / Institute Name <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="school_college" value="{{ old('school_college') }}" required
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
                                            <input type="number" step="any" name="total_marks" x-model="totalMarks" @input="calcPercentage()" required
                                                   placeholder="e.g. 600"
                                                   class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs font-bold focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all outline-none">
                                        </div>

                                        <div class="space-y-1">
                                            <label class="text-[11px] font-bold text-slate-700 flex items-center gap-1">
                                                Obtained Marks <span class="text-rose-500">*</span>
                                            </label>
                                            <input type="number" step="any" name="received_marks" x-model="receivedMarks" @input="calcPercentage()" required
                                                   placeholder="e.g. 520"
                                                   class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs font-bold focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all outline-none">
                                        </div>

                                        <div class="space-y-1">
                                            <label class="text-[11px] font-bold text-primary-900 flex items-center gap-1">
                                                Percentage (%) <span class="text-rose-500">*</span>
                                            </label>
                                            <input type="text" name="percentage" x-model="percentage" required
                                                   placeholder="Auto-calculated (e.g. 86.67%)"
                                                   class="w-full px-3 py-2 bg-white border border-primary-300 rounded-lg text-xs font-extrabold text-primary-700 focus:border-primary-500 transition-all outline-none">
                                        </div>
                                    </div>
                                </div>

                                <!-- Marksheet File Upload Field -->
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 flex items-center justify-between">
                                        <span>Upload Marksheet File (Image / PDF) <span class="text-rose-500">*</span></span>
                                        <span class="text-[10px] text-slate-400 font-medium">Supported: JPG, PNG, PDF (Max 5MB)</span>
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <input type="file" name="marksheet_file" accept="image/*,.pdf" required
                                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-600 file:mr-3 file:py-1.5 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-primary-500 file:text-white hover:file:bg-primary-600 cursor-pointer">
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700">Special Achievement / Remarks (Optional)</label>
                                    <textarea name="remarks" rows="2"
                                              placeholder="Mention rank, board awards, sports honors or extra accomplishments"
                                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all outline-none">{{ old('remarks') }}</textarea>
                                </div>
                            </div>
                        </template>
                    </div>

                @elseif(($event->event_type ?? 'normal') === 'yuva_melo')
                    <!-- Yuva Melo Step Tabs Navigation -->
                    <div class="flex border-b border-slate-200/80 mb-5 gap-2 overflow-x-auto pb-1">
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
                    <div x-show="yuvaTab === 1" class="space-y-4 text-xs">
                        <div class="bg-slate-50/80 p-3 rounded-xl border border-slate-100 font-bold text-primary-800">
                            {{ __('messages.yuva_sec_1') }}
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700">{{ __('messages.surname') }} <span class="text-rose-500">*</span></label>
                                <input type="text" name="surname" value="{{ old('surname', $registration->form_data['surname'] ?? '') }}" required placeholder="Enter surname" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700">{{ __('messages.first_name') }} <span class="text-rose-500">*</span></label>
                                <input type="text" name="first_name" value="{{ old('first_name', $registration->form_data['first_name'] ?? '') }}" required placeholder="Enter first name" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700">{{ __('messages.gender') }} <span class="text-rose-500">*</span></label>
                                <select name="gender" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    <option value="Male" {{ old('gender', $registration->form_data['gender'] ?? '') === 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender', $registration->form_data['gender'] ?? '') === 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender', $registration->form_data['gender'] ?? '') === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3.5">
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700">{{ __('messages.birth_date') }} <span class="text-rose-500">*</span></label>
                                <input type="date" name="birth_date" value="{{ old('birth_date', $registration->form_data['birth_date'] ?? '') }}" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700">{{ __('messages.age') }} <span class="text-rose-500">*</span></label>
                                <input type="number" name="age" value="{{ old('age', $registration->form_data['age'] ?? '') }}" required placeholder="e.g. 25" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700">{{ __('messages.height') }}</label>
                                <input type="text" name="height" value="{{ old('height', $registration->form_data['height'] ?? '') }}" placeholder="e.g. 5'6\"" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700">{{ __('messages.weight') }}</label>
                                <input type="text" name="weight" value="{{ old('weight', $registration->form_data['weight'] ?? '') }}" placeholder="e.g. 60 kg" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700">{{ __('messages.state') }} <span class="text-rose-500">*</span></label>
                                <input type="text" name="state" value="{{ old('state', $registration->form_data['state'] ?? 'Gujarat') }}" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700">{{ __('messages.district') }} <span class="text-rose-500">*</span></label>
                                <input type="text" name="district" value="{{ old('district', $registration->form_data['district'] ?? '') }}" required placeholder="Enter district" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700">{{ __('messages.association') }}</label>
                                <input type="text" name="association" value="{{ old('association', $registration->form_data['association'] ?? '') }}" placeholder="Enter mandal/association" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[11px] font-bold text-slate-700">{{ __('messages.address') }} <span class="text-rose-500">*</span></label>
                            <textarea name="address" rows="2" required placeholder="Enter full address" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">{{ old('address', $registration->form_data['address'] ?? '') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700">{{ __('messages.mobile') }} <span class="text-rose-500">*</span></label>
                                <input type="text" name="mobile_no" value="{{ old('mobile_no', $registration->form_data['mobile_no'] ?? auth()->user()->memberProfile->phone ?? '') }}" required maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" placeholder="10-digit number" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700">{{ __('messages.whatsapp_no') }}</label>
                                <input type="text" name="whatsapp" value="{{ old('whatsapp', $registration->form_data['whatsapp'] ?? '') }}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" placeholder="10-digit whatsapp number" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700">{{ __('messages.qualification') }} <span class="text-rose-500">*</span></label>
                                <input type="text" name="qualification" value="{{ old('qualification', $registration->form_data['qualification'] ?? '') }}" required placeholder="e.g. Graduate / B.E." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700">{{ __('messages.occupation') }} <span class="text-rose-500">*</span></label>
                                <input type="text" name="occupation" value="{{ old('occupation', $registration->form_data['occupation'] ?? '') }}" required placeholder="e.g. Job / Business" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700">{{ __('messages.monthly_income') }}</label>
                                <input type="text" name="monthly_income" value="{{ old('monthly_income', $registration->form_data['monthly_income'] ?? '') }}" placeholder="e.g. Rs. 25,000" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[11px] font-bold text-slate-700">{{ __('messages.occupation_address') }}</label>
                            <input type="text" name="occupation_address" value="{{ old('occupation_address', $registration->form_data['occupation_address'] ?? '') }}" placeholder="Enter job/business address" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700">{{ __('messages.divorce_status') }} <span class="text-rose-500">*</span></label>
                                <select name="divorce" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    <option value="No" {{ old('divorce', $registration->form_data['divorce'] ?? '') === 'No' ? 'selected' : '' }}>No (ના)</option>
                                    <option value="Yes" {{ old('divorce', $registration->form_data['divorce'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes (હા)</option>
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700">{{ __('messages.special_need') }}</label>
                                <input type="text" name="special_need" value="{{ old('special_need', $registration->form_data['special_need'] ?? '') }}" placeholder="e.g. None / Physical Disability details" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                            </div>
                        </div>

                        <!-- Document Upload Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                            <div class="bg-slate-50/50 p-2.5 rounded-xl border border-slate-200/60 space-y-1">
                                <label class="text-[10px] font-bold text-slate-600 block">{{ __('messages.member_photo') }}</label>
                                <input type="file" name="member_photo" accept="image/*" class="w-full text-[10px] text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer">
                                @if(isset($registration->form_data['member_photo_url']))
                                    <div class="mt-1 flex items-center gap-1.5">
                                        <img src="{{ $registration->form_data['member_photo_url'] }}" class="w-8 h-8 object-cover rounded border">
                                        <span class="text-[9px] text-emerald-600 font-bold">Uploaded</span>
                                    </div>
                                @endif
                            </div>
                            <div class="bg-slate-50/50 p-2.5 rounded-xl border border-slate-200/60 space-y-1">
                                <label class="text-[10px] font-bold text-slate-600 block">{{ __('messages.aadhaar_photo') }}</label>
                                <input type="file" name="aadhaar_photo" accept="image/*" class="w-full text-[10px] text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer">
                                @if(isset($registration->form_data['aadhaar_photo_url']))
                                    <div class="mt-1 flex items-center gap-1.5">
                                        <img src="{{ $registration->form_data['aadhaar_photo_url'] }}" class="w-8 h-8 object-cover rounded border">
                                        <span class="text-[9px] text-emerald-600 font-bold">Uploaded</span>
                                    </div>
                                @endif
                            </div>
                            <div class="bg-slate-50/50 p-2.5 rounded-xl border border-slate-200/60 space-y-1">
                                <label class="text-[10px] font-bold text-slate-600 block">{{ __('messages.selfie') }}</label>
                                <input type="file" name="selfie" accept="image/*" class="w-full text-[10px] text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer">
                                @if(isset($registration->form_data['selfie_url']))
                                    <div class="mt-1 flex items-center gap-1.5">
                                        <img src="{{ $registration->form_data['selfie_url'] }}" class="w-8 h-8 object-cover rounded border">
                                        <span class="text-[9px] text-emerald-600 font-bold">Uploaded</span>
                                    </div>
                                @endif
                            </div>
                            <div class="bg-slate-50/50 p-2.5 rounded-xl border border-slate-200/60 space-y-1">
                                <label class="text-[10px] font-bold text-slate-600 block">{{ __('messages.whatsapp_image') }}</label>
                                <input type="file" name="whatsapp_image" accept="image/*" class="w-full text-[10px] text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer">
                                @if(isset($registration->form_data['whatsapp_image_url']))
                                    <div class="mt-1 flex items-center gap-1.5">
                                        <img src="{{ $registration->form_data['whatsapp_image_url'] }}" class="w-8 h-8 object-cover rounded border">
                                        <span class="text-[9px] text-emerald-600 font-bold">Uploaded</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Tab 1 Next Button -->
                        <div class="flex justify-end pt-2">
                            <button type="button" @click="yuvaTab = 2" class="px-5 py-2 bg-primary-500 text-white hover:bg-primary-600 font-bold text-xs rounded-xl shadow-xs transition-colors cursor-pointer">
                                {!! __('messages.next_step') !!}
                            </button>
                        </div>
                    </div>

                    <!-- STEP 2: Father & Family Info -->
                    <div x-show="yuvaTab === 2" class="space-y-4 text-xs" x-cloak>
                        <div class="bg-slate-50/80 p-3 rounded-xl border border-slate-100 font-bold text-primary-800">
                            {{ __('messages.yuva_sec_2') }}
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700">{{ __('messages.father_name') }} <span class="text-rose-500">*</span></label>
                                <input type="text" name="father_name" value="{{ old('father_name', $registration->form_data['father_name'] ?? '') }}" required placeholder="Enter father's full name" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700">{{ __('messages.grandfather_name') }} <span class="text-rose-500">*</span></label>
                                <input type="text" name="grandfather_name" value="{{ old('grandfather_name', $registration->form_data['grandfather_name'] ?? '') }}" required placeholder="Enter grandfather's full name" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700">{{ __('messages.father_age') }}</label>
                                <input type="number" name="father_age" value="{{ old('father_age', $registration->form_data['father_age'] ?? '') }}" placeholder="e.g. 52" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700">{{ __('messages.father_occupation') }}</label>
                                <input type="text" name="father_occupation" value="{{ old('father_occupation', $registration->form_data['father_occupation'] ?? '') }}" placeholder="e.g. Farming / Business" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700">{{ __('messages.father_income') }}</label>
                                <input type="text" name="father_income" value="{{ old('father_income', $registration->form_data['father_income'] ?? '') }}" placeholder="Annual or Monthly Income" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700">{{ __('messages.mother_name') }} <span class="text-rose-500">*</span></label>
                                <input type="text" name="mother_name" value="{{ old('mother_name', $registration->form_data['mother_name'] ?? '') }}" required placeholder="Enter mother's name" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700">{{ __('messages.mother_occupation') }}</label>
                                <input type="text" name="mother_occupation" value="{{ old('mother_occupation', $registration->form_data['mother_occupation'] ?? 'Housewife') }}" placeholder="e.g. Housewife" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700">{{ __('messages.native_place') }} <span class="text-rose-500">*</span></label>
                                <input type="text" name="native_place" value="{{ old('native_place', $registration->form_data['native_place'] ?? '') }}" required placeholder="Enter native village/city" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                            </div>
                        </div>

                        <!-- Brother and Sister Details -->
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80 space-y-3">
                            <h4 class="font-extrabold text-[11px] text-slate-600 uppercase tracking-wider">{{ __('messages.siblings_details') }}</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700">{{ __('messages.elder_brother') }}</label>
                                    <input type="text" name="elder_brother" value="{{ old('elder_brother', $registration->form_data['elder_brother'] ?? '') }}" placeholder="e.g. 1 Brother (Married)" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs font-semibold focus:border-primary-500 outline-none">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700">{{ __('messages.retired') }}</label>
                                    <input type="text" name="retired" value="{{ old('retired', $registration->form_data['retired'] ?? '') }}" placeholder="Retired members details" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs font-semibold focus:border-primary-500 outline-none">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700">{{ __('messages.younger_brother') }}</label>
                                    <input type="text" name="younger_brother" value="{{ old('younger_brother', $registration->form_data['younger_brother'] ?? '') }}" placeholder="e.g. 1 Brother" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs font-semibold focus:border-primary-500 outline-none">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700">{{ __('messages.younger_brother_married') }}</label>
                                    <select name="younger_brother_married" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs font-semibold focus:border-primary-500 outline-none">
                                        <option value="" disabled selected>Select status</option>
                                        <option value="No" {{ old('younger_brother_married', $registration->form_data['younger_brother_married'] ?? '') === 'No' ? 'selected' : '' }}>No (ના)</option>
                                        <option value="Yes" {{ old('younger_brother_married', $registration->form_data['younger_brother_married'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes (હા)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700">{{ __('messages.elder_sister') }}</label>
                                    <input type="text" name="elder_sister" value="{{ old('elder_sister', $registration->form_data['elder_sister'] ?? '') }}" placeholder="e.g. 2 Sisters" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs font-semibold focus:border-primary-500 outline-none">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700">{{ __('messages.elder_sister_married') }}</label>
                                    <select name="elder_sister_married" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs font-semibold focus:border-primary-500 outline-none">
                                        <option value="" disabled selected>Select status</option>
                                        <option value="No" {{ old('elder_sister_married', $registration->form_data['elder_sister_married'] ?? '') === 'No' ? 'selected' : '' }}>No (ના)</option>
                                        <option value="Yes" {{ old('elder_sister_married', $registration->form_data['elder_sister_married'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes (હા)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700">{{ __('messages.younger_sister') }}</label>
                                    <input type="text" name="younger_sister" value="{{ old('younger_sister', $registration->form_data['younger_sister'] ?? '') }}" placeholder="e.g. 1 Sister" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs font-semibold focus:border-primary-500 outline-none">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700">{{ __('messages.younger_sister_married') }}</label>
                                    <select name="younger_sister_married" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs font-semibold focus:border-primary-500 outline-none">
                                        <option value="" disabled selected>Select status</option>
                                        <option value="No" {{ old('younger_sister_married', $registration->form_data['younger_sister_married'] ?? '') === 'No' ? 'selected' : '' }}>No (ના)</option>
                                        <option value="Yes" {{ old('younger_sister_married', $registration->form_data['younger_sister_married'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes (હા)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Family Business, Property, Vehicle Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700">{{ __('messages.business_details') }}</label>
                                <input type="text" name="business" value="{{ old('business', $registration->form_data['business'] ?? '') }}" placeholder="Family business info" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700">{{ __('messages.house_type') }}</label>
                                <input type="text" name="house" value="{{ old('house', $registration->form_data['house'] ?? '') }}" placeholder="e.g. Flat / Tenement" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700">{{ __('messages.own_house') }}</label>
                                <select name="own_house" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                                    <option value="Yes" {{ old('own_house', $registration->form_data['own_house'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes (હા)</option>
                                    <option value="No" {{ old('own_house', $registration->form_data['own_house'] ?? '') === 'No' ? 'selected' : '' }}>No (ના)</option>
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700">{{ __('messages.vehicle_details') }}</label>
                                <input type="text" name="vehicle" value="{{ old('vehicle', $registration->form_data['vehicle'] ?? '') }}" placeholder="e.g. Two Wheeler / Car model" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                            </div>
                        </div>

                        <!-- Tab 2 Next/Prev Buttons -->
                        <div class="flex justify-between pt-2">
                            <button type="button" @click="yuvaTab = 1" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 font-bold text-xs rounded-xl transition-colors cursor-pointer">
                                {!! __('messages.prev_step') !!}
                            </button>
                            <button type="button" @click="yuvaTab = 3" class="px-5 py-2 bg-primary-500 text-white hover:bg-primary-600 font-bold text-xs rounded-xl shadow-xs transition-colors cursor-pointer">
                                {!! __('messages.next_step') !!}
                            </button>
                        </div>
                    </div>

                    <!-- STEP 3: Maternal Info -->
                    <div x-show="yuvaTab === 3" class="space-y-4 text-xs" x-cloak>
                        <div class="bg-slate-50/80 p-3 rounded-xl border border-slate-100 font-bold text-primary-800">
                            {{ __('messages.yuva_sec_3') }}
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700">{{ __('messages.maternal_uncle_name') }} <span class="text-rose-500">*</span></label>
                                <input type="text" name="maternal_uncle_name" value="{{ old('maternal_uncle_name', $registration->form_data['maternal_uncle_name'] ?? '') }}" required placeholder="Enter maternal uncle's name" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700">{{ __('messages.maternal_grandfather_name') }} <span class="text-rose-500">*</span></label>
                                <input type="text" name="maternal_grandfather_name" value="{{ old('maternal_grandfather_name', $registration->form_data['maternal_grandfather_name'] ?? '') }}" required placeholder="Enter maternal grandfather's name" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 outline-none">
                            </div>
                        </div>

                        <!-- Tab 3 Prev Button -->
                        <div class="flex justify-start pt-2">
                            <button type="button" @click="yuvaTab = 2" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 font-bold text-xs rounded-xl transition-colors cursor-pointer">
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
                            <input type="text" name="full_name" value="{{ old('full_name', auth()->user()->name) }}" required
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all outline-none">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[11px] font-bold text-slate-700 flex items-center gap-1">
                                Contact Number <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="contact_number" value="{{ old('contact_number', auth()->user()->memberProfile->phone ?? '') }}" required
                                   maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                                   placeholder="Enter 10-digit mobile number"
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all outline-none">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[11px] font-bold text-slate-700">Special Notes / Remarks (Optional)</label>
                            <textarea name="remarks" rows="2" placeholder="Any special seating or assistance requirements"
                                      class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all outline-none">{{ old('remarks') }}</textarea>
                        </div>
                    </div>
                @endif

                <!-- ACTION BUTTONS -->
                <div x-show="(('{{ $event->event_type ?? 'normal' }}' !== 'inam_vitaran') || selectedStudent !== '') && ('{{ $event->event_type ?? 'normal' }}' !== 'yuva_melo' || yuvaTab === 3)" class="pt-3 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('member.events.index') }}" 
                       class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2.5 bg-primary-500 hover:bg-primary-600 text-white font-extrabold text-xs rounded-xl shadow-md hover:shadow-lg transition-all transform active:scale-95 flex items-center gap-2 uppercase tracking-wider cursor-pointer">
                        <span>Submit Registration</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if(isset($registrations) && $registrations->count() > 0)
        <!-- COMPACT CARD-STYLE SUBMITTED DETAILS LIST (3 CARDS PER ROW) -->
        <div class="space-y-3 pt-2">
            <div class="flex items-center justify-between px-1">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider">
                        Submitted Registration Details ({{ $registrations->count() }})
                    </h3>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($registrations as $index => $reg)
                    @if(!empty($reg->form_data))
                        <div class="bg-white border border-slate-200/90 rounded-xl p-4 space-y-3 shadow-xs hover:shadow-md transition-shadow">
                            <!-- Card Header: #Index & Candidate Name -->
                            <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                                <div class="flex items-center gap-1.5 min-w-0">
                                    <span class="text-xs font-black text-slate-400">#{{ $registrations->count() - $index }}</span>
                                    <h4 class="text-xs font-black text-slate-900 truncate">
                                        {{ $reg->form_data['student_name'] ?? $reg->form_data['full_name'] ?? 'Registration' }}
                                    </h4>
                                </div>
                            </div>

                            <!-- Card Body: Clean Specified Fields Only -->
                            <div class="space-y-2.5 text-[11px] text-slate-600">
                                @php $fd = $reg->form_data; @endphp

                                @if(($event->event_type ?? 'normal') === 'inam_vitaran')
                                    <div>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Student Name</span>
                                        <span class="font-extrabold text-slate-900 text-xs block">{{ $fd['student_name'] ?? '-' }}</span>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Education</span>
                                            <span class="font-bold text-slate-800">{{ $fd['education'] ?? '-' }}</span>
                                        </div>
                                        <div>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">School / College</span>
                                            <span class="font-bold text-slate-800 truncate block" title="{{ $fd['school_college'] ?? '-' }}">{{ $fd['school_college'] ?? '-' }}</span>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-3 gap-1.5 bg-slate-50/90 p-2 rounded-lg border border-slate-100">
                                        <div>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase block">Total Marks</span>
                                            <span class="font-bold text-slate-800">{{ $fd['total_marks'] ?? '-' }}</span>
                                        </div>
                                        <div>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase block">Obtained</span>
                                            <span class="font-bold text-slate-800">{{ $fd['received_marks'] ?? '-' }}</span>
                                        </div>
                                        <div>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase block">Percentage</span>
                                            <span class="font-black text-amber-700 bg-amber-50 px-1 py-0.5 rounded border border-amber-200 inline-block text-[10px]">{{ $fd['percentage'] ?? '-' }}</span>
                                        </div>
                                    </div>

                                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                                        <div>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Marksheet File</span>
                                            @if(!empty($fd['marksheet_url']))
                                                <a href="{{ $fd['marksheet_url'] }}" target="_blank" class="inline-flex items-center gap-1 font-bold text-primary-600 hover:text-primary-700 underline text-[11px]">
                                                    View File ↗
                                                </a>
                                            @else
                                                <span class="text-slate-400">-</span>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Submission Date</span>
                                            <span class="font-bold text-slate-800 text-[10px]">{{ $fd['submission_date'] ?? ($reg->created_at ? $reg->created_at->format('d-M-Y') : '-') }}</span>
                                        </div>
                                    </div>

                                @elseif(($event->event_type ?? 'normal') === 'yuva_melo')
                                    <div>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Full Name</span>
                                        <span class="font-extrabold text-slate-900 text-xs block">{{ $fd['full_name'] ?? '-' }}</span>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Age</span>
                                            <span class="font-bold text-slate-800">{{ $fd['age'] ?? '-' }} Years</span>
                                        </div>
                                        <div>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Education / Occupation</span>
                                            <span class="font-bold text-slate-800 truncate block">{{ $fd['education_occupation'] ?? '-' }}</span>
                                        </div>
                                    </div>

                                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                                        @if(!empty($fd['contact_number']))
                                            <div>
                                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Contact Number</span>
                                                <span class="font-bold text-slate-800">{{ $fd['contact_number'] }}</span>
                                            </div>
                                        @endif
                                        <div class="text-right">
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Submission Date</span>
                                            <span class="font-bold text-slate-800 text-[10px]">{{ $fd['submission_date'] ?? ($reg->created_at ? $reg->created_at->format('d-M-Y') : '-') }}</span>
                                        </div>
                                    </div>

                                @else
                                    <div>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Participant Name</span>
                                        <span class="font-extrabold text-slate-900 text-xs block">{{ $fd['full_name'] ?? '-' }}</span>
                                    </div>

                                    @if(!empty($fd['remarks']))
                                        <div>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Remarks</span>
                                            <span class="font-semibold text-slate-700 truncate block">{{ $fd['remarks'] }}</span>
                                        </div>
                                    @endif

                                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                                        @if(!empty($fd['contact_number']))
                                            <div>
                                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Contact Number</span>
                                                <span class="font-bold text-slate-800">{{ $fd['contact_number'] }}</span>
                                            </div>
                                        @endif
                                        <div class="text-right">
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Submission Date</span>
                                            <span class="font-bold text-slate-800 text-[10px]">{{ $fd['submission_date'] ?? ($reg->created_at ? $reg->created_at->format('d-M-Y') : '-') }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
