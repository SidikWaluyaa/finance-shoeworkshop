<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Financial Health Monitoring System - Shoeworkshop">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body
    class="min-h-screen transition-colors duration-300"
    x-data="{
        sidebarOpen: false,
        sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
        dark: localStorage.getItem('dark') === 'true',
        init() {
            this.$watch('dark', v => {
                localStorage.setItem('dark', v);
                document.documentElement.classList.toggle('dark', v);
            });
            this.$watch('sidebarCollapsed', v => {
                localStorage.setItem('sidebarCollapsed', v);
            });
            document.documentElement.classList.toggle('dark', this.dark);
        }
    }"
    :class="dark ? 'bg-[var(--color-dm-bg)] text-[var(--color-dm-text)]' : 'bg-[var(--color-surface-alt)] text-[var(--color-dark)]'"
>

    {{-- Sidebar Overlay (mobile) --}}
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/50 z-40 md:hidden" style="display:none;"></div>

    {{-- ===== SIDEBAR ===== --}}
    <aside class="fixed top-0 left-0 h-full z-50 flex flex-col transform transition-all duration-300 ease-in-out border-r"
           :class="[
               sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0',
               sidebarCollapsed ? 'md:w-20' : 'md:w-64',
               dark ? 'bg-[#0f172a] border-slate-800' : 'bg-white border-slate-200'
           ]">

        {{-- Sidebar Header --}}
        <div class="relative flex items-center h-20 px-4 border-b flex-shrink-0 transition-all duration-300"
             :class="dark ? 'border-slate-800' : 'border-slate-100'">
            
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[var(--color-primary)] to-emerald-500 flex items-center justify-center shrink-0 shadow-lg shadow-emerald-500/20">
                    <img src="{{ asset('logo.png') }}" alt="Logo" class="w-7 h-7 object-contain brightness-0 invert">
                </div>
                <div x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap">
                    <h1 class="font-black text-sm tracking-tight" :class="dark ? 'text-white' : 'text-slate-900'">FinanceSW</h1>
                    <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest">Enterprise</p>
                </div>
            </div>

            {{-- Toggle Button (Desktop/Tablet) --}}
            <button @click="sidebarCollapsed = !sidebarCollapsed" 
                    class="hidden md:flex absolute -right-4 top-6 w-8 h-8 rounded-xl items-center justify-center transition-all duration-300 z-[60] shadow-xl border group hover:scale-110 active:scale-95 bg-emerald-500 border-emerald-400 text-white"
                    :class="dark ? 'shadow-emerald-900/40' : 'shadow-emerald-500/20'">
                <div class="flex items-center justify-center transition-transform duration-300" :class="sidebarCollapsed ? 'rotate-180' : ''">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 19l-7-7 7-7M20 19l-7-7 7-7"/>
                    </svg>
                </div>
            </button>
        </div>

        {{-- Nav Content --}}
        <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto overflow-x-hidden scrollbar-none custom-scrollbar">
            {{-- Dark Mode Toggle --}}
            <div class="px-2 mb-6 transition-all duration-300">
                <button @click="dark = !dark" 
                        class="w-full flex items-center gap-3 rounded-xl transition-all duration-300"
                        :class="[
                            sidebarCollapsed ? 'px-0 justify-center h-12' : 'px-4 py-3',
                            dark ? 'bg-slate-800/50 text-amber-400 hover:bg-slate-800' : 'bg-slate-50 text-slate-600 hover:bg-slate-100'
                        ]">
                    <div class="shrink-0 flex items-center justify-center w-5 h-5">
                        <template x-if="!dark"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg></template>
                        <template x-if="dark"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg></template>
                    </div>
                    <span x-show="!sidebarCollapsed" class="text-xs font-bold whitespace-nowrap">Mode <span x-text="dark ? 'Terang' : 'Gelap'"></span></span>
                </button>
            </div>

            <p x-show="!sidebarCollapsed" class="px-4 mb-3 text-[10px] uppercase tracking-[0.2em] font-black opacity-40"
               :class="dark ? 'text-white' : 'text-slate-900'">Menu Utama</p>

            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" :class="sidebarCollapsed ? 'justify-center px-0 h-12 rounded-xl' : 'px-4 py-3 rounded-xl gap-3'">
                <div class="shrink-0 w-5 h-5 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                </div>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="text-xs font-bold leading-none">Dashboard</span>
            </a>
            <a href="{{ route('transactions') }}" class="sidebar-link {{ request()->routeIs('transactions') ? 'active' : '' }}" :class="sidebarCollapsed ? 'justify-center px-0 h-12 rounded-xl' : 'px-4 py-3 rounded-xl gap-3'">
                <div class="shrink-0 w-5 h-5 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                </div>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="text-xs font-bold leading-none">Transaksi</span>
            </a>
            <a href="{{ route('invoices') }}" class="sidebar-link {{ request()->routeIs('invoices') ? 'active' : '' }}" :class="sidebarCollapsed ? 'justify-center px-0 h-12 rounded-xl' : 'px-4 py-3 rounded-xl gap-3'">
                <div class="shrink-0 w-5 h-5 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="text-xs font-bold leading-none">Piutang</span>
            </a>
            <a href="{{ route('rabs') }}" class="sidebar-link {{ request()->routeIs('rabs') ? 'active' : '' }}" :class="sidebarCollapsed ? 'justify-center px-0 h-12 rounded-xl' : 'px-4 py-3 rounded-xl gap-3'">
                <div class="shrink-0 w-5 h-5 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="text-xs font-bold leading-none">RAB</span>
            </a>
            <a href="{{ route('payables') }}" class="sidebar-link {{ request()->routeIs('payables') ? 'active' : '' }}" :class="sidebarCollapsed ? 'justify-center px-0 h-12 rounded-xl' : 'px-4 py-3 rounded-xl gap-3'">
                <div class="shrink-0 w-5 h-5 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18M3 14h18M3 18h18"/></svg>
                </div>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="text-xs font-bold leading-none">Utang</span>
            </a>
            <a href="{{ route('expense-locations') }}" class="sidebar-link {{ request()->routeIs('expense-locations') ? 'active' : '' }}" :class="sidebarCollapsed ? 'justify-center px-0 h-12 rounded-xl' : 'px-4 py-3 rounded-xl gap-3'">
                <div class="shrink-0 w-5 h-5 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="text-xs font-bold leading-none">Lokasi</span>
            </a>

            <div class="pt-6 my-4 border-t opacity-10" :class="dark ? 'border-white' : 'border-slate-900'"></div>

            <p x-show="!sidebarCollapsed" class="px-4 mb-3 text-[10px] uppercase tracking-[0.2em] font-black opacity-40"
               :class="dark ? 'text-white' : 'text-slate-900'">Pengaturan</p>
            <a href="{{ route('accounts') }}" class="sidebar-link {{ request()->routeIs('accounts') ? 'active' : '' }}" :class="sidebarCollapsed ? 'justify-center px-0 h-12 rounded-xl' : 'px-4 py-3 rounded-xl gap-3'">
                <div class="shrink-0 w-5 h-5 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="text-xs font-bold leading-none">Akun</span>
            </a>
            <a href="{{ route('categories') }}" class="sidebar-link {{ request()->routeIs('categories') ? 'active' : '' }}" :class="sidebarCollapsed ? 'justify-center px-0 h-12 rounded-xl' : 'px-4 py-3 rounded-xl gap-3'">
                <div class="shrink-0 w-5 h-5 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="text-xs font-bold leading-none">Kategori</span>
            </a>
            <a href="{{ route('users') }}" class="sidebar-link {{ request()->routeIs('users') ? 'active' : '' }}" :class="sidebarCollapsed ? 'justify-center px-0 h-12 rounded-xl' : 'px-4 py-3 rounded-xl gap-3'">
                <div class="shrink-0 w-5 h-5 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="text-xs font-bold leading-none">Pengguna</span>
            </a>
            <a href="{{ route('trash') }}" class="sidebar-link {{ request()->routeIs('trash') ? 'active' : '' }}" :class="sidebarCollapsed ? 'justify-center px-0 h-12 rounded-xl' : 'px-4 py-3 rounded-xl gap-3'">
                <div class="shrink-0 w-5 h-5 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="text-xs font-bold leading-none">Tempat Sampah</span>
            </a>

            @auth
            <div class="pt-6 my-4 border-t opacity-10" :class="dark ? 'border-rose-500' : 'border-rose-300'"></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-link w-full text-left group hover:bg-rose-500/10 hover:text-rose-500 transition-all" :class="sidebarCollapsed ? 'justify-center px-0 h-12 rounded-xl' : 'px-4 py-3 rounded-xl gap-3'">
                    <div class="shrink-0 w-5 h-5 flex items-center justify-center">
                        <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </div>
                    <span x-show="!sidebarCollapsed" x-transition.opacity class="text-xs font-bold leading-none text-rose-500">Keluar</span>
                </button>
            </form>
            @endauth
        </nav>
    </aside>

    {{-- ===== MAIN CONTENT ===== --}}
    <main class="transition-all duration-300 pt-14 md:pt-0 pb-20 md:pb-6 min-h-screen"
          :class="sidebarCollapsed ? 'md:ml-20' : 'md:ml-64'">
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

    {{-- ===== GLOBAL TOAST ===== --}}
    {{-- ===== GLOBAL SWEETALERT2 NOTIFICATIONS ===== --}}
    <script>
        // SweetAlert2 Toast config
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        function showSwalToast(data) {
            const detail = Array.isArray(data) ? data[0] : data;
            const type = detail.type || 'info';
            const message = detail.message || '';

            const iconMap = {
                'success': 'success',
                'error': 'error',
                'warning': 'warning',
                'info': 'info'
            };

            Toast.fire({
                icon: iconMap[type] || 'info',
                title: message,
                background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#1e293b',
            });
        }

        // Listen for Livewire 'alert' and 'notify' events
        document.addEventListener('livewire:init', () => {
            Livewire.on('alert', (data) => showSwalToast(data));
            Livewire.on('notify', (data) => showSwalToast(data));
        });

        // Handle session flash messages on page load
        document.addEventListener('DOMContentLoaded', () => {
            @if(session('success'))
                showSwalToast({ type: 'success', message: '{{ session("success") }}' });
            @endif
            @if(session('error'))
                showSwalToast({ type: 'error', message: '{{ session("error") }}' });
            @endif
            @if(session('warning'))
                showSwalToast({ type: 'warning', message: '{{ session("warning") }}' });
            @endif
        });
    </script>

    @livewireScripts
</body>
</html>
