@extends('layouts.member')
 
@section('page_title', __('messages.submit_award_claim'))
 
@section('content')
<div class="max-w-6xl bg-white border border-slate-100 rounded-2xl p-4 md:p-5 shadow-sm">
    
    <!-- Validation errors -->
    @if ($errors->any())
        <div class="mb-4 p-3 bg-rose-50 border border-rose-100 text-rose-800 rounded-xl">
            <ul class="list-disc pl-4 text-xs font-semibold space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
 
    <form method="POST" action="{{ route('member.awards.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        
        <div class="space-y-1">
            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">{{ __('messages.student_name') }}</label>
            <input type="text" name="student_name" value="{{ old('student_name') }}" required class="w-full text-xs font-semibold px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl">
        </div>
 
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">{{ __('messages.standard_class') }}</label>
                <input type="text" name="standard" value="{{ old('standard') }}" placeholder="e.g. 10th Grade" required class="w-full text-xs font-semibold px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl">
            </div>
 
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">{{ __('messages.school_institute_name') }}</label>
                <input type="text" name="school" value="{{ old('school') }}" required class="w-full text-xs font-semibold px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl">
            </div>
        </div>
 
        <div class="space-y-1">
            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">{{ __('messages.achievement_details') }}</label>
            <input type="text" name="achievement" value="{{ old('achievement') }}" placeholder="e.g. Secured 92.5% marks" required class="w-full text-xs font-semibold px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl">
        </div>
 
        <div class="space-y-1">
            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">{{ __('messages.award_category_name') }}</label>
            <input type="text" name="award_name" value="{{ old('award_name') }}" placeholder="e.g. Pratibha Shali Award" required class="w-full text-xs font-semibold px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl">
        </div>
 
        <div class="space-y-1">
            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">{{ __('messages.upload_certificate') }}</label>
            <input type="file" name="certificate" required class="text-xs block w-full text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-[10px] file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
        </div>
 
        <div class="space-y-1">
            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">{{ __('messages.additional_remarks') }}</label>
            <textarea name="remarks" rows="2" class="w-full text-xs font-semibold px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl">{{ old('remarks') }}</textarea>
        </div>
 
        <div class="pt-3 border-t border-slate-100 flex justify-end items-center space-x-3">
            <a href="{{ route('member.awards.index') }}" class="px-4 py-1.5 border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-xl transition-colors">{{ __('messages.cancel') }}</a>
            <button type="submit" class="px-5 py-2 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-xl shadow-sm transition-colors">
                {{ __('messages.submit_claim') }}
            </button>
        </div>
    </form>
</div>
@endsection
