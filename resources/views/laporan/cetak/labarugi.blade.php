<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laba Rugi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #000; padding: 20px; }
        .header { text-align: center; margin-bottom: 16px; }
        .header h2 { font-size: 14px; font-weight: bold; }
        .header p  { font-size: 11px; color: #555; }
        .periode   { text-align: center; font-size: 12px; font-weight: bold; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th { background: #f0eeff; padding: 6px 8px; text-align: left; border: 1px solid #ccc; font-size: 10px; text-transform: uppercase; }
        td { padding: 4px 8px; border: 1px solid #ddd; vertical-align: middle; }
        tfoot td { font-weight: bold; }
        .text-right  { text-align: right; }
        .text-center { text-align: center; }
        .indent      { padding-left: 20px !important; color: #555; }
        .group-header { font-weight: 700; background: #faf8ff; }
        .section-header { background: #ede8ff; font-weight: 700; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        .total-pendapatan { background: #d4ecd9; font-weight: bold; }
        .total-beban      { background: #f5d4d4; font-weight: bold; }
        .total-laba       { background: #d4ecd9; font-weight: bold; font-size: 12px; }
        .total-rugi       { background: #f5d4d4; font-weight: bold; font-size: 12px; }
        .footer { margin-top: 20px; font-size: 10px; color: #888; text-align: center; }
        .no-print { margin-top: 16px; text-align: center; }
        @media print {
            .no-print { display: none; }
            body { padding: 10px; }
        }
    </style>
</head>
<body>

<div class="header">
    <h2>UIO RUMAH MAKAN</h2>
    <p>Laporan Laba Rugi</p>
</div>

<div class="periode">
    Periode: {{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }}
</div>

<table>
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama Akun</th>
            <th class="text-right">Debet</th>
            <th class="text-right">Kredit</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalPendapatan = 0;
            $totalBeban      = 0;
            $currentLev1     = null;
        @endphp
        @foreach($data as $row)
            @if($row['akun']->lev1 != $currentLev1)
                @php $currentLev1 = $row['akun']->lev1; @endphp
                <tr class="section-header">
                    <td colspan="4">
                        {{ $row['akun']->lev1 == 4 ? '▲ PENDAPATAN' : '▼ BEBAN' }}
                    </td>
                </tr>
            @endif
            <tr class="{{ $row['akun']->lev4 == 0 ? 'group-header' : '' }}">
                <td>{{ $row['akun']->kode_akun }}</td>
                <td class="{{ $row['akun']->lev4 > 0 ? 'indent' : '' }}">
                    {{ $row['akun']->nama_akun }}
                </td>
                <td class="text-right">
                    {{ $row['total_debet'] > 0 ? number_format($row['total_debet'], 0, ',', '.') : '-' }}
                </td>
                <td class="text-right">
                    {{ $row['total_kredit'] > 0 ? number_format($row['total_kredit'], 0, ',', '.') : '-' }}
                </td>
            </tr>
            @php
                if($row['akun']->lev1 == 4) $totalPendapatan += $row['total_kredit'];
                if($row['akun']->lev1 == 5) $totalBeban      += $row['total_debet'];
            @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr class="total-pendapatan">
            <td colspan="3" class="text-right">Total Pendapatan</td>
            <td class="text-right">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
        </tr>
        <tr class="total-beban">
            <td colspan="2" class="text-right">Total Beban</td>
            <td class="text-right">Rp {{ number_format($totalBeban, 0, ',', '.') }}</td>
            <td></td>
        </tr>
        @php $labaRugi = $totalPendapatan - $totalBeban; @endphp
        <tr class="{{ $labaRugi >= 0 ? 'total-laba' : 'total-rugi' }}">
            <td colspan="3" class="text-right">
                {{ $labaRugi >= 0 ? '✅ LABA BERSIH' : '❌ RUGI BERSIH' }}
            </td>
            <td class="text-right">
                Rp {{ number_format(abs($labaRugi), 0, ',', '.') }}
            </td>
        </tr>
    </tfoot>
</table>

<div class="footer">
    Dicetak pada: {{ now()->format('d/m/Y H:i') }} — UIO Rumah Makan
</div>

<div class="no-print">
    <button onclick="window.print()"
            style="padding: 8px 20px; background: #8B7EC8; color: white; border: none; border-radius: 6px; cursor: pointer;">
        🖨️ Cetak
    </button>
    <button onclick="window.close()"
            style="padding: 8px 20px; background: #eee; color: #333; border: none; border-radius: 6px; cursor: pointer; margin-left: 8px;">
        ✕ Tutup
    </button>
</div>

</body>
</html>
