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
        $family = auth()->user()->familyMembers;
        return view('member.family.create', compact('family'));
    }

    public function store(Request $request)
    {
        $userProfile = auth()->user()->memberProfile;
        $parentDob = $userProfile && $userProfile->dob ? $userProfile->dob : null;

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
        $member = auth()->user()->familyMembers()->findOrFail($id);
        $family = auth()->user()->familyMembers()->where('id', '!=', $id)->get();
        return view('member.family.edit', compact('member', 'family'));
    }

    public function update(Request $request, $id)
    {
        $member = auth()->user()->familyMembers()->findOrFail($id);
        $userProfile = auth()->user()->memberProfile;
        $parentDob = $userProfile && $userProfile->dob ? $userProfile->dob : null;

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
