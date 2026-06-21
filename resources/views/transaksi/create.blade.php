@extends('layouts.app')

@section('title', 'Transaksi Baru')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-cart-plus"></i> Transaksi Baru</h2>
    <a href="{{ route('transaksi.index') }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="row g-3">

    {{-- Form Transaksi --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-list-check"></i> Item Pesanan
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('transaksi.store') }}" id="formTransaksi">
                    @csrf

                    <input type="hidden" name="tanggal_transaksi" value="{{ now() }}">

                    {{-- Item List --}}
                    <div id="itemList">
                        <div class="item-row row g-2 mb-2 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label">Menu</label>
                                <select name="details[0][menu_id]" class="form-select menu-select" required>
                                    <option value="">-- Pilih Menu --</option>
                                    @foreach($menuList as $menu)
                                        <option value="{{ $menu->id }}"
                                                data-harga="{{ $menu->harga_jual }}"
                                                data-modal="{{ $menu->harga_modal }}"
                                                data-grosir='@json($menu->hargaGrosirs->map(fn($g) => ["min_qty" => (int)$g->min_qty, "harga" => (float)$g->harga_total])->values())'>
                                            {{ $menu->nama_menu }} — Rp {{ number_format($menu->harga_jual, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Jumlah</label>
                                <input type="number" name="details[0][jumlah]"
                                       class="form-control jumlah-input"
                                       value="1" min="1" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Harga</label>
                                <input type="number" name="details[0][harga_satuan]"
                                       class="form-control harga-input"
                                       value="0" readonly>
                                <small class="badge-hemat text-success" style="display:none;font-size:0.7rem;"></small>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Subtotal</label>
                                <input type="text"
                                       class="form-control subtotal-display"
                                       value="Rp 0" readonly tabindex="-1"
                                       style="background: var(--uio-bg);">
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-outline-danger btn-hapus-item w-100">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="btnTambahItem" class="btn btn-outline-primary btn-sm mt-2">
                        <i class="bi bi-plus-circle"></i> Tambah Item
                    </button>

                    <hr style="border-color: var(--uio-border);">

                    {{-- Pembayaran --}}
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                            <select name="metode_pembayaran" class="form-select" id="metodePembayaran" required>
                                <optgroup label="Tunai">
                                    <option value="tunai">💵 Tunai</option>
                                </optgroup>
                                <optgroup label="Kasir Digital">
                                    <option value="qris">📱 QRIS</option>
                                    <option value="debit">💳 Debit</option>
                                    <option value="transfer">🏦 Transfer</option>
                                </optgroup>
                                <optgroup label="Platform Online">
                                    <option value="gopay">🟢 GoPay</option>
                                    <option value="grab">🟢 GrabFood</option>
                                    <option value="shopeepay">🟠 ShopeePay</option>
                                </optgroup>
                            </select>
                        </div>

                        {{-- Tunai --}}
                        <div class="col-md-6" id="sectionTunai">
                            <label class="form-label">Uang Bayar <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: var(--uio-bg); border-color: var(--uio-border);">Rp</span>
                                <input type="number" name="uang_bayar" id="uangBayar"
                                       class="form-control" value="0" min="0">
                            </div>
                        </div>

                        {{-- Non-tunai --}}
                        <div class="col-md-6" id="sectionNonTunai" style="display:none;">
                            <label class="form-label">Nominal Diterima <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: var(--uio-bg); border-color: var(--uio-border);">Rp</span>
                                <input type="number" name="nominal_diterima" id="nominalDiterima"
                                       class="form-control" value="0" min="0">
                            </div>
                            <div class="form-text" style="color: var(--uio-text-muted); font-size:0.8rem;">
                                <i class="bi bi-info-circle"></i>
                                Isi nominal yang benar-benar diterima (setelah diskon platform)
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Proses Transaksi
                        </button>
                        <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Ringkasan --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-receipt"></i> Ringkasan
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0" style="font-size:0.9rem;">
                    <tr>
                        <td style="color: var(--uio-text-muted);">Total Harga</td>
                        <td class="text-end"><strong id="totalHarga">Rp 0</strong></td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Total Modal</td>
                        <td class="text-end" id="totalModal">Rp 0</td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);">Keuntungan</td>
                        <td class="text-end" id="totalKeuntungan" style="color: var(--uio-primary-dark);">Rp 0</td>
                    </tr>
                    <tr style="border-top: 2px solid var(--uio-border);">
                        <td style="color: var(--uio-text-muted);">Uang Bayar</td>
                        <td class="text-end" id="dispUangBayar">Rp 0</td>
                    </tr>
                    <tr>
                        <td style="color: var(--uio-text-muted);"  id="labelKembalian">Kembalian</td>
                        <td class="text-end">
                            <strong id="kembalian" style="color: var(--uio-primary-dark);">Rp 0</strong>
                        </td>
                    </tr>
                </table>
            </div>

            {{-- Keypad Tunai --}}
            <div id="keypadSection" style="display:none;">
                <hr style="border-color: var(--uio-border);" class="mt-3 mb-2">
                <div class="d-grid gap-1">
                    <div class="row g-1">
                        <div class="col-4"><button type="button" class="btn btn-outline-primary w-100 key-btn" data-val="7">7</button></div>
                        <div class="col-4"><button type="button" class="btn btn-outline-primary w-100 key-btn" data-val="8">8</button></div>
                        <div class="col-4"><button type="button" class="btn btn-outline-primary w-100 key-btn" data-val="9">9</button></div>
                    </div>
                    <div class="row g-1">
                        <div class="col-4"><button type="button" class="btn btn-outline-primary w-100 key-btn" data-val="4">4</button></div>
                        <div class="col-4"><button type="button" class="btn btn-outline-primary w-100 key-btn" data-val="5">5</button></div>
                        <div class="col-4"><button type="button" class="btn btn-outline-primary w-100 key-btn" data-val="6">6</button></div>
                    </div>
                    <div class="row g-1">
                        <div class="col-4"><button type="button" class="btn btn-outline-primary w-100 key-btn" data-val="1">1</button></div>
                        <div class="col-4"><button type="button" class="btn btn-outline-primary w-100 key-btn" data-val="2">2</button></div>
                        <div class="col-4"><button type="button" class="btn btn-outline-primary w-100 key-btn" data-val="3">3</button></div>
                    </div>
                    <div class="row g-1">
                        <div class="col-4"><button type="button" class="btn btn-outline-primary w-100 key-btn" data-val="00">00</button></div>
                        <div class="col-4"><button type="button" class="btn btn-outline-primary w-100 key-btn" data-val="0">0</button></div>
                        <div class="col-4"><button type="button" class="btn btn-outline-primary w-100 key-btn" data-val="000">000</button></div>
                    </div>
                    <div class="row g-1 mt-2">
                        <div class="col-6">
                            <button type="button" id="btnKeypadReset" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" id="btnKeypadSubmit" class="btn btn-primary w-100">
                                <i class="bi bi-check-circle"></i> Proses Transaksi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
let itemIndex = 1;
let uangBayarManual = false;
let keypadFresh = true;

function formatRp(val) {
    return 'Rp ' + parseInt(val || 0).toLocaleString('id-ID');
}

function getTotalHarga() {
    let total = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const sel    = row.querySelector('.menu-select');
        const opt    = sel.options[sel.selectedIndex];
        const jumlah = parseInt(row.querySelector('.jumlah-input').value) || 0;
        const harga  = parseFloat(row.querySelector('.harga-input').value) || 0;
        const modal  = parseFloat(opt?.dataset.modal) || 0;
        total += jumlah * harga;
    });
    return total;
}

function syncUangBayarAuto() {
    if (!isTunai() || uangBayarManual) return;
    const el = document.getElementById('uangBayar');
    if (el) el.value = getTotalHarga();
}

function isTunai() {
    return document.getElementById('metodePembayaran').value === 'tunai';
}

function getTiersForRow(row) {
    const sel = row.querySelector('.menu-select');
    const opt = sel.options[sel.selectedIndex];
    if (!opt || !opt.dataset.grosir) return [];
    try {
        return JSON.parse(opt.dataset.grosir) || [];
    } catch (e) { return []; }
}

function getTierTertinggi(tiers, qty) {
    if (!tiers || qty < 2) return null;
    const valid = tiers.filter(t => qty >= t.min_qty);
    if (valid.length === 0) return null;
    return valid.reduce((best, t) => (t.min_qty > best.min_qty ? t : best));
}

function updateRowHarga(row) {
    const sel    = row.querySelector('.menu-select');
    const opt    = sel.options[sel.selectedIndex];
    const jumlah = parseInt(row.querySelector('.jumlah-input').value) || 0;

    const hargaNormal = parseFloat(opt?.dataset.harga) || 0;
    const tiers       = getTiersForRow(row);
    const tier        = getTierTertinggi(tiers, jumlah);

    let subtotal, hargaSatuan, badgeText = null;

    if (tier && jumlah >= 2) {
        const bundleCount = Math.floor(jumlah / tier.min_qty);
        const sisaQty     = jumlah % tier.min_qty;
        subtotal   = bundleCount * tier.harga + sisaQty * hargaNormal;
        hargaSatuan = subtotal / jumlah;
        const subtotalNormal = jumlah * hargaNormal;
        const hemat = subtotalNormal - subtotal;
        if (hemat > 0) {
            const parts = [];
            if (bundleCount > 0) parts.push(bundleCount + '× bundle @' + tier.min_qty);
            if (sisaQty > 0)     parts.push(sisaQty + '× normal');
            badgeText = '🎉 Hemat ' + formatRp(hemat) + ' (' + parts.join(' + ') + ')';
        }
    } else {
        subtotal    = jumlah * hargaNormal;
        hargaSatuan = hargaNormal;
    }

    row.querySelector('.harga-input').value = hargaSatuan.toFixed(2);
    row.querySelector('.subtotal-display').value = formatRp(subtotal);

    const badge = row.querySelector('.badge-hemat');
    if (badgeText) {
        badge.textContent = badgeText;
        badge.style.display = '';
    } else {
        badge.style.display = 'none';
    }
}

