<?php

namespace App\Livewire\Trash;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;
use App\Models\Rab;
use App\Models\Invoice;
use App\Models\Payable;
use App\Models\Account;
use App\Models\Category;
use App\Models\ExpenseLocation;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;

    public string $typeFilter = 'all';
    public array $selectedItems = [];
    public bool $selectAll = false;

    public function updatingTypeFilter(): void
    {
        $this->selectedItems = [];
        $this->selectAll = false;
    }

    public function updatedSelectAll(bool $value): void
    {
        if ($value) {
            $items = [];
            
            if ($this->typeFilter === 'all' || $this->typeFilter === 'transaction') {
                $ids = Transaction::onlyTrashed()->pluck('id');
                foreach ($ids as $id) $items[] = "transaction-$id";
            }
            
            if ($this->typeFilter === 'all' || $this->typeFilter === 'rab') {
                $ids = Rab::onlyTrashed()->pluck('id');
                foreach ($ids as $id) $items[] = "rab-$id";
            }
            
            if ($this->typeFilter === 'all' || $this->typeFilter === 'invoice') {
                $ids = Invoice::onlyTrashed()->pluck('id');
                foreach ($ids as $id) $items[] = "invoice-$id";
            }

            if ($this->typeFilter === 'all' || $this->typeFilter === 'payable') {
                $ids = Payable::onlyTrashed()->pluck('id');
                foreach ($ids as $id) $items[] = "payable-$id";
            }

            if ($this->typeFilter === 'all' || $this->typeFilter === 'account') {
                $ids = Account::onlyTrashed()->pluck('id');
                foreach ($ids as $id) $items[] = "account-$id";
            }

            if ($this->typeFilter === 'all' || $this->typeFilter === 'category') {
                $ids = Category::onlyTrashed()->pluck('id');
                foreach ($ids as $id) $items[] = "category-$id";
            }

            if ($this->typeFilter === 'all' || $this->typeFilter === 'location') {
                $ids = ExpenseLocation::onlyTrashed()->pluck('id');
                foreach ($ids as $id) $items[] = "location-$id";
            }
            
            $this->selectedItems = $items;
        } else {
            $this->selectedItems = [];
        }
    }

    public function updatedSelectedItems(): void
    {
        $this->selectAll = false;
    }

    public function restore(string $type, int $id, \App\Services\TransactionService $transactionService, \App\Services\RabService $rabService): void
    {
        $restored = match ($type) {
            'transaction' => $transactionService->restore($id),
            'rab' => $rabService->restore($id),
            'invoice', 'payable', 'account', 'category', 'location' => $this->getModel($type, $id)?->restore(),
            default => false,
        };
        
        if ($restored) {
            $this->dispatch('alert', ['type' => 'success', 'message' => 'Data berhasil dipulihkan!']);
        }
    }

    public function forceDelete(string $type, int $id, \App\Services\TransactionService $transactionService, \App\Services\RabService $rabService): void
    {
        $deleted = match ($type) {
            'transaction' => $transactionService->forceDelete($id),
            'rab' => $rabService->forceDelete($id),
            'invoice', 'payable', 'account', 'category', 'location' => $this->getModel($type, $id)?->forceDelete(),
            default => false,
        };
        
        if ($deleted) {
            $this->dispatch('alert', ['type' => 'success', 'message' => 'Data berhasil dihapus permanen!']);
        }
    }

    public function bulkRestore(\App\Services\TransactionService $transactionService, \App\Services\RabService $rabService): void
    {
        if (empty($this->selectedItems)) return;

        $grouped = $this->groupSelectedItems();
        $count = 0;

        foreach ($grouped as $type => $ids) {
            foreach ($ids as $id) {
                $restored = match ($type) {
                    'transaction' => $transactionService->restore($id),
                    'rab' => $rabService->restore($id),
                    'invoice', 'payable', 'account', 'category', 'location' => $this->getModel($type, $id)?->restore(),
                    default => false,
                };
                if ($restored) $count++;
            }
        }

        $this->selectedItems = [];
        $this->selectAll = false;
        $this->dispatch('alert', ['type' => 'success', 'message' => "$count data berhasil dipulihkan!"]);
    }

    public function bulkForceDelete(\App\Services\TransactionService $transactionService, \App\Services\RabService $rabService): void
    {
        if (empty($this->selectedItems)) return;

        $grouped = $this->groupSelectedItems();
        $count = 0;

        foreach ($grouped as $type => $ids) {
            $count += match ($type) {
                'transaction' => $transactionService->bulkForceDelete($ids),
                'rab' => $rabService->bulkForceDelete($ids),
                'invoice' => Invoice::onlyTrashed()->whereIn('id', $ids)->forceDelete(),
                'payable' => Payable::onlyTrashed()->whereIn('id', $ids)->forceDelete(),
                'account' => Account::onlyTrashed()->whereIn('id', $ids)->forceDelete(),
                'category' => Category::onlyTrashed()->whereIn('id', $ids)->forceDelete(),
                'location' => ExpenseLocation::onlyTrashed()->whereIn('id', $ids)->forceDelete(),
                default => 0,
            };
        }

        $this->selectedItems = [];
        $this->selectAll = false;
        $this->dispatch('alert', ['type' => 'success', 'message' => "$count data berhasil dihapus permanen!"]);
    }

    private function groupSelectedItems(): array
    {
        $grouped = [];
        foreach ($this->selectedItems as $item) {
            if (str_contains($item, '-')) {
                [$type, $id] = explode('-', $item);
                $grouped[$type][] = (int)$id;
            }
        }
        return $grouped;
    }

    private function getModel(string $type, int $id)
    {
        return match ($type) {
            'transaction' => Transaction::onlyTrashed()->find($id),
            'rab' => Rab::onlyTrashed()->find($id),
            'invoice' => Invoice::onlyTrashed()->find($id),
            'payable' => Payable::onlyTrashed()->find($id),
            'account' => Account::onlyTrashed()->find($id),
            'category' => Category::onlyTrashed()->find($id),
            'location' => ExpenseLocation::onlyTrashed()->find($id),
            default => null,
        };
    }

    public function render()
    {
        $deletedTotal = Transaction::onlyTrashed()->count() + 
                      Rab::onlyTrashed()->count() + 
                      Invoice::onlyTrashed()->count() + 
                      Payable::onlyTrashed()->count() +
                      Account::onlyTrashed()->count() +
                      Category::onlyTrashed()->count() +
                      ExpenseLocation::onlyTrashed()->count();

        $transactions = ($this->typeFilter === 'all' || $this->typeFilter === 'transaction') 
            ? Transaction::onlyTrashed()->orderBy('deleted_at', 'desc')->get() 
            : collect();

        $rabs = ($this->typeFilter === 'all' || $this->typeFilter === 'rab') 
            ? Rab::onlyTrashed()->orderBy('deleted_at', 'desc')->get() 
            : collect();

        $invoices = ($this->typeFilter === 'all' || $this->typeFilter === 'invoice') 
            ? Invoice::onlyTrashed()->orderBy('deleted_at', 'desc')->get() 
            : collect();

        $payables = ($this->typeFilter === 'all' || $this->typeFilter === 'payable') 
            ? Payable::onlyTrashed()->orderBy('deleted_at', 'desc')->get() 
            : collect();
            
        $accounts = ($this->typeFilter === 'all' || $this->typeFilter === 'account') 
            ? Account::onlyTrashed()->orderBy('deleted_at', 'desc')->get() 
            : collect();
            
        $categories = ($this->typeFilter === 'all' || $this->typeFilter === 'category') 
            ? Category::onlyTrashed()->orderBy('deleted_at', 'desc')->get() 
            : collect();
            
        $locations = ($this->typeFilter === 'all' || $this->typeFilter === 'location') 
            ? ExpenseLocation::onlyTrashed()->orderBy('deleted_at', 'desc')->get() 
            : collect();

        return view('livewire.trash.index', [
            'deletedTotal' => $deletedTotal,
            'transactions' => $transactions,
            'rabs' => $rabs,
            'invoices' => $invoices,
            'payables' => $payables,
            'accounts' => $accounts,
            'categories' => $categories,
            'locations' => $locations,
        ]);
    }
}
