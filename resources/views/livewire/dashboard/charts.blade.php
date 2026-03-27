<div>
    <div id="chart-data" data-monthly='@json($monthlyData)' data-b2b='@json($b2bB2cData)' class="hidden"></div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Cashflow Chart --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-black text-sm tracking-tight"
                    :class="dark ? 'text-[var(--color-dm-text)]' : 'text-slate-900'">Arus Kas Bulanan</h3>
                <div class="flex items-center gap-3">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full" style="background:#22AF85"></span>
                        <span class="text-[10px] font-bold uppercase tracking-wider" :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-slate-400'">Masuk</span>
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full" style="background:#FFC232"></span>
                        <span class="text-[10px] font-bold uppercase tracking-wider" :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-slate-400'">Keluar</span>
                    </span>
                </div>
            </div>
            <div id="cashflow-chart" wire:ignore style="height: 280px; width: 100%;"></div>
        </div>

        {{-- Donut Chart --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-black text-sm tracking-tight"
                    :class="dark ? 'text-[var(--color-dm-text)]' : 'text-slate-900'">Segmentasi Pendapatan</h3>
                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider border"
                      :class="dark ? 'bg-[var(--color-dm-surface2)] border-[var(--color-dm-border)] text-[var(--color-dm-muted)]' : 'bg-slate-50 border-slate-100 text-slate-500'">
                    Pangsa Pasar
                </span>
            </div>
            <div id="b2b-chart" wire:ignore style="height: 280px; width: 100%;"></div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:navigated', initDashCharts);
    document.addEventListener('DOMContentLoaded', initDashCharts);

    // Reactive Refresh when data changes
    document.addEventListener('livewire:init', () => {
        Livewire.on('dataUpdated', () => {
            // Give a tiny delay for Livewire to finish DOM sync if needed
            setTimeout(initDashCharts, 50);
        });
    });

    function initDashCharts() {
        if (typeof ApexCharts === 'undefined') {
            setTimeout(initDashCharts, 200);
            return;
        }

        const dataEl = document.getElementById('chart-data');
        if (!dataEl) return;

        const monthlyData = JSON.parse(dataEl.getAttribute('data-monthly')) || { income: [], expense: [], labels: [] };
        const b2bData     = JSON.parse(dataEl.getAttribute('data-b2b')) || { data: [0,0], labels: ['B2B', 'B2C'] };
        const isDark      = document.documentElement.classList.contains('dark');

        const p = {
            green  : '#22AF85',
            yellow : '#FFC232',
            danger : '#EF4444',
            textColor : isDark ? '#7A9A87' : '#6B7280',
            bgColor   : isDark ? '#1A2119' : '#ffffff',
            gridColor : isDark ? '#2D3E34' : '#E4EAE8',
            darkText  : isDark ? '#E8F0EC' : '#111827',
        };

        // ——— Cashflow Area Chart ———
        const cashflowEl = document.getElementById('cashflow-chart');
        if (cashflowEl) {
            cashflowEl.innerHTML = '';
            new ApexCharts(cashflowEl, {
                chart: {
                    type: 'area',
                    height: 280,
                    background: 'transparent',
                    toolbar: { show: false },
                    fontFamily: 'Inter, sans-serif',
                    zoom: { enabled: false },
                    animations: { easing: 'easeinout', speed: 800 }
                },
                theme: { mode: isDark ? 'dark' : 'light' },
                series: [
                    { name: 'Pemasukan',   data: monthlyData.income   || [] },
                    { name: 'Pengeluaran', data: monthlyData.expense   || [] },
                ],
                colors : [p.green, p.yellow],
                stroke : { curve: 'smooth', width: 3 },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom   : 0.35,
                        opacityTo     : 0.02,
                        stops         : [10, 100]
                    }
                },
                xaxis: {
                    categories: monthlyData.labels || [],
                    axisBorder: { show: false },
                    axisTicks : { show: false },
                    labels    : { style: { colors: p.textColor, fontWeight: 600, fontSize: '10px' } }
                },
                yaxis: {
                    labels: {
                        style    : { colors: p.textColor, fontWeight: 600, fontSize: '10px' },
                        formatter: (v) => (v / 1000000).toFixed(1) + 'jt'
                    }
                },
                dataLabels : { enabled: false },
                legend     : { show: false },
                grid: {
                    borderColor   : p.gridColor,
                    strokeDashArray: 4,
                    padding       : { left: 4, right: 4 }
                },
                tooltip: {
                    theme: isDark ? 'dark' : 'light',
                    y    : { formatter: (v) => 'Rp ' + new Intl.NumberFormat('id-ID').format(v) }
                }
            }).render();
        }

        // ——— Donut Chart ———
        const b2bEl = document.getElementById('b2b-chart');
        if (b2bEl) {
            b2bEl.innerHTML = '';
            new ApexCharts(b2bEl, {
                chart: {
                    type      : 'donut',
                    height    : 280,
                    background: 'transparent',
                    fontFamily: 'Inter, sans-serif',
                },
                theme: { mode: isDark ? 'dark' : 'light' },
                series : b2bData.data   || [0, 0],
                labels : b2bData.labels || ['B2B', 'B2C'],
                colors : [p.green, p.yellow],
                stroke : { width: 0 },
                plotOptions: {
                    pie: {
                        donut: {
                            size  : '78%',
                            labels: {
                                show : true,
                                name : { show: true, fontSize: '11px', fontWeight: 700, color: p.textColor, offsetY: -8 },
                                value: {
                                    show: true, fontSize: '18px', fontWeight: 900,
                                    color: p.darkText, offsetY: 8,
                                    formatter: (v) => 'Rp' + (v / 1000000).toFixed(1) + 'jt'
                                },
                                total: {
                                    show: true, label: 'Total Pendapatan', color: p.textColor,
                                    fontSize: '11px', fontWeight: 700,
                                    formatter: (w) => {
                                        const t = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                        return 'Rp' + (t / 1000000).toFixed(1) + 'jt';
                                    }
                                }
                            }
                        }
                    }
                },
                legend     : { position: 'bottom', fontWeight: 700, fontSize: '11px', markers: { radius: 8 }, labels: { colors: p.textColor } },
                dataLabels : { enabled: false },
                tooltip    : { theme: isDark ? 'dark' : 'light', y: { formatter: (v) => 'Rp ' + new Intl.NumberFormat('id-ID').format(v) } }
            }).render();
        }
    }
</script>
