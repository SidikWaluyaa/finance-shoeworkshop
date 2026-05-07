<?php

namespace App\Livewire\Transaction;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\Transaction;
use App\Models\Account;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterType = '';
    public string $filterSource = '';
    public string $filterAccount = '';
    
    public array $selectedRows = [];
    public bool $selectAll = false;

    protected $queryString = ['search', 'filterType', 'filterSource', 'filterAccount'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSelectAll(bool $value): void
    {
        if ($value) {
            $this->selectedRows = $this->getTransactionsQuery()->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedRows = [];
        }
    }

    public function updatedSelectedRows(): void
    {
        $this->selectAll = false;
    }

    #[On('dataUpdated')]
    public function refresh(): void
    {
        // Component will automatically re-render
    }

    public function delete(int $id, \App\Services\TransactionService $service): void
    {
        $service->delete($id);
        $this->dispatch('dataUpdated');
        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Transaksi berhasil dipindahkan ke tempat sampah.'
        ]);
    }

    public function bulkDelete(\App\Services\TransactionService $service): void
    {
        if (empty($this->selectedRows)) return;

        $count = $service->bulkDelete($this->selectedRows);

        $this->selectedRows = [];
        $this->selectAll = false;
        
        $this->dispatch('dataUpdated');
        $this->dispatch('alert', [
            'type' => 'success',
            'message' => "$count Transaksi berhasil dihapus."
        ]);
    }

    private function getTransactionsQuery()
    {
        $query = Transaction::with(['account', 'category', 'invoice', 'rab', 'location'])
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc');

        if ($this->search) {
            $query->where('description', 'like', '%' . $this->search . '%');
        }

        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        if ($this->filterSource) {
            $query->where('source_type', $this->filterSource);
        }

        if ($this->filterAccount) {
            $query->where('account_id', $this->filterAccount);
        }

        return $query;
    }

    public function render()
    {
        return view('livewire.transaction.index', [
            'accounts' => Account::orderBy('name')->get(),
            'transactions' => $this->getTransactionsQuery()->paginate(15),
        ]);
    }
}
