<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Storage;

class BusinessController extends Controller
{
    /**
     * List Businesses
     */
    public function index(Request $request)
    {
        $query = Business::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                  ->orWhere('owner_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $businesses = $query->orderBy('created_at', 'desc')->paginate(15);
        $categories = BusinessCategory::withCount('businesses')->orderBy('name')->get();

        $pendingCount = Business::where('status', 'pending')->count();
        $approvedCount = Business::where('status', 'approved')->count();
        $rejectedCount = Business::where('status', 'rejected')->count();
        $totalCount = Business::count();

        return view('admin.businesses.index', compact('businesses', 'categories', 'pendingCount', 'approvedCount', 'rejectedCount', 'totalCount'));
    }

    /**
     * Approve Business
     */
    public function approve($id)
    {
        $business = Business::findOrFail($id);
        $business->update([
            'status' => 'approved',
            'membership_status' => 'active',
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Business directory entry approved successfully.');
    }

    /**
     * Reject Business
     */
    public function reject($id)
    {
        $business = Business::findOrFail($id);
        $business->update([
            'status' => 'rejected',
            'membership_status' => 'inactive',
            'approved_at' => null,
        ]);

        return redirect()->back()->with('warning', 'Business directory entry rejected.');
    }

    /**
     * Deactivate Business
     */
    public function deactivate($id)
    {
        $business = Business::findOrFail($id);
        $business->update([
            'membership_status' => 'inactive',
        ]);

        return redirect()->back()->with('warning', 'Business directory entry marked as inactive.');
    }

    /**
     * Activate Business
     */
    public function activate($id)
    {
        $business = Business::findOrFail($id);
        $business->update([
            'membership_status' => 'active',
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Business directory entry marked as active.');
    }

    /**
     * Delete Business
     */
    public function destroy($id)
    {
        $business = Business::findOrFail($id);
        $business->delete();

        return redirect()->route('admin.businesses.index')->with('success', 'Business directory entry deleted.');
    }

    /**
     * Show Business
     */
    public function show($id)
    {
        $business = Business::with('category')->findOrFail($id);
        return view('admin.businesses.show', compact('business'));
    }

    /**
     * Edit Business
     */
    public function edit($id)
    {
        $business = Business::findOrFail($id);
        $categories = BusinessCategory::orderBy('name')->get();
        $areas = \App\Models\Area::orderBy('name')->get();
        return view('admin.businesses.edit', compact('business', 'categories', 'areas'));
    }

    /**
     * Update Business
     */
    public function update(Request $request, $id)
    {
        $business = Business::findOrFail($id);

        $request->validate([
            'member_id' => 'required|string|max:255',
            'business_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:business_categories,id',
            'area_id' => 'required|exists:areas,id',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'phone' => 'required|digits:10',
            'whatsapp' => 'nullable|digits:10',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'youtube' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
            'status' => 'required|in:pending,approved,rejected',
            'membership_status' => 'required|in:active,inactive',
            'approved_at' => 'nullable|date',
            'logo' => 'nullable|image|max:2048',
            'payment_screenshot' => 'nullable|image|max:2048',
            'gallery.*' => 'nullable|image|max:10240',
        ]);

        $data = [
            'member_id' => $request->member_id,
            'area_id' => $request->area_id,
            'business_name' => $request->business_name,
            'owner_name' => $request->owner_name,
            'category_id' => $request->category_id,
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
            'status' => $request->status,
            'membership_status' => $request->membership_status,
        ];

        if ($request->filled('approved_at')) {
            $data['approved_at'] = $request->approved_at;
        } elseif ($request->status === 'approved' && !$business->approved_at) {
            $data['approved_at'] = now();
        } elseif ($request->status !== 'approved') {
            $data['approved_at'] = null;
        }

        if ($request->hasFile('logo')) {
            if ($business->logo_path && Storage::disk('public')->exists($business->logo_path)) {
                Storage::disk('public')->delete($business->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('businesses/logos', 'public');
        }

        if ($request->hasFile('payment_screenshot')) {
            if ($business->payment_screenshot_path && Storage::disk('public')->exists($business->payment_screenshot_path)) {
                Storage::disk('public')->delete($business->payment_screenshot_path);
            }
            $data['payment_screenshot_path'] = $request->file('payment_screenshot')->store('businesses/payments', 'public');
        }

        if ($request->hasFile('gallery')) {
            $galleryPaths = $business->gallery_images ?? [];
            foreach ($request->file('gallery') as $file) {
                $path = $file->store('businesses/gallery', 'public');
                $galleryPaths[] = $path;
            }
            $data['gallery_images'] = $galleryPaths;
        }

        if ($request->has('remove_gallery_images')) {
            $galleryPaths = $business->gallery_images ?? [];
            foreach ($request->remove_gallery_images as $imgToRemove) {
                if (in_array($imgToRemove, $galleryPaths)) {
                    if (Storage::disk('public')->exists($imgToRemove)) {
                        Storage::disk('public')->delete($imgToRemove);
                    }
                    $galleryPaths = array_diff($galleryPaths, [$imgToRemove]);
                }
            }
            $data['gallery_images'] = array_values($galleryPaths);
        }

        $business->update($data);

        return redirect()->route('admin.businesses.index')->with('success', 'Business directory entry updated successfully.');
    }

    /**
     * Category Index & Store
     */
    public function categories()
    {
        $categories = BusinessCategory::withCount('businesses')->orderBy('name')->paginate(10);
        return view('admin.businesses.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:business_categories,name',
        ]);

        BusinessCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.businesses.index')->with('success', 'Category created successfully.');
    }

    public function editCategory($id)
    {
        $category = BusinessCategory::findOrFail($id);
        return view('admin.businesses.edit_category', compact('category'));
    }

    public function updateCategory(Request $request, $id)
    {
        $category = BusinessCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:business_categories,name,' . $category->id,
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.businesses.index')->with('success', 'Category updated successfully.');
    }

    public function destroyCategory($id)
    {
        $category = BusinessCategory::findOrFail($id);
        
        if ($category->businesses()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete category containing registered businesses.');
        }

        $category->delete();
        return redirect()->route('admin.businesses.index')->with('success', 'Category deleted successfully.');
    }

    /**
     * Export Businesses CSV / Excel
     */
    public function exportCsv(Request $request)
    {
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=businesses_export_" . date('Y-m-d') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $query = Business::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                  ->orWhere('owner_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $businesses = $query->orderBy('created_at', 'desc')->get();

        $callback = function() use ($businesses) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, ['ID', 'Business Name', 'Owner Name', 'Category', 'Phone', 'Email', 'City', 'State', 'Status', 'Created At']);

            foreach ($businesses as $b) {
                fputcsv($file, [
                    $b->id,
                    $b->business_name,
                    $b->owner_name,
                    $b->category ? $b->category->name : '',
                    $b->phone ?? '',
                    $b->email ?? '',
                    $b->city ?? '',
                    $b->state ?? '',
                    ucfirst($b->status),
                    $b->created_at ? $b->created_at->format('Y-m-d H:i') : '',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
