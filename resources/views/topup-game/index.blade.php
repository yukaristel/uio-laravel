@extends('layouts.app')

@section('title', 'Riwayat Top-Up Game')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-controller"></i> Riwayat Top-Up Game</h2>
    <a href="{{ route('topup-game.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle"></i> Top-Up Baru
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>No. Transaksi</th>
                    <th>Tanggal</th>
                    <th>Game</th>
                    <th>Item</th>
                    <th>Jumlah</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Keuntungan</th>
                    <th>ID Pelanggan</th>
                    <th>Oleh</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topupList as $t)
                <tr>
                    <td><code>{{ $t->no_transaksi }}</code></td>
                    <td style="font-size:0.85rem; color: var(--uio-text-muted);">
                        {{ $t->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td>
                        <span class="badge badge-uio-info">{{ $t->nama_game }}</span>
                    </td>
                    <td>{{ $t->nama_item }}</td>
                    <td>{{ number_format($t->jumlah_item, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($t->harga_beli, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($t->harga_jual, 0, ',', '.') }}</td>
                    <td style="color: {{ $t->keuntungan >= 0 ? 'var(--uio-primary-dark)' : 'var(--uio-danger)' }};">
                        <strong>Rp {{ number_format($t->keuntungan, 0, ',', '.') }}</strong>
                    </td>
                    <td style="font-size:0.85rem;">{{ $t->id_pelanggan ?? '-' }}</td>
                    <td style="font-size:0.85rem;">{{ $t->user->nama_lengkap }}</td>
                    <td>
                        <a href="{{ route('topup-game.show', $t) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="text-center py-4" style="color: var(--uio-text-muted);">
                        <i class="bi bi-inbox"></i> Belum ada transaksi top-up
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($topupList->hasPages())
    <div class="card-footer" style="background: var(--uio-sidebar);">
        {{ $topupList->links() }}
    </div>
    @endif
</div>

@endsection
