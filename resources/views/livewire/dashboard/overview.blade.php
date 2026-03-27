<div wire:poll.60s="refreshData">
    {{-- ===== PAGE HEADER ===== --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div>
                <h2 class="text-2xl font-extrabold tracking-tight"
                    :class="dark ? 'text-[var(--color-dm-text)]' : 'text-[var(--color-dark)]'">
                    Pusat Keuangan
                </h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="w-2 h-2 rounded-full bg-[var(--color-primary)] animate-pulse"></span>
                    <p class="text-xs font-medium" :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-[var(--color-secondary)]'">
                        Sistem Monitoring Shoeworkshop
                    </p>
                </div>
            </div>
            {{-- NEW: Live Badge --}}
            <div class="hidden md:flex items-center gap-1.5 px-3 py-1.5 rounded-full border bg-emerald-50/50 border-emerald-100">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span class="text-[10px] font-black uppercase tracking-widest text-emerald-600">Live Sync</span>
            </div>
        </div>
        {{-- Period Filter --}}
        <div class="flex items-center gap-1.5 p-1 rounded-2xl border self-start sm:self-auto shadow-sm"
             :class="dark ? 'bg-[var(--color-dm-surface)] border-[var(--color-dm-border)]' : 'bg-white border-[var(--color-border)]'">
            <button wire:click="$set('period', 'this_month')"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all
                       {{ $period === 'this_month' ? 'bg-[var(--color-primary)] text-white shadow-md' : 'text-[var(--color-secondary)] hover:bg-[var(--color-primary-muted)] hover:text-[var(--color-primary)]' }}">
                Bulan Ini
            </button>
            <button wire:click="$set('period', '3_months')"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all
                       {{ $period === '3_months' ? 'bg-[var(--color-primary)] text-white shadow-md' : 'text-[var(--color-secondary)] hover:bg-[var(--color-primary-muted)] hover:text-[var(--color-primary)]' }}">
                3 Bulan
            </button>
            <button wire:click="$set('period', 'all')"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all
                       {{ $period === 'all' ? 'bg-[var(--color-primary)] text-white shadow-md' : 'text-[var(--color-secondary)] hover:bg-[var(--color-primary-muted)] hover:text-[var(--color-primary)]' }}">
                Semua
            </button>
        </div>
    </div>

    {{-- ===== HERO: Health Score + Saldo ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 mb-6">

        {{-- Health Score Card (dark glass style) --}}
        <div class="lg:col-span-8 rounded-3xl p-6 md:p-8 text-white relative overflow-hidden shadow-xl"
             style="background: linear-gradient(135deg, #0F1511 0%, #152217 50%, #0F1F1A 100%);">
            {{-- Glow blobs --}}
            <div class="absolute top-0 right-0 w-64 h-64 rounded-full -mr-20 -mt-20 pointer-events-none"
                 style="background: radial-gradient(circle, rgba(34,175,133,0.2) 0%, transparent 70%);"></div>
            <div class="absolute bottom-0 left-1/2 w-48 h-48 rounded-full pointer-events-none"
                 style="background: radial-gradient(circle, rgba(255,194,50,0.08) 0%, transparent 70%);"></div>

            <div class="relative z-10 flex flex-col sm:flex-row items-center gap-8">
                {{-- Score Ring --}}
                <div class="relative flex-shrink-0">
                    <svg width="150" height="150" viewBox="0 0 140 140">
                        <circle cx="70" cy="70" r="60" stroke="rgba(255,255,255,0.06)" stroke-width="10" fill="none"/>
                        <circle cx="70" cy="70" r="60"
                                stroke="url(#scoreGrad)" stroke-width="12" fill="none"
                                stroke-dasharray="{{ 2 * 3.14159 * 60 }}"
                                stroke-dashoffset="{{ 2 * 3.14159 * 60 * (1 - ($healthScore['total'] ?? 0) / 100) }}"
                                stroke-linecap="round"
                                transform="rotate(-90 70 70)"
                                style="transition: stroke-dashoffset 1s ease-out;"/>
                        <defs>
                            <linearGradient id="scoreGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" style="stop-color:#FFC232"/>
                                <stop offset="100%" style="stop-color:#22AF85"/>
                            </linearGradient>
                        </defs>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-4xl font-black leading-none">{{ $healthScore['total'] ?? 0 }}</span>
                        <span class="text-[9px] uppercase font-bold tracking-[0.18em] opacity-50 mt-1">Indeks Kesehatan</span>
                    </div>
                </div>

                {{-- Metric mini-cards --}}
                <div class="flex-1 w-full">
                    <h3 class="text-base font-bold mb-0.5">Performa Keuangan</h3>
                    <p class="text-xs text-white/50 mb-5">
                        Berdasarkan data {{ $period === 'this_month' ? 'bulan ini' : ($period === '3_months' ? '3 bulan terakhir' : 'historis') }}.
                    </p>
                    <div class="grid grid-cols-3 gap-3">
                        @php
                            $metrics = [
                                ['label' => 'Arus Kas', 'val' => $healthScore['cashflow'] ?? 0, 'color' => '#22AF85'],
                                ['label' => 'Utang',    'val' => $healthScore['payable']  ?? 0, 'color' => '#FFC232'],
                                ['label' => 'Anggaran', 'val' => $healthScore['rab']       ?? 0, 'color' => '#3DC99D'],
                            ];
                        @endphp
                        @foreach($metrics as $m)
                        <div class="bg-white/5 border border-white/10 rounded-2xl p-3 hover:bg-white/8 transition">
                            <p class="text-[10px] font-semibold opacity-60 mb-2">{{ $m['label'] }}</p>
                            <p class="text-xl font-black leading-none mb-2">{{ $m['val'] }}%</p>
                            <div class="h-1 bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-700" style="width:{{ $m['val'] }}%; background:{{ $m['color'] }};"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4">
            <div class="card h-full flex flex-col">
                <p class="text-[10px] uppercase font-black tracking-[0.18em] text-[var(--color-muted)] mb-2">Kas & Bank</p>
                <h4 class="text-2xl md:text-3xl font-black tracking-tight mb-5"
                    :class="dark ? 'text-[var(--color-dm-text)]' : 'text-[var(--color-dark)]'">
                    Rp {{ number_format($summary['total_balance'] ?? 0, 0, ',', '.') }}
                </h4>
                <div class="space-y-2 flex-1">
                    @foreach(array_slice($summary['account_balances'] ?? [], 0, 3) as $acc)
                    <div class="flex items-center justify-between p-2.5 rounded-xl border transition hover:border-[var(--color-primary)]/40"
                         :class="dark ? 'bg-[var(--color-dm-surface2)] border-[var(--color-dm-border)]' : 'bg-slate-50 border-slate-100'">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center text-sm border"
                                 :class="dark ? 'bg-[var(--color-dm-surface)] border-[var(--color-dm-border)]' : 'bg-white shadow-sm border-slate-100'">
                                {{ $acc['type'] === 'bank' ? '🏦' : '💵' }}
                            </div>
                            <div>
                                <p class="text-[11px] font-bold leading-none" :class="dark ? 'text-[var(--color-dm-text)]' : 'text-slate-700'">{{ strtoupper($acc['name']) }}</p>
                                <p class="text-[10px] mt-0.5 capitalize" :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-slate-400'">{{ $acc['type'] }}</p>
                            </div>
                        </div>
                        <span class="text-sm font-black" :class="dark ? 'text-[var(--color-dm-text)]' : 'text-slate-900'">
                            {{ $this->formatCurrencyShort($acc['balance']) }}
                        </span>
                    </div>
                    @endforeach
                </div>
                <a href="{{ route('accounts') }}"
                   class="mt-5 w-full inline-flex items-center justify-center py-3 rounded-xl text-xs font-bold transition shadow-md"
                   style="background: var(--color-accent); color: #111827; box-shadow: 0 4px 12px rgba(255,194,50,0.25);">
                    Kelola Akun
                </a>
            </div>
        </div>
    </div>

    {{-- ===== KEY STATS ===== --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        @php
            $stats = [
                ['label' => 'Pemasukan',    'value' => $summary['total_income'] ?? 0,       'mom' => $summary['mom']['income'] ?? null,  'colorCls' => 'emerald', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6', 'url' => route('transactions')],
                ['label' => 'Pengeluaran',  'value' => $summary['total_expense'] ?? 0,      'mom' => $summary['mom']['expense'] ?? null, 'colorCls' => 'rose',    'icon' => 'M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6', 'url' => route('transactions')],
                ['label' => 'Total Piutang','value' => $summary['total_receivables'] ?? 0,  'mom' => null,                               'colorCls' => 'amber',   'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'url' => route('invoices')],
                ['label' => 'Total Utang',  'value' => $summary['total_payables'] ?? 0,    'mom' => null,                               'colorCls' => 'slate',   'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'url' => route('payables')],
            ];
        @endphp
        @foreach($stats as $s)
        <div class="card group cursor-pointer transition-all duration-300 hover:scale-[1.05] hover:shadow-xl hover:-translate-y-1"
             onclick="window.location.href='{{ $s['url'] }}'">
            <div class="flex items-start justify-between mb-4">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-300 shrink-0
                            bg-{{ $s['colorCls'] }}-50 text-{{ $s['colorCls'] }}-600
                            group-hover:bg-{{ $s['colorCls'] }}-500 group-hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $s['icon'] }}"/>
                    </svg>
                </div>
                @if($s['mom'])
                @php
                    $isGood = ($s['mom']['trend'] === 'up' && $s['label'] === 'Pemasukan')
                           || ($s['mom']['trend'] === 'down' && $s['label'] === 'Pengeluaran');
                @endphp
                <span class="text-[10px] font-black px-2 py-1 rounded-lg
                             {{ $isGood ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                    {{ $s['mom']['trend'] === 'up' ? '↑' : '↓' }} {{ $s['mom']['percent'] }}%
                </span>
                @endif
            </div>
            <p class="text-[10px] uppercase font-black tracking-widest text-[var(--color-muted)] mb-1">{{ $s['label'] }}</p>
            <h5 class="text-lg font-black tracking-tight"
                :class="dark ? 'text-[var(--color-dm-text)]' : 'text-[var(--color-dark)]'">
                Rp {{ number_format($s['value'], 0, ',', '.') }}
            </h5>
        </div>
        @endforeach
    </div>

    {{-- ===== WAWASAN AI ===== --}}
    @if(count($insights) > 0)
    <div class="mb-6 rounded-3xl p-5 md:p-6 relative overflow-hidden shadow-lg"
         style="background: linear-gradient(135deg, #0F1511 0%, #152217 100%);">
        <div class="absolute top-0 right-0 w-32 h-32 pointer-events-none"
             style="background: radial-gradient(circle at top right, rgba(34,175,133,0.12), transparent 70%);"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-4">
                <span class="text-base">✨</span>
                <h3 class="text-sm font-black tracking-tight text-white">Wawasan Finansial AI</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($insights as $insight)
                <div class="flex items-start gap-3 p-3.5 rounded-2xl bg-white/5 border border-white/8 hover:bg-white/8 transition">
                    <span class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center text-base shrink-0">{{ $insight['icon'] }}</span>
                    <p class="text-xs font-medium leading-relaxed text-white/80">{{ $insight['text'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- ===== CHARTS ===== --}}
    <div class="mb-6 rounded-2xl border p-5 md:p-6 shadow-sm transition-colors"
         :class="dark ? 'bg-[var(--color-dm-surface)] border-[var(--color-dm-border)]' : 'bg-white border-[var(--color-border)]'">
        <livewire:dashboard.charts />
    </div>

    {{-- ===== AKTIVITAS TERBARU ===== --}}
    <div class="rounded-2xl border overflow-hidden mb-20 md:mb-6 shadow-sm transition-colors"
         :class="dark ? 'bg-[var(--color-dm-surface)] border-[var(--color-dm-border)]' : 'bg-white border-[var(--color-border)]'">

        {{-- Top accent --}}
        <div class="h-1" style="background: linear-gradient(90deg, var(--color-primary), var(--color-accent), var(--color-primary));"></div>

        <div class="p-5 md:p-6">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="font-black text-base leading-none mb-1"
                        :class="dark ? 'text-[var(--color-dm-text)]' : 'text-slate-900'">Aktivitas Terbaru</h3>
                    <p class="text-[10px] font-bold uppercase tracking-widest" :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-slate-400'">Update Real-time</p>
                </div>
                <a href="{{ route('transactions') }}"
                   class="w-9 h-9 rounded-2xl flex items-center justify-center transition-all shadow-sm border"
                   :class="dark
                       ? 'bg-[var(--color-dm-surface2)] border-[var(--color-dm-border)] text-[var(--color-dm-muted)] hover:text-[var(--color-primary)] hover:border-[var(--color-primary)]/40'
                       : 'bg-slate-50 border-slate-100 text-slate-400 hover:text-[var(--color-primary)] hover:bg-[var(--color-primary-muted)]'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>

            {{-- Transaction list --}}
            <div class="divide-y"
                 :class="dark ? 'divide-[var(--color-dm-border)]' : 'divide-slate-50'">
                @forelse($recentTransactions as $tx)
                <div class="group flex items-center justify-between gap-4 py-3 -mx-1 px-1 hover:rounded-xl transition"
                     :class="dark ? 'hover:bg-[var(--color-dm-surface2)]' : 'hover:bg-slate-50/70'">
                    <div class="flex items-center gap-3 min-w-0">
                        {{-- Icon bubble --}}
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0 transition-all duration-200
                                    {{ $tx->type === 'income'
                                        ? 'bg-emerald-50 text-emerald-600 group-hover:bg-emerald-500 group-hover:text-white'
                                        : 'bg-rose-50 text-rose-600 group-hover:bg-rose-500 group-hover:text-white' }}">
                            @if($tx->type === 'income')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19V5m0 0l-7 7m7-7l7 7"/></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 5v14m0 0l-7-7m7 7l7-7"/></svg>
                            @endif
                        </div>
                        {{-- Info --}}
                        <div class="min-w-0">
                            <p class="text-sm font-bold truncate transition"
                               :class="dark ? 'text-[var(--color-dm-text)] group-hover:text-[var(--color-primary-light)]' : 'text-slate-800 group-hover:text-[var(--color-primary)]'">
                                {{ $tx->description ?: ($tx->category?->name ?? 'Transaksi') }}
                            </p>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="text-[10px] font-semibold" :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-slate-400'">{{ $tx->date->translatedFormat('d M') }}</span>
                                <span class="w-1 h-1 rounded-full" :class="dark ? 'bg-[var(--color-dm-border)]' : 'bg-slate-300'"></span>
                                <span class="text-[10px] font-bold uppercase truncate" :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-slate-500'">{{ $tx->account?->name }}</span>
                            </div>
                        </div>
                    </div>
                    {{-- Amount --}}
                    <div class="text-right shrink-0">
                        <p class="text-sm font-black {{ $tx->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $tx->type === 'income' ? '+' : '-' }}{{ $this->formatCurrencyShort($tx->amount) }}
                        </p>
                        @if($tx->location)
                        <p class="text-[9px] font-semibold mt-0.5 truncate max-w-[100px]" :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-slate-400'">
                            📍 {{ $tx->location->name }}
                        </p>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-14">
                    <div class="w-14 h-14 rounded-3xl mx-auto flex items-center justify-center mb-3"
                         :class="dark ? 'bg-[var(--color-dm-surface2)]' : 'bg-slate-50'">
                        <span class="text-xl opacity-40">📋</span>
                    </div>
                    <p class="text-xs font-black uppercase tracking-widest" :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-slate-400'">Belum ada aktivitas</p>
                </div>
                @endforelse
            </div>

            {{-- CTA Footer --}}
            <div class="mt-4 pt-4 -mx-5 md:-mx-6 px-5 md:px-6 border-t"
                 :class="dark ? 'border-[var(--color-dm-border)] bg-[var(--color-dm-surface2)]/50' : 'border-slate-100 bg-slate-50/60'">
                <a href="{{ route('transactions') }}"
                   class="flex items-center gap-3 group/cta">
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-white shadow-lg shrink-0 transition group-hover/cta:scale-105"
                         style="background: var(--color-primary); box-shadow: 0 4px 12px rgba(34,175,133,0.3);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-black transition"
                           :class="dark ? 'text-[var(--color-dm-text)] group-hover/cta:text-[var(--color-primary-light)]' : 'text-slate-800 group-hover/cta:text-[var(--color-primary)]'">
                            Mulai Transaksi Baru
                        </p>
                        <p class="text-[10px] font-semibold" :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-slate-400'">Catat keuangan hari ini</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
