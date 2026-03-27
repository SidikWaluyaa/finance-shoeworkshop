<div>
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data x-transition>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" @click="$wire.closeModal()"></div>

            <div class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden transition-all transform animate-in fade-in zoom-in duration-300">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">
                                {{ $locationId ? 'Edit Lokasi' : 'Tambah Lokasi Baru' }}
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">Lengkapi informasi tempat pengeluaran</p>
                        </div>
                        <button wire:click="closeModal" class="w-10 h-10 flex items-center justify-center rounded-2xl hover:bg-gray-100 transition-colors text-gray-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form wire:submit="save" class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lokasi / Vendor</label>
                            <input type="text" wire:model="name" placeholder="Contoh: Toko Bangunan Jaya, SPBU Pertamina, dll" 
                                class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-gray-300">
                            @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat (Opsional)</label>
                            <input type="text" wire:model="address" placeholder="Jl. Raya No. 123..." 
                                class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-gray-300">
                            @error('address') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Icon / Emoji (Opsional)</label>
                            <input type="text" wire:model="icon" placeholder="Contoh: 🏪, 🍔, ⛽" 
                                class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-gray-300">
                            <p class="text-[10px] text-gray-400 mt-1">Gunakan emoji untuk visualisasi yang lebih menarik</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi (Opsional)</label>
                            <textarea wire:model="description" rows="3" placeholder="Keterangan tambahan..." 
                                class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-gray-300 resize-none"></textarea>
                            @error('description') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex gap-4 pt-4">
                            <button type="button" wire:click="closeModal" 
                                class="flex-1 px-6 py-4 rounded-2xl font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 transition-all">
                                Batal
                            </button>
                            <button type="submit" 
                                class="flex-[2] bg-primary text-white px-6 py-4 rounded-2xl font-bold shadow-lg shadow-primary/20 hover:bg-primary-dark transition-all active:scale-95">
                                <span wire:loading.remove wire:target="save">Simpan Lokasi</span>
                                <span wire:loading wire:target="save">Menyimpan...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
