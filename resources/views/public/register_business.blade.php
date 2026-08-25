@extends('layouts.public')

@section('content')
@include('partials.page_header', [
    'title' => __('messages.register_your_business'),
    'subtitle' => __('messages.promote_your_work'),
    'breadcrumb' => __('messages.business_registration_breadcrumb')
])

<!-- Form Body -->
<section class="py-6 bg-slate-50/50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white border border-slate-200/60 rounded-2xl p-5 md:p-6 shadow-xs">
            
            @if(isset($existingBusiness) && $existingBusiness)
                <div class="mb-5 p-4 bg-amber-50 border border-amber-200 text-amber-900 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-xs">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2 font-black text-xs text-amber-900">
                            <span>⚠️</span>
                            <span>{{ __('messages.business_limit_exceeded') }}</span>
                        </div>
                        <p class="text-xs text-amber-800 font-medium">
                            {{ __('messages.business_limit_desc', ['name' => $existingBusiness->business_name, 'status' => strtoupper($existingBusiness->status)]) }}
                        </p>
                    </div>
                    <a href="{{ route('member.businesses.my') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl transition-all shrink-0">
                        <span>{{ __('messages.my_businesses') }}</span> &rarr;
                    </a>
                </div>
            @endif

            <!-- Validation errors -->
            @if ($errors->any())
                <div class="mb-4 p-3 bg-rose-50 border border-rose-100 text-rose-800 rounded-xl">
                    <p class="text-xs font-bold mb-2">{{ __('messages.please_correct_errors') }}</p>
                    <ul class="list-disc pl-4 text-[11px] font-medium space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.business.submit') }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-x-4 gap-y-4">
                @csrf
                
                <!-- Row 1: Member ID & Business Name -->
                <div class="space-y-1" 
                     x-data="{ 
                         memberId: '{{ old('member_id') }}', 
                         memberStatus: '', 
                         isFound: null, 
                         loading: false,
                         checkMember() {
                             if(!this.memberId || this.memberId.trim() === '') {
                                 this.memberStatus = '';
                                 this.isFound = null;
                                 return;
                             }
                             this.loading = true;
                             fetch('{{ route('api.check_member_id') }}?member_id=' + encodeURIComponent(this.memberId))
                                 .then(res => res.json())
                                 .then(data => {
                                     this.loading = false;
                                     this.isFound = data.found;
                                     this.memberStatus = data.message;
                                 })
                                 .catch(() => { this.loading = false; });
                         }
                     }"
                     x-init="if(memberId) checkMember()">
                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.member_id_label') }}</label>
                    <div class="relative">
                        <input type="text" name="member_id" x-model="memberId" @input.debounce.400ms="checkMember()" placeholder="{{ __('messages.member_id_placeholder') }}" class="w-full text-sm font-semibold px-3 py-2 bg-slate-50 border rounded-lg focus:bg-white focus:ring-0 transition-colors" :class="isFound === true ? 'border-emerald-400' : (isFound === false ? 'border-rose-400' : 'border-slate-200')">
                        <span x-show="loading" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-bold">{{ __('messages.checking') }}</span>
                    </div>
                    <div x-show="memberStatus" class="mt-0.5 text-xs font-bold" :class="isFound ? 'text-emerald-700' : 'text-rose-600'" x-text="memberStatus"></div>
                    @error('member_id')
                        <p class="text-xs text-rose-600 font-bold mt-0.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1 md:col-span-2">
                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.firm_name_label') }} <span class="text-rose-500">*</span></label>
                    <input type="text" name="business_name" required value="{{ old('business_name') }}" placeholder="{{ __('messages.firm_name_placeholder') }}" class="w-full text-sm font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-0">
                </div>

                <!-- Row 2: Owner, Category, Area -->
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.contact_person_label') }} <span class="text-rose-500">*</span></label>
                    <input type="text" name="owner_name" required value="{{ old('owner_name') }}" placeholder="{{ __('messages.contact_person_placeholder') }}" class="w-full text-sm font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-0">
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.business_category_label') }}</label>
                    <select name="category_id" class="w-full text-sm font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-0">
                        <option value="">{{ __('messages.select_category') }}</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.area_label') }} <span class="text-rose-500">*</span></label>
                    <select name="area_id" required class="w-full text-sm font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-0">
                        <option value="">{{ __('messages.select_area') }}</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>{{ $area->name }}{{ $area->pincode ? ' (' . $area->pincode . ')' : '' }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Row 3: Contacts with Phone & WhatsApp Toggle Switch -->
                <div class="md:col-span-3 bg-slate-50/80 border border-slate-200/80 rounded-xl p-3.5 space-y-3" 
                     x-data="{ 
                         sameWhatsapp: {{ old('whatsapp') && old('whatsapp') !== old('phone') ? 'false' : 'true' }}, 
                         phoneNum: '{{ old('phone') }}', 
                         whatsappNum: '{{ old('whatsapp') }}' 
                     }">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 border-b border-slate-200/60 pb-2.5">
                        <div class="flex items-center gap-2">
                            <span class="text-xs sm:text-sm font-bold text-slate-800 uppercase tracking-wide">{{ __('messages.contact_details_sec') }}</span>
                            <span class="text-xs font-bold text-slate-500">({{ __('messages.phone_whatsapp_label') }})</span>
                        </div>

                        <!-- Toggle Switch UI -->
                        <div class="flex items-center gap-2 select-none cursor-pointer" @click="sameWhatsapp = !sameWhatsapp; if(sameWhatsapp) whatsappNum = phoneNum">
                            <span class="text-xs font-bold text-slate-700">{{ __('messages.whatsapp_same_as_phone') }}</span>
                            <button type="button" 
                                    :class="sameWhatsapp ? 'bg-emerald-500' : 'bg-slate-300'" 
                                    class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none">
                                <span :class="sameWhatsapp ? 'translate-x-4' : 'translate-x-0'" 
                                      class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow-md ring-0 transition duration-200 ease-in-out"></span>
                            </button>
                            <span x-text="sameWhatsapp ? '{{ __('messages.yes') }}' : '{{ __('messages.no') }}'" :class="sameWhatsapp ? 'text-emerald-700 font-extrabold' : 'text-slate-500 font-bold'" class="text-xs w-6"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        <!-- Phone Field -->
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.phone_mobile') }} <span class="text-rose-500">*</span></label>
                            <input type="text" name="phone" x-model="phoneNum" @input="if(sameWhatsapp) whatsappNum = phoneNum" required minlength="10" maxlength="10" pattern="[0-9]{10}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" placeholder="{{ __('messages.ten_digits') }}" class="w-full text-sm font-semibold px-3 py-2 bg-white border border-slate-200 rounded-lg focus:border-primary-500 focus:ring-0">
                        </div>

                        <!-- WhatsApp Field -->
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                                {{ __('messages.whatsapp_number') }} 
                                <template x-if="sameWhatsapp">
                                    <span class="text-emerald-600 font-bold text-xs lowercase">({{ __('messages.same_as_phone') }})</span>
                                </template>
                            </label>
                            <input type="text" name="whatsapp" x-model="whatsappNum" :readonly="sameWhatsapp" :class="sameWhatsapp ? 'bg-slate-100 text-slate-500 cursor-not-allowed border-slate-200' : 'bg-white border-emerald-400 focus:border-emerald-500'" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" placeholder="{{ __('messages.ten_digit_whatsapp_placeholder') }}" class="w-full text-sm font-semibold px-3 py-2 rounded-lg focus:ring-0">
                        </div>

                        <!-- Email Field -->
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.email_address_label') }}</label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="{{ __('messages.email_placeholder') }}" class="w-full text-sm font-semibold px-3 py-2 bg-white border border-slate-200 rounded-lg focus:border-primary-500 focus:ring-0">
                        </div>
                    </div>
                </div>

                <!-- Row 4: Links -->
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.website_url_label') }}</label>
                    <input type="url" name="website" value="{{ old('website') }}" placeholder="https://example.com" class="w-full text-sm font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-0">
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.facebook_link_label') }}</label>
                    <input type="text" name="facebook" value="{{ old('facebook') }}" placeholder="https://facebook.com/username" class="w-full text-sm font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-0">
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.instagram_link_label') }}</label>
                    <input type="text" name="instagram" value="{{ old('instagram') }}" placeholder="https://instagram.com/username" class="w-full text-sm font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-0">
                </div>

                <!-- Row 5: Links Continued & File -->
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.youtube_link_label') }}</label>
                    <input type="text" name="youtube" value="{{ old('youtube') }}" placeholder="https://youtube.com/@username" class="w-full text-sm font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-0">
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.linkedin_link_label') }}</label>
                    <input type="text" name="linkedin" value="{{ old('linkedin') }}" placeholder="https://linkedin.com/in/username" class="w-full text-sm font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-0">
                </div>

                <div class="space-y-1" x-data="{ logoPreview: null }">
                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.business_logo_label') }} <span class="text-rose-500">*</span></label>
                    <div class="flex items-center gap-3">
                        <!-- Small thumbnail preview -->
                        <div class="relative w-11 h-11 rounded-lg border border-slate-200 bg-slate-100 overflow-hidden shrink-0 flex items-center justify-center shadow-2xs">
                            <template x-if="logoPreview">
                                <img :src="logoPreview" alt="Logo Preview" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!logoPreview">
                                <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </template>
                        </div>
                        <input type="file" name="logo" required accept="image/*" 
                               @change="const file = $event.target.files[0]; if (file) { const r = new FileReader(); r.onload = e => logoPreview = e.target.result; r.readAsDataURL(file); }"
                               class="text-xs font-semibold block w-full text-slate-600 bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer">
                    </div>
                </div>

                <!-- Row 6: Showcase Photos & Description -->
                <div class="space-y-2 md:col-span-3 border border-slate-100 rounded-xl p-3.5 bg-slate-50/50" x-data="multiShowcaseUploader()">
                    <div class="flex items-center justify-between gap-3 flex-wrap">
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">
                            {{ __('messages.showcase_photos_label') }} <span class="text-slate-500 font-normal">({{ __('messages.select_multiple_append') }})</span>
                        </label>
                        <div class="flex items-center gap-2">
                            <button type="button" x-show="files.length < 6" @click="$refs.hiddenFileInput.click()" 
                                class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs rounded-lg shadow-2xs transition-all flex items-center gap-1 cursor-pointer" x-cloak>
                                <span>{{ __('messages.select_files') }}</span>
                            </button>
                            <button type="button" @click="clearAll()" x-show="files.length > 0" x-cloak
                                class="px-3 py-1.5 bg-rose-100 hover:bg-rose-200 text-rose-700 font-bold text-xs rounded-lg transition-all cursor-pointer">
                                <span>{{ __('messages.clear') }}</span>
                            </button>
                        </div>
                    </div>

                    <input type="file" x-ref="hiddenFileInput" name="gallery[]" accept="image/*" multiple @change="selectFiles($event)" class="hidden">

                    <div 
                        @dragover.prevent="isDragging = true"
                        @dragleave.prevent="isDragging = false"
                        @drop.prevent="dropFiles($event)"
                        :class="isDragging ? 'border-blue-500 bg-blue-50/60' : 'border-slate-300 bg-white'"
                        class="border border-dashed rounded-xl p-4 text-center transition-all cursor-pointer"
                        @click="$refs.hiddenFileInput.click()">
                        
                        <div x-show="files.length === 0" class="py-2 space-y-1">
                            <p class="text-xs font-bold text-slate-700">{{ __('messages.drop_files_here_or') }} <span class="text-blue-600 underline">{{ __('messages.browse') }}</span></p>
                            <p class="text-xs text-slate-400 font-medium">{{ __('messages.drop_files_subtitle') }}</p>
                        </div>

                        <div x-show="files.length > 0" class="space-y-2" @click.stop x-cloak>
                            <div class="flex items-center justify-between text-xs font-bold text-slate-600 px-1 border-b border-slate-100 pb-1.5">
                                <span>{{ __('messages.selected_showcase_photos') }}</span>
                                <span class="text-blue-600 font-extrabold bg-blue-50 px-2.5 py-0.5 rounded-md border border-blue-200/80" x-text="files.length + '/6 {{ __('messages.selected') }}'"></span>
                            </div>
                            <div class="flex flex-wrap items-center gap-3 pt-1">
                                <template x-for="(f, idx) in files" :key="f.id">
                                    <div class="relative group w-24 h-24 rounded-xl overflow-hidden border border-slate-200 bg-slate-100 shadow-2xs shrink-0">
                                        <img :src="f.url" class="w-full h-full object-cover">
                                        
                                        <!-- Remove Button -->
                                        <button type="button" @click.stop="removeFile(idx)" 
                                            class="absolute top-1 right-1 w-5 h-5 rounded-full bg-rose-600 text-white flex items-center justify-center shadow-md hover:bg-rose-700 transition-colors text-xs font-black" title="Remove photo">
                                            ✕
                                        </button>
                                        
                                        <!-- File Name Bar -->
                                        <div class="absolute bottom-0 inset-x-0 bg-slate-900/80 text-white p-0.5 text-[9px] truncate font-semibold backdrop-blur-xs text-center">
                                            <span x-text="f.name"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-1 md:col-span-3">
                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.business_desc_label') }} <span class="text-slate-400 font-normal">{{ __('messages.optional') }}</span></label>
                    <textarea name="description" rows="2" placeholder="{{ __('messages.business_desc_placeholder') }}" class="w-full text-sm font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-0">{{ old('description') }}</textarea>
                </div>

                <!-- Row 7: Address -->
                <div class="space-y-1 md:col-span-3">
                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.office_address_label') }} <span class="text-rose-500">*</span></label>
                    <textarea name="address" rows="2" required placeholder="{{ __('messages.office_address_placeholder') }}" class="w-full text-sm font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-0">{{ old('address') }}</textarea>
                </div>

                <!-- Payment Summary & Submit Button -->
                <div class="md:col-span-3 space-y-3 pt-2">
                    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                    @if(($businessFee ?? 500) > 0)
                        <div class="bg-primary-50/90 border border-primary-200/80 rounded-xl p-3.5 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="text-lg">💳</span>
                                <div>
                                    <h4 class="text-xs sm:text-sm font-bold text-primary-900">{{ __('messages.business_listing_fee') }}</h4>
                                    <p class="text-xs font-medium text-primary-700">{{ __('messages.payment_processed_securely') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-base font-black text-primary-700">₹{{ number_format($businessFee ?? 500) }}</span>
                                <span class="block text-xs font-extrabold text-slate-400">{{ __('messages.one_time_fee') }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-end border-t border-slate-100 pt-3">
                        <button type="submit" id="submitBusinessBtn" class="inline-flex items-center justify-center px-6 py-3 bg-primary-600 hover:bg-primary-700 font-extrabold text-xs sm:text-sm text-white uppercase tracking-wider rounded-xl shadow-xs transition-all active:scale-95 cursor-pointer">
                            <span>{{ __('messages.pay_and_register_business', ['amount' => number_format($businessFee ?? 500)]) }}</span> &rarr;
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
function multiShowcaseUploader() {
    return {
        files: [],
        isDragging: false,
        maxFiles: 6,
        showLimitModal: false,
        
        selectFiles(e) {
            if (e.target.files && e.target.files.length > 0) {
                this.addFiles(Array.from(e.target.files));
            }
        },
        
        dropFiles(e) {
            this.isDragging = false;
            if (e.dataTransfer && e.dataTransfer.files) {
                this.addFiles(Array.from(e.dataTransfer.files));
            }
        },
        
        addFiles(newFiles) {
            let overflow = false;
            newFiles.forEach(file => {
                if (file.type.startsWith('image/')) {
                    const exists = this.files.some(f => f.name === file.name && f.file.size === file.size);
                    if (!exists) {
                        if (this.files.length < this.maxFiles) {
                            this.files.push({
                                id: Math.random().toString(36).substring(2, 9),
                                file: file,
                                name: file.name,
                                size: (file.size / (1024 * 1024)).toFixed(2) + ' MB',
                                url: URL.createObjectURL(file)
                            });
                        } else {
                            overflow = true;
                        }
                    }
                }
            });

            if (overflow) {
                this.showLimitModal = true;
            }

            this.syncInput();
        },
        
        removeFile(index) {
            if (this.files[index]) {
                URL.revokeObjectURL(this.files[index].url);
                this.files.splice(index, 1);
                this.syncInput();
            }
        },
        
        clearAll() {
            this.files.forEach(f => URL.revokeObjectURL(f.url));
            this.files = [];
            this.syncInput();
        },
        
        syncInput() {
            const input = this.$refs.hiddenFileInput;
            if (input) {
                const dt = new DataTransfer();
                this.files.forEach(f => dt.items.add(f.file));
                input.files = dt.files;
            }
        }
    };
}
</script>

@if(($businessFee ?? 500) > 0)
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form[action="{{ route('register.business.submit') }}"]');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        const paymentIdInput = document.getElementById('razorpay_payment_id');
        if (paymentIdInput && paymentIdInput.value) {
            return true; // Already paid, allow normal submit
        }

        e.preventDefault();

        // Trigger HTML5 validation check
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const razorpayKey = "{{ $razorpayKeyId ?? '' }}";
        const feeAmountPaise = {{ ($businessFee ?? 500) * 100 }};
        const businessName = form.querySelector('[name="business_name"]')?.value || '';
        const ownerName = form.querySelector('[name="owner_name"]')?.value || '';
        const email = form.querySelector('[name="email"]')?.value || '';
        const phone = form.querySelector('[name="phone"]')?.value.trim() || '';
        if (phone.length !== 10 || !/^\d{10}$/.test(phone)) {
            alert("{{ __('messages.mobile_10_digits_required') ?? 'મોબાઈલ નંબર બરાબર ૧૦ અંકનો હોવો જરૂરી છે.' }}");
            form.querySelector('[name="phone"]')?.focus();
            return;
        }

        const options = {
            "key": razorpayKey || "rzp_test_key",
            "amount": feeAmountPaise,
            "currency": "INR",
            "name": "{{ config('app.name', 'Satwara Community') }}",
            "description": "Business Registration Fee - " + businessName,
            "handler": function (response) {
                paymentIdInput.value = response.razorpay_payment_id;
                form.submit();
            },
            "prefill": {
                "name": ownerName,
                "email": email,
                "contact": phone
            },
            "theme": {
                "color": "#2563EB"
            }
        };

        if (window.Razorpay) {
            const rzp = new Razorpay(options);
            rzp.open();
        } else {
            alert('Razorpay Payment Gateway failed to load. Submitting application...');
            form.submit();
        }
    });
});
</script>
@endif
@endsection
