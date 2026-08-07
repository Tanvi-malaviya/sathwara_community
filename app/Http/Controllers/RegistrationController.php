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
            'photo' => 'nullable|image|max:2048',
        ]);

        // 1. Create Login User
        $user = User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'status' => 'pending',
        ]);
        $user->member_code = 'SSAM' . sprintf('%04d', $user->id);
        $user->save();

        // Assign Member role
        $memberRole = Role::findByName('Member');
        $user->assignRole($memberRole);

        // Fetch area info for city, state, pincode fallback
        $area = Area::find($validated['area_id']);

        // Handle Photo Upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('registrations/photos', 'public');
        }

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
            'city' => !empty($validated['city']) ? $validated['city'] : ($area ? $area->city : 'Ahmedabad'),
            'state' => !empty($validated['state']) ? $validated['state'] : ($area ? $area->state : 'Gujarat'),
            'pincode' => !empty($validated['pincode']) ? $validated['pincode'] : ($area ? $area->pincode : ''),
            'photo_path' => $photoPath,
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

        $existingBusiness = null;
        if (auth()->check()) {
            $user = auth()->user();
            $formattedMemberId = '#' . sprintf('%05d', $user->id);
            $existingBusiness = Business::where('user_id', $user->id)
                                ->orWhere('member_id', (string)$user->id)
                                ->orWhere('member_id', $formattedMemberId)
                                ->orWhere('member_id', '#' . $user->id)
                                ->first();
        }

        return view('public.register_business', compact('categories', 'areas', 'existingBusiness'));
    }

    /**
     * Handle Business Registration Submission
     */
    public function submitBusinessRegister(Request $request)
    {
        $request->validate([
            'member_id' => 'nullable|string|max:255',
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
            'logo' => 'required|file|mimes:jpeg,jpg,png,webp,gif,bmp,pdf|max:10240', // Attach Business Logo / Visiting Card
            'gallery' => 'nullable|array|max:6',
            'gallery.*' => 'nullable|file|mimes:jpeg,jpg,png,webp,gif,bmp|max:10240',
        ], [
            'logo.required' => 'Please upload your Business Logo or Visiting Card.',
            'logo.mimes' => 'Business Logo must be an image file (JPG, PNG, WEBP) or a PDF document.',
            'logo.max' => 'Business Logo file size must not exceed 10MB.',
        ]);

        $userId = null;
        $rawMemberId = null;

        if ($request->filled('member_id')) {
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
                    'member_id' => __('messages.member_id_not_found') ?? 'The entered Member ID does not exist in our database. Please check your Member ID.',
                ]);
            }

            $userId = $memberUser->id;
        }

        if (!$userId && auth()->check()) {
            $userId = auth()->id();
        }

        // Single Business per Member Constraint Check
        if ($userId) {
            $formattedMemberId = '#' . sprintf('%05d', $userId);
            $existingBusiness = Business::where('user_id', $userId)
                                ->orWhere('member_id', (string)$userId)
                                ->orWhere('member_id', $formattedMemberId)
                                ->orWhere('member_id', '#' . $userId)
                                ->first();

            if ($existingBusiness) {
                return back()->withInput()->withErrors([
                    'member_id' => "Each member is allowed to register only 1 business. You have already registered '{$existingBusiness->business_name}'. (દરેક સભ્ય માત્ર ૧ જ વ્યવસાય રજીસ્ટર કરી શકે છે.)",
                ]);
            }
        }

        if ($rawMemberId) {
            $existingBusiness = Business::where('member_id', $rawMemberId)->first();
            if ($existingBusiness) {
                return back()->withInput()->withErrors([
                    'member_id' => "A business ('{$existingBusiness->business_name}') has already been registered with Member ID '{$rawMemberId}'. Only 1 business registration per member is allowed.",
                ]);
            }
        }

        // Upload Logo
        $logoPath = $request->file('logo')->store('businesses/logos', 'public');

        // Upload Gallery Images (Max 6 Photos)
        $galleryPaths = [];
        if ($request->hasFile('gallery')) {
            $files = array_slice($request->file('gallery'), 0, 6);
            foreach ($files as $file) {
                $path = $file->store('businesses/gallery', 'public');
                $galleryPaths[] = $path;
            }
        }

        // Create Business (anyone can register)
        Business::create([
            'user_id' => $userId,
            'category_id' => $request->category_id,
            'area_id' => $request->area_id,
            'member_id' => $rawMemberId,
            'business_name' => $request->business_name,
            'owner_name' => $request->owner_name,
            'description' => $request->description ?? '',
            'address' => $request->address,
            'phone' => $request->phone,
            'whatsapp' => $request->whatsapp ?? $request->phone,
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

        return redirect()->route('business.directory')->with('success', 'Your business directory registration has been submitted successfully and is pending admin approval.');
    }

    /**
     * Live AJAX check if Member ID exists & single business limit check
     */
    public function checkMemberId(Request $request)
    {
        $memberId = trim($request->query('member_id', ''));
        if (empty($memberId)) {
            return response()->json(['found' => false, 'message' => '']);
        }

        $numericId = (int) preg_replace('/[^0-9]/', '', $memberId);
        $memberUser = null;
        if ($numericId > 0) {
            $memberUser = User::find($numericId);
        }

        if (!$memberUser) {
            $profile = MemberProfile::where('id', $numericId)
                        ->orWhere('phone', $memberId)
                        ->first();
            if ($profile) {
                $memberUser = $profile->user;
            }
        }

        if ($memberUser) {
            $name = $memberUser->memberProfile ? ($memberUser->memberProfile->first_name . ' ' . $memberUser->memberProfile->last_name) : $memberUser->name;
            $formattedMemberId = '#' . sprintf('%05d', $memberUser->id);

            // Check if member already registered a business
            $existingBusiness = Business::where('user_id', $memberUser->id)
                                ->orWhere('member_id', $memberId)
                                ->orWhere('member_id', (string)$memberUser->id)
                                ->orWhere('member_id', $formattedMemberId)
                                ->first();

            if ($existingBusiness) {
                return response()->json([
                    'found' => false,
                    'has_business' => true,
                    'message' => "❌ Member {$name} has already registered a business ('{$existingBusiness->business_name}'). Each member is allowed to register only 1 business."
                ]);
            }

            return response()->json([
                'found' => true,
                'name' => $name,
                'member_id' => $formattedMemberId,
                'message' => "Member Found: {$name} ({$formattedMemberId})"
            ]);
        }

        return response()->json([
            'found' => false,
            'message' => 'Member ID does not exist in database.'
        ]);
    }
}
