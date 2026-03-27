<?php

namespace App\Livewire\Payable;

use Livewire\Component;
use App\Models\Payable;
use App\Services\PayableService;

class Form extends Component
{
    public ?int $payableId = null;
    public string $supplier_name = '';
    public string $total = '';
    public string $status = 'unpaid';
    public string $due_date = '';
    public string $description = '';

    public bool $showModal = false;

    protected $listeners = ['editPayable', 'createPayable'];

    protected function rules(): array
    {
        return [
            'supplier_name' => 'required|string|max:255',
            'total' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'description' => 'nullable|string|max:500',
        ];
    }

    public function createPayable(): void
    {
        $this->resetFields();
        $this->due_date = now()->addDays(30)->format('Y-m-d');
        $this->showModal = true;
    }

    public function editPayable(int $id): void
    {
        $payable = Payable::findOrFail($id);
        $this->payableId = $payable->id;
        $this->supplier_name = $payable->supplier_name;
        $this->total = $payable->total;
        $this->status = $payable->status;
        $this->due_date = \Carbon\Carbon::parse($payable->due_date)->format('Y-m-d');
        $this->description = $payable->description ?? '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $service = app(PayableService::class);
        $data = [
            'supplier_name' => $this->supplier_name,
            'total' => $this->total,
            'due_date' => $this->due_date,
            'description' => $this->description,
        ];

        if ($this->payableId) {
            $payable = Payable::findOrFail($this->payableId);
            $service->updatePayable($payable, $data);
        } else {
            $service->createPayable($data);
        }

        $this->showModal = false;
        $this->resetFields();
        $this->dispatch('dataUpdated');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetFields();
    }

    private function resetFields(): void
    {
        $this->payableId = null;
        $this->supplier_name = '';
        $this->total = '';
        $this->status = 'unpaid';
        $this->due_date = '';
        $this->description = '';
    }

    public function render()
    {
        return view('livewire.payable.form');
    }
}
