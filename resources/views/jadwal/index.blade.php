<x-app-layout>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800 mb-1">Jadwal Dokter</h1>
        <p class="text-slate-400 text-sm">Pilih jadwal dokter yang tersedia.</p>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-3 items-end">
            <div class="flex-1">
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Tanggal</label>
                <input type="date" name="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}"
                    class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
            </div>
            <div class="flex-1">
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Pilih Spesialis</label>
                <select name="spesialis" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    <option value="">Semua Spesialis</option>
                    @foreach ($spesialisList as $item)
                        <option value="{{ $item }}" @selected($spesialis == $item)>{{ $item }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Cari Dokter</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Nama dokter..."
                    class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
            </div>
            <button type="submit" class="px-5 py-2 bg-cyan-500 text-white rounded-lg text-sm font-medium hover:bg-cyan-600 transition">
                Cari
            </button>
        </form>
    </div>

    <!-- Doctors List -->
    <div class="space-y-4">
        @forelse ($jadwal->groupBy('dokter_id') as $dokterId => $jadwalPerDokter)
            @php $dokter = $jadwalPerDokter->first()->dokter; @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <div class="flex flex-col md:flex-row gap-4">
                    <!-- Dokter Info -->
                    <div class="flex items-center gap-4 md:w-60 shrink-0">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($dokter->nama) }}&background=e0f2fe&color=0369a1&size=80"
                            class="w-16 h-16 rounded-full object-cover border-2 border-cyan-100" alt="{{ $dokter->nama }}">
                        <div>
                            <p class="font-bold text-slate-800 text-sm">{{ $dokter->nama }}</p>
                            <p class="text-xs text-cyan-600">{{ $dokter->spesialisasi }}</p>
                        </div>
                    </div>

                    <!-- Jadwal Slots -->
                    <div class="flex-1">
                        @foreach ($jadwalPerDokter as $j)
                        <div class="flex flex-wrap items-center gap-2 mb-2 last:mb-0">
                            <span class="text-xs text-slate-500 w-20 shrink-0">{{ $j->jam_mulai }} - {{ $j->jam_selesai }}</span>
                            @php
                                $slots = ['08:00','09:00','10:00','11:00','13:00'];
                            @endphp
                            @foreach($slots as $slot)
                                <span class="px-3 py-1 rounded-full border border-cyan-200 text-xs text-cyan-700 bg-cyan-50 font-medium">{{ $slot }}</span>
                            @endforeach
                        </div>
                        @endforeach
                    </div>

                    <!-- Booking Button -->
                    @if(auth()->user()?->hasRole('user'))
                    <div class="flex items-center shrink-0">
                        <a href="{{ route('booking.create') }}" class="px-5 py-2 bg-cyan-500 text-white rounded-lg text-sm font-medium hover:bg-cyan-600 transition">
                            Booking
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-10 text-center">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p class="text-slate-400">Jadwal belum tersedia.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $jadwal->links() }}</div>
</x-app-layout>
