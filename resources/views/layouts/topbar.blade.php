<header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-10">
    <div>
        @isset($header)
            <div class="font-semibold text-xl text-slate-800">
                {{ $header }}
            </div>
        @endisset
    </div>
    <div class="flex items-center gap-6">
        <!-- Notification -->
        <div class="relative" x-data="notificationSystem()" x-init="init()">
            <button @click="toggleDropdown()" class="relative p-2 text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <template x-if="unreadCount > 0">
                    <span class="absolute top-1 right-1 w-3 h-3 bg-red-500 border-2 border-white rounded-full animate-bounce"></span>
                </template>
            </button>

            <!-- Dropdown -->
            <div x-show="isOpen" @click.away="isOpen = false" x-cloak x-transition.opacity class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-slate-100 overflow-hidden z-50">
                <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h3 class="font-bold text-slate-800">Notifikasi</h3>
                    <span x-show="unreadCount > 0" class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-medium" x-text="unreadCount + ' Baru'"></span>
                </div>
                <div class="max-h-80 overflow-y-auto">
                    <template x-if="notifications.length === 0">
                        <div class="p-6 text-center text-slate-500 text-sm">Belum ada notifikasi.</div>
                    </template>
                    <template x-for="notif in notifications" :key="notif.id">
                        <div :class="notif.is_read ? 'bg-white text-slate-600' : 'bg-blue-50 text-slate-800 font-medium'" class="p-4 border-b border-slate-50 hover:bg-slate-50 transition-colors text-sm">
                            <p x-text="notif.message" class="leading-relaxed"></p>
                            <p class="text-xs text-slate-400 mt-2" x-text="formatDate(notif.created_at)"></p>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Profile -->
        <div class="flex items-center gap-3 border-l border-slate-200 pl-6 cursor-pointer">
            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold overflow-hidden">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=E0F2FE&color=0369A1" alt="Profile" class="w-full h-full object-cover">
            </div>
            <div class="hidden md:block">
                <p class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</p>
                <p class="text-xs text-slate-500">
                    @if(auth()->user()->hasRole('super_admin'))
                        Super Admin
                    @elseif(auth()->user()->hasRole('admin'))
                        Admin
                    @else
                        Pasien
                    @endif
                </p>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('notificationSystem', () => ({
            isOpen: false,
            unreadCount: 0,
            notifications: [],
            toastVisible: false,
            toastMessage: '',
            init() {
                this.fetchUnreadCount();
                // Auto refresh every 5 seconds
                setInterval(() => {
                    this.fetchUnreadCount();
                }, 5000);
            },
            toggleDropdown() {
                this.isOpen = !this.isOpen;
                if (this.isOpen) {
                    this.fetchNotifications();
                    if (this.unreadCount > 0) {
                        this.markAllRead();
                    }
                }
            },
            fetchUnreadCount() {
                fetch('{{ route("notifications.unreadCount") }}')
                    .then(res => res.json())
                    .then(data => {
                        // Bonus: Toast alert untuk notifikasi baru
                        if (data.count > this.unreadCount && this.unreadCount !== 0) {
                            this.showToast('Ada notifikasi baru!');
                        }
                        this.unreadCount = data.count;
                    });
            },
            fetchNotifications() {
                fetch('{{ route("notifications.index") }}')
                    .then(res => res.json())
                    .then(data => {
                        this.notifications = data;
                    });
            },
            markAllRead() {
                fetch('{{ route("notifications.markAllRead") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }).then(() => {
                    this.unreadCount = 0;
                });
            },
            formatDate(dateStr) {
                const date = new Date(dateStr);
                return date.toLocaleString('id-ID', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' });
            },
            showToast(message) {
                window.dispatchEvent(new CustomEvent('show-toast', { detail: message }));
            }
        }));
    });
</script>

<!-- Simple Global Toast UI -->
<div x-data="{ visible: false, message: '' }" 
     @alpine:init="window.addEventListener('show-toast', e => { visible = true; message = e.detail; setTimeout(() => visible = false, 3000) })"
     x-show="visible" 
     x-transition.opacity.duration.300ms
     class="fixed bottom-5 right-5 bg-emerald-500 text-white px-6 py-3 rounded-xl shadow-lg z-[100] flex items-center gap-3"
     style="display: none;">
    <svg class="w-5 h-5 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
    <span x-text="message" class="font-medium text-sm"></span>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
