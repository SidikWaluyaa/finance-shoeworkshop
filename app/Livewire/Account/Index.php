<?php

namespace App\Livewire\Account;

use Livewire\Component;
use App\Models\Account;

class Index extends Component
{
    public string $search = '';
    public ?int $selectedAccountId = null;
    public bool $showHistory = false;

    public function openHistory(int $id): void
    {
        $this->selectedAccountId = $id;
        $this->showHistory = true;
    }

    public function closeHistory(): void
    {
        $this->showHistory = false;
        $this->selectedAccountId = null;
    }

    public function delete(int $id): void
    {
        $account = Account::findOrFail($id);
        $account->delete();
        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Akun berhasil dipindahkan ke Tempat Sampah.'
        ]);
        $this->dispatch('dataUpdated');
    }

    public function render()
    {
        $query = Account::withCount('transactions');

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $historyTransactions = [];
        $selectedAccount = null;
        if ($this->selectedAccountId) {
            $selectedAccount = Account::find($this->selectedAccountId);
            if ($selectedAccount) {
                $historyTransactions = $selectedAccount->transactions()
                    ->with(['category', 'location'])
                    ->orderBy('date', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        }

        return view('livewire.account.index', [
            'accounts' => $query->get(),
            'historyTransactions' => $historyTransactions,
            'selectedAccount' => $selectedAccount,
        ]);
    }
}
