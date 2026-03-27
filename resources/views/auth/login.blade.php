<x-guest-layout>
    <div class="w-full text-center">
        <h2 class="text-[32px] font-black tracking-tight text-[#1a3a3a] mb-2">Sign In</h2>
        <p class="text-slate-500 font-medium text-sm mb-10">Enter your details to access your dashboard.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="w-full space-y-6">
        @csrf

        <!-- Email/Username -->
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <span class="text-xl font-medium text-slate-400 group-focus-within:text-[#00BFA5] transition-colors duration-200">@</span>
            </div>
            <input id="email" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autofocus 
                   placeholder="Email or Username"
                   class="block w-full pl-12 pr-4 py-4 rounded-xl border border-slate-200 bg-[#f9fafb] text-slate-900 font-semibold focus:ring-2 focus:ring-[#FFC107]/20 focus:border-[#FFC107] transition-all duration-200 placeholder-slate-400 outline-none">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-slate-400 group-focus-within:text-[#00BFA5] transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>
            <input id="password" 
                   type="password" 
                   name="password" 
                   required 
                   placeholder="Password"
                   class="block w-full pl-12 pr-4 py-4 rounded-xl border border-slate-200 bg-[#f9fafb] text-slate-900 font-semibold focus:ring-2 focus:ring-[#FFC107]/20 focus:border-[#FFC107] transition-all duration-200 placeholder-slate-400 outline-none">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end">
            @if (Route::has('password.request'))
                <a class="text-sm font-bold text-slate-600 hover:text-[#1a3a3a] transition-colors" href="{{ route('password.request') }}">
                    Forgot Password?
                </a>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit" 
                    class="w-full py-5 rounded-full bg-[#FFC107] hover:bg-[#ffb300] text-white font-black text-lg shadow-[0_8px_20px_-5px_rgba(255,193,7,0.4)] hover:shadow-[0_12px_25px_-5px_rgba(255,193,7,0.5)] transform hover:-translate-y-1 active:scale-95 transition-all duration-300 uppercase tracking-widest">
                SIGN IN
            </button>
        </div>

    </form>
</x-guest-layout>
