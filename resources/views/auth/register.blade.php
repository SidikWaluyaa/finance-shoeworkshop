<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-[10px] font-black uppercase tracking-widest mb-2" :class="dark ? 'text-white/60' : 'text-slate-500'">
                Nama Lengkap
            </label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[var(--color-primary)] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <input id="name" 
                       type="text" 
                       name="name" 
                       value="{{ old('name') }}" 
                       required 
                       autofocus 
                       autocomplete="name"
                       class="block w-full pl-10 pr-4 py-3 rounded-2xl border transition-all duration-300 focus:ring-0 text-sm font-bold"
                       :class="dark 
                         ? 'bg-white/5 border-white/10 text-white placeholder-white/20 focus:border-[var(--color-primary)]/50 focus:bg-white/10' 
                         : 'bg-slate-50 border-slate-200 text-slate-900 placeholder-slate-400 focus:border-[var(--color-primary)] focus:bg-white'"
                       placeholder="Nama Anda">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-[10px] font-black uppercase tracking-widest mb-2" :class="dark ? 'text-white/60' : 'text-slate-500'">
                Email Aktif
            </label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[var(--color-primary)] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/></svg>
                </div>
                <input id="email" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autocomplete="username"
                       class="block w-full pl-10 pr-4 py-3 rounded-2xl border transition-all duration-300 focus:ring-0 text-sm font-bold"
                       :class="dark 
                         ? 'bg-white/5 border-white/10 text-white placeholder-white/20 focus:border-[var(--color-primary)]/50 focus:bg-white/10' 
                         : 'bg-slate-50 border-slate-200 text-slate-900 placeholder-slate-400 focus:border-[var(--color-primary)] focus:bg-white'"
                       placeholder="nama@email.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-[10px] font-black uppercase tracking-widest mb-2" :class="dark ? 'text-white/60' : 'text-slate-500'">
                Kata Sandi Baru
            </label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[var(--color-primary)] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <input id="password" 
                       type="password" 
                       name="password" 
                       required 
                       autocomplete="new-password"
                       class="block w-full pl-10 pr-4 py-3 rounded-2xl border transition-all duration-300 focus:ring-0 text-sm font-bold"
                       :class="dark 
                         ? 'bg-white/5 border-white/10 text-white placeholder-white/20 focus:border-[var(--color-primary)]/50 focus:bg-white/10' 
                         : 'bg-slate-50 border-slate-200 text-slate-900 placeholder-slate-400 focus:border-[var(--color-primary)] focus:bg-white'"
                       placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-[10px] font-black uppercase tracking-widest mb-2" :class="dark ? 'text-white/60' : 'text-slate-500'">
                Konfirmasi Sandi
            </label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[var(--color-primary)] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <input id="password_confirmation" 
                       type="password" 
                       name="password_confirmation" 
                       required 
                       autocomplete="new-password"
                       class="block w-full pl-10 pr-4 py-3 rounded-2xl border transition-all duration-300 focus:ring-0 text-sm font-bold"
                       :class="dark 
                         ? 'bg-white/5 border-white/10 text-white placeholder-white/20 focus:border-[var(--color-primary)]/50 focus:bg-white/10' 
                         : 'bg-slate-50 border-slate-200 text-slate-900 placeholder-slate-400 focus:border-[var(--color-primary)] focus:bg-white'"
                       placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-4">
            <button type="submit" 
                    class="w-full py-4 rounded-2xl bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-primary-dark)] text-white font-black text-sm uppercase tracking-[0.2em] shadow-lg shadow-[var(--color-primary)]/20 hover:shadow-xl hover:shadow-[var(--color-primary)]/30 hover:-translate-y-0.5 transition-all duration-300 active:scale-95">
                Daftar Akun Baru
            </button>
        </div>

        <p class="text-center text-[10px] font-bold uppercase tracking-widest opacity-40 hover:opacity-100 transition" :class="dark ? 'text-white' : 'text-slate-600'">
            Sudah terdaftar? <a href="{{ route('login') }}" class="text-[var(--color-primary)] hover:underline">Masuk Disini</a>
        </p>
    </form>
</x-guest-layout>
