<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Klinik Sehat - Daftar Akun Baru</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            margin: auto;
            overflow: auto;
            background: linear-gradient(315deg, rgba(6,182,212,1) 3%, rgba(16,185,129,1) 38%, rgba(59,130,246,1) 68%, rgba(13,148,136,1) 98%);
            animation: gradient 15s ease infinite;
            background-size: 400% 400%;
            background-attachment: fixed;
        }
        @keyframes gradient {
            0% { background-position: 0% 0%; }
            50% { background-position: 100% 100%; }
            100% { background-position: 0% 0%; }
        }
        .wave {
            background: rgb(255 255 255 / 15%);
            border-radius: 1000% 1000% 0 0;
            position: fixed;
            width: 200%;
            height: 12em;
            animation: wave 10s -3s linear infinite;
            transform: translate3d(0, 0, 0);
            opacity: 0.8;
            bottom: 0;
            left: 0;
            z-index: 0;
        }
        .wave:nth-of-type(2) {
            bottom: -1.25em;
            animation: wave 18s linear reverse infinite;
            opacity: 0.8;
        }
        .wave:nth-of-type(3) {
            bottom: -2.5em;
            animation: wave 20s -1s reverse infinite;
            opacity: 0.9;
        }
        @keyframes wave {
            2% { transform: translateX(1px); }
            25% { transform: translateX(-25%); }
            50% { transform: translateX(-50%); }
            75% { transform: translateX(-25%); }
            100% { transform: translateX(1px); }
        }
    </style>
</head>
<body class="relative min-h-screen flex items-center justify-center p-6 overflow-x-hidden overflow-y-auto">
    
    <!-- Animated Waves Background -->
    <div>
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
    </div>

    <!-- Floating Card -->
    <div class="relative z-10 w-full max-w-2xl bg-white/80 backdrop-blur-xl rounded-[2.5rem] p-8 md:p-12 shadow-[0_8px_40px_rgb(0,0,0,0.08)] border border-white/50 my-10">
        
        <!-- Logo -->
        <div class="flex justify-center mb-8">
            <a href="{{ route('landing') }}" class="flex items-center gap-3 group">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-cyan-500 to-emerald-500 flex items-center justify-center text-white shadow-lg group-hover:shadow-cyan-500/30 transition duration-300">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/></svg>
                </div>
            </a>
        </div>

        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-slate-800 mb-2">Buat Akun Baru</h1>
            <p class="text-slate-500">Lengkapi form pendaftaran di bawah ini</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <!-- Top Grid (Name & Email) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}"
                        class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm transition-all shadow-inner"
                        placeholder="Nama Lengkap" required autofocus>
                    @error('name') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                        class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm transition-all shadow-inner"
                        placeholder="nama@email.com" required>
                    @error('email') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Middle Grid (HP, NIK, Tanggal Lahir, Jenis Kelamin) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">No. HP</label>
                    <input id="telepon" type="text" name="telepon" value="{{ old('telepon') }}"
                        class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm transition-all shadow-inner"
                        placeholder="08xxxxxxxxxx" required>
                    @error('telepon') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">NIK</label>
                    <input id="nik" type="text" name="nik" value="{{ old('nik') }}"
                        class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm transition-all shadow-inner"
                        placeholder="16 digit NIK" required>
                    @error('nik') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Lahir</label>
                    <input id="tanggal_lahir" type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                        class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm transition-all shadow-inner text-slate-600" required>
                    @error('tanggal_lahir') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm transition-all shadow-inner text-slate-600" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L" @selected(old('jenis_kelamin') === 'L')>Laki-laki</option>
                        <option value="P" @selected(old('jenis_kelamin') === 'P')>Perempuan</option>
                    </select>
                    @error('jenis_kelamin') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Alamat -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Alamat</label>
                <textarea name="alamat" rows="2"
                    class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm transition-all shadow-inner resize-none"
                    placeholder="Jl. Contoh No. 10..." required>{{ old('alamat') }}</textarea>
                @error('alamat') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <!-- Password Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                    <input id="password" type="password" name="password"
                        class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm transition-all shadow-inner"
                        placeholder="Buat password" required>
                    @error('password') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                        class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm transition-all shadow-inner"
                        placeholder="Ulangi password" required>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-4 bg-gradient-to-r from-cyan-500 to-emerald-500 hover:from-cyan-600 hover:to-emerald-600 text-white font-bold rounded-2xl transition duration-300 shadow-lg shadow-emerald-500/30 transform hover:-translate-y-0.5">
                    Daftar Sekarang
                </button>
            </div>
        </form>

        <div class="mt-8 text-center">
            <p class="text-slate-500 text-sm">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-bold text-cyan-600 hover:text-cyan-700 transition">Masuk di sini</a>
            </p>
        </div>
    </div>

</body>
</html>
