@extends('layouts.app')

@section('title', 'Edit Kategori Menu')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-pencil"></i> Edit Kategori Menu</h2>
    <a href="{{ route('kategori-menu.index') }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-tags"></i> Form Edit Kategori
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('kategori-menu.update', $kategoriMenu) }}">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text"
                               name="nama_kategori"
                               class="form-control @error('nama_kategori') is-invalid @enderror"
                               value="{{ old('nama_kategori', $kategoriMenu->nama_kategori) }}">
                        @error('nama_kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi"
                                  class="form-control @error('deskripsi') is-invalid @enderror"
                                  rows="3">{{ old('deskripsi', $kategoriMenu->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update
                        </button>
                        <a href="{{ route('kategori-menu.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection
