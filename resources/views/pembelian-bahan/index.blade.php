@extends('layouts.app')

@section('title', 'Pembelian Bahan')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-cart-plus"></i> Pembelian Bahan Baku</h2>
    <a href="{{ route('pembelian-bahan.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle"></i> Catat Pembelian
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Bahan Baku</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Total</th>
                    <th>Supplier</th>
                    <th>Oleh</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pembelianList as $p)
                <tr>
                    <td style="color: var(--uio-text-muted); font-size:0.85rem;">
                        {{ \Carbon\Carbon::parse($p->tanggal_beli)->format('d/m/Y') }}
                    </td>
                    <td><strong>{{ $p->bahanBaku->nama_bahan }}</strong></td>
                    <td>
                        {{ number_format($p->jumlah_beli, 2, ',', '.') }}
                        <span class="badge badge-uio-info">{{ $p->bahanBaku->satuan }}</span>
                    </td>
                    <td>Rp {{ number_format($p->harga_beli_satuan, 0, ',', '.') }}</td>
                    <td><strong>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</strong></td>
                    <td style="color: var(--uio-text-muted);">{{ $p->supplier ?? '-' }}</td>
                    <td style="font-size:0.85rem;">{{ $p->user->nama_lengkap }}</td>
                    <td>
                        <a href="{{ route('pembelian-bahan.show', $p) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4" style="color: var(--uio-text-muted);">
                        <i class="bi bi-inbox"></i> Belum ada pembelian bahan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pembelianList->hasPages())
    <div class="card-footer" style="background: var(--uio-sidebar);">
        {{ $pembelianList->links() }}
    </div>
    @endif
</div>

@endsection
