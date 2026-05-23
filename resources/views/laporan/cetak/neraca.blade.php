<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Neraca</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #000; padding: 20px; }
        .header { text-align: center; margin-bottom: 16px; }
        .header h2 { font-size: 14px; font-weight: bold; }
        .header p  { font-size: 11px; color: #555; }
        .periode   { text-align: center; font-size: 12px; font-weight: bold; margin-bottom: 12px; }
        .row-2col  { display: flex; gap: 16px; }
        .col       { flex: 1; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th { background: #f0eeff; padding: 6px 8px; text-align: left; border: 1px solid #ccc; font-size: 10px; text-transform: uppercase; }
        td { padding: 4px 8px; border: 1px solid #ddd; vertical-align: middle; }
        tfoot td { background: #f7f5ff; font-weight: bold; }
        .text-right  { text-align: right; }
        .text-center { text-align: center; }
        .indent      { padding-left: 20px !important; color: #555; }
        .group-header { font-weight: 700; background: #faf8ff; }
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
    <p>Laporan Neraca</p>
</div>

<div class="periode">
    Periode: {{ $bulan > 0 ? DateTime::createFromFormat('!m', $bulan)->format('F') . ' ' : 'Seluruh Bulan — ' }}{{ $tahun }}
</div>

<div class="row-2col">

    {{-- ASET --}}
    <div class="col">
        <table>
            <thead>
                <tr><th colspan="3">ASET</th></tr>
                <tr>
                    <th>Kode</th>
                    <th>Nama Akun</th>
                    <th class="text-right">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @php $totalAset = 0; @endphp
                @foreach($data as $row)
                    @if($row['akun']->lev1 == 1)
                    <tr class="{{ $row['akun']->lev4 == 0 ? 'group-header' : '' }}">
                        <td>{{ $row['akun']->kode_akun }}</td>
                        <td class="{{ $row['akun']->lev4 > 0 ? 'indent' : '' }}">
                            {{ $row['akun']->nama_akun }}
                        </td>
                        <td class="text-right">
                            @php $saldo = $row['total_debet'] - $row['total_kredit']; @endphp
                            {{ $saldo != 0 ? number_format($saldo, 0, ',', '.') : '-' }}
                        </td>
                    </tr>
                    @php $totalAset += $saldo; @endphp
                    @endif
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-right">Total Aset</td>
                    <td class="text-right">Rp {{ number_format($totalAset, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- KEWAJIBAN & MODAL --}}
    <div class="col">
        <table>
            <thead>
                <tr><th colspan="3">KEWAJIBAN & MODAL</th></tr>
                <tr>
                    <th>Kode</th>
                    <th>Nama Akun</th>
                    <th class="text-right">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @php $totalKewModal = 0; @endphp
                @foreach($data as $row)
                    @if(in_array($row['akun']->lev1, [2, 3]))
                    <tr class="{{ $row['akun']->lev4 == 0 ? 'group-header' : '' }}">
                        <td>{{ $row['akun']->kode_akun }}</td>
                        <td class="{{ $row['akun']->lev4 > 0 ? 'indent' : '' }}">
                            {{ $row['akun']->nama_akun }}
                        </td>
                        <td class="text-right">
                            @php $saldo = $row['total_kredit'] - $row['total_debet']; @endphp
                            {{ $saldo != 0 ? number_format($saldo, 0, ',', '.') : '-' }}
                        </td>
                    </tr>
                    @php $totalKewModal += $saldo; @endphp
                    @endif
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-right">Total Kewajiban & Modal</td>
                    <td class="text-right">Rp {{ number_format($totalKewModal, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

</div>

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
