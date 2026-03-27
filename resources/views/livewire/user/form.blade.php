<div>
    @if($isOpen)
    <div class="modal-backdrop" x-data x-transition>
        <div class="modal-content !max-w-md" @click.outside="$wire.close()">
            <div class="flex items-center justify-between mb-4 border-b dark:border-slate-700 pb-4">
                <h3 class="text-lg font-black text-slate-800 dark:text-white tracking-tight flex items-center gap-2">
                    <div class="p-2 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    {{ $userId ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}
                </h3>
                <button wire:click="close" class="p-2 rounded-full text-slate-400 hover:text-slate-600 dark:hover:text-white hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit="save" class="space-y-5">
                <div>
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" wire:model="name" class="form-input" placeholder="Contoh: Budi Santoso">
                    @error('name') <span class="text-xs font-bold text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="form-label">Alamat Email</label>
                    <input type="email" wire:model="email" class="form-input" placeholder="admin@shoeworkshop.com">
                    @error('email') <span class="text-xs font-bold text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="form-label">Peran Pengguna</label>
                    <select wire:model="role" class="form-select">
                        <option value="">Pilih Peran...</option>
                        @foreach($roles as $r)
                            <option value="{{ $r->name }}">{{ $r->name }}</option>
                        @endforeach
                    </select>
                    @error('role') <span class="text-xs font-bold text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="form-label">
                        Pin / Password 
                        @if($userId)
                            <span class="text-xs text-slate-400 font-medium ml-1">(Kosongkan jika tidak ingin diubah)</span>
                        @endif
                    </label>
                    <input type="password" wire:model="password" class="form-input" placeholder="••••••••">
                    @error('password') <span class="text-xs font-bold text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>


                <div class="flex gap-3 pt-4 border-t dark:border-slate-700">
                    <button type="button" wire:click="close" class="btn btn-secondary flex-1">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary flex-1">
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
