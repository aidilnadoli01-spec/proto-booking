<?php

namespace App\Http\Controllers;

use App\Models\Antrean;
use App\Notifications\QueueCalledNotification;
use App\Support\Audit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdminAntreanController extends Controller
{
    public function callNext(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'dokter_id' => 'nullable|exists:dokter,id',
        ]);

        $tanggal = $validated['tanggal'];
        $dokterId = $validated['dokter_id'] ?? null;

        $antrean = DB::transaction(function () use ($tanggal, $dokterId, $request) {
            $query = Antrean::with(['jadwalDokter.dokter', 'pendaftaran.pasien.user'])
                ->whereDate('tanggal_periksa', $tanggal)
                ->where('status', 'menunggu')
                ->when($dokterId, function ($q) use ($dokterId) {
                    $q->whereHas('jadwalDokter', fn ($jadwal) => $jadwal->where('dokter_id', $dokterId));
                })
                ->orderBy('nomor_antrean')
                ->lockForUpdate();

            $next = $query->first();

            if (! $next) {
                throw ValidationException::withMessages([
                    'tanggal' => 'Tidak ada antrean menunggu untuk filter tersebut.',
                ]);
            }

            $old = $next->only(['status', 'dipanggil_pada']);

            $next->update([
                'status' => 'dipanggil',
                'dipanggil_pada' => now(),
                'updated_by_user_id' => $request->user()?->id,
            ]);

            Audit::log($request, 'queue.called', $next, $old, $next->only(['status', 'dipanggil_pada', 'updated_by_user_id']));

            return $next;
        });

        $user = $antrean->pendaftaran->first()?->pasien?->user;
        if ($user) {
            $user->notify(new QueueCalledNotification($antrean));
        }

        return back()->with('success', "Antrean dipanggil: #{$antrean->nomor_antrean}");
    }

    public function updateStatus(Request $request, Antrean $antrean): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:menunggu,dipanggil,selesai,batal',
        ]);

        $antrean->load(['pendaftaran.pasien.user', 'jadwalDokter.dokter']);

        $old = $antrean->only(['status', 'dipanggil_pada', 'selesai_pada', 'dibatalkan_pada']);
        $status = $validated['status'];

        $patch = [
            'status' => $status,
            'updated_by_user_id' => $request->user()?->id,
        ];

        if ($status === 'dipanggil') {
            $patch['dipanggil_pada'] = $antrean->dipanggil_pada ?? now();
        }

        if ($status === 'selesai') {
            $patch['selesai_pada'] = now();
        }

        if ($status === 'batal') {
            $patch['dibatalkan_pada'] = now();
        }

        DB::transaction(function () use ($antrean, $patch, $request, $old) {
            $antrean->update($patch);

            if ($patch['status'] === 'batal') {
                $antrean->pendaftaran()->where('status_kunjungan', '!=', 'dibatalkan')->update([
                    'status_kunjungan' => 'dibatalkan',
                ]);
            }

            if ($patch['status'] === 'selesai') {
                $antrean->pendaftaran()->where('status_kunjungan', '!=', 'selesai')->update([
                    'status_kunjungan' => 'selesai',
                ]);
            }

            Audit::log($request, 'queue.status_updated', $antrean, $old, $antrean->only(['status', 'dipanggil_pada', 'selesai_pada', 'dibatalkan_pada', 'updated_by_user_id']));
        });

        return back()->with('success', 'Status antrean berhasil diperbarui.');
    }
}

