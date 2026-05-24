@extends('layouts.app')

@section('title', 'Detail Top-Up Game')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-controller"></i> Detail Top-Up Game</h2>
    <a href="{{ route('topup-game.index') }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-info-circle"></i> Informasi Top-Up
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0" style="font-size:0.9rem;">
                    <tr>
                        <td style="color: var(--uio-text-muted); width:40%;">No. Transaksi</td>
                        <td><code>{{ $topupGame->no_transaksi }}</code></td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Tanggal</td>
                        <td>{{ $topupGame->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Game</td>
                        <td><span class="badge badge-uio-info">{{ $topupGame->nama_game }}</span></td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Item</td>
                        <td><strong>{{ $topupGame->nama_item }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Jumlah</td>
                        <td>{{ number_format($topupGame->jumlah_item, 0, ',', '.') }}</td>
                    </tr>
                    <tr style="border-top: 2px solid var(--uio-border);">
                        <td style="color: var(--uio-text-muted);">Harga Beli</td>
                        <td>Rp {{ number_format($topupGame->harga_beli, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Harga Jual</td>
                        <td><strong>Rp {{ number_format($topupGame->harga_jual, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Keuntungan</td>
                        <td>
                            <strong style="color: {{ $topupGame->keuntungan >= 0 ? 'var(--uio-primary-dark)' : 'var(--uio-danger)' }};">
                                Rp {{ number_format($topupGame->keuntungan, 0, ',', '.') }}
                            </strong>
                        </td>
                    </tr>
                    <tr style="border-top: 2px solid var(--uio-border);">
                        <td style="color: var(--uio-text-muted);">Dibeli Via</td>
                        <td>
                            <code>{{ $topupGame->rekening_beli }}</code>
                            <div style="font-size:0.8rem; color: var(--uio-text-muted);">
                                {{ $topupGame->rekeningBeli->nama_akun ?? '-' }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Dibayar Via</td>
                        <td>
                            <code>{{ $topupGame->rekening_bayar }}</code>
                            <div style="font-size:0.8rem; color: var(--uio-text-muted);">
                                {{ $topupGame->rekeningBayar->nama_akun ?? '-' }}
                            </div>
                        </td>
                    </tr>
                    @if($topupGame->id_pelanggan)
                    <tr>
                        <td style="color: var(--uio-text-muted);">ID Pelanggan</td>
                        <td><strong>{{ $topupGame->id_pelanggan }}</strong></td>
                    </tr>
                    @endif
                    @if($topupGame->keterangan)
                    <tr>
                        <td style="color: var(--uio-text-muted);">Keterangan</td>
                        <td>{{ $topupGame->keterangan }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="color: var(--uio-text-muted);">Dicatat Oleh</td>
                        <td>{{ $topupGame->user->nama_lengkap }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
