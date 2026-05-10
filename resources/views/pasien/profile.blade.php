<x-app-layout>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800 mb-1">Profil Pasien</h1>
        <p class="text-slate-400 text-sm">Kelola data diri dan informasi akun Anda.</p>
    </div>

    <div class="max-w-3xl">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <!-- Profile Header -->
            <div class="bg-gradient-to-r from-cyan-500 to-blue-600 px-6 py-8 flex items-center gap-5">
                <div class="w-20 h-20 rounded-full bg-white/30 flex items-center justify-center overflow-hidden border-4 border-white/50">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=e0f2fe&color=0369a1&size=80"
                        class="w-full h-full object-cover" alt="Profile">
                </div>
                <div class="text-white">
                    <p class="text-xl font-bold">{{ Auth::user()->name }}</p>
                    <p class="text-cyan-100 text-sm">{{ Auth::user()->email }}</p>
                    <span class="inline-block mt-2 px-3 py-0.5 bg-white/20 rounded-full text-xs font-medium">Pasien</span>
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('pasien.profile.update') }}" class="p-6 space-y-4">
                @csrf
                @method('PATCH')

                @if (session('success'))
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 px-4 py-3 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Lengkap</label>
                        <input type="text" value="{{ Auth::user()->name }}" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-500 cursor-not-allowed" disabled>
                        <p class="text-xs text-slate-400 mt-1">Nama tidak dapat diubah.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                        <input type="text" value="{{ Auth::user()->email }}" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-500 cursor-not-allowed" disabled>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">No. HP</label>
                    <input type="text" name="telepon" value="{{ old('telepon', $pasien->telepon) }}"
                        class="w-full px-3 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-400 text-sm" required>
                    @error('telepon') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Alamat</label>
                    <textarea name="alamat" rows="2"
                        class="w-full px-3 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-400 text-sm resize-none" required>{{ old('alamat', $pasien->alamat) }}</textarea>
                    @error('alamat') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">NIK</label>
                        <input type="text" name="nik" value="{{ old('nik', $pasien->nik) }}"
                            class="w-full px-3 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-400 text-sm" required>
                        @error('nik') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $pasien->tanggal_lahir) }}"
                            class="w-full px-3 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-400 text-sm" required>
                        @error('tanggal_lahir') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-400 text-sm" required>
                        <option value="L" @selected(old('jenis_kelamin', $pasien->jenis_kelamin) === 'L')>Laki-laki</option>
                        <option value="P" @selected(old('jenis_kelamin', $pasien->jenis_kelamin) === 'P')>Perempuan</option>
                    </select>
                    @error('jenis_kelamin') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold rounded-xl transition text-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
