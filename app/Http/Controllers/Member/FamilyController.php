<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\FamilyMember;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $profile = $user->memberProfile;
        $family = $user->familyMembers;
        return view('member.family.index', compact('user', 'profile', 'family'));
    }

    public function create()
    {
        $user = auth()->user();
        $family = $user->familyMembers;
        $profile = $user->memberProfile;
        return view('member.family.create', compact('family', 'profile'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $userProfile = $user->memberProfile;
        $userGender = strtolower($userProfile->gender ?? 'Male');
        $parentDob = $userProfile && $userProfile->dob ? $userProfile->dob : null;

        if (in_array($request->relationship, ['Wife', 'Husband', 'Spouse', 'પત્ની', 'પતિ'])) {
            $existingSpouse = $user->familyMembers()->whereIn('relationship', ['Wife', 'Husband', 'Spouse', 'પત્ની', 'પતિ'])->exists();
            if ($existingSpouse) {
                return redirect()->back()->withErrors(['relationship' => 'You can only add 1 spouse (Wife/Husband).'])->withInput();
            }
            if ($userGender === 'female' && in_array($request->relationship, ['Wife', 'પત્ની'])) {
                return redirect()->back()->withErrors(['relationship' => 'Female member can only add Husband as relationship.'])->withInput();
            }
            if ($userGender !== 'female' && in_array($request->relationship, ['Husband', 'પતિ'])) {
                return redirect()->back()->withErrors(['relationship' => 'Male member can only add Wife as relationship.'])->withInput();
            }
        }

        if (in_array($request->relationship, ['Wife', 'Husband', 'Daughter-in-law', 'Son-in-law', 'Spouse', 'પત્ની', 'પતિ', 'વહુ', 'જમાઈ'])) {
            $request->merge(['marital_status' => 'Married']);
        }
        if (in_array($request->relationship, ['Husband', 'Son-in-law', 'Son', 'पति', 'પતિ', 'જમાઈ', 'દીકરો'])) {
            $request->merge(['gender' => 'Male']);
        } elseif (in_array($request->relationship, ['Wife', 'Daughter-in-law', 'Daughter', 'પત્ની', 'વહુ', 'દીકરી'])) {
            $request->merge(['gender' => 'Female']);
        }

        $dobRules = ['nullable', 'date', 'before:today'];
        if ($parentDob) {
            $dobRules[] = 'after:' . $parentDob;
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'relationship' => 'required|string|max:100',
            'parent_id' => 'nullable|exists:family_members,id',
            'gender' => 'required|in:Male,Female,Other',
            'marital_status' => 'required|in:Unmarried,Married',
            'dob' => $dobRules,
            'education' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'phone' => 'nullable|digits:10',
            'email' => 'nullable|email|max:255',
            'blood_group' => 'nullable|string|max:10',
        ], [
            'dob.after' => __('messages.family_dob_after_member'),
        ]);

        auth()->user()->familyMembers()->create($request->all());

        return redirect()->route('member.family.index')->with('success', 'Family member added successfully.');
    }

    public function edit($id)
    {
        $user = auth()->user();
        $member = $user->familyMembers()->findOrFail($id);
        $family = $user->familyMembers()->where('id', '!=', $id)->get();
        $profile = $user->memberProfile;
        return view('member.family.edit', compact('member', 'family', 'profile'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $member = $user->familyMembers()->findOrFail($id);
        $userProfile = $user->memberProfile;
        $userGender = strtolower($userProfile->gender ?? 'Male');
        $parentDob = $userProfile && $userProfile->dob ? $userProfile->dob : null;

        if (in_array($request->relationship, ['Wife', 'Husband', 'Spouse', 'પત્ની', 'પતિ'])) {
            $existingSpouse = $user->familyMembers()->where('id', '!=', $id)->whereIn('relationship', ['Wife', 'Husband', 'Spouse', 'પત્ની', 'પતિ'])->exists();
            if ($existingSpouse) {
                return redirect()->back()->withErrors(['relationship' => 'You can only add 1 spouse (Wife/Husband).'])->withInput()->with('edit_id', $id);
            }
            if ($userGender === 'female' && in_array($request->relationship, ['Wife', 'પત્ની'])) {
                return redirect()->back()->withErrors(['relationship' => 'Female member can only add Husband as relationship.'])->withInput()->with('edit_id', $id);
            }
            if ($userGender !== 'female' && in_array($request->relationship, ['Husband', 'પતિ'])) {
                return redirect()->back()->withErrors(['relationship' => 'Male member can only add Wife as relationship.'])->withInput()->with('edit_id', $id);
            }
        }

        if (in_array($request->relationship, ['Wife', 'Husband', 'Daughter-in-law', 'Son-in-law', 'Spouse', 'પત્ની', 'પતિ', 'વહુ', 'જમાઈ'])) {
            $request->merge(['marital_status' => 'Married']);
        }
        if (in_array($request->relationship, ['Husband', 'Son-in-law', 'Son', 'पति', 'પતિ', 'જમાઈ', 'દીકરો'])) {
            $request->merge(['gender' => 'Male']);
        } elseif (in_array($request->relationship, ['Wife', 'Daughter-in-law', 'Daughter', 'પત્ની', 'વહુ', 'દીકરી'])) {
            $request->merge(['gender' => 'Female']);
        }

        $dobRules = ['nullable', 'date', 'before:today'];
        if ($parentDob) {
            $dobRules[] = 'after:' . $parentDob;
        }

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'relationship' => 'required|string|max:100',
                'parent_id' => 'nullable|exists:family_members,id',
                'gender' => 'required|in:Male,Female,Other',
                'marital_status' => 'required|in:Unmarried,Married',
                'dob' => $dobRules,
                'education' => 'nullable|string|max:255',
                'occupation' => 'nullable|string|max:255',
                'phone' => 'nullable|digits:10',
                'email' => 'nullable|email|max:255',
                'blood_group' => 'nullable|string|max:10',
            ], [
                'dob.after' => __('messages.family_dob_after_member'),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput()->with('edit_id', $id);
        }

        $member->update($request->all());

        return redirect()->route('member.family.index')->with('success', 'Family member updated successfully.');
    }

    public function destroy($id)
    {
        $member = auth()->user()->familyMembers()->findOrFail($id);
        $member->delete();

        return redirect()->route('member.family.index')->with('success', 'Family member removed successfully.');
    }
}
