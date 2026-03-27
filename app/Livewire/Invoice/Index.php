<?php

namespace App\Livewire\Invoice;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Category;

use App\Services\PdfService;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterStatus = '';

    protected $listeners = ['dataUpdated' => '$refresh'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function download(int $id, PdfService $service)
    {
        $invoice = Invoice::findOrFail($id);
        return $service->downloadInvoice($invoice);
    }

    public function markAsPaid(int $id): void
    {
        $invoice = Invoice::findOrFail($id);

        if ($invoice->isPaid()) return;

        // Auto-create income transaction
        $account = Account::first();
        $category = Category::where('type', 'income')->first();

        if (!$account) {
            session()->flash('error', 'Silakan buat akun keuangan terlebih dahulu.');
            return;
        }

        Transaction::create([
            'account_id' => $account->id,
            'type' => 'income',
            'amount' => $invoice->remaining_amount,
            'category_id' => $category?->id,
            'source_type' => 'B2B',
            'description' => 'Pembayaran invoice: ' . $invoice->client_name,
            'date' => now()->toDateString(),
            'invoice_id' => $invoice->id,
        ]);

        $invoice->refresh();
        $invoice->update(['status' => $invoice->payment_status]);

        $this->dispatch('dataUpdated');
        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Pembayaran berhasil dicatat!'
        ]);
    }

    public function delete(int $id): void
    {
        $this->authorize('delete invoices');
        
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();
        
        $this->dispatch('dataUpdated');
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Invoice dipindahkan ke Tempat Sampah.']);
    }

    public function render()
    {
        $query = Invoice::orderBy('due_date', 'asc');

        if ($this->search) {
            $query->where('client_name', 'like', '%' . $this->search . '%');
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        return view('livewire.invoice.index', [
            'invoices' => $query->paginate(15),
        ]);
    }
}
