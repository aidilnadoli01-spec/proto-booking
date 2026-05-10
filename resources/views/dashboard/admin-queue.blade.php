<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">Detail Antrean Harian</h2>
    </x-slot>

    <div class="py-8 space-y-6">
        <div class="max-w-7xl mx-auto px-4">
            <form method="GET" action="{{ route('admin.queue') }}" class="bg-white rounded-2xl shadow p-4 grid md:grid-cols-4 gap-3">
                <div>
                    <label class="block text-sm font-medium">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ $filters['tanggal'] }}" class="w-full mt-1 rounded-lg border-slate-300">
                </div>
                <div>
                    <label class="block text-sm font-medium">Dokter</label>
                    <select name="dokter_id" class="w-full mt-1 rounded-lg border-slate-300">
                        <option value="">Semua Dokter</option>
                        @foreach ($dokterList as $dokter)
                            <option value="{{ $dokter->id }}" @selected((string) $filters['dokter_id'] === (string) $dokter->id)>{{ $dokter->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium">Status</label>
                    <select name="status" class="w-full mt-1 rounded-lg border-slate-300">
                        <option value="">Semua Status</option>
                        @foreach (['menunggu', 'dipanggil', 'selesai', 'batal'] as $status)
                            <option value="{{ $status }}" @selected($filters['status'] === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg py-2">Terapkan Filter</button>
                </div>
            </form>

            <div class="mt-4 grid sm:grid-cols-2 lg:grid-cols-5 gap-3">
                <div class="bg-white rounded-2xl shadow p-4 border border-slate-100">
                    <p class="text-xs text-slate-500">Total</p>
                    <p class="mt-1 text-2xl font-bold text-slate-800">{{ $summary['total'] ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow p-4 border border-slate-100">
                    <p class="text-xs text-slate-500">Menunggu</p>
                    <p class="mt-1 text-2xl font-bold text-amber-700">{{ $summary['menunggu'] ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow p-4 border border-slate-100">
                    <p class="text-xs text-slate-500">Dipanggil</p>
                    <p class="mt-1 text-2xl font-bold text-sky-700">{{ $summary['dipanggil'] ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow p-4 border border-slate-100">
                    <p class="text-xs text-slate-500">Selesai</p>
                    <p class="mt-1 text-2xl font-bold text-emerald-700">{{ $summary['selesai'] ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow p-4 border border-slate-100">
                    <p class="text-xs text-slate-500">Batal</p>
                    <p class="mt-1 text-2xl font-bold text-rose-700">{{ $summary['batal'] ?? 0 }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.queue.call_next') }}" class="mt-3 flex flex-wrap gap-2 items-center">
                @csrf
                <input type="hidden" name="tanggal" value="{{ $filters['tanggal'] }}">
                <input type="hidden" name="dokter_id" value="{{ $filters['dokter_id'] }}">
                <button class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg">
                    Panggil Berikutnya
                </button>
                <span class="text-sm text-slate-500">
                    Memanggil antrean status <span class="font-semibold">menunggu</span> paling kecil untuk filter saat ini.
                </span>
            </form>
        </div>

        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-white rounded-2xl shadow overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="p-3 text-left">No Antrean</th>
                            <th class="p-3 text-left">Dokter</th>
                            <th class="p-3 text-left">Pasien</th>
                            <th class="p-3 text-left">Jadwal</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($antrianHarian as $antrean)
                            <tr class="border-t">
                                <td class="p-3 font-semibold text-blue-700">#{{ $antrean->nomor_antrean }}</td>
                                <td class="p-3">{{ $antrean->jadwalDokter->dokter->nama ?? '-' }}</td>
                                <td class="p-3">{{ $antrean->pendaftaran->first()?->pasien->user->name ?? '-' }}</td>
                                <td class="p-3">{{ $antrean->jadwalDokter->jam_mulai ?? '-' }} - {{ $antrean->jadwalDokter->jam_selesai ?? '-' }}</td>
                                <td class="p-3">
                                    <x-status-badge :status="$antrean->status" />
                                </td>
                                <td class="p-3">
                                    <div class="flex flex-wrap gap-2">
                                        <form method="POST" action="{{ route('admin.queue.update_status', $antrean) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="dipanggil">
                                            <button class="px-3 py-1.5 rounded-lg text-white bg-sky-600 hover:bg-sky-700 text-xs">
                                                Panggil
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.queue.update_status', $antrean) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="selesai">
                                            <button class="px-3 py-1.5 rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 text-xs">
                                                Selesai
                                            </button>
                                        </form>
                                        <button
                                            class="px-3 py-1.5 rounded-lg text-white bg-rose-600 hover:bg-rose-700 text-xs"
                                            x-data
                                            x-on:click.prevent="$dispatch('open-modal', 'cancel-antrean-{{ $antrean->id }}')"
                                        >
                                            Batal
                                        </button>

                                        <x-modal name="cancel-antrean-{{ $antrean->id }}" maxWidth="md">
                                            <div class="p-6">
                                                <h2 class="text-lg font-semibold text-slate-800">Batalkan antrean?</h2>
                                                <p class="mt-2 text-sm text-slate-600">
                                                    Antrean <span class="font-semibold">#{{ $antrean->nomor_antrean }}</span> akan dibatalkan.
                                                    Tindakan ini juga akan membatalkan status kunjungan pasien.
                                                </p>

                                                <div class="mt-5 flex justify-end gap-3">
                                                    <button
                                                        type="button"
                                                        class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50"
                                                        x-on:click="$dispatch('close-modal', 'cancel-antrean-{{ $antrean->id }}')"
                                                    >
                                                        Batal
                                                    </button>

                                                    <form method="POST" action="{{ route('admin.queue.update_status', $antrean) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="batal">
                                                        <button class="px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white">
                                                            Ya, Batalkan
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </x-modal>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="p-3" colspan="6">Tidak ada antrean untuk filter ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $antrianHarian->links() }}</div>
        </div>
    </div>
</x-app-layout>
