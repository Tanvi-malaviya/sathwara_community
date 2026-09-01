<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\AwardApplication;
use App\Services\AdminNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AwardController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->awardApplications();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('student_name', 'like', "%{$search}%")
                  ->orWhere('school', 'like', "%{$search}%")
                  ->orWhere('award_name', 'like', "%{$search}%");
            });
        }
        $applications = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        return view('member.award.index', compact('applications'));
    }

    public function create()
    {
        return view('member.award.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_name' => 'required|string|max:255',
            'standard' => 'required|string|max:100',
            'school' => 'required|string|max:255',
            'achievement' => 'required|string|max:255',
            'award_name' => 'required|string|max:255',
            'certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'remarks' => 'nullable|string',
        ]);

        $path = $request->file('certificate')->store('awards/certificates', 'public');

        $application = auth()->user()->awardApplications()->create([
            'student_name' => $request->student_name,
            'standard' => $request->standard,
            'school' => $request->school,
            'achievement' => $request->achievement,
            'award_name' => $request->award_name,
            'certificate_path' => $path,
            'remarks' => $request->remarks,
            'status' => 'pending',
        ]);

        AdminNotifier::send(
            permission: 'events_manage',
            type: 'award_application',
            title: 'New Award Application',
            message: "{$application->student_name} applied for \"{$application->award_name}\" ({$application->school})",
            url: route('admin.awards.show', $application->id),
            meta: ['application_id' => $application->id],
            color: 'amber'
        );

        return redirect()->route('member.awards.index')->with('success', 'Award application submitted successfully and is pending review.');
    }
}
