@extends('layouts.app')

@section('title', 'Jurnal Transaksi')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-journal-bookmark"></i> Jurnal Transaksi</h2>
</div>

{{-- Filter --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('laporan.jurnal-transaksi') }}" class="row g-2 align-items-end">

            {{-- Mode --}}
            <div class="col-md-2">
                <label class="form-label">Periode</label>
                <select name="mode" class="form-select form-select-sm" id="modeSelect">
                    <option value="harian"  {{ $mode === 'harian'  ? 'selected' : '' }}>Harian</option>
                    <option value="bulanan" {{ $mode === 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                    <option value="tahunan" {{ $mode === 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                </select>
            </div>

            {{-- Harian --}}
            <div class="col-md-3" id="filterHarian" style="{{ $mode !== 'harian' ? 'display:none' : '' }}">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-select form-select-sm"
                       value="{{ $tanggal }}">
            </div>

            {{-- Bulanan --}}
            <div class="col-md-2" id="filterBulan" style="{{ $mode !== 'bulanan' ? 'display:none' : '' }}">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-select form-select-sm">
                    @foreach(range(1,12) as $b)
                        <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $b)->format('F') }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tahun --}}
            <div class="col-md-2" id="filterTahun" style="{{ $mode === 'harian' ? 'display:none' : '' }}">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select form-select-sm">
                    @foreach(range(now()->year, now()->year - 3) as $y)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="bi bi-filter"></i> Filter
                </button>
            </div>

            <div class="col-md-2">
                <a href="{{ route('laporan.jurnal-transaksi', array_merge(request()->all(), ['cetak' => 1])) }}"
                   target="_blank" class="btn btn-outline-secondary btn-sm w-100">
                    <i class="bi bi-printer"></i> Cetak
                </a>
            </div>

        </form>
    </div>
</div>

{{-- Tabel Jurnal --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong><i class="bi bi-journal-bookmark"></i> Jurnal Transaksi —
            @if($mode === 'harian')
                {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}
            @elseif($mode === 'bulanan')
                {{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }}
            @else
                Tahun {{ $tahun }}
            @endif
        </strong>
        <span style="font-size:0.82rem; color: var(--uio-text-muted);">
            {{ $data['jurnal_list']->count() }} transaksi
        </span>
    </div>
    <div class="card-body p-0">
        <table class="table mb-0" style="font-size:0.85rem;">
            <thead>
                <tr>
                    <th style="width:50px;">No</th>
                    <th>Tanggal</th>
                    <th>Ref. ID</th>
                    <th>Rek. Debet</th>
                    <th>Rek. Kredit</th>
                    <th>Keterangan</th>
                    <th class="text-end">Jumlah</th>
                    <th>User</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['jurnal_list'] as $i => $j)
                <tr>
                    <td style="color: var(--uio-text-muted);">{{ $i + 1 }}</td>
                    <td style="color: var(--uio-text-muted); white-space:nowrap;">
                        {{ \Carbon\Carbon::parse($j->tgl_transaksi)->format('d/m/Y') }}
                    </td>
                    <td>
                        <span class="badge badge-uio-info">#{{ $j->id }}</span>
                    </td>
                    <td>
                        <code style="font-size:0.78rem;">{{ $j->rekening_debet }}</code>
                        <div style="font-size:0.75rem; color: var(--uio-text-muted);">
                            {{ $j->rekeningDebet->nama_akun ?? '-' }}
                        </div>
                    </td>
                    <td>
                        <code style="font-size:0.78rem;">{{ $j->rekening_kredit }}</code>
                        <div style="font-size:0.75rem; color: var(--uio-text-muted);">
                            {{ $j->rekeningKredit->nama_akun ?? '-' }}
                        </div>
                    </td>
                    <td>{{ $j->keterangan_transaksi }}</td>
                    <td class="text-end">
                        <strong>Rp {{ number_format($j->jumlah, 0, ',', '.') }}</strong>
                    </td>
                    <td style="font-size:0.82rem;">{{ $j->user->nama_lengkap ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4" style="color: var(--uio-text-muted);">
                        <i class="bi bi-inbox"></i> Tidak ada jurnal untuk periode ini
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($data['jurnal_list']->count() > 0)
            <tfoot>
                <tr style="background: var(--uio-bg);">
                    <td colspan="6" class="text-end"><strong>Total Transaksi</strong></td>
                    <td class="text-end">
                        <strong style="color: var(--uio-primary-dark);">
                            Rp {{ number_format($data['total_jumlah'], 0, ',', '.') }}
                        </strong>
                    </td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('modeSelect').addEventListener('change', function() {
    const mode = this.value;
    document.getElementById('filterHarian').style.display = mode === 'harian'  ? 'block' : 'none';
    document.getElementById('filterBulan').style.display  = mode === 'bulanan' ? 'block' : 'none';
    document.getElementById('filterTahun').style.display  = mode === 'harian'  ? 'none'  : 'block';
});
</script>
@endpush
