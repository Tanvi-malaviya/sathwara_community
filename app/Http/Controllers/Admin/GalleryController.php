<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * General Gallery Index
     */
    public function index(Request $request)
    {
        $query = Gallery::whereNull('event_id');
        if ($request->filled('search')) {
            $query->where('caption', 'like', "%{$request->search}%");
        }
        $photos = $query->orderBy('display_order')->paginate(15)->withQueryString();
        return view('admin.gallery.index', compact('photos'));
    }

    public function store(Request $request)
    {
        // Pre-check for PHP file upload errors (e.g. Disk Full, Temp Dir errors)
        $filesToCheck = [];
        if ($request->hasFile('images')) {
            $filesToCheck = is_array($request->file('images')) ? $request->file('images') : [$request->file('images')];
        } elseif ($request->hasFile('image')) {
            $filesToCheck = [$request->file('image')];
        }

        foreach ($filesToCheck as $file) {
            if ($file && !$file->isValid()) {
                $errorCode = $file->getError();
                if ($errorCode === UPLOAD_ERR_CANT_WRITE) {
                    return redirect()->back()->withErrors([
                        'images' => 'File upload failed: Server disk space (C: drive) is completely FULL! Please free up disk space on your computer.'
                    ])->withInput();
                } elseif ($errorCode === UPLOAD_ERR_NO_TMP_DIR) {
                    return redirect()->back()->withErrors([
                        'images' => 'File upload failed: PHP temporary folder is missing or not writable.'
                    ])->withInput();
                }
            }
        }

        $request->validate([
            'image' => 'nullable|file|mimes:zip,jpeg,png,jpg,gif,svg,webp|max:51200',
            'images.*' => 'nullable|image|max:51200',
            'caption' => 'nullable|string|max:255',
        ], [
            'images.*.uploaded' => 'One of the images failed to upload. Please verify that your computer drive has free disk space.',
            'images.*.image' => 'All selected files must be valid images.',
            'images.*.max' => 'Each image must be less than 50MB in size.',
            'image.uploaded' => 'The ZIP file failed to upload. Please verify that your computer drive has free disk space.',
        ]);

        $eventId = $request->input('event_id');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = strtolower($file->getClientOriginalExtension());

            if ($extension === 'zip') {
                if (!class_exists('\ZipArchive')) {
                    return redirect()->back()->with('error', 'PHP ZipArchive extension is not enabled on this server.');
                }

                $zip = new \ZipArchive();
                if ($zip->open($file->getRealPath()) === true) {
                    $tempPath = storage_path('app/temp_zip_' . time());
                    if (!file_exists($tempPath)) {
                        mkdir($tempPath, 0777, true);
                    }

                    $zip->extractTo($tempPath);
                    $zip->close();

                    $files = new \RecursiveIteratorIterator(
                        new \RecursiveDirectoryIterator($tempPath),
                        \RecursiveIteratorIterator::LEAVES_ONLY
                    );

                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
                    $uploadedCount = 0;

                    foreach ($files as $name => $f) {
                        if (!$f->isDir()) {
                            $filePath = $f->getRealPath();
                            $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

                            if (in_array($fileExtension, $allowedExtensions)) {
                                $fileName = 'zip_' . uniqid() . '.' . $fileExtension;
                                $destinationDir = $eventId ? 'events/gallery' : 'gallery/general';
                                Storage::disk('public')->makeDirectory($destinationDir);

                                $publicPath = $destinationDir . '/' . $fileName;
                                Storage::disk('public')->put($publicPath, file_get_contents($filePath));

                                Gallery::create([
                                    'event_id' => $eventId,
                                    'image_path' => $publicPath,
                                    'caption' => $request->caption ?? ($eventId ? 'Event Photo' : 'Gallery Photo'),
                                    'display_order' => Gallery::where('event_id', $eventId)->max('display_order') + 1,
                                ]);
                                $uploadedCount++;
                            }
                        }
                    }

                    $this->deleteDir($tempPath);

                    if ($uploadedCount === 0) {
                        return redirect()->back()->with('error', 'No valid images found in the ZIP archive.');
                    }

                    return redirect()->back()->with('success', "$uploadedCount photos extracted and uploaded successfully from ZIP archive.");
                } else {
                    return redirect()->back()->with('error', 'Failed to open the ZIP file.');
                }
            } else {
                $path = $file->store($eventId ? 'events/gallery' : 'gallery/general', 'public');
                Gallery::create([
                    'event_id' => $eventId,
                    'image_path' => $path,
                    'caption' => $request->caption ?? 'Community Event Photo',
                    'display_order' => Gallery::where('event_id', $eventId)->max('display_order') + 1,
                ]);
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store($eventId ? 'events/gallery' : 'gallery/general', 'public');
                Gallery::create([
                    'event_id' => $eventId,
                    'image_path' => $path,
                    'caption' => $request->caption ?? 'Community Event Photo',
                    'display_order' => Gallery::where('event_id', $eventId)->max('display_order') + 1,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Photos added successfully.');
    }

    /**
     * Delete Photo
     */
    public function destroy($id)
    {
        $photo = Gallery::findOrFail($id);

        if (Storage::disk('public')->exists($photo->image_path) && !str_starts_with($photo->image_path, 'http')) {
            Storage::disk('public')->delete($photo->image_path);
        }

        $photo->delete();
        return redirect()->back()->with('success', 'Photo deleted successfully.');
    }

    /**
     * Recursive folder deletion helper
     */
    private function deleteDir($dirPath)
    {
        if (!is_dir($dirPath)) {
            return;
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    /**
     * Export General Gallery CSV / Excel
     */
    public function exportCsv(Request $request)
    {
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=gallery_export_" . date('Y-m-d') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $query = Gallery::whereNull('event_id');
        if ($request->filled('search')) {
            $query->where('caption', 'like', "%{$request->search}%");
        }
        $photos = $query->orderBy('display_order')->get();

        $callback = function() use ($photos) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, ['ID', 'Caption', 'Image Path', 'Display Order', 'Uploaded At']);

            foreach ($photos as $p) {
                fputcsv($file, [
                    $p->id,
                    $p->caption ?? '',
                    $p->image_path ?? '',
                    $p->display_order ?? 0,
                    $p->created_at ? $p->created_at->format('Y-m-d H:i') : '',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
