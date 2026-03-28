<div>
    @if($showModal)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 bg-slate-900/60 backdrop-blur-sm overflow-y-auto" x-data x-transition>
        <div class="relative w-full max-w-4xl bg-white dark:bg-slate-800 rounded-3xl shadow-2xl shadow-emerald-900/10 dark:shadow-black/50 border border-slate-100 dark:border-slate-700/50 p-6 sm:p-8 m-auto transform transition-all" @click.outside="if (!$event.target.closest('.default-select2') && !$event.target.closest('.flatpickr-calendar')) $wire.closeModal()">
            <div class="flex items-center justify-between mb-4 sm:mb-6">
                <h3 class="text-xl font-black text-[var(--color-dark)] dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6 text-[#22AF85]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    {{ $transactionId ? 'Edit Transaksi' : 'Tambah Transaksi' }}
                </h3>
                <button wire:click="closeModal" class="p-2 rounded-xl hover:bg-rose-50 dark:hover:bg-rose-500/10 hover:text-rose-500 transition-colors text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit="save" class="space-y-4 sm:space-y-5">
                <!-- ROW 1: Tipe, Source, Akun -->
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div class="sm:col-span-1">
                        <label class="form-label">Tipe</label>
                        <select wire:model.live="type" class="form-select @error('type') form-input-error @enderror">
                            <option value="income">Pemasukan</option>
                            <option value="expense">Pengeluaran</option>
                        </select>
                        @error('type') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                    <div class="sm:col-span-1">
                        <label class="form-label">Source</label>
                        <select wire:model="source_type" class="form-select">
                            <option value="B2C">B2C</option>
                            <option value="B2B">B2B</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="form-label">Akun</label>
                        <select wire:model="account_id" class="form-select @error('account_id') form-input-error @enderror">
                            <option value="0">Pilih Akun</option>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->name }} ({{ $acc->type }})</option>
                            @endforeach
                        </select>
                        @error('account_id') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- ROW 2: Jumlah, Tanggal, Kategori, RAB -->
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div class="sm:col-span-1">
                        <label class="form-label">Jumlah (Rp)</label>
                        <input type="number" wire:model="amount" class="form-input @error('amount') form-input-error @enderror" placeholder="0">
                        @error('amount') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                    <div class="sm:col-span-1">
                        <label class="form-label">Tanggal</label>
                        <input type="date" wire:model="date" class="form-input @error('date') form-input-error @enderror">
                        @error('date') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                    <div class="sm:col-span-1">
                        <label class="form-label">Kategori</label>
                        <select wire:model="category_id" class="form-select">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-1">
                        <label class="form-label">RAB (Opsional)</label>
                        <select wire:model="rab_id" class="form-select">
                            <option value="">Tidak ada</option>
                            @foreach($rabs as $rab)
                                <option value="{{ $rab->id }}">{{ $rab->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- ROW 3: Lokasi (jika pengeluaran) & Deskripsi -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @if($type === 'expense')
                    <div class="sm:col-span-1">
                        <label class="form-label font-semibold text-[#22AF85]">📍 Lokasi Pengeluaran (Opsional)</label>
                        <select wire:model="expense_location_id" class="form-select border-[#22AF85]/20 focus:border-[#22AF85] focus:ring-[#22AF85]/20 dark:bg-slate-800">
                            <option value="">Pilih Lokasi / Vendor</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->icon }} {{ $loc->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-[9px] text-slate-400 dark:text-slate-500 mt-1 uppercase tracking-wider font-bold">Lacak tujuan pengeluaran</p>
                    </div>
                    <div class="sm:col-span-1">
                    @else
                    <div class="sm:col-span-2">
                    @endif
                        <label class="form-label">Deskripsi</label>
                        <textarea wire:model="description" class="form-input" rows="2" placeholder="Tuliskan keterangan lebih lanjut..."></textarea>
                    </div>
                </div>

                <!-- ROW 4: Bukti Transaksi -->
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700/50">
                    <label class="form-label flex items-center gap-2 mb-3 text-slate-700 dark:text-slate-300">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Bukti Transaksi (Pilih Foto)
                    </label>
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <div class="relative w-full sm:w-24 h-24 border-2 border-dashed border-slate-200 dark:border-slate-600 rounded-xl overflow-hidden flex items-center justify-center bg-white dark:bg-slate-800 shrink-0 shadow-inner group transition-colors hover:border-[#22AF85]/50">
                            @if ($evidence)
                                <img src="{{ $evidence->temporaryUrl() }}" class="object-cover w-full h-full">
                            @elseif ($existingEvidence)
                                <img src="{{ asset('storage/' . $existingEvidence) }}" class="object-cover w-full h-full">
                            @else
                                <svg class="w-8 h-8 text-slate-300 group-hover:text-[#22AF85]/50 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            @endif
                            <div wire:loading wire:target="evidence" class="absolute inset-0 bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm flex items-center justify-center">
                                <svg class="animate-spin h-5 w-5 text-[#22AF85]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </div>
                        </div>
                        <div class="flex-1 w-full">
                            <input type="file" wire:model="evidence" class="block w-full text-sm text-slate-500
                                file:mr-4 file:py-2.5 file:px-5
                                file:rounded-xl file:border-0
                                file:text-xs file:font-bold file:tracking-widest file:uppercase
                                file:bg-[#22AF85]/10 file:text-[#22AF85]
                                hover:file:bg-[#22AF85] hover:file:text-white transition-all cursor-pointer">
                            <p class="text-[10px] uppercase font-bold tracking-widest text-slate-400 mt-2">PNG, JPG, max 2MB.</p>
                        </div>
                    </div>
                    @error('evidence') <span class="text-xs font-bold text-rose-500 mt-2 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex gap-3 pt-4 border-t border-slate-100 dark:border-slate-700/50 mt-4">
                    <button type="button" wire:click="closeModal" wire:loading.attr="disabled" class="btn bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 w-1/3 disabled:opacity-50 disabled:cursor-not-allowed">
                        Kembali
                    </button>
                    <button type="submit" wire:loading.attr="disabled" class="btn btn-primary flex-1 shadow-lg shadow-[#22AF85]/30 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="save">💾 Simpan Transaksi</span>
                        <span wire:loading wire:target="save" class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
