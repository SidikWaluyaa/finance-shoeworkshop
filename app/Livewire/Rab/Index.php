<?php

namespace App\Livewire\Rab;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Rab;

use App\Services\PdfService;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $viewMode = 'list'; // 'list' or 'calendar'

    protected $listeners = ['dataUpdated' => '$refresh', 'deleteRab'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function download(int $id, PdfService $service)
    {
        $rab = Rab::with('items')->findOrFail($id);
        return $service->downloadRab($rab);
    }

    public function delete(int $id, \App\Services\RabService $service): void
    {
        $service->delete($id);
        $this->dispatch('dataUpdated');
        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'RAB berhasil dipindahkan ke tempat sampah.'
        ]);
    }

    public function render()
    {
        $query = Rab::orderBy('created_at', 'desc');

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        return view('livewire.rab.index', [
            'rabs' => $query->paginate(15),
        ]);
    }
}
