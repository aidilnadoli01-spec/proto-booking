<x-app-layout>
    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 mb-1">Manajemen Dokter & Jadwal</h1>
            <p class="text-slate-500 text-sm">Kelola data dokter, jadwal praktik, dan kuota pasien</p>
        </div>
        <button onclick="document.getElementById('modal-tambah-dokter').classList.remove('hidden')"
            class="flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition shadow-sm">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Dokter Baru
        </button>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-700 px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif

    <!-- Search & Filter -->
    <div class="mb-6 bg-white rounded-xl shadow-sm border border-slate-100 p-5">
        <div class="flex flex-col gap-4">
            <!-- Search Bar -->
            <div class="relative">
                <form method="GET" action="{{ route('dokter.index') }}" class="flex gap-2">
                    <div class="flex-1 relative">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari berdasarkan nama dokter..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </form>
            </div>

            <!-- Filter Buttons -->
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('dokter.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ !$spesialis ? 'bg-blue-100 text-blue-700 border border-blue-300' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200' }}">
                    Semua Spesialis
                </a>
                @foreach($spesialisList as $spec)
                    <a href="{{ route('dokter.index', ['spesialis' => $spec]) }}" 
                        class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $spesialis === $spec ? 'bg-blue-100 text-blue-700 border border-blue-300' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200' }}">
                        {{ $spec }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Doctors List -->
    <div class="space-y-4">
        @forelse($dokter as $d)
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition">
                <!-- Main Content -->
                <div class="p-5">
                    <div class="flex items-start justify-between mb-4">
                        <!-- Left: Dokter Info -->
                        <div class="flex gap-4 flex-1">
                            <!-- Avatar -->
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($d->nama) }}&background=0ea5e9&color=ffffff&bold=true"
                                class="w-16 h-16 rounded-lg flex-shrink-0" alt="{{ $d->nama }}">
                            
                            <!-- Info -->
                            <div class="flex-1">
                                <h3 class="font-semibold text-slate-900 text-lg">{{ $d->nama }}</h3>
                                <p class="text-sm text-slate-600">{{ $d->spesialisasi }}</p>
                                <p class="text-xs text-slate-500 mt-1">STR: {{ $d->no_str }}</p>
                                @if($d->telepon)
                                    <p class="text-xs text-slate-500">{{ $d->telepon }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Right: Status & Actions -->
                        <div class="flex flex-col items-end gap-2">
                            @if($d->jadwalDokter->count() > 0)
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">✓ Aktif</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">Tidak Aktif</span>
                            @endif
                            
                            <!-- Dropdown Actions -->
                            <div class="relative group">
                                <button class="p-2 hover:bg-slate-100 rounded-lg transition">
                                    <svg class="w-5 h-5 text-slate-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/></svg>
                                </button>
                                <div class="absolute right-0 mt-1 w-32 bg-white rounded-lg shadow-lg border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition z-10">
                                    <button data-dokter="{{ json_encode($d) }}" onclick="openEditDokter(JSON.parse(this.dataset.dokter))"
                                        class="w-full text-left px-4 py-2 text-sm text-blue-600 hover:bg-blue-50">Edit Dokter</button>
                                    <form method="POST" action="{{ route('dokter.destroy', $d) }}" onsubmit="return confirm('Hapus dokter ini?')" class="inline-block w-full">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Jadwal Section -->
                    @if($d->jadwalDokter->count() > 0)
                        <div class="border-t border-slate-100 pt-4">
                            <h4 class="text-sm font-semibold text-slate-700 mb-3">Jadwal Praktik (Ringkasan)</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($d->jadwalDokter->sortBy('hari') as $j)
                                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg border border-slate-100">
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-slate-900">{{ ucfirst($j->hari) }}</p>
                                            <p class="text-xs text-slate-600 mt-0.5">{{ $j->jam_mulai }} - {{ $j->jam_selesai }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs text-slate-500">Kuota:</p>
                                            <p class="text-sm font-semibold text-slate-900">{{ $j->kuota }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="flex gap-2 mt-4">
                                <button onclick="document.getElementById('modal-jadwal-{{ $d->id }}').classList.remove('hidden')"
                                    class="flex-1 px-3 py-2 text-sm font-medium text-blue-600 border border-blue-200 rounded-lg hover:bg-blue-50 transition">
                                    Edit Jadwal
                                </button>
                                <button onclick="document.getElementById('modal-add-jadwal-{{ $d->id }}').classList.remove('hidden')"
                                    class="flex-1 px-3 py-2 text-sm font-medium text-emerald-600 border border-emerald-200 rounded-lg hover:bg-emerald-50 transition">
                                    + Tambah Sesi
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="border-t border-slate-100 pt-4 text-center">
                            <p class="text-sm text-slate-500 mb-3">Belum ada jadwal</p>
                            <button onclick="document.getElementById('modal-add-jadwal-{{ $d->id }}').classList.remove('hidden')"
                                class="px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                                Atur Jadwal
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Modal Add Jadwal -->
            <div id="modal-add-jadwal-{{ $d->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <h2 class="font-semibold text-slate-800">Tambah Jadwal - {{ $d->nama }}</h2>
                        <button onclick="document.getElementById('modal-add-jadwal-{{ $d->id }}').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('jadwal.store') }}" class="p-6 space-y-4">
                        @csrf
                        <input type="hidden" name="dokter_id" value="{{ $d->id }}">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Hari</label>
                            <select name="hari" class="w-full px-3 py-2.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                                <option value="">Pilih Hari</option>
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                                <option value="Sabtu">Sabtu</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Jam Mulai</label>
                                <input type="time" name="jam_mulai" class="w-full px-3 py-2.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Jam Selesai</label>
                                <input type="time" name="jam_selesai" class="w-full px-3 py-2.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Kuota Pasien</label>
                            <input type="number" name="kuota" min="1" max="100" value="20" class="w-full px-3 py-2.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                        </div>
                        <div class="flex gap-3">
                            <button type="button" onclick="document.getElementById('modal-add-jadwal-{{ $d->id }}').classList.add('hidden')"
                                class="flex-1 py-2.5 border border-slate-200 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50 transition">Batal</button>
                            <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-16 bg-white rounded-xl border border-dashed border-slate-200">
                <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                <p class="text-slate-500 text-lg mb-2">Belum ada data dokter</p>
                <p class="text-slate-400 text-sm mb-4">Mulai dengan menambah dokter baru</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($dokter->hasPages())
        <div class="mt-8">
            {{ $dokter->links() }}
        </div>
    @endif

    <!-- Modal Tambah Dokter -->
    <div id="modal-tambah-dokter" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h2 class="font-semibold text-slate-800 text-lg">Tambah Dokter Baru</h2>
                <button onclick="document.getElementById('modal-tambah-dokter').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('dokter.store') }}" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Dokter</label>
                    <input name="nama" placeholder="dr. Nama Lengkap" class="w-full px-3 py-2.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Spesialisasi</label>
                    <input name="spesialisasi" placeholder="Contoh: Dokter Umum, Dokter Gigi" class="w-full px-3 py-2.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">No STR</label>
                    <input name="no_str" placeholder="No STR Dokter" class="w-full px-3 py-2.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Telepon</label>
                    <input name="telepon" placeholder="08xxxxxxxxxx" class="w-full px-3 py-2.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Alamat</label>
                    <textarea name="alamat" rows="2" placeholder="Alamat lengkap" class="w-full px-3 py-2.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none"></textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('modal-tambah-dokter').classList.add('hidden')"
                        class="flex-1 py-2.5 border border-slate-200 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50 transition">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Dokter -->
    <div id="modal-edit-dokter" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h2 class="font-semibold text-slate-800 text-lg">Edit Dokter</h2>
                <button onclick="document.getElementById('modal-edit-dokter').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="form-edit-dokter" method="POST" class="p-6 space-y-4">
                @csrf @method('PATCH')
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Dokter</label>
                    <input id="edit-nama" name="nama" class="w-full px-3 py-2.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Spesialisasi</label>
                    <input id="edit-spesialisasi" name="spesialisasi" class="w-full px-3 py-2.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">No STR</label>
                    <input id="edit-no_str" name="no_str" class="w-full px-3 py-2.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Telepon</label>
                    <input id="edit-telepon" name="telepon" class="w-full px-3 py-2.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Alamat</label>
                    <textarea id="edit-alamat" name="alamat" rows="2" class="w-full px-3 py-2.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none"></textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('modal-edit-dokter').classList.add('hidden')"
                        class="flex-1 py-2.5 border border-slate-200 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50 transition">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-sm font-semibold transition">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditDokter(dokter) {
            document.getElementById('edit-nama').value = dokter.nama;
            document.getElementById('edit-spesialisasi').value = dokter.spesialisasi;
            document.getElementById('edit-no_str').value = dokter.no_str;
            document.getElementById('edit-telepon').value = dokter.telepon || '';
            document.getElementById('edit-alamat').value = dokter.alamat || '';
            document.getElementById('form-edit-dokter').action = '/admin/dokter/' + dokter.id;
            document.getElementById('modal-edit-dokter').classList.remove('hidden');
        }
    </script>
</x-app-layout>
