<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MemberProfile;
use App\Models\FamilyMember;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * List Members
     */
    public function index(Request $request)
    {
        $query = User::role('Member')->with(['memberProfile.area']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('member_code', 'like', "%{$search}%")
                    ->orWhereHas('memberProfile', function ($sub) use ($search) {
                        $sub->where('phone', 'like', "%{$search}%")
                            ->orWhere('city', 'like', "%{$search}%")
                            ->orWhereHas('area', function ($areaSub) use ($search) {
                                $areaSub->where('name', 'like', "%{$search}%");
                            });
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('city')) {
            $city = $request->city;
            $query->whereHas('memberProfile', function ($q) use ($city) {
                $q->where('city', 'like', "%{$city}%");
            });
        }

        $members = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        // Get status counts for filter tabs
        $pendingCount = User::role('Member')->where('status', 'pending')->count();
        $approvedCount = User::role('Member')->where('status', 'approved')->count();
        $rejectedCount = User::role('Member')->where('status', 'rejected')->count();
        $allCount = User::role('Member')->count();

        // Get all unique cities for filtering options
        $cities = MemberProfile::select('city')->distinct()->pluck('city')->toArray();

        return view('admin.members.index', compact('members', 'cities', 'pendingCount', 'approvedCount', 'rejectedCount', 'allCount'));
    }

    /**
     * Show Create Member Form
     */
    public function create()
    {
        $areas = \App\Models\Area::orderBy('name')->get();
        return view('admin.members.create', compact('areas'));
    }

    /**
     * Store New Member
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'status' => 'required|in:pending,approved,rejected',
            'account_status' => 'nullable|in:open,close',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'dob' => 'required|date|before_or_equal:today',
            'phone' => 'required|digits:10',
            'whatsapp' => 'nullable|digits:10',
            'area_id' => 'nullable|exists:areas,id',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'address' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $fullName = trim(preg_replace('/\s+/', ' ', implode(' ', array_filter([$request->first_name, $request->middle_name, $request->last_name]))));

        // Create User
        $user = User::create([
            'name' => $fullName,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'status' => $request->status,
            'account_status' => $request->account_status ?? 'open',
        ]);

        // Assign Member Role
        $user->assignRole('Member');

        $photoPath = 'NOT_SPECIFIED';
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('profile_photos', 'public');
        }

        $pincode = $request->pincode;
        if ($request->filled('area_id')) {
            $selectedArea = \App\Models\Area::find($request->area_id);
            if ($selectedArea && $selectedArea->pincode) {
                $pincode = $selectedArea->pincode;
            }
        }

        // Create Member Profile
        MemberProfile::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'phone' => $request->phone,
            'whatsapp' => $request->phone,
            'address' => $request->address,
            'area_id' => $request->area_id,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $pincode,
            'photo_path' => $photoPath,
            'aadhaar_number' => 'NOT_SPECIFIED',
            'aadhaar_path' => 'NOT_SPECIFIED',
        ]);

        return redirect()->route('admin.members.index')->with('success', 'Member created successfully.');
    }

    /**
     * View Details
     */
    public function show($id)
    {
        $member = User::role('Member')->with(['memberProfile.area', 'familyMembers'])->findOrFail($id);
        return view('admin.members.show', compact('member'));
    }

    /**
     * Edit Member
     */
    public function edit($id)
    {
        $member = User::role('Member')->with(['memberProfile.area'])->findOrFail($id);
        $areas = \App\Models\Area::orderBy('name')->get();
        return view('admin.members.edit', compact('member', 'areas'));
    }

    /**
     * Update Member Details
     */
    public function update(Request $request, $id)
    {
        $member = User::role('Member')->findOrFail($id);
        $profile = $member->memberProfile;

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $member->id,
            'status' => 'required|in:pending,approved,rejected',
            'account_status' => 'required|in:open,close',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'dob' => 'required|date|before_or_equal:today',
            'phone' => 'required|digits:10',
            'whatsapp' => 'nullable|digits:10',
            'area_id' => 'nullable|exists:areas,id',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'address' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $fullName = trim(preg_replace('/\s+/', ' ', implode(' ', array_filter([$request->first_name, $request->middle_name, $request->last_name]))));

        $member->update([
            'name' => $fullName,
            'email' => $request->email,
            'status' => $request->status,
            'account_status' => $request->account_status,
        ]);

        $pincode = $request->pincode ?: ($profile ? $profile->pincode : null);
        if ($request->filled('area_id')) {
            $selectedArea = \App\Models\Area::find($request->area_id);
            if ($selectedArea && $selectedArea->pincode) {
                $pincode = $selectedArea->pincode;
            }
        }

        if ($profile) {
            $updateData = [
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'father_member_id' => $request->father_member_id,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'phone' => $request->phone,
                'whatsapp' => $request->phone,
                'address' => $request->address,
                'area_id' => $request->area_id,
                'city' => $request->city,
                'state' => $request->state,
                'pincode' => $pincode,
            ];

            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($profile->photo_path && $profile->photo_path !== 'NOT_SPECIFIED' && \Illuminate\Support\Facades\Storage::disk('public')->exists($profile->photo_path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($profile->photo_path);
                }
                $updateData['photo_path'] = $request->file('photo')->store('profile_photos', 'public');
            }

            $profile->update($updateData);
        } else {
            $photoPath = 'NOT_SPECIFIED';
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('profile_photos', 'public');
            }

            MemberProfile::create([
                'user_id' => $member->id,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'phone' => $request->phone,
                'whatsapp' => $request->phone,
                'address' => $request->address,
                'area_id' => $request->area_id,
                'city' => $request->city,
                'state' => $request->state,
                'pincode' => $pincode,
                'photo_path' => $photoPath,
                'aadhaar_number' => 'NOT_SPECIFIED',
                'aadhaar_path' => 'NOT_SPECIFIED',
            ]);
        }

        return redirect()->route('admin.members.show', $member->id)->with('success', 'Member details updated successfully.');
    }

    private function checkEditPermission()
    {
        $user = auth()->user();
        if ($user->hasRole('Administrator')) {
            return;
        }
        $userPerms = $user->permissions->pluck('name');
        if ($userPerms->contains('members_manage') || $userPerms->contains('members_edit')) {
            return;
        }
        abort(403, 'You do not have permission to approve, reject, or modify members.');
    }

    /**
     * Approve Member
     */
    public function approve($id)
    {
        $this->checkEditPermission();

        $member = User::role('Member')->findOrFail($id);
        $member->update([
            'status' => 'approved',
            'rejection_reason' => null,
        ]);

        return redirect()->back()->with('success', 'Member approved successfully.');
    }

    /**
     * Reject Member
     */
    public function reject(Request $request, $id)
    {
        $this->checkEditPermission();

        $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $member = User::role('Member')->findOrFail($id);
        $member->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        // Send Email Notice

        return redirect()->back()->with('warning', 'Member registration rejected.');
    }

    /**
     * Toggle Account Status (Open / Close)
     */
    public function toggleAccountStatus($id)
    {
        $this->checkEditPermission();

        $member = User::role('Member')->findOrFail($id);
        $newStatus = ($member->account_status === 'close') ? 'open' : 'close';
        $member->update([
            'account_status' => $newStatus,
        ]);

        $message = ($newStatus === 'open') ? 'Member account opened successfully.' : 'Member account closed successfully.';
        return redirect()->back()->with('success', $message);
    }

    /**
     * Soft Delete Member
     */
    public function destroy($id)
    {
        $member = User::role('Member')->findOrFail($id);
        $member->delete();

        return redirect()->route('admin.members.index')->with('success', 'Member deleted successfully.');
    }

    /**
     * CSV/Excel Export
     */
    public function exportCsv(Request $request)
    {
        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=members_export_" . date('Y-m-d') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $query = User::role('Member')->with(['memberProfile', 'familyMembers']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $members = $query->orderBy('id', 'desc')->get();

        $callback = function () use ($members) {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM for Excel to display Noto Sans Gujarati text correctly
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                __('messages.csv_id'),
                __('messages.csv_name'),
                __('messages.csv_email'),
                __('messages.csv_status'),
                __('messages.csv_phone'),
                __('messages.csv_gender'),
                __('messages.csv_dob'),
                __('messages.csv_city'),
                __('messages.csv_state'),
                __('messages.csv_pincode'),
                __('messages.csv_address'),
                __('messages.csv_family_count')
            ]);

            foreach ($members as $member) {
                $profile = $member->memberProfile;
                $gender = $profile ? strtolower($profile->gender ?? '') : '';
                $statusKey = strtolower($member->status ?? '');

                fputcsv($file, [
                    $member->member_code ?: $member->formatted_member_id,
                    $member->name,
                    $member->email,
                    __('messages.' . $statusKey) != 'messages.' . $statusKey ? __('messages.' . $statusKey) : ucfirst($member->status),
                    $profile ? $profile->phone : '',
                    $gender && __('messages.' . $gender) != 'messages.' . $gender ? __('messages.' . $gender) : ($profile ? $profile->gender : ''),
                    $profile ? $profile->dob : '',
                    $profile ? $profile->city : '',
                    $profile ? $profile->state : '',
                    $profile ? $profile->pincode : '',
                    $profile ? $profile->address : '',
                    $member->familyMembers->count(),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Print Members List
     */
    public function printList(Request $request)
    {
        $query = User::role('Member')->with('memberProfile');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('member_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $members = $query->orderBy('name')->get();

        return view('admin.members.print', compact('members'));
    }
}
