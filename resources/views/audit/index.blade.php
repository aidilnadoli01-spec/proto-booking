<x-app-layout>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800 mb-1">Audit Log</h1>
        <p class="text-slate-400 text-sm">Rekam jejak semua aktivitas di dalam sistem.</p>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-6">
        <form method="GET" class="grid md:grid-cols-6 gap-3 items-end">
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Cari</label>
                <input name="q" value="{{ $filters['q'] }}" placeholder="action / type / id / ip"
                    class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Action</label>
                <select name="action" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    <option value="">Semua</option>
                    @foreach ($actions as $a)
                        <option value="{{ $a }}" @selected($filters['action'] === $a)>{{ $a }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5">User</label>
                <select name="user_id" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    <option value="">Semua</option>
                    @foreach ($users as $u)
                        <option value="{{ $u->id }}" @selected((string) $filters['user_id'] === (string) $u->id)>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Dari</label>
                <input type="date" name="date_from" value="{{ $filters['date_from'] }}"
                    class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Sampai</label>
                <input type="date" name="date_to" value="{{ $filters['date_to'] }}"
                    class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
            </div>
            <div class="md:col-span-6 flex gap-2">
                <button class="px-5 py-2.5 bg-cyan-500 hover:bg-cyan-600 text-white rounded-xl text-sm font-medium transition">Filter</button>
                <a href="{{ route('audit.index') }}" class="px-5 py-2.5 border border-slate-200 hover:bg-slate-50 rounded-xl text-slate-600 text-sm font-medium transition">Reset</a>
            </div>
        </form>
    </div>

    <!-- Log Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-semibold text-slate-800">Log Aktivitas</h2>
            @if($filters['date_from'] || $filters['date_to'])
                <span class="text-xs text-slate-400">{{ $filters['date_from'] }} {{ $filters['date_to'] ? '- '.$filters['date_to'] : '' }}</span>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-5 py-3.5 text-left font-medium">Waktu</th>
                        <th class="px-5 py-3.5 text-left font-medium">User</th>
                        <th class="px-5 py-3.5 text-left font-medium">Aktivitas</th>
                        <th class="px-5 py-3.5 text-left font-medium">Target</th>
                        <th class="px-5 py-3.5 text-left font-medium">Waktu Log</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="px-5 py-4 text-slate-500 whitespace-nowrap text-xs">
                                {{ $log->created_at->format('d M Y') }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($log->user?->name ?? 'S') }}&background=e0f2fe&color=0369a1&size=32"
                                        class="w-7 h-7 rounded-full" alt="">
                                    <div>
                                        <p class="font-medium text-slate-800 text-xs">{{ $log->user?->name ?? '-' }}</p>
                                        <p class="text-xs text-slate-400">{{ $log->user?->role?->label ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="font-medium text-slate-800">{{ $log->action }}</span>
                            </td>
                            <td class="px-5 py-4 text-slate-500">
                                <span class="font-mono text-xs">{{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}</span>
                            </td>
                            <td class="px-5 py-4 text-slate-400 text-xs whitespace-nowrap">
                                {{ $log->created_at->format('d M Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-slate-400">Belum ada data audit.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">{{ $logs->links() }}</div>
    </div>
</x-app-layout>
