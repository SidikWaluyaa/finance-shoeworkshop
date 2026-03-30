<div class="card overflow-hidden min-h-[600px] p-0 sm:p-6" wire:poll.15s>
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

                $dayPayables = $payables->filter(function($p) use ($currentDate) {
                    if ($p->promise_to_pay_date) {
                        return Carbon\Carbon::parse($p->promise_to_pay_date)->startOfDay()->equalTo($currentDate);
                    }
                    return Carbon\Carbon::parse($p->due_date)->startOfDay()->equalTo($currentDate);
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
                    @foreach($dayRabs->take(2) as $rab)
                        <div class="px-1.5 sm:px-2 py-0.5 sm:py-1 text-[8px] sm:text-[10px] font-bold bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-500/30 rounded pl-1.5 border-l-[3px] border-l-emerald-500 truncate cursor-pointer hover:bg-emerald-200 dark:hover:bg-emerald-500/10 transition-colors"
                             title="RAB: {{ $rab->name }}"
                             wire:click="$dispatch('editRab', { id: {{ $rab->id }} })">
                            📝 {{ $rab->name }}
                        </div>
                    @endforeach

                    @foreach($dayPayables->take(2) as $payable)
                        @php
                            $status = $payable->payment_status;
                            $colorClass = match($status) {
                                'paid' => 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-500 border-slate-200 dark:border-slate-700 border-l-slate-400',
                                'partial' => 'bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 border-amber-200/50 dark:border-amber-500/30 border-l-amber-500',
                                default => 'bg-rose-100 dark:bg-rose-500/20 text-rose-700 dark:text-rose-400 border-rose-200/50 dark:border-rose-500/30 border-l-rose-500',
                            };
                        @endphp
                        <div class="px-1.5 sm:px-2 py-0.5 sm:py-1 text-[8px] sm:text-[10px] font-bold {{ $colorClass }} border rounded pl-1.5 border-l-[3px] truncate cursor-pointer hover:opacity-80 transition-all shadow-sm"
                             title="Utang: {{ $payable->supplier_name }} - Rp {{ number_format($payable->remaining_amount, 0, ',', '.') }} ({{ $payable->promise_to_pay_date ? 'Janji Bayar' : 'Jatuh Tempo' }})"
                             wire:click="$dispatch('editPayable', { id: {{ $payable->id }} })">
                            {{ $payable->promise_to_pay_date ? '🤝' : '💸' }} {{ $payable->supplier_name }}
                        </div>
                    @endforeach

                    @php $extraCount = max(0, $dayRabs->count() - 2) + max(0, $dayPayables->count() - 2); @endphp
                    @if($extraCount > 0)
                        <div class="text-[8px] font-bold text-slate-400 dark:text-slate-500 pl-1">+{{ $extraCount }} lainnya</div>
                    @endif
                </div>

                @if($dayRabs->isEmpty() && $dayPayables->isEmpty())
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
