<?php

namespace App\Livewire\Account;

use Livewire\Component;
use App\Models\Account;

class Form extends Component
{
    public ?int $accountId = null;
    public string $name = '';
    public string $type = 'bank';

    public bool $showModal = false;

    protected $listeners = ['editAccount', 'createAccount'];

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|in:bank,cash,e-wallet',
        ];
    }

    public function createAccount(): void
    {
        $this->resetFields();
        $this->showModal = true;
    }

    public function editAccount(int $id): void
    {
        $account = Account::findOrFail($id);
        $this->accountId = $account->id;
        $this->name = $account->name;
        $this->type = $account->type;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'type' => $this->type,
        ];

        if ($this->accountId) {
            Account::findOrFail($this->accountId)->update($data);
        } else {
            Account::create($data);
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
        $this->accountId = null;
        $this->name = '';
        $this->type = 'bank';
    }

    public function render()
    {
        return view('livewire.account.form');
    }
}
