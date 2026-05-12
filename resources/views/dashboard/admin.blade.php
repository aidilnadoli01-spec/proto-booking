<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">Dashboard Admin</h2>
    </x-slot>
    <div class="py-8 space-y-6">
        <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-4 gap-4">
            <div class="rounded-2xl shadow-lg p-5 text-white bg-gradient-to-r from-blue-600 to-cyan-600"><p class="text-sm opacity-90">Data Dokter</p><p class="text-3xl font-bold">{{ $dokterCount }}</p></div>
            <div class="rounded-2xl shadow-lg p-5 text-white bg-gradient-to-r from-indigo-600 to-blue-700"><p class="text-sm opacity-90">Jadwal Dokter</p><p class="text-3xl font-bold">{{ $jadwalCount }}</p></div>
            <div class="rounded-2xl shadow-lg p-5 text-white bg-gradient-to-r from-emerald-500 to-green-600"><p class="text-sm opacity-90">Antrean Hari Ini</p><p class="text-3xl font-bold">{{ $todayQueue }}</p></div>
            <div class="rounded-2xl shadow-lg p-5 text-white bg-gradient-to-r from-cyan-500 to-sky-600"><p class="text-sm opacity-90">Menunggu</p><p class="text-3xl font-bold">{{ $pendingQueue }}</p></div>
        </div>



        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="font-semibold text-slate-800 mb-4">Statistik Status Antrean</h3>
                <div class="h-80">
                    <canvas id="queueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const queueCtx = document.getElementById('queueChart');
        if (queueCtx) {
            new Chart(queueCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Menunggu', 'Dipanggil', 'Selesai', 'Batal'],
                    datasets: [{
                        data: JSON.parse('{!! json_encode(array_values($queueStatus)) !!}'),
                        backgroundColor: ['#3b82f6', '#06b6d4', '#10b981', '#f43f5e']
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        }
    </script>
</x-app-layout>
