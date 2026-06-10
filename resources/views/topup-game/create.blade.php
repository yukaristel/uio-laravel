@extends('layouts.app')

@section('title', 'Top-Up Game')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-controller"></i> Top-Up Game</h2>
    <a href="{{ route('topup-game.index') }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-joystick"></i> Form Top-Up Game
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('topup-game.store') }}">
                    @csrf

                    <div class="row g-3">

                        {{-- Game --}}
                        <div class="col-md-6">
                            <label class="form-label">Game <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="nama_game" id="namaGame"
                                        class="form-select @error('nama_game') is-invalid @enderror">
                                    <option value="">-- Pilih Game --</option>
                                    @foreach($gameList as $game)
                                        <option value="{{ $game }}" {{ old('nama_game') === $game ? 'selected' : '' }}>
                                            {{ $game }}
                                        </option>
                                    @endforeach
                                    <option value="__custom__">+ Tambah Game Baru</option>
                                </select>
                            </div>
                            <input type="text" id="namaGameCustom"
                                   class="form-control mt-2"
                                   placeholder="Ketik nama game baru..."
                                   style="display:none;">
                            @error('nama_game')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Item --}}
                        <div class="col-md-6">
                            <label class="form-label">Nama Item <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="nama_item"
                                   class="form-control @error('nama_item') is-invalid @enderror"
                                   value="{{ old('nama_item') }}"
                                   placeholder="contoh: 12 Diamond, 86 UC">
                            @error('nama_item')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Jumlah Item --}}
                        <div class="col-md-4">
                            <label class="form-label">Jumlah Item <span class="text-danger">*</span></label>
                            <input type="number"
                                   name="jumlah_item"
                                   class="form-control @error('jumlah_item') is-invalid @enderror"
                                   value="{{ old('jumlah_item', 0) }}"
                                   step="any" min="0">
                            @error('jumlah_item')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Harga Beli --}}
                        <div class="col-md-4">
                            <label class="form-label">Harga Beli (Modal) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: var(--uio-bg); border-color: var(--uio-border);">Rp</span>
                                <input type="number"
                                       name="harga_beli"
                                       id="hargaBeli"
                                       class="form-control @error('harga_beli') is-invalid @enderror"
                                       value="{{ old('harga_beli', 0) }}"
                                       step="any" min="0">
                            </div>
                            @error('harga_beli')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Harga Jual --}}
                        <div class="col-md-4">
                            <label class="form-label">Harga Jual <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: var(--uio-bg); border-color: var(--uio-border);">Rp</span>
                                <input type="number"
                                       name="harga_jual"
                                       id="hargaJual"
                                       class="form-control @error('harga_jual') is-invalid @enderror"
                                       value="{{ old('harga_jual', 0) }}"
                                       step="any" min="0">
                            </div>
                            @error('harga_jual')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Keuntungan Preview --}}
                        <div class="col-md-12">
                            <div class="p-3 rounded" style="background: var(--uio-bg); border: 1px solid var(--uio-border);">
                                <div class="d-flex justify-content-between">
                                    <span style="color: var(--uio-text-muted);">Keuntungan</span>
                                    <strong id="previewKeuntungan" style="color: var(--uio-primary-dark);">Rp 0</strong>
                                </div>
                            </div>
                        </div>

                        {{-- Rekening Beli --}}
                        <div class="col-md-6">
                            <label class="form-label">Dibeli Via (Kas Keluar) <span class="text-danger">*</span></label>
                            <select name="rekening_beli"
                                    class="form-select @error('rekening_beli') is-invalid @enderror">
                                <option value="">-- Pilih Rekening --</option>
                                @foreach($kasAkun as $akun)
                                    <option value="{{ $akun->kode_akun }}"
                                        {{ old('rekening_beli') === $akun->kode_akun ? 'selected' : '' }}>
                                        {{ $akun->kode_akun }} — {{ $akun->nama_akun }}
                                    </option>
                                @endforeach
                            </select>
                            @error('rekening_beli')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Rekening Bayar --}}
                        <div class="col-md-6">
                            <label class="form-label">Dibayar Via (Kas Masuk) <span class="text-danger">*</span></label>
                            <select name="rekening_bayar"
                                    class="form-select @error('rekening_bayar') is-invalid @enderror">
                                <option value="">-- Pilih Rekening --</option>
                                @foreach($kasAkun as $akun)
                                    <option value="{{ $akun->kode_akun }}"
                                        {{ old('rekening_bayar') === $akun->kode_akun ? 'selected' : '' }}>
                                        {{ $akun->kode_akun }} — {{ $akun->nama_akun }}
                                    </option>
                                @endforeach
                            </select>
                            @error('rekening_bayar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ID Pelanggan --}}
                        <div class="col-md-6">
                            <label class="form-label">ID Pelanggan</label>
                            <input type="text"
                                   name="id_pelanggan"
                                   class="form-control"
                                   value="{{ old('id_pelanggan') }}"
                                   placeholder="ID game pelanggan (opsional)">
                        </div>

                        {{-- Keterangan --}}
                        <div class="col-md-6">
                            <label class="form-label">Keterangan</label>
                            <input type="text"
                                   name="keterangan"
                                   class="form-control"
                                   value="{{ old('keterangan') }}"
                                   placeholder="Keterangan tambahan (opsional)">
                        </div>

                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Proses Top-Up
                        </button>
                        <a href="{{ route('topup-game.index') }}" class="btn btn-outline-secondary">
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
// Preview keuntungan
function hitungKeuntungan() {
    const beli = parseInt(document.getElementById('hargaBeli').value) || 0;
    const jual = parseInt(document.getElementById('hargaJual').value) || 0;
    const untung = jual - beli;
    const el = document.getElementById('previewKeuntungan');
    el.textContent = 'Rp ' + untung.toLocaleString('id-ID');
    el.style.color = untung >= 0 ? 'var(--uio-primary-dark)' : 'var(--uio-danger)';
}

document.getElementById('hargaBeli').addEventListener('input', hitungKeuntungan);
document.getElementById('hargaJual').addEventListener('input', hitungKeuntungan);

// Game custom
document.getElementById('namaGame').addEventListener('change', function() {
    const custom = document.getElementById('namaGameCustom');
    if (this.value === '__custom__') {
        custom.style.display = 'block';
        custom.setAttribute('name', 'nama_game');
        this.removeAttribute('name');
    } else {
        custom.style.display = 'none';
        custom.removeAttribute('name');
        this.setAttribute('name', 'nama_game');
    }
});
</script>
@endpush
