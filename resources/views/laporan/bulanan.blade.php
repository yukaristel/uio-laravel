@extends('layouts.app')

@section('title', 'Laporan Bulanan')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-calendar-month"></i> Laporan Bulanan</h2>
</div>

{{-- Filter --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('laporan.bulanan') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-select form-select-sm">
                    @foreach(range(1,12) as $b)
                        <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $b)->format('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select form-select-sm">
                    @foreach(range(now()->year, now()->year - 3) as $y)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
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

{{-- Daily Breakdown --}}
<div class="card">
    <div class="card-header">
        <i class="bi bi-table"></i> Rincian Per Hari
    </div>
    <div class="card-body p-0">
        <table class="table mb-0" style="font-size:0.88rem;">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jumlah Transaksi</th>
                    <th>Total Penjualan</th>
                    <th>Keuntungan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['daily_breakdown'] as $d)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($d->tanggal)->format('d F Y') }}</td>
                    <td>{{ $d->jumlah_transaksi }}x</td>
                    <td><strong>Rp {{ number_format($d->total_penjualan, 0, ',', '.') }}</strong></td>
                    <td style="color: var(--uio-primary-dark);">
                        Rp {{ number_format($d->keuntungan, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4" style="color: var(--uio-text-muted);">
                        <i class="bi bi-inbox"></i> Tidak ada data untuk periode ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
