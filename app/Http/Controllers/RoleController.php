<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(): View
    {
        return view('super-admin.roles', [
            'roles' => Role::latest()->paginate(10),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:roles,name',
            'label' => 'required|string|max:100',
        ]);

        Role::create($validated);

        return back()->with('success', 'Role baru berhasil ditambahkan.');
    }
}
