<div>
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-8">
        <div>
            <h2 class="page-header">Piutang (Invoice)</h2>
            <p class="page-description">Kelola piutang dan tagihan klien untuk memantau arus kas masuk</p>
        </div>
        <button wire:click="$dispatch('createInvoice')" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Invoice
        </button>
    </div>

    {{-- Filters --}}
    <div class="card mb-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari klien..." class="form-input">
            </div>
            <select wire:model.live="filterStatus" class="form-select sm:w-40">
                <option value="">Semua Status</option>
                <option value="unpaid">Belum Bayar</option>
                <option value="paid">Lunas</option>
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Klien</th>
                        <th class="text-right">Total</th>
                        <th>Status</th>
                        <th>Jatuh Tempo</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $inv)
                        <tr wire:key="inv-{{ $inv->id }}">
                            <td class="font-medium">{{ $inv->client_name }}</td>
                            <td class="text-right whitespace-nowrap">
                                <span class="font-semibold block">Rp {{ number_format($inv->total, 0, ',', '.') }}</span>
                                <div class="w-full bg-gray-100 dark:bg-[var(--color-dm-surface2)] rounded-full h-1 mt-1 overflow-hidden">
                                    <div class="bg-[var(--color-primary)] h-1 transition-all duration-500" style="width: {{ ($inv->paid_amount / $inv->total) * 100 }}%"></div>
                                </div>
                                <span class="text-[10px] text-[var(--color-secondary)] uppercase">Dibayar: Rp {{ number_format($inv->paid_amount, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                @if($inv->payment_status === 'paid')
                                    <span class="badge badge-success">Lunas</span>
                                @elseif($inv->payment_status === 'partial')
                                    <span class="badge" style="background: var(--color-primary-light); color: var(--color-primary-dark);">Dicicil</span>
                                @elseif($inv->isOverdue())
                                    <span class="badge badge-danger">Jatuh Tempo</span>
                                @else
                                    <span class="badge badge-warning">Belum Bayar</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap text-sm">{{ $inv->due_date->format('d M Y') }}</td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @if($inv->payment_status !== 'paid')
                                        <button wire:click="markAsPaid({{ $inv->id }})" wire:confirm="Terima sisa pembayaran sebesar Rp {{ number_format($inv->remaining_amount, 0, ',', '.') }}?" class="btn btn-sm" style="background: var(--color-primary); color: white;">
                                            ✓ {{ $inv->payment_status === 'partial' ? 'Lunaskan' : 'Bayar' }}
                                        </button>
                                    @endif
                                    <button wire:click="download({{ $inv->id }})" class="btn btn-secondary btn-sm" title="Download PDF">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    </button>
                                    <button wire:click="$dispatch('editInvoice', { id: {{ $inv->id }} })" class="btn btn-secondary btn-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    @can('delete invoices')
                                    <button wire:click="delete({{ $inv->id }})" wire:confirm="Hapus invoice ini?" class="btn btn-danger btn-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-16">
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <div class="w-20 h-20 rounded-[2.5rem] bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center">
                                    <span class="text-3xl">🎉</span>
                                </div>
                                <div>
                                    <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-2">Semua Lunas!</p>
                                    <p class="text-sm text-slate-500">Tidak ada piutang yang perlu ditagih saat ini.</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $invoices->links() }}</div>
    </div>
</div>
