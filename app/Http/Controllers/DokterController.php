<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Support\Audit;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DokterController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search');
        $spesialis = $request->string('spesialis');
        
        $dokter = Dokter::query()
            ->with('jadwalDokter')
            ->when($search, fn ($q) => $q->where('nama', 'like', "%{$search}%")->orWhere('spesialisasi', 'like', "%{$search}%"))
            ->when($spesialis, fn ($q) => $q->where('spesialisasi', $spesialis))
            ->latest()
            ->paginate(8)
            ->withQueryString();

        $spesialisList = Dokter::query()->distinct()->pluck('spesialisasi')->sort();

        return view('dokter.index', compact('dokter', 'search', 'spesialis', 'spesialisList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'spesialisasi' => 'required|string|max:255',
            'no_str' => 'required|string|max:100|unique:dokter,no_str',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:1000',
        ]);

        $dokter = Dokter::create($validated);
        Audit::log($request, 'dokter.created', $dokter, null, $dokter->toArray());

        return back()->with('success', 'Data dokter berhasil ditambahkan.');
    }

    public function update(Request $request, Dokter $dokter): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'spesialisasi' => 'required|string|max:255',
            'no_str' => ['required', 'string', 'max:100', Rule::unique('dokter', 'no_str')->ignore($dokter->id)],
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:1000',
        ]);

        $old = $dokter->toArray();
        $dokter->update($validated);
        Audit::log($request, 'dokter.updated', $dokter, $old, $dokter->toArray());

        return back()->with('success', 'Data dokter berhasil diperbarui.');
    }

    public function destroy(Request $request, Dokter $dokter): RedirectResponse
    {
        $old = $dokter->toArray();
        $dokter->delete();
        Audit::log($request, 'dokter.deleted', $dokter, $old, null);

        return back()->with('success', 'Data dokter berhasil dihapus.');
    }
}
