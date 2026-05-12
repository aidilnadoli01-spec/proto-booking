@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Control Panel -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-6">📺 Panel Kontrol TV Display</h1>

                <!-- Current Queue Info -->
                <div class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-lg p-6 mb-6 text-white">
                    <h2 class="text-lg font-semibold mb-4">Antrean Saat Ini</h2>
                    <div id="currentQueueInfo">
                        <p class="text-gray-200">Memuat...</p>
                    </div>
                </div>

                <!-- Call Next Button -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Aksi Antrean</h2>
                    <button id="callNextBtn" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition duration-200 mb-4">
                        ✅ Panggil Antrean Berikutnya
                    </button>
                    <button id="resetBtn" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-lg transition duration-200">
                        🔄 Reset Antrean Hari Ini
                    </button>
                </div>

                <!-- Next Queue Preview -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Antrean Berikutnya</h2>
                    <div id="nextQueuePreview" class="space-y-2">
                        <p class="text-gray-500">Memuat...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- TV Display Link -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📺 Akses TV Display</h3>
                <a href="{{ route('tv.display') }}" target="_blank" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg text-center transition duration-200">
                    Buka TV Display
                </a>
                <p class="text-sm text-gray-500 mt-3">
                    Buka di fullscreen di layar TV terpisah
                </p>
            </div>

            <!-- Statistics -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📊 Statistik</h3>
                <div id="statsInfo" class="space-y-3">
                    <p class="text-gray-500">Memuat...</p>
                </div>
            </div>

            <!-- Auto Refresh Info -->
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                <h3 class="font-semibold text-yellow-800 mb-2">ℹ️ Info Auto Refresh</h3>
                <p class="text-sm text-yellow-700">
                    TV Display akan otomatis refresh setiap 3 detik untuk menampilkan data terbaru.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    let currentQueueNumber = null;

    async function fetchQueueData() {
        try {
            const response = await fetch('/api/tv-queue');
            const data = await response.json();

            if (data.success) {
                updateCurrentQueueInfo(data.current);
                updateNextQueuePreview(data.nextQueue);
                updateStats(data.statistics);
            }
        } catch (error) {
            console.error('Error fetching queue data:', error);
            document.getElementById('currentQueueInfo').innerHTML = '<p class="text-red-200">Error memuat data</p>';
        }
    }

    function updateCurrentQueueInfo(current) {
        const container = document.getElementById('currentQueueInfo');
        if (current) {
            container.innerHTML = `
                <div>
                    <p class="text-2xl font-bold mb-2">Nomor: <span class="text-4xl">${current.nomor_antrean}</span></p>
                    <p class="mb-1">Loket: <strong>${current.loket}</strong></p>
                    <p class="mb-1">Dokter: <strong>Dr. ${current.dokter}</strong></p>
                    <p class="text-sm text-gray-300">Dipanggil: ${current.dipanggil_pada || '-'}</p>
                </div>
            `;
            currentQueueNumber = current.nomor_antrean;
        } else {
            container.innerHTML = '<p class="text-gray-200">Belum ada antrean dipanggil</p>';
            currentQueueNumber = null;
        }
    }

    function updateNextQueuePreview(nextQueue) {
        const container = document.getElementById('nextQueuePreview');
        if (!nextQueue || nextQueue.length === 0) {
            container.innerHTML = '<p class="text-gray-500">Tidak ada antrean menunggu</p>';
            return;
        }

        const items = nextQueue.slice(0, 5).map((item, index) => `
            <div class="bg-white p-3 rounded border-l-4 border-blue-500">
                <p class="font-semibold text-gray-800">${index + 1}. ${item.nomor_antrean}</p>
                <p class="text-sm text-gray-600">Status: ${item.status}</p>
            </div>
        `).join('');

        container.innerHTML = items;
    }

    function updateStats(statistics) {
        const container = document.getElementById('statsInfo');
        container.innerHTML = `
            <div>
                <p class="text-2xl font-bold text-blue-600">${statistics.waiting}</p>
                <p class="text-gray-600">Menunggu</p>
            </div>
            <div>
                <p class="text-2xl font-bold text-green-600">${statistics.completed}</p>
                <p class="text-gray-600">Selesai Hari Ini</p>
            </div>
            <div>
                <p class="text-2xl font-bold text-purple-600">${statistics.activeDoctors}</p>
                <p class="text-gray-600">Dokter Aktif</p>
            </div>
        `;
    }

    document.getElementById('callNextBtn').addEventListener('click', async function() {
        if (!confirm('Yakin ingin panggil antrean berikutnya?')) {
            return;
        }

        try {
            const response = await fetch('/api/tv-display/call-next', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                }
            });

            const data = await response.json();

            if (data.success) {
                alert('✅ ' + data.message);
                fetchQueueData();
            } else {
                alert('❌ ' + (data.message || 'Gagal panggil antrean'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('❌ Error: ' + error.message);
        }
    });

    document.getElementById('resetBtn').addEventListener('click', async function() {
        if (!confirm('⚠️ Yakin ingin reset SEMUA antrean hari ini? Ini tidak bisa dibatalkan!')) {
            return;
        }

        try {
            const response = await fetch('/api/tv-display/reset', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                }
            });

            const data = await response.json();

            if (data.success) {
                alert('✅ ' + data.message);
                fetchQueueData();
            } else {
                alert('❌ ' + (data.message || 'Gagal reset antrean'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('❌ Error: ' + error.message);
        }
    });

    // Initial load
    document.addEventListener('DOMContentLoaded', () => {
        fetchQueueData();
        setInterval(fetchQueueData, 3000);
    });
</script>
@endsection
