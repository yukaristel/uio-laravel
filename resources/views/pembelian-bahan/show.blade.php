@extends('layouts.app')

@section('title', 'Detail Pembelian Bahan')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-cart-plus"></i> Detail Pembelian Bahan</h2>
    <a href="{{ route('pembelian-bahan.index') }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-info-circle"></i> Informasi Pembelian
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0" style="font-size:0.9rem;">
                    <tr>
                        <td style="color: var(--uio-text-muted); width:40%;">Tanggal Beli</td>
                        <td><strong>{{ \Carbon\Carbon::parse($pembelianBahan->tanggal_beli)->format('d F Y') }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Bahan Baku</td>
                        <td><strong>{{ $pembelianBahan->bahanBaku->nama_bahan }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Jumlah Beli</td>
                        <td>
                            {{ number_format($pembelianBahan->jumlah_beli, 2, ',', '.') }}
                            <span class="badge badge-uio-info">{{ $pembelianBahan->bahanBaku->satuan }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Harga Beli/Satuan</td>
                        <td>Rp {{ number_format($pembelianBahan->harga_beli_satuan, 0, ',', '.') }}</td>
                    </tr>
                    <tr style="border-top: 2px solid var(--uio-border);">
                        <td style="color: var(--uio-text-muted);">Total Harga</td>
                        <td>
                            <strong style="font-size:1.1rem; color: var(--uio-primary-dark);">
                                Rp {{ number_format($pembelianBahan->total_harga, 0, ',', '.') }}
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Supplier</td>
                        <td>{{ $pembelianBahan->supplier ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Dicatat Oleh</td>
                        <td>{{ $pembelianBahan->user->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Stok Sekarang</td>
                        <td>
                            <strong>
                                {{ number_format($pembelianBahan->bahanBaku->stok_tersedia, 2, ',', '.') }}
                                {{ $pembelianBahan->bahanBaku->satuan }}
                            </strong>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
