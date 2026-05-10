<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">Reschedule Booking</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 space-y-4">
            <div class="bg-white shadow rounded-xl p-6">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="text-sm text-slate-500">Booking saat ini</p>
                        <p class="font-semibold text-slate-800">
                            {{ $pendaftaran->antrean->jadwalDokter->dokter->nama ?? '-' }}
                        </p>
                        <p class="text-sm text-slate-600">
                            Tanggal: {{ $pendaftaran->antrean->tanggal_periksa ?? '-' }},
                            Jam: {{ $pendaftaran->antrean->jadwalDokter->jam_mulai ?? '-' }} - {{ $pendaftaran->antrean->jadwalDokter->jam_selesai ?? '-' }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-slate-500">Nomor antrean</p>
                        <p class="text-2xl font-bold text-blue-700">#{{ $pendaftaran->antrean->nomor_antrean ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('booking.reschedule', $pendaftaran) }}" class="bg-white shadow rounded-xl p-6 space-y-4">
                @csrf
                @method('PATCH')

                @if ($errors->any())
                    <div class="rounded-lg border border-rose-200 bg-rose-50 text-rose-700 px-4 py-3 text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium">Pilih Jadwal Dokter Baru</label>
                    <select name="jadwal_dokter_id" class="w-full rounded-lg border-slate-300 mt-1" required>
                        <option value="">Pilih Jadwal</option>
                        @foreach ($jadwal as $item)
                            <option value="{{ $item->id }}" @selected(old('jadwal_dokter_id', $selectedJadwal) == $item->id)>
                                {{ $item->dokter->nama }} - {{ $item->hari }} ({{ $item->jam_mulai }}-{{ $item->jam_selesai }}) | Kuota: {{ $item->kuota }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium">Tanggal Periksa Baru</label>
                    <input
                        type="date"
                        name="tanggal_periksa"
                        value="{{ old('tanggal_periksa', $pendaftaran->antrean->tanggal_periksa ?? '') }}"
                        class="w-full rounded-lg border-slate-300 mt-1"
                        required
                    >
                    <p class="mt-2 text-xs text-slate-500">Catatan: reschedule mengikuti kebijakan maksimal 2 jam sebelum jadwal mulai.</p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg">
                        Simpan Reschedule
                    </button>
                    <a href="{{ route('booking.index') }}" class="w-full sm:w-auto text-center border border-slate-300 hover:bg-slate-50 py-2 px-4 rounded-lg text-slate-700">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

