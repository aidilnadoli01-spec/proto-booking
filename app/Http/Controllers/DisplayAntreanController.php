<?php

namespace App\Http\Controllers;

use App\Models\Antrean;
use App\Models\Dokter;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DisplayAntreanController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'tanggal' => 'nullable|date',
            'dokter_id' => 'nullable|exists:dokter,id',
            'mode' => 'nullable|in:tv,web',
        ]);

        $tanggal = $validated['tanggal'] ?? now()->toDateString();
        $dokterId = $validated['dokter_id'] ?? null;
        $mode = $validated['mode'] ?? 'web';

        $baseQuery = Antrean::with(['jadwalDokter.dokter'])
            ->whereDate('tanggal_periksa', $tanggal)
            ->when($dokterId, function ($q) use ($dokterId) {
                $q->whereHas('jadwalDokter', fn ($jadwal) => $jadwal->where('dokter_id', $dokterId));
            })
            ->orderBy('nomor_antrean');

        $currentCalled = (clone $baseQuery)->where('status', 'dipanggil')->first();

        $nextWaiting = (clone $baseQuery)
            ->where('status', 'menunggu')
            ->limit(10)
            ->get();

        return view('display.antrean', [
            'tanggal' => $tanggal,
            'dokterId' => $dokterId,
            'dokterList' => Dokter::orderBy('nama')->get(),
            'currentCalled' => $currentCalled,
            'nextWaiting' => $nextWaiting,
            'refreshSeconds' => 10,
            'mode' => $mode,
        ]);
    }

    public function data(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'nullable|date',
            'dokter_id' => 'nullable|exists:dokter,id',
        ]);

        $tanggal = $validated['tanggal'] ?? now()->toDateString();
        $dokterId = $validated['dokter_id'] ?? null;

        $baseQuery = Antrean::with(['jadwalDokter.dokter'])
            ->whereDate('tanggal_periksa', $tanggal)
            ->when($dokterId, function ($q) use ($dokterId) {
                $q->whereHas('jadwalDokter', fn ($jadwal) => $jadwal->where('dokter_id', $dokterId));
            })
            ->orderBy('nomor_antrean');

        $currentCalled = (clone $baseQuery)->where('status', 'dipanggil')->first();

        $nextWaiting = (clone $baseQuery)
            ->where('status', 'menunggu')
            ->limit(10)
            ->get()
            ->map(function ($q) {
                return [
                    'id' => $q->id,
                    'nomor_antrean' => $q->nomor_antrean,
                    'dokter' => $q->jadwalDokter?->dokter?->nama,
                ];
            })
            ->values();

        return response()->json([
            'tanggal' => $tanggal,
            'dokter_id' => $dokterId,
            'current_called' => $currentCalled ? [
                'id' => $currentCalled->id,
                'nomor_antrean' => $currentCalled->nomor_antrean,
                'dokter' => $currentCalled->jadwalDokter?->dokter?->nama,
                'jam_mulai' => (string) ($currentCalled->jadwalDokter?->jam_mulai ?? ''),
                'jam_selesai' => (string) ($currentCalled->jadwalDokter?->jam_selesai ?? ''),
            ] : null,
            'next_waiting' => $nextWaiting,
        ]);
    }
}

