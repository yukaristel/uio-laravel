@extends('layouts.app')

@section('title', 'Tambah Menu Makanan')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-plus-circle"></i> Tambah Menu Makanan</h2>
    <a href="{{ route('menu-makanan.index') }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-egg-fried"></i> Form Tambah Menu
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('menu-makanan.store') }}">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label">Kode Menu <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="kode_menu"
                                   class="form-control @error('kode_menu') is-invalid @enderror"
                                   value="{{ old('kode_menu') }}"
                                   placeholder="contoh: MNU001">
                            @error('kode_menu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">Nama Menu <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="nama_menu"
                                   class="form-control @error('nama_menu') is-invalid @enderror"
                                   value="{{ old('nama_menu') }}"
                                   placeholder="contoh: Nasi Goreng Spesial">
                            @error('nama_menu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori_id"
                                    class="form-select @error('kategori_id') is-invalid @enderror">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoriList as $kategori)
                                    <option value="{{ $kategori->id }}"
                                        {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status"
                                    class="form-select @error('status') is-invalid @enderror">
                                <option value="tersedia" {{ old('status') === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="habis" {{ old('status') === 'habis' ? 'selected' : '' }}>Habis</option>
                                <option value="tidak_tersedia" {{ old('status') === 'tidak_tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Harga Modal</label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: var(--uio-bg); border-color: var(--uio-border);">Rp</span>
                                <input type="number"
                                       name="harga_modal"
                                       class="form-control @error('harga_modal') is-invalid @enderror"
                                       value="{{ old('harga_modal', 0) }}"
                                       step="100" min="0">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Harga Jual <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: var(--uio-bg); border-color: var(--uio-border);">Rp</span>
                                <input type="number"
                                       name="harga_jual"
                                       class="form-control @error('harga_jual') is-invalid @enderror"
                                       value="{{ old('harga_jual', 0) }}"
                                       step="100" min="0">
                                @error('harga_jual')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Margin Keuntungan</label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: var(--uio-bg); border-color: var(--uio-border);">Rp</span>
                                <input type="number"
                                       name="margin_keuntungan"
                                       class="form-control"
                                       value="{{ old('margin_keuntungan', 0) }}"
                                       step="100">
                            </div>
                        </div>

                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan
                        </button>
                        <a href="{{ route('menu-makanan.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection
