<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PasienProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $pasien = Pasien::firstOrCreate(
            ['user_id' => $request->user()->id],
            [
                'nik' => 'AUTO'.str_pad((string) $request->user()->id, 12, '0', STR_PAD_LEFT),
                'tanggal_lahir' => '2000-01-01',
                'jenis_kelamin' => 'L',
                'telepon' => '080000000000',
                'alamat' => 'Alamat belum diisi',
            ]
        );

        return view('pasien.profile', [
            'pasien' => $pasien,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nik' => 'required|string|min:12|max:20',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
            'telepon' => 'required|string|min:10|max:20',
            'alamat' => 'required|string|max:1000',
        ]);

        $pasien = Pasien::firstOrCreate(['user_id' => $request->user()->id]);
        $pasien->update($validated);

        return back()->with('success', 'Profil pasien berhasil diperbarui.');
    }
}
