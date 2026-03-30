<div wire:poll.15s="refreshData">
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

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
        }
    </style>

    {{-- ===== HERO: Health Score + Saldo ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 mb-6">

        {{-- COLUMN 1: RAB REGULER --}}
        <div class="lg:col-span-4 rounded-3xl p-6 text-white relative overflow-hidden shadow-xl"
             style="background: linear-gradient(135deg, #0F1511 0%, #152217 100%);">
            <div class="relative z-10 flex flex-col h-full">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center bg-white/10">
                            <span class="text-sm">📋</span>
                        </div>
                        <h3 class="text-sm font-bold uppercase tracking-widest text-emerald-400">RAB Reguler</h3>
                    </div>
                    <a href="{{ route('rabs') }}" class="text-[10px] font-black uppercase tracking-widest text-white/40 hover:text-emerald-400 transition">Lihat Semua</a>
                </div>

                <div class="space-y-4 flex-1">
                    @forelse(collect($activeRabs['items'])->take(3) as $rab)
                    <div class="group">
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex flex-col min-w-0">
                                <span class="text-[11px] font-bold truncate">{{ $rab['name'] }}</span>
                                <span class="text-[9px] text-emerald-400/80 font-black uppercase tracking-tighter">Budget: {{ $this->formatCurrencyShort($rab['total_budget']) }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-[11px] font-black text-emerald-400">{{ $rab['percent'] }}%</span>
                            </div>
                        </div>
                        <div class="h-1.5 bg-white/5 rounded-full overflow-hidden border border-white/5">
                            <div class="h-full rounded-full transition-all duration-1000 ease-out"
                                 style="width: {{ min($rab['percent'], 100) }}%; background: linear-gradient(90deg, #22AF85, #3DC99D);"></div>
                        </div>
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center py-8 opacity-40">
                        <span class="text-2xl mb-2">📊</span>
                        <p class="text-[10px] font-bold uppercase tracking-widest">Belum ada RAB</p>
                    </div>
                    @endforelse
                </div>

                <div class="mt-4 pt-4 border-t border-white/5 flex items-center justify-between">
                    <div>
                        <p class="text-[9px] font-bold uppercase tracking-widest opacity-40 mb-0.5">Total Budget</p>
                        <p class="text-xs font-black">{{ $this->formatCurrencyShort($activeRabs['total_budget'] ?? 0) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[9px] font-bold uppercase tracking-widest opacity-40 mb-0.5">Tersedia</p>
                        <p class="text-xs font-black text-emerald-400">{{ $this->formatCurrencyShort($activeRabs['total_remaining'] ?? 0) }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- COLUMN 2: DAFTAR UTANG --}}
        <div class="lg:col-span-4 rounded-3xl p-6 text-white relative overflow-hidden shadow-xl"
             style="background: linear-gradient(135deg, #1A160F 0%, #221C15 100%);">
            <div class="relative z-10 flex flex-col h-full">
                <div class="flex items-center justify-between mb-4 shrink-0">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center bg-white/10">
                            <span class="text-sm">💸</span>
                        </div>
                        <h3 class="text-sm font-bold uppercase tracking-widest text-amber-400">Daftar Utang</h3>
                    </div>
                    <a href="{{ route('payables') }}" class="text-[10px] font-black uppercase tracking-widest text-white/40 hover:text-amber-400 transition">Bayar Utang</a>
                </div>

                <div class="space-y-2.5 flex-1 max-h-[310px] overflow-y-auto pr-1.5 custom-scrollbar">
                    @forelse($priorityPayables as $p)
                    <div class="flex items-center justify-between p-2.5 rounded-2xl bg-white/5 border border-white/5 hover:bg-white/10 transition cursor-pointer"
                         wire:click="$dispatch('editPayable', { id: {{ $p->id }} })">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center bg-white/5 text-xs">
                                {{ $p->promise_to_pay_date ? '🤝' : '📅' }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-[11px] font-bold truncate">{{ $p->supplier_name }}</p>
                                <p class="text-[9px] font-black {{ $p->isOverdue() ? 'text-rose-400' : 'opacity-40' }} uppercase tracking-tighter">
                                    {{ $p->promise_to_pay_date ? 'Janji: ' . $p->promise_to_pay_date->format('d M') : 'Tempo: ' . $p->due_date->format('d M') }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-[11px] font-black text-rose-400">{{ $this->formatCurrencyShort($p->remaining_amount) }}</p>
                            @if($p->payment_status === 'partial')
                            <span class="text-[8px] font-black bg-amber-400/20 text-amber-400 px-1 py-0.5 rounded uppercase">Dicicil</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center py-8 opacity-40">
                        <span class="text-2xl mb-2">🙌</span>
                        <p class="text-[10px] font-bold uppercase tracking-widest">Bebas Utang!</p>
                    </div>
                    @endforelse
                </div>

                <div class="mt-4 pt-4 border-t border-white/5 shrink-0">
                    <div class="flex items-center justify-between">
                        <p class="text-[9px] font-bold uppercase tracking-widest opacity-40">Total Kewajiban</p>
                        <p class="text-xs font-black text-rose-400">Rp {{ number_format($summary['total_payables'] ?? 0, 0, ',', '.') }}</p>
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

    {{-- ===== 📅 PULSE HARI INI ===== --}}
    <div class="mb-6">
        {{-- Section Header --}}
        <div class="flex items-center gap-3 mb-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center"
                     style="background: linear-gradient(135deg, var(--color-primary), var(--color-accent));">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-black tracking-tight leading-none" :class="dark ? 'text-[var(--color-dm-text)]' : 'text-slate-900'">
                        Pulse Hari Ini
                    </h3>
                    <p class="text-[10px] font-bold mt-0.5" :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-slate-400'">
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider"
                 :class="dark ? 'bg-emerald-500/10 text-emerald-400' : 'bg-emerald-50 text-emerald-600'">
                <span class="relative flex h-1.5 w-1.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                </span>
                Real-time
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

            {{-- 🧾 INVOICE HARI INI (col-span-6) --}}
            <div class="lg:col-span-6 rounded-2xl border overflow-hidden shadow-sm transition-colors"
                 :class="dark ? 'bg-[var(--color-dm-surface)] border-[var(--color-dm-border)]' : 'bg-white border-[var(--color-border)]'">
                {{-- Top gradient accent --}}
                <div class="h-1" style="background: linear-gradient(90deg, #6366f1, #8b5cf6, #a78bfa);"></div>

                <div class="p-5">
                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-violet-50 text-violet-600 transition-all duration-300 hover:bg-violet-500 hover:text-white"
                                 :class="dark ? 'bg-violet-500/10 text-violet-400' : ''">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-black uppercase tracking-widest" :class="dark ? 'text-[var(--color-dm-text)]' : 'text-slate-800'">Invoice Hari Ini</h4>
                                <p class="text-[10px] font-semibold mt-0.5" :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-slate-400'">Baru & Jatuh Tempo</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if(($todayInvoices['count_overdue'] ?? 0) > 0)
                            <span class="px-2 py-1 rounded-lg text-[10px] font-black bg-rose-50 text-rose-600 animate-pulse"
                                  :class="dark ? 'bg-rose-500/10 text-rose-400' : ''">
                                {{ $todayInvoices['count_overdue'] }} Overdue
                            </span>
                            @endif
                            <a href="{{ route('invoices') }}" class="w-8 h-8 rounded-xl flex items-center justify-center transition-all border"
                               :class="dark ? 'bg-[var(--color-dm-surface2)] border-[var(--color-dm-border)] text-[var(--color-dm-muted)] hover:text-violet-400' : 'bg-slate-50 border-slate-100 text-slate-400 hover:text-violet-600 hover:bg-violet-50'">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                        </div>
                    </div>

                    {{-- Stats Row --}}
                    <div class="grid grid-cols-3 gap-3 mb-4">
                        <div class="rounded-xl p-3 text-center transition border"
                             :class="dark ? 'bg-[var(--color-dm-surface2)] border-[var(--color-dm-border)]' : 'bg-slate-50 border-slate-100'">
                            <p class="text-lg font-black" :class="dark ? 'text-[var(--color-dm-text)]' : 'text-slate-900'">{{ $todayInvoices['count_created'] ?? 0 }}</p>
                            <p class="text-[9px] font-bold uppercase tracking-widest mt-0.5" :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-slate-400'">Baru</p>
                        </div>
                        <div class="rounded-xl p-3 text-center transition border"
                             :class="dark ? 'bg-[var(--color-dm-surface2)] border-[var(--color-dm-border)]' : 'bg-amber-50/60 border-amber-100'">
                            <p class="text-lg font-black" :class="dark ? 'text-amber-400' : 'text-amber-600'">{{ $todayInvoices['count_due'] ?? 0 }}</p>
                            <p class="text-[9px] font-bold uppercase tracking-widest mt-0.5" :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-slate-400'">Jatuh Tempo</p>
                        </div>
                        <div class="rounded-xl p-3 text-center transition border"
                             :class="dark ? 'bg-[var(--color-dm-surface2)] border-[var(--color-dm-border)]' : 'bg-rose-50/60 border-rose-100'">
                            <p class="text-lg font-black" :class="dark ? 'text-rose-400' : 'text-rose-600'">{{ $todayInvoices['count_overdue'] ?? 0 }}</p>
                            <p class="text-[9px] font-bold uppercase tracking-widest mt-0.5" :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-slate-400'">Overdue</p>
                        </div>
                    </div>

                    {{-- Invoice List --}}
                    <div class="space-y-2">
                        @php
                            $allInvoicesToday = collect($todayInvoices['created_today'] ?? [])->merge($todayInvoices['due_today'] ?? [])->unique('id')->take(3);
                        @endphp
                        @forelse($allInvoicesToday as $inv)
                        <div class="flex items-center justify-between p-2.5 rounded-xl border transition-all hover:shadow-sm group"
                             :class="dark ? 'bg-[var(--color-dm-surface2)] border-[var(--color-dm-border)] hover:border-violet-500/30' : 'bg-white border-slate-100 hover:border-violet-200'">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-sm shrink-0
                                    {{ $inv->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-600' : ($inv->payment_status === 'partial' ? 'bg-amber-50 text-amber-600' : ($inv->isOverdue() ? 'bg-rose-50 text-rose-600' : 'bg-slate-50 text-slate-500')) }}"
                                    :class="dark ? 'opacity-90' : ''">
                                    {{ $inv->payment_status === 'paid' ? '✓' : ($inv->payment_status === 'partial' ? '◐' : ($inv->isOverdue() ? '!' : '○')) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[11px] font-bold truncate" :class="dark ? 'text-[var(--color-dm-text)]' : 'text-slate-700'">{{ $inv->client_name }}</p>
                                    <p class="text-[9px] font-semibold" :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-slate-400'">
                                        Due: {{ $inv->due_date->format('d M') }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-[11px] font-black" :class="dark ? 'text-[var(--color-dm-text)]' : 'text-slate-800'">{{ $this->formatCurrencyShort($inv->total) }}</p>
                                <span class="text-[8px] font-black uppercase px-1.5 py-0.5 rounded
                                    {{ $inv->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700' : ($inv->payment_status === 'partial' ? 'bg-amber-50 text-amber-700' : ($inv->isOverdue() ? 'bg-rose-50 text-rose-700' : 'bg-slate-50 text-slate-500')) }}">
                                    {{ $inv->payment_status === 'paid' ? 'LUNAS' : ($inv->payment_status === 'partial' ? 'CICILAN' : ($inv->isOverdue() ? 'TELAT' : 'BELUM')) }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-6 rounded-xl border"
                             :class="dark ? 'bg-[var(--color-dm-surface2)] border-[var(--color-dm-border)]' : 'bg-slate-50/50 border-slate-100'">
                            <div class="w-10 h-10 rounded-2xl mx-auto flex items-center justify-center mb-2"
                                 :class="dark ? 'bg-[var(--color-dm-surface)]' : 'bg-white shadow-sm'">
                                <span class="text-lg opacity-40">🧾</span>
                            </div>
                            <p class="text-[10px] font-black uppercase tracking-widest" :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-slate-400'">Tidak ada invoice hari ini</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- 💰 UANG MASUK HARI INI (col-span-3) --}}
            <div class="lg:col-span-3 rounded-2xl border overflow-hidden shadow-sm transition-all group"
                 :class="dark ? 'bg-[var(--color-dm-surface)] border-[var(--color-dm-border)]' : 'bg-white border-[var(--color-border)]'">
                <div class="h-1" style="background: linear-gradient(90deg, #059669, #10b981, #34d399);"></div>

                <div class="p-5 flex flex-col h-[calc(100%-4px)]">
                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-emerald-50 text-emerald-600 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-lg group-hover:shadow-emerald-200"
                             :class="dark ? 'bg-emerald-500/10 text-emerald-400 group-hover:shadow-emerald-900/20' : ''">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19V5m0 0l-7 7m7-7l7 7"/>
                            </svg>
                        </div>
                        @if(($todaySummary['income_change']['percent'] ?? 0) > 0)
                        <span class="text-[10px] font-black px-2 py-1 rounded-lg
                            {{ ($todaySummary['income_change']['trend'] ?? 'up') === 'up' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}"
                            :class="dark ? '{{ ($todaySummary['income_change']['trend'] ?? 'up') === 'up' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400' }}' : ''">
                            {{ ($todaySummary['income_change']['trend'] ?? 'up') === 'up' ? '↑' : '↓' }} {{ $todaySummary['income_change']['percent'] ?? 0 }}%
                        </span>
                        @endif
                    </div>

                    <p class="text-[10px] uppercase font-black tracking-widest text-[var(--color-muted)] mb-1">Uang Masuk</p>
                    <h5 class="text-xl font-black tracking-tight mb-1"
                        :class="dark ? 'text-emerald-400' : 'text-emerald-600'">
                        Rp {{ number_format($todaySummary['income_today'] ?? 0, 0, ',', '.') }}
                    </h5>
                    <p class="text-[10px] font-semibold mb-4" :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-slate-400'">
                        Kemarin: {{ $this->formatCurrencyShort($todaySummary['income_yesterday'] ?? 0) }}
                    </p>

                    {{-- Sparkline --}}
                    <div class="mt-auto">
                        <p class="text-[9px] font-bold uppercase tracking-widest mb-2" :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-slate-400'">Tren 7 Hari</p>
                        @php
                            $incomeTrend = $todaySummary['income_trend'] ?? [0,0,0,0,0,0,0];
                            $maxIncome = max(max($incomeTrend), 1);
                            $sparkWidth = 100;
                            $sparkHeight = 40;
                        @endphp
                        <svg viewBox="0 0 {{ $sparkWidth }} {{ $sparkHeight }}" class="w-full h-10 overflow-visible" preserveAspectRatio="none">
                            {{-- Gradient fill --}}
                            <defs>
                                <linearGradient id="incomeGradFill" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#10b981" stop-opacity="0.3"/>
                                    <stop offset="100%" stop-color="#10b981" stop-opacity="0.02"/>
                                </linearGradient>
                            </defs>
                            @php
                                $points = [];
                                foreach ($incomeTrend as $i => $val) {
                                    $x = ($i / 6) * $sparkWidth;
                                    $y = $sparkHeight - (($val / $maxIncome) * ($sparkHeight - 4)) - 2;
                                    $points[] = "$x,$y";
                                }
                                $polyline = implode(' ', $points);
                                $firstX = 0;
                                $lastX = $sparkWidth;
                                $fillPoints = "0,$sparkHeight " . $polyline . " $sparkWidth,$sparkHeight";
                            @endphp
                            <polygon points="{{ $fillPoints }}" fill="url(#incomeGradFill)"/>
                            <polyline points="{{ $polyline }}" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            {{-- Active dot on last point --}}
                            @php $lastPoint = end($points); [$lx, $ly] = explode(',', $lastPoint); @endphp
                            <circle cx="{{ $lx }}" cy="{{ $ly }}" r="3" fill="#10b981" stroke="white" stroke-width="1.5"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- 💸 UANG KELUAR HARI INI (col-span-3) --}}
            <div class="lg:col-span-3 rounded-2xl border overflow-hidden shadow-sm transition-all group"
                 :class="dark ? 'bg-[var(--color-dm-surface)] border-[var(--color-dm-border)]' : 'bg-white border-[var(--color-border)]'">
                <div class="h-1" style="background: linear-gradient(90deg, #e11d48, #f43f5e, #fb7185);"></div>

                <div class="p-5 flex flex-col h-[calc(100%-4px)]">
                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-rose-50 text-rose-600 transition-all duration-300 group-hover:bg-rose-500 group-hover:text-white group-hover:shadow-lg group-hover:shadow-rose-200"
                             :class="dark ? 'bg-rose-500/10 text-rose-400 group-hover:shadow-rose-900/20' : ''">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 5v14m0 0l-7-7m7 7l7-7"/>
                            </svg>
                        </div>
                        @if(($todaySummary['expense_change']['percent'] ?? 0) > 0)
                        <span class="text-[10px] font-black px-2 py-1 rounded-lg
                            {{ ($todaySummary['expense_change']['trend'] ?? 'up') === 'up' ? 'bg-rose-50 text-rose-700' : 'bg-emerald-50 text-emerald-700' }}"
                            :class="dark ? '{{ ($todaySummary['expense_change']['trend'] ?? 'up') === 'up' ? 'bg-rose-500/10 text-rose-400' : 'bg-emerald-500/10 text-emerald-400' }}' : ''">
                            {{ ($todaySummary['expense_change']['trend'] ?? 'up') === 'up' ? '↑' : '↓' }} {{ $todaySummary['expense_change']['percent'] ?? 0 }}%
                        </span>
                        @endif
                    </div>

                    <p class="text-[10px] uppercase font-black tracking-widest text-[var(--color-muted)] mb-1">Uang Keluar</p>
                    <h5 class="text-xl font-black tracking-tight mb-1"
                        :class="dark ? 'text-rose-400' : 'text-rose-600'">
                        Rp {{ number_format($todaySummary['expense_today'] ?? 0, 0, ',', '.') }}
                    </h5>
                    <p class="text-[10px] font-semibold mb-4" :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-slate-400'">
                        Kemarin: {{ $this->formatCurrencyShort($todaySummary['expense_yesterday'] ?? 0) }}
                    </p>

                    {{-- Top Expense Categories --}}
                    <div class="mt-auto">
                        <p class="text-[9px] font-bold uppercase tracking-widest mb-2" :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-slate-400'">Top Kategori</p>
                        @php
                            $topCats = $todaySummary['top_expense_categories'] ?? [];
                            $maxCatTotal = count($topCats) > 0 ? max(array_column($topCats, 'total')) : 1;
                            $catColors = ['#f43f5e', '#fb923c', '#a78bfa'];
                        @endphp
                        @if(count($topCats) > 0)
                        <div class="space-y-2">
                            @foreach($topCats as $idx => $cat)
                            <div>
                                <div class="flex items-center justify-between mb-0.5">
                                    <span class="text-[10px] font-bold truncate max-w-[90px]" :class="dark ? 'text-[var(--color-dm-text)]' : 'text-slate-600'">{{ $cat['name'] }}</span>
                                    <span class="text-[10px] font-black" :class="dark ? 'text-[var(--color-dm-text)]' : 'text-slate-700'">{{ $this->formatCurrencyShort($cat['total']) }}</span>
                                </div>
                                <div class="h-1.5 rounded-full overflow-hidden" :class="dark ? 'bg-white/5' : 'bg-slate-100'">
                                    <div class="h-full rounded-full transition-all duration-700"
                                         style="width: {{ ($cat['total'] / $maxCatTotal) * 100 }}%; background: {{ $catColors[$idx] ?? '#94a3b8' }};"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-3 rounded-xl" :class="dark ? 'bg-[var(--color-dm-surface2)]' : 'bg-slate-50'">
                            <p class="text-[10px] font-bold" :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-slate-400'">Belum ada pengeluaran</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== 📊 RAB AKTIF PROGRESS TRACKER ===== --}}
    @if(($activeRabs['count'] ?? 0) > 0)
    <div class="mb-6 rounded-2xl border overflow-hidden shadow-sm transition-colors"
         :class="dark ? 'bg-[var(--color-dm-surface)] border-[var(--color-dm-border)]' : 'bg-white border-[var(--color-border)]'">
        {{-- Top accent --}}
        <div class="h-1" style="background: linear-gradient(90deg, #22AF85, #FFC232, #22AF85);"></div>

        <div class="p-5 md:p-6">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-300"
                         style="background: linear-gradient(135deg, rgba(34,175,133,0.1), rgba(255,194,50,0.1));">
                        <svg class="w-5 h-5" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-black text-sm tracking-tight leading-none" :class="dark ? 'text-[var(--color-dm-text)]' : 'text-slate-900'">RAB Aktif</h3>
                        <p class="text-[10px] font-bold uppercase tracking-widest mt-0.5" :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-slate-400'">
                            {{ $activeRabs['count'] }} Anggaran • Terpakai {{ number_format($activeRabs['overall_percent'] ?? 0, 1) }}%
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    {{-- Overall mini-stats --}}
                    <div class="hidden md:flex items-center gap-4 mr-2">
                        <div class="text-right">
                            <p class="text-[9px] font-bold uppercase tracking-widest" :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-slate-400'">Total Budget</p>
                            <p class="text-sm font-black" :class="dark ? 'text-[var(--color-dm-text)]' : 'text-slate-800'">{{ $this->formatCurrencyShort($activeRabs['total_budget'] ?? 0) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] font-bold uppercase tracking-widest" :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-slate-400'">Sisa</p>
                            <p class="text-sm font-black text-emerald-600">{{ $this->formatCurrencyShort($activeRabs['total_remaining'] ?? 0) }}</p>
                        </div>
                    </div>
                    <a href="{{ route('rabs') }}" class="w-8 h-8 rounded-xl flex items-center justify-center transition-all border"
                       :class="dark ? 'bg-[var(--color-dm-surface2)] border-[var(--color-dm-border)] text-[var(--color-dm-muted)] hover:text-[var(--color-primary)]' : 'bg-slate-50 border-slate-100 text-slate-400 hover:text-[var(--color-primary)] hover:bg-[var(--color-primary-muted)]'">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>
            </div>

            {{-- RAB Items --}}
            <div class="space-y-3">
                @foreach(($activeRabs['items'] ?? []) as $rab)
                @php
                    $barColor = $rab['status'] === 'danger' ? '#ef4444' : ($rab['status'] === 'warning' ? '#f59e0b' : '#22AF85');
                    $barBg = $rab['status'] === 'danger' ? 'bg-rose-50' : ($rab['status'] === 'warning' ? 'bg-amber-50' : 'bg-emerald-50');
                    $textColor = $rab['status'] === 'danger' ? 'text-rose-600' : ($rab['status'] === 'warning' ? 'text-amber-600' : 'text-emerald-600');
                @endphp
                <div class="rounded-xl p-4 border transition-all hover:shadow-sm group/rab"
                     :class="dark ? 'bg-[var(--color-dm-surface2)] border-[var(--color-dm-border)] hover:border-[var(--color-primary)]/30' : 'bg-slate-50/50 border-slate-100 hover:border-slate-200'">
                    <div class="flex items-center justify-between mb-2.5">
                        <div class="flex items-center gap-2 min-w-0">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 {{ $barBg }} transition-colors"
                                 :class="dark ? 'bg-white/5' : ''">
                                <span class="text-xs">📋</span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[11px] font-bold truncate" :class="dark ? 'text-[var(--color-dm-text)]' : 'text-slate-700'">{{ $rab['name'] }}</p>
                                <p class="text-[9px] font-semibold" :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-slate-400'">
                                    {{ $rab['start_date'] }}{{ $rab['end_date'] ? ' — ' . $rab['end_date'] : '' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <div class="text-right hidden sm:block">
                                <p class="text-[10px] font-bold" :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-slate-500'">
                                    {{ $this->formatCurrencyShort($rab['used_budget']) }} / {{ $this->formatCurrencyShort($rab['total_budget']) }}
                                </p>
                            </div>
                            <span class="text-[11px] font-black px-2 py-1 rounded-lg {{ $textColor }}
                                {{ $rab['status'] === 'danger' ? 'bg-rose-50' : ($rab['status'] === 'warning' ? 'bg-amber-50' : 'bg-emerald-50') }}"
                                :class="dark ? 'opacity-90' : ''">
                                {{ number_format($rab['percent'], 1) }}%
                            </span>
                        </div>
                    </div>
                    {{-- Progress bar --}}
                    <div class="h-2 rounded-full overflow-hidden" :class="dark ? 'bg-white/5' : 'bg-slate-200/60'">
                        <div class="h-full rounded-full transition-all duration-1000 ease-out relative"
                             style="width: {{ min($rab['percent'], 100) }}%; background: {{ $barColor }};">
                            @if($rab['status'] === 'danger')
                            <div class="absolute inset-0 rounded-full animate-pulse" style="background: {{ $barColor }}; opacity: 0.5;"></div>
                            @endif
                        </div>
                    </div>
                    @if($rab['percent'] >= 90)
                    <p class="text-[9px] font-black mt-1.5 flex items-center gap-1 uppercase tracking-tighter">
                        @if($rab['percent'] > 100)
                            <span class="text-rose-600 animate-pulse">⚠️ Melebihi Budget (Sisa: -Rp {{ number_format(abs($rab['used_budget'] - $rab['total_budget']), 0, ',', '.') }})</span>
                        @elseif($rab['percent'] == 100)
                            <span class="text-emerald-500">✓ Sudah Terbayar (Lunas)</span>
                        @else
                            <span class="text-amber-500">⚡ Hampir Limit</span>
                        @endif
                    </p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
    {{-- Empty RAB State --}}
    <div class="mb-6 rounded-2xl border p-5 md:p-6 shadow-sm transition-colors"
         :class="dark ? 'bg-[var(--color-dm-surface)] border-[var(--color-dm-border)]' : 'bg-white border-[var(--color-border)]'">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                     :class="dark ? 'bg-[var(--color-dm-surface2)]' : 'bg-slate-50'">
                    <span class="text-lg opacity-60">📊</span>
                </div>
                <div>
                    <h3 class="text-sm font-black" :class="dark ? 'text-[var(--color-dm-text)]' : 'text-slate-700'">Belum Ada RAB Aktif</h3>
                    <p class="text-[10px] font-semibold" :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-slate-400'">Buat rencana anggaran untuk monitoring budget</p>
                </div>
            </div>
            <a href="{{ route('rabs') }}"
               class="px-4 py-2 rounded-xl text-[11px] font-bold transition-all shadow-sm"
               style="background: var(--color-primary); color: white; box-shadow: 0 4px 12px rgba(34,175,133,0.2);">
                + Buat RAB
            </a>
        </div>
    </div>
    @endif

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
