<?php

namespace App\Livewire\Rab;

use Livewire\Component;
use App\Models\Rab;
use App\Models\RabItem;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public ?int $rabId = null;
    public string $name = '';
    public string $start_date = '';
    public string $end_date = '';
    public string $total_budget = '0';
    public string $description = '';
    public array $items = [];

    public bool $showModal = false;

    protected $listeners = ['editRab' => 'edit', 'createRab' => 'create'];

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'total_budget' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
            'items.*.amount' => 'required|numeric|min:0',
        ];
    }

    public function addItem(): void
    {
        $this->items[] = ['name' => '', 'amount' => '0', 'description' => ''];
    }

    public function removeItem(int $index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->updateTotal();
    }

    public function updateTotal(): void
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += (float) ($item['amount'] ?: 0);
        }
        $this->total_budget = (string) $total;
    }

    public function create(): void
    {
        $this->reset(['rabId', 'name', 'total_budget', 'description', 'items']);
        $this->start_date = now()->startOfMonth()->toDateString();
        $this->end_date = now()->endOfMonth()->toDateString();
        $this->addItem();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $rab = Rab::with('items')->findOrFail($id);
        $this->rabId = $rab->id;
        $this->name = $rab->name;
        $this->start_date = $rab->start_date ? \Illuminate\Support\Carbon::parse($rab->start_date)->format('Y-m-d') : now()->startOfMonth()->format('Y-m-d');
        $this->end_date = $rab->end_date ? \Illuminate\Support\Carbon::parse($rab->end_date)->format('Y-m-d') : now()->endOfMonth()->format('Y-m-d');
        $this->total_budget = (string) $rab->total_budget;
        $this->description = $rab->description ?? '';
        $this->items = $rab->items->toArray();
        $this->showModal = true;
    }

    public function save(\App\Services\RabService $service): void
    {
        $this->updateTotal();
        $this->validate();

        $data = [
            'name' => $this->name,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'total_budget' => $this->total_budget,
            'description' => $this->description,
        ];

        $service->store($data, $this->items, $this->rabId);

        $this->showModal = false;
        $this->resetFields();
        $this->dispatch('dataUpdated');
        $this->dispatch('alert', ['type' => 'success', 'message' => 'RAB berhasil disimpan!']);
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetFields();
        $this->dispatch('dataUpdated');
    }

    private function resetFields(): void
    {
        $this->rabId = null;
        $this->name = '';
        $this->start_date = now()->startOfMonth()->toDateString();
        $this->end_date = now()->endOfMonth()->toDateString();
        $this->total_budget = '0';
        $this->description = '';
        $this->items = [];
    }

    public function render()
    {
        return view('livewire.rab.form');
    }
}
