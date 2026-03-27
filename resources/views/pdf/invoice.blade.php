@extends('pdf.layout')

@section('content')
    <div class="info-grid">
        <table style="width: 100%; border: none; margin-bottom: 20px;">
            <tr>
                <td style="width: 50%; border: none; padding: 0;">
                    <div class="label">Tagihan Untuk:</div>
                    <div class="value">{{ $invoice->client_name }}</div>
                    <div style="font-size: 11px; color: #6b7280; margin-top: 5px;">
                        Klien Terdaftar
                    </div>
                </td>
                <td style="width: 50%; border: none; padding: 0; text-align: right;">
                    <div class="label">Nomor Invoice:</div>
                    <div class="value">INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</div>
                    <div class="label" style="margin-top: 10px;">Tanggal Jatuh Tempo:</div>
                    <div class="value" style="color: {{ $invoice->isOverdue() ? '#ef4444' : '#1f2937' }}">
                        {{ $invoice->due_date->format('d M Y') }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 60%">Deskripsi Layanan / Item</th>
                <th class="text-right">Kuantitas</th>
                <th class="text-right">Harga Satuan</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>{{ $invoice->client_name }} - Proyek Keuangan</strong><br>
                    <span style="font-size: 10px; color: #6b7280;">{{ $invoice->notes ?: 'Layanan konsultasi dan pengerjaan proyek sepatu' }}</span>
                </td>
                <td class="text-right">1</td>
                <td class="text-right">Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="totals">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; padding: 5px 0;">Subtotal</td>
                <td style="border: none; padding: 5px 0; text-align: right;">Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="border: none; padding: 5px 0;">Diskon / Pajak</td>
                <td style="border: none; padding: 5px 0; text-align: right;">Rp 0</td>
            </tr>
            <tr class="total-row">
                <td style="border: none; font-weight: bold;">Grand Total</td>
                <td style="border: none; text-align: right; font-weight: bold;">Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="border: none; padding: 10px 0; color: #059669;">Telah Dibayar</td>
                <td style="border: none; padding: 10px 0; text-align: right; font-weight: bold; color: #059669;">- Rp {{ number_format($invoice->paid_amount, 0, ',', '.') }}</td>
            </tr>
            <tr class="grand-total">
                <td style="border: none;">Sisa Tagihan</td>
                <td style="border: none; text-align: right;">Rp {{ number_format($invoice->remaining_amount, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 50px;">
        <div class="label">Status Pembayaran:</div>
        <div style="margin-top: 5px;">
            @if($invoice->payment_status === 'paid')
                <span class="badge badge-success" style="background-color: #d1fae5; color: #065f46; border: 1px solid #065f46;">LUNAS</span>
            @elseif($invoice->payment_status === 'partial')
                <span class="badge badge-warning" style="background-color: #fef3c7; color: #92400e; border: 1px solid #92400e;">DIBAYAR SEBAGIAN</span>
            @else
                <span class="badge" style="background-color: #fee2e2; color: #991b1b; border: 1px solid #991b1b;">BELUM DIBAYAR</span>
            @endif
        </div>
    </div>

    <div style="margin-top: 50px; font-size: 10px; color: #6b7280; line-height: 1.5;">
        <strong>Instruksi Pembayaran:</strong><br>
        Silakan transfer pembayaran ke rekening berikut:<br>
        Bank Central Asia (BCA) - 1234567890 a/n SHOEWORKSHOP INDONESIA<br>
        Harap sertakan nomor invoice <strong>INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</strong> pada berita transfer.
    </div>
@endsection
