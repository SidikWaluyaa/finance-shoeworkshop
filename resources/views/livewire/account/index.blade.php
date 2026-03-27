<div x-data="{ showHistory: @entangle('showHistory') }" class="relative">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-8">
        <div>
            <h2 class="page-header">Akun (Sumber Dana)</h2>
            <p class="page-description">Kelola saldo bank, kas fisik, dan e-wallet secara terpusat</p>
        </div>
        <button wire:click="$dispatch('createAccount')" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Akun
        </button>
    </div>

    {{-- Search --}}
    <div class="card mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari akun..." class="form-input">
    </div>

    {{-- Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($accounts as $acc)
            <div class="card group cursor-pointer transition-all duration-200 hover:scale-[1.02] hover:border-[var(--color-primary)]/30" 
                 wire:key="acc-{{ $acc->id }}"
                 wire:click="openHistory({{ $acc->id }})">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center
                            {{ $acc->type === 'bank' ? 'bg-blue-50 dark:bg-blue-500/10' : ($acc->type === 'cash' ? 'bg-green-50 dark:bg-emerald-500/10' : 'bg-purple-50 dark:bg-purple-500/10') }}">
                            @if($acc->type === 'bank')
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            @elseif($acc->type === 'cash')
                                <svg class="w-5 h-5 text-[var(--color-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            @else
                                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="font-semibold text-[var(--color-dark)] group-hover:text-[var(--color-primary)]">{{ $acc->name }}</h3>
                            <p class="text-xs text-[var(--color-secondary)] capitalize">{{ $acc->type }}</p>
                        </div>
                    </div>
                    <div class="flex gap-1" onclick="event.stopPropagation()">
                        <button wire:click="$dispatch('editAccount', { id: {{ $acc->id }} })" class="btn btn-secondary btn-sm p-1.5 min-w-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        @can('manage accounts')
                        <button wire:click="delete({{ $acc->id }})" wire:confirm="Hapus akun ini?" class="btn btn-danger btn-sm p-1.5 min-w-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                        @endcan
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-[var(--color-border)] flex items-center justify-between">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-[var(--color-secondary)]">{{ $acc->transactions_count }} transaksi</p>
                    <span class="text-[10px] font-black text-[var(--color-primary)] opacity-0 group-hover:opacity-100 transition-opacity">Lihat Riwayat →</span>
                </div>
            </div>
        @empty
            <div class="col-span-3 card text-center py-12">
                <div class="w-16 h-16 rounded-3xl bg-slate-50 dark:bg-[var(--color-dm-surface2)] flex items-center justify-center mx-auto mb-3">
                    <span class="text-2xl">🏦</span>
                </div>
                <p class="text-sm font-bold text-[var(--color-secondary)] uppercase tracking-widest">Belum ada akun.</p>
            </div>
        @endforelse
    </div>

    {{-- ===== SLIDE-OVER DRAWER ===== --}}
    <div x-show="showHistory" 
         class="fixed inset-0 z-[60] overflow-hidden" 
         style="display: none;"
         x-transition:enter="transition ease-in-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in-out duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        {{-- Overlay --}}
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showHistory = false"></div>

        {{-- Panel --}}
        <div class="absolute inset-y-0 right-0 max-w-full flex">
            <div x-show="showHistory"
                 x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 class="w-screen max-w-md">
                
                <div class="h-full flex flex-col bg-white dark:bg-[var(--color-dm-surface)] shadow-2xl relative">
                    {{-- Drawer Header --}}
                    <div class="px-6 py-5 border-b border-slate-100 dark:border-[var(--color-dm-border)] flex items-center justify-between bg-white dark:bg-[var(--color-dm-surface)] sticky top-0 z-10">
                        <div>
                            <h3 class="text-lg font-black text-slate-900 dark:text-white leading-tight">
                                {{ $selectedAccount?->name ?? 'Detail Akun' }}
                            </h3>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mt-0.5">Riwayat Transaksi</p>
                        </div>
                        <button @click="showHistory = false" class="p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-[var(--color-dm-surface2)] transition-colors text-slate-400 group">
                            <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Drawer Content --}}
                    <div class="flex-1 overflow-y-auto custom-scrollbar p-6">
                        @if($selectedAccount)
                            <div class="space-y-4">
                                {{-- Balance Info Card --}}
                                <div class="p-5 rounded-3xl bg-slate-900 text-white relative overflow-hidden shadow-lg mb-6">
                                    <div class="absolute top-0 right-0 w-32 h-32 rounded-full -mr-16 -mt-16 bg-emerald-500/10 pointer-events-none"></div>
                                    <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-50 mb-1">Saldo Saat Ini</p>
                                    <h4 class="text-2xl font-black">
                                        Rp {{ number_format($selectedAccount->realtime_balance, 0, ',', '.') }}
                                    </h4>
                                </div>

                                {{-- Transaction List --}}
                                <div class="space-y-3">
                                    @forelse($historyTransactions as $tx)
                                        <div class="p-4 rounded-2xl border border-slate-50 dark:border-[var(--color-dm-border)] bg-slate-50/50 dark:bg-[var(--color-dm-surface2)]/30 hover:bg-white dark:hover:bg-[var(--color-dm-surface2)]/50 hover:shadow-md hover:border-slate-100 transition-all duration-200 group">
                                            <div class="flex items-center justify-between gap-4">
                                                <div class="flex items-center gap-3 min-w-0">
                                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 
                                                        {{ $tx->type === 'income' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}">
                                                        @if($tx->type === 'income')
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19V5m0 0l-7 7m7-7l7 7"/></svg>
                                                        @else
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 5v14m0 0l-7-7m7 7l7-7"/></svg>
                                                        @endif
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="text-xs font-black text-slate-800 dark:text-slate-200 truncate mb-0.5">
                                                            {{ $tx->description ?: ($tx->category?->name ?? 'Transaksi') }}
                                                        </p>
                                                        <p class="text-[10px] font-bold text-slate-400">
                                                            {{ $tx->date->format('d M Y') }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="text-right shrink-0">
                                                    <p class="text-sm font-black {{ $tx->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                                        {{ $tx->type === 'income' ? '+' : '-' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                                                    </p>
                                                    <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400 mt-0.5">
                                                        {{ $tx->category?->name ?? 'Lainnya' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-10">
                                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Belum ada transaksi</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
