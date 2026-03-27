<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-8">
        <div>
            <h2 class="page-header">Manajemen Pengguna</h2>
            <p class="page-description">Kelola hak akses dan akun staf untuk keamanan sistem</p>
        </div>
        <button wire:click="$dispatch('openUserForm')" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Pengguna
        </button>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>PENGGUNA</th>
                        <th>EMAIL</th>
                        <th>PERAN</th>
                        <th>BERGABUNG SEJAK</th>
                        <th class="text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr wire:key="user-{{ $user->id }}">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-[var(--color-primary)]/20 flex items-center justify-center text-[var(--color-primary)] font-bold text-xs ring-2 ring-[var(--color-surface)]">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <span class="font-medium text-[var(--color-dark)] border-none bg-transparent">{{ $user->name }}
                                        @if(auth()->id() == $user->id) 
                                        <span class="ml-2 badge badge-success text-[10px] uppercase font-bold px-2 py-0.5 opacity-80">Anda</span> 
                                        @endif
                                    </span>
                                </div>
                            </td>
                            <td class="text-[var(--color-secondary)] font-medium">{{ $user->email }}</td>
                            <td>
                                @forelse($user->roles as $role)
                                    <span class="badge {{ $role->name === 'Super Admin' ? 'badge-danger' : ($role->name === 'Manager' ? 'badge-primary' : 'badge-secondary') }} text-[10px] uppercase font-bold px-2 py-0.5 whitespace-nowrap">
                                        {{ $role->name }}
                                    </span>
                                @empty
                                    <span class="text-slate-300 text-[10px] italic">Tanpa Peran</span>
                                @endforelse
                            </td>
                            <td class="text-[var(--color-secondary)]">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <button wire:click="$dispatch('openUserForm', { id: {{ $user->id }} })" class="btn btn-secondary btn-sm" title="Edit Pengguna">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    @if(auth()->id() != $user->id)
                                    <button wire:click="delete({{ $user->id }})" wire:confirm="Hapus akun pengguna ini secara permanen?" class="btn btn-danger btn-sm" title="Hapus Pengguna">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    @else
                                    <button disabled class="btn btn-secondary btn-sm opacity-50 cursor-not-allowed hidden sm:inline-flex" title="Tidak dapat menghapus diri sendiri">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-12">
                                <div class="flex flex-col items-center justify-center opacity-50">
                                    <svg class="w-12 h-12 text-slate-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    <span class="text-[var(--color-secondary)] font-medium tracking-wide">Belum ada akun pengguna lain.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
        <div class="px-5 py-4 border-t border-[var(--color-border)]">
            {{ $users->links() }}
        </div>
        @endif
    </div>

    <!-- Include Modal Form -->
    <livewire:user.form />
</div>
