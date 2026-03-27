<div class="container mx-auto">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Tempat Pengeluaran</h1>
            <p class="text-gray-500 mt-1">Kelola lokasi atau vendor tempat uang keluar</p>
        </div>
        <button wire:click="$dispatch('createLocation')" 
            class="flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-2xl font-semibold shadow-lg shadow-primary/20 hover:bg-primary-dark transition-all active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Lokasi
        </button>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 mb-8">
        <div class="relative max-w-md">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
            <input type="text" wire:model.live.debounce.300ms="search" 
                class="w-full pl-11 pr-4 py-3 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 outline-none transition-all" 
                placeholder="Cari lokasi atau vendor...">
        </div>
    </div>

    {{-- Locations Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($locations as $location)
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all group" wire:key="loc-{{ $location->id }}">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary font-bold text-xl">
                            {{ $location->icon ?: substr($location->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 group-hover:text-primary transition-colors">{{ $location->name }}</h3>
                            <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $location->address ?: 'Alamat tidak diatur' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-1">
                        <button wire:click="$dispatch('editLocation', { id: {{ $location->id }} })" class="p-2 hover:bg-gray-100 rounded-xl transition-all text-gray-400 hover:text-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        @can('manage locations')
                        <button wire:click="delete({{ $location->id }})" wire:confirm="Hapus lokasi ini?" class="p-2 hover:bg-red-50 rounded-xl transition-all text-gray-400 hover:text-red-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                        @endcan
                    </div>
                </div>

                @if($location->description)
                    <p class="text-sm text-gray-500 mb-4 line-clamp-2 italic">"{{ $location->description }}"</p>
                @endif

                <div class="pt-4 border-t border-gray-50 flex items-center justify-between text-xs">
                    <span class="text-gray-400 uppercase tracking-wider font-semibold">Total Transaksi</span>
                    <span class="font-bold text-gray-900">{{ $location->transactions_count ?? $location->transactions()->count() }} Kali</span>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-dashed border-gray-200">
                <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-gray-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                </div>
                <h3 class="text-gray-900 font-bold">Belum ada lokasi pengeluaran</h3>
                <p class="text-gray-500 text-sm mt-1 mb-6">Mulai catat tempat atau vendor langganan pengeluaran Anda.</p>
                <button wire:click="$dispatch('createLocation')" class="text-primary font-bold text-sm hover:underline">Tambah Lokasi Pertama</button>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $locations->links() }}
    </div>

    <livewire:expense-location.form />
</div>
