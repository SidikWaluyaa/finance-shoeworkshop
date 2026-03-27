<?php

namespace App\Livewire\Invoice;

use Livewire\Component;
use App\Models\Invoice;

class Form extends Component
{
    public ?int $invoiceId = null;
    public string $client_name = '';
    public string $total = '';
    public string $status = 'unpaid';
    public string $due_date = '';
    public string $notes = '';

    public bool $showModal = false;

    protected $listeners = ['editInvoice', 'createInvoice'];

    protected function rules(): array
    {
        return [
            'client_name' => 'required|string|max:255',
            'total' => 'required|numeric|min:0',
            'status' => 'required|in:unpaid,paid',
            'due_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function createInvoice(): void
    {
        $this->resetFields();
        $this->due_date = now()->addDays(30)->format('Y-m-d');
        $this->showModal = true;
    }

    public function editInvoice(int $id): void
    {
        $invoice = Invoice::findOrFail($id);
        $this->invoiceId = $invoice->id;
        $this->client_name = $invoice->client_name;
        $this->total = $invoice->total;
        $this->status = $invoice->status;
        $this->due_date = \Carbon\Carbon::parse($invoice->due_date)->format('Y-m-d');
        $this->notes = $invoice->notes ?? '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'client_name' => $this->client_name,
            'total' => $this->total,
            'status' => $this->status,
            'due_date' => $this->due_date,
            'notes' => $this->notes,
        ];

        if ($this->invoiceId) {
            Invoice::findOrFail($this->invoiceId)->update($data);
        } else {
            Invoice::create($data);
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
        $this->invoiceId = null;
        $this->client_name = '';
        $this->total = '';
        $this->status = 'unpaid';
        $this->due_date = '';
        $this->notes = '';
    }

    public function render()
    {
        return view('livewire.invoice.form');
    }
}
