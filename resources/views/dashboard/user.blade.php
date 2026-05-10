<x-app-layout>
    <!-- Welcome Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800 mb-1">Selamat Datang, {{ explode(' ', Auth::user()->name)[0] }}!</h1>
        <p class="text-slate-500">Selamat Datang di Klinik Sehat Utama!</p>
    </div>

    <!-- Main Active Queue Card -->
    <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 mb-6 flex flex-col md:flex-row justify-between items-start md:items-center">
        <div class="flex flex-col gap-4">
            <div class="flex items-center gap-3">
                <h2 class="text-lg font-semibold text-slate-800">Antrean Aktif Saat Ini</h2>
                @if($activeQueue > 0)
                    <span class="px-3 py-1 bg-emerald-500 text-white text-xs font-semibold rounded-full">Sedang Menunggu</span>
                @else
                    <span class="px-3 py-1 bg-slate-200 text-slate-600 text-xs font-semibold rounded-full">Tidak Ada</span>
                @endif
            </div>
            
            <div class="flex items-center gap-8">
                <div class="text-6xl font-bold text-[#23458c] tracking-tight">
                    {{ $activeQueue > 0 && isset($pendaftaran[0]) && $pendaftaran[0]->antrean->status == 'menunggu' ? 'B-'.$pendaftaran[0]->antrean->nomor_antrean : '-' }}
                </div>
                @if($activeQueue > 0 && isset($pendaftaran[0]) && $pendaftaran[0]->antrean->status == 'menunggu')
                <div class="border-l-2 border-slate-200 pl-8">
                    <h3 class="text-xl font-bold text-slate-800">Poliklinik Penyakit Dalam</h3>
                    <p class="text-slate-600 mb-2">{{ $pendaftaran[0]->antrean->jadwalDokter->dokter->nama ?? '-' }}</p>
                    <p class="text-sm text-slate-400">Antrean Aktif Saya</p>
                </div>
                @else
                <div class="border-l-2 border-slate-200 pl-8">
                    <h3 class="text-xl font-bold text-slate-800">Belum Ada Antrean</h3>
                    <p class="text-slate-600 mb-2">Silakan buat booking baru</p>
                </div>
                @endif
            </div>
        </div>

        @if($activeQueue > 0 && isset($pendaftaran[0]) && $pendaftaran[0]->antrean->status == 'menunggu')
        <div class="mt-6 md:mt-0 text-right">
            <p class="text-slate-600 mb-2">Estimasi Dipanggil: <span class="font-bold text-slate-800">Segera</span></p>
        </div>
        @endif
    </div>

    <!-- 4 Grid Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Jadwal Dokter -->
        <a href="{{ route('jadwal.index') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow group flex flex-col items-center text-center">
            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mb-4 group-hover:bg-blue-100 transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="font-bold text-slate-800 mb-4">Jadwal Dokter<br>Hari Ini</h3>
            <div class="flex flex-col gap-3 w-full">
                <div class="flex items-center gap-3 text-left">
                    <img src="https://ui-avatars.com/api/?name=Sarah+Wijaya&background=random" class="w-10 h-10 rounded-full" alt="Dokter">
                    <div>
                        <p class="text-sm font-bold text-slate-800">dr. Sarah Wijaya</p>
                        <p class="text-xs text-slate-500">Spesialis PD</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-left">
                    <img src="https://ui-avatars.com/api/?name=Ahmad+Fauzi&background=random" class="w-10 h-10 rounded-full" alt="Dokter">
                    <div>
                        <p class="text-sm font-bold text-slate-800">dr. Ahmad Fauzi</p>
                        <p class="text-xs text-slate-500">Spesialis Anak</p>
                    </div>
                </div>
            </div>
        </a>

        <!-- Buat Booking -->
        <a href="{{ route('booking.create') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow group flex flex-col items-center justify-center text-center min-h-[220px]">
            <div class="flex items-center gap-2 text-blue-600 mb-4">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <h3 class="font-bold">Buat Booking<br>Antrean Baru</h3>
            </div>
            <div class="mt-4 text-slate-300 group-hover:text-blue-500 transition-colors">
                <svg class="w-16 h-16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
            </div>
        </a>

        <!-- Riwayat Booking -->
        <a href="{{ route('booking.index') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow group flex flex-col items-center justify-center text-center min-h-[220px]">
            <div class="flex items-center gap-2 text-[#23458c] mb-6">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                <h3 class="font-bold">Semua Riwayat<br>Booking</h3>
            </div>
            <div class="text-slate-400 group-hover:text-[#23458c] transition-colors">
                <svg class="w-20 h-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11l-4 4-2-2"/></svg>
            </div>
        </a>

        <!-- Ganti Profil -->
        <a href="{{ route('pasien.profile.edit') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow group flex flex-col items-center justify-center text-center min-h-[220px]">
            <div class="flex items-center gap-2 text-[#23458c] mb-6">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                <h3 class="font-bold">Ganti Profil</h3>
            </div>
            <div class="text-slate-400 group-hover:text-[#23458c] transition-colors">
                <svg class="w-20 h-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            </div>
        </a>
    </div>

    <!-- Riwayat Singkat -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100">
        <div class="p-5 border-b"><h3 class="font-semibold text-slate-800">Booking Terbaru</h3></div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr><th class="p-4 text-left font-medium">Dokter</th><th class="p-4 text-left font-medium">Tanggal</th><th class="p-4 text-left font-medium">Nomor</th><th class="p-4 text-left font-medium">Status</th></tr>
                </thead>
                <tbody class="text-slate-700">
                    @forelse ($pendaftaran as $item)
                        <tr class="border-t border-slate-100 hover:bg-slate-50 transition-colors">
                            <td class="p-4">{{ $item->antrean->jadwalDokter->dokter->nama ?? '-' }}</td>
                            <td class="p-4">{{ $item->antrean->tanggal_periksa ?? '-' }}</td>
                            <td class="p-4 font-semibold text-[#23458c]">#{{ $item->antrean->nomor_antrean ?? '-' }}</td>
                            <td class="p-4">
                                @if($item->antrean->status == 'menunggu')
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-md text-xs">Menunggu</span>
                                @elseif($item->antrean->status == 'dipanggil')
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-md text-xs">Dipanggil</span>
                                @elseif($item->antrean->status == 'selesai')
                                    <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-md text-xs">Selesai</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded-md text-xs">Batal</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td class="p-4 text-center text-slate-500" colspan="4">Belum ada booking.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">{{ $pendaftaran->links() }}</div>
    </div>
</x-app-layout>
