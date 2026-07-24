<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Business;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\AwardApplication;
use App\Models\Update;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_members' => User::role('Member')->where('status', 'approved')->count(),
            'pending_members' => User::role('Member')->where('status', 'pending')->count(),
            'total_businesses' => Business::count(),
            'pending_businesses' => Business::where('status', 'pending')->count(),
            'total_events' => Event::count(),
            'gallery_images' => Gallery::count(),
            'award_applications' => AwardApplication::count(),
            'pending_awards' => AwardApplication::where('status', 'pending')->count(),
            'total_updates' => Update::count(),
        ];

        $latestMembers = User::role('Member')
            ->with('memberProfile')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $latestBusinesses = Business::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Chart Data - Monthly member registrations for the last 6 months
        // Let's get counts grouped by month
        $monthlyData = User::role('Member')
            ->select(DB::raw('count(id) as count'), DB::raw("DATE_FORMAT(created_at, '%b %Y') as month"), DB::raw('max(created_at) as max_date'))
            ->groupBy('month')
            ->orderBy('max_date', 'asc')
            ->take(6)
            ->get();

        $chartLabels = $monthlyData->pluck('month')->toArray();
        $chartValues = $monthlyData->pluck('count')->toArray();

        // Fallback if empty
        if (empty($chartLabels)) {
            $chartLabels = [date('b Y')];
            $chartValues = [0];
        }

        return view('admin.dashboard', compact('stats', 'latestMembers', 'latestBusinesses', 'chartLabels', 'chartValues'));
    }
}
