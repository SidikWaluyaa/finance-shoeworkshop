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
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Carbon;

class TransactionsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsEmptyRows
{
    private array $failures = [];

    /**
     * Heading row is row 1. Row 2 (instruction row) will be treated as data
     * but will be skipped by validation since its values don't match.
     * We filter it out in the model() method.
     */
    public function headingRow(): int
    {
        return 1;
    }

    /**
     * Parse amount from Indonesian currency format (e.g., "100.000" or "1.500.000")
     */
    private function parseAmount($raw): float
    {
        if ($raw === null || $raw === '') {
            return 0;
        }

        if (is_numeric($raw)) {
            return (float) $raw;
        }

        $cleaned = (string) $raw;

        // Remove currency prefix (Rp, IDR, etc.)
        $cleaned = preg_replace('/^(Rp\.?\s*|IDR\s*)/i', '', $cleaned);
        $cleaned = str_replace(' ', '', $cleaned);

        // Detect Indonesian format: dots as thousands, comma as decimal
        if (str_contains($cleaned, '.') && str_contains($cleaned, ',')) {
            // "1.500.000,50" → remove dots, replace comma with dot
            $cleaned = str_replace('.', '', $cleaned);
            $cleaned = str_replace(',', '.', $cleaned);
        } elseif (str_contains($cleaned, '.')) {
            // Only dots: check if it's thousands separator or decimal
            $parts = explode('.', $cleaned);
            $lastPart = end($parts);
            if (strlen($lastPart) === 3 && count($parts) > 1) {
                // Indonesian thousands: "100.000" → "100000"
                $cleaned = str_replace('.', '', $cleaned);
            }
        } elseif (str_contains($cleaned, ',')) {
            // Only comma: treat as decimal "100,50" → "100.50"
            $cleaned = str_replace(',', '.', $cleaned);
        }

        return (float) $cleaned;
    }

    public function model(array $row)
    {
        // Skip the instruction row (row 2) - it contains hints like "WAJIB"
        $tanggal = $row['tanggal'] ?? null;
        if ($tanggal === null || str_contains(strtolower((string) $tanggal), 'wajib')) {
            return null;
        }

        // Parse optional fields
        $account = !empty($row['akun']) ? Account::where('name', $row['akun'])->first() : null;
        $category = !empty($row['kategori']) ? Category::where('name', $row['kategori'])->first() : null;
        $location = !empty($row['lokasi']) ? ExpenseLocation::where('name', $row['lokasi'])->first() : null;

        // Parse type: null if blank
        $type = null;
        if (!empty($row['tipe'])) {
            $type = strtolower($row['tipe']) === 'masuk' ? 'income' : 'expense';
        }

        // Parse amount with Indonesian currency support
        $amount = $this->parseAmount($row['jumlah'] ?? 0);

        // Date parsing
        $rawDate = $row['tanggal'];
        if (is_numeric($rawDate)) {
            $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($rawDate);
        } else {
            $rawDate = trim($rawDate);
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
            'type' => $type,
            'amount' => $amount,
            'description' => $row['deskripsi'] ?? '',
            'date' => $date,
        ]);
    }

    public function rules(): array
    {
        return [
            // Use wildcard format for row validation
            '*.tanggal' => 'nullable', // We validate in model() instead
            '*.deskripsi' => 'nullable',
            '*.jumlah' => 'nullable',
            '*.tipe' => 'nullable',
            '*.akun' => 'nullable',
            '*.kategori' => 'nullable',
            '*.lokasi' => 'nullable',
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
