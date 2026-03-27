<?php

namespace App\Livewire\Category;

use Livewire\Component;
use App\Models\Category;

class Form extends Component
{
    public ?int $categoryId = null;
    public string $name = '';
    public string $type = 'income';

    public bool $showModal = false;

    protected $listeners = ['editCategory', 'createCategory'];

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
        ];
    }

    public function createCategory(): void
    {
        $this->resetFields();
        $this->showModal = true;
    }

    public function editCategory(int $id): void
    {
        $category = Category::findOrFail($id);
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->type = $category->type;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'type' => $this->type,
        ];

        if ($this->categoryId) {
            Category::findOrFail($this->categoryId)->update($data);
        } else {
            Category::create($data);
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
        $this->categoryId = null;
        $this->name = '';
        $this->type = 'income';
    }

    public function render()
    {
        return view('livewire.category.form');
    }
}
