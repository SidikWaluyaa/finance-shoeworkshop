<div>
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-8">
        <div>
            <h2 class="page-header">Transaksi</h2>
            <p class="page-description">Kelola semua catatan keuangan arus kas masuk dan keluar</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="Livewire.dispatch('openImportModal')" class="btn btn-secondary !bg-white dark:!bg-slate-800 border border-slate-200 dark:border-slate-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                <span class="hidden sm:inline">Import</span>
            </button>
            @can('create transactions')
            <button wire:click="$dispatch('createTransaction')" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span class="hidden sm:inline">Tambah Transaksi</span>
                <span class="sm:hidden text-lg">+</span>
            </button>
            @endcan
        </div>

    </div>

    {{-- Filters --}}
    <div class="card mb-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari transaksi..." class="form-input">
            </div>
            <select wire:model.live="filterType" class="form-select sm:w-40">
                <option value="">Semua Tipe</option>
                <option value="income">Pemasukan</option>
                <option value="expense">Pengeluaran</option>
            </select>
            <select wire:model.live="filterSource" class="form-select sm:w-40">
                <option value="">Semua Source</option>
                <option value="B2B">B2B</option>
                <option value="B2C">B2C</option>
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Deskripsi</th>
                        <th>Lokasi</th>
                        <th>Kategori</th>
                        <th>Tipe</th>
                        <th>Source</th>
                        <th class="text-right">Jumlah</th>
                        <th class="text-center">Bukti</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $tx)
                        <tr wire:key="tx-{{ $tx->id }}">
                            <td class="whitespace-nowrap">{{ $tx->date->format('d/m/Y') }}</td>
                            <td>
                                <div class="max-w-xs truncate font-medium text-[var(--color-dark)]">{{ $tx->description ?: '-' }}</div>
                                <div class="text-[10px] text-[var(--color-secondary)] uppercase font-semibold">{{ $tx->account?->name }}</div>
                            </td>
                            <td class="whitespace-nowrap">
                                @if($tx->location)
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-primary">{{ $tx->location->icon ?: '📍' }}</span>
                                        <span class="text-[var(--color-dark)] font-medium">{{ $tx->location->name }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                            <td>{{ $tx->category?->name ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $tx->type === 'income' ? 'badge-success' : 'badge-danger' }}">
                                    {{ $tx->type === 'income' ? 'Masuk' : 'Keluar' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-warning">{{ $tx->source_type }}</span>
                            </td>
                            <td class="text-right whitespace-nowrap font-semibold {{ $tx->type === 'income' ? 'text-[var(--color-primary)]' : 'text-red-500' }}">
                                {{ $tx->type === 'income' ? '+' : '-' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                            </td>
                        <td class="text-center">
                            @if($tx->evidence_path)
                                <a href="{{ asset('storage/' . $tx->evidence_path) }}" target="_blank" class="inline-block w-8 h-8 rounded-lg overflow-hidden border border-[var(--color-border)] hover:border-[var(--color-primary)] transition">
                                    <img src="{{ asset('storage/' . $tx->evidence_path) }}" class="w-full h-full object-cover">
                                </a>
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-1">
                                @can('edit transactions')
                                <button wire:click="$dispatch('editTransaction', { id: {{ $tx->id }} })" class="btn btn-secondary btn-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                @endcan
                                
                                @can('delete transactions')
                                <button wire:click="delete({{ $tx->id }})" wire:confirm="Hapus transaksi ini?" class="btn btn-danger btn-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                                @endcan
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-16">
                            <div class="flex flex-col items-center justify-center space-y-3 prose-sm mx-auto">
                                <div class="w-16 h-16 rounded-3xl bg-slate-50 dark:bg-[var(--color-dm-surface2)]/50 flex items-center justify-center">
                                    <span class="text-2xl">📝</span>
                                </div>
                                <div class="text-[var(--color-secondary)]">
                                    <p class="font-bold uppercase tracking-widest text-xs mb-1">Data Kosong</p>
                                    <p class="text-sm text-slate-500">Belum ada catatan transaksi. Mulai catat transaksi pertama Anda?</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
