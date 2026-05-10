<x-app-layout>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800 mb-1">Booking Saya</h1>
        <p class="text-slate-400 text-sm">Riwayat booking dan status antrean Anda.</p>
    </div>

    <!-- Tabs -->
    <div class="flex gap-1 bg-slate-100 rounded-xl p-1 w-fit mb-6">
        <button onclick="showTab('aktif')" id="tab-aktif" class="px-5 py-2 rounded-lg text-sm font-medium transition bg-white text-slate-800 shadow-sm">Aktif</button>
        <button onclick="showTab('riwayat')" id="tab-riwayat" class="px-5 py-2 rounded-lg text-sm font-medium transition text-slate-500 hover:text-slate-700">Riwayat</button>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 px-4 py-3 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 text-rose-700 px-4 py-3 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Booking List -->
    <div class="space-y-4">
        @forelse ($bookings as $item)
            @php
                $status = $item->antrean->status ?? 'batal';
                $isAktif = in_array($status, ['menunggu', 'dipanggil']);
            @endphp
            <div class="booking-item {{ $isAktif ? 'aktif' : 'riwayat' }} bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex flex-col md:flex-row gap-4 items-start md:items-center">
                <!-- Nomor Antrean -->
                <div class="shrink-0 w-20 h-20 rounded-2xl bg-cyan-50 border-2 border-cyan-100 flex flex-col items-center justify-center">
                    <span class="text-xs text-cyan-500 font-medium">Antrean</span>
                    <span class="text-2xl font-extrabold text-cyan-700">A-{{ str_pad($item->antrean->nomor_antrean ?? 0, 3, '0', STR_PAD_LEFT) }}</span>
                </div>

                <!-- Info -->
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($item->antrean->jadwalDokter->dokter->nama ?? 'Dokter') }}&background=e0f2fe&color=0369a1"
                            class="w-8 h-8 rounded-full" alt="Dokter">
                        <p class="font-semibold text-slate-800 text-sm">{{ $item->antrean->jadwalDokter->dokter->nama ?? '-' }}</p>
                    </div>
                    <p class="text-xs text-slate-500">{{ $item->antrean->jadwalDokter->dokter->spesialisasi ?? '-' }}</p>
                    <p class="text-xs text-slate-400 mt-1">{{ $item->antrean->tanggal_periksa ?? '-' }} · {{ $item->antrean->jadwalDokter->jam_mulai ?? '' }} - {{ $item->antrean->jadwalDokter->jam_selesai ?? '' }}</p>
                </div>

                <!-- Status & Actions -->
                <div class="flex items-center gap-3 shrink-0">
                    @php $s = $item->antrean->status ?? 'batal'; @endphp
                    @if($s === 'menunggu')
                        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">Menunggu</span>
                    @elseif($s === 'dipanggil')
                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">Dipanggil</span>
                    @elseif($s === 'selesai')
                        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">Selesai</span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">Dibatalkan</span>
                    @endif

                    @if ($isAktif)
                        <a href="{{ route('booking.reschedule.form', $item) }}"
                            class="px-3 py-1 rounded-lg border border-cyan-300 text-cyan-700 text-xs font-medium hover:bg-cyan-50 transition">
                            Reschedule
                        </a>
                        <button
                            x-data x-on:click.prevent="$dispatch('open-modal', 'cancel-booking-{{ $item->id }}')"
                            class="px-3 py-1 rounded-lg border border-red-200 text-red-600 text-xs font-medium hover:bg-red-50 transition">
                            Batalkan
                        </button>

                        <x-modal name="cancel-booking-{{ $item->id }}" maxWidth="md">
                            <div class="p-6">
                                <h2 class="text-lg font-semibold text-slate-800">Batalkan Booking?</h2>
                                <p class="mt-2 text-sm text-slate-600">
                                    Booking nomor <span class="font-semibold text-cyan-700">A-{{ str_pad($item->antrean->nomor_antrean ?? 0, 3, '0', STR_PAD_LEFT) }}</span> akan dibatalkan. Tindakan ini tidak bisa diurungkan.
                                </p>
                                <div class="mt-5 flex justify-end gap-3">
                                    <button type="button" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 text-sm hover:bg-slate-50"
                                        x-on:click="$dispatch('close-modal', 'cancel-booking-{{ $item->id }}')">
                                        Kembali
                                    </button>
                                    <form method="POST" action="{{ route('booking.cancel', $item) }}">
                                        @csrf @method('PATCH')
                                        <button class="px-4 py-2 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-medium">Ya, Batalkan</button>
                                    </form>
                                </div>
                            </div>
                        </x-modal>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-10 text-center">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                <p class="text-slate-400 font-medium">Belum ada booking.</p>
                <a href="{{ route('booking.create') }}" class="mt-3 inline-block px-5 py-2 bg-cyan-500 text-white rounded-lg text-sm font-medium hover:bg-cyan-600 transition">
                    Buat Booking Baru
                </a>
            </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $bookings->links() }}</div>

    <script>
        function showTab(tab) {
            document.querySelectorAll('.booking-item').forEach(el => {
                if (tab === 'aktif') el.classList.toggle('hidden', !el.classList.contains('aktif'));
                else el.classList.remove('hidden');
            });
            document.getElementById('tab-aktif').className = tab === 'aktif'
                ? 'px-5 py-2 rounded-lg text-sm font-medium transition bg-white text-slate-800 shadow-sm'
                : 'px-5 py-2 rounded-lg text-sm font-medium transition text-slate-500 hover:text-slate-700';
            document.getElementById('tab-riwayat').className = tab === 'riwayat'
                ? 'px-5 py-2 rounded-lg text-sm font-medium transition bg-white text-slate-800 shadow-sm'
                : 'px-5 py-2 rounded-lg text-sm font-medium transition text-slate-500 hover:text-slate-700';
        }
        showTab('aktif');
    </script>
</x-app-layout>
