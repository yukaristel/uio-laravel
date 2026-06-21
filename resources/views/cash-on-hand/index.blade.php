@extends('layouts.app')

@section('title', 'Cash on Hand')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-cash-coin"></i> Cash on Hand</h2>
    <span class="text-muted" style="font-size:0.9rem;">
        <i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
    </span>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle"></i> {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form method="POST" action="{{ route('cash-on-hand.store') }}" id="formCashOnHand">
    @csrf

    <div class="row g-3">

        {{-- Saldo Sistem --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-bank"></i> Kas di Sistem
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">Akun Kas Tunai (1.1.01.01)</small>
                    </div>
                    <div style="font-size:1.8rem; font-weight:700; color: var(--uio-primary-dark);">
                        Rp {{ number_format($saldoSistem, 0, ',', '.') }}
                    </div>
                    <hr style="border-color: var(--uio-border);">
                    <div class="d-flex justify-content-between" style="font-size:0.85rem;">
                        <span class="text-muted">Total Uang Fisik</span>
                        <strong id="totalFisik">Rp 0</strong>
                    </div>
                    <div class="d-flex justify-content-between" style="font-size:0.85rem;">
                        <span class="text-muted">Selisih</span>
                        <strong id="selisih" style="color: var(--uio-text-muted);">Rp 0</strong>
                    </div>
                    <div class="mt-2" id="statusSelisih"></div>
                </div>
            </div>
        </div>

        {{-- Input Pecahan --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-calculator"></i> Hitung Uang Tunai
                </div>
                <div class="card-body p-0">
                    <table class="table table-borderless mb-0" style="font-size:0.95rem;">
                        <thead>
                            <tr style="border-bottom: 2px solid var(--uio-border);">
                                <th class="ps-3" style="width:30%;">Pecahan</th>
                                <th class="text-center" style="width:30%;">Jumlah</th>
                                <th class="text-end pe-3" style="width:40%;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $pecahan = [100000, 50000, 20000, 10000, 5000, 2000, 1000, 500, 200, 100];
                            @endphp
                            @foreach($pecahan as $nom)
                                <tr class="row-pecahan">
                                    <td class="ps-3"><strong>Rp {{ number_format($nom, 0, ',', '.') }}</strong></td>
                                    <td>
                                        <input type="number" min="0"
                                               name="jumlah_{{ $nom }}"
                                               class="form-control form-control-sm text-center input-pecahan"
                                               data-nominal="{{ $nom }}"
                                               value="0">
                                    </td>
                                    <td class="text-end pe-3 subtotal-pecahan" data-nominal="{{ $nom }}">
                                        Rp 0
                                    </td>
                                </tr>
                            @endforeach
                            <tr style="border-top: 2px solid var(--uio-border); background: var(--uio-bg);">
                                <td class="ps-3"><strong>Nominal Lain (koin/custom)</strong></td>
                                <td colspan="2" class="pe-3">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text" style="background:white; border-color: var(--uio-border);">Rp</span>
                                        <input type="number" min="0" name="nominal_lain" id="nominalLain"
                                               class="form-control" value="0">
                                    </div>
                                </td>
                            </tr>
                            <tr style="background: var(--uio-bg);">
                                <td class="ps-3"><strong>Total</strong></td>
                                <td colspan="2" class="text-end pe-3">
                                    <strong id="totalFisikInline" style="font-size:1.1rem; color: var(--uio-primary-dark);">Rp 0</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <label class="form-label">Keterangan (opsional)</label>
                    <input type="text" name="keterangan" class="form-control" maxlength="255"
                           placeholder="Catatan untuk selisih kas (jika ada)">
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="button" id="btnPeriksa" class="btn btn-primary">
                    <i class="bi bi-search"></i> Periksa
                </button>
                <button type="reset" class="btn btn-outline-secondary" id="btnReset">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset Form
                </button>
            </div>
        </div>

    </div>

    {{-- Konfirmasi Selisih (hidden by default) --}}
    <div id="konfirmasiBox" class="card mt-3" style="display:none; border: 2px solid #ffc107;">
        <div class="card-body">
            <h5 class="mb-3"><i class="bi bi-exclamation-triangle text-warning"></i> Konfirmasi Selisih Kas</h5>
            <table class="table table-borderless mb-3" style="font-size:0.95rem;">
                <tr>
                    <td style="width:50%;" class="text-muted">Total Uang Fisik</td>
                    <td class="text-end"><strong id="konfFisik">Rp 0</strong></td>
                </tr>
                <tr>
                    <td class="text-muted">Kas di Sistem</td>
                    <td class="text-end"><strong id="konfSistem">Rp 0</strong></td>
                </tr>
                <tr style="border-top: 2px solid var(--uio-border);">
                    <td class="text-muted">Selisih</td>
                    <td class="text-end">
                        <strong id="konfSelisih" style="font-size:1.1rem;">Rp 0</strong>
                        <div id="konfArah" class="text-muted" style="font-size:0.85rem;"></div>
                    </td>
                </tr>
            </table>
            <div class="alert alert-info mb-3" style="font-size:0.85rem;">
                <i class="bi bi-info-circle"></i>
                <span id="konfInfo"></span>
            </div>
            <input type="hidden" name="konfirmasi" id="konfirmasiFlag" value="0">
            <div class="d-flex gap-2 justify-content-end">
                <button type="button" id="btnBatal" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle"></i> Batal / Cek Ulang
                </button>
                <button type="submit" id="btnKonfirmasi" class="btn btn-warning">
                    <i class="bi bi-check2-circle"></i> Konfirmasi & Catat
                </button>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
const SALDO_SISTEM = {{ (float) $saldoSistem }};

function formatRp(val) {
    return 'Rp ' + parseInt(val || 0).toLocaleString('id-ID');
}

function hitungTotal() {
    let total = 0;
    document.querySelectorAll('.input-pecahan').forEach(inp => {
        const nominal = parseInt(inp.dataset.nominal);
        const jml     = parseInt(inp.value) || 0;
        const sub     = nominal * jml;
        const cell    = document.querySelector(`.subtotal-pecahan[data-nominal="${nominal}"]`);
        if (cell) cell.textContent = formatRp(sub);
        total += sub;
    });
    const lain = parseFloat(document.getElementById('nominalLain').value) || 0;
    total += lain;

    document.getElementById('totalFisik').textContent       = formatRp(total);
    document.getElementById('totalFisikInline').textContent  = formatRp(total);

    const selisih = total - SALDO_SISTEM;
    const elSel   = document.getElementById('selisih');
    const elStat  = document.getElementById('statusSelisih');

    if (Math.abs(selisih) < 0.01) {
        elSel.textContent = 'Rp 0';
        elSel.style.color = 'var(--uio-primary-dark)';
        elStat.innerHTML = '<span class="badge bg-success"><i class="bi bi-check-circle"></i> Sudah sesuai</span>';
    } else if (selisih > 0) {
        elSel.textContent = '+ ' + formatRp(selisih);
        elSel.style.color = '#198754';
        elStat.innerHTML = '<span class="badge bg-warning text-dark">Lebih ' + formatRp(selisih) + '</span>';
    } else {
        elSel.textContent = '- ' + formatRp(Math.abs(selisih));
        elSel.style.color = '#dc3545';
        elStat.innerHTML = '<span class="badge bg-danger">Kurang ' + formatRp(Math.abs(selisih)) + '</span>';
    }

    return { total, selisih };
}

document.querySelectorAll('.input-pecahan, #nominalLain').forEach(el => {
    el.addEventListener('input', hitungTotal);
});

document.getElementById('btnReset').addEventListener('click', function() {
    setTimeout(() => {
        document.querySelectorAll('.subtotal-pecahan').forEach(c => c.textContent = 'Rp 0');
        document.getElementById('konfirmasiBox').style.display = 'none';
        document.getElementById('konfirmasiFlag').value = '0';
        hitungTotal();
    }, 10);
});

document.getElementById('btnPeriksa').addEventListener('click', function() {
    const { total, selisih } = hitungTotal();

    if (Math.abs(selisih) < 0.01) {
        document.getElementById('konfirmasiBox').style.display = 'none';
        document.getElementById('konfirmasiFlag').value = '0';
        document.getElementById('formCashOnHand').submit();
        return;
    }

    document.getElementById('konfFisik').textContent   = formatRp(total);
    document.getElementById('konfSistem').textContent  = formatRp(SALDO_SISTEM);
    document.getElementById('konfSelisih').textContent = (selisih > 0 ? '+ ' : '- ') + formatRp(Math.abs(selisih));

    const elArah = document.getElementById('konfArah');
    const elInfo = document.getElementById('konfInfo');
    if (selisih > 0) {
        elArah.innerHTML = '<span class="text-success">LEBIH (kelebihan uang fisik)</span>';
        elInfo.innerHTML  = 'Akan dicatat jurnal: <strong>Debet Kas Tunai</strong> / <strong>Kredit Pendapatan Lain-lain</strong> sebesar <strong>' + formatRp(selisih) + '</strong>.';
    } else {
        elArah.innerHTML = '<span class="text-danger">KURANG (uang fisik kurang)</span>';
        elInfo.innerHTML  = 'Akan dicatat jurnal: <strong>Debet Beban Lain-lain</strong> / <strong>Kredit Kas Tunai</strong> sebesar <strong>' + formatRp(Math.abs(selisih)) + '</strong>.';
    }

    document.getElementById('konfirmasiFlag').value = '0';
    document.getElementById('konfirmasiBox').style.display = 'block';
    document.getElementById('konfirmasiBox').scrollIntoView({ behavior: 'smooth', block: 'center' });
});

document.getElementById('btnBatal').addEventListener('click', function() {
    document.getElementById('konfirmasiBox').style.display = 'none';
    document.getElementById('konfirmasiFlag').value = '0';
});

document.getElementById('btnKonfirmasi').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('konfirmasiFlag').value = '1';
    document.getElementById('formCashOnHand').submit();
});

hitungTotal();
</script>
@endpush
