<?php

namespace App\Http\Controllers;

use App\Models\Antrean;
use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\Pendaftaran;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $role = $user?->role?->name;

        if ($role === 'super_admin') {
            $period = collect(range(6, 0))->map(fn ($i) => Carbon::now()->subDays($i)->format('Y-m-d'));
            $bookingByDay = $period->map(fn ($day) => Pendaftaran::whereDate('created_at', $day)->count());

            return view('dashboard.super-admin', [
                'userCount' => User::count(),
                'roleCount' => Role::count(),
                'bookingCount' => Pendaftaran::count(),
                'todayQueue' => Antrean::whereDate('tanggal_periksa', now()->toDateString())->count(),
                'chartLabels' => $period->map(fn ($d) => Carbon::parse($d)->format('d M'))->values(),
                'bookingByDay' => $bookingByDay->values(),
            ]);
        }

        if ($role === 'admin') {
            $queueStatus = [
                'menunggu' => Antrean::where('status', 'menunggu')->count(),
                'dipanggil' => Antrean::where('status', 'dipanggil')->count(),
                'selesai' => Antrean::where('status', 'selesai')->count(),
                'batal' => Antrean::where('status', 'batal')->count(),
            ];

            return view('dashboard.admin', [
                'dokterCount' => Dokter::count(),
                'jadwalCount' => JadwalDokter::count(),
                'todayQueue' => Antrean::whereDate('tanggal_periksa', now()->toDateString())->count(),
                'pendingQueue' => Antrean::where('status', 'menunggu')->count(),
                'queueStatus' => $queueStatus,
            ]);
        }

        $pendaftaran = Pendaftaran::with('antrean.jadwalDokter.dokter')
            ->where('pasien_id', $user?->pasien?->id)
            ->latest()
            ->paginate(5);

        return view('dashboard.user', [
            'pendaftaran' => $pendaftaran,
            'activeQueue' => Antrean::where('status', 'menunggu')
                ->whereHas('pendaftaran', fn ($q) => $q->where('pasien_id', $user?->pasien?->id))
                ->count(),
        ]);
    }

    public function adminQueue(Request $request): View
    {
        $validated = $request->validate([
            'tanggal' => 'nullable|date',
            'dokter_id' => 'nullable|exists:dokter,id',
            'status' => 'nullable|in:menunggu,dipanggil,selesai,batal',
        ]);

        $tanggal = $validated['tanggal'] ?? now()->toDateString();
        $dokterId = $validated['dokter_id'] ?? null;
        $status = $validated['status'] ?? null;

        $baseQuery = Antrean::query()
            ->whereDate('tanggal_periksa', $tanggal)
            ->when($dokterId, function ($q) use ($dokterId) {
                $q->whereHas('jadwalDokter', fn ($jadwal) => $jadwal->where('dokter_id', $dokterId));
            })
            ->orderBy('nomor_antrean');

        $summaryQuery = (clone $baseQuery);
        $summary = [
            'total' => (clone $summaryQuery)->count(),
            'menunggu' => (clone $summaryQuery)->where('status', 'menunggu')->count(),
            'dipanggil' => (clone $summaryQuery)->where('status', 'dipanggil')->count(),
            'selesai' => (clone $summaryQuery)->where('status', 'selesai')->count(),
            'batal' => (clone $summaryQuery)->where('status', 'batal')->count(),
        ];

        $query = (clone $baseQuery)
            ->with(['jadwalDokter.dokter', 'pendaftaran.pasien.user'])
            ->when($status, fn ($q) => $q->where('status', $status));

        return view('dashboard.admin-queue', [
            'antrianHarian' => $query->paginate(15)->withQueryString(),
            'dokterList' => Dokter::orderBy('nama')->get(),
            'summary' => $summary,
            'filters' => [
                'tanggal' => $tanggal,
                'dokter_id' => $dokterId,
                'status' => $status,
            ],
        ]);
    }
}
