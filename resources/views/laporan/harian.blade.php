@extends('layouts.app')

@section('title', 'Laporan Harian')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-calendar-day"></i> Laporan Harian</h2>
</div>

{{-- Filter --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('laporan.harian') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control form-control-sm"
                       value="{{ $tanggal->format('Y-m-d') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="bi bi-filter"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #E0DBFF, #EDE8FF);">
            <div class="stat-label">Total Transaksi</div>
            <div class="stat-value">{{ $data['total_transaksi'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #D4ECD9, #E8F5EA);">
            <div class="stat-label">Total Penjualan</div>
            <div class="stat-value" style="font-size:1.1rem;">
                Rp {{ number_format($data['total_penjualan'], 0, ',', '.') }}
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #F5E6C0, #FAF0D4);">
            <div class="stat-label">Total Modal</div>
            <div class="stat-value" style="font-size:1.1rem;">
                Rp {{ number_format($data['total_modal'], 0, ',', '.') }}
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #D4E6F5, #E8F0FA);">
            <div class="stat-label">Keuntungan</div>
            <div class="stat-value" style="font-size:1.1rem;">
                Rp {{ number_format($data['total_keuntungan'], 0, ',', '.') }}
            </div>
        </div>
    </div>
</div>

<div class="row g-3">

    {{-- Metode Pembayaran --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pie-chart"></i> Per Metode Pembayaran
            </div>
            <div class="card-body p-0">
                <table class="table mb-0" style="font-size:0.88rem;">
                    <thead>
                        <tr>
                            <th>Metode</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['metode_pembayaran'] as $m)
                        <tr>
                            <td>
                                <span class="badge badge-uio-info">{{ strtoupper($m->metode_pembayaran) }}</span>
                            </td>
                            <td>{{ $m->jumlah }}x</td>
                            <td>Rp {{ number_format($m->total, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-3" style="color: var(--uio-text-muted);">
                                Tidak ada data
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Daftar Transaksi --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-list-ul"></i> Daftar Transaksi — {{ $tanggal->format('d F Y') }}
            </div>
            <div class="card-body p-0">
                <table class="table mb-0" style="font-size:0.88rem;">
                    <thead>
                        <tr>
                            <th>No. Transaksi</th>
                            <th>Waktu</th>
                            <th>Total</th>
                            <th>Keuntungan</th>
                            <th>Metode</th>
                            <th>Kasir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['transaksi_list'] as $t)
                        <tr>
                            <td>
                                <a href="{{ route('transaksi.show', $t) }}">
                                    <code>{{ $t->no_transaksi }}</code>
                                </a>
                            </td>
                            <td style="color: var(--uio-text-muted);">
                                {{ $t->tanggal_transaksi->format('H:i') }}
                            </td>
                            <td><strong>Rp {{ number_format($t->total_harga, 0, ',', '.') }}</strong></td>
                            <td style="color: var(--uio-primary-dark);">
                                Rp {{ number_format($t->total_keuntungan, 0, ',', '.') }}
                            </td>
                            <td>
                                <span class="badge badge-uio-info">{{ strtoupper($t->metode_pembayaran) }}</span>
                            </td>
                            <td>{{ $t->user->nama_lengkap }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4" style="color: var(--uio-text-muted);">
                                <i class="bi bi-inbox"></i> Tidak ada transaksi pada tanggal ini
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
