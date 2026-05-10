<x-app-layout>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800 mb-1">Booking Antrean</h1>
        <p class="text-slate-400 text-sm">Pilih dokter dan jadwal untuk mendapatkan nomor antrean.</p>
    </div>

    <!-- Step Indicator -->
    <div class="flex items-center justify-center mb-8">
        <div class="flex items-center gap-2">
            <div id="step-indicator-1" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-cyan-500 text-white flex items-center justify-center text-sm font-bold">1</div>
                <span class="text-sm font-medium text-cyan-600">Pilih Dokter</span>
            </div>
            <div class="w-16 h-0.5 bg-slate-200 mx-2"></div>
            <div id="step-indicator-2" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center text-sm font-bold">2</div>
                <span class="text-sm font-medium text-slate-400">Pilih Jadwal</span>
            </div>
            <div class="w-16 h-0.5 bg-slate-200 mx-2"></div>
            <div id="step-indicator-3" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center text-sm font-bold">3</div>
                <span class="text-sm font-medium text-slate-400">Konfirmasi</span>
            </div>
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-6">
        <!-- Left: Form -->
        <div class="md:col-span-2">
            <form method="POST" action="{{ route('booking.store') }}" id="booking-form">
                @csrf

                @if ($errors->any())
                    <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 text-rose-700 px-4 py-3 text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Step 1: Pilih Dokter -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-4">
                    <h3 class="font-semibold text-slate-800 mb-4">Pilih Dokter</h3>
                    <div class="space-y-3">
                        @foreach ($jadwal->groupBy('dokter_id') as $dokterId => $jadwalList)
                            @php $dokter = $jadwalList->first()->dokter; @endphp
                            <label class="flex items-center gap-4 p-4 rounded-xl border-2 border-slate-100 cursor-pointer hover:border-cyan-300 transition has-[:checked]:border-cyan-500 has-[:checked]:bg-cyan-50">
                                <input type="radio" name="dokter_id_temp" value="{{ $dokterId }}" class="sr-only" onchange="filterJadwal(this.value)">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($dokter->nama) }}&background=e0f2fe&color=0369a1"
                                    class="w-12 h-12 rounded-full" alt="{{ $dokter->nama }}">
                                <div>
                                    <p class="font-semibold text-slate-800 text-sm">{{ $dokter->nama }}</p>
                                    <p class="text-xs text-cyan-600">{{ $dokter->spesialisasi }}</p>
                                </div>
                                <div class="ml-auto w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 check-circle">
                                    <div class="w-3 h-3 rounded-full bg-cyan-500 hidden check-dot"></div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Step 2: Pilih Jadwal -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-4">
                    <h3 class="font-semibold text-slate-800 mb-4">Pilih Jadwal</h3>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-2">Jadwal Dokter</label>
                        <select name="jadwal_dokter_id" id="jadwal-select" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 mb-4" required>
                            <option value="">Pilih Jadwal</option>
                            @foreach ($jadwal as $item)
                                <option value="{{ $item->id }}" data-dokter="{{ $item->dokter_id }}" @selected(old('jadwal_dokter_id', $selectedJadwal) == $item->id)>
                                    {{ $item->dokter->nama }} - {{ $item->hari }} ({{ $item->jam_mulai }}-{{ $item->jam_selesai }}) | Kuota: {{ $item->kuota }}
                                </option>
                            @endforeach
                        </select>

                        <label class="block text-sm font-medium text-slate-600 mb-2">Tanggal Periksa</label>
                        <input type="date" name="tanggal_periksa" value="{{ old('tanggal_periksa') }}"
                            class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 mb-4" required>

                        <label class="block text-sm font-medium text-slate-600 mb-2">Keluhan (Opsional)</label>
                        <textarea name="keluhan" rows="3"
                            class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 resize-none">{{ old('keluhan') }}</textarea>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-cyan-500 hover:bg-cyan-600 text-white font-semibold rounded-xl transition">
                    Konfirmasi Booking
                </button>
            </form>
        </div>

        <!-- Right: Summary -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sticky top-24">
                <h3 class="font-semibold text-slate-800 mb-4">Ringkasan Booking</h3>
                <div class="flex flex-col items-center text-center py-6">
                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mb-3">
                        <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <p class="text-sm text-slate-400">Pilih dokter terlebih dahulu untuk melihat ringkasan booking Anda.</p>
                    <div class="mt-4 w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center">
                        <svg class="w-12 h-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
