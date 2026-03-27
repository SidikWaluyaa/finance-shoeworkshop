<?php

namespace App\Livewire\Payable;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payable;
use App\Models\Account;
use App\Services\PayableService;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterStatus = '';
    public ?int $payAccountId = null;
    public string $payAmount = '';
    public bool $showPayModal = false;
    public ?int $payingId = null;

    protected $listeners = ['dataUpdated' => '$refresh'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function confirmPay(int $id): void
    {
        $payable = Payable::findOrFail($id);
        $this->payingId = $id;
        $this->payAccountId = Account::first()?->id;
        $this->payAmount = (string) $payable->remaining_amount;
        $this->showPayModal = true;
    }

    public function markAsPaid(): void
    {
        if (!$this->payingId || !$this->payAccountId) return;

        $this->validate([
            'payAmount' => 'required|numeric|min:1',
            'payAccountId' => 'required|exists:accounts,id',
        ]);

        try {
            $service = app(PayableService::class);
            $service->markAsPaid($this->payingId, $this->payAccountId, (float) $this->payAmount);
            
            $this->showPayModal = false;
            $this->payingId = null;
            $this->payAmount = '';
            
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

    public function closePayModal(): void
    {
        $this->showPayModal = false;
        $this->payingId = null;
    }

    public function delete(int $id): void
    {
        $this->authorize('delete payables');
        
        $payable = Payable::findOrFail($id);
        $payable->delete();

        $this->dispatch('dataUpdated');
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Data utang dipindahkan ke Tempat Sampah.']);
    }

    public function render()
    {
        $query = Payable::orderBy('due_date', 'asc');

        if ($this->search) {
            $query->where('supplier_name', 'like', '%' . $this->search . '%');
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        return view('livewire.payable.index', [
            'payables' => $query->paginate(15),
            'accounts' => Account::all(),
        ]);
    }
}
