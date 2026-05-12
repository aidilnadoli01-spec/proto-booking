<?php

namespace App\Http\Controllers;

use App\Models\Antrean;
use App\Models\JadwalDokter;
use Illuminate\Http\Request;

class TvDisplayController extends Controller
{
    /**
     * Tampilkan halaman TV display fullscreen
     */
    public function index()
    {
        return view('tv.display');
    }

    /**
     * Tampilkan admin panel untuk kontrol TV display
     */
    public function adminPanel()
    {
        return view('tv.admin-panel');
    }

    /**
     * API endpoint untuk fetch data antrean realtime
     * Dipanggil setiap 3 detik untuk update display
     */
    public function getQueueData()
    {
        try {
            // Ambil antrean yang sedang dipanggil
            $current = Antrean::where('status', 'dipanggil')
                ->orderBy('dipanggil_pada', 'desc')
                ->with(['jadwalDokter.dokter'])
                ->first();

            // Ambil 5 antrean berikutnya yang masih menunggu
            $nextQueue = Antrean::where('status', 'menunggu')
                ->orderBy('tanggal_periksa', 'asc')
                ->orderBy('nomor_antrean', 'asc')
                ->limit(5)
                ->get();

            // Hitung statistik
            $waitingCount = Antrean::where('status', 'menunggu')->count();
            $completedCount = Antrean::where('status', 'selesai')
                ->whereDate('created_at', today())
                ->count();

            // Get info dokter yang sedang praktik
            $activeDoctors = JadwalDokter::where('aktif', true)
                ->with('dokter')
                ->get();

            return response()->json([
                'success' => true,
                'current' => $current ? [
                    'id' => $current->id,
                    'nomor_antrean' => $current->nomor_antrean,
                    'dokter' => $current->jadwalDokter->dokter->nama ?? 'Umum',
                    'loket' => $current->jadwalDokter->id,
                    'status' => $current->status,
                    'dipanggil_pada' => $current->dipanggil_pada->format('H:i:s') ?? null,
                ] : null,
                'nextQueue' => $nextQueue->map(function($item) {
                    return [
                        'nomor_antrean' => $item->nomor_antrean,
                        'status' => $item->status,
                    ];
                })->values()->all(),
                'statistics' => [
                    'waiting' => $waitingCount,
                    'completed' => $completedCount,
                    'activeDoctors' => $activeDoctors->count(),
                ],
                'timestamp' => now()->format('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Panggil antrean berikutnya
     * Endpoint untuk admin panel
     */
    public function callNext(Request $request)
    {
        try {
            // Tandai current antrean sebagai selesai
            Antrean::where('status', 'dipanggil')
                ->update([
                    'status' => 'selesai',
                    'selesai_pada' => now(),
                ]);

            // Ambil antrean menunggu yang pertama
            $next = Antrean::where('status', 'menunggu')
                ->orderBy('tanggal_periksa', 'asc')
                ->orderBy('nomor_antrean', 'asc')
                ->first();

            if ($next) {
                $next->update([
                    'status' => 'dipanggil',
                    'dipanggil_pada' => now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Antrean berhasil dipanggil',
                    'queue' => [
                        'nomor_antrean' => $next->nomor_antrean,
                        'status' => $next->status,
                    ],
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Tidak ada antrean menunggu',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reset antrean untuk hari berikutnya
     */
    public function reset(Request $request)
    {
        try {
            Antrean::where('tanggal_periksa', today())
                ->whereIn('status', ['menunggu', 'dipanggil'])
                ->update([
                    'status' => 'batal',
                    'dibatalkan_pada' => now(),
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Antrean berhasil direset',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
