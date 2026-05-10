<?php

namespace Database\Seeders;

use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\Pasien;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();

        User::updateOrCreate(
            ['email' => 'superadmin@hospital.test'],
            ['name' => 'Super Admin', 'password' => Hash::make('password'), 'role_id' => $superAdminRole?->id]
        );

        User::updateOrCreate(
            ['email' => 'admin@hospital.test'],
            ['name' => 'Admin Klinik', 'password' => Hash::make('password'), 'role_id' => $adminRole?->id]
        );

        $pasienUser = User::updateOrCreate(
            ['email' => 'pasien@hospital.test'],
            ['name' => 'Pasien Demo', 'password' => Hash::make('password'), 'role_id' => $userRole?->id]
        );

        Pasien::updateOrCreate(
            ['user_id' => $pasienUser->id],
            ['nik' => '3200000000000001', 'tanggal_lahir' => '1998-04-20', 'jenis_kelamin' => 'L', 'telepon' => '081234567890', 'alamat' => 'Jakarta']
        );

        $dokter1 = Dokter::updateOrCreate(['no_str' => 'STR-001'], ['nama' => 'dr. Maya Putri', 'spesialisasi' => 'Penyakit Dalam', 'telepon' => '081200001', 'alamat' => 'Jakarta']);
        $dokter2 = Dokter::updateOrCreate(['no_str' => 'STR-002'], ['nama' => 'dr. Reza Akbar', 'spesialisasi' => 'Anak', 'telepon' => '081200002', 'alamat' => 'Bandung']);

        JadwalDokter::updateOrCreate(['dokter_id' => $dokter1->id, 'hari' => 'Senin'], ['jam_mulai' => '09:00', 'jam_selesai' => '12:00', 'kuota' => 25, 'aktif' => true]);
        JadwalDokter::updateOrCreate(['dokter_id' => $dokter2->id, 'hari' => 'Selasa'], ['jam_mulai' => '10:00', 'jam_selesai' => '13:00', 'kuota' => 20, 'aktif' => true]);
    }
}
