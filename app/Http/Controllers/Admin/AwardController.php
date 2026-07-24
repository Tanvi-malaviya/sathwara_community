<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AwardApplication;
use Illuminate\Http\Request;

class AwardController extends Controller
{
    /**
     * List Applications
     */
    public function index(Request $request)
    {
        $query = AwardApplication::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('student_name', 'like', "%{$search}%")
                  ->orWhere('school', 'like', "%{$search}%")
                  ->orWhere('award_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.awards.index', compact('applications'));
    }

    /**
     * View Application Details
     */
    public function show($id)
    {
        $application = AwardApplication::with('user.memberProfile')->findOrFail($id);
        return view('admin.awards.show', compact('application'));
    }

    /**
     * Approve Application
     */
    public function approve(Request $request, $id)
    {
        $application = AwardApplication::findOrFail($id);
        $application->update([
            'status' => 'approved',
            'admin_notes' => $request->admin_notes ?? 'Criteria satisfied.',
        ]);

        return redirect()->back()->with('success', 'Award application approved.');
    }

    /**
     * Reject Application
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'required|string',
        ]);

        $application = AwardApplication::findOrFail($id);
        $application->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->back()->with('warning', 'Award application rejected.');
    }

    /**
     * Delete Application
     */
    public function destroy($id)
    {
        $application = AwardApplication::findOrFail($id);
        $application->delete();

        return redirect()->route('admin.awards.index')->with('success', 'Award application record removed.');
    }

    /**
     * Export Awards Applications CSV / Excel
     */
    public function exportCsv(Request $request)
    {
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=awards_export_" . date('Y-m-d') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $query = AwardApplication::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('student_name', 'like', "%{$search}%")
                  ->orWhere('school', 'like', "%{$search}%")
                  ->orWhere('award_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->orderBy('created_at', 'desc')->get();

        $callback = function() use ($applications) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, ['ID', 'Student Name', 'Parent Member', 'Standard', 'School', 'Total Marks', 'Obtained Marks', 'Percentage', 'Status', 'Submitted Date']);

            foreach ($applications as $app) {
                fputcsv($file, [
                    $app->id,
                    $app->student_name,
                    $app->user ? $app->user->name : '',
                    $app->standard ?? '',
                    $app->school ?? '',
                    $app->total_marks ?? '',
                    $app->received_marks ?? '',
                    $app->percentage ? $app->percentage.'%' : '',
                    ucfirst($app->status),
                    $app->created_at ? $app->created_at->format('Y-m-d H:i') : '',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
