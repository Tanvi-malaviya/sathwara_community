@extends('layouts.member')

@section('page_title', 'Edit Family Member')

@section('content')
<div class="max-w-xl bg-white border border-slate-100 rounded-2xl p-4 md:p-5 shadow-sm">
    
    <!-- Errors -->
    @if ($errors->any())
        <div class="mb-4 p-3 bg-rose-50 border border-rose-100 text-rose-800 rounded-xl">
            <ul class="list-disc pl-4 text-xs font-semibold space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('member.family.update', $member->id) }}" class="grid grid-cols-1 gap-4"
        x-data="{
            rel: '{{ old('relationship', $member->relationship) }}',
            gender: '{{ old('gender', $member->gender) }}',
            maritalStatus: '{{ old('marital_status', $member->marital_status) }}',
            init() {
                this.$watch('rel', value => {
                    if (['Wife', 'Daughter-in-law', 'Daughter', 'Granddaughter (Son\'s Daughter)', 'Granddaughter (Daughter\'s Daughter)', 'પત્ની', 'દીકરી', 'વહુ', 'પૌત્રી', 'દોહિત્રી'].includes(value)) {
                        this.gender = 'Female';
                    } else if (['Husband', 'Son', 'Son-in-law', 'Grandson (Son\'s Son)', 'Grandson (Daughter\'s Son)', 'पति', 'પતિ', 'દીકરો', 'જમાઈ', 'પૌત્ર', 'દોહિત્ર'].includes(value)) {
                        this.gender = 'Male';
                    }
                    
                    if (['Wife', 'Husband', 'Daughter-in-law', 'Son-in-law', 'પત્ની', 'पति', 'પતિ', 'વહુ', 'જમાઈ'].includes(value)) {
                        this.maritalStatus = 'Married';
                    }
                });
            }
        }">
        @csrf
        @method('PUT')
        
        <div class="space-y-1">
            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">Full Name</label>
            <input type="text" name="name" value="{{ old('name', $member->name) }}" required class="w-full text-xs font-semibold px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">Relationship</label>
                @php
                    $rel = old('relationship', $member->relationship);
                    $userGender = $profile->gender ?? 'Male';
                    $isFemaleMember = strtolower($userGender) === 'female';
                    $hasExistingSpouse = isset($family) && $family->contains(fn($m) => in_array($m->relationship, ['Wife', 'Husband', 'Spouse', 'પત્ની', 'પતિ']));
                    $isCurrentSpouse = in_array($rel, ['Wife', 'Husband', 'Spouse', 'પત્ની', 'પતિ']);
                @endphp
                <select name="relationship" required x-model="rel" class="w-full text-xs font-semibold px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                    @if(!$hasExistingSpouse || $isCurrentSpouse)
                        @if($isFemaleMember)
                            <option value="Husband" {{ in_array($rel, ['Husband', 'પતિ']) ? 'selected' : '' }}>{{ __('messages.rel_husband') }}</option>
                        @else
                            <option value="Wife" {{ in_array($rel, ['Wife', 'Spouse', 'પત્ની']) ? 'selected' : '' }}>{{ __('messages.rel_wife') }}</option>
                        @endif
                    @endif
                    <option value="Son" {{ in_array($rel, ['Son', 'દીકરો']) ? 'selected' : '' }}>{{ __('messages.rel_son') }}</option>
                    <option value="Daughter" {{ in_array($rel, ['Daughter', 'દીકરી']) ? 'selected' : '' }}>{{ __('messages.rel_daughter') }}</option>
                    <option value="Daughter-in-law" {{ in_array($rel, ['Daughter-in-law', 'વહુ']) ? 'selected' : '' }}>{{ __('messages.rel_daughter_in_law') }}</option>
                    <option value="Son-in-law" {{ in_array($rel, ['Son-in-law', 'જમાઈ']) ? 'selected' : '' }}>{{ __('messages.rel_son_in_law') }}</option>
                    <option value="Grandson (Son's Son)" {{ in_array($rel, ["Grandson (Son's Son)", 'પૌત્ર']) ? 'selected' : '' }}>{{ __('messages.rel_grandson_sons_son') }}</option>
                    <option value="Granddaughter (Son's Daughter)" {{ in_array($rel, ["Granddaughter (Son's Daughter)", 'પૌત્રી']) ? 'selected' : '' }}>{{ __('messages.rel_granddaughter_sons_daughter') }}</option>
                    <option value="Grandson (Daughter's Son)" {{ in_array($rel, ["Grandson (Daughter's Son)", 'દોહિત્ર']) ? 'selected' : '' }}>{{ __('messages.rel_grandson_daughters_son') }}</option>
                    <option value="Granddaughter (Daughter's Daughter)" {{ in_array($rel, ["Granddaughter (Daughter's Daughter)", 'દોહિત્રી']) ? 'selected' : '' }}>{{ __('messages.rel_granddaughter_daughters_daughter') }}</option>
                    <!-- <option value="Father" {{ in_array($rel, ['Father', 'પિતા']) ? 'selected' : '' }}>{{ __('messages.rel_father') }}</option>
                    <option value="Mother" {{ in_array($rel, ['Mother', 'માતા']) ? 'selected' : '' }}>{{ __('messages.rel_mother') }}</option>
                    <option value="Brother" {{ in_array($rel, ['Brother', 'ભાઈ']) ? 'selected' : '' }}>{{ __('messages.rel_brother') }}</option>
                    <option value="Sister" {{ in_array($rel, ['Sister', 'બહેન']) ? 'selected' : '' }}>{{ __('messages.rel_sister') }}</option>
                    <option value="Other" {{ in_array($rel, ['Other', 'અન્ય']) ? 'selected' : '' }}>{{ __('messages.rel_other') }}</option> -->
                </select>

                @php
                    $sons = isset($family) ? $family->filter(fn($m) => in_array($m->relationship, ['Son', 'Son (દીકરો)', 'દીકરો'])) : collect();
                    $daughters = isset($family) ? $family->filter(fn($m) => in_array($m->relationship, ['Daughter', 'Daughter (દીકરી)', 'દીકરી'])) : collect();
                    $currentParentId = old('parent_id', $member->parent_id);
                @endphp

                <div x-show="['Grandson (Son\'s Son)', 'Granddaughter (Son\'s Daughter)', 'Daughter-in-law'].includes(rel)" class="space-y-1 mt-2">
                    <label class="text-[10px] font-bold text-primary-600 uppercase tracking-wide">Select Parent Son / Husband</label>
                    <select name="parent_id" class="w-full text-xs font-semibold px-4 py-2 bg-primary-50 border border-primary-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none">
                        <option value="">Select Son</option>
                        @foreach($sons as $s)
                            <option value="{{ $s->id }}" {{ $currentParentId == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div x-show="['Grandson (Daughter\'s Son)', 'Granddaughter (Daughter\'s Daughter)', 'Son-in-law'].includes(rel)" class="space-y-1 mt-2">
                    <label class="text-[10px] font-bold text-primary-600 uppercase tracking-wide">Select Parent Daughter / Wife</label>
                    <select name="parent_id" class="w-full text-xs font-semibold px-4 py-2 bg-primary-50 border border-primary-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none">
                        <option value="">Select Daughter</option>
                        @foreach($daughters as $d)
                            <option value="{{ $d->id }}" {{ $currentParentId == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">Gender</label>
                <select name="gender" required x-model="gender" class="w-full text-xs font-semibold px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">Marital Status</label>
                <select name="marital_status" required x-model="maritalStatus" class="w-full text-xs font-semibold px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                    <option value="Unmarried">Unmarried</option>
                    <option value="Married">Married</option>
                </select>
            </div>

            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">Date of Birth</label>
                <input type="date" name="dob" value="{{ old('dob', $member->dob) }}" min="{{ auth()->user()->memberProfile && auth()->user()->memberProfile->dob ? \Carbon\Carbon::parse(auth()->user()->memberProfile->dob)->addDay()->format('Y-m-d') : '' }}" max="{{ \Carbon\Carbon::yesterday()->format('Y-m-d') }}" class="w-full text-xs font-semibold px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">Education</label>
                <input type="text" name="education" value="{{ old('education', $member->education) }}" class="w-full text-xs font-semibold px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl">
            </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">Occupation</label>
                <input type="text" name="occupation" value="{{ old('occupation', $member->occupation) }}" class="w-full text-xs font-semibold px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl">
            </div>

            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">Phone Number (Optional, 10 Digits)</label>
                <input type="text" name="phone" value="{{ old('phone', $member->phone) }}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" class="w-full text-xs font-semibold px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl">
            </div>
        </div>

        <div class="pt-3 border-t border-slate-100 flex justify-end items-center space-x-3">
            <a href="{{ route('member.family.index') }}" class="px-4 py-1.5 border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-xl transition-colors">Cancel</a>
            <button type="submit" class="px-5 py-2 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-xl shadow-sm transition-colors">
                Update Details
            </button>
        </div>
    </form>
</div>
@endsection
