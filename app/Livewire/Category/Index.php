<?php

namespace App\Livewire\Category;

use Livewire\Component;
use App\Models\Category;

class Index extends Component
{
    public string $search = '';

    public function delete(int $id): void
    {
        $category = Category::findOrFail($id);
        $category->delete();
        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Kategori berhasil dipindahkan ke Tempat Sampah.'
        ]);
    }

    public function render()
    {
        $query = Category::withCount('transactions');

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        return view('livewire.category.index', [
            'categories' => $query->get(),
        ]);
    }
}
