<div>
    {{-- View Options --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="page-header">RAB (Anggaran)</h1>
            <p class="page-description">Kelola perencanaan anggaran produksi & operasional</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- View Switcher -->
            <div class="flex items-center bg-slate-100 dark:bg-slate-800 p-1 rounded-2xl border border-slate-200 dark:border-slate-700">
                <button wire:click="$set('viewMode', 'list')" 
                    @class([
                        'flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-black transition-all',
                        'bg-white dark:bg-slate-700 shadow-sm text-emerald-500' => $viewMode === 'list',
                        'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200' => $viewMode !== 'list'
                    ])>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    List
                </button>
                <button wire:click="$set('viewMode', 'calendar')" 
                    @class([
                        'flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-black transition-all',
                        'bg-white dark:bg-slate-700 shadow-sm text-emerald-500' => $viewMode === 'calendar',
                        'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200' => $viewMode !== 'calendar'
                    ])>
                     Kalender
                </button>
            </div>

            @can('create rabs')
            <button wire:click="$dispatch('createRab')" class="btn btn-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah RAB
            </button>
            @endcan
        </div>
    </div>

    @if($viewMode === 'list')
        {{-- Search Section --}}
        <div class="card mb-8">
            <div class="relative max-w-md">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="text" wire:model.live.debounce.300ms="search" 
                    class="form-input pl-11" placeholder="Cari RAB...">
            </div>
        </div>

        {{-- RAB Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($rabs as $rab)
                <div class="card p-6 border-slate-100 dark:border-slate-700 group hover:shadow-xl transition-all" wire:key="rab-{{ $rab->id }}">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="font-black text-slate-800 dark:text-slate-100 group-hover:text-emerald-500 transition-colors">{{ $rab->name }}</h3>
                            @if($rab->description)
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 line-clamp-1 italic">"{{ $rab->description }}"</p>
                            @endif
                            @if($rab->start_date && $rab->end_date)
                                <p class="text-[10px] text-slate-400 mt-2 flex items-center gap-1 font-bold uppercase tracking-tighter">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ $rab->start_date->format('d M') }} - {{ $rab->end_date->format('d M Y') }}
                                </p>
                            @endif
                        </div>
                        <div class="flex gap-1 ml-2">
                            <button wire:click="download({{ $rab->id }})" class="p-2 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 rounded-xl transition-all text-slate-400 hover:text-emerald-500" title="Download PDF">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            </button>
                            
                            @can('edit rabs')
                            <button wire:click="$dispatch('editRab', { id: {{ $rab->id }} })" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition-all text-slate-400 hover:text-blue-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            @endcan

                            @can('delete rabs')
                            <button wire:click="delete({{ $rab->id }})" wire:confirm="Hapus RAB ini?" class="p-2 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-xl transition-all text-slate-400 hover:text-rose-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                            @endcan
                        </div>
                    </div>

                    <div x-data="{ open: false }" class="mt-4 border-t border-slate-50 dark:border-slate-700 pt-4">
                        <button @click="open = !open" class="flex items-center gap-2 text-[10px] font-black text-emerald-500 hover:opacity-80 transition mb-3 uppercase tracking-widest">
                            <svg class="w-3 h-3 transition-transform" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                            LIHAT ITEM ({{ $rab->items->count() }})
                        </button>
                        
                        <div x-show="open" x-collapse class="bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-4 mb-4 space-y-3 border border-slate-100 dark:border-slate-700/50">
                            @foreach($rab->items as $item)
                            <div class="flex justify-between items-center text-[11px]">
                                <span class="text-slate-500 dark:text-slate-400 font-medium">{{ $item->name }}</span>
                                <span class="font-black text-slate-800 dark:text-slate-200">Rp {{ number_format($item->amount, 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Budget Progress --}}
                    <div class="space-y-3 bg-slate-50/50 dark:bg-slate-800/30 p-4 rounded-2xl border border-slate-100/50 dark:border-slate-700/50">
                        <div class="flex justify-between text-[11px]">
                            <span class="text-slate-500 dark:text-slate-400 font-medium uppercase tracking-tighter">Budget</span>
                            <span class="font-black text-slate-800 dark:text-slate-100">Rp {{ number_format($rab->total_budget, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-[11px]">
                            <span class="text-slate-500 dark:text-slate-400 font-medium uppercase tracking-tighter text-red-400">Terpakai</span>
                            <span class="font-black text-rose-500">Rp {{ number_format($rab->used_budget, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-[11px] border-t border-slate-100 dark:border-slate-700 pt-2">
                            <span class="text-slate-500 dark:text-slate-400 font-medium uppercase tracking-tighter">Sisa</span>
                            <span class="font-black text-emerald-500">Rp {{ number_format($rab->remaining_budget, 0, ',', '.') }}</span>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="w-full h-2.5 bg-slate-100 dark:bg-slate-700 rounded-full mt-2 overflow-hidden shadow-inner">
                            <div class="h-full rounded-full transition-all duration-700
                                {{ $rab->usage_percent > 90 ? 'bg-rose-500' : ($rab->usage_percent > 70 ? 'bg-amber-500' : 'bg-emerald-500') }}"
                                style="width: {{ min(100, $rab->usage_percent) }}%">
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest">{{ $rab->usage_percent }}% Dialokasikan</span>
                            @if($rab->usage_percent > 90)
                                <span class="text-[9px] text-rose-500 font-black animate-pulse inline-flex items-center gap-1 uppercase tracking-tighter">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    Limit Terlampaui!
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full card p-16 text-center border-dashed border-2">
                    <div class="w-24 h-24 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200 dark:text-slate-700">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-slate-900 dark:text-white font-black text-xl mb-2">Belum Ada Rencana Anggaran</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm max-w-xs mx-auto mb-8">Rencanakan anggaran produksi Anda sekarang untuk memantau sisa saldo secara akurat.</p>
                    @can('create rabs')
                    <button wire:click="$dispatch('createRab')" class="btn btn-primary">
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
