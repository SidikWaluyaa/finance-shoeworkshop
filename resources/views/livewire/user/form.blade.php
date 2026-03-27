<div>
    @if($isOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" x-data x-transition>
        <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl shadow-emerald-900/10 overflow-hidden transform transition-all" @click.outside="$wire.close()">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="text-lg font-black text-slate-800 tracking-tight flex items-center gap-2">
                    <div class="p-2 rounded-xl bg-emerald-100 text-emerald-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    {{ $userId ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}
                </h3>
                <button wire:click="close" class="p-2 rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit="save" class="p-6 space-y-5">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                    <input type="text" wire:model="name" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors font-medium text-slate-900 placeholder-slate-400" placeholder="Contoh: Budi Santoso">
                    @error('name') <span class="text-xs font-bold text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Email</label>
                    <input type="email" wire:model="email" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors font-medium text-slate-900 placeholder-slate-400" placeholder="admin@shoeworkshop.com">
                    @error('email') <span class="text-xs font-bold text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Peran Pengguna</label>
                    <select wire:model="role" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors font-medium text-slate-900 appearance-none">
                        <option value="">Pilih Peran...</option>
                        @foreach($roles as $r)
                            <option value="{{ $r->name }}">{{ $r->name }}</option>
                        @endforeach
                    </select>
                    @error('role') <span class="text-xs font-bold text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        Pin / Password 
                        @if($userId)
                            <span class="text-xs text-slate-400 font-medium ml-1">(Kosongkan jika tidak ingin diubah)</span>
                        @endif
                    </label>
                    <input type="password" wire:model="password" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors font-medium text-slate-900 placeholder-slate-400" placeholder="••••••••">
                    @error('password') <span class="text-xs font-bold text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>


                <div class="flex gap-3 pt-4 border-t border-slate-100">
                    <button type="button" wire:click="close" class="px-5 py-3 rounded-xl font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-5 py-3 rounded-xl font-bold bg-emerald-500 text-white hover:bg-emerald-600 active:bg-emerald-700 transition-all transform hover:-translate-y-0.5 shadow-lg shadow-emerald-500/30 flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="save">Simpan Akun</span>
                        <span wire:loading wire:target="save" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Memproses...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
