@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-speedometer2"></i> Dashboard</h2>
    <span style="color: var(--uio-text-muted); font-size: 0.85rem;">
        <i class="bi bi-calendar3"></i> {{ now()->translatedFormat('l, d F Y') }}
    </span>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">

    <div class="col-6 col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #D4ECD9, #E8F5EA);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Transaksi Hari Ini</div>
                    <div class="stat-value">{{ $transaksiHariIni }}</div>
                </div>
                <div class="stat-icon" style="background-color: rgba(124,158,135,0.20);">
                    <i class="bi bi-cart3" style="color: var(--uio-primary-dark);"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #F5E6C0, #FAF0D4);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Pendapatan Hari Ini</div>
                    <div class="stat-value" style="font-size:1.1rem;">
                        Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}
                    </div>
                </div>
                <div class="stat-icon" style="background-color: rgba(232,201,138,0.30);">
                    <i class="bi bi-cash-coin" style="color: #8A6A20;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #F5D4D4, #FAE8E8);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Stok Menipis</div>
                    <div class="stat-value">{{ $stokMenuipis }}</div>
                </div>
                <div class="stat-icon" style="background-color: rgba(232,152,152,0.25);">
                    <i class="bi bi-exclamation-triangle" style="color: #8A3A3A;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #D4E6F5, #E8F0FA);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Menu Tersedia</div>
                    <div class="stat-value">{{ $totalMenu }}</div>
                </div>
                <div class="stat-icon" style="background-color: rgba(152,192,232,0.25);">
                    <i class="bi bi-egg-fried" style="color: #2A5A8A;"></i>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Row 2 --}}
<div class="row g-3 mb-4">

    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-graph-up"></i> Ringkasan Bulan Ini
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between py-2" style="border-bottom: 1px solid var(--uio-border);">
                    <span style="color: var(--uio-text-muted);">Total Pendapatan</span>
                    <strong>Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</strong>
                </div>
                <div class="d-flex justify-content-between py-2" style="border-bottom: 1px solid var(--uio-border);">
                    <span style="color: var(--uio-text-muted);">Keuntungan Hari Ini</span>
                    <strong style="color: var(--uio-primary-dark);">
                        Rp {{ number_format($keuntunganHariIni, 0, ',', '.') }}
                    </strong>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <span style="color: var(--uio-text-muted);">Total Aset Aktif</span>
                    <strong>{{ $totalAset }} unit</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history"></i> Transaksi Terakhir</span>
                <a href="{{ route('transaksi.index') }}" class="btn btn-outline-primary btn-sm">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>No. Transaksi</th>
                            <th>Waktu</th>
                            <th>Total</th>
                            <th>Metode</th>
                            <th>Kasir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksiTerakhir as $t)
                        <tr>
                            <td>
                                <a href="{{ route('transaksi.show', $t) }}">
                                    <code>{{ $t->no_transaksi }}</code>
                                </a>
                            </td>
                            <td style="font-size:0.83rem; color: var(--uio-text-muted);">
                                {{ $t->tanggal_transaksi->format('d/m H:i') }}
                            </td>
                            <td><strong>Rp {{ number_format($t->total_harga, 0, ',', '.') }}</strong></td>
                            <td>
                                <span class="badge badge-uio-info">{{ strtoupper($t->metode_pembayaran) }}</span>
                            </td>
                            <td style="font-size:0.85rem;">{{ $t->user->nama_lengkap }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4" style="color: var(--uio-text-muted);">
                                <i class="bi bi-inbox"></i> Belum ada transaksi hari ini
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection
