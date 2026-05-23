@extends('layouts.app')

@section('title', 'Stock Movement')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-arrow-left-right"></i> Stock Movement</h2>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table mb-0" style="font-size:0.88rem;">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Bahan Baku</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Harga/Satuan</th>
                    <th>Total Nilai</th>
                    <th>Stok Sebelum</th>
                    <th>Stok Sesudah</th>
                    <th>Referensi</th>
                    <th>Oleh</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movements as $m)
                <tr>
                    <td style="color: var(--uio-text-muted);">
                        {{ $m->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td><strong>{{ $m->bahanBaku->nama_bahan }}</strong></td>
                    <td>
                        @if($m->jenis_pergerakan === 'masuk')
                            <span class="badge badge-uio-success">
                                <i class="bi bi-arrow-down"></i> Masuk
                            </span>
                        @elseif($m->jenis_pergerakan === 'keluar')
                            <span class="badge badge-uio-danger">
                                <i class="bi bi-arrow-up"></i> Keluar
                            </span>
                        @else
                            <span class="badge badge-uio-warning">
                                {{ ucfirst($m->jenis_pergerakan) }}
                            </span>
                        @endif
                    </td>
                    <td>{{ number_format($m->jumlah, 2, ',', '.') }} {{ $m->satuan }}</td>
                    <td>Rp {{ number_format($m->harga_per_satuan, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($m->total_nilai, 0, ',', '.') }}</td>
                    <td>{{ number_format($m->stok_sebelum, 2, ',', '.') }}</td>
                    <td>{{ number_format($m->stok_sesudah, 2, ',', '.') }}</td>
                    <td>
                        <span class="badge badge-uio-info">{{ $m->referensi_type }}</span>
                        @if($m->keterangan)
                            <div style="font-size:0.78rem; color: var(--uio-text-muted);">
                                {{ $m->keterangan }}
                            </div>
                        @endif
                    </td>
                    <td style="font-size:0.82rem;">{{ $m->user->nama_lengkap }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-4" style="color: var(--uio-text-muted);">
                        <i class="bi bi-inbox"></i> Belum ada pergerakan stok
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($movements->hasPages())
    <div class="card-footer" style="background: var(--uio-sidebar);">
        {{ $movements->links() }}
    </div>
    @endif
</div>

@endsection
