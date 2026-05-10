<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Support\Audit;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class JadwalDokterController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search');
        $spesialis = $request->string('spesialis');

        $jadwal = JadwalDokter::with('dokter')
            ->where('aktif', true)
            ->when($search, fn ($q) => $q->whereHas('dokter', fn ($sub) => $sub->where('nama', 'like', "%{$search}%")))
            ->when($spesialis, fn ($q) => $q->whereHas('dokter', fn ($sub) => $sub->where('spesialisasi', $spesialis)))
            ->orderBy('hari')
            ->paginate(8)
            ->withQueryString();

        return view('jadwal.index', [
            'jadwal' => $jadwal,
            'dokterList' => Dokter::orderBy('nama')->get(),
            'spesialisList' => Dokter::query()->distinct()->pluck('spesialisasi'),
            'search' => $search,
            'spesialis' => $spesialis,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'dokter_id' => 'required|exists:dokter,id',
            'hari' => 'required|string|max:20',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'kuota' => 'required|integer|min:1|max:100',
        ]);

        $jadwal = JadwalDokter::create($validated);
        Audit::log($request, 'jadwal.created', $jadwal, null, $jadwal->toArray());

        return back()->with('success', 'Jadwal dokter berhasil ditambahkan.');
    }

    public function update(Request $request, JadwalDokter $jadwal): RedirectResponse
    {
        $validated = $request->validate([
            'dokter_id' => 'required|exists:dokter,id',
            'hari' => 'required|string|max:20',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'kuota' => 'required|integer|min:1|max:100',
            'aktif' => 'nullable|boolean',
        ]);

        $old = $jadwal->toArray();
        $jadwal->update([
            ...$validated,
            'aktif' => $request->boolean('aktif'),
        ]);
        Audit::log($request, 'jadwal.updated', $jadwal, $old, $jadwal->toArray());

        return back()->with('success', 'Jadwal dokter berhasil diperbarui.');
    }

    public function destroy(JadwalDokter $jadwal): RedirectResponse
    {
        $old = $jadwal->toArray();
        $jadwal->delete();
        Audit::log($request, 'jadwal.deleted', $jadwal, $old, null);

        return back()->with('success', 'Jadwal dokter berhasil dihapus.');
    }
}
