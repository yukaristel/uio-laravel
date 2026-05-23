@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-receipt"></i> Detail Transaksi</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('transaksi.struk', $transaksi) }}"
           class="btn btn-outline-secondary btn-sm" target="_blank">
            <i class="bi bi-printer"></i> Cetak Struk
        </a>
        <a href="{{ route('transaksi.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row g-3">

    {{-- Info Transaksi --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-info-circle"></i> Informasi Transaksi
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0" style="font-size:0.9rem;">
                    <tr>
                        <td style="color: var(--uio-text-muted); width:45%;">No. Transaksi</td>
                        <td><code>{{ $transaksi->no_transaksi }}</code></td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Tanggal</td>
                        <td>{{ $transaksi->tanggal_transaksi->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Kasir</td>
                        <td>{{ $transaksi->user->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Metode</td>
                        <td>
                            <span class="badge badge-uio-info">
                                {{ strtoupper($transaksi->metode_pembayaran) }}
                            </span>
                        </td>
                    </tr>
                    <tr style="border-top: 2px solid var(--uio-border);">
                        <td style="color: var(--uio-text-muted);">Total Harga</td>
                        <td><strong>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Total Modal</td>
                        <td>Rp {{ number_format($transaksi->total_modal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Keuntungan</td>
                        <td style="color: var(--uio-primary-dark);">
                            <strong>Rp {{ number_format($transaksi->total_keuntungan, 0, ',', '.') }}</strong>
                        </td>
                    </tr>
                    <tr style="border-top: 2px solid var(--uio-border);">
                        <td style="color: var(--uio-text-muted);">Uang Bayar</td>
                        <td>Rp {{ number_format($transaksi->uang_bayar, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Kembalian</td>
                        <td><strong>Rp {{ number_format($transaksi->uang_kembali, 0, ',', '.') }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Detail Item --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-list-ul"></i> Item Pesanan
            </div>
            <div class="card-body p-0">
                <table class="table mb-0" style="font-size:0.88rem;">
                    <thead>
                        <tr>
                            <th>Menu</th>
                            <th class="text-center">Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Modal Satuan</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi->details as $detail)
                        <tr>
                            <td><strong>{{ $detail->menu->nama_menu }}</strong></td>
                            <td class="text-center">{{ $detail->jumlah }}x</td>
                            <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($detail->harga_modal_satuan, 0, ',', '.') }}</td>
                            <td><strong>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background: var(--uio-bg);">
                            <td colspan="4" class="text-end"><strong>Total</strong></td>
                            <td>
                                <strong style="color: var(--uio-primary-dark);">
                                    Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                </strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection
