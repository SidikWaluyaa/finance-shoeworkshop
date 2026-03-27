<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Rab;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class PdfService
{
    /**
     * Generate Invoice PDF and return for download.
     */
    public function downloadInvoice(Invoice $invoice)
    {
        $data = [
            'title' => 'Invoice INV-' . str_pad($invoice->id, 5, '0', STR_PAD_LEFT),
            'invoice' => $invoice,
        ];

        $pdf = Pdf::loadView('pdf.invoice', $data);
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'Invoice-' . str_pad($invoice->id, 5, '0', STR_PAD_LEFT) . '.pdf');
    }

    /**
     * Generate RAB Report PDF and return for download.
     */
    public function downloadRab(Rab $rab)
    {
        $data = [
            'title' => 'RAB REPORT: ' . $rab->name,
            'rab' => $rab,
        ];

        $pdf = Pdf::loadView('pdf.rab', $data);
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'RAB-' . str_replace(' ', '-', $rab->name) . '.pdf');
    }
}
