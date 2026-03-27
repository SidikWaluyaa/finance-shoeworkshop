<?php

namespace App\Livewire\Transaction;

use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TransactionsImport;
use Illuminate\Support\Facades\Log;

class Import extends Component
{
    use WithFileUploads;

    public $file;
    public bool $showModal = false;
    public array $importFailures = [];
    public bool $isProcessing = false;

    protected $listeners = ['openImportModal' => 'open'];

    public function open(): void
    {
        $this->reset(['file', 'importFailures', 'isProcessing']);
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240', // 10MB max
        ]);

        $this->isProcessing = true;
        $this->importFailures = [];

        try {
            $import = new TransactionsImport();
            Excel::import($import, $this->file->getRealPath());

            $failures = $import->getFailures();

            if (count($failures) > 0) {
                foreach ($failures as $failure) {
                    $this->importFailures[] = [
                        'row' => $failure->row(),
                        'attribute' => $failure->attribute(),
                        'errors' => $failure->errors(),
                        'values' => $failure->values(),
                    ];
                }
                $this->dispatch('alert', ['type' => 'warning', 'message' => 'Import selesai dengan beberapa error.']);
            } else {
                $this->showModal = false;
                $this->dispatch('dataUpdated');
                $this->dispatch('alert', ['type' => 'success', 'message' => 'Semua transaksi berhasil diimport!']);
            }
        } catch (\Exception $e) {
            Log::error('Import Error: ' . $e->getMessage());
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Terjadi kesalahan saat memproses file: ' . $e->getMessage()]);
        } finally {
            $this->isProcessing = false;
        }
    }

    public function render()
    {
        return view('livewire.transaction.import');
    }
}
