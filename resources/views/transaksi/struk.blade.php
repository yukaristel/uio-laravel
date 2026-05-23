<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk — {{ $transaksi->no_transaksi }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            background: #fff;
            color: #000;
        }
        .struk {
            width: 300px;
            margin: 0 auto;
            padding: 16px 12px;
        }
        .text-center { text-align: center; }
        .text-right  { text-align: right; }
        .bold        { font-weight: bold; }
        .divider     { border-top: 1px dashed #000; margin: 8px 0; }
        .row         { display: flex; justify-content: space-between; margin: 3px 0; }
        .header      { margin-bottom: 8px; }
        .header h2   { font-size: 14px; font-weight: bold; }
        .header p    { font-size: 11px; color: #555; }
        .total-row   { font-size: 13px; font-weight: bold; }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="struk">

    {{-- Header --}}
    <div class="header text-center">
        <h2>UIO RUMAH MAKAN</h2>
        <p>Sistem Informasi Manajemen</p>
    </div>

    <div class="divider"></div>

    <div class="row">
        <span>No</span>
        <span>{{ $transaksi->no_transaksi }}</span>
    </div>
    <div class="row">
        <span>Tanggal</span>
        <span>{{ $transaksi->tanggal_transaksi->format('d/m/Y H:i') }}</span>
    </div>
    <div class="row">
        <span>Kasir</span>
        <span>{{ $transaksi->user->nama_lengkap }}</span>
    </div>
    <div class="row">
        <span>Metode</span>
        <span>{{ strtoupper($transaksi->metode_pembayaran) }}</span>
    </div>

    <div class="divider"></div>

    {{-- Items --}}
    @foreach($transaksi->details as $detail)
    <div style="margin-bottom: 4px;">
        <div class="bold">{{ $detail->menu->nama_menu }}</div>
        <div class="row">
            <span>{{ $detail->jumlah }}x Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</span>
            <span>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
        </div>
    </div>
    @endforeach

    <div class="divider"></div>

    {{-- Total --}}
    <div class="row total-row">
        <span>TOTAL</span>
        <span>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
    </div>
    <div class="row">
        <span>Bayar</span>
        <span>Rp {{ number_format($transaksi->uang_bayar, 0, ',', '.') }}</span>
    </div>
    <div class="row bold">
        <span>Kembali</span>
        <span>Rp {{ number_format($transaksi->uang_kembali, 0, ',', '.') }}</span>
    </div>

    <div class="divider"></div>

    <div class="text-center" style="margin-top: 8px; font-size: 11px;">
        Terima kasih sudah berkunjung!<br>
        Sampai jumpa lagi 🙏
    </div>

</div>

<div class="no-print text-center" style="margin-top: 16px;">
    <button onclick="window.print()"
            style="padding: 8px 20px; background: #8B7EC8; color: white; border: none; border-radius: 6px; cursor: pointer;">
        🖨️ Cetak Struk
    </button>
</div>

</body>
</html>
