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
    <aside class="fixed top-0 left-0 h-full z-50 flex flex-col transform transition-all duration-300 ease-in-out border-r"
           :class="[
               sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
               sidebarCollapsed ? 'lg:w-20' : 'lg:w-64',
               dark ? 'bg-[var(--color-dm-surface)] border-[var(--color-dm-border)]' : 'bg-white border-[var(--color-border)]'
           ]">

        {{-- Logo & Collapser --}}
        <div class="flex items-center justify-between px-4 h-16 border-b flex-shrink-0 transition-all duration-300"
             :class="dark ? 'border-[var(--color-dm-border)]' : 'border-[var(--color-border)]'">
            <div class="flex items-center gap-3 overflow-hidden">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="w-9 h-9 shrink-0 object-contain hover:scale-105 transition-transform duration-300">
                <div x-show="!sidebarCollapsed" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="whitespace-nowrap">
                    <h1 class="font-black text-sm" :class="dark ? 'text-[var(--color-dm-text)]' : 'text-[var(--color-dark)]'">FinanceSW</h1>
                    <p class="text-[10px]" :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-[var(--color-secondary)]'">Shoeworkshop</p>
                </div>
            </div>
            {{-- Toggle Button --}}
            <button @click="sidebarCollapsed = !sidebarCollapsed" 
                    class="hidden lg:flex w-7 h-7 rounded-lg items-center justify-center transition border"
                    :class="dark ? 'bg-[var(--color-dm-surface2)] border-[var(--color-dm-border)] text-slate-400 hover:text-[var(--color-accent)]' : 'bg-slate-50 border-[var(--color-border)] text-slate-400 hover:text-[var(--color-primary)]'">
                <svg class="w-4 h-4 transition-transform duration-300" :class="sidebarCollapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
        </div>        {{-- Nav --}}
        <nav class="flex-1 px-3 py-5 space-y-0.5 overflow-y-auto overflow-x-hidden scrollbar-thin">
            <div class="flex items-center justify-between mb-3 px-3">
                <p x-show="!sidebarCollapsed" class="text-[10px] uppercase tracking-widest font-black"
                   :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-[var(--color-secondary)]'">Menu Utama</p>
                {{-- Dark mode toggle moved inside nav when collapsed (optional) or kept at top --}}
                <button @click="dark = !dark"
                        class="w-6 h-6 rounded-lg flex items-center justify-center transition shrink-0"
                        :class="dark ? 'bg-[var(--color-dm-surface2)] text-[var(--color-accent)]' : 'bg-slate-100 text-slate-500'">
                    <template x-if="!dark"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg></template>
                    <template x-if="dark"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg></template>
                </button>
            </div>

            <a href="{{ route('dashboard') }}"
               class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" @click="sidebarOpen = false" :class="sidebarCollapsed ? 'justify-center px-0 mx-1' : ''">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                <span x-show="!sidebarCollapsed" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Dashboard</span>
            </a>
            <a href="{{ route('transactions') }}"
               class="sidebar-link {{ request()->routeIs('transactions') ? 'active' : '' }}" @click="sidebarOpen = false" :class="sidebarCollapsed ? 'justify-center px-0 mx-1' : ''">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                <span x-show="!sidebarCollapsed" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Transaksi</span>
            </a>
            <a href="{{ route('invoices') }}"
               class="sidebar-link {{ request()->routeIs('invoices') ? 'active' : '' }}" @click="sidebarOpen = false" :class="sidebarCollapsed ? 'justify-center px-0 mx-1' : ''">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span x-show="!sidebarCollapsed" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Piutang</span>
            </a>
            <a href="{{ route('rabs') }}"
               class="sidebar-link {{ request()->routeIs('rabs') ? 'active' : '' }}" @click="sidebarOpen = false" :class="sidebarCollapsed ? 'justify-center px-0 mx-1' : ''">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                <span x-show="!sidebarCollapsed" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">RAB</span>
            </a>
            <a href="{{ route('payables') }}"
               class="sidebar-link {{ request()->routeIs('payables') ? 'active' : '' }}" @click="sidebarOpen = false" :class="sidebarCollapsed ? 'justify-center px-0 mx-1' : ''">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18M3 14h18M3 18h18"/></svg>
                <span x-show="!sidebarCollapsed" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Utang</span>
            </a>
            <a href="{{ route('expense-locations') }}"
               class="sidebar-link {{ request()->routeIs('expense-locations') ? 'active' : '' }}" @click="sidebarOpen = false" :class="sidebarCollapsed ? 'justify-center px-0 mx-1' : ''">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span x-show="!sidebarCollapsed" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Lokasi</span>
            </a>

            <p x-show="!sidebarCollapsed" class="px-3 mt-5 mb-3 text-[10px] uppercase tracking-widest font-black"
               :class="dark ? 'text-[var(--color-dm-muted)]' : 'text-[var(--color-secondary)]'">Pengaturan</p>
            <a href="{{ route('accounts') }}"
               class="sidebar-link {{ request()->routeIs('accounts') ? 'active' : '' }}" @click="sidebarOpen = false" :class="sidebarCollapsed ? 'justify-center px-0 mx-1' : ''">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                <span x-show="!sidebarCollapsed" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Akun</span>
            </a>
            <a href="{{ route('categories') }}"
               class="sidebar-link {{ request()->routeIs('categories') ? 'active' : '' }}" @click="sidebarOpen = false" :class="sidebarCollapsed ? 'justify-center px-0 mx-1' : ''">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                <span x-show="!sidebarCollapsed" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Kategori</span>
            </a>
            <a href="{{ route('users') }}"
               class="sidebar-link {{ request()->routeIs('users') ? 'active' : '' }}" @click="sidebarOpen = false" :class="sidebarCollapsed ? 'justify-center px-0 mx-1' : ''">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <span x-show="!sidebarCollapsed" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Pengguna</span>
            </a>
            <a href="{{ route('trash') }}" class="sidebar-link {{ request()->routeIs('trash') ? 'active' : '' }}" :class="sidebarCollapsed ? 'justify-center px-0 mx-1' : ''">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                <span x-show="!sidebarCollapsed" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Tempat Sampah</span>
            </a>

            @auth
            <p x-show="!sidebarCollapsed" class="px-3 mt-5 mb-3 text-[10px] uppercase tracking-widest font-black text-rose-500">Sesi Akun</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-link w-full text-left group hover:bg-rose-500/10 hover:text-rose-500 transition-all" :class="sidebarCollapsed ? 'justify-center px-0 mx-1' : ''">
                    <svg class="w-5 h-5 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span x-show="!sidebarCollapsed" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Keluar Sistem</span>
                </button>
            </form>
            @endauth
        </nav>

        {{-- Footer --}}
        <div class="px-4 py-3 border-t flex-shrink-0 transition-all duration-300"
             :class="dark ? 'border-[var(--color-dm-border)] text-[var(--color-dm-muted)]' : 'border-[var(--color-border)] text-[var(--color-secondary)]'">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-8 h-8 rounded-full bg-[var(--color-primary)]/20 flex items-center justify-center text-[var(--color-primary)] font-bold text-xs shrink-0">
                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                </div>
                <div x-show="!sidebarCollapsed" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="whitespace-nowrap">
                    <p class="text-[10px] font-bold leading-none" :class="dark ? 'text-[var(--color-dm-text)]' : 'text-slate-900'">{{ Auth::user()->name ?? 'Guest User' }}</p>
                    <p class="text-[8px] mt-0.5 opacity-60">{{ Auth::user()->email ?? 'guest@example.com' }}</p>
                </div>
            </div>
            <p x-show="!sidebarCollapsed" class="text-[8px] mt-3 opacity-40">© 2026 Shoeworkshop Finance</p>
        </div>
    </aside>

    {{-- ===== MAIN CONTENT ===== --}}
    <main class="transition-all duration-300 pt-14 lg:pt-0 pb-20 lg:pb-6 min-h-screen"
          :class="sidebarCollapsed ? 'lg:ml-20' : 'lg:ml-64'">
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
    <div x-data="{
            notifications: [],
            add(n) {
                const id = Date.now();
                this.notifications.push({ id, type: n.type, message: n.message, show: true });
                setTimeout(() => {
                    this.close(id);
                }, 4500);
            },
            close(id) {
                const i = this.notifications.findIndex(x => x.id === id);
                if (i !== -1) {
                    this.notifications[i].show = false;
                    setTimeout(() => {
                        this.notifications = this.notifications.filter(x => x.id !== id);
                    }, 400);
                }
            },
            init() {
                @if(session('success')) setTimeout(() => this.add({ type: 'success', message: '{{ session('success') }}' }), 100); @endif
                @if(session('error')) setTimeout(() => this.add({ type: 'error', message: '{{ session('error') }}' }), 100); @endif
                @if(session('warning')) setTimeout(() => this.add({ type: 'warning', message: '{{ session('warning') }}' }), 100); @endif
            }
         }"
         @alert.window="add(Array.isArray($event.detail) ? $event.detail[0] : $event.detail)"
         @notify.window="add(Array.isArray($event.detail) ? $event.detail[0] : $event.detail)"
         class="fixed top-6 right-6 z-[100] flex flex-col gap-3 w-80 pointer-events-none">
        <template x-for="n in notifications" :key="n.id">
            <div x-show="n.show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="translate-x-full opacity-0 scale-90"
                 x-transition:enter-end="translate-x-0 opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-90"
                 class="px-5 py-4 rounded-2xl shadow-2xl flex items-center gap-4 pointer-events-auto border animate-spring"
                 :class="{
                    'toast-success': n.type === 'success',
                    'toast-error': n.type === 'error',
                    'toast-warning': n.type === 'warning',
                    'glass text-blue-800 border-blue-200/50': n.type === 'info'
                 }">
                
                {{-- Icon --}}
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 bg-white/20 shadow-inner">
                    <template x-if="n.type === 'success'"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg></template>
                    <template x-if="n.type === 'error'"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg></template>
                    <template x-if="n.type === 'warning'"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg></template>
                </div>

                <div class="flex-1">
                    <p class="text-[10px] uppercase font-black tracking-widest opacity-60 mb-0.5" x-text="n.type"></p>
                    <p class="text-sm font-bold leading-tight" x-text="n.message"></p>
                </div>

                <button @click="close(n.id)" class="opacity-40 hover:opacity-100 transition shrink-0 p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </template>
    </div>

    @livewireScripts
</body>
</html>
