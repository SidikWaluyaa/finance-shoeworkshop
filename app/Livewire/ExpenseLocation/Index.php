<?php

namespace App\Livewire\ExpenseLocation;

use Livewire\Component;
use App\Models\ExpenseLocation;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    protected $listeners = ['dataUpdated' => '$refresh', 'deleteLocation'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $location = ExpenseLocation::findOrFail($id);
        $location->delete();
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Lokasi berhasil dipindahkan ke Tempat Sampah!']);
    }

    public function render()
    {
        $locations = ExpenseLocation::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy('name')
            ->paginate(12);

        return view('livewire.expense-location.index', [
            'locations' => $locations
        ]);
    }
}
