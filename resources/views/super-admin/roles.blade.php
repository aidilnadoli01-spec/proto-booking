<x-app-layout>
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 mb-1">Kelola Role</h1>
            <p class="text-slate-400 text-sm">Manajemen hak akses pengguna dalam sistem.</p>
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-6">
        <!-- Add Role Form -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h2 class="font-semibold text-slate-800 mb-4">Tambah Role</h2>
                <form method="POST" action="{{ route('super.roles.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Role</label>
                        <input name="name" placeholder="contoh: admin" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-400 text-sm" required>
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Label</label>
                        <input name="label" placeholder="contoh: Administrator" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-400 text-sm" required>
                        @error('label') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Deskripsi</label>
                        <input name="description" placeholder="Deskripsi singkat..." class="w-full px-3 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-400 text-sm">
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-cyan-500 hover:bg-cyan-600 text-white font-semibold rounded-xl transition text-sm">
                        + Tambah Role
                    </button>
                </form>
            </div>
        </div>

        <!-- Roles Table -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-5 border-b border-slate-100">
                    <h2 class="font-semibold text-slate-800">Daftar Role</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                            <tr>
                                <th class="px-5 py-3.5 text-left font-medium">Role</th>
                                <th class="px-5 py-3.5 text-left font-medium">Deskripsi</th>
                                <th class="px-5 py-3.5 text-left font-medium">Izin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($roles as $role)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-slate-800">{{ $role->label }}</p>
                                        <p class="text-xs text-slate-400 font-mono">{{ $role->name }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-slate-500 text-xs">
                                        @if($role->name === 'user')
                                            Dapat booking, lihat jadwal, kelola profil
                                        @elseif($role->name === 'admin')
                                            Mengelola antrean, dokter, laporan
                                        @elseif($role->name === 'super_admin')
                                            Akses penuh ke semua fitur sistem
                                        @else
                                            {{ $role->description ?? '-' }}
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex flex-wrap gap-1.5">
                                            @if($role->name === 'user')
                                                <span class="px-2 py-0.5 bg-cyan-100 text-cyan-700 rounded text-xs">Jadwal</span>
                                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded text-xs">Booking</span>
                                                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-xs">Profil</span>
                                            @elseif($role->name === 'admin')
                                                <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded text-xs">Antrean</span>
                                                <span class="px-2 py-0.5 bg-orange-100 text-orange-700 rounded text-xs">Dokter</span>
                                                <span class="px-2 py-0.5 bg-purple-100 text-purple-700 rounded text-xs">Laporan</span>
                                            @else
                                                <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded text-xs">Semua Akses</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-slate-100">{{ $roles->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
