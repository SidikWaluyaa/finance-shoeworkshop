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
                
                // Add instruction row (row 2) with field requirements
                $instructions = [
                    'WAJIB - DD/MM/YYYY',
                    'WAJIB',
                    'Opsional (Masuk/Keluar)',
                    'WAJIB - contoh: 100000 atau 100.000',
                    'Opsional',
                    'Opsional',
                    'Opsional',
                ];
                $col = 'A';
                foreach ($instructions as $hint) {
                    $sheet->setCellValue($col . '2', $hint);
                    $col++;
                }
                // Style instruction row (italic, gray, smaller)
                $sheet->getStyle('A2:G2')->applyFromArray([
                    'font' => [
                        'italic' => true,
                        'size' => 9,
                        'color' => ['rgb' => '888888'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFF9E6'],
                    ],
                ]);

                // Style header row with green for mandatory, gray for optional
                $mandatoryCols = ['A1', 'B1', 'D1'];
                $optionalCols = ['C1', 'E1', 'F1', 'G1'];
                foreach ($mandatoryCols as $c) {
                    $sheet->getStyle($c)->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => '22AF85'],
                        ],
                    ]);
                }
                foreach ($optionalCols as $c) {
                    $sheet->getStyle($c)->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => '333333']],
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'E2E8F0'],
                        ],
                    ]);
                }

                // Get all accounts, categories, and locations
                $accounts = Account::pluck('name')->toArray();
                $categories = Category::pluck('name')->toArray();
                $locations = ExpenseLocation::pluck('name')->toArray();
                
                // Add fixed "Masuk/Keluar" options
                $types = ['Masuk', 'Keluar'];

                // Apply dynamic validation to rows 3-100 (row 2 is instruction)
                for ($row = 3; $row <= 100; $row++) {
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
            'A' => NumberFormat::FORMAT_TEXT, // Text to prevent Excel date mangling
            'D' => NumberFormat::FORMAT_TEXT, // Text to support "100.000" notation
        ];
    }

    private function setDateValidation($sheet, $cell): void
    {
        $validation = $sheet->getDataValidation($cell);
        $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_DATE);
        $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION); // Allow skipping error
        $validation->setAllowBlank(true);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setOperator(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::OPERATOR_BETWEEN);
        $validation->setFormula1('1900-01-01');
        $validation->setFormula2('2099-12-31');
        $validation->setErrorTitle('Peringatan Format');
        $validation->setError('Sangat disarankan menggunakan format YYYY-MM-DD (contoh: 2025-12-31) untuk akurasi data.');
        $validation->setPromptTitle('Input Tanggal');
        $validation->setPrompt('Format: YYYY-MM-DD (Contoh: 2026-03-27) atau DD/MM/YYYY.');
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
