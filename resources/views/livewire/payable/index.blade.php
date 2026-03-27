<div>
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-[var(--color-dark)]">Utang (Payables)</h2>
            <p class="text-sm text-[var(--color-secondary)] mt-1">Kelola utang kepada supplier</p>
        </div>
        <button wire:click="$dispatch('createPayable')" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Utang
        </button>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="card mb-4 bg-green-50 border border-green-200 text-[var(--color-primary-dark)] text-sm flex items-center gap-2">
            ✅ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="card mb-4 bg-red-50 border border-red-200 text-red-700 text-sm">{{ session('error') }}</div>
    @endif

    {{-- Filters --}}
    <div class="card mb-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari supplier..." class="form-input">
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
                        <th>Supplier</th>
                        <th class="text-right">Total</th>
                        <th>Status</th>
                        <th>Jatuh Tempo</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payables as $pay)
                        <tr wire:key="pay-{{ $pay->id }}">
                            <td>
                                <div class="font-medium">{{ $pay->supplier_name }}</div>
                                @if($pay->description)
                                    <div class="text-xs text-[var(--color-secondary)] max-w-xs truncate">{{ $pay->description }}</div>
                                @endif
                            </td>
                            <td class="text-right whitespace-nowrap">
                                <span class="font-semibold block text-red-500">Rp {{ number_format($pay->total, 0, ',', '.') }}</span>
                                <div class="w-full bg-gray-100 rounded-full h-1 mt-1 overflow-hidden">
                                    <div class="bg-red-500 h-1 transition-all duration-500" style="width: {{ ($pay->paid_amount / $pay->total) * 100 }}%"></div>
                                </div>
                                <span class="text-[10px] text-[var(--color-secondary)] uppercase">Terbayar: Rp {{ number_format($pay->paid_amount, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                @if($pay->payment_status === 'paid')
                                    <span class="badge badge-success">Lunas</span>
                                @elseif($pay->payment_status === 'partial')
                                    <span class="badge" style="background: var(--color-primary-light); color: var(--color-primary-dark);">Dicicil</span>
                                @elseif($pay->isOverdue())
                                    <span class="badge badge-danger">Jatuh Tempo</span>
                                @else
                                    <span class="badge badge-warning">Belum Bayar</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap text-sm">{{ $pay->due_date->format('d M Y') }}</td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @if($pay->payment_status !== 'paid')
                                        <button wire:click="confirmPay({{ $pay->id }})" class="btn btn-sm" style="background: var(--color-primary); color: white;">
                                            💰 {{ $pay->payment_status === 'partial' ? 'Cicil' : 'Bayar' }}
                                        </button>
                                    @endif
                                    <button wire:click="$dispatch('editPayable', { id: {{ $pay->id }} })" class="btn btn-secondary btn-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    @can('delete payables')
                                    <button wire:click="delete({{ $pay->id }})" wire:confirm="Hapus utang ini?" class="btn btn-danger btn-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-16">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <div class="w-16 h-16 rounded-3xl bg-rose-50 flex items-center justify-center">
                                        <span class="text-2xl">🙌</span>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Bebas Utang!</p>
                                        <p class="text-sm text-slate-500">Semua kewajiban Anda sudah lunas atau belum tercatat.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $payables->links() }}</div>
    </div>

    {{-- Pay Confirmation Modal --}}
    @if($showPayModal)
    <div class="modal-backdrop" x-data x-transition>
        <div class="modal-content" @click.outside="$wire.closePayModal()">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-[var(--color-dark)]">💰 Bayar Utang</h3>
                <button wire:click="closePayModal" class="p-1 rounded-lg hover:bg-gray-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="bg-yellow-50 rounded-xl p-4 mb-4">
                <p class="text-xs text-[var(--color-accent-dark)]">
                    ⚠️ Pembayaran akan membuat transaksi <strong>pengeluaran</strong> otomatis.
                </p>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Akun Pembayaran</label>
                        <select wire:model="payAccountId" class="form-select">
                            <option value="">Pilih Akun</option>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->name }} ({{ $acc->type }})</option>
                            @endforeach
                        </select>
                        @error('payAccountId') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="form-label">Jumlah Pembayaran (Rp)</label>
                        <input type="number" wire:model="payAmount" class="form-input" placeholder="0">
                        @error('payAmount') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex gap-2 pt-2">
                    <button wire:click="markAsPaid" class="btn btn-primary flex-1">
                        <span wire:loading.remove wire:target="markAsPaid">✅ Konfirmasi Pembayaran</span>
                        <span wire:loading wire:target="markAsPaid">Memproses...</span>
                    </button>
                    <button wire:click="closePayModal" class="btn btn-secondary">Batal</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
