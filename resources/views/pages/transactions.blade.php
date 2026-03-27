<x-layouts.app title="Transaksi">
    <div class="flex justify-end gap-3 mb-4 no-print">
        <button onclick="Livewire.dispatch('openImportModal')" class="bg-white text-primary border border-primary/20 px-6 py-3 rounded-2xl font-bold shadow-sm hover:bg-primary hover:text-white transition-all active:scale-95 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
            Import Transaksi
        </button>
    </div>

    <livewire:transaction.form />
    <livewire:transaction.index />
    <livewire:transaction.import />
</x-layouts.app>
