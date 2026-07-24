@extends('layouts.admin')

@section('page_title', 'Edit Business Directory Entry')

@section('content')
<div class="max-w-6xl bg-white border border-slate-100 rounded-xl p-5 shadow-sm space-y-4">
    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="p-3 bg-rose-50 border border-rose-100 text-rose-800 rounded-xl">
            <p class="text-xs font-bold mb-1">Please correct the following errors:</p>
            <ul class="list-disc pl-4 text-[11px] font-medium space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.businesses.update', $business->id) }}" class="space-y-4" enctype="multipart/form-data">
        @csrf

        <!-- SECTION 1: BASIC INFORMATION -->
        <div>
            <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2.5 pb-1 border-b border-slate-100">
                1. Basic Information
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">Member ID <span class="text-rose-500">*</span></label>
                    <input type="text" name="member_id" value="{{ old('member_id', $business->member_id) }}" required placeholder="e.g. LIFETIME-1234" 
                           class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">Business Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="business_name" value="{{ old('business_name', $business->business_name) }}" required placeholder="e.g. Acme Corporation" 
                           class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">Owner Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="owner_name" value="{{ old('owner_name', $business->owner_name) }}" required placeholder="e.g. John Doe" 
                           class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">Status <span class="text-rose-500">*</span></label>
                    <select name="status" required class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                        <option value="pending" {{ old('status', $business->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ old('status', $business->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ old('status', $business->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.membership_status') }} <span class="text-rose-500">*</span></label>
                    <select name="membership_status" required class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                        <option value="active" {{ old('membership_status', $business->membership_status) == 'active' ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                        <option value="inactive" {{ old('membership_status', $business->membership_status) == 'inactive' ? 'selected' : '' }}>{{ __('messages.inactive') }}</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- SECTION 2: CONTACT DETAILS & CATEGORY -->
        <div class="pt-1">
            <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2.5 pb-1 border-b border-slate-100">
                2. Category, Contact Info & Social Media
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">Category (Optional)</label>
                    <select name="category_id" class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $business->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">Area <span class="text-rose-500">*</span></label>
                    <select name="area_id" required class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                        <option value="">-- Select Area --</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ old('area_id', $business->area_id) == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $business->phone) }}" required placeholder="e.g. 9876543210" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                           class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">WhatsApp Number</label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp', $business->whatsapp) }}" placeholder="e.g. 9876543210" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                           class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $business->email) }}" placeholder="e.g. info@acme.com" 
                           class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">Website URL</label>
                    <input type="url" name="website" value="{{ old('website', $business->website) }}" placeholder="e.g. https://www.acme.com" 
                           class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">Facebook Link</label>
                    <input type="text" name="facebook" value="{{ old('facebook', $business->facebook) }}" placeholder="e.g. https://facebook.com/username" 
                           class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">Instagram Link</label>
                    <input type="text" name="instagram" value="{{ old('instagram', $business->instagram) }}" placeholder="e.g. https://instagram.com/username" 
                           class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">YouTube Link</label>
                    <input type="text" name="youtube" value="{{ old('youtube', $business->youtube) }}" placeholder="e.g. https://youtube.com/@username" 
                           class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">LinkedIn Link</label>
                    <input type="text" name="linkedin" value="{{ old('linkedin', $business->linkedin) }}" placeholder="e.g. https://linkedin.com/in/username" 
                           class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>
            </div>
        </div>

        <!-- SECTION 3: DESCRIPTION & ADDRESS -->
        <div class="pt-1">
            <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2.5 pb-1 border-b border-slate-100">
                3. Business Details
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">Description (Optional)</label>
                    <textarea name="description" rows="3" placeholder="Describe your business, products, services..." 
                              class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">{{ old('description', $business->description) }}</textarea>
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">Full Address <span class="text-rose-500">*</span></label>
                    <textarea name="address" rows="3" required placeholder="Shop number, street, locality, city..." 
                              class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">{{ old('address', $business->address) }}</textarea>
                </div>
            </div>
        </div>

        <!-- SECTION 4: MEDIA (LOGO, PAYMENT SCREENSHOT & GALLERY) -->
        <div class="pt-1">
            <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2.5 pb-1 border-b border-slate-100">
                4. Logo, Payment Screenshot & Showcase Gallery
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- LOGO UPLOAD -->
                <div class="space-y-2 border border-slate-100 rounded-xl p-3 bg-slate-50/50">
                    <label class="text-[10px] font-bold text-slate-500 uppercase block">Logo Image / V.Card <span class="text-rose-500">*</span></label>
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
                    <label class="text-[10px] font-bold text-slate-500 uppercase block">Payment Screenshot <span class="text-rose-500">*</span></label>
                    <div class="flex items-center space-x-3">
                        @if($business->payment_screenshot_path)
                            <img src="{{ asset('storage/' . $business->payment_screenshot_path) }}" 
                                 class="w-12 h-12 rounded-lg object-cover border border-slate-200 bg-white shadow-sm shrink-0" alt="Payment Screenshot">
                        @else
                            <div class="w-12 h-12 rounded-lg border border-slate-200 bg-slate-100 flex items-center justify-center shrink-0 text-[8px] font-extrabold text-slate-400">None</div>
                        @endif
                        <div class="space-y-1">
                            <input type="file" name="payment_screenshot" accept="image/*" 
                                   class="text-xs font-semibold file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-[11px] file:font-bold file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300">
                            <p class="text-[9px] text-slate-400 font-semibold">Max 2MB</p>
                        </div>
                    </div>
                </div>

                <!-- GALLERY UPLOAD & MANAGEMENT -->
                <div class="space-y-2 border border-slate-100 rounded-xl p-3 bg-slate-50/50">
                    <label class="text-[10px] font-bold text-slate-500 uppercase block">Add Showcase Photos</label>
                    <input type="file" name="gallery[]" accept="image/*" multiple 
                           class="w-full text-xs font-semibold file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-[11px] file:font-bold file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300">
                    <p class="text-[9px] text-slate-400 font-semibold">Max 10MB per image</p>
                </div>
            </div>

            <!-- EXISTING GALLERY PHOTOS WITH DELETE OPTION -->
            @if($business->gallery_images && count($business->gallery_images) > 0)
                <div class="mt-3 border border-slate-100 rounded-xl p-3">
                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-2">Existing Showcase Photos (Select to delete)</p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3">
                        @foreach($business->gallery_images as $index => $img)
                            <label class="block relative aspect-square rounded-lg overflow-hidden border border-slate-200 cursor-pointer group bg-slate-50">
                                <img src="{{ asset('storage/' . $img) }}" class="w-full h-full object-cover group-hover:opacity-75 transition-opacity">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                    <span class="text-white text-[9px] font-bold">Remove</span>
                                </div>
                                <input type="checkbox" name="remove_gallery_images[]" value="{{ $img }}" class="absolute top-1 right-1 w-3.5 h-3.5 rounded text-rose-600 focus:ring-rose-500 border-slate-300">
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- FORM ACTIONS -->
        <div class="pt-3 border-t border-slate-100 flex justify-end items-center space-x-2">
            <a href="{{ route('admin.businesses.index') }}" 
               class="px-3.5 py-1.5 border border-slate-200 hover:bg-slate-50 text-slate-600 font-bold text-xs rounded-lg transition-colors">
                Cancel
            </a>
            <button type="submit" 
                    class="px-4 py-1.5 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-lg shadow-sm transition-colors">
                Save Updates
            </button>
        </div>
    </form>
</div>
@endsection
