<?php

namespace App\Imports;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\Category;
use App\Models\ExpenseLocation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Carbon;

class TransactionsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    private array $failures = [];

    public function model(array $row)
    {
        $account = Account::where('name', $row['akun'])->first();
        $category = Category::where('name', $row['kategori'])->first();
        $location = isset($row['lokasi']) ? ExpenseLocation::where('name', $row['lokasi'])->first() : null;

        // Date parsing: handle Excel serial, DD/MM/YYYY, or YYYY-MM-DD
        $rawDate = $row['tanggal'];
        if (is_numeric($rawDate)) {
            $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($rawDate);
        } else {
            // Remove any extra whitespace
            $rawDate = trim($rawDate);
            
            // Try to detect DD/MM/YYYY vs YYYY-MM-DD
            if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $rawDate)) {
                $date = Carbon::createFromFormat('d/m/Y', $rawDate);
            } elseif (preg_match('/^\d{1,2}-\d{1,2}-\d{4}$/', $rawDate)) {
                $date = Carbon::createFromFormat('d-m-Y', $rawDate);
            } else {
                $date = Carbon::parse($rawDate);
            }
        }

        return new Transaction([
            'account_id' => $account?->id,
            'category_id' => $category?->id,
            'expense_location_id' => $location?->id,
            'type' => strtolower($row['tipe']) === 'masuk' ? 'income' : 'expense',
            'amount' => $row['jumlah'],
            'description' => $row['deskripsi'],
            'date' => $date,
        ]);
    }

    public function rules(): array
    {
        return [
            'tanggal' => 'required',
            'deskripsi' => 'required|string',
            'tipe' => 'required|in:Masuk,Keluar,masuk,keluar',
            'jumlah' => 'required|numeric|min:0',
            'akun' => 'required|exists:accounts,name',
            'kategori' => 'required|exists:categories,name',
            'lokasi' => 'nullable|exists:expense_locations,name',
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        $this->failures = array_merge($this->failures, $failures);
    }

    public function getFailures(): array
    {
        return $this->failures;
    }
}
