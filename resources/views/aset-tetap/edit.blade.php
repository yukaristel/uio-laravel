@extends('layouts.app')

@section('title', 'Edit Aset Tetap')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-pencil"></i> Edit Aset Tetap</h2>
    <a href="{{ route('aset-tetap.index') }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-building"></i> Form Edit Aset
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('aset-tetap.update', $asetTetap) }}">
                    @csrf @method('PUT')

                    <div class="row g-3">

                        <div class="col-md-12">
                            <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="nama_barang"
                                   class="form-control @error('nama_barang') is-invalid @enderror"
                                   value="{{ old('nama_barang', $asetTetap->nama_barang) }}">
                            @error('nama_barang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Jenis <span class="text-danger">*</span></label>
                            <select name="jenis"
                                    class="form-select @error('jenis') is-invalid @enderror">
                                @foreach(['Peralatan Dapur','Furniture','Elektronik','Lainnya'] as $j)
                                    <option value="{{ $j }}"
                                        {{ old('jenis', $asetTetap->jenis) === $j ? 'selected' : '' }}>
                                        {{ $j }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Metode Beli <span class="text-danger">*</span></label>
                            <select name="metode_beli"
                                    class="form-select @error('metode_beli') is-invalid @enderror">
                                @foreach(['tunai','transfer','kredit'] as $m)
                                    <option value="{{ $m }}"
                                        {{ old('metode_beli', $asetTetap->metode_beli) === $m ? 'selected' : '' }}>
                                        {{ ucfirst($m) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('metode_beli')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status"
                                    class="form-select @error('status') is-invalid @enderror">
                                @foreach(['Baik','Rusak','Maintenance'] as $s)
                                    <option value="{{ $s }}"
                                        {{ old('status', $asetTetap->status) === $s ? 'selected' : '' }}>
                                        {{ $s }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tanggal Beli <span class="text-danger">*</span></label>
                            <input type="date"
                                   name="tgl_beli"
                                   class="form-control @error('tgl_beli') is-invalid @enderror"
                                   value="{{ old('tgl_beli', $asetTetap->tgl_beli->format('Y-m-d')) }}">
                            @error('tgl_beli')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Jumlah Unit <span class="text-danger">*</span></label>
                            <input type="number"
                                   name="unit"
                                   class="form-control @error('unit') is-invalid @enderror"
                                   value="{{ old('unit', $asetTetap->unit) }}"
                                   min="1">
                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Harga Satuan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: var(--uio-bg); border-color: var(--uio-border);">Rp</span>
                                <input type="number"
                                       name="harsat"
                                       class="form-control @error('harsat') is-invalid @enderror"
                                       value="{{ old('harsat', $asetTetap->harsat) }}"
                                       step="1000" min="0">
                                @error('harsat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Umur Ekonomis (bulan)</label>
                            <input type="number"
                                   name="umur_ekonomis"
                                   class="form-control"
                                   value="{{ old('umur_ekonomis', $asetTetap->umur_ekonomis) }}"
                                   min="1">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tanggal Validasi</label>
                            <input type="date"
                                   name="tgl_validasi"
                                   class="form-control"
                                   value="{{ old('tgl_validasi', $asetTetap->tgl_validasi?->format('Y-m-d')) }}">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan"
                                      class="form-control"
                                      rows="2">{{ old('keterangan', $asetTetap->keterangan) }}</textarea>
                        </div>

                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update
                        </button>
                        <a href="{{ route('aset-tetap.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection
