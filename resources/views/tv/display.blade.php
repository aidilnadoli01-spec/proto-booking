<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TV Display - Sistem Antrean</title>
    @vite('resources/css/app.css')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            height: 100vh;
            overflow: hidden;
            color: white;
        }

        .tv-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
            padding: 30px;
            gap: 20px;
        }

        /* Header */
        .tv-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 30px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .clinic-info h1 {
            font-size: clamp(24px, 3vw, 36px);
            font-weight: bold;
            color: #00d9ff;
        }

        .clinic-info p {
            font-size: clamp(14px, 1.5vw, 18px);
            color: rgba(255, 255, 255, 0.7);
        }

        .digital-clock {
            font-size: clamp(28px, 4vw, 48px);
            font-weight: bold;
            color: #00d9ff;
            font-family: 'Courier New', monospace;
            letter-spacing: 2px;
        }

        /* Main Content */
        .tv-main {
            display: flex;
            flex: 1;
            gap: 30px;
            justify-content: center;
            align-items: center;
        }

        /* Current Queue Section */
        .current-queue-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex: 1;
            min-width: 400px;
        }

        .current-label {
            font-size: clamp(28px, 3vw, 40px);
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 20px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .current-queue {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border: 2px solid #00d9ff;
            border-radius: 40px;
            padding: 40px;
            min-width: 300px;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 217, 255, 0.2);
            animation: zoomPulse 0.6s ease-out;
        }

        .queue-number {
            font-size: clamp(80px, 15vw, 180px);
            font-weight: 900;
            color: #00d9ff;
            letter-spacing: 10px;
            line-height: 1;
            margin-bottom: 20px;
            text-shadow: 0 0 20px rgba(0, 217, 255, 0.5);
        }

        .queue-counter {
            font-size: clamp(24px, 3vw, 36px);
            color: #fbbf24;
            font-weight: 600;
            margin-top: 15px;
        }

        .queue-doctor {
            font-size: clamp(16px, 2vw, 24px);
            color: rgba(255, 255, 255, 0.8);
            margin-top: 10px;
        }

        /* Next Queue Section */
        .next-queue-section {
            display: flex;
            flex-direction: column;
            flex: 0.6;
            min-width: 250px;
        }

        .next-label {
            font-size: clamp(20px, 2.5vw, 30px);
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 15px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .next-queue-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            flex: 1;
            overflow-y: auto;
            padding-right: 10px;
        }

        .next-queue-item {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(0, 217, 255, 0.3);
            border-radius: 15px;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s ease;
        }

        .next-queue-item:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: #00d9ff;
            transform: translateX(5px);
        }

        .next-queue-number {
            font-size: clamp(18px, 2.5vw, 28px);
            font-weight: bold;
            color: #00d9ff;
            min-width: 80px;
            text-align: center;
        }

        .next-queue-status {
            font-size: clamp(12px, 1.5vw, 16px);
            color: rgba(255, 255, 255, 0.6);
        }

        /* Footer - Running Text */
        .tv-footer {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 15px 30px;
            height: 60px;
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        .running-text {
            font-size: clamp(16px, 2vw, 24px);
            color: #fbbf24;
            white-space: nowrap;
            animation: scroll 15s linear infinite;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px;
            color: rgba(255, 255, 255, 0.6);
        }

        .empty-state-icon {
            font-size: clamp(60px, 10vw, 100px);
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-state-text {
            font-size: clamp(20px, 3vw, 32px);
            font-weight: 600;
        }

        /* Animations */
        @keyframes zoomPulse {
            0% {
                transform: scale(0.8);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes scroll {
            0% {
                transform: translateX(100%);
            }
            100% {
                transform: translateX(-100%);
            }
        }

        /* Scrollbar */
        .next-queue-list::-webkit-scrollbar {
            width: 6px;
        }

        .next-queue-list::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }

        .next-queue-list::-webkit-scrollbar-thumb {
            background: rgba(0, 217, 255, 0.5);
            border-radius: 10px;
        }

        .next-queue-list::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 217, 255, 0.8);
        }

        /* Statistics */
        .statistics {
            display: flex;
            gap: 15px;
            font-size: clamp(12px, 1.5vw, 16px);
            color: rgba(255, 255, 255, 0.6);
            margin-top: auto;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>
<body>
    <div class="tv-container">
        <!-- Header -->
        <div class="tv-header">
            <div class="clinic-info">
                <h1>🏥 KLINIK ANTREAN</h1>
                <p>Sistem Manajemen Antrean Digital</p>
            </div>
            <div class="digital-clock" id="clock">00:00:00</div>
        </div>

        <!-- Main Content -->
        <div class="tv-main">
            <!-- Current Queue -->
            <div class="current-queue-section">
                <div class="current-label">Nomor Antrean Saat Ini</div>
                <div class="current-queue" id="currentQueueContainer">
                    <div class="queue-number" id="queueNumber">-</div>
                    <div class="queue-counter" id="queueCounter">Loket -</div>
                    <div class="queue-doctor" id="queueDoctor">-</div>
                </div>
            </div>

            <!-- Next Queue -->
            <div class="next-queue-section">
                <div class="next-label">Antrean Berikutnya</div>
                <div class="next-queue-list" id="nextQueueList">
                    <div class="empty-state">
                        <div class="empty-state-icon">📋</div>
                        <div class="empty-state-text">Memuat...</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="tv-footer">
            <div class="running-text" id="runningText">
                ℹ️ Selamat datang di Sistem Antrean Digital | Silakan tunggu giliran Anda dengan tenang | 📞 Layanan Pelanggan: 0812-3456-7890
            </div>
        </div>
    </div>

    <script>
        // Update Digital Clock
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('clock').innerText = `${hours}:${minutes}:${seconds}`;
        }

        // Voice Notification
        function playVoiceNotification(queueNumber, counter, doctor) {
            if ('speechSynthesis' in window) {
                const message = `Nomor antrean ${queueNumber}, silakan menuju loket ${counter}. Dokter ${doctor}`;
                const utterance = new SpeechSynthesisUtterance(message);
                utterance.lang = 'id-ID';
                utterance.rate = 1;
                utterance.pitch = 1;
                window.speechSynthesis.speak(utterance);
            }
        }

        // Fetch Queue Data
        async function fetchQueueData() {
            try {
                const response = await fetch('/api/tv-queue');
                const data = await response.json();

                if (data.success) {
                    updateCurrentQueue(data.current);
                    updateNextQueue(data.nextQueue);
                    updateRunningText(data.statistics);
                }
            } catch (error) {
                console.error('Error fetching queue data:', error);
            }
        }

        // Update Current Queue Display
        function updateCurrentQueue(current) {
            const container = document.getElementById('currentQueueContainer');
            const queueNumber = document.getElementById('queueNumber');
            const queueCounter = document.getElementById('queueCounter');
            const queueDoctor = document.getElementById('queueDoctor');

            if (current) {
                // Play voice notification if changed
                const previousNumber = queueNumber.innerText;
                if (previousNumber !== current.nomor_antrean) {
                    playVoiceNotification(
                        current.nomor_antrean,
                        current.loket,
                        current.dokter
                    );
                }

                queueNumber.innerText = current.nomor_antrean;
                queueCounter.innerText = `Loket ${current.loket}`;
                queueDoctor.innerText = `Dr. ${current.dokter}`;
                container.style.animation = 'none';
                setTimeout(() => {
                    container.style.animation = 'zoomPulse 0.6s ease-out';
                }, 10);
            } else {
                queueNumber.innerText = '-';
                queueCounter.innerText = 'Loket -';
                queueDoctor.innerText = 'Belum ada antrean dipanggil';
            }
        }

        // Update Next Queue List
        function updateNextQueue(nextQueue) {
            const listContainer = document.getElementById('nextQueueList');

            if (!nextQueue || nextQueue.length === 0) {
                listContainer.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">✅</div>
                        <div class="empty-state-text">Tidak ada antrean menunggu</div>
                    </div>
                `;
                return;
            }

            listContainer.innerHTML = nextQueue.map((item, index) => `
                <div class="next-queue-item">
                    <div class="next-queue-number">${index + 1}. ${item.nomor_antrean}</div>
                    <div class="next-queue-status">Status: ${item.status === 'menunggu' ? '⏳ Menunggu' : item.status}</div>
                </div>
            `).join('');
        }

        // Update Running Text
        function updateRunningText(statistics) {
            const runningText = document.getElementById('runningText');
            runningText.innerText = `
                ℹ️ Antrean Menunggu: ${statistics.waiting} | Selesai Hari Ini: ${statistics.completed} | Dokter Aktif: ${statistics.activeDoctors} | 📞 Layanan Pelanggan: 0812-3456-7890
            `;
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            updateClock();
            fetchQueueData();

            // Update clock every second
            setInterval(updateClock, 1000);

            // Fetch queue data every 3 seconds
            setInterval(fetchQueueData, 3000);

            // Request fullscreen on desktop
            setTimeout(() => {
                if (document.documentElement.requestFullscreen) {
                    document.documentElement.requestFullscreen().catch(() => {
                        // Fullscreen request denied, continue normally
                    });
                }
            }, 1000);
        });

        // Handle ESC key to exit fullscreen
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && document.fullscreenElement) {
                document.exitFullscreen();
            }
        });
    </script>
</body>
</html>
