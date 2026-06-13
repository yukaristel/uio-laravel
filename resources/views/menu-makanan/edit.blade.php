@extends('layouts.app')

@section('title', 'Edit Menu Makanan')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-pencil"></i> Edit Menu Makanan</h2>
    <a href="{{ route('menu-makanan.index') }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<form method="POST" action="{{ route('menu-makanan.update', $menuMakanan) }}">
    @csrf @method('PUT')

    <div class="row g-3">

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-egg-fried"></i> Form Edit — <code>{{ $menuMakanan->kode_menu }}</code>
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label">Kode Menu <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="kode_menu"
                                   class="form-control @error('kode_menu') is-invalid @enderror"
                                   value="{{ old('kode_menu', $menuMakanan->kode_menu) }}">
                            @error('kode_menu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">Nama Menu <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="nama_menu"
                                   class="form-control @error('nama_menu') is-invalid @enderror"
                                   value="{{ old('nama_menu', $menuMakanan->nama_menu) }}">
                            @error('nama_menu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori_id"
                                    class="form-select @error('kategori_id') is-invalid @enderror">
                                @foreach($kategoriList as $kategori)
                                    <option value="{{ $kategori->id }}"
                                        {{ old('kategori_id', $menuMakanan->kategori_id) == $kategori->id ? 'selected' : '' }}>
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
                                <option value="tersedia" {{ old('status', $menuMakanan->status) === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="habis" {{ old('status', $menuMakanan->status) === 'habis' ? 'selected' : '' }}>Habis</option>
                                <option value="tidak_tersedia" {{ old('status', $menuMakanan->status) === 'tidak_tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
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
                                       id="harga_modal"
                                       class="form-control"
                                       value="{{ old('harga_modal', $menuMakanan->harga_modal) }}"
                                       step="any" readonly>
                            </div>
                            <small style="color: var(--uio-text-muted);">Otomatis dari total bahan.</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Harga Jual <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: var(--uio-bg); border-color: var(--uio-border);">Rp</span>
                                <input type="number"
                                       name="harga_jual"
                                       id="harga_jual"
                                       class="form-control @error('harga_jual') is-invalid @enderror"
                                       value="{{ old('harga_jual', $menuMakanan->harga_jual) }}"
                                       step="any">
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
                                       id="margin_keuntungan"
                                       class="form-control"
                                       value="{{ old('margin_keuntungan', $menuMakanan->margin_keuntungan) }}"
                                       step="any" readonly>
                            </div>
                            <small style="color: var(--uio-text-muted);">Otomatis = Jual − Modal.</small>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-journal-text"></i> Komposisi Bahan</span>
                    <button type="button" id="btn-add-resep" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus"></i> Tambah
                    </button>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0" style="font-size:0.85rem;">
                        <thead>
                            <tr>
                                <th>Bahan</th>
                                <th style="width:90px;">Jumlah</th>
                                <th style="width:90px;">Satuan</th>
                                <th style="width:36px;"></th>
                            </tr>
                        </thead>
                        <tbody id="resep-body">
                        </tbody>
                    </table>
                    <div id="resep-empty" class="text-center py-3" style="color: var(--uio-text-muted);">
                        <i class="bi bi-inbox"></i> Belum ada bahan
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-3 mt-1">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-tags"></i> Harga Grosir (Bundle) <small class="text-muted">(opsional — harga khusus untuk kelipatan qty)</small></span>
                    <button type="button" id="btn-add-grosir" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus"></i> Tambah Tier
                    </button>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0" style="font-size:0.88rem;">
                        <thead>
                            <tr>
                                <th style="width:120px;">Min. Qty</th>
                                <th>Harga Bundle (total)</th>
                                <th style="width:60px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="grosir-body">
                        </tbody>
                    </table>
                    <div id="grosir-empty" class="text-center py-3" style="color: var(--uio-text-muted);">
                        <i class="bi bi-info-circle"></i> Belum ada tier grosir. Qty 1 akan pakai harga normal.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Update
        </button>
        <a href="{{ route('menu-makanan.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-x-circle"></i> Batal
        </a>
    </div>
</form>

<template id="resep-row-template">
    <tr class="resep-row">
        <td>
            <select name="resep[__INDEX__][bahan_id]" class="form-select form-select-sm bahan-select" required>
                <option value="">-- Pilih Bahan --</option>
                @foreach($bahanList as $bahan)
                    <option value="{{ $bahan->id }}" data-harga="{{ $bahan->harga_beli_per_satuan }}">
                        {{ $bahan->nama_bahan }} ({{ $bahan->satuan }})
                    </option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" name="resep[__INDEX__][jumlah_bahan]"
                   class="form-control form-control-sm jumlah-input"
                   step="0.01" min="0.01" placeholder="0.5" required>
        </td>
        <td>
            <select name="resep[__INDEX__][satuan]" class="form-select form-select-sm satuan-select" required>
                <option value="kg">kg</option>
                <option value="gram">gram</option>
                <option value="liter">liter</option>
                <option value="ml">ml</option>
                <option value="pcs">pcs</option>
                <option value="sachet">sachet</option>
            </select>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-resep">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    </tr>
</template>

<template id="grosir-row-template">
    <tr class="grosir-row">
        <td>
            <input type="number" name="harga_grosir[__INDEX__][min_qty]"
                   class="form-control form-control-sm min-qty-input"
                   min="2" placeholder="min qty" required>
        </td>
        <td>
            <div class="input-group input-group-sm">
                <span class="input-group-text" style="background: var(--uio-bg);">Rp</span>
                <input type="number" name="harga_grosir[__INDEX__][harga_total]"
                       class="form-control harga-grosir-input"
                       min="1" placeholder="harga total bundle" required>
            </div>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-grosir">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    </tr>
</template>

@if($bahanList->isEmpty())
<div class="alert alert-warning mt-3 d-flex align-items-center gap-2">
    <i class="bi bi-exclamation-triangle-fill"></i>
    Belum ada data bahan baku. <a href="{{ route('bahan-baku.create') }}" class="alert-link">Tambah bahan baku</a> dulu.
</div>
@endif

@endsection

@push('scripts')
@php
    $hargaBahanJs = $bahanList->pluck('harga_beli_per_satuan', 'id');
    $existingJs   = old('resep');
    if (!$existingJs) {
        $existingJs = $menuMakanan->reseps->map(function ($r) {
            return [
                'bahan_id'     => $r->bahan_id,
                'jumlah_bahan' => (float) $r->jumlah_bahan,
                'satuan'       => $r->satuan,
            ];
        })->values()->all();
    }

    $existingGrosirJs = old('harga_grosir');
    if (!$existingGrosirJs) {
        $existingGrosirJs = $menuMakanan->hargaGrosirs->map(function ($g) {
            return [
                'min_qty'     => (int) $g->min_qty,
                'harga_total' => (float) $g->harga_total,
            ];
        })->values()->all();
    }
@endphp
<script>
(function () {
    const body     = document.getElementById('resep-body');
    const empty    = document.getElementById('resep-empty');
    const template = document.getElementById('resep-row-template');
    const btnAdd   = document.getElementById('btn-add-resep');
    const hargaJualEl = document.getElementById('harga_jual');
    const modalEl  = document.getElementById('harga_modal');
    const marginEl = document.getElementById('margin_keuntungan');

    const hargaBahan = @json($hargaBahanJs);
    const existing   = @json($existingJs);

    let index = 0;

    function recalc() {
        let total = 0;
        body.querySelectorAll('.resep-row').forEach(function (row) {
            const sel    = row.querySelector('.bahan-select');
            const jumlah = parseFloat(row.querySelector('.jumlah-input').value) || 0;
            const id     = parseInt(sel.value);
            if (id && hargaBahan[id] != null) {
                total += jumlah * parseFloat(hargaBahan[id]);
            }
        });
        modalEl.value = total.toFixed(2);
        const jual = parseFloat(hargaJualEl.value) || 0;
        marginEl.value = (jual - total).toFixed(2);
        empty.style.display = body.children.length === 0 ? '' : 'none';
    }

    function addRow(prefill) {
        const frag = template.content.cloneNode(true);
        const row  = frag.querySelector('.resep-row');
        row.querySelectorAll('[name]').forEach(function (el) {
            el.name = el.name.replace('__INDEX__', index);
        });
        if (prefill) {
            row.querySelector('.bahan-select').value   = prefill.bahan_id || '';
            row.querySelector('.jumlah-input').value   = prefill.jumlah_bahan || '';
            row.querySelector('.satuan-select').value  = prefill.satuan || 'pcs';
        }
        body.appendChild(frag);
        index++;
        recalc();
    }

    btnAdd.addEventListener('click', function () { addRow(); });
    body.addEventListener('click', function (e) {
        if (e.target.closest('.btn-remove-resep')) {
            e.target.closest('.resep-row').remove();
            recalc();
        }
    });
    body.addEventListener('input', recalc);
    body.addEventListener('change', recalc);
    hargaJualEl.addEventListener('input', recalc);

    if (existing.length > 0) {
        existing.forEach(function (r) { addRow(r); });
    } else {
        addRow();
    }

    // Grosir
    const existingGrosir = @json($existingGrosirJs);
    const gBody     = document.getElementById('grosir-body');
    const gEmpty    = document.getElementById('grosir-empty');
    const gTemplate = document.getElementById('grosir-row-template');
    const btnGrosir = document.getElementById('btn-add-grosir');
    let gIndex = 0;

    function syncGrosir() {
        gEmpty.style.display = gBody.children.length === 0 ? '' : 'none';
    }

    function addGrosir(prefill) {
        const frag = gTemplate.content.cloneNode(true);
        const row  = frag.querySelector('.grosir-row');
        row.querySelectorAll('[name]').forEach(function (el) {
            el.name = el.name.replace('__INDEX__', gIndex);
        });
        if (prefill) {
            row.querySelector('.min-qty-input').value      = prefill.min_qty || '';
            row.querySelector('.harga-grosir-input').value = prefill.harga_total || '';
        }
        gBody.appendChild(frag);
        gIndex++;
        syncGrosir();
    }

    btnGrosir.addEventListener('click', function () { addGrosir(); });
    gBody.addEventListener('click', function (e) {
        if (e.target.closest('.btn-remove-grosir')) {
            e.target.closest('.grosir-row').remove();
            syncGrosir();
        }
    });

    if (existingGrosir.length > 0) {
        existingGrosir.forEach(function (g) { addGrosir(g); });
    }
    syncGrosir();
})();
</script>
@endpush
