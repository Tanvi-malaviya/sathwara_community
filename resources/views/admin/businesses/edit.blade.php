@extends('layouts.admin')

@section('page_title', __('messages.edit_business_entry'))

@section('content')
<div class="max-w-6xl bg-white border border-slate-100 rounded-xl p-5 shadow-sm space-y-4">

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="p-3 bg-rose-50 border border-rose-100 text-rose-800 rounded-xl">
            <p class="text-xs font-bold mb-1">{{ __('messages.please_correct_errors') }}</p>
            <ul class="list-disc pl-4 text-[11px] font-medium space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php 
        $existingCount = is_array($business->gallery_images) ? count($business->gallery_images) : 0; 
    @endphp

    <form method="POST" action="{{ route('admin.businesses.update', $business->id) }}" class="space-y-4" enctype="multipart/form-data">
        @csrf

        <!-- SECTION 1: BASIC INFORMATION -->
        <div>
            <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2.5 pb-1 border-b border-slate-100">
                1. {{ __('messages.basic_information_sec') }}
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.member_id_label') }} <span class="text-slate-400 font-normal">({{ __('messages.optional') }})</span></label>
                    <input type="text" name="member_id" value="{{ old('member_id', $business->member_id) }}" placeholder="e.g. #00005 ({{ __('messages.optional') }})" 
                           class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.firm_name_label') }} <span class="text-rose-500">*</span></label>
                    <input type="text" name="business_name" value="{{ old('business_name', $business->business_name) }}" required placeholder="{{ __('messages.firm_name_placeholder') }}" 
                           class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.contact_person_label') }} <span class="text-rose-500">*</span></label>
                    <input type="text" name="owner_name" value="{{ old('owner_name', $business->owner_name) }}" required placeholder="{{ __('messages.contact_person_placeholder') }}" 
                           class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.status_label') }} <span class="text-rose-500">*</span></label>
                    <select name="status" required class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                        <option value="pending" {{ old('status', $business->status) == 'pending' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                        <option value="approved" {{ old('status', $business->status) == 'approved' ? 'selected' : '' }}>{{ __('messages.approved') }}</option>
                        <option value="rejected" {{ old('status', $business->status) == 'rejected' ? 'selected' : '' }}>{{ __('messages.rejected') }}</option>
                    </select>
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.membership_status') }} <span class="text-rose-500">*</span></label>
                    <select name="membership_status" required class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                        <option value="active" {{ old('membership_status', $business->membership_status) == 'active' ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                        <option value="inactive" {{ old('membership_status', $business->membership_status) == 'inactive' ? 'selected' : '' }}>{{ __('messages.inactive') }}</option>
                    </select>
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.subscription_purchase_date') }}</label>
                    <input type="date" name="approved_at" value="{{ old('approved_at', $business->approved_at ? $business->approved_at->format('Y-m-d') : '') }}" 
                           class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>
            </div>
        </div>

        <!-- SECTION 2: CONTACT DETAILS & CATEGORY -->
        <div class="pt-1">
            <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2.5 pb-1 border-b border-slate-100">
                2. {{ __('messages.category_contact_social_sec') }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.business_category_label') }} <span class="text-slate-400 font-normal">({{ __('messages.optional') }})</span></label>
                    <select name="category_id" class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                        <option value="">-- {{ __('messages.select_category') }} --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $business->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.area_label') }} <span class="text-rose-500">*</span></label>
                    <select name="area_id" required class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                        <option value="">-- {{ __('messages.select_area') }} --</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ old('area_id', $business->area_id) == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.phone_whatsapp_number') }}</label>
                    <input type="text" name="phone" value="{{ old('phone', $business->phone) }}" required placeholder="e.g. 9876543210" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                           class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.email_address_label') }}</label>
                    <input type="email" name="email" value="{{ old('email', $business->email) }}" placeholder="{{ __('messages.email_placeholder') }}" 
                           class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.website_url_label') }}</label>
                    <input type="url" name="website" value="{{ old('website', $business->website) }}" placeholder="https://example.com" 
                           class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.facebook_link_label') }}</label>
                    <input type="text" name="facebook" value="{{ old('facebook', $business->facebook) }}" placeholder="https://facebook.com/username" 
                           class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.instagram_link_label') }}</label>
                    <input type="text" name="instagram" value="{{ old('instagram', $business->instagram) }}" placeholder="https://instagram.com/username" 
                           class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.youtube_link_label') }}</label>
                    <input type="text" name="youtube" value="{{ old('youtube', $business->youtube) }}" placeholder="https://youtube.com/@username" 
                           class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.linkedin_link_label') }}</label>
                    <input type="text" name="linkedin" value="{{ old('linkedin', $business->linkedin) }}" placeholder="https://linkedin.com/in/username" 
                           class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>
            </div>
        </div>

        <!-- SECTION 3: DESCRIPTION & ADDRESS -->
        <div class="pt-1">
            <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2.5 pb-1 border-b border-slate-100">
                3. {{ __('messages.business_details_sec') }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.business_desc_label') }} <span class="text-slate-400 font-normal">({{ __('messages.optional') }})</span></label>
                    <textarea name="description" rows="3" placeholder="{{ __('messages.business_desc_placeholder') }}" 
                              class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">{{ old('description', $business->description) }}</textarea>
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.office_address_label') }} <span class="text-rose-500">*</span></label>
                    <textarea name="address" rows="3" required placeholder="{{ __('messages.office_address_placeholder') }}" 
                              class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">{{ old('address', $business->address) }}</textarea>
                </div>
            </div>
        </div>

        <!-- SECTION 4: MEDIA (LOGO, PAYMENT SCREENSHOT & GALLERY) -->
        <div class="pt-1">
            <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2.5 pb-1 border-b border-slate-100">
                4. {{ __('messages.media_gallery_sec') }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- LOGO UPLOAD -->
                <div class="space-y-2 border border-slate-100 rounded-xl p-3 bg-slate-50/50">
                    <label class="text-[10px] font-bold text-slate-500 uppercase block">{{ __('messages.business_logo_label') }}</label>
                    <div class="flex items-center space-x-3">
                        <img src="{{ str_starts_with($business->logo_path, 'http') ? $business->logo_path : asset('storage/' . $business->logo_path) }}" 
                             class="w-12 h-12 rounded-lg object-cover border border-slate-200 bg-white shadow-sm shrink-0" alt="Logo">
                        <div class="space-y-1">
                            <input type="file" name="logo" accept="image/*" 
                                   class="text-xs font-semibold file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-[11px] file:font-bold file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300">
                            <p class="text-[9px] text-slate-400 font-semibold">Max 2MB</p>
                        </div>
                    </div>
                </div>

                <!-- PAYMENT SCREENSHOT UPLOAD -->
                <div class="space-y-2 border border-slate-100 rounded-xl p-3 bg-slate-50/50">
                    <label class="text-[10px] font-bold text-slate-500 uppercase block">{{ __('messages.payment_screenshot_label') }} <span class="text-slate-400 font-normal">({{ __('messages.optional') }})</span></label>
                    <div class="flex items-center space-x-3">
                        @if($business->payment_screenshot_path)
                            <img src="{{ asset('storage/' . $business->payment_screenshot_path) }}" 
                                 class="w-12 h-12 rounded-lg object-cover border border-slate-200 bg-white shadow-sm shrink-0" alt="Payment Screenshot">
                        @else
                            <div class="w-12 h-12 rounded-lg border border-slate-200 bg-slate-100 flex items-center justify-center shrink-0 text-[8px] font-extrabold text-slate-400">{{ __('messages.none') }}</div>
                        @endif
                        <div class="space-y-1">
                            <input type="file" name="payment_screenshot" accept="image/*" 
                                   class="text-xs font-semibold file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-[11px] file:font-bold file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300">
                            <p class="text-[9px] text-slate-400 font-semibold">Max 2MB</p>
                        </div>
                    </div>
                </div>

                <!-- GALLERY UPLOAD & MANAGEMENT -->
                <div class="space-y-4 border border-slate-200/80 rounded-2xl p-4 bg-slate-50/60 shadow-2xs md:col-span-3" x-data="multiShowcaseUploader({{ $existingCount }})">
                    <div class="flex items-center justify-between gap-3 pb-1 flex-wrap">
                        <div>
                            <label class="text-[11px] font-extrabold text-slate-700 uppercase tracking-wider block">{{ __('messages.showcase_photos_label') }}</label>
                            <p class="text-[10px] text-slate-400 font-medium">{{ __('messages.select_multiple_append') }}</p>
                        </div>

                        <div class="flex items-center gap-2">
                            <!-- SELECT FILES BUTTON -->
                            <button type="button" @click="$refs.hiddenFileInput.click()" 
                                :disabled="maxFiles <= 0"
                                :class="maxFiles <= 0 ? 'bg-slate-300 text-slate-500 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700 text-white cursor-pointer'"
                                class="px-4 py-2 font-extrabold text-xs rounded-xl shadow-xs transition-all flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                <span>{{ __('messages.select_files') }}</span>
                            </button>

                            <!-- CLEAR BUTTON -->
                            <button type="button" @click="clearAll()" x-show="files.length > 0" x-cloak
                                class="px-3.5 py-2 bg-rose-100 hover:bg-rose-200 text-rose-700 font-extrabold text-xs rounded-xl border border-rose-200/80 transition-all flex items-center gap-1.5 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <span>{{ __('messages.clear') }}</span>
                            </button>
                        </div>
                    </div>

                    <!-- Hidden file input linked to DataTransfer for actual form submission -->
                    <input type="file" x-ref="hiddenFileInput" name="gallery[]" accept="image/*" multiple @change="selectFiles($event)" class="hidden">

                    <!-- Drag & Drop Container Zone -->
                    <div 
                        @dragover.prevent="isDragging = true"
                        @dragleave.prevent="isDragging = false"
                        @drop.prevent="dropFiles($event)"
                        :class="isDragging ? 'border-blue-500 bg-blue-50/60 scale-[0.99]' : 'border-slate-300 bg-white hover:bg-slate-50/80'"
                        class="border-2 border-dashed rounded-2xl p-5 text-center transition-all cursor-pointer relative"
                        @click="$refs.hiddenFileInput.click()">
                        
                        <div x-show="files.length === 0" class="space-y-1.5 py-3">
                            <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto border border-blue-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="text-xs font-extrabold text-slate-700">{{ __('messages.drop_files_here_or') }} <span class="text-blue-600 underline">{{ __('messages.browse') }}</span></p>
                            <p class="text-[10px] text-slate-400 font-medium">
                                {{ __('messages.drop_files_subtitle') }}
                            </p>
                        </div>

                        <!-- Selected Photos Thumbnails Grid -->
                        <div x-show="files.length > 0" class="space-y-2" @click.stop x-cloak>
                            <div class="flex items-center justify-between text-[11px] font-bold text-slate-500 px-1 border-b border-slate-100 pb-2">
                                <span>{{ __('messages.selected_showcase_photos') }}</span>
                                <span class="text-blue-600 font-black bg-blue-50 px-2 py-0.5 rounded-md border border-blue-200/80" 
                                      x-text="files.length + '/' + maxFiles + ' Selected'"></span>
                            </div>

                            <div class="flex flex-wrap items-center gap-3 pt-1">
                                <template x-for="(f, idx) in files" :key="f.id">
                                    <div class="relative group w-24 h-24 rounded-xl overflow-hidden border border-slate-200 bg-slate-100 shadow-2xs shrink-0">
                                        <img :src="f.url" class="w-full h-full object-cover">
                                        
                                        <!-- Remove Button -->
                                        <button type="button" @click.stop="removeFile(idx)" 
                                            class="absolute top-1 right-1 w-5 h-5 rounded-full bg-rose-600 text-white flex items-center justify-center shadow-md hover:bg-rose-700 transition-colors text-[10px] font-black" title="Remove photo">
                                            ✕
                                        </button>
                                        
                                        <!-- File Name Bar -->
                                        <div class="absolute bottom-0 inset-x-0 bg-slate-900/80 text-white p-0.5 text-[8px] truncate font-semibold backdrop-blur-xs text-center">
                                            <span x-text="f.name"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- EXISTING GALLERY PHOTOS WITH DELETE OPTION -->
                    @if($business->gallery_images && count($business->gallery_images) > 0)
                        <div class="mt-4 pt-4 border-t border-slate-200/80">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">{{ __('messages.existing_showcase_photos') }}</p>
                                <span x-show="removedImages.length > 0" class="text-[10px] font-extrabold text-rose-600 bg-rose-50 border border-rose-100 px-2 py-0.5 rounded-md">
                                    <span x-text="removedImages.length"></span> photo(s) marked for removal
                                </span>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3">
                                @foreach($business->gallery_images as $index => $img)
                                    <label class="block relative aspect-square rounded-xl overflow-hidden border-2 transition-all cursor-pointer group bg-slate-50 shadow-2xs"
                                           :class="isRemoved('{{ $img }}') ? 'border-rose-500 ring-2 ring-rose-500/30' : 'border-slate-200 hover:border-slate-300'">
                                        <img src="{{ asset('storage/' . $img) }}" 
                                             class="w-full h-full object-cover transition-all"
                                             :class="isRemoved('{{ $img }}') ? 'opacity-30 grayscale' : 'group-hover:opacity-85'">
                                        
                                        <div x-show="isRemoved('{{ $img }}')" class="absolute inset-0 bg-rose-950/40 flex flex-col items-center justify-center text-white">
                                            <span class="text-[9px] font-black uppercase tracking-wider bg-rose-600 px-2 py-0.5 rounded-md shadow-sm">{{ __('messages.will_delete') }}</span>
                                        </div>

                                        <input type="checkbox" name="remove_gallery_images[]" value="{{ $img }}" 
                                               @change="toggleExisting('{{ $img }}', $event)" 
                                               class="absolute top-2 right-2 w-4 h-4 rounded text-rose-600 focus:ring-rose-500 border-slate-300 shadow-sm z-10 cursor-pointer">
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <!-- Custom Pop-up Modal for Photo Limit Warning -->
                    <div x-show="showLimitModal" 
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
                        @keydown.escape.window="showLimitModal = false"
                        x-cloak>
                        
                        <div class="bg-white rounded-3xl p-6 max-w-sm w-full text-center shadow-2xl border border-slate-100 space-y-4 relative overflow-hidden" @click.away="showLimitModal = false">
                            <div class="w-14 h-14 rounded-full bg-amber-50 text-amber-500 border border-amber-200/80 flex items-center justify-center mx-auto shadow-inner">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>

                            <div class="space-y-1.5">
                                <h3 class="text-base font-black text-slate-900">{{ __('messages.photo_limit_reached') }}</h3>
                                <p class="text-xs text-slate-500 font-medium leading-relaxed">
                                    {{ __('messages.photo_limit_desc') }}
                                </p>
                            </div>

                            <button type="button" @click="showLimitModal = false" 
                                class="w-full py-2.5 bg-amber-500 hover:bg-amber-600 active:scale-[0.98] text-white font-extrabold text-xs rounded-xl shadow-md transition-all cursor-pointer">
                                {{ __('messages.got_it_thanks') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FORM ACTIONS -->
        <div class="pt-3 border-t border-slate-100 flex justify-end items-center space-x-2">
            <a href="{{ route('admin.businesses.index') }}" 
               class="px-3.5 py-1.5 border border-slate-200 hover:bg-slate-50 text-slate-600 font-bold text-xs rounded-lg transition-colors">
                {{ __('messages.cancel') }}
            </a>
            <button type="submit" 
                    class="px-4 py-1.5 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-lg shadow-sm transition-colors">
                {{ __('messages.save_changes') }}
            </button>
        </div>
    </form>
</div>

<script>
function multiShowcaseUploader(initialExistingCount = 0) {
    return {
        files: [],
        removedImages: [],
        isDragging: false,
        initialExistingCount: initialExistingCount,
        showLimitModal: false,
        
        get existingCount() {
            return Math.max(0, this.initialExistingCount - this.removedImages.length);
        },

        get maxFiles() {
            return Math.max(0, 6 - this.existingCount);
        },

        isRemoved(imgPath) {
            return this.removedImages.includes(imgPath);
        },

        toggleExisting(imgPath, e) {
            if (e.target.checked) {
                if (!this.removedImages.includes(imgPath)) {
                    this.removedImages.push(imgPath);
                }
            } else {
                this.removedImages = this.removedImages.filter(img => img !== imgPath);
                if (this.files.length > this.maxFiles) {
                    this.files.splice(this.maxFiles);
                    this.syncInput();
                }
            }
        },

        selectFiles(e) {
            if (this.maxFiles <= 0) {
                this.showLimitModal = true;
                return;
            }
            if (e.target.files && e.target.files.length > 0) {
                this.addFiles(Array.from(e.target.files));
            }
        },
        
        dropFiles(e) {
            this.isDragging = false;
            if (this.maxFiles <= 0) {
                this.showLimitModal = true;
                return;
            }
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

            if (overflow || (this.maxFiles <= 0 && newFiles.length > 0)) {
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
@endsection
