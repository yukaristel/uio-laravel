@extends('layouts.app')

@section('title', 'Catat Pembelian Bahan')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-cart-plus"></i> Catat Pembelian Bahan</h2>
    <a href="{{ route('pembelian-bahan.index') }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-bag-plus"></i> Form Pembelian Bahan
            </div>
            <div class="card-body">

                <div class="alert alert-success d-flex gap-2 py-2 mb-3" style="font-size:0.85rem;">
                    <i class="bi bi-info-circle-fill"></i>
                    Pembelian akan otomatis menambah stok bahan & mencatat jurnal akuntansi.
                </div>

                <form method="POST" action="{{ route('pembelian-bahan.store') }}">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Beli <span class="text-danger">*</span></label>
                            <input type="date"
                                   name="tanggal_beli"
                                   class="form-control @error('tanggal_beli') is-invalid @enderror"
                                   value="{{ old('tanggal_beli', now()->format('Y-m-d')) }}">
                            @error('tanggal_beli')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Supplier</label>
                            <input type="text"
                                   name="supplier"
                                   class="form-control"
                                   value="{{ old('supplier') }}"
                                   placeholder="Nama supplier (opsional)">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Bahan Baku <span class="text-danger">*</span></label>
                            <select name="bahan_id"
                                    class="form-select @error('bahan_id') is-invalid @enderror"
                                    id="bahanSelect">
                                <option value="">-- Pilih Bahan --</option>
                                @foreach($bahanList as $bahan)
                                    <option value="{{ $bahan->id }}"
                                            data-satuan="{{ $bahan->satuan }}"
                                            data-harga="{{ $bahan->harga_beli_per_satuan }}"
                                            {{ old('bahan_id') == $bahan->id ? 'selected' : '' }}>
                                        {{ $bahan->nama_bahan }} (Stok: {{ $bahan->stok_tersedia }} {{ $bahan->satuan }})
                                    </option>
                                @endforeach
                            </select>
                            @error('bahan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Jumlah Beli <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number"
                                       name="jumlah_beli"
                                       id="jumlahBeli"
                                       class="form-control @error('jumlah_beli') is-invalid @enderror"
                                       value="{{ old('jumlah_beli', 0) }}"
                                       step="0.01" min="0.01">
                                <span class="input-group-text" id="satuanLabel"
                                      style="background: var(--uio-bg); border-color: var(--uio-border);">
                                    -
                                </span>
                                @error('jumlah_beli')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Total Harga <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"
                                      style="background: var(--uio-bg); border-color: var(--uio-border);">Rp</span>
                                <input type="number"
                                       id="totalHargaInput"
                                       class="form-control"
                                       value="{{ old('total_harga', 0) }}"
                                       step="100" min="0">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Harga Beli/Satuan</label>
                            <div class="input-group">
                                <span class="input-group-text"
                                      style="background: var(--uio-bg); border-color: var(--uio-border);">Rp</span>
                                <input type="number"
                                       name="harga_beli_satuan"
                                       id="hargaBeli"
                                       class="form-control @error('harga_beli_satuan') is-invalid @enderror"
                                       value="{{ old('harga_beli_satuan', 0) }}"
                                       readonly
                                       style="background: var(--uio-bg); font-weight:600;">
                                @error('harga_beli_satuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Rekening Pembayaran <span class="text-danger">*</span></label>
                            <select name="rekening_bayar"
                                    class="form-select @error('rekening_bayar') is-invalid @enderror">
                                <option value="">-- Pilih Rekening --</option>
                                <option value="1.1.01.01" {{ old('rekening_bayar') === '1.1.01.01' ? 'selected' : '' }}>
                                    1.1.01.01 — Kas Tunai
                                </option>
                                <option value="1.1.01.02" {{ old('rekening_bayar') === '1.1.01.02' ? 'selected' : '' }}>
                                    1.1.01.02 — Kas QRIS
                                </option>
                                <option value="1.1.02.01" {{ old('rekening_bayar') === '1.1.02.01' ? 'selected' : '' }}>
                                    1.1.02.01 — Bank Mandiri
                                </option>
                                <option value="2.1.01.01" {{ old('rekening_bayar') === '2.1.01.01' ? 'selected' : '' }}>
                                    2.1.01.01 — Utang Supplier (Kredit)
                                </option>
                            </select>
                            @error('rekening_bayar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan & Catat Jurnal
                        </button>
                        <a href="{{ route('pembelian-bahan.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function hitungHargaSatuan() {
    const jumlah = parseFloat(document.getElementById('jumlahBeli').value) || 0;
    const total  = parseFloat(document.getElementById('totalHargaInput').value) || 0;
    const harga  = jumlah > 0 ? Math.round(total / jumlah) : 0;
    document.getElementById('hargaBeli').value = harga;
}

document.getElementById('bahanSelect').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    document.getElementById('satuanLabel').textContent = opt.dataset.satuan || '-';
    hitungHargaSatuan();
});

document.getElementById('jumlahBeli').addEventListener('input', hitungHargaSatuan);
document.getElementById('totalHargaInput').addEventListener('input', hitungHargaSatuan);
</script>
@endpush
