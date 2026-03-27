<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Financial Health Monitoring System - Shoeworkshop">
    <title>{{ $title ?? 'Dashboard' }} — FinanceSW</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    {{-- ApexCharts --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.49.0/dist/apexcharts.min.js" defer></script>
</head>
<body
    class="min-h-screen transition-colors duration-300"
    x-data="{
        sidebarOpen: false,
        dark: localStorage.getItem('dark') === 'true',
        init() {
            this.$watch('dark', v => {
                localStorage.setItem('dark', v);
                document.documentElement.classList.toggle('dark', v);
            });
            document.documentElement.classList.toggle('dark', this.dark);
        }
    }"
    :class="dark ? 'bg-[var(--color-dm-bg)] text-[var(--color-dm-text)]' : 'bg-[var(--color-surface-alt)] text-[var(--color-dark)]'"
>

    {{-- ===== MOBILE TOPBAR ===== --}}
    <header class="lg:hidden fixed top-0 left-0 right-0 z-40 h-14 flex items-center justify-between px-4"
            :class="dark ? 'bg-[var(--color-dm-surface)]/90 border-b border-[var(--color-dm-border)]' : 'bg-white/80 border-b border-[var(--color-border)]'"
            style="backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);">
        <button @click="sidebarOpen = !sidebarOpen"
                class="p-2 rounded-xl transition"
                :class="dark ? 'hover:bg-white/10 text-[var(--color-dm-text)]' : 'hover:bg-gray-100 text-gray-700'">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        <div class="flex items-center gap-2">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="w-7 h-7 object-contain">
            <span class="font-bold text-sm">FinanceSW</span>
        </div>
        {{-- Dark mode toggle (mobile) --}}
        <button @click="dark = !dark"
                class="p-2 rounded-xl transition"
                :class="dark ? 'hover:bg-white/10 text-[var(--color-dm-text)]' : 'hover:bg-gray-100 text-gray-600'">
            <template x-if="!dark">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
            </template>
            <template x-if="dark">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </template>
        </button>
    </header>

    {{-- Sidebar Overlay (mobile) --}}
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/50 z-40 lg:hidden" style="display:none;"></div>

    {{-- ===== SIDEBAR ===== --}}
    <aside class="fixed top-0 left-0 h-full z-50 w-64 flex flex-col transform transition-transform duration-200 ease-out border-r"
           :class="[
               sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
               dark ? 'bg-[var(--color-dm-surface)] border-[var(--color-dm-border)]' : 'bg-white border-[var(--color-border)]'
           ]">

        {{-- Logo & Dark Toggle --}}
        <div class="flex items-center justify-between px-5 h-16 border-b flex-shrink-0"
             :class="dark ? 'border-[var(--color-dm-border)]' : 'border-[var(--color-border)]'">
            <div class="flex items-center gap-3">
                {{-- Logo icon --}}
                <img src="{{ asset('logo.png') }}" alt="Logo" class="w-9 h-9 object-contain hover:scale-105 transition-transform duration-300">
                <div>
                    <h1 class="font-black text-sm" :class="dark ? 'text-[var(--color-dm-text)]' : 'text-[var(--color-dark)]'">FinanceSW</h1>
                    <p class="text-[10px]" :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-[var(--color-secondary)]'">Shoeworkshop Finance</p>
                </div>
            </div>
            {{-- Dark mode toggle (desktop) --}}
            <button @click="dark = !dark"
                    class="w-8 h-8 rounded-lg flex items-center justify-center transition"
                    :class="dark ? 'bg-[var(--color-dm-surface2)] text-[var(--color-accent)] hover:bg-white/10' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'">
                <template x-if="!dark">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </template>
                <template x-if="dark">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </template>
            </button>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-5 space-y-0.5 overflow-y-auto">
            <p class="px-3 mb-3 text-[10px] uppercase tracking-widest font-black"
               :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-[var(--color-secondary)]'">Menu Utama</p>

            <a href="{{ route('dashboard') }}"
               class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" @click="sidebarOpen = false">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                Dashboard
            </a>
            <a href="{{ route('transactions') }}"
               class="sidebar-link {{ request()->routeIs('transactions') ? 'active' : '' }}" @click="sidebarOpen = false">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                Transaksi
            </a>
            <a href="{{ route('invoices') }}"
               class="sidebar-link {{ request()->routeIs('invoices') ? 'active' : '' }}" @click="sidebarOpen = false">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Piutang
            </a>
            <a href="{{ route('rabs') }}"
               class="sidebar-link {{ request()->routeIs('rabs') ? 'active' : '' }}" @click="sidebarOpen = false">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                RAB
            </a>
            <a href="{{ route('payables') }}"
               class="sidebar-link {{ request()->routeIs('payables') ? 'active' : '' }}" @click="sidebarOpen = false">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18M3 14h18M3 18h18"/></svg>
                Utang
            </a>
            <a href="{{ route('expense-locations') }}"
               class="sidebar-link {{ request()->routeIs('expense-locations') ? 'active' : '' }}" @click="sidebarOpen = false">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Lokasi
            </a>

            <p class="px-3 mt-5 mb-3 text-[10px] uppercase tracking-widest font-black"
               :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-[var(--color-secondary)]'">Pengaturan</p>
            <a href="{{ route('accounts') }}"
               class="sidebar-link {{ request()->routeIs('accounts') ? 'active' : '' }}" @click="sidebarOpen = false">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Akun
            </a>
            <a href="{{ route('categories') }}"
               class="sidebar-link {{ request()->routeIs('categories') ? 'active' : '' }}" @click="sidebarOpen = false">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                Kategori
            </a>
            @role('Super Admin')
            <a href="{{ route('users') }}" class="sidebar-link group {{ request()->routeIs('users') ? 'active text-rose-500' : '' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Pengguna
            </a>
            @endrole

            @can('access trash')
            <a href="{{ route('trash') }}" class="sidebar-link group {{ request()->routeIs('trash') ? 'active text-rose-500' : '' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Tempat Sampah
            </a>
            @endcan

            @auth
            <p class="px-3 mt-5 mb-3 text-[10px] uppercase tracking-widest font-black text-rose-500">Sesi Akun</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-link w-full text-left group hover:bg-rose-500/10 hover:text-rose-500 transition-all">
                    <svg class="w-5 h-5 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Keluar Sistem
                </button>
            </form>
            @endauth
        </nav>

        {{-- Footer --}}
        <div class="px-5 py-3 border-t flex-shrink-0"
             :class="dark ? 'border-[var(--color-dm-border)] text-[var(--color-dm-muted)]' : 'border-[var(--color-border)] text-[var(--color-secondary)]'">
            <p class="text-[10px]">© 2025 Shoeworkshop</p>
        </div>
    </aside>

    {{-- ===== MAIN CONTENT ===== --}}
    <main class="lg:ml-64 pt-14 lg:pt-0 pb-20 lg:pb-6 min-h-screen">
        <div class="px-4 lg:px-8 py-4 lg:py-6">
            {{ $slot }}
        </div>
    </main>

    {{-- ===== MOBILE BOTTOM NAV ===== --}}
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 z-40 safe-area-bottom border-t"
         :class="dark ? 'bg-[var(--color-dm-surface)]/95 border-[var(--color-dm-border)]' : 'bg-white/90 border-[var(--color-border)]'"
         style="backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);">
        <div class="flex items-center justify-around h-16 px-2">
            <a href="{{ route('dashboard') }}" class="bottom-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z"/></svg>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('transactions') }}" class="bottom-nav-item {{ request()->routeIs('transactions') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                <span>Transaksi</span>
            </a>
            <a href="{{ route('invoices') }}" class="bottom-nav-item {{ request()->routeIs('invoices') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Piutang</span>
            </a>
            <a href="{{ route('rabs') }}" class="bottom-nav-item {{ request()->routeIs('rabs') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                <span>RAB</span>
            </a>
            <a href="{{ route('expense-locations') }}" class="bottom-nav-item {{ request()->routeIs('expense-locations') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Lokasi</span>
            </a>
            <a href="{{ route('accounts') }}" class="bottom-nav-item {{ request()->routeIs('accounts') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Lainnya</span>
            </a>
        </div>
    </nav>

    {{-- ===== SWEETALERT2 NOTIFICATIONS ===== --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            const isDark = () => document.documentElement.classList.contains('dark');

            const showAlert = (data) => {
                Swal.fire({
                    icon: data.type === 'danger' ? 'error' : (data.type === 'warning' ? 'warning' : 'success'),
                    title: data.type === 'success' ? 'Berhasil!' : (data.type === 'error' || data.type === 'danger' ? 'Gagal!' : 'Informasi'),
                    text: data.message,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    background: isDark() ? '#1e293b' : '#ffffff',
                    color: isDark() ? '#f8fafc' : '#0f172a',
                    customClass: {
                        popup: 'rounded-3xl shadow-2xl border ' + (isDark() ? 'border-slate-700' : 'border-slate-100'),
                        title: 'text-2xl font-black ' + (isDark() ? 'text-white' : 'text-slate-800'),
                    }
                });
            };

            Livewire.on('alert', (event) => {
                let data = Array.isArray(event) ? event[0] : event;
                showAlert(data);
            });

            Livewire.on('notify', (event) => {
                let data = Array.isArray(event) ? event[0] : event;
                showAlert(data);
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            const isDark = () => document.documentElement.classList.contains('dark');
            
            @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: isDark() ? '#1e293b' : '#ffffff',
                color: isDark() ? '#f8fafc' : '#0f172a',
                customClass: { popup: 'rounded-3xl shadow-2xl border ' + (isDark() ? 'border-slate-700' : 'border-slate-100') }
            });
            @endif

            @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: isDark() ? '#1e293b' : '#ffffff',
                color: isDark() ? '#f8fafc' : '#0f172a',
                customClass: { popup: 'rounded-3xl shadow-2xl border ' + (isDark() ? 'border-slate-700' : 'border-slate-100') }
            });
            @endif
        });
    </script>

    @livewireScripts
</body>
</html>
