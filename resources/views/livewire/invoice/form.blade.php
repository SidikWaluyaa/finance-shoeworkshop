<div>
    @if($showModal)
    <div class="modal-backdrop" x-data x-transition>
        <div class="modal-content" @click.outside="$wire.closeModal()">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-[var(--color-dark)] dark:text-white">
                    {{ $invoiceId ? 'Edit Invoice' : 'Tambah Invoice' }}
                </h3>
                <button wire:click="closeModal" class="p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit="save" class="space-y-4">
                <div>
                    <label class="form-label">Nama Klien</label>
                    <input type="text" wire:model="client_name" class="form-input @error('client_name') form-input-error @enderror" placeholder="PT Contoh">
                    @error('client_name') <span class="error-msg"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Total (Rp)</label>
                        <input type="number" wire:model="total" class="form-input @error('total') form-input-error @enderror" placeholder="0">
                        @error('total') <span class="error-msg"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="form-label">Jatuh Tempo</label>
                        <input type="date" wire:model="due_date" class="form-input @error('due_date') form-input-error @enderror">
                        @error('due_date') <span class="error-msg"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="form-label">Status</label>
                    <select wire:model="status" class="form-select">
                        <option value="unpaid">Belum Bayar</option>
                        <option value="paid">Lunas</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Catatan</label>
                    <textarea wire:model="notes" class="form-input" rows="2" placeholder="Catatan tambahan..."></textarea>
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="submit" class="btn btn-primary flex-1">
                        <span wire:loading.remove wire:target="save">💾 Simpan</span>
                        <span wire:loading wire:target="save">Menyimpan...</span>
                    </button>
                    <button type="button" wire:click="closeModal" class="btn btn-secondary">Batal</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
