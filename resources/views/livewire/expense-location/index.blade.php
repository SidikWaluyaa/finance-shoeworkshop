<div class="container mx-auto">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="page-header">Tempat Pengeluaran</h1>
            <p class="page-description">Kelola lokasi atau vendor pengeluaran secara terpusat untuk analisis biaya yang lebih akurat</p>
        </div>
        <button wire:click="$dispatch('createLocation')" class="btn btn-primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Lokasi
        </button>
    </div>

    {{-- Search --}}
    <div class="card mb-8">
        <div class="relative max-w-md">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-[var(--color-secondary)]">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
            <input type="text" wire:model.live.debounce.300ms="search" 
                class="form-input pl-11" 
                placeholder="Cari lokasi atau vendor...">
        </div>
    </div>

    {{-- Locations Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($locations as $location)
            <div class="card group" wire:key="loc-{{ $location->id }}">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary font-bold text-xl ring-2 ring-primary/5">
                            {{ $location->icon ?: substr($location->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-[var(--color-dark)] group-hover:text-primary transition-colors leading-tight">{{ $location->name }}</h3>
                            <p class="text-[10px] text-[var(--color-secondary)] mt-1 flex items-center gap-1 font-bold uppercase tracking-wider">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $location->address ?: 'Alamat belum diatur' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-1">
                        <button wire:click="$dispatch('editLocation', { id: {{ $location->id }} })" class="btn btn-secondary btn-sm p-2 min-w-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        @can('manage locations')
                        <button wire:click="delete({{ $location->id }})" wire:confirm="Hapus lokasi ini?" class="btn btn-danger btn-sm p-2 min-w-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                        @endcan
                    </div>
                </div>

                @if($location->description)
                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-[var(--color-dm-surface2)]/50 mb-4 border border-slate-100 dark:border-transparent">
                        <p class="text-xs text-[var(--color-secondary)] line-clamp-2 italic">"{{ $location->description }}"</p>
                    </div>
                @endif

                <div class="pt-4 border-t border-[var(--color-border)] flex items-center justify-between text-[10px]">
                    <span class="text-[var(--color-secondary)] uppercase font-black tracking-widest opacity-60">Total Kunjungan</span>
                    <span class="font-black text-[var(--color-dark)]">{{ $location->transactions_count ?? $location->transactions()->count() }} x</span>
                </div>
            </div>
        @empty
            <div class="col-span-full card py-20 text-center border-dashed">
                <div class="w-20 h-20 bg-slate-50 dark:bg-[var(--color-dm-surface2)] rounded-3xl flex items-center justify-center mx-auto mb-5 text-[var(--color-secondary)]">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                </div>
                <h3 class="text-[var(--color-dark)] font-black text-xl">Data Lokasi Kosong</h3>
                <p class="text-[var(--color-secondary)] text-sm mt-2 mb-8 max-w-sm mx-auto">Mulai catat tempat atau vendor langganan pengeluaran operasional Anda.</p>
                <button wire:click="$dispatch('createLocation')" class="btn btn-primary">Tambah Lokasi Pertama</button>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $locations->links() }}
    </div>

    <livewire:expense-location.form />
</div>
