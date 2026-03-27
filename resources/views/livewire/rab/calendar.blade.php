<div class="card overflow-hidden min-h-[600px] p-0 sm:p-6">
    <!-- Calendar Header -->
    <div class="flex items-center justify-between p-4 sm:p-0 mb-4 sm:mb-8 border-b sm:border-b-0 border-slate-100 dark:border-slate-700/50">
        <div>
            <h2 class="text-2xl font-black" :class="dark ? 'text-[var(--color-dm-text)]' : 'text-slate-800'">{{ $monthName }} {{ $currentYear }}</h2>
            <p class="text-sm font-medium mt-1 text-slate-500 dark:text-slate-400">Visualisasi Jadwal Anggaran RAB</p>
        </div>
        <div class="flex items-center gap-2 bg-slate-50 dark:bg-slate-800/50 p-1.5 rounded-2xl border border-slate-200/60 dark:border-slate-700/50 shadow-inner">
            <button wire:click="previousMonth" class="p-2 hover:bg-white dark:hover:bg-slate-700 hover:shadow-sm rounded-xl transition-all text-slate-500 dark:text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button wire:click="nextMonth" class="p-2 hover:bg-white dark:hover:bg-slate-700 hover:shadow-sm rounded-xl transition-all text-slate-500 dark:text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="grid grid-cols-7 border-t border-l border-slate-100 dark:border-slate-700/50 sm:rounded-2xl overflow-hidden bg-slate-50/30 dark:bg-slate-800/20">
        <!-- Weekdays -->
        @foreach(['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $day)
            <div class="bg-slate-100/50 dark:bg-slate-800 py-3 text-center text-[10px] sm:text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest border-r border-b border-slate-100 dark:border-slate-700/50">
                {{ $day }}
            </div>
        @endforeach

        <!-- Empty cells for first day -->
        @for($i = 0; $i < $firstDayOfMonth; $i++)
            <div class="aspect-square bg-slate-50/50 dark:bg-slate-800/30 border-r border-b border-slate-100 dark:border-slate-700/50"></div>
        @endfor

        <!-- Days of month -->
        @for($day = 1; $day <= $daysInMonth; $day++)
            @php
                $currentDate = Carbon\Carbon::createFromDate($currentYear, $currentMonth, $day)->startOfDay();
                $isToday = $currentDate->isToday();
                
                $dayRabs = $rabs->filter(function($rab) use ($currentDate) {
                    return $currentDate->between(
                        Carbon\Carbon::parse($rab->start_date)->startOfDay(),
                        Carbon\Carbon::parse($rab->end_date)->endOfDay()
                    );
                });
            @endphp
            <div class="aspect-square p-1 sm:p-2 border-r border-b border-slate-100 dark:border-slate-700/50 group hover:bg-white dark:hover:bg-slate-800/80 transition-colors relative min-h-[80px] sm:min-h-[100px]">
                <div @class([
                    'w-6 h-6 sm:w-8 sm:h-8 flex items-center justify-center text-xs sm:text-sm font-bold rounded-full mb-1.5 transition-all',
                    'bg-[#22AF85] text-white shadow-md shadow-[#22AF85]/30' => $isToday,
                    'text-slate-700 dark:text-slate-300' => !$isToday
                ])>
                    {{ $day }}
                </div>

                <!-- RAB Indicators -->
                <div class="space-y-1 sm:space-y-1.5 overflow-hidden">
                    @foreach($dayRabs->take(3) as $rab)
                        <div class="px-1.5 sm:px-2 py-0.5 sm:py-1 text-[8px] sm:text-[10px] font-bold bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-500/30 rounded pl-1.5 border-l-[3px] border-l-emerald-500 truncate cursor-pointer hover:bg-emerald-200 dark:hover:bg-emerald-500/30 transition-colors"
                             wire:click="$dispatch('editRab', { id: {{ $rab->id }} })">
                            {{ $rab->name }}
                        </div>
                    @endforeach
                    @if($dayRabs->count() > 3)
                        <div class="text-[8px] font-bold text-slate-400 dark:text-slate-500 pl-1">+{{ $dayRabs->count() - 3 }} lainnya</div>
                    @endif
                </div>

                @if($dayRabs->isEmpty())
                    <button class="absolute inset-0 w-full h-full opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity"
                            wire:click="$dispatch('createRab')">
                        <span class="bg-white/90 dark:bg-slate-700/90 backdrop-blur-sm px-2.5 py-1.5 rounded-lg shadow-sm border border-slate-200 dark:border-slate-600 text-[10px] font-bold text-slate-500 dark:text-slate-300 transform scale-95 group-hover:scale-100 transition-transform">
                            + Anggaran
                        </span>
                    </button>
                @endif
            </div>
        @endfor

        <!-- Empty cells for last days -->
        @php $totalCells = $firstDayOfMonth + $daysInMonth; @endphp
        @for($i = 0; $i < (7 - ($totalCells % 7)) % 7; $i++)
            <div class="aspect-square bg-slate-50/50 dark:bg-slate-800/30 border-r border-b border-slate-100 dark:border-slate-700/50"></div>
        @endfor
    </div>
</div>
