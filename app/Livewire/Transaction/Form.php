<?php

namespace App\Livewire\Transaction;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Category;
use App\Models\Rab;
use App\Models\ExpenseLocation;

class Form extends Component
{
    use WithFileUploads;

    public ?int $transactionId = null;
    public ?int $account_id = null;
    public string $type = 'expense';
    public string $amount = '';
    public ?int $category_id = null;
    public string $source_type = 'B2C';
    public string $description = '';
    public string $date = '';
    public ?int $rab_id = null;
    public ?int $expense_location_id = null;
    public $evidence;
    public ?string $existingEvidence = null;

    public bool $showModal = false;

    protected $listeners = ['editTransaction', 'createTransaction'];

    protected function rules(): array
    {
        return [
            'account_id' => 'nullable|exists:accounts,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'source_type' => 'required|in:B2B,B2C',
            'description' => 'nullable|string|max:500',
            'date' => 'required|date',
            'rab_id' => 'nullable|exists:rabs,id',
            'expense_location_id' => 'nullable|exists:expense_locations,id',
            'evidence' => 'nullable|image|max:2048', // 2MB Max
        ];
    }

    public function createTransaction(): void
    {
        $this->resetFields();
        $this->date = now()->format('Y-m-d');
        $this->showModal = true;
    }

    public function editTransaction(int $id): void
    {
        $transaction = Transaction::findOrFail($id);
        $this->transactionId = $transaction->id;
        $this->account_id = $transaction->account_id;
        $this->type = $transaction->type ?? 'expense';
        $this->amount = $transaction->amount;
        $this->category_id = $transaction->category_id;
        $this->source_type = $transaction->source_type;
        $this->description = $transaction->description ?? '';
        $this->date = \Carbon\Carbon::parse($transaction->date)->format('Y-m-d');
        $this->rab_id = $transaction->rab_id;
        $this->expense_location_id = $transaction->expense_location_id;
        $this->existingEvidence = $transaction->evidence_path;
        $this->showModal = true;
    }

    public function save(\App\Services\TransactionService $service): void
    {
        if (empty($this->account_id)) {
            $defaultAccount = Account::first();
            if ($defaultAccount) $this->account_id = $defaultAccount->id;
        }

        // Clean amount string from commas to handle formatting gracefully
        $this->amount = str_replace(',', '.', $this->amount);

        $this->validate();

        $data = [
            'account_id' => $this->account_id,
            'type' => $this->type,
            'amount' => (float) $this->amount,
            'category_id' => $this->category_id ?: null,
            'source_type' => $this->source_type,
            'description' => $this->description,
            'date' => $this->date,
            'rab_id' => $this->rab_id ?: null,
            'expense_location_id' => $this->expense_location_id ?: null,
        ];

        try {
            if ($this->evidence) {
                $data['evidence_path'] = $this->evidence->store('evidence', 'public');
            }

            $service->store($data, $this->transactionId);

            $this->showModal = false;
            $this->resetFields();
            $this->dispatch('dataUpdated');
            $this->dispatch('alert', ['type' => 'success', 'message' => 'Transaksi berhasil disimpan!']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error saving transaction: ' . $e->getMessage());
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Gagal menyimpan transaksi. Terjadi kesalahan sistem.']);
        }
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetFields();
    }

    private function resetFields(): void
    {
        $this->transactionId = null;
        $this->account_id = null;
        $this->type = 'expense';
        $this->amount = '';
        $this->category_id = null;
        $this->source_type = 'B2C';
        $this->description = '';
        $this->date = '';
        $this->rab_id = null;
        $this->expense_location_id = null;
        $this->evidence = null;
        $this->existingEvidence = null;
    }

    public function render()
    {
        return view('livewire.transaction.form', [
            'accounts' => Account::all(),
            'categories' => Category::where('type', $this->type)->get(),
            'rabs' => Rab::all(),
            'locations' => ExpenseLocation::all(),
        ]);
    }
}
