<div class="space-y-6">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="page-header flex items-center gap-3">
                <div class="p-2.5 rounded-2xl bg-rose-500/10 text-rose-500 shadow-inner">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                Tempat Sampah (Recycle Bin)
            </h1>
            <p class="page-description">Data terhapus sementara disimpan di sini sebelum dibersihkan permanen.</p>
        </div>
        
        <div class="flex items-center gap-2 bg-white/50 dark:bg-slate-800/50 backdrop-blur-md p-1.5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-x-auto max-w-full">
            <button wire:click="$set('typeFilter', 'all')" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-tighter transition-all {{ $typeFilter === 'all' ? 'bg-slate-800 text-white shadow-lg' : 'text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700' }}">Semua</button>
            <button wire:click="$set('typeFilter', 'transaction')" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-tighter transition-all {{ $typeFilter === 'transaction' ? 'bg-[#22AF85] text-white shadow-lg' : 'text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700' }}">Transaksi</button>
            <button wire:click="$set('typeFilter', 'rab')" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-tighter transition-all {{ $typeFilter === 'rab' ? 'bg-[#FFC232] text-slate-900 shadow-lg' : 'text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700' }}">RAB</button>
            <button wire:click="$set('typeFilter', 'invoice')" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-tighter transition-all {{ $typeFilter === 'invoice' ? 'bg-blue-500 text-white shadow-lg' : 'text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700' }}">Invoice</button>
            <button wire:click="$set('typeFilter', 'account')" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-tighter transition-all {{ $typeFilter === 'account' ? 'bg-indigo-500 text-white shadow-lg' : 'text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700' }}">Akun</button>
            <button wire:click="$set('typeFilter', 'category')" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-tighter transition-all {{ $typeFilter === 'category' ? 'bg-emerald-500 text-white shadow-lg' : 'text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700' }}">Kategori</button>
        </div>
    </div>

    {{-- Bulk Action Bar --}}
    @if(count($selectedItems) > 0)
        <div class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 animate-in fade-in slide-in-from-bottom-4 duration-300">
            <div class="bg-slate-900 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-6 border border-slate-800">
                <div class="flex items-center gap-2 border-r border-slate-700 pr-6">
                    <span class="flex items-center justify-center w-6 h-6 bg-rose-500 text-[10px] font-bold rounded-full text-white">{{ count($selectedItems) }}</span>
                    <span class="text-sm font-medium text-slate-300">Data dipilih</span>
                </div>
                
                <div class="flex items-center gap-2">
                    <button wire:click="bulkRestore" class="flex items-center gap-2 px-3 py-1.5 rounded-lg hover:bg-blue-500/10 text-blue-400 transition-colors text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Pulihkan
                    </button>

                    <button wire:click="bulkForceDelete" wire:confirm="Hapus permanen {{ count($selectedItems) }} data yang dipilih? Tindakan ini tidak dapat dibatalkan." class="flex items-center gap-2 px-3 py-1.5 rounded-lg hover:bg-red-500/10 text-red-400 transition-colors text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Hapus Permanen
                    </button>
                    
                    <button wire:click="$set('selectedItems', [])" class="ml-2 text-slate-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($deletedTotal === 0)
    <div class="glass py-20 flex flex-col items-center justify-center text-center space-y-4 border-dashed">
        <div class="w-24 h-24 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-200 dark:text-slate-700">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </div>
        <div>
            <h3 class="text-xl font-bold text-slate-700 dark:text-slate-300">Tempat Sampah Kosong</h3>
            <p class="text-slate-400">Tidak ada data yang baru saja dihapus.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
    </div>
    @else
    
    <div class="grid grid-cols-1 gap-8">
        <!-- Transactions Section -->
        @if($transactions->count() > 0)
        <div class="space-y-3">
            <h2 class="text-sm font-black uppercase tracking-widest text-[#22AF85] flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-[#22AF85]"></span>
                Transaksi Terhapus
            </h2>
            <div class="glass overflow-hidden shadow-sm !bg-white/50 dark:!bg-slate-900/50">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 text-slate-400 dark:text-slate-500">
                            <th class="px-6 py-4 w-10">
                                <div class="flex items-center justify-center">
                                    <input type="checkbox" wire:model.live="selectAll" class="form-checkbox h-3.5 w-3.5 rounded border-slate-300 dark:border-slate-600 text-rose-500 focus:ring-rose-500">
                                </div>
                            </th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider">Tanggal / Deskripsi</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider">Dihapus</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($transactions as $t)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors group {{ in_array('transaction-'.$t->id, $selectedItems) ? 'bg-rose-50 dark:bg-rose-500/5' : '' }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center">
                                    <input type="checkbox" wire:model.live="selectedItems" value="transaction-{{ $t->id }}" class="form-checkbox h-4 w-4 rounded border-slate-300 dark:border-slate-600 text-rose-500 focus:ring-rose-500">
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-700 dark:text-white">{{ $t->date->format('d/m/Y') }}</div>
                                <div class="text-xs text-slate-400 dark:text-slate-500 truncate max-w-xs">{{ $t->description }}</div>
                            </td>
                            <td class="px-6 py-4 font-black">
                                <span class="{{ $t->type === 'income' ? 'text-[#22AF85]' : 'text-rose-500' }}">
                                    {{ $t->type === 'income' ? '+' : '-' }}Rp {{ number_format($t->amount, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs font-medium text-slate-400">
                                {{ $t->deleted_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-1">
                                <button wire:click="restore('transaction', {{ $t->id }})" class="p-2 rounded-xl text-blue-500 hover:bg-blue-50 shadow-sm border border-transparent hover:border-blue-100 transition-all"><svg class="w-5 h-5 shadow-inner" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></button>
                                <button wire:click="forceDelete('transaction', {{ $t->id }})" wire:confirm="Hapus permanen transaksi ini?" class="p-2 rounded-xl text-rose-500 hover:bg-rose-50 shadow-sm border border-transparent hover:border-rose-100 transition-all"><svg class="w-5 h-5 shadow-inner" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Master Data Section (Combined) -->
        @if($accounts->count() > 0 || $categories->count() > 0 || $locations->count() > 0)
        <div class="space-y-3">
            <h2 class="text-sm font-black uppercase tracking-widest text-indigo-500 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                Master Data Terhapus
            </h2>
            <div class="glass overflow-hidden shadow-sm">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/50">
                            <th class="px-6 py-4 w-10"></th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-slate-400">Nama / Tipe</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-slate-400">Jenis</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-slate-400 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($accounts as $acc)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors {{ in_array('account-'.$acc->id, $selectedItems) ? 'bg-rose-50 dark:bg-rose-500/5' : '' }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center">
                                    <input type="checkbox" wire:model.live="selectedItems" value="account-{{ $acc->id }}" class="form-checkbox h-4 w-4 rounded border-slate-300 dark:border-slate-600 text-rose-500 focus:ring-rose-500">
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-700 dark:text-white">{{ $acc->name }}</td>
                            <td class="px-6 py-4"><span class="badge badge-info">AKUN</span></td>
                            <td class="px-6 py-4 text-right space-x-1">
                                <button wire:click="restore('account', {{ $acc->id }})" class="p-2 rounded-xl text-blue-500 hover:bg-blue-50 transition-all"><svg class="w-5 h-5 shadow-inner" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></button>
                            </td>
                        </tr>
                        @endforeach
                        @foreach($categories as $cat)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors {{ in_array('category-'.$cat->id, $selectedItems) ? 'bg-rose-50 dark:bg-rose-500/5' : '' }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center">
                                    <input type="checkbox" wire:model.live="selectedItems" value="category-{{ $cat->id }}" class="form-checkbox h-4 w-4 rounded border-slate-300 dark:border-slate-600 text-rose-500 focus:ring-rose-500">
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-700 dark:text-white">{{ $cat->name }}</td>
                            <td class="px-6 py-4"><span class="badge badge-success">KATEGORI</span></td>
                            <td class="px-6 py-4 text-right space-x-1">
                                <button wire:click="restore('category', {{ $cat->id }})" class="p-2 rounded-xl text-blue-500 hover:bg-blue-50 transition-all"><svg class="w-5 h-5 shadow-inner" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></button>
                            </td>
                        </tr>
                        @endforeach
                        @foreach($locations as $loc)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors {{ in_array('location-'.$loc->id, $selectedItems) ? 'bg-rose-50 dark:bg-rose-500/5' : '' }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center">
                                    <input type="checkbox" wire:model.live="selectedItems" value="location-{{ $loc->id }}" class="form-checkbox h-4 w-4 rounded border-slate-300 dark:border-slate-600 text-rose-500 focus:ring-rose-500">
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-700 dark:text-white">{{ $loc->name }}</td>
                            <td class="px-6 py-4"><span class="badge badge-warning">LOKASI</span></td>
                            <td class="px-6 py-4 text-right space-x-1">
                                <button wire:click="restore('location', {{ $loc->id }})" class="p-2 rounded-xl text-blue-500 hover:bg-blue-50 transition-all"><svg class="w-5 h-5 shadow-inner" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Bills Section (Invoices & Payables) -->
        @if($invoices->count() > 0 || $payables->count() > 0)
        <div class="space-y-3">
            <h2 class="text-sm font-black uppercase tracking-widest text-blue-500 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                Invoices & Utang Terhapus
            </h2>
            <div class="glass overflow-hidden shadow-sm">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/50">
                            <th class="px-6 py-4 w-10"></th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-slate-400">Nama / Tipe</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-slate-400">Total</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-slate-400 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($invoices as $i)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors {{ in_array('invoice-'.$i->id, $selectedItems) ? 'bg-rose-50 dark:bg-rose-500/5' : '' }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center">
                                    <input type="checkbox" wire:model.live="selectedItems" value="invoice-{{ $i->id }}" class="form-checkbox h-4 w-4 rounded border-slate-300 dark:border-slate-600 text-rose-500 focus:ring-rose-500">
                                </div>
                            </td>
                            <td class="px-6 py-4 italic">
                                <div class="font-bold text-slate-700 dark:text-white">{{ $i->client_name }}</div>
                                <div class="text-[9px] font-black text-blue-500 dark:text-blue-400 uppercase tracking-tighter">PIUTANG (INVOICE)</div>
                            </td>
                            <td class="px-6 py-4 font-black text-slate-700 dark:text-slate-300">Rp {{ number_format($i->total, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right space-x-1">
                                <button wire:click="restore('invoice', {{ $i->id }})" class="p-2 rounded-xl text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-500/10 shadow-sm transition-all"><svg class="w-5 h-5 shadow-inner" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></button>
                                <button wire:click="forceDelete('invoice', {{ $i->id }})" wire:confirm="Hapus permanen invoice ini?" class="p-2 rounded-xl text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 shadow-sm transition-all"><svg class="w-5 h-5 shadow-inner" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                            </td>
                        </tr>
                        @endforeach
                        @foreach($payables as $p)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors {{ in_array('payable-'.$p->id, $selectedItems) ? 'bg-rose-50 dark:bg-rose-500/5' : '' }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center">
                                    <input type="checkbox" wire:model.live="selectedItems" value="payable-{{ $p->id }}" class="form-checkbox h-4 w-4 rounded border-slate-300 dark:border-slate-600 text-rose-500 focus:ring-rose-500">
                                </div>
                            </td>
                            <td class="px-6 py-4 italic">
                                <div class="font-bold text-slate-700 dark:text-white">{{ $p->supplier_name }}</div>
                                <div class="text-[9px] font-black text-rose-500 dark:text-rose-400 uppercase tracking-tighter">HUTANG SUPPLIER</div>
                            </td>
                            <td class="px-6 py-4 font-black text-slate-700 dark:text-slate-300">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right space-x-1">
                                <button wire:click="restore('payable', {{ $p->id }})" class="p-2 rounded-xl text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-500/10 shadow-sm transition-all"><svg class="w-5 h-5 shadow-inner" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></button>
                                <button wire:click="forceDelete('payable', {{ $p->id }})" wire:confirm="Hapus permanen data utang ini?" class="p-2 rounded-xl text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 shadow-sm transition-all"><svg class="w-5 h-5 shadow-inner" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
    @endif
</div>
