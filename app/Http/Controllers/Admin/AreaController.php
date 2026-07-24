<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    /**
     * Display Area Management List
     */
    public function index(Request $request)
    {
        $query = Area::withCount('memberProfiles');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('pincode', 'like', "%{$search}%");
            });
        }

        $areas = $query->orderBy('name')->paginate(15);
        $totalAreas = Area::count();

        return view('admin.areas.index', compact('areas', 'totalAreas'));
    }

    /**
     * Store New Area
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:areas,name',
            'pincode' => 'nullable|string|max:10',
        ]);

        Area::create([
            'name' => $request->name,
            'pincode' => $request->pincode,
        ]);

        return redirect()->route('admin.areas.index')->with('success', 'Area added successfully.');
    }

    /**
     * Update Existing Area
     */
    public function update(Request $request, $id)
    {
        $area = Area::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:areas,name,' . $area->id,
            'pincode' => 'nullable|string|max:10',
        ]);

        $area->update([
            'name' => $request->name,
            'pincode' => $request->pincode,
        ]);

        return redirect()->route('admin.areas.index')->with('success', 'Area updated successfully.');
    }

    /**
     * Delete Area
     */
    public function destroy($id)
    {
        $area = Area::findOrFail($id);
        
        if ($area->memberProfiles()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete area as it is currently assigned to registered members.');
        }

        $area->delete();
        return redirect()->route('admin.areas.index')->with('success', 'Area deleted successfully.');
    }

    /**
     * Export Areas CSV / Excel
     */
    public function exportCsv(Request $request)
    {
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=areas_export_" . date('Y-m-d') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $query = Area::withCount('memberProfiles');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('pincode', 'like', "%{$search}%");
            });
        }

        $areas = $query->orderBy('name')->get();

        $callback = function() use ($areas) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, ['ID', 'Area Name', 'Pincode', 'Assigned Members Count', 'Created At']);

            foreach ($areas as $area) {
                fputcsv($file, [
                    $area->id,
                    $area->name,
                    $area->pincode ?? 'N/A',
                    $area->member_profiles_count ?? 0,
                    $area->created_at ? $area->created_at->format('Y-m-d H:i') : '',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
