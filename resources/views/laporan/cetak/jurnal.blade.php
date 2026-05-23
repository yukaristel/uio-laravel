<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Jurnal Transaksi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #000; padding: 20px; }
        .header { text-align: center; margin-bottom: 16px; }
        .header h2 { font-size: 14px; font-weight: bold; }
        .header p  { font-size: 11px; color: #555; }
        .periode   { text-align: center; font-size: 12px; font-weight: bold; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th { background: #f0eeff; padding: 6px 8px; text-align: left; border: 1px solid #ccc; font-size: 10px; text-transform: uppercase; }
        td { padding: 5px 8px; border: 1px solid #ddd; vertical-align: top; }
        tfoot td { background: #f7f5ff; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
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
    <p>Laporan Jurnal Transaksi</p>
</div>

<div class="periode">
    Periode:
    @if($mode === 'harian')
        {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}
    @elseif($mode === 'bulanan')
        {{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }}
    @else
        Tahun {{ $tahun }}
    @endif
</div>

<table>
    <thead>
        <tr>
            <th style="width:35px;">No</th>
            <th>Tanggal</th>
            <th>Ref. ID</th>
            <th>Rek. Debet</th>
            <th>Rek. Kredit</th>
            <th>Keterangan</th>
            <th class="text-right">Jumlah</th>
            <th>User</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data['jurnal_list'] as $i => $j)
        <tr>
            <td class="text-center">{{ $i + 1 }}</td>
            <td style="white-space:nowrap;">{{ \Carbon\Carbon::parse($j->tgl_transaksi)->format('d/m/Y') }}</td>
            <td class="text-center">#{{ $j->id }}</td>
            <td>
                {{ $j->rekening_debet }}<br>
                <span style="color:#888; font-size:9px;">{{ $j->rekeningDebet->nama_akun ?? '-' }}</span>
            </td>
            <td>
                {{ $j->rekening_kredit }}<br>
                <span style="color:#888; font-size:9px;">{{ $j->rekeningKredit->nama_akun ?? '-' }}</span>
            </td>
            <td>{{ $j->keterangan_transaksi }}</td>
            <td class="text-right">Rp {{ number_format($j->jumlah, 0, ',', '.') }}</td>
            <td>{{ $j->user->nama_lengkap ?? '-' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center">Tidak ada data</td>
        </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6" class="text-right">Total Transaksi</td>
            <td class="text-right">Rp {{ number_format($data['total_jumlah'], 0, ',', '.') }}</td>
            <td></td>
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
