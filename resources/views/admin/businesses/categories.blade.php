@extends('layouts.admin')

@section('page_title', 'Business Category Management')

@section('content')
<div x-data="{ 
    addOpen: false, 
    editOpen: false, 
    editName: '',
    editUrl: ''
}" class="space-y-2">

    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">Categories List</h3>
        <button type="button" @click="addOpen = true" class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-xl shadow-sm transition-colors">
            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Category
        </button>
    </div>

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl text-xs text-rose-800 font-semibold space-y-1">
            <p class="font-bold">Please correct the following errors:</p>
            <ul class="list-disc pl-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Category list -->
    <div class="bg-white border border-slate-100 rounded-xl overflow-hidden shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-xs font-black uppercase text-slate-700 tracking-wider border-b border-slate-200">
                    <th class="py-2.5 px-4">Category Name</th>
                    <th class="py-2.5 px-4">URL Slug</th>
                    <th class="py-2.5 px-4">Total Shops</th>
                    <th class="py-2.5 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                @forelse($categories as $cat)
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-2.5 px-4 text-slate-900 font-bold">{{ $cat->name }}</td>
                        <td class="py-2.5 px-4 text-slate-400 font-medium">{{ $cat->slug }}</td>
                        <td class="py-2.5 px-4 font-bold">{{ $cat->businesses_count }} shops</td>
                        <td class="py-2.5 px-4 text-right">
                            <div class="flex justify-end items-center space-x-2">
                                <button type="button" 
                                        @click="editName = '{{ addslashes($cat->name) }}'; editUrl = '{{ route('admin.businesses.categories.update', $cat->id) }}'; editOpen = true" 
                                        class="px-2 py-1 text-[10px] font-bold text-primary-500 hover:bg-primary-50 rounded-lg transition-colors">
                                    Edit
                                </button>
                                <button type="button" @click="$dispatch('confirm-delete', { action: '{{ route('admin.businesses.categories.destroy', $cat->id) }}', message: 'Are you sure you want to delete category: {{ $cat->name }}?' })" class="px-2 py-1 text-[10px] font-bold text-rose-500 hover:bg-rose-50 rounded-lg">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center text-slate-400">
                            No business categories declared yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div>
        {{ $categories->links() }}
    </div>

    <!-- Add Category Modal -->
    <template x-teleport="body">
        <div x-show="addOpen" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-sm"
             x-transition
             x-cloak>
            <div class="bg-white rounded-xl max-w-md w-full p-6 border border-slate-100 shadow-xl space-y-4"
                 @click.away="addOpen = false">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-extrabold text-slate-950">New Business Category</h3>
                    <button type="button" @click="addOpen = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form method="POST" action="{{ route('admin.businesses.categories.store') }}" class="space-y-4">
                    @csrf
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 uppercase">Category Name</label>
                        <input type="text" name="name" required placeholder="e.g. Electrical Services" 
                               class="w-full text-xs font-semibold px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500">
                    </div>
                    <div class="flex items-center justify-end space-x-2 pt-2">
                        <button type="button" @click="addOpen = false" class="px-4 py-2 border border-slate-200 text-slate-700 font-bold text-xs rounded-xl hover:bg-slate-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-xl shadow-sm transition-colors">
                            Create Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <!-- Edit Category Modal -->
    <template x-teleport="body">
        <div x-show="editOpen" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-sm"
             x-transition
             x-cloak>
            <div class="bg-white rounded-xl max-w-md w-full p-6 border border-slate-100 shadow-xl space-y-4"
                 @click.away="editOpen = false">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-extrabold text-slate-950">Edit Business Category</h3>
                    <button type="button" @click="editOpen = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form method="POST" :action="'{{ url('admin/businesses/categories') }}/' + editId" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 uppercase">Category Name</label>
                        <input type="text" name="name" x-model="editName" required 
                               class="w-full text-xs font-semibold px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500">
                    </div>
                    <div class="flex items-center justify-end space-x-2 pt-2">
                        <button type="button" @click="editOpen = false" class="px-4 py-2 border border-slate-200 text-slate-700 font-bold text-xs rounded-xl hover:bg-slate-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-xl shadow-sm transition-colors">
                            Update Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>

</div>
@endsection
