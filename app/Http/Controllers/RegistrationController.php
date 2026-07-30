<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MemberProfile;
use App\Models\FamilyMember;
use App\Models\BusinessCategory;
use App\Models\Business;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class RegistrationController extends Controller
{
    /**
     * Display Single-page Registration Form
     */
    public function showMemberRegister()
    {
        $areas = Area::orderBy('name')->get();
        return view('public.register_member', compact('areas'));
    }

    /**
     * Final Submission of Membership Registration
     */
    public function submitMemberRegister(Request $request)
    {
        $validated = $request->validate([
            // Mandatory Fields (as per form spec)
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|digits:10',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'required|string',
            'area_id' => 'required|exists:areas,id',

            // Optional Fields
            'father_member_id' => 'nullable|string|max:50',
            'gender' => 'nullable|in:Male,Female,Other',
            'dob' => 'nullable|date|before:today',
            'blood_group' => 'nullable|string|max:10',
            'education' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|digits:10',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
        ]);

        // 1. Create Login User
        $user = User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'status' => 'pending',
        ]);

        // Assign Member role
        $memberRole = Role::findByName('Member');
        $user->assignRole($memberRole);

        // 2. Create Member Profile
        MemberProfile::create([
            'user_id' => $user->id,
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'father_member_id' => $validated['father_member_id'] ?? null,
            'gender' => $validated['gender'] ?? 'Male',
            'dob' => $validated['dob'] ?? null,
            'blood_group' => $validated['blood_group'] ?? null,
            'education' => $validated['education'] ?? null,
            'occupation' => $validated['occupation'] ?? null,
            'phone' => $validated['phone'],
            'whatsapp' => $validated['phone'],
            'address' => $validated['address'],
            'area_id' => $validated['area_id'],
            'city' => $validated['city'] ?? null,
            'state' => $validated['state'] ?? null,
            'pincode' => $validated['pincode'] ?? null,
            'photo_path' => null,
            'aadhaar_number' => null,
            'aadhaar_path' => null,
            'pan_number' => null,
            'pan_path' => null,
        ]);

        // Log the user in and redirect to account status page
        auth()->login($user);

        return redirect()->route('account.status')->with('success', 'Your membership registration has been submitted successfully and is pending approval.');
    }

    /**
     * Show Public Business Registration Form
     */
    public function showBusinessRegister()
    {
        $categories = BusinessCategory::orderBy('name')->get();
        $areas = Area::orderBy('name')->get();
        return view('public.register_business', compact('categories', 'areas'));
    }

    /**
     * Handle Business Registration Submission
     */
    public function submitBusinessRegister(Request $request)
    {
        $request->validate([
            'member_id' => 'required|string|max:255',
            'business_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:business_categories,id',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'area_id' => 'required|exists:areas,id',
            'phone' => 'required|digits:10',
            'whatsapp' => 'nullable|digits:10',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'youtube' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
            'logo' => 'required|image|max:2048', // Attach a Business Details / V.Card
            'gallery.*' => 'nullable|image|max:10240',
        ]);

        // Validate Member ID against Database
        $rawMemberId = trim($request->member_id);
        $numericId = (int) preg_replace('/[^0-9]/', '', $rawMemberId);

        $memberUser = null;
        if ($numericId > 0) {
            $memberUser = User::find($numericId);
        }
        
        if (!$memberUser) {
            $profile = MemberProfile::where('id', $numericId)
                        ->orWhere('phone', $rawMemberId)
                        ->first();
            if ($profile) {
                $memberUser = $profile->user;
            }
        }

        if (!$memberUser) {
            return back()->withInput()->withErrors([
                'member_id' => __('messages.invalid_member_id')
            ]);
        }

        if ($memberUser->status !== 'approved') {
            return back()->withInput()->withErrors([
                'member_id' => __('messages.pending_member_id')
            ]);
        }

        // Upload Logo
        $logoPath = $request->file('logo')->store('businesses/logos', 'public');

        // Upload Gallery Images
        $galleryPaths = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $path = $file->store('businesses/gallery', 'public');
                $galleryPaths[] = $path;
            }
        }

        // Create Business (linked to verified member user_id)
        Business::create([
            'user_id' => $memberUser->id,
            'category_id' => $request->category_id,
            'area_id' => $request->area_id,
            'member_id' => $rawMemberId,
            'business_name' => $request->business_name,
            'owner_name' => $request->owner_name,
            'description' => $request->description,
            'address' => $request->address,
            'phone' => $request->phone,
            'whatsapp' => $request->phone,
            'email' => $request->email,
            'website' => $request->website,
            'facebook' => $request->facebook,
            'instagram' => $request->instagram,
            'youtube' => $request->youtube,
            'linkedin' => $request->linkedin,
            'logo_path' => $logoPath,
            'gallery_images' => $galleryPaths,
            'status' => 'pending',
        ]);

        return redirect()->route('business.directory')->with('success', 'Your business registration has been submitted and is pending admin approval.');
    }
}
