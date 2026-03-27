<div>
    @if($showModal)
    <div class="modal-backdrop" x-data x-transition>
        <div class="modal-content" @click.outside="$wire.closeModal()">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-[var(--color-dark)]">
                    {{ $accountId ? 'Edit Akun' : 'Tambah Akun' }}
                </h3>
                <button wire:click="closeModal" class="p-1 rounded-lg hover:bg-gray-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit="save" class="space-y-4">
                <div>
                    <label class="form-label">Nama Akun</label>
                    <input type="text" wire:model="name" class="form-input" placeholder="BCA Business">
                    @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="form-label">Tipe</label>
                    <select wire:model="type" class="form-select">
                        <option value="bank">Bank</option>
                        <option value="cash">Kas/Cash</option>
                        <option value="e-wallet">E-Wallet</option>
                    </select>
                    @error('type') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
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
