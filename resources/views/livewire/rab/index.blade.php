<div>
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">RAB (Anggaran)</h1>
            <p class="text-gray-500 mt-1 text-sm">Kelola perencanaan anggaran produksi & operasional</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- View Switcher -->
            <div class="flex items-center bg-gray-100 p-1 rounded-2xl border border-gray-200">
                <button wire:click="$set('viewMode', 'list')" 
                    @class([
                        'flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition-all',
                        'bg-white shadow-sm text-primary' => $viewMode === 'list',
                        'text-gray-500 hover:text-gray-700' => $viewMode !== 'list'
                    ])>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    List
                </button>
                <button wire:click="$set('viewMode', 'calendar')" 
                    @class([
                        'flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition-all',
                        'bg-white shadow-sm text-primary' => $viewMode === 'calendar',
                        'text-gray-500 hover:text-gray-700' => $viewMode !== 'calendar'
                    ])>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Kalender
                </button>
            </div>

            @can('create rabs')
            <button wire:click="$dispatch('createRab')" 
                class="flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-2xl font-semibold shadow-lg shadow-primary/20 hover:bg-primary-dark transition-all active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah RAB
            </button>
            @endcan

        </div>
    </div>

    @if($viewMode === 'list')
        {{-- Search --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 mb-8">
            <div class="relative max-w-md">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="text" wire:model.live.debounce.300ms="search" 
                    class="w-full pl-11 pr-4 py-3 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 outline-none transition-all" 
                    placeholder="Cari RAB...">
            </div>
        </div>

        {{-- RAB Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($rabs as $rab)
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all group" wire:key="rab-{{ $rab->id }}">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-900 group-hover:text-primary transition-colors">{{ $rab->name }}</h3>
                            @if($rab->description)
                                <p class="text-xs text-gray-500 mt-1 line-clamp-1">{{ $rab->description }}</p>
                            @endif
                            @if($rab->start_date && $rab->end_date)
                                <p class="text-[10px] text-gray-400 mt-1 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ $rab->start_date->format('d M') }} - {{ $rab->end_date->format('d M Y') }}
                                </p>
                            @endif
                        </div>
                        <div class="flex gap-1 ml-2">
                            <button wire:click="download({{ $rab->id }})" class="p-2 hover:bg-gray-100 rounded-xl transition-all text-gray-400 hover:text-primary" title="Download PDF">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            </button>
                            
                            @can('edit rabs')
                            <button wire:click="$dispatch('editRab', { id: {{ $rab->id }} })" class="p-2 hover:bg-gray-100 rounded-xl transition-all text-gray-400 hover:text-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            @endcan

                            @can('delete rabs')
                            <button wire:click="delete({{ $rab->id }})" wire:confirm="Hapus RAB ini?" class="p-2 hover:bg-red-50 rounded-xl transition-all text-gray-400 hover:text-red-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                            @endcan
                        </div>

                    </div>

                    <div x-data="{ open: false }" class="mt-4 border-t border-gray-50 pt-4">
                        <button @click="open = !open" class="flex items-center gap-2 text-[10px] font-bold text-primary hover:opacity-80 transition mb-3 uppercase tracking-wider">
                            <svg class="w-3 h-3 transition-transform" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                            LIHAT ITEM ({{ $rab->items->count() }})
                        </button>
                        
                        <div x-show="open" x-collapse class="bg-gray-50 rounded-2xl p-4 mb-4 space-y-3 border border-gray-100">
                            @foreach($rab->items as $item)
                            <div class="flex justify-between items-center text-[11px]">
                                <span class="text-gray-500">{{ $item->name }}</span>
                                <span class="font-bold text-gray-900">Rp {{ number_format($item->amount, 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Budget Progress --}}
                    <div class="space-y-3">
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">Total Anggaran</span>
                            <span class="font-bold text-gray-900">Rp {{ number_format($rab->total_budget, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">Terpakai</span>
                            <span class="font-bold text-red-500">Rp {{ number_format($rab->used_budget, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">Sisa</span>
                            <span class="font-bold text-emerald-500">Rp {{ number_format($rab->remaining_budget, 0, ',', '.') }}</span>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="w-full h-3 bg-gray-100 rounded-full mt-2 overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-700
                                {{ $rab->usage_percent > 90 ? 'bg-red-500' : ($rab->usage_percent > 70 ? 'bg-amber-500' : 'bg-primary') }}"
                                style="width: {{ min(100, $rab->usage_percent) }}%">
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] text-gray-400 font-medium uppercase tracking-tight">{{ $rab->usage_percent }}% Dialokasikan</span>
                            @if($rab->usage_percent > 90)
                                <span class="text-[10px] text-red-500 font-bold animate-pulse inline-flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    LIMIT!
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-[2rem] p-16 text-center border border-dashed border-slate-200">
                    <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-slate-900 font-black text-lg mb-2">Belum Ada Rencana Anggaran</h3>
                    <p class="text-slate-500 text-sm max-w-xs mx-auto mb-8 leading-relaxed">Mulai rencanakan anggaran produksi atau operasional untuk menjaga kesehatan arus kas Anda.</p>
                    @can('create rabs')
                    <button wire:click="$dispatch('createRab')" 
                            class="inline-flex items-center gap-2 px-6 py-3 bg-[var(--color-primary)] text-white rounded-2xl font-bold shadow-lg shadow-[var(--color-primary)]/20 hover:scale-105 transition-all active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Buat RAB Pertama
                    </button>
                    @endcan

                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $rabs->links() }}
        </div>
    @else
        <livewire:rab.calendar />
    @endif
</div>
