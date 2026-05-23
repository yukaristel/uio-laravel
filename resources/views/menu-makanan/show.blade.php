@extends('layouts.app')

@section('title', 'Detail Menu Makanan')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-egg-fried"></i> Detail Menu Makanan</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('menu-makanan.edit', $menuMakanan) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('menu-makanan.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row g-3">

    {{-- Info Menu --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-info-circle"></i> Informasi Menu
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0" style="font-size:0.9rem;">
                    <tr>
                        <td style="color: var(--uio-text-muted); width:45%;">Kode</td>
                        <td><code>{{ $menuMakanan->kode_menu }}</code></td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Nama</td>
                        <td><strong>{{ $menuMakanan->nama_menu }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Kategori</td>
                        <td><span class="badge badge-uio-info">{{ $menuMakanan->kategori->nama_kategori }}</span></td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Harga Modal</td>
                        <td>Rp {{ number_format($menuMakanan->harga_modal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Harga Jual</td>
                        <td><strong>Rp {{ number_format($menuMakanan->harga_jual, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Margin</td>
                        <td style="color: var(--uio-primary-dark);">
                            <strong>Rp {{ number_format($menuMakanan->margin_keuntungan, 0, ',', '.') }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Status</td>
                        <td>
                            @if($menuMakanan->status === 'tersedia')
                                <span class="badge badge-uio-success">Tersedia</span>
                            @elseif($menuMakanan->status === 'habis')
                                <span class="badge badge-uio-danger">Habis</span>
                            @else
                                <span class="badge badge-uio-warning">Tidak Tersedia</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Resep --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-journal-text"></i> Resep / Komposisi Bahan</span>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0" style="font-size:0.88rem;">
                    <thead>
                        <tr>
                            <th>Bahan Baku</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                            <th>Biaya Bahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($menuMakanan->reseps as $resep)
                        <tr>
                            <td><strong>{{ $resep->bahanBaku->nama_bahan }}</strong></td>
                            <td>{{ number_format($resep->jumlah_bahan, 2, ',', '.') }}</td>
                            <td><span class="badge badge-uio-info">{{ $resep->satuan }}</span></td>
                            <td>Rp {{ number_format($resep->biaya_bahan, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4" style="color: var(--uio-text-muted);">
                                <i class="bi bi-inbox"></i> Belum ada resep untuk menu ini
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($menuMakanan->reseps->count() > 0)
                    <tfoot>
                        <tr style="background: var(--uio-bg);">
                            <td colspan="3" class="text-end"><strong>Total Biaya Bahan</strong></td>
                            <td>
                                <strong style="color: var(--uio-primary-dark);">
                                    Rp {{ number_format($menuMakanan->reseps->sum('biaya_bahan'), 0, ',', '.') }}
                                </strong>
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

</div>

@endsection
