<?php

namespace App\Http\Controllers;

use App\Models\Antrean;
use App\Models\JadwalDokter;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Notifications\BookingCancelledNotification;
use App\Notifications\BookingCreatedNotification;
use App\Support\Audit;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function create(Request $request): View
    {
        $jadwal = JadwalDokter::with('dokter')
            ->where('aktif', true)
            ->orderBy('hari')
            ->get();

        return view('booking.create', [
            'jadwal' => $jadwal,
            'selectedJadwal' => $request->integer('jadwal_id'),
        ]);
    }

    public function index(Request $request): View
    {
        $user = $request->user();

        $bookings = Pendaftaran::with('antrean.jadwalDokter.dokter')
            ->where('pasien_id', $user?->pasien?->id)
            ->latest()
            ->paginate(10);

        return view('booking.index', [
            'bookings' => $bookings,
        ]);
    }

    public function rescheduleForm(Request $request, Pendaftaran $pendaftaran): View
    {
        $user = $request->user();
        $pasienId = $user?->pasien?->id;

        if (! $pasienId || $pendaftaran->pasien_id !== $pasienId) {
            abort(403);
        }

        $pendaftaran->load('antrean.jadwalDokter.dokter');

        $jadwal = JadwalDokter::with('dokter')
            ->where('aktif', true)
            ->orderBy('hari')
            ->get();

        return view('booking.reschedule', [
            'pendaftaran' => $pendaftaran,
            'jadwal' => $jadwal,
            'selectedJadwal' => (int) ($pendaftaran->antrean?->jadwal_dokter_id ?? 0),
        ]);
    }

    public function reschedule(Request $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        $validated = $request->validate([
            'jadwal_dokter_id' => 'required|exists:jadwal_dokter,id',
            'tanggal_periksa' => 'required|date|after_or_equal:today',
        ]);

        $user = $request->user();
        $pasienId = $user?->pasien?->id;

        if (! $pasienId || $pendaftaran->pasien_id !== $pasienId) {
            abort(403);
        }

        $pendaftaran->load('antrean.jadwalDokter');

        if ($pendaftaran->status_kunjungan === 'dibatalkan' || $pendaftaran->antrean?->status === 'batal') {
            return back()->with('error', 'Booking ini sudah dibatalkan sehingga tidak bisa dijadwal ulang.');
        }

        $tanggalPeriksa = $pendaftaran->antrean?->tanggal_periksa;
        $jamMulai = $pendaftaran->antrean?->jadwalDokter?->jam_mulai;
        if ($tanggalPeriksa && $jamMulai) {
            $batas = Carbon::parse($tanggalPeriksa.' '.$jamMulai)->subHours(2);
            if (now()->greaterThanOrEqualTo($batas)) {
                return back()->with('error', 'Reschedule ditutup karena sudah melewati batas waktu (maksimal 2 jam sebelum jadwal mulai).');
            }
        }

        $newAntrean = DB::transaction(function () use ($validated, $pendaftaran, $request) {
            $jadwal = JadwalDokter::whereKey($validated['jadwal_dokter_id'])
                ->lockForUpdate()
                ->first();

            if (! $jadwal || ! $jadwal->aktif) {
                throw ValidationException::withMessages([
                    'jadwal_dokter_id' => 'Jadwal dokter tidak tersedia.',
                ]);
            }

            $alreadyBooked = Pendaftaran::where('pasien_id', $pendaftaran->pasien_id)
                ->where('id', '!=', $pendaftaran->id)
                ->where('status_kunjungan', '!=', 'dibatalkan')
                ->whereHas('antrean', function ($query) use ($validated) {
                    $query->where('jadwal_dokter_id', $validated['jadwal_dokter_id'])
                        ->whereDate('tanggal_periksa', $validated['tanggal_periksa'])
                        ->where('status', '!=', 'batal');
                })
                ->exists();

            if ($alreadyBooked) {
                throw ValidationException::withMessages([
                    'tanggal_periksa' => 'Anda sudah memiliki booking lain pada jadwal dan tanggal tersebut.',
                ]);
            }

            $totalBooked = Antrean::where('jadwal_dokter_id', $validated['jadwal_dokter_id'])
                ->whereDate('tanggal_periksa', $validated['tanggal_periksa'])
                ->where('status', '!=', 'batal')
                ->count();

            if ($totalBooked >= $jadwal->kuota) {
                throw ValidationException::withMessages([
                    'jadwal_dokter_id' => 'Kuota antrean pada jadwal dan tanggal yang dipilih sudah penuh.',
                ]);
            }

            $lastNumber = Antrean::where('jadwal_dokter_id', $validated['jadwal_dokter_id'])
                ->whereDate('tanggal_periksa', $validated['tanggal_periksa'])
                ->lockForUpdate()
                ->max('nomor_antrean');

            $oldAntrean = $pendaftaran->antrean()->lockForUpdate()->first();
            if ($oldAntrean) {
                $oldAntrean->update([
                    'status' => 'batal',
                    'dibatalkan_pada' => now(),
                    'updated_by_user_id' => $request->user()?->id,
                ]);
                Audit::log($request, 'booking.rescheduled_from', $oldAntrean, null, $oldAntrean->only(['status', 'dibatalkan_pada', 'updated_by_user_id']));
            }

            $antrean = Antrean::create([
                'jadwal_dokter_id' => $validated['jadwal_dokter_id'],
                'tanggal_periksa' => $validated['tanggal_periksa'],
                'nomor_antrean' => ($lastNumber ?? 0) + 1,
                'status' => 'menunggu',
                'updated_by_user_id' => $request->user()?->id,
            ]);

            $pendaftaran->update([
                'antrean_id' => $antrean->id,
                'status_kunjungan' => 'terdaftar',
            ]);

            Audit::log($request, 'booking.rescheduled_to', $antrean, null, $antrean->toArray());

            return $antrean;
        });

        $newAntrean->load('jadwalDokter.dokter');
        $user?->notify(new BookingCreatedNotification($newAntrean));

        return redirect()->route('booking.index')->with('success', 'Reschedule berhasil. Nomor antrean baru: #'.$newAntrean->nomor_antrean);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'jadwal_dokter_id' => 'required|exists:jadwal_dokter,id',
            'tanggal_periksa' => 'required|date|after_or_equal:today',
            'keluhan' => 'nullable|string|max:1000',
        ]);

        $user = $request->user();
        $pasien = Pasien::firstOrCreate(
            ['user_id' => $user->id],
            [
                'nik' => 'AUTO'.str_pad((string) $user->id, 12, '0', STR_PAD_LEFT),
                'tanggal_lahir' => '2000-01-01',
                'jenis_kelamin' => 'L',
                'telepon' => '080000000000',
                'alamat' => 'Alamat belum diisi',
            ]
        );

        $antrean = DB::transaction(function () use ($validated, $pasien, $request) {
            $jadwal = JadwalDokter::whereKey($validated['jadwal_dokter_id'])
                ->lockForUpdate()
                ->first();

            if (! $jadwal || ! $jadwal->aktif) {
                throw ValidationException::withMessages([
                    'jadwal_dokter_id' => 'Jadwal dokter tidak tersedia.',
                ]);
            }

            $alreadyBooked = Pendaftaran::where('pasien_id', $pasien->id)
                ->where('status_kunjungan', '!=', 'dibatalkan')
                ->whereHas('antrean', function ($query) use ($validated) {
                    $query->where('jadwal_dokter_id', $validated['jadwal_dokter_id'])
                        ->whereDate('tanggal_periksa', $validated['tanggal_periksa'])
                        ->where('status', '!=', 'batal');
                })
                ->exists();

            if ($alreadyBooked) {
                throw ValidationException::withMessages([
                    'tanggal_periksa' => 'Anda sudah melakukan booking pada jadwal dan tanggal tersebut.',
                ]);
            }

            $totalBooked = Antrean::where('jadwal_dokter_id', $validated['jadwal_dokter_id'])
                ->whereDate('tanggal_periksa', $validated['tanggal_periksa'])
                ->where('status', '!=', 'batal')
                ->count();

            if ($totalBooked >= $jadwal->kuota) {
                throw ValidationException::withMessages([
                    'jadwal_dokter_id' => 'Kuota antrean pada jadwal dan tanggal yang dipilih sudah penuh.',
                ]);
            }

            $lastNumber = Antrean::where('jadwal_dokter_id', $validated['jadwal_dokter_id'])
                ->whereDate('tanggal_periksa', $validated['tanggal_periksa'])
                ->lockForUpdate()
                ->max('nomor_antrean');

            $antrean = Antrean::create([
                'jadwal_dokter_id' => $validated['jadwal_dokter_id'],
                'tanggal_periksa' => $validated['tanggal_periksa'],
                'nomor_antrean' => ($lastNumber ?? 0) + 1,
                'status' => 'menunggu',
                'updated_by_user_id' => $request->user()?->id,
            ]);

            Pendaftaran::create([
                'pasien_id' => $pasien->id,
                'antrean_id' => $antrean->id,
                'keluhan' => $validated['keluhan'] ?? null,
                'status_kunjungan' => 'terdaftar',
            ]);

            Audit::log($request, 'booking.created', $antrean, null, $antrean->toArray());

            return $antrean;
        });

        $antrean->load('jadwalDokter.dokter');
        $user->notify(new BookingCreatedNotification($antrean));

        return redirect()->route('dashboard')->with('success', "Booking berhasil. Nomor antrean Anda: {$antrean->nomor_antrean}");
    }

    public function cancel(Request $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        $user = $request->user();
        $pasienId = $user?->pasien?->id;

        if (! $pasienId || $pendaftaran->pasien_id !== $pasienId) {
            abort(403);
        }

        $pendaftaran->load('antrean.jadwalDokter');

        if ($pendaftaran->status_kunjungan === 'dibatalkan' || $pendaftaran->antrean?->status === 'batal') {
            return back()->with('error', 'Booking ini sudah dibatalkan sebelumnya.');
        }

        $tanggalPeriksa = $pendaftaran->antrean?->tanggal_periksa;
        $jamMulai = $pendaftaran->antrean?->jadwalDokter?->jam_mulai;

        if (! $tanggalPeriksa || ! $jamMulai) {
            return back()->with('error', 'Data jadwal tidak lengkap, booking tidak bisa dibatalkan.');
        }

        $batasPembatalan = Carbon::parse($tanggalPeriksa.' '.$jamMulai)->subHours(2);
        if (now()->greaterThanOrEqualTo($batasPembatalan)) {
            return back()->with('error', 'Pembatalan ditutup karena sudah melewati batas waktu (maksimal 2 jam sebelum jadwal mulai).');
        }

        DB::transaction(function () use ($pendaftaran) {
            $pendaftaran->update([
                'status_kunjungan' => 'dibatalkan',
            ]);

            $pendaftaran->antrean?->update([
                'status' => 'batal',
                'dibatalkan_pada' => now(),
            ]);
        });

        $antrean = $pendaftaran->antrean;
        if ($antrean) {
            $antrean->load('jadwalDokter.dokter');
            Audit::log($request, 'booking.cancelled', $antrean, null, $antrean->only(['status', 'dibatalkan_pada']));
            $user?->notify(new BookingCancelledNotification($antrean));
        }

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }
}
