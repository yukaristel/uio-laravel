@extends('layouts.app')

@section('title', 'Detail Bahan Baku')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-box-seam"></i> Detail Bahan Baku</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('bahan-baku.edit', $bahanBaku) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('bahan-baku.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row g-3">

    {{-- Info Bahan --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-info-circle"></i> Informasi Bahan
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0" style="font-size:0.9rem;">
                    <tr>
                        <td style="color: var(--uio-text-muted); width:45%;">Kode</td>
                        <td><code>{{ $bahanBaku->kode_bahan }}</code></td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Nama</td>
                        <td><strong>{{ $bahanBaku->nama_bahan }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Satuan</td>
                        <td><span class="badge badge-uio-info">{{ $bahanBaku->satuan }}</span></td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Stok Tersedia</td>
                        <td>
                            <strong class="{{ $bahanBaku->isStokMenuipis() ? 'text-danger' : '' }}">
                                {{ number_format($bahanBaku->stok_tersedia, 2, ',', '.') }}
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Stok Minimum</td>
                        <td>{{ number_format($bahanBaku->stok_minimum, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Harga Beli</td>
                        <td>Rp {{ number_format($bahanBaku->harga_beli_per_satuan, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Status</td>
                        <td>
                            @if($bahanBaku->isStokMenuipis())
                                <span class="badge badge-uio-danger">
                                    <i class="bi bi-exclamation-triangle"></i> Menipis
                                </span>
                            @else
                                <span class="badge badge-uio-success">
                                    <i class="bi bi-check-circle"></i> Normal
                                </span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Riwayat Stok --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history"></i> Riwayat Pergerakan Stok
            </div>
            <div class="card-body p-0">
                <table class="table mb-0" style="font-size:0.88rem;">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Stok Sebelum</th>
                            <th>Stok Sesudah</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($history as $h)
                        <tr>
                            <td style="color: var(--uio-text-muted);">
                                {{ $h->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td>
                                @if($h->jenis_pergerakan === 'masuk')
                                    <span class="badge badge-uio-success">
                                        <i class="bi bi-arrow-down"></i> Masuk
                                    </span>
                                @elseif($h->jenis_pergerakan === 'keluar')
                                    <span class="badge badge-uio-danger">
                                        <i class="bi bi-arrow-up"></i> Keluar
                                    </span>
                                @else
                                    <span class="badge badge-uio-warning">
                                        {{ ucfirst($h->jenis_pergerakan) }}
                                    </span>
                                @endif
                            </td>
                            <td>{{ number_format($h->jumlah, 2, ',', '.') }} {{ $h->satuan }}</td>
                            <td>{{ number_format($h->stok_sebelum, 2, ',', '.') }}</td>
                            <td>{{ number_format($h->stok_sesudah, 2, ',', '.') }}</td>
                            <td style="font-size:0.82rem; color: var(--uio-text-muted);">
                                {{ $h->keterangan ?? '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4" style="color: var(--uio-text-muted);">
                                <i class="bi bi-inbox"></i> Belum ada riwayat pergerakan stok
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($history->hasPages())
            <div class="card-footer" style="background: var(--uio-sidebar);">
                {{ $history->links() }}
            </div>
            @endif
        </div>
    </div>

</div>

@endsection
