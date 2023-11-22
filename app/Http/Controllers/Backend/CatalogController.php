<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class CatalogController extends Controller
{
    public function __construct()
    {

    }
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
        $request->validate([
            'name' => 'required',
            'pdf_file' => 'required|mimes:pdf|max:10240', // Adjust the max file size as needed
        ]);

        $file = $request->file('pdf_file');
        $path = $file->store('catalogues', 'public');

        Catalog::create([
            'name' => $request->name,
            'file_path' => $path,
        ]);

        return redirect()->route('admin.catalogues.index')->with('success', 'Catalog uploaded successfully!');
    }

    public function edit(Request $request, $id)
    {
        $catalog = Catalog::findOrFail($id);
        return view('backend.pages.catalogues.edit', compact('catalog'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'pdf_file' => 'nullable|mimes:pdf|max:10240', // Adjust the max file size as needed
        ]);

        $catalog = Catalog::findOrFail($request->id);

        $catalog->name = $request->name;

        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $path = $file->store('catalogues', 'public');
            $catalog->file_path = $path;
        }

        $catalog->save();

        return redirect()->route('admin.catalogues.index')->with('success', 'Catalog updated successfully!');
    }

    public function delete($id)
    {
        $catalog = Catalog::findOrFail($id);
        Storage::disk('public')->delete($catalog->file_path);
        $catalog->delete();

        return redirect()->route('admin.catalogues.index')->with('success', 'Catalog deleted successfully!');
    }
}
