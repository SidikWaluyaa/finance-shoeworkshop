<?php

namespace App\Livewire\Transaction;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\Transaction;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterType = '';
    public string $filterSource = '';

    protected $queryString = ['search', 'filterType', 'filterSource'];

    public function updatingSearch(): void
    {
        $this->resetPage();
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

    public function render()
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

        return view('livewire.transaction.index', [
            'transactions' => $query->paginate(15),
        ]);
    }
}
