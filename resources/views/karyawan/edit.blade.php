@extends('layouts.app')

@section('title', 'Edit Karyawan')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-person-gear"></i> Edit Karyawan</h2>
    <a href="{{ route('karyawan.index') }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-person"></i> Form Edit — <code>{{ $karyawan->username }}</code>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('karyawan.update', $karyawan) }}">
                    @csrf @method('PUT')

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="nama_lengkap"
                                   class="form-control @error('nama_lengkap') is-invalid @enderror"
                                   value="{{ old('nama_lengkap', $karyawan->nama_lengkap) }}">
                            @error('nama_lengkap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="username"
                                   class="form-control @error('username') is-invalid @enderror"
                                   value="{{ old('username', $karyawan->username) }}">
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email"
                                   name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $karyawan->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role"
                                    class="form-select @error('role') is-invalid @enderror">
                                <option value="karyawan" {{ old('role', $karyawan->role) === 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                                <option value="kasir" {{ old('role', $karyawan->role) === 'kasir' ? 'selected' : '' }}>Kasir</option>
                                <option value="admin" {{ old('role', $karyawan->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Password Baru</label>
                            <input type="password"
                                   name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Kosongkan jika tidak diubah">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password"
                                   name="password_confirmation"
                                   class="form-control"
                                   placeholder="Ulangi password baru">
                        </div>

                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update
                        </button>
                        <a href="{{ route('karyawan.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection
