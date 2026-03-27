<?php

namespace App\Exports;

use App\Models\Account;
use App\Models\Category;
use App\Models\ExpenseLocation;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TransactionTemplateExport implements WithHeadings, WithEvents, ShouldAutoSize, WithColumnFormatting
{
    public function headings(): array
    {
        return [
            'Tanggal',
            'Deskripsi',
            'Tipe',
            'Jumlah',
            'Akun',
            'Kategori',
            'Lokasi',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Get all accounts, categories, and locations
                $accounts = Account::pluck('name')->toArray();
                $categories = Category::pluck('name')->toArray();
                $locations = ExpenseLocation::pluck('name')->toArray();
                
                // Add fixed "Masuk/Keluar" options
                $types = ['Masuk', 'Keluar'];

                // Apply dynamic validation to rows 2-100 (for example)
                for ($row = 2; $row <= 100; $row++) {
                    // Tanggal (Cell A)
                    $this->setDateValidation($sheet, 'A' . $row);

                    // Tipe (Cell C)
                    $this->setDropdown($sheet, 'C' . $row, $types);
                    
                    // Akun (Cell E)
                    $this->setDropdown($sheet, 'E' . $row, $accounts);
                    
                    // Kategori (Cell F)
                    $this->setDropdown($sheet, 'F' . $row, $categories);
                    
                    // Lokasi (Cell G)
                    $this->setDropdown($sheet, 'G' . $row, $locations);
                }
            },
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => 'yyyy-mm-dd',
        ];
    }

    private function setDateValidation($sheet, $cell): void
    {
        $validation = $sheet->getDataValidation($cell);
        $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_DATE);
        $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
        $validation->setAllowBlank(true);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setOperator(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::OPERATOR_BETWEEN);
        $validation->setFormula1('1900-01-01');
        $validation->setFormula2('2099-12-31');
        $validation->setErrorTitle('Input Error');
        $validation->setError('Format tanggal tidak valid (gunakan YYYY-MM-DD).');
        $validation->setPromptTitle('Input Tanggal');
        $validation->setPrompt('Masukkan tanggal (YYYY-MM-DD) atau Double-Klik untuk kalender (Google Sheets).');
    }

    private function setDropdown($sheet, $cell, array $options): void
    {
        if (empty($options)) return;
        
        $validation = $sheet->getDataValidation($cell);
        $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
        $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
        $validation->setAllowBlank(true);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setErrorTitle('Input Error');
        $validation->setError('Pilihan tidak valid. Silakan pilih dari daftar.');
        $validation->setPromptTitle('Pilih dari daftar');
        $validation->setPrompt('Gunakan dropdown untuk memilih data yang valid.');
        $validation->setFormula1('"' . implode(',', $options) . '"');
    }
}
