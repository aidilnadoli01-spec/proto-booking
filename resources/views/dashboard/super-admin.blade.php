<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">Dashboard Super Admin</h2>
    </x-slot>
    <div class="py-8 space-y-6">
        <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-4 gap-4">
            <div class="rounded-2xl shadow-lg p-5 text-white bg-gradient-to-r from-blue-600 to-cyan-600"><p class="text-sm opacity-90">Total User</p><p class="text-3xl font-bold">{{ $userCount }}</p></div>
            <div class="rounded-2xl shadow-lg p-5 text-white bg-gradient-to-r from-violet-600 to-indigo-600"><p class="text-sm opacity-90">Total Role</p><p class="text-3xl font-bold">{{ $roleCount }}</p></div>
            <div class="rounded-2xl shadow-lg p-5 text-white bg-gradient-to-r from-emerald-500 to-green-600"><p class="text-sm opacity-90">Total Booking</p><p class="text-3xl font-bold">{{ $bookingCount }}</p></div>
            <div class="rounded-2xl shadow-lg p-5 text-white bg-gradient-to-r from-cyan-500 to-sky-600"><p class="text-sm opacity-90">Antrean Hari Ini</p><p class="text-3xl font-bold">{{ $todayQueue }}</p></div>
        </div>



        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="font-semibold text-slate-800 mb-4">Tren Booking 7 Hari Terakhir</h3>
                <div class="h-80">
                    <canvas id="bookingTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const bookingCtx = document.getElementById('bookingTrendChart');
        if (bookingCtx) {
            new Chart(bookingCtx, {
                type: 'line',
                data: {
                    labels: JSON.parse('{!! json_encode($chartLabels) !!}'),
                    datasets: [{
                        label: 'Jumlah Booking',
                        data: JSON.parse('{!! json_encode($bookingByDay) !!}'),
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.18)',
                        tension: 0.35,
                        fill: true
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: true, position: 'bottom' }
                    },
                    scales: {
                        y: { beginAtZero: true, ticks: { precision: 0 } }
                    }
                }
            });
        }
    </script>
</x-app-layout>
