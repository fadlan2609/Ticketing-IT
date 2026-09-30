<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDivisionRequest;
use App\Http\Requests\UpdateDivisionRequest;
use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeSuperAdmin();

        $query = Division::withCount('users');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        $divisions = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('divisions.index', compact('divisions'));
    }

    public function create()
    {
        $this->authorizeSuperAdmin();
        return view('divisions.create');
    }

    public function store(StoreDivisionRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        $division = Division::create($data);

        return redirect()->route('divisions.index')
            ->with('success', "Divisi \"{$division->name}\" berhasil ditambahkan.");
    }

    public function show(Division $division)
    {
        $this->authorizeSuperAdmin();

        $division->loadCount('users');
        $users = $division->users()->with('roles')->latest()->limit(20)->get();

        return view('divisions.show', compact('division', 'users'));
    }

    public function edit(Division $division)
    {
        $this->authorizeSuperAdmin();
        return view('divisions.edit', compact('division'));
    }

    public function update(UpdateDivisionRequest $request, Division $division)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        $division->update($data);

        return redirect()->route('divisions.index')
            ->with('success', "Divisi \"{$division->name}\" berhasil diperbarui.");
    }

    public function destroy(Division $division)
    {
        $this->authorizeSuperAdmin();

        if ($division->users()->count() > 0) {
            return back()->with('error', 
                "Tidak dapat menghapus divisi. Masih ada {$division->users()->count()} user terkait.");
        }

        $name = $division->name;
        $division->delete();

        return redirect()->route('divisions.index')
            ->with('success', "Divisi \"{$name}\" berhasil dihapus.");
    }

    private function authorizeSuperAdmin(): void
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403);
        }
    }
}