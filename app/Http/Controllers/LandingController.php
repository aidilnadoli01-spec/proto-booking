<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\JadwalDokter;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Schema;

class LandingController extends Controller
{
    public function index(): View
    {
        return view('landing', [
            'dokterCount' => Schema::hasTable('dokter') ? Dokter::count() : 0,
            'jadwalCount' => Schema::hasTable('jadwal_dokter') ? JadwalDokter::where('aktif', true)->count() : 0,
        ]);
    }
}
