@extends('layouts.admin')

@section('page_title', 'Edit Category Name')

@section('content')
<div class="max-w-md bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
    <form method="POST" action="{{ route('admin.businesses.categories.update', $category->id) }}" class="space-y-4">
        @csrf
        @method('POST')
        <div class="space-y-1">
            <label class="text-[10px] font-bold text-slate-400 uppercase">Category Name</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" required 
                   class="w-full text-xs font-semibold px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500">
        </div>
        <div class="pt-4 border-t border-slate-100 flex justify-end space-x-2">
            <a href="{{ route('admin.businesses.categories') }}" class="px-4 py-2 border border-slate-200 text-slate-700 font-bold text-xs rounded-xl">Cancel</a>
            <button type="submit" class="px-5 py-2 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-xl shadow-sm">
                Save Updates
            </button>
        </div>
    </form>
</div>
@endsection
