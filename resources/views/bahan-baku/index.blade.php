@extends('layouts.app')

@section('title', 'Bahan Baku')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-box-seam"></i> Daftar Bahan Baku</h2>
    <a href="{{ route('bahan-baku.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle"></i> Tambah Bahan
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Bahan</th>
                    <th>Satuan</th>
                    <th>Stok Tersedia</th>
                    <th>Stok Minimum</th>
                    <th>Harga Beli</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bahanList as $bahan)
                <tr>
                    <td><code>{{ $bahan->kode_bahan }}</code></td>
                    <td><strong>{{ $bahan->nama_bahan }}</strong></td>
                    <td>
                        <span class="badge badge-uio-info">{{ $bahan->satuan }}</span>
                    </td>
                    <td>{{ number_format($bahan->stok_tersedia, 2, ',', '.') }}</td>
                    <td>{{ number_format($bahan->stok_minimum, 2, ',', '.') }}</td>
                    <td>Rp {{ number_format($bahan->harga_beli_per_satuan, 0, ',', '.') }}</td>
                    <td>
                        @if($bahan->isStokMenuipis())
                            <span class="badge badge-uio-danger">
                                <i class="bi bi-exclamation-triangle"></i> Menipis
                            </span>
                        @else
                            <span class="badge badge-uio-success">
                                <i class="bi bi-check-circle"></i> Normal
                            </span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('bahan-baku.show', $bahan) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('bahan-baku.edit', $bahan) }}"
                           class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('bahan-baku.destroy', $bahan) }}"
                              style="display:inline;"
                              onsubmit="return confirm('Yakin hapus bahan ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4" style="color: var(--uio-text-muted);">
                        <i class="bi bi-inbox"></i> Belum ada data bahan baku
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($bahanList->hasPages())
    <div class="card-footer" style="background: var(--uio-sidebar);">
        {{ $bahanList->links() }}
    </div>
    @endif
</div>

@endsection
