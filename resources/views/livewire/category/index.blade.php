<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-[var(--color-dark)]">Kategori</h2>
            <p class="text-sm text-[var(--color-secondary)] mt-1">Kelola kategori pemasukan dan pengeluaran</p>
        </div>
        <button wire:click="$dispatch('createCategory')" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Kategori
        </button>
    </div>

    @if(session('error'))
        <div class="card mb-4 bg-red-50 border border-red-200 text-red-700 text-sm">{{ session('error') }}</div>
    @endif

    <div class="card mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari kategori..." class="form-input">
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Tipe</th>
                        <th>Transaksi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                        <tr wire:key="cat-{{ $cat->id }}">
                            <td class="font-medium">{{ $cat->name }}</td>
                            <td>
                                <span class="badge {{ $cat->type === 'income' ? 'badge-success' : 'badge-danger' }}">
                                    {{ $cat->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                </span>
                            </td>
                            <td>{{ $cat->transactions_count }} transaksi</td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <button wire:click="$dispatch('editCategory', { id: {{ $cat->id }} })" class="btn btn-secondary btn-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    @can('manage categories')
                                    <button wire:click="delete({{ $cat->id }})" wire:confirm="Hapus kategori ini?" class="btn btn-danger btn-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-[var(--color-secondary)]">Belum ada kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
