@extends('layouts.app')

@section('title', 'Aset Tetap')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-building"></i> Aset Tetap</h2>
    <a href="{{ route('aset-tetap.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle"></i> Tambah Aset
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Jenis</th>
                    <th>Tgl Beli</th>
                    <th>Nilai Total</th>
                    <th>Akumulasi Susut</th>
                    <th>Nilai Buku</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($asetList as $aset)
                <tr>
                    <td><strong>{{ $aset->nama_barang }}</strong></td>
                    <td><span class="badge badge-uio-info">{{ $aset->jenis }}</span></td>
                    <td style="font-size:0.85rem; color: var(--uio-text-muted);">
                        {{ $aset->tgl_beli->format('d/m/Y') }}
                    </td>
                    <td>Rp {{ number_format($aset->nilai_total, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($aset->akumulasi_penyusutan, 0, ',', '.') }}</td>
                    <td><strong>Rp {{ number_format($aset->nilai_buku, 0, ',', '.') }}</strong></td>
                    <td>
                        @if($aset->status === 'Baik')
                            <span class="badge badge-uio-success">Baik</span>
                        @elseif($aset->status === 'Maintenance')
                            <span class="badge badge-uio-warning">Maintenance</span>
                        @elseif($aset->status === 'Rusak')
                            <span class="badge badge-uio-danger">Rusak</span>
                        @else
                            <span class="badge badge-uio-info">{{ $aset->status }}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('aset-tetap.show', $aset) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('aset-tetap.edit', $aset) }}"
                           class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('aset-tetap.destroy', $aset) }}"
                              style="display:inline;"
                              onsubmit="return confirm('Yakin hapus aset ini?')">
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
                        <i class="bi bi-inbox"></i> Belum ada aset tetap
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($asetList->hasPages())
    <div class="card-footer" style="background: var(--uio-sidebar);">
        {{ $asetList->links() }}
    </div>
    @endif
</div>

@endsection
