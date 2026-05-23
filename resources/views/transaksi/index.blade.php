@extends('layouts.app')

@section('title', 'Transaksi Penjualan')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-cart3"></i> Transaksi Penjualan</h2>
    <a href="{{ route('transaksi.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle"></i> Transaksi Baru
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>No. Transaksi</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Modal</th>
                    <th>Keuntungan</th>
                    <th>Metode</th>
                    <th>Kasir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksiList as $t)
                <tr>
                    <td><code>{{ $t->no_transaksi }}</code></td>
                    <td style="font-size:0.85rem; color: var(--uio-text-muted);">
                        {{ $t->tanggal_transaksi->format('d/m/Y H:i') }}
                    </td>
                    <td><strong>Rp {{ number_format($t->total_harga, 0, ',', '.') }}</strong></td>
                    <td>Rp {{ number_format($t->total_modal, 0, ',', '.') }}</td>
                    <td style="color: {{ $t->total_keuntungan >= 0 ? 'var(--uio-primary-dark)' : 'var(--uio-danger)' }};">
                        <strong>Rp {{ number_format($t->total_keuntungan, 0, ',', '.') }}</strong>
                    </td>
                    <td>
                        <span class="badge badge-uio-info">{{ strtoupper($t->metode_pembayaran) }}</span>
                    </td>
                    <td style="font-size:0.85rem;">{{ $t->user->nama_lengkap }}</td>
                    <td>
                        <a href="{{ route('transaksi.show', $t) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('transaksi.struk', $t) }}"
                           class="btn btn-sm btn-outline-secondary" target="_blank">
                            <i class="bi bi-printer"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4" style="color: var(--uio-text-muted);">
                        <i class="bi bi-inbox"></i> Belum ada transaksi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($transaksiList->hasPages())
    <div class="card-footer" style="background: var(--uio-sidebar);">
        {{ $transaksiList->links() }}
    </div>
    @endif
</div>

@endsection
