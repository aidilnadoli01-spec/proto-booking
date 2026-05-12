<x-app-layout>
    <div x-data="userManagement()" class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 mb-1">Manajemen Pengguna</h1>
                <p class="text-slate-400 text-sm">Kelola akun, role, dan status pengguna dalam sistem.</p>
            </div>
            <button @click="openCreateModal()" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition shadow-sm hover:shadow-md">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah User
            </button>
        </div>

        <!-- Tabel User -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h2 class="font-semibold text-slate-800">Daftar Pengguna</h2>
                <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-medium border border-blue-100">{{ $users->total() }} User</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold">Pengguna</th>
                            <th class="px-6 py-4 text-left font-semibold">Role</th>
                            <th class="px-6 py-4 text-left font-semibold">Status</th>
                            <th class="px-6 py-4 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($users as $item)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <!-- Kolom Pengguna -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($item->name) }}&background=EFF6FF&color=1D4ED8"
                                            class="w-10 h-10 rounded-full border border-blue-100 shadow-sm" alt="{{ $item->name }}">
                                        <div>
                                            <p class="font-semibold text-slate-800">{{ $item->name }}</p>
                                            <p class="text-slate-500 text-xs mt-0.5">{{ $item->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <!-- Kolom Role -->
                                <td class="px-6 py-4">
                                    @php $roleName = $item->role->name ?? '-'; @endphp
                                    @if($roleName === 'super_admin')
                                        <span class="px-3 py-1 rounded-full bg-purple-100 text-purple-700 border border-purple-200 text-xs font-bold">Super Admin</span>
                                    @elseif($roleName === 'admin')
                                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 border border-blue-200 text-xs font-bold">Admin</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 border border-green-200 text-xs font-bold">Pasien</span>
                                    @endif
                                </td>
                                <!-- Kolom Status Toggle -->
                                <td class="px-6 py-4">
                                    <form method="POST" action="{{ route('super.users.toggle_status', $item) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" @if($item->id === Auth::id()) disabled title="Tidak bisa menonaktifkan akun sendiri" @endif class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold transition-all border {{ $item->is_active ? 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100' : 'bg-slate-50 text-slate-500 border-slate-200 hover:bg-slate-100' }} {{ $item->id === Auth::id() ? 'opacity-50 cursor-not-allowed' : '' }}">
                                            <span class="w-2 h-2 rounded-full {{ $item->is_active ? 'bg-green-500 animate-pulse' : 'bg-slate-400' }}"></span>
                                            {{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                        </button>
                                    </form>
                                </td>
                                <!-- Kolom Aksi -->
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="openEditModal({{ Js::from($item) }})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        @if($item->id !== Auth::id())
                                            <button @click="openDeleteModal('{{ $item->id }}', '{{ addslashes($item->name) }}')" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        @else
                                            <div class="w-9 h-9"></div> <!-- Spacer for alignment -->
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="p-5 border-t border-slate-100 bg-slate-50/50">{{ $users->links() }}</div>
            @endif
        </div>

        <!-- MODAL FORM (Create/Edit) -->
        <div x-show="showFormModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div x-show="showFormModal" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeFormModal()"></div>
            
            <div x-show="showFormModal" x-transition.scale.origin.bottom class="bg-white rounded-2xl shadow-xl border border-slate-100 w-full max-w-md relative z-10 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-800" x-text="isEdit ? 'Edit Pengguna' : 'Tambah Pengguna Baru'"></h3>
                    <button @click="closeFormModal()" class="text-slate-400 hover:text-slate-600 transition"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                
                <form :action="formAction" method="POST" class="p-6">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PATCH">
                    </template>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" x-model="formData.name" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" x-model="formData.email" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">
                                Password 
                                <template x-if="isEdit"><span class="text-slate-400 font-normal">(Kosongkan jika tidak ingin mengubah)</span></template>
                                <template x-if="!isEdit"><span class="text-red-500">*</span></template>
                            </label>
                            <input type="password" name="password" :required="!isEdit" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Role <span class="text-red-500">*</span></label>
                            <select name="role_id" x-model="formData.role_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm appearance-none bg-white">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center gap-3 pt-2">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" x-model="formData.is_active" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                            </label>
                            <span class="text-sm font-semibold text-slate-700">Akun Aktif</span>
                        </div>
                    </div>
                    
                    <div class="mt-8 flex gap-3 justify-end">
                        <button type="button" @click="closeFormModal()" class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">Batal</button>
                        <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm transition">Simpan Pengguna</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL DELETE -->
        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div x-show="showDeleteModal" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showDeleteModal = false"></div>
            
            <div x-show="showDeleteModal" x-transition.scale.origin.bottom class="bg-white rounded-2xl shadow-xl border border-slate-100 w-full max-w-sm relative z-10 overflow-hidden text-center p-6">
                <div class="w-16 h-16 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Hapus Pengguna?</h3>
                <p class="text-sm text-slate-500 mb-6">Anda yakin ingin menghapus <span class="font-bold text-slate-800" x-text="deleteName"></span>? Tindakan ini tidak dapat dibatalkan.</p>
                
                <form :action="deleteAction" method="POST" class="flex gap-3 justify-center">
                    @csrf @method('DELETE')
                    <button type="button" @click="showDeleteModal = false" class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition w-full">Batal</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-sm transition w-full">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('userManagement', () => ({
                showFormModal: false,
                showDeleteModal: false,
                isEdit: false,
                formAction: '',
                deleteAction: '',
                deleteName: '',
                formData: {
                    name: '',
                    email: '',
                    role_id: '',
                    is_active: true
                },
                openCreateModal() {
                    this.isEdit = false;
                    this.formAction = '{{ route("super.users.store") }}';
                    this.formData = {
                        name: '',
                        email: '',
                        role_id: '', // default empty, user must pick
                        is_active: true
                    };
                    this.showFormModal = true;
                },
                openEditModal(user) {
                    this.isEdit = true;
                    this.formAction = '/super-admin/users/' + user.id;
                    this.formData = {
                        name: user.name,
                        email: user.email,
                        role_id: user.role_id,
                        is_active: user.is_active == 1
                    };
                    this.showFormModal = true;
                },
                closeFormModal() {
                    this.showFormModal = false;
                },
                openDeleteModal(id, name) {
                    this.deleteAction = '/super-admin/users/' + id;
                    this.deleteName = name;
                    this.showDeleteModal = true;
                }
            }));
        });
    </script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
