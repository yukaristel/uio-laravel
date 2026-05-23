@extends('layouts.app')

@section('title', 'Tambah Bahan Baku')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-plus-circle"></i> Tambah Bahan Baku</h2>
    <a href="{{ route('bahan-baku.index') }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-box-seam"></i> Form Tambah Bahan Baku
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('bahan-baku.store') }}">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Kode Bahan <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="kode_bahan"
                                   class="form-control @error('kode_bahan') is-invalid @enderror"
                                   value="{{ old('kode_bahan') }}"
                                   placeholder="contoh: BHN001">
                            @error('kode_bahan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nama Bahan <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="nama_bahan"
                                   class="form-control @error('nama_bahan') is-invalid @enderror"
                                   value="{{ old('nama_bahan') }}"
                                   placeholder="contoh: Tepung Terigu">
                            @error('nama_bahan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Satuan <span class="text-danger">*</span></label>
                            <select name="satuan"
                                    class="form-select @error('satuan') is-invalid @enderror">
                                <option value="">-- Pilih --</option>
                                @foreach(['kg','gram','liter','ml','pcs','sachet'] as $s)
                                    <option value="{{ $s }}" {{ old('satuan') === $s ? 'selected' : '' }}>
                                        {{ $s }}
                                    </option>
                                @endforeach
                            </select>
                            @error('satuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Stok Tersedia <span class="text-danger">*</span></label>
                            <input type="number"
                                   name="stok_tersedia"
                                   class="form-control @error('stok_tersedia') is-invalid @enderror"
                                   value="{{ old('stok_tersedia', 0) }}"
                                   step="0.01" min="0">
                            @error('stok_tersedia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Stok Minimum <span class="text-danger">*</span></label>
                            <input type="number"
                                   name="stok_minimum"
                                   class="form-control @error('stok_minimum') is-invalid @enderror"
                                   value="{{ old('stok_minimum', 0) }}"
                                   step="0.01" min="0">
                            @error('stok_minimum')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Harga Beli per Satuan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"
                                      style="background: var(--uio-bg); border-color: var(--uio-border);">
                                    Rp
                                </span>
                                <input type="number"
                                       name="harga_beli_per_satuan"
                                       class="form-control @error('harga_beli_per_satuan') is-invalid @enderror"
                                       value="{{ old('harga_beli_per_satuan', 0) }}"
                                       step="100" min="0">
                                @error('harga_beli_per_satuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan
                        </button>
                        <a href="{{ route('bahan-baku.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection
