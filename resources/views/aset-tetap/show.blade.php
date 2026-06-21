@extends('layouts.app')

@section('title', 'Detail Aset Tetap')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-building"></i> Detail Aset Tetap</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('aset-tetap.edit', $asetTetap) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('aset-tetap.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row g-3">

    {{-- Info Aset --}}
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-info-circle"></i> Informasi Aset
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0" style="font-size:0.9rem;">
                    <tr>
                        <td style="color: var(--uio-text-muted); width:45%;">Nama Barang</td>
                        <td><strong>{{ $asetTetap->nama_barang }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Jenis</td>
                        <td><span class="badge badge-uio-info">{{ $asetTetap->jenis }}</span></td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Tanggal Beli</td>
                        <td>{{ $asetTetap->tgl_beli->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Unit</td>
                        <td>{{ $asetTetap->unit }} unit</td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Harga Satuan</td>
                        <td>Rp {{ number_format($asetTetap->harsat, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Nilai Total</td>
                        <td><strong>Rp {{ number_format($asetTetap->nilai_total, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Umur Ekonomis</td>
                        <td>{{ $asetTetap->umur_ekonomis ?? '-' }} bulan</td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Akumulasi Susut</td>
                        <td>Rp {{ number_format($asetTetap->akumulasi_penyusutan, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Nilai Buku</td>
                        <td>
                            <strong style="color: var(--uio-primary-dark);">
                                Rp {{ number_format($asetTetap->nilai_buku, 0, ',', '.') }}
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Status</td>
                        <td>
                            @if($asetTetap->status === 'Baik')
                                <span class="badge badge-uio-success">Baik</span>
                            @elseif($asetTetap->status === 'Maintenance')
                                <span class="badge badge-uio-warning">Maintenance</span>
                            @elseif($asetTetap->status === 'Rusak')
                                <span class="badge badge-uio-danger">Rusak</span>
                            @else
                                <span class="badge badge-uio-info">{{ $asetTetap->status }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Metode Beli</td>
                        <td>{{ ucfirst($asetTetap->metode_beli) }}</td>
                    </tr>
                    @if($asetTetap->keterangan)
                    <tr>
                        <td style="color: var(--uio-text-muted);">Keterangan</td>
                        <td>{{ $asetTetap->keterangan }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    {{-- Aksi Aset --}}
    <div class="col-md-7">

        {{-- Penyusutan --}}
        @if(in_array($asetTetap->status, ['Baik', 'Maintenance']) && $asetTetap->umur_ekonomis)
        <div class="card mb-3">
            <div class="card-header">
                <i class="bi bi-graph-down"></i> Catat Penyusutan
            </div>
            <div class="card-body">
                <p style="font-size:0.88rem; color: var(--uio-text-muted);">
                    Beban penyusutan per bulan:
                    <strong>Rp {{ number_format(($asetTetap->nilai_total / $asetTetap->umur_ekonomis), 0, ',', '.') }}</strong>
                </p>
                <form method="POST" action="{{ route('aset-tetap.penyusutan', $asetTetap) }}">
                    @csrf
                    <div class="row g-2 align-items-end">
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Penyusutan</label>
                            <input type="date" name="tanggal" class="form-control"
                                   value="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary w-100"
                                    onclick="return confirm('Catat penyusutan bulan ini?')">
                                <i class="bi bi-save"></i> Catat Penyusutan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif

        {{-- Pelepasan Aset --}}
        @if(in_array($asetTetap->status, ['Baik', 'Rusak', 'Maintenance']))
        <div class="card">
            <div class="card-header">
                <i class="bi bi-box-arrow-right"></i> Pelepasan Aset
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('aset-tetap.lepas', $asetTetap) }}">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label">Status Baru</label>
                            <select name="status_baru" class="form-select" id="statusBaru">
                                <option value="Dijual">Dijual</option>
                                <option value="Hapus">Dihapus</option>
                            </select>
                        </div>
                        <div class="col-md-4" id="hargaJualWrap">
                            <label class="form-label">Harga Jual</label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: var(--uio-bg); border-color: var(--uio-border);">Rp</span>
                                <input type="number" name="harga_jual" class="form-control"
                                       value="0" step="any" min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control"
                                   value="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-outline-danger"
                                    onclick="return confirm('Yakin lepas aset ini? Jurnal akan otomatis dicatat!')">
                                <i class="bi bi-box-arrow-right"></i> Proses Pelepasan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif

    </div>

</div>

@endsection

@push('scripts')
<script>
document.getElementById('statusBaru').addEventListener('change', function() {
    document.getElementById('hargaJualWrap').style.display =
        this.value === 'Dijual' ? 'block' : 'none';
});
</script>
@endpush
