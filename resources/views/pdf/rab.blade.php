@extends('pdf.layout')

@section('content')
    <div class="info-grid">
        <table style="width: 100%; border: none; margin-bottom: 20px;">
            <tr>
                <td style="width: 60%; border: none; padding: 0;">
                    <div class="label">Nama Anggaran (RAB):</div>
                    <div class="value">{{ $rab->name }}</div>
                    <div style="font-size: 11px; color: #6b7280; margin-top: 5px;">
                        {{ $rab->description ?: 'Rencana Anggaran Biaya Operasional' }}
                    </div>
                </td>
                <td style="width: 40%; border: none; padding: 0; text-align: right;">
                    <div class="label">ID RAB:</div>
                    <div class="value">RAB-{{ str_pad($rab->id, 4, '0', STR_PAD_LEFT) }}</div>
                    <div class="label" style="margin-top: 10px;">Tanggal Laporan:</div>
                    <div class="value">{{ now()->format('d M Y') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div style="background-color: #f3f4f6; padding: 15px; border-radius: 8px; margin-bottom: 30px;">
        <table style="width: 100%; border: none; margin-bottom: 0;">
            <tr>
                <td style="border: none; padding: 0;">
                    <div class="label">Total Anggaran</div>
                    <div class="value" style="font-size: 18px; color: #4f46e5;">Rp {{ number_format($rab->total_budget, 0, ',', '.') }}</div>
                </td>
                <td style="border: none; padding: 0; text-align: center;">
                    <div class="label">Terpakai</div>
                    <div class="value" style="font-size: 18px; color: #1f2937;">Rp {{ number_format($rab->used_budget, 0, ',', '.') }}</div>
                </td>
                <td style="border: none; padding: 0; text-align: right;">
                    <div class="label">Sisa Saldo</div>
                    <div class="value" style="font-size: 18px; color: {{ $rab->remaining_budget < 0 ? '#ef4444' : '#059669' }}">
                        Rp {{ number_format($rab->remaining_budget, 0, ',', '.') }}
                    </div>
                </td>
            </tr>
        </table>
        <div style="width: 100%; height: 8px; background-color: #e5e7eb; border-radius: 4px; margin-top: 15px;">
            <div style="width: {{ min(100, $rab->usage_percentage) }}%; height: 8px; background-color: {{ $rab->usage_percentage > 90 ? '#ef4444' : '#4f46e5' }}; border-radius: 4px;"></div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">#</th>
                <th style="width: 65%">Item Anggaran</th>
                <th class="text-right">Alokasi Dana</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rab->items as $index => $item)
            <tr>
                <td style="color: #6b7280;">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $item->name }}</strong><br>
                    <span style="font-size: 10px; color: #6b7280;">{{ $item->description ?: 'Alokasi dana operasional' }}</span>
                </td>
                <td class="text-right font-semibold">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 100px; width: 100%;">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 33%; border: none; text-align: center;">
                    <div class="label">Disusun Oleh,</div>
                    <div style="margin-top: 60px; font-weight: bold; border-top: 1px solid #1f2937; display: inline-block; padding: 5px 20px;">
                        Financial Controller
                    </div>
                </td>
                <td style="width: 33%; border: none;"></td>
                <td style="width: 33%; border: none; text-align: center;">
                    <div class="label">Disetujui Oleh,</div>
                    <div style="margin-top: 60px; font-weight: bold; border-top: 1px solid #1f2937; display: inline-block; padding: 5px 20px;">
                        Workshop Manager
                    </div>
                </td>
            </tr>
        </table>
    </div>
@endsection
