<?php

namespace App\Livewire\Invoice;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Category;

use App\Services\PdfService;
use App\Services\InvoiceService;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterStatus = '';
    public array $selectedRows = [];
    public bool $selectAll = false;

    protected $listeners = ['dataUpdated' => '$refresh'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSelectAll(bool $value): void
    {
        if ($value) {
            $this->selectedRows = $this->getInvoicesQuery()->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedRows = [];
        }
    }

    public function updatedSelectedRows(): void
    {
        $this->selectAll = false;
    }

    public function download(int $id, PdfService $service)
    {
        $invoice = Invoice::findOrFail($id);
        return $service->downloadInvoice($invoice);
    }

    public function markAsPaid(int $id, InvoiceService $service): void
    {
        try {
            $service->markAsPaid($id);
            $this->dispatch('dataUpdated');
            $this->dispatch('alert', [
                'type' => 'success',
                'message' => 'Pembayaran berhasil dicatat!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete(int $id, InvoiceService $service): void
    {
        $this->authorize('delete invoices');
        $service->delete($id);
        
        $this->dispatch('dataUpdated');
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Invoice dipindahkan ke Tempat Sampah.']);
    }

    public function bulkDelete(): void
    {
        $this->authorize('delete invoices');
        
        if (empty($this->selectedRows)) return;

        $count = Invoice::whereIn('id', $this->selectedRows)->delete();

        $this->selectedRows = [];
        $this->selectAll = false;

        $this->dispatch('dataUpdated');
        $this->dispatch('alert', ['type' => 'success', 'message' => "$count invoice berhasil dihapus."]);
    }

    private function getInvoicesQuery()
    {
        $query = Invoice::orderBy('due_date', 'asc');

        if ($this->search) {
            $query->where('client_name', 'like', '%' . $this->search . '%');
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        return $query;
    }

    public function render()
    {
        return view('livewire.invoice.index', [
            'invoices' => $this->getInvoicesQuery()->paginate(15),
        ]);
    }
}
