@extends('layouts.app')

@section('title', 'Edit Jurnal')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-pencil-square"></i> Edit Jurnal</h2>
    <a href="{{ route('jurnal.index') }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil"></i> Form Edit Jurnal
            </div>
            <div class="card-body">

                <div class="alert alert-warning d-flex gap-2 py-2 mb-3" style="font-size:0.85rem;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    Perubahan jurnal akan otomatis memperbarui saldo rekening terkait.
                </div>

                <form method="POST" action="{{ route('jurnal.update', $jurnal) }}">
                    @csrf @method('PUT')

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date"
                                   name="tgl_transaksi"
                                   class="form-control @error('tgl_transaksi') is-invalid @enderror"
                                   value="{{ old('tgl_transaksi', $jurnal->tgl_transaksi->format('Y-m-d')) }}">
                            @error('tgl_transaksi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: var(--uio-bg); border-color: var(--uio-border);">Rp</span>
                                <input type="number"
                                       name="jumlah"
                                       class="form-control @error('jumlah') is-invalid @enderror"
                                       value="{{ old('jumlah', $jurnal->jumlah) }}"
                                       step="1000" min="1">
                                @error('jumlah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Rekening Debet <span class="text-danger">*</span></label>
                            <select name="rekening_debet"
                                    class="form-select @error('rekening_debet') is-invalid @enderror">
                                @foreach($akunList as $akun)
                                    <option value="{{ $akun->kode_akun }}"
                                        {{ old('rekening_debet', $jurnal->rekening_debet) === $akun->kode_akun ? 'selected' : '' }}>
                                        {{ $akun->kode_akun }} — {{ $akun->nama_akun }}
                                    </option>
                                @endforeach
                            </select>
                            @error('rekening_debet')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Rekening Kredit <span class="text-danger">*</span></label>
                            <select name="rekening_kredit"
                                    class="form-select @error('rekening_kredit') is-invalid @enderror">
                                @foreach($akunList as $akun)
                                    <option value="{{ $akun->kode_akun }}"
                                        {{ old('rekening_kredit', $jurnal->rekening_kredit) === $akun->kode_akun ? 'selected' : '' }}>
                                        {{ $akun->kode_akun }} — {{ $akun->nama_akun }}
                                    </option>
                                @endforeach
                            </select>
                            @error('rekening_kredit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Keterangan <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="keterangan_transaksi"
                                   class="form-control @error('keterangan_transaksi') is-invalid @enderror"
                                   value="{{ old('keterangan_transaksi', $jurnal->keterangan_transaksi) }}">
                            @error('keterangan_transaksi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Jurnal
                        </button>
                        <a href="{{ route('jurnal.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection
