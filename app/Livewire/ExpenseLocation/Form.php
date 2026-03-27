<?php

namespace App\Livewire\ExpenseLocation;

use Livewire\Component;
use App\Models\ExpenseLocation;

class Form extends Component
{
    public ?int $locationId = null;
    public string $name = '';
    public string $address = '';
    public string $description = '';
    public string $icon = '';

    public bool $showModal = false;

    protected $listeners = ['createLocation' => 'create', 'editLocation' => 'edit'];

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:50',
        ];
    }

    public function create(): void
    {
        $this->reset(['locationId', 'name', 'address', 'description', 'icon']);
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $location = ExpenseLocation::findOrFail($id);
        $this->locationId = $location->id;
        $this->name = $location->name;
        $this->address = $location->address ?? '';
        $this->description = $location->description ?? '';
        $this->icon = $location->icon ?? '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        ExpenseLocation::updateOrCreate(
            ['id' => $this->locationId],
            [
                'name' => $this->name,
                'address' => $this->address,
                'description' => $this->description,
                'icon' => $this->icon,
            ]
        );

        $this->showModal = false;
        $this->dispatch('dataUpdated');
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Lokasi berhasil disimpan!']);
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.expense-location.form');
    }
}
