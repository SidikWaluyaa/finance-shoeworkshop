<div>
    @if($showModal)
    <div class="modal-backdrop" x-data x-transition>
        <div class="modal-content" @click.outside="$wire.closeModal()">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-[var(--color-dark)] dark:text-white">
                    {{ $rabId ? 'Edit RAB' : 'Tambah RAB' }}
                </h3>
                <button wire:click="closeModal" class="p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit="save" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="col-span-1 sm:col-span-2">
                        <label class="form-label">Nama RAB</label>
                        <input type="text" wire:model="name" class="form-input @error('name') form-input-error @enderror" placeholder="Misal: Operasional Q1 2025">
                        @error('name') <span class="error-msg"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" wire:model="start_date" class="form-input">
                        @error('start_date') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" wire:model="end_date" class="form-input">
                        @error('end_date') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="form-label font-bold text-[var(--color-primary)]">Total Anggaran (Otomatis)</label>
                    <div class="form-input bg-gray-50 dark:bg-slate-900/50 font-bold border-dashed border-2 border-emerald-500/20">
                        Rp {{ number_format((float)$total_budget, 0, ',', '.') }}
                    </div>
                </div>

                <div>
                    <label class="form-label">Deskripsi</label>
                    <textarea wire:model="description" class="form-input" rows="1" placeholder="Keterangan singkat..."></textarea>
                </div>

                <div class="border-t dark:border-slate-700 pt-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-bold text-[var(--color-dark)] dark:text-white">Item Anggaran</h4>
                        <button type="button" wire:click="addItem" class="btn btn-secondary btn-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah Baris
                        </button>
                    </div>

                    <div class="space-y-3 max-h-60 overflow-y-auto pr-1">
                        @foreach($items as $index => $item)
                            <div class="flex gap-2 items-start" wire:key="rab-item-{{ $index }}">
                                <div class="flex-1">
                                    <input type="text" wire:model="items.{{ $index }}.name" class="form-input text-sm" placeholder="Nama Item">
                                    @error("items.$index.name") <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div class="w-40">
                                    <input type="number" wire:model.live="items.{{ $index }}.amount" wire:change="updateTotal" class="form-input text-sm" placeholder="Jumlah">
                                    @error("items.$index.amount") <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <button type="button" wire:click="removeItem({{ $index }})" class="p-2 text-red-400 hover:text-red-600 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                    @error('items') <p class="text-xs text-red-500 mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-2 pt-2 border-t mt-4">
                    <button type="submit" class="btn btn-primary flex-1">
                        <span wire:loading.remove wire:target="save">💾 Simpan RAB</span>
                        <span wire:loading wire:target="save">Menyimpan...</span>
                    </button>
                    <button type="button" wire:click="closeModal" class="btn btn-secondary">Batal</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
