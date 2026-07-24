@extends('layouts.public')

@section('content')
@include('partials.page_header', [
    'title' => 'Register Your Business',
    'subtitle' => 'Promote your work inside the community',
    'breadcrumb' => 'Business Registration'
])

<!-- Form Body -->
<section class="py-6 bg-slate-50/50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white border border-slate-200/60 rounded-2xl p-5 md:p-6 shadow-xs">
            
            <!-- Validation errors -->
            @if ($errors->any())
                <div class="mb-4 p-3 bg-rose-50 border border-rose-100 text-rose-800 rounded-xl">
                    <p class="text-xs font-bold mb-2">Please correct the following errors:</p>
                    <ul class="list-disc pl-4 text-[11px] font-medium space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.business.submit') }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-x-4 gap-y-3">
                @csrf
                
                <!-- Row 1: Member ID & Business Name -->
                <div class="space-y-0.5">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Member ID (આજીવન સભ્ય નં.) <span class="text-rose-500">*</span></label>
                    <input type="text" name="member_id" required value="{{ old('member_id') }}" placeholder="e.g. LIFETIME-1234" class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-0">
                </div>

                <div class="space-y-0.5 md:col-span-2">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Business / Company Name (Firm Name) <span class="text-rose-500">*</span></label>
                    <input type="text" name="business_name" required value="{{ old('business_name') }}" placeholder="Enter business name" class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-0">
                </div>

                <!-- Row 2: Owner, Category, Area -->
                <div class="space-y-0.5">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Contact Person <span class="text-rose-500">*</span></label>
                    <input type="text" name="owner_name" required value="{{ old('owner_name') }}" placeholder="Owner / Representative" class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-0">
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Business Category (Optional)</label>
                    <select name="category_id" class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-0">
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Area <span class="text-rose-500">*</span></label>
                    <select name="area_id" required class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-0">
                        <option value="">-- Select Area --</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Row 3: Contacts -->
                <div class="space-y-0.5">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Phone Number <span class="text-rose-500">*</span></label>
                    <input type="text" name="phone" required value="{{ old('phone') }}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" placeholder="10 Digits" class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-0">
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">WhatsApp Number (Optional)</label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" placeholder="10 Digits" class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-0">
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Email Address (Optional)</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="example@email.com" class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-0">
                </div>

                <!-- Row 4: Links -->
                <div class="space-y-0.5">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Website URL (Optional)</label>
                    <input type="url" name="website" value="{{ old('website') }}" placeholder="https://example.com" class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-0">
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Facebook Link (Optional)</label>
                    <input type="text" name="facebook" value="{{ old('facebook') }}" placeholder="https://facebook.com/username" class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-0">
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Instagram Link (Optional)</label>
                    <input type="text" name="instagram" value="{{ old('instagram') }}" placeholder="https://instagram.com/username" class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-0">
                </div>

                <!-- Row 5: Links Continued & File -->
                <div class="space-y-0.5">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">YouTube Link (Optional)</label>
                    <input type="text" name="youtube" value="{{ old('youtube') }}" placeholder="https://youtube.com/@username" class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-0">
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">LinkedIn Link (Optional)</label>
                    <input type="text" name="linkedin" value="{{ old('linkedin') }}" placeholder="https://linkedin.com/in/username" class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-0">
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Attach V.Card / Details <span class="text-rose-500">*</span></label>
                    <input type="file" name="logo" required class="text-[11px] block w-full text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-[10px] file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                </div>

                <!-- Row 6: Showcase Photos & Description -->
                <div class="space-y-0.5">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Showcase Photos (Optional)</label>
                    <input type="file" name="gallery[]" multiple class="text-[11px] block w-full text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-[10px] file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                </div>

                <div class="space-y-0.5 md:col-span-2">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Business Description (Optional)</label>
                    <textarea name="description" rows="1" placeholder="Brief details about your products/services..." class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-0">{{ old('description') }}</textarea>
                </div>

                <!-- Row 7: Address -->
                <div class="space-y-0.5 md:col-span-3">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Office / Store Address <span class="text-rose-500">*</span></label>
                    <textarea name="address" rows="1" required placeholder="Full office address..." class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-0">{{ old('address') }}</textarea>
                </div>

                <!-- Submit Button -->
                <div class="md:col-span-3 pt-2 flex justify-end">
                    <button type="submit" class="inline-flex items-center justify-center px-5 py-2 bg-primary-500 hover:bg-primary-600 font-extrabold text-[10px] text-white uppercase tracking-wider rounded-lg shadow-xs transition-transform hover:-translate-y-0.5">
                        Register Business
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
