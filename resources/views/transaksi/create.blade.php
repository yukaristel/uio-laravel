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
                                                data-modal="{{ $menu->harga_modal }}">
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
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Modal</label>
                                <input type="number" name="details[0][harga_modal_satuan]"
                                       class="form-control modal-input"
                                       value="0" readonly>
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
                            <select name="metode_pembayaran" class="form-select" required>
                                <option value="tunai">Tunai</option>
                                <option value="qris">QRIS</option>
                                <option value="debit">Debit</option>
                                <option value="transfer">Transfer</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Uang Bayar <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: var(--uio-bg); border-color: var(--uio-border);">Rp</span>
                                <input type="number" name="uang_bayar" id="uangBayar"
                                       class="form-control" value="0" min="0" required>
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
                        <td style="color: var(--uio-text-muted);">Kembalian</td>
                        <td class="text-end"><strong id="kembalian" style="color: var(--uio-primary-dark);">Rp 0</strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
let itemIndex = 1;

function formatRp(val) {
    return 'Rp ' + parseInt(val || 0).toLocaleString('id-ID');
}

function hitungTotal() {
    let totalHarga = 0, totalModal = 0;

    document.querySelectorAll('.item-row').forEach(row => {
        const jumlah = parseInt(row.querySelector('.jumlah-input').value) || 0;
        const harga  = parseInt(row.querySelector('.harga-input').value) || 0;
        const modal  = parseInt(row.querySelector('.modal-input').value) || 0;
        totalHarga  += jumlah * harga;
        totalModal  += jumlah * modal;
    });

    const uangBayar   = parseInt(document.getElementById('uangBayar').value) || 0;
    const keuntungan  = totalHarga - totalModal;
    const kembalian   = uangBayar - totalHarga;

    document.getElementById('totalHarga').textContent     = formatRp(totalHarga);
    document.getElementById('totalModal').textContent     = formatRp(totalModal);
    document.getElementById('totalKeuntungan').textContent= formatRp(keuntungan);
    document.getElementById('dispUangBayar').textContent  = formatRp(uangBayar);
    document.getElementById('kembalian').textContent      = formatRp(kembalian);
}

function bindRow(row) {
    row.querySelector('.menu-select').addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        row.querySelector('.harga-input').value = opt.dataset.harga || 0;
        row.querySelector('.modal-input').value = opt.dataset.modal || 0;
        hitungTotal();
    });

    row.querySelector('.jumlah-input').addEventListener('input', hitungTotal);

    row.querySelector('.btn-hapus-item').addEventListener('click', function() {
        if (document.querySelectorAll('.item-row').length > 1) {
            row.remove();
            hitungTotal();
        }
    });
}

// Bind existing row
document.querySelectorAll('.item-row').forEach(bindRow);

// Tambah item
document.getElementById('btnTambahItem').addEventListener('click', function() {
    const template = document.querySelector('.item-row').cloneNode(true);
    template.querySelectorAll('select, input').forEach(el => {
        const name = el.getAttribute('name');
        if (name) el.setAttribute('name', name.replace(/\[\d+\]/, `[${itemIndex}]`));
        if (el.tagName === 'SELECT') el.selectedIndex = 0;
        else if (!el.readOnly) el.value = el.type === 'number' ? (el.classList.contains('jumlah-input') ? 1 : 0) : '';
        else el.value = 0;
    });
    document.getElementById('itemList').appendChild(template);
    bindRow(template);
    itemIndex++;
});

document.getElementById('uangBayar').addEventListener('input', hitungTotal);
</script>
@endpush
