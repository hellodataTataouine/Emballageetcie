<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CatalogController extends Controller
{
    public function togglePublishStatus(Request $request, $id)
    {
        $catalog = Catalog::findOrFail($id);

        $catalog->is_published = !$catalog->is_published;
        $catalog->save();

        return redirect()->route('admin.catalogues.index')->with('success', 'Publish status toggled successfully!');
    }

    public function show(Request $request, $id)
    {
        $catalog = Catalog::findOrFail($id);

        return view('backend.pages.catalogues.show', compact('catalog'));
    }

    public function displayPdf(Request $request, $id)
    {
        $catalog = Catalog::findOrFail($id);
        $filePath = storage_path('app/public/' . $catalog->file_path);

        if (!file_exists($filePath)) {
            abort(404);
        }

        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $catalog->name . '.pdf"',
        ];

        return response()->file($filePath, $headers);
    }

    public function index(Request $request)
    {
        $searchKey = null;
        $catalogues = Catalog::oldest();

        if ($request->search != null) {
            $catalogues = $catalogues->where('name', 'like', '%' . $request->search . '%');
            $searchKey = $request->search;
        }

        $catalogues = $catalogues->paginate(paginationNumber());
        return view('backend.pages.catalogues.index', compact('catalogues', 'searchKey'));
    }

    public function create()
    {
        return view('backend.pages.catalogues.create');
    }

    public function store(Request $request)
{
    try {
        $request->validate([
            'name' => 'required',
            'pdf_file' => 'required|mimes:pdf|max:10240',
            'banner' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle the upload of PDF file to the Media Manager
        $pdfPath = handleMediaUpload('pdf', 'pdf_file', 'catalogues');

        // Handle the upload of the banner to the Media Manager
        $bannerPath = handleMediaUpload('image', 'banner', 'banners');

        $catalog = Catalog::create([
            'name' => $request->name,
            'file_path' => $pdfPath,
            'banner' => $bannerPath,
        ]);

        return redirect()->route('admin.catalogues.index')->with('success', 'Catalog uploaded successfully!');
    } catch (\Exception $e) {
        // Log the error for troubleshooting
        \Log::error('Catalog upload failed: ' . $e->getMessage());
        return redirect()->route('admin.catalogues.create')->with('error', 'Catalog upload failed. Please try again.');
    }
}


    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'pdf_file' => 'nullable|mimes:pdf|max:10240',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $catalog = Catalog::findOrFail($request->id);

        $catalog->name = $request->name;

        if ($request->hasFile('pdf_file')) {
            $pdfPath = $this->handleMediaUpload('pdf', 'pdf_file', 'catalogues');
            // Delete the old PDF file
            Storage::disk('public')->delete($catalog->file_path);
            $catalog->file_path = $pdfPath;
        }

        if ($request->hasFile('banner')) {
            // Handle the upload of the new banner to the Media Manager
            $bannerPath = $this->handleMediaUpload('image', 'banner', 'banners');
            // Delete the old banner
            Storage::disk('public')->delete($catalog->banner);
            $catalog->banner = $bannerPath;
        }

        $catalog->save();

        return redirect()->route('admin.catalogues.index')->with('success', 'Catalog updated successfully!');
    }

    public function edit(Request $request, $id)
    {
        $catalog = Catalog::findOrFail($id);
        return view('backend.pages.catalogues.edit', compact('catalog'));
    }

    public function delete($id)
    {
        $catalog = Catalog::findOrFail($id);
        Storage::disk('public')->delete($catalog->file_path);
        Storage::disk('public')->delete($catalog->banner);
        $catalog->delete();

        return redirect()->route('admin.catalogues.index')->with('success', 'Catalog deleted successfully!');
    }

    // Function to handle media upload
    private function handleMediaUpload($type, $fieldName, $directory)
    {
        $file = request()->file($fieldName);
        $fileName = $file->getClientOriginalName();

        // Store the file in the specified directory
        $path = $file->storeAs($directory, $fileName, 'public');

        return $path;
    }
}
