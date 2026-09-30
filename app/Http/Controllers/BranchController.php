<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeSuperAdmin();

        $query = Branch::withCount('users');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->has('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        $branches = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('branches.index', compact('branches'));
    }

    public function create()
    {
        $this->authorizeSuperAdmin();
        return view('branches.create');
    }

    public function store(StoreBranchRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        $branch = Branch::create($data);

        return redirect()->route('branches.index')
            ->with('success', "Cabang \"{$branch->name}\" berhasil ditambahkan.");
    }

    public function show(Branch $branch)
    {
        $this->authorizeSuperAdmin();

        $branch->loadCount('users');
        $users = $branch->users()->with('roles')->latest()->limit(20)->get();

        return view('branches.show', compact('branch', 'users'));
    }

    public function edit(Branch $branch)
    {
        $this->authorizeSuperAdmin();
        return view('branches.edit', compact('branch'));
    }

    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        $branch->update($data);

        return redirect()->route('branches.index')
            ->with('success', "Cabang \"{$branch->name}\" berhasil diperbarui.");
    }

    public function destroy(Branch $branch)
    {
        $this->authorizeSuperAdmin();

        // Cek apakah masih ada user terkait
        if ($branch->users()->count() > 0) {
            return back()->with('error', 
                "Tidak dapat menghapus cabang. Masih ada {$branch->users()->count()} user terkait.");
        }

        // Cek apakah ada tiket terkait
        if ($branch->tickets()->count() > 0) {
            return back()->with('error', 
                "Tidak dapat menghapus cabang. Masih ada {$branch->tickets()->count()} tiket terkait.");
        }

        $name = $branch->name;
        $branch->delete();

        return redirect()->route('branches.index')
            ->with('success', "Cabang \"{$name}\" berhasil dihapus.");
    }

    private function authorizeSuperAdmin(): void
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403);
        }
    }
}