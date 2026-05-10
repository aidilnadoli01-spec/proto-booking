<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Display Antrean TV</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-900 text-white overflow-hidden" x-data="queueSystem()" x-init="init()">
    <div class="min-h-screen flex flex-col relative">
        
        <!-- Background Effects -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-1/4 -right-1/4 w-[1000px] h-[1000px] bg-blue-600/10 rounded-full blur-[120px]"></div>
            <div class="absolute -bottom-1/4 -left-1/4 w-[800px] h-[800px] bg-emerald-600/10 rounded-full blur-[100px]"></div>
        </div>

        <!-- Header -->
        <header class="p-8 flex justify-between items-center relative z-10">
            <div>
                <h1 class="text-4xl font-extrabold tracking-tight text-white mb-2">KLINIK SEHAT</h1>
                <p class="text-slate-400 text-lg">Sistem Antrean Real-Time</p>
            </div>
            <div class="flex items-center gap-4">
                <button @click="enableSound()" x-show="!soundEnabled" class="px-4 py-2 bg-red-500/20 text-red-400 border border-red-500/30 rounded-xl text-sm font-bold animate-pulse">
                    Aktifkan Suara
                </button>
                <div class="text-right">
                    <div class="text-2xl font-bold font-mono" x-text="currentTime"></div>
                    <div class="text-slate-400 text-sm" x-text="currentDate"></div>
                </div>
            </div>
        </header>

        <!-- Main Display -->
        <main class="flex-1 p-8 grid grid-cols-1 lg:grid-cols-12 gap-8 relative z-10">
            
            <!-- Saat Ini Dipanggil -->
            <div class="lg:col-span-8 flex flex-col justify-center">
                <div class="bg-gradient-to-br from-blue-900/50 to-slate-800/50 border border-blue-500/30 rounded-[3rem] p-16 text-center relative overflow-hidden shadow-2xl">
                    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-400 to-emerald-400"></div>
                    
                    <h2 class="text-3xl font-bold text-slate-300 mb-8 uppercase tracking-widest">Nomor Antrean</h2>
                    
                    <!-- Animasi Perubahan Angka -->
                    <div class="relative overflow-hidden mb-8 h-64 flex items-center justify-center">
                        <template x-if="currentQueue">
                            <div x-transition:enter="transition ease-out duration-500"
                                 x-transition:enter-start="opacity-0 translate-y-12 scale-90"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 class="text-[12rem] font-black text-white leading-none tracking-tighter drop-shadow-[0_0_30px_rgba(59,130,246,0.5)]" 
                                 x-text="currentQueue.nomor_antrian">
                            </div>
                        </template>
                        <template x-if="!currentQueue">
                            <div class="text-[8rem] font-black text-slate-700 leading-none">--</div>
                        </template>
                    </div>

                    <div class="flex items-center justify-center gap-4">
                        <span class="px-6 py-2 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 font-bold text-xl uppercase tracking-wider">
                            Silakan Menuju Loket
                        </span>
                    </div>
                </div>
            </div>

            <!-- Selanjutnya & Admin Control -->
            <div class="lg:col-span-4 flex flex-col gap-6">
                <!-- Selanjutnya -->
                <div class="bg-white/5 border border-white/10 rounded-3xl p-8 flex-1 flex flex-col">
                    <h3 class="text-xl font-bold text-slate-400 mb-6 uppercase tracking-wider border-b border-white/10 pb-4">Selanjutnya</h3>
                    
                    <div class="flex-1 flex flex-col items-center justify-center">
                        <template x-if="nextQueue">
                            <div class="text-center">
                                <div class="text-6xl font-black text-slate-200 mb-4" x-text="nextQueue.nomor_antrian"></div>
                                <div class="text-slate-400 text-lg">Status: <span class="text-amber-400 font-semibold">Menunggu</span></div>
                            </div>
                        </template>
                        <template x-if="!nextQueue">
                            <div class="text-center text-slate-500">
                                <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p>Belum ada antrean baru</p>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Admin Testing Panel (Hanya untuk demo/tes) -->
                <div class="bg-white/5 border border-white/10 rounded-3xl p-6">
                    <p class="text-xs text-slate-500 mb-4 uppercase tracking-widest text-center">Panel Admin (Tes Fitur)</p>
                    <div class="grid grid-cols-2 gap-3">
                        <button @click="testAdd()" class="col-span-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-sm transition">
                            + Tambah Pasien
                        </button>
                        <button @click="testCall()" class="px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold transition shadow-lg">
                            Panggil (Next)
                        </button>
                        <button @click="testDone()" class="px-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold transition shadow-lg">
                            Selesai (Done)
                        </button>
                    </div>
                </div>
            </div>
            
        </main>
    </div>

    <!-- Script Logic API Polling & Audio -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('queueSystem', () => ({
                currentQueue: null,
                nextQueue: null,
                soundEnabled: false,
                currentTime: '',
                currentDate: '',

                init() {
                    this.updateClock();
                    setInterval(() => this.updateClock(), 1000);

                    // Initial Fetch
                    this.fetchData();

                    // Polling setiap 3 detik
                    setInterval(() => {
                        this.fetchData();
                    }, 3000);
                },

                enableSound() {
                    this.soundEnabled = true;
                    this.playBeep(); // Test sound
                },

                playBeep() {
                    if (!this.soundEnabled) return;
                    try {
                        const ctx = new (window.AudioContext || window.webkitAudioContext)();
                        
                        // Bunyi "Ting"
                        const osc1 = ctx.createOscillator();
                        const gain1 = ctx.createGain();
                        osc1.type = 'sine';
                        osc1.frequency.value = 880; // A5
                        osc1.connect(gain1);
                        gain1.connect(ctx.destination);
                        osc1.start(ctx.currentTime);
                        gain1.gain.exponentialRampToValueAtTime(0.00001, ctx.currentTime + 1.5);
                        osc1.stop(ctx.currentTime + 1.5);

                        // Bunyi "Tong"
                        setTimeout(() => {
                            const osc2 = ctx.createOscillator();
                            const gain2 = ctx.createGain();
                            osc2.type = 'sine';
                            osc2.frequency.value = 659.25; // E5
                            osc2.connect(gain2);
                            gain2.connect(ctx.destination);
                            osc2.start(ctx.currentTime);
                            gain2.gain.exponentialRampToValueAtTime(0.00001, ctx.currentTime + 2);
                            osc2.stop(ctx.currentTime + 2);
                        }, 500);

                    } catch (e) {
                        console.log('Audio error:', e);
                    }
                },

                async fetchData() {
                    try {
                        // Ambil antrean saat ini (Current)
                        const resCurrent = await fetch('/api/queue/current');
                        const dataCurrent = await resCurrent.json();
                        
                        // Jika ada perubahan pada antrean yang dipanggil, bunyikan bel
                        if (dataCurrent && this.currentQueue) {
                            if (dataCurrent.id !== this.currentQueue.id) {
                                this.playBeep();
                            }
                        } else if (dataCurrent && !this.currentQueue) {
                            // Baru pertama kali dipanggil dari kosong
                            this.playBeep();
                        }
                        
                        this.currentQueue = dataCurrent && dataCurrent.id ? dataCurrent : null;

                        // Ambil antrean berikutnya (Next)
                        const resNext = await fetch('/api/queue/next');
                        const dataNext = await resNext.json();
                        this.nextQueue = dataNext && dataNext.id ? dataNext : null;

                    } catch (error) {
                        console.error('Gagal mengambil data:', error);
                    }
                },

                // --- FUNGSI ADMIN TES ---
                async testAdd() {
                    await fetch('/api/queue/add', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                    });
                    this.fetchData();
                },

                async testCall() {
                    await fetch('/api/queue/call', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                    });
                    this.fetchData();
                },

                async testDone() {
                    await fetch('/api/queue/done', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                    });
                    this.fetchData();
                },

                updateClock() {
                    const now = new Date();
                    this.currentTime = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                    this.currentDate = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                }
            }));
        });
    </script>
</body>
</html>
