<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOfficeInfoRequest;
use App\Http\Requests\UpdateOfficeInfoRequest;
use App\Models\OfficeInfo;
use Illuminate\Http\Request;

class OfficeInfoController extends Controller
{
    /**
     * Daftar informasi kantor
     */
    public function index(Request $request)
    {
        $query = OfficeInfo::with('author');

        // Filter: hanya Super Admin yang bisa lihat draft
        if (!auth()->user()->hasRole('Super Admin')) {
            $query->published();
        }

        // Filter kategori
        if ($category = $request->input('category')) {
            $query->byCategory($category);
        }

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $infos = $query->ordered()->paginate(12)->withQueryString();

        // Statistik kategori
        $categoryCounts = OfficeInfo::published()
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        return view('office-info.index', compact('infos', 'categoryCounts'));
    }

    /**
     * Detail informasi
     */
    public function show(OfficeInfo $officeInfo)
    {
        // Cek publish (kecuali Super Admin)
        if (!$officeInfo->is_published && !auth()->user()->hasRole('Super Admin')) {
            abort(404);
        }

        $officeInfo->incrementViews();
        $officeInfo->load('author');

        // Info terkait (kategori sama)
        $relatedInfos = OfficeInfo::published()
            ->where('id', '!=', $officeInfo->id)
            ->where('category', $officeInfo->category)
            ->ordered()
            ->limit(3)
            ->get();

        return view('office-info.show', compact('officeInfo', 'relatedInfos'));
    }

    /**
     * Form buat informasi baru (Super Admin)
     */
    public function create()
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403);
        }

        return view('office-info.create');
    }

    /**
     * Simpan informasi baru
     */
    public function store(StoreOfficeInfoRequest $request)
    {
        $data = $request->validated();
        $data['author_id'] = auth()->id();
        $data['is_pinned'] = $request->boolean('is_pinned');
        $data['is_published'] = $request->boolean('is_published', true);

        $info = OfficeInfo::create($data);

        return redirect()->route('office-info.show', $info)
            ->with('success', 'Informasi berhasil dibuat.');
    }

    /**
     * Form edit
     */
    public function edit(OfficeInfo $officeInfo)
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403);
        }

        return view('office-info.edit', compact('officeInfo'));
    }

    /**
     * Update informasi
     */
    public function update(UpdateOfficeInfoRequest $request, OfficeInfo $officeInfo)
    {
        $data = $request->validated();
        $data['is_pinned'] = $request->boolean('is_pinned');
        $data['is_published'] = $request->boolean('is_published', true);

        $officeInfo->update($data);

        return redirect()->route('office-info.show', $officeInfo)
            ->with('success', 'Informasi berhasil diperbarui.');
    }

    /**
     * Hapus informasi
     */
    public function destroy(OfficeInfo $officeInfo)
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403);
        }

        $officeInfo->delete();

        return redirect()->route('office-info.index')
            ->with('success', 'Informasi berhasil dihapus.');
    }
}