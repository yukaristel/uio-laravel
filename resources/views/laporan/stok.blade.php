@extends('layouts.app')

@section('title', 'Laporan Stok')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-box"></i> Laporan Stok</h2>
</div>

{{-- Summary --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #F5D4D4, #FAE8E8);">
            <div class="stat-label">Stok Menipis</div>
            <div class="stat-value">{{ $data['stok_menipis']->count() }} bahan</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #D4ECD9, #E8F5EA);">
            <div class="stat-label">Stok Normal</div>
            <div class="stat-value">{{ $data['stok_normal']->count() }} bahan</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #E0DBFF, #EDE8FF);">
            <div class="stat-label">Total Nilai Stok</div>
            <div class="stat-value" style="font-size:1.1rem;">
                Rp {{ number_format($data['total_nilai_stok'], 0, ',', '.') }}
            </div>
        </div>
    </div>
</div>

{{-- Stok Menipis --}}
@if($data['stok_menipis']->count() > 0)
<div class="card mb-3">
    <div class="card-header" style="background: #F5D4D4;">
        <i class="bi bi-exclamation-triangle"></i> Stok Menipis
    </div>
    <div class="card-body p-0">
        <table class="table mb-0" style="font-size:0.88rem;">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Bahan</th>
                    <th>Stok Tersedia</th>
                    <th>Stok Minimum</th>
                    <th>Satuan</th>
                    <th>Nilai Stok</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['stok_menipis'] as $b)
                <tr>
                    <td><code>{{ $b->kode_bahan }}</code></td>
                    <td><strong>{{ $b->nama_bahan }}</strong></td>
                    <td style="color: var(--uio-danger);">
                        <strong>{{ number_format($b->stok_tersedia, 2, ',', '.') }}</strong>
                    </td>
                    <td>{{ number_format($b->stok_minimum, 2, ',', '.') }}</td>
                    <td><span class="badge badge-uio-info">{{ $b->satuan }}</span></td>
                    <td>Rp {{ number_format($b->stok_tersedia * $b->harga_beli_per_satuan, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Stok Normal --}}
<div class="card">
    <div class="card-header">
        <i class="bi bi-check-circle"></i> Semua Bahan Baku
    </div>
    <div class="card-body p-0">
        <table class="table mb-0" style="font-size:0.88rem;">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Bahan</th>
                    <th>Stok Tersedia</th>
                    <th>Stok Minimum</th>
                    <th>Satuan</th>
                    <th>Harga Beli</th>
                    <th>Nilai Stok</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['stok_normal']->merge($data['stok_menipis'])->sortBy('nama_bahan') as $b)
                <tr>
                    <td><code>{{ $b->kode_bahan }}</code></td>
                    <td><strong>{{ $b->nama_bahan }}</strong></td>
                    <td>{{ number_format($b->stok_tersedia, 2, ',', '.') }}</td>
                    <td>{{ number_format($b->stok_minimum, 2, ',', '.') }}</td>
                    <td><span class="badge badge-uio-info">{{ $b->satuan }}</span></td>
                    <td>Rp {{ number_format($b->harga_beli_per_satuan, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($b->stok_tersedia * $b->harga_beli_per_satuan, 0, ',', '.') }}</td>
                    <td>
                        @if($b->isStokMenuipis())
                            <span class="badge badge-uio-danger">Menipis</span>
                        @else
                            <span class="badge badge-uio-success">Normal</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
