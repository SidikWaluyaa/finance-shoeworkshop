<div x-data="{ show: @entangle('showModal') }" x-show="show" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen p-4 px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        {{-- Backdrop --}}
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 transition-opacity bg-gray-500/75 backdrop-blur-sm"></div>

        {{-- Modal Content --}}
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
            class="inline-block relative overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            
            <div class="px-6 pt-6 pb-4 bg-white">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 tracking-tight">Import Transaksi Massal</h3>
                    <button @click="show = false" class="text-gray-400 hover:text-gray-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Template Download Section --}}
                <div class="bg-primary/5 rounded-2xl p-5 mb-6 border border-primary/10 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center text-primary">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">Belum punya template?</p>
                            <p class="text-xs text-gray-500">Unduh template Excel resmi dengan dropdown otomatis.</p>
                        </div>
                    </div>
                    <a href="{{ route('transactions.template') }}" class="bg-white text-primary border border-primary/20 px-4 py-2 rounded-xl text-sm font-bold hover:bg-primary hover:text-white transition-all shadow-sm">
                        Unduh Template
                    </a>
                </div>

                {{-- Upload Area --}}
                <div class="relative group">
                    <input type="file" wire:model="file" class="absolute inset-0 z-10 w-full h-full opacity-0 cursor-pointer">
                    <div class="border-2 border-dashed border-gray-200 rounded-2xl p-8 text-center group-hover:border-primary/50 group-hover:bg-primary/5 transition-all {{ $file ? 'bg-green-50 border-green-200' : '' }}">
                        <div class="mx-auto w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-4 text-gray-400 group-hover:text-primary transition-colors">
                            @if($file)
                                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            @endif
                        </div>
                        <p class="text-gray-900 font-bold">{{ $file ? $file->getClientOriginalName() : 'Klik atau tarik file ke sini' }}</p>
                        <p class="text-gray-400 text-xs mt-1">Format: .xlsx, .xls, .csv (Max 10MB)</p>
                    </div>
                </div>

                @error('file') <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> @enderror

                {{-- Error Reporting --}}
                @if(count($importFailures) > 0)
                <div class="mt-6">
                    <p class="text-sm font-bold text-red-600 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Beberapa data gagal divalidasi:
                    </p>
                    <div class="max-h-48 overflow-y-auto bg-red-50 rounded-2xl border border-red-100 p-4 space-y-3">
                        @foreach($importFailures as $failure)
                        <div class="text-xs text-red-700 pb-2 border-b border-red-100 last:border-0 last:pb-0">
                            <span class="font-bold">Baris {{ $failure['row'] }}:</span> 
                            {{ implode(', ', $failure['errors']) }}
                            <div class="text-[10px] text-red-500 mt-1 italic">
                                Data Terdeteksi: {{ json_encode($failure['values']) }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3 rounded-b-3xl">
                <button @click="show = false" class="px-5 py-2.5 text-sm font-bold text-gray-500 hover:bg-gray-200 rounded-xl transition-all">
                    Batal
                </button>
                <button wire:click="save" wire:loading.attr="disabled" 
                    class="bg-primary text-white px-8 py-2.5 rounded-xl font-bold shadow-lg shadow-primary/20 hover:bg-primary-dark transition-all active:scale-95 disabled:opacity-50 flex items-center gap-2">
                    <span wire:loading wire:target="save" class="inline-block animate-spin w-4 h-4 border-2 border-white border-t-transparent rounded-full mr-2"></span>
                    Mulai Import
                </button>
            </div>
        </div>
    </div>
</div>
