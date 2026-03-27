<div class="space-y-6">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 dark:text-white flex items-center gap-3">
                <div class="p-3 rounded-2xl bg-rose-500/10 text-rose-500 shadow-inner">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                Tempat Sampah (Recycle Bin)
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium italic">Data yang dihapus (Soft Delete) tersimpan di sini sebelum dibersihkan permanen.</p>
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
            <div class="glass overflow-hidden shadow-sm">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/50">
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-slate-400">Tanggal / Deskripsi</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-slate-400">Jumlah</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-slate-400">Dihapus</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-slate-400 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($transactions as $t)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-700">{{ $t->date->format('d/m/Y') }}</div>
                                <div class="text-xs text-slate-400 truncate max-w-xs">{{ $t->description }}</div>
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
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-slate-400">Nama / Tipe</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-slate-400">Jenis</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-slate-400 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($accounts as $acc)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-700">{{ $acc->name }}</td>
                            <td class="px-6 py-4"><span class="badge" style="background: #E0E7FF; color: #4338CA;">AKUN</span></td>
                            <td class="px-6 py-4 text-right space-x-1">
                                <button wire:click="restore('account', {{ $acc->id }})" class="p-2 rounded-xl text-blue-500 hover:bg-blue-50 transition-all"><svg class="w-5 h-5 shadow-inner" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></button>
                            </td>
                        </tr>
                        @endforeach
                        @foreach($categories as $cat)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-700">{{ $cat->name }}</td>
                            <td class="px-6 py-4"><span class="badge" style="background: #D1FAE5; color: #059669;">KATEGORI</span></td>
                            <td class="px-6 py-4 text-right space-x-1">
                                <button wire:click="restore('category', {{ $cat->id }})" class="p-2 rounded-xl text-blue-500 hover:bg-blue-50 transition-all"><svg class="w-5 h-5 shadow-inner" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></button>
                            </td>
                        </tr>
                        @endforeach
                        @foreach($locations as $loc)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-700">{{ $loc->name }}</td>
                            <td class="px-6 py-4"><span class="badge" style="background: #FEF3C7; color: #D97706;">LOKASI</span></td>
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
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-slate-400">Nama / Tipe</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-slate-400">Total</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-slate-400 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($invoices as $i)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 italic">
                                <div class="font-bold text-slate-700">{{ $i->client_name }}</div>
                                <div class="text-[9px] font-black text-blue-500 uppercase tracking-tighter">PIUTANG (INVOICE)</div>
                            </td>
                            <td class="px-6 py-4 font-black text-slate-700">Rp {{ number_format($i->total, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right space-x-1">
                                <button wire:click="restore('invoice', {{ $i->id }})" class="p-2 rounded-xl text-blue-500 hover:bg-blue-50 shadow-sm transition-all"><svg class="w-5 h-5 shadow-inner" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></button>
                                <button wire:click="forceDelete('invoice', {{ $i->id }})" wire:confirm="Hapus permanen invoice ini?" class="p-2 rounded-xl text-rose-500 hover:bg-rose-50 shadow-sm transition-all"><svg class="w-5 h-5 shadow-inner" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                            </td>
                        </tr>
                        @endforeach
                        @foreach($payables as $p)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 italic">
                                <div class="font-bold text-slate-700">{{ $p->supplier_name }}</div>
                                <div class="text-[9px] font-black text-rose-500 uppercase tracking-tighter">HUTANG SUPPLIER</div>
                            </td>
                            <td class="px-6 py-4 font-black text-slate-700">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right space-x-1">
                                <button wire:click="restore('payable', {{ $p->id }})" class="p-2 rounded-xl text-blue-500 hover:bg-blue-50 shadow-sm transition-all"><svg class="w-5 h-5 shadow-inner" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></button>
                                <button wire:click="forceDelete('payable', {{ $p->id }})" wire:confirm="Hapus permanen data utang ini?" class="p-2 rounded-xl text-rose-500 hover:bg-rose-50 shadow-sm transition-all"><svg class="w-5 h-5 shadow-inner" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
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
