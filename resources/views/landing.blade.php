<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik Sehat - Sistem Antrean & Penjadwalan Dokter Online</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-white text-slate-800">

    <!-- Navbar -->
    <header class="fixed top-0 z-50 w-full bg-white border-b border-slate-100 shadow-sm transition-all duration-300">
        <nav class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <!-- Logo (Left) -->
            <a href="#" class="flex items-center gap-2 group">
                <div class="w-10 h-10 rounded-xl bg-cyan-500 flex items-center justify-center text-white shadow-md transition">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/></svg>
                </div>
                <span class="text-xl font-extrabold text-slate-800">Klinik Sehat</span>
            </a>
            
            <!-- Navigation Links (Middle) -->
            <div class="hidden md:flex items-center justify-center flex-1 mx-8 gap-8 text-sm font-semibold text-slate-600">
                <a href="#" class="text-cyan-600 hover:text-cyan-700 transition relative after:absolute after:-bottom-1 after:left-0 after:w-full after:h-0.5 after:bg-cyan-500 after:rounded-full">Beranda</a>
                <a href="#fitur" class="hover:text-cyan-600 transition relative after:absolute after:-bottom-1 after:left-0 after:w-0 after:h-0.5 after:bg-cyan-500 after:transition-all after:duration-300 hover:after:w-full after:rounded-full">Fitur</a>
                <a href="#testimoni" class="hover:text-cyan-600 transition relative after:absolute after:-bottom-1 after:left-0 after:w-0 after:h-0.5 after:bg-cyan-500 after:transition-all after:duration-300 hover:after:w-full after:rounded-full">Testimoni</a>
                <a href="#kontak" class="hover:text-cyan-600 transition relative after:absolute after:-bottom-1 after:left-0 after:w-0 after:h-0.5 after:bg-cyan-500 after:transition-all after:duration-300 hover:after:w-full after:rounded-full">Kontak</a>
            </div>
            
            <!-- CTA Buttons (Right) -->
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="hidden sm:inline-flex px-5 py-2.5 rounded-xl text-slate-600 font-semibold text-sm hover:bg-slate-100 transition">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="px-6 py-2.5 rounded-xl bg-emerald-500 text-white font-semibold text-sm hover:bg-emerald-600 transition shadow-md hover:shadow-emerald-500/30 transform hover:-translate-y-0.5">
                    Booking
                </a>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-b from-cyan-50/50 to-white min-h-screen flex items-center justify-center pt-32 pb-24 md:pt-40 md:pb-32">
        <div class="max-w-4xl mx-auto px-6 text-center flex flex-col items-center z-10">
            <div data-aos="fade-up" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-slate-200 shadow-sm mb-8 text-sm font-medium text-cyan-700">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Sistem Layanan Digital 24/7
            </div>
            
            <h1 data-aos="fade-up" data-aos-delay="100" class="text-5xl md:text-7xl font-extrabold text-slate-800 leading-tight mb-6">
                Klinik<span class="text-emerald-500">Sehat</span>
            </h1>
            
            <p data-aos="fade-up" data-aos-delay="200" class="text-slate-600 text-lg md:text-xl mb-10 leading-relaxed max-w-2xl">
                Booking lebih mudah, antrean lebih teratur, pelayanan lebih cepat dan nyaman. Nikmati kemudahan akses kesehatan di ujung jari Anda.
            </p>
            
            <div data-aos="fade-up" data-aos-delay="300" class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                <a href="{{ route('register') }}" class="px-8 py-4 rounded-xl bg-emerald-500 text-white font-semibold text-lg hover:bg-emerald-600 transition shadow-lg hover:shadow-emerald-500/30 transform hover:-translate-y-1">
                    Daftar Sekarang
                </a>
                <a href="{{ route('jadwal.index') }}" class="px-8 py-4 rounded-xl bg-white text-cyan-600 font-semibold text-lg hover:bg-cyan-50 transition shadow-md border border-cyan-100 hover:border-cyan-200 transform hover:-translate-y-1">
                    Lihat Jadwal
                </a>
            </div>

            <!-- Optional: small features below buttons -->
            <div data-aos="fade-up" data-aos-delay="400" class="mt-12 flex flex-wrap items-center justify-center gap-6 text-sm text-slate-500 font-medium">
                <div class="flex items-center gap-2 bg-white/50 px-4 py-2 rounded-lg backdrop-blur-sm border border-white/40">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Gratis Registrasi
                </div>
                <div class="flex items-center gap-2 bg-white/50 px-4 py-2 rounded-lg backdrop-blur-sm border border-white/40">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Akses Cepat
                </div>
                <div class="flex items-center gap-2 bg-white/50 px-4 py-2 rounded-lg backdrop-blur-sm border border-white/40">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Data Aman
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="bg-white py-24 md:py-32">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Stat Card 1 -->
                <div class="bg-white rounded-2xl border border-slate-100 p-8 text-center shadow-sm hover:shadow-md transition duration-300 relative overflow-hidden group" data-aos="fade-up">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-cyan-400 to-cyan-500 transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
                    <p class="text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-cyan-600 to-cyan-400 mb-2">{{ $dokterCount }}</p>
                    <p class="text-slate-500 font-medium">Dokter Aktif</p>
                </div>
                
                <!-- Stat Card 2 -->
                <div class="bg-white rounded-2xl border border-slate-100 p-8 text-center shadow-sm hover:shadow-md transition duration-300 relative overflow-hidden group" data-aos="fade-up" data-aos-delay="100">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 to-emerald-500 transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
                    <p class="text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-emerald-400 mb-2">{{ $jadwalCount }}</p>
                    <p class="text-slate-500 font-medium">Jadwal Tersedia</p>
                </div>
                
                <!-- Stat Card 3 -->
                <div class="bg-white rounded-2xl border border-slate-100 p-8 text-center shadow-sm hover:shadow-md transition duration-300 relative overflow-hidden group" data-aos="fade-up" data-aos-delay="200">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 to-blue-500 transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
                    <p class="text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-blue-400 mb-2">100%</p>
                    <p class="text-slate-500 font-medium">Layanan Digital</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Fitur Unggulan -->
    <section class="bg-slate-50 py-24 md:py-32" id="fitur">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 mb-4">Fitur Unggulan Kami</h2>
                <p class="text-slate-500 text-lg max-w-2xl mx-auto">Kami menghadirkan berbagai kemudahan digital untuk memberikan pengalaman layanan kesehatan terbaik bagi Anda dan keluarga.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 group" data-aos="fade-up">
                    <div class="w-16 h-16 rounded-2xl bg-cyan-50 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-cyan-100 transition-all duration-300">
                        <svg class="w-8 h-8 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3 group-hover:text-cyan-600 transition-colors">Pendaftaran Online</h3>
                    <p class="text-slate-500 leading-relaxed">Pilih jadwal dokter dan booking nomor antrean langsung dari rumah tanpa perlu datang ke klinik lebih awal.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 rounded-2xl bg-emerald-50 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-emerald-100 transition-all duration-300">
                        <svg class="w-8 h-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3 group-hover:text-emerald-600 transition-colors">Pantau Antrean Real-Time</h3>
                    <p class="text-slate-500 leading-relaxed">Cek status panggilan secara langsung (live) dari HP Anda. Datang tepat waktu saat giliran Anda sudah dekat.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 group" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-blue-100 transition-all duration-300">
                        <svg class="w-8 h-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3 group-hover:text-blue-600 transition-colors">Rekam Medis Digital</h3>
                    <p class="text-slate-500 leading-relaxed">Riwayat kunjungan, diagnosa, dan obat tersimpan dengan aman dan rapi. Mudah diakses kapanpun Anda butuhkan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimoni -->
    <section class="bg-white py-24 md:py-32" id="testimoni">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">Apa Kata Pasien Kami</h2>
                <p class="text-slate-500 max-w-2xl mx-auto text-lg">Pengalaman nyata dari para pasien yang telah menggunakan layanan booking online Klinik Sehat.</p>
            </div>
            
            <div class="columns-1 md:columns-2 lg:columns-3 gap-6 space-y-6">
                <!-- Card 1 (Short) -->
                <div class="break-inside-avoid bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition duration-300" data-aos="fade-up">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-cyan-100 flex items-center justify-center text-cyan-600 font-bold text-lg">
                            A
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800">Andi Saputra</h4>
                            <div class="flex text-yellow-400 text-sm">
                                ★★★★★
                            </div>
                        </div>
                    </div>
                    <p class="text-slate-600 leading-relaxed">
                        "Sangat praktis! Saya bisa booking dokter dari rumah dan datang pas dekat waktu giliran. Hemat waktu banget."
                    </p>
                </div>

                <!-- Card 2 (Long) -->
                <div class="break-inside-avoid bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition duration-300" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold text-lg">
                            S
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800">Siti Aminah</h4>
                            <div class="flex text-yellow-400 text-sm">
                                ★★★★★
                            </div>
                        </div>
                    </div>
                    <p class="text-slate-600 leading-relaxed">
                        "Awalnya ragu menggunakan sistem online karena gaptek, tapi ternyata UI-nya sangat ramah pengguna. Notifikasi antreannya juga sangat membantu saya untuk tidak terlalu lama menunggu di klinik yang kadang ramai. Perawat dan dokternya pun ramah."
                    </p>
                </div>

                <!-- Card 3 (Medium) -->
                <div class="break-inside-avoid bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition duration-300" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-lg">
                            B
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800">Budi Santoso</h4>
                            <div class="flex text-yellow-400 text-sm">
                                ★★★★☆
                            </div>
                        </div>
                    </div>
                    <p class="text-slate-600 leading-relaxed">
                        "Fitur lihat jadwal dokter secara real-time sangat mempermudah saya mengatur waktu cuti kerja untuk kontrol kesehatan. Mantap!"
                    </p>
                </div>

                <!-- Card 4 (Medium) -->
                <div class="break-inside-avoid bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition duration-300" data-aos="fade-up">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold text-lg">
                            D
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800">Dewi Lestari</h4>
                            <div class="flex text-yellow-400 text-sm">
                                ★★★★★
                            </div>
                        </div>
                    </div>
                    <p class="text-slate-600 leading-relaxed">
                        "Klinik Sehat memberikan layanan digital terbaik di kota ini. Saya sangat mengapresiasi inovasi antrean onlinenya."
                    </p>
                </div>

                <!-- Card 5 (Long) -->
                <div class="break-inside-avoid bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition duration-300" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-rose-100 flex items-center justify-center text-rose-600 font-bold text-lg">
                            R
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800">Rina Kartika</h4>
                            <div class="flex text-yellow-400 text-sm">
                                ★★★★★
                            </div>
                        </div>
                    </div>
                    <p class="text-slate-600 leading-relaxed">
                        "Saya sering membawa anak balita saya periksa, dan menunggu lama di ruang tunggu sangat melelahkan. Dengan sistem antrean Klinik Sehat, saya tinggal pantau nomor dari HP dan baru jalan ke klinik saat giliran sudah dekat. Terima kasih banyak!"
                    </p>
                </div>

                <!-- Card 6 (Short) -->
                <div class="break-inside-avoid bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition duration-300" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 font-bold text-lg">
                            F
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800">Faisal Rahman</h4>
                            <div class="flex text-yellow-400 text-sm">
                                ★★★★☆
                            </div>
                        </div>
                    </div>
                    <p class="text-slate-600 leading-relaxed">
                        "Layanan sangat profesional. Website berjalan lancar tanpa error saat saya mendaftar di jam sibuk."
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Artikel & Edukasi Kesehatan -->
    <section class="bg-slate-50 py-24 md:py-32" id="blog">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12" data-aos="fade-up">
                <div>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 mb-4">Artikel & Edukasi Kesehatan</h2>
                    <p class="text-slate-500 text-lg max-w-2xl">Baca informasi dan tips kesehatan terkini langsung dari para ahli medis kami.</p>
                </div>
                <a href="#" class="hidden md:inline-flex items-center gap-2 text-emerald-600 font-semibold hover:text-emerald-700 transition">
                    Lihat Semua Artikel
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Featured Post (Large) -->
                <a href="#" class="group lg:w-2/3 bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl transition duration-300 overflow-hidden flex flex-col relative" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-full h-72 md:h-96 relative overflow-hidden">
                        <img src="{{ asset('img/featured_blog.png') }}" alt="Featured Article" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-emerald-600 uppercase tracking-wider shadow-sm">
                            Fasilitas
                        </div>
                    </div>
                    <div class="p-8 md:p-10 flex flex-col flex-1">
                        <div class="flex items-center gap-3 text-sm text-slate-400 mb-4">
                            <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> 12 Mei 2026</span>
                            <span>•</span>
                            <span>5 min read</span>
                        </div>
                        <h3 class="text-2xl md:text-3xl font-bold text-slate-800 mb-4 group-hover:text-emerald-600 transition">Mengenal Fasilitas Baru Klinik Sehat: Kenyamanan Ekstra Saat Menunggu</h3>
                        <p class="text-slate-500 text-lg leading-relaxed mb-6 flex-1">Kami terus berinovasi untuk memberikan pelayanan terbaik bagi pasien. Mengintip pembaruan fasilitas ruang tunggu dan poli yang dirancang khusus untuk mempercepat pemulihan dan kenyamanan Anda.</p>
                        <div class="flex items-center gap-3 mt-auto">
                            <div class="w-10 h-10 rounded-full bg-cyan-100 flex items-center justify-center text-cyan-600 font-bold text-sm">KS</div>
                            <span class="font-medium text-slate-700">Tim Redaksi Klinik Sehat</span>
                        </div>
                    </div>
                </a>

                <!-- Side Smaller Posts Grid -->
                <div class="lg:w-1/3 flex flex-col gap-8">
                    <!-- Small Post 1 -->
                    <a href="#" class="group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-lg transition duration-300 overflow-hidden flex flex-col h-full" data-aos="fade-up" data-aos-delay="200">
                        <div class="w-full h-48 relative overflow-hidden">
                            <img src="{{ asset('img/small_blog_1.png') }}" alt="Article 1" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-cyan-600 uppercase tracking-wider shadow-sm">
                                Konsultasi
                            </div>
                        </div>
                        <div class="p-6 flex flex-col flex-1">
                            <div class="text-xs text-slate-400 mb-2">10 Mei 2026</div>
                            <h3 class="text-xl font-bold text-slate-800 mb-2 group-hover:text-cyan-600 transition leading-snug">Pentingnya Memilih Dokter yang Tepat Untuk Keluarga</h3>
                            <p class="text-slate-500 text-sm line-clamp-2 mt-auto">Tips dan trik menemukan tenaga kesehatan yang cocok untuk jangka panjang.</p>
                        </div>
                    </a>

                    <!-- Small Post 2 -->
                    <a href="#" class="group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-lg transition duration-300 overflow-hidden flex flex-col h-full" data-aos="fade-up" data-aos-delay="300">
                        <div class="w-full h-48 relative overflow-hidden">
                            <img src="{{ asset('img/small_blog_2.png') }}" alt="Article 2" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-blue-600 uppercase tracking-wider shadow-sm">
                                Gaya Hidup
                            </div>
                        </div>
                        <div class="p-6 flex flex-col flex-1">
                            <div class="text-xs text-slate-400 mb-2">8 Mei 2026</div>
                            <h3 class="text-xl font-bold text-slate-800 mb-2 group-hover:text-blue-600 transition leading-snug">5 Kebiasaan Sederhana Untuk Menjaga Jantung Sehat</h3>
                            <p class="text-slate-500 text-sm line-clamp-2 mt-auto">Terapkan langkah-langkah mudah ini di rumah untuk meningkatkan kualitas hidup.</p>
                        </div>
                    </a>
                </div>
            </div>
            
            <div class="mt-8 text-center md:hidden" data-aos="fade-up">
                <a href="#" class="inline-flex items-center gap-2 text-emerald-600 font-semibold hover:text-emerald-700 transition">
                    Lihat Semua Artikel
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </section>

    <!-- FAQ Chat Style -->
    <section class="bg-white py-24 md:py-32" id="faq">
        <div class="max-w-3xl mx-auto px-6">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 mb-4">Pertanyaan Seputar Layanan</h2>
                <p class="text-slate-500 text-lg">Punya pertanyaan? Mungkin jawabannya sudah ada di bawah ini.</p>
            </div>

            <!-- Chat Window -->
            <div class="bg-slate-50 rounded-3xl p-6 md:p-8 border border-slate-100 shadow-inner flex flex-col gap-6 relative" data-aos="fade-up" data-aos-delay="100">
                
                <!-- Chat 1: Q -->
                <div class="flex items-end justify-end gap-3 w-full" data-aos="fade-up">
                    <div class="max-w-[80%] md:max-w-[70%]">
                        <div class="bg-cyan-500 text-white p-4 rounded-2xl rounded-br-sm shadow-md">
                            <p class="text-sm md:text-base leading-relaxed">Halo, apakah saya harus bayar untuk pakai aplikasi booking online ini?</p>
                        </div>
                        <span class="text-xs text-slate-400 mt-1 block text-right font-medium">Calon Pasien</span>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-slate-200 flex-shrink-0 border-2 border-white shadow-sm overflow-hidden flex items-center justify-center">
                        <svg class="w-6 h-6 text-slate-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    </div>
                </div>

                <!-- Chat 1: A -->
                <div class="flex items-end justify-start gap-3 w-full" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-10 h-10 rounded-full bg-emerald-500 flex-shrink-0 border-2 border-white shadow-sm flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/></svg>
                    </div>
                    <div class="max-w-[80%] md:max-w-[70%]">
                        <div class="bg-white border border-slate-100 text-slate-700 p-4 rounded-2xl rounded-bl-sm shadow-sm">
                            <p class="text-sm md:text-base leading-relaxed">Halo! Tidak, pendaftaran dan penggunaan sistem booking ini 100% gratis. Anda hanya membayar biaya pengobatan di klinik nanti.</p>
                        </div>
                        <span class="text-xs text-slate-400 mt-1 block font-medium">Admin Klinik Sehat</span>
                    </div>
                </div>

                <!-- Chat 2: Q -->
                <div class="flex items-end justify-end gap-3 w-full" data-aos="fade-up" data-aos-delay="200">
                    <div class="max-w-[80%] md:max-w-[70%]">
                        <div class="bg-cyan-500 text-white p-4 rounded-2xl rounded-br-sm shadow-md">
                            <p class="text-sm md:text-base leading-relaxed">Gimana kalau saya telat datang dari jadwal yang sudah diperkirakan?</p>
                        </div>
                        <span class="text-xs text-slate-400 mt-1 block text-right font-medium">Calon Pasien</span>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-slate-200 flex-shrink-0 border-2 border-white shadow-sm overflow-hidden flex items-center justify-center">
                        <svg class="w-6 h-6 text-slate-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    </div>
                </div>

                <!-- Chat 2: A -->
                <div class="flex items-end justify-start gap-3 w-full" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-10 h-10 rounded-full bg-emerald-500 flex-shrink-0 border-2 border-white shadow-sm flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/></svg>
                    </div>
                    <div class="max-w-[80%] md:max-w-[70%]">
                        <div class="bg-white border border-slate-100 text-slate-700 p-4 rounded-2xl rounded-bl-sm shadow-sm">
                            <p class="text-sm md:text-base leading-relaxed">Sistem kami akan memberi toleransi keterlambatan hingga 3 nomor antrean. Jika lewat dari itu, nomor Anda akan dipanggil ulang di sesi akhir.</p>
                        </div>
                        <span class="text-xs text-slate-400 mt-1 block font-medium">Admin Klinik Sehat</span>
                    </div>
                </div>

                <!-- Chat 3: Q -->
                <div class="flex items-end justify-end gap-3 w-full" data-aos="fade-up" data-aos-delay="400">
                    <div class="max-w-[80%] md:max-w-[70%]">
                        <div class="bg-cyan-500 text-white p-4 rounded-2xl rounded-br-sm shadow-md">
                            <p class="text-sm md:text-base leading-relaxed">Apakah saya bisa mendaftarkan anggota keluarga menggunakan 1 akun?</p>
                        </div>
                        <span class="text-xs text-slate-400 mt-1 block text-right font-medium">Calon Pasien</span>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-slate-200 flex-shrink-0 border-2 border-white shadow-sm overflow-hidden flex items-center justify-center">
                        <svg class="w-6 h-6 text-slate-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    </div>
                </div>

                <!-- Chat 3: A -->
                <div class="flex items-end justify-start gap-3 w-full" data-aos="fade-up" data-aos-delay="500">
                    <div class="w-10 h-10 rounded-full bg-emerald-500 flex-shrink-0 border-2 border-white shadow-sm flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/></svg>
                    </div>
                    <div class="max-w-[80%] md:max-w-[70%]">
                        <div class="bg-white border border-slate-100 text-slate-700 p-4 rounded-2xl rounded-bl-sm shadow-sm">
                            <p class="text-sm md:text-base leading-relaxed">Tentu saja bisa! Anda dapat menambahkan profil keluarga di dashboard akun Anda dan memilih profil tersebut saat booking jadwal.</p>
                        </div>
                        <span class="text-xs text-slate-400 mt-1 block font-medium">Admin Klinik Sehat</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Hubungi Kami -->
    <section class="bg-slate-50 py-24 md:py-32" id="bantuan">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 mb-4">Butuh Bantuan?</h2>
                <p class="text-slate-500 text-lg max-w-2xl mx-auto">Tim kami siap membantu Anda kapan saja. Pilih saluran bantuan yang paling nyaman untuk Anda.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Live Chat -->
                <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm hover:shadow-xl transition duration-300 flex flex-col group" data-aos="fade-up">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-emerald-100 transition-all">
                        <svg class="w-7 h-7 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Live Chat</h3>
                    <p class="text-slate-500 text-sm leading-relaxed flex-1 mb-6">Ngobrol langsung dengan tim support kami untuk respon tercepat.</p>
                    <a href="#" class="inline-flex items-center gap-2 text-emerald-600 font-semibold hover:text-emerald-700 transition mt-auto group-hover:translate-x-2 duration-300">
                        Mulai Chat
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>

                <!-- Email -->
                <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm hover:shadow-xl transition duration-300 flex flex-col group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-14 h-14 rounded-2xl bg-cyan-50 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-cyan-100 transition-all">
                        <svg class="w-7 h-7 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Email</h3>
                    <p class="text-slate-500 text-sm leading-relaxed flex-1 mb-6">Kirim pesan detail untuk kendala yang memerlukan penanganan khusus.</p>
                    <a href="mailto:support@kliniksehat.id" class="inline-flex items-center gap-2 text-cyan-600 font-semibold hover:text-cyan-700 transition mt-auto group-hover:translate-x-2 duration-300">
                        Kirim Email
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>

                <!-- Docs -->
                <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm hover:shadow-xl transition duration-300 flex flex-col group" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-blue-100 transition-all">
                        <svg class="w-7 h-7 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Dokumentasi</h3>
                    <p class="text-slate-500 text-sm leading-relaxed flex-1 mb-6">Cari panduan langkah-demi-langkah penggunaan seluruh fitur kami.</p>
                    <a href="#" class="inline-flex items-center gap-2 text-blue-600 font-semibold hover:text-blue-700 transition mt-auto group-hover:translate-x-2 duration-300">
                        Baca Panduan
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>

                <!-- Community -->
                <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm hover:shadow-xl transition duration-300 flex flex-col group" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-14 h-14 rounded-2xl bg-purple-50 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-purple-100 transition-all">
                        <svg class="w-7 h-7 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Komunitas</h3>
                    <p class="text-slate-500 text-sm leading-relaxed flex-1 mb-6">Bergabung dengan forum sesama pengguna untuk berbagi tips sehat.</p>
                    <a href="#" class="inline-flex items-center gap-2 text-purple-600 font-semibold hover:text-purple-700 transition mt-auto group-hover:translate-x-2 duration-300">
                        Join Forum
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <section class="bg-slate-900 text-slate-400 py-20 md:py-24 border-t border-slate-800" id="kontak">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-12 mb-16">
                
                <!-- Logo & Tagline (Far Left, takes 2 columns on lg) -->
                <div class="lg:col-span-2">
                    <a href="#" class="flex items-center gap-3 mb-6 group">
                        <div class="w-10 h-10 rounded-xl bg-cyan-500 flex items-center justify-center text-white shadow-md group-hover:shadow-lg transition">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/></svg>
                        </div>
                        <span class="text-2xl font-extrabold text-white tracking-tight">Klinik Sehat</span>
                    </a>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-sm mb-8">
                        Sistem Antrean & Penjadwalan Dokter Online terpercaya. Kami mendigitalisasi layanan kesehatan untuk pengalaman pasien yang lebih cepat, mudah, dan nyaman.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-emerald-500 hover:text-white transition duration-300">
                            <!-- Twitter/X Icon -->
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-emerald-500 hover:text-white transition duration-300">
                            <!-- Facebook Icon -->
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-emerald-500 hover:text-white transition duration-300">
                            <!-- Instagram Icon -->
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Product Links -->
                <div>
                    <h4 class="text-white font-bold mb-6">Produk</h4>
                    <ul class="space-y-4 text-sm">
                        <li><a href="#" class="hover:text-cyan-400 transition">Booking Antrean</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition">Jadwal Dokter</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition">Rekam Medis</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition">Konsultasi Online</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition">Apotek Digital</a></li>
                    </ul>
                </div>

                <!-- Company Links -->
                <div>
                    <h4 class="text-white font-bold mb-6">Perusahaan</h4>
                    <ul class="space-y-4 text-sm">
                        <li><a href="#" class="hover:text-cyan-400 transition">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition">Karir</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition">Blog & Edukasi</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition">Mitra Klinik</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition">Kontak</a></li>
                    </ul>
                </div>

                <!-- Resources Links -->
                <div>
                    <h4 class="text-white font-bold mb-6">Sumber Daya</h4>
                    <ul class="space-y-4 text-sm">
                        <li><a href="#" class="hover:text-cyan-400 transition">Pusat Bantuan</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition">Panduan Pasien</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition">FAQ</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition">Komunitas</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition">Status Sistem</a></li>
                    </ul>
                </div>

                <!-- Legal Links -->
                <div>
                    <h4 class="text-white font-bold mb-6">Legal</h4>
                    <ul class="space-y-4 text-sm">
                        <li><a href="#" class="hover:text-cyan-400 transition">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition">Kebijakan Cookie</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition">Lisensi</a></li>
                    </ul>
                </div>
            </div>

            <!-- Copyright and Bottom -->
            <div class="pt-8 border-t border-slate-800 flex flex-col md:flex-row justify-between items-center gap-4 text-sm">
                <p>&copy; {{ date('Y') }} Klinik Sehat. All rights reserved.</p>
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span>(021) 123-4567</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>support@kliniksehat.id</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>AOS.init({ duration: 700, once: true });</script>
</body>
</html>