function hitungTotal() {
    let totalHarga = 0, totalModal = 0;

    document.querySelectorAll('.item-row').forEach(row => {
        const sel    = row.querySelector('.menu-select');
        const opt    = sel.options[sel.selectedIndex];
        const jumlah = parseInt(row.querySelector('.jumlah-input').value) || 0;
        const harga  = parseFloat(row.querySelector('.harga-input').value) || 0;
        const modal  = parseFloat(opt?.dataset.modal) || 0;
        totalHarga  += jumlah * harga;
        totalModal  += jumlah * modal;
    });

    document.getElementById('totalHarga').textContent = formatRp(totalHarga);
    document.getElementById('totalModal').textContent = formatRp(totalModal);

    syncUangBayarAuto();

    if (isTunai()) {
        const uangBayar  = parseInt(document.getElementById('uangBayar').value) || 0;
        const keuntungan = totalHarga - totalModal;
        const kembalian  = uangBayar - totalHarga;

        document.getElementById('totalKeuntungan').textContent = formatRp(keuntungan);
        document.getElementById('dispUangBayar').textContent   = formatRp(uangBayar);
        document.getElementById('kembalian').textContent       = formatRp(kembalian);
        document.getElementById('labelKembalian').textContent  = 'Kembalian';
    } else {
        const nominalDiterima = parseInt(document.getElementById('nominalDiterima').value) || 0;
        const keuntungan      = nominalDiterima - totalModal;

        document.getElementById('totalHarga').textContent      = formatRp(nominalDiterima);
        document.getElementById('totalKeuntungan').textContent = formatRp(keuntungan);
        document.getElementById('dispUangBayar').textContent   = formatRp(nominalDiterima);
        document.getElementById('kembalian').textContent       = '-';
        document.getElementById('labelKembalian').textContent  = 'Kembalian';
    }
}

function toggleMetode() {
    const tunai = isTunai();
    document.getElementById('sectionTunai').style.display    = tunai ? 'block' : 'none';
    document.getElementById('sectionNonTunai').style.display = tunai ? 'none' : 'block';
    document.getElementById('keypadSection').style.display  = tunai ? 'block' : 'none';
    if (tunai) {
        uangBayarManual = false;
        keypadFresh = true;
        syncUangBayarAuto();
    }
    hitungTotal();
}

function bindRow(row) {
    row.querySelector('.menu-select').addEventListener('change', function() {
        updateRowHarga(row);
        hitungTotal();
    });

    row.querySelector('.jumlah-input').addEventListener('input', function() {
        updateRowHarga(row);
        hitungTotal();
    });

    row.querySelector('.btn-hapus-item').addEventListener('click', function() {
        if (document.querySelectorAll('.item-row').length > 1) {
            row.remove();
            hitungTotal();
        }
    });
}

document.querySelectorAll('.item-row').forEach(bindRow);
document.querySelectorAll('.item-row').forEach(updateRowHarga);

document.getElementById('btnTambahItem').addEventListener('click', function() {
    const template = document.querySelector('.item-row').cloneNode(true);
    template.querySelectorAll('select, input').forEach(el => {
        const name = el.getAttribute('name');
        if (name) el.setAttribute('name', name.replace(/\[\d+\]/, `[${itemIndex}]`));
        if (el.tagName === 'SELECT') el.selectedIndex = 0;
        else if (el.classList.contains('harga-input')) el.value = 0;
        else if (el.classList.contains('jumlah-input')) el.value = 1;
        else if (el.classList.contains('subtotal-display')) el.value = 'Rp 0';
        else if (el.classList.contains('badge-hemat')) el.style.display = 'none';
        else if (!el.readOnly) el.value = '';
        else el.value = 0;
    });
    document.getElementById('itemList').appendChild(template);
    bindRow(template);
    itemIndex++;
});

document.getElementById('metodePembayaran').addEventListener('change', toggleMetode);
document.getElementById('uangBayar').addEventListener('input', function() {
    uangBayarManual = true;
    keypadFresh = false;
    hitungTotal();
});
document.getElementById('nominalDiterima').addEventListener('input', hitungTotal);

document.querySelectorAll('.key-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        uangBayarManual = true;
        const el = document.getElementById('uangBayar');
        const cur = el.value || '0';
        if (keypadFresh) {
            el.value = btn.dataset.val;
            keypadFresh = false;
        } else {
            el.value = (parseInt(cur) === 0 && btn.dataset.val !== '00' && btn.dataset.val !== '000')
                ? btn.dataset.val
                : (cur + btn.dataset.val);
        }
        hitungTotal();
    });
});

document.getElementById('btnKeypadReset').addEventListener('click', function() {
    uangBayarManual = false;
    keypadFresh = true;
    syncUangBayarAuto();
    hitungTotal();
});

document.getElementById('btnKeypadSubmit').addEventListener('click', function() {
    document.getElementById('formTransaksi').submit();
});

toggleMetode();
</script>
@endpush
