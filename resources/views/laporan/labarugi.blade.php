@extends('layouts.app')

@section('title', 'Laporan Laba Rugi')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-graph-up"></i> Laporan Laba Rugi</h2>
</div>

{{-- Filter --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('laporan.labarugi') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-select form-select-sm">
                    @foreach(range(1,12) as $b)
                        <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $b)->format('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
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
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <strong><i class="bi bi-graph-up"></i> Laporan Laba Rugi — {{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }}</strong>
    </div>
    <div class="card-body p-0">
        <table class="table mb-0" style="font-size:0.88rem;">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Akun</th>
                    <th class="text-end">Debet</th>
                    <th class="text-end">Kredit</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalPendapatan = 0;
                    $totalBeban = 0;
                    $currentLev1 = null;
                @endphp
                @foreach($data as $row)
                    @if($row['akun']->lev1 != $currentLev1)
                        @php $currentLev1 = $row['akun']->lev1; @endphp
                        <tr style="background: var(--uio-bg);">
                            <td colspan="4" style="font-weight:700; font-size:0.8rem; text-transform:uppercase; color: var(--uio-text-muted); letter-spacing:0.5px;">
                                {{ $row['akun']->lev1 == 4 ? '📈 PENDAPATAN' : '📉 BEBAN' }}
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td><code style="font-size:0.78rem;">{{ $row['akun']->kode_akun }}</code></td>
                        <td style="{{ $row['akun']->lev4 == 0 ? 'font-weight:600;' : 'padding-left:1.5rem; color:var(--uio-text-muted);' }}">
                            {{ $row['akun']->nama_akun }}
                        </td>
                        <td class="text-end">
                            {{ $row['total_debet'] > 0 ? number_format($row['total_debet'], 0, ',', '.') : '-' }}
                        </td>
                        <td class="text-end">
                            {{ $row['total_kredit'] > 0 ? number_format($row['total_kredit'], 0, ',', '.') : '-' }}
                        </td>
                    </tr>
                    @php
                        if($row['akun']->lev1 == 4) $totalPendapatan += $row['total_kredit'];
                        if($row['akun']->lev1 == 5) $totalBeban += $row['total_debet'];
                    @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background: var(--uio-bg); font-weight:700;">
                    <td colspan="2">Total Pendapatan</td>
                    <td class="text-end">-</td>
                    <td class="text-end" style="color: var(--uio-primary-dark);">
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </td>
                </tr>
                <tr style="background: var(--uio-bg); font-weight:700;">
                    <td colspan="2">Total Beban</td>
                    <td class="text-end" style="color: var(--uio-danger);">
                        Rp {{ number_format($totalBeban, 0, ',', '.') }}
                    </td>
                    <td class="text-end">-</td>
                </tr>
                <tr style="background: {{ ($totalPendapatan - $totalBeban) >= 0 ? '#D4ECD9' : '#F5D4D4' }}; font-weight:700; font-size:1rem;">
                    <td colspan="2">
                        {{ ($totalPendapatan - $totalBeban) >= 0 ? '✅ LABA BERSIH' : '❌ RUGI BERSIH' }}
                    </td>
                    <td colspan="2" class="text-end">
                        Rp {{ number_format(abs($totalPendapatan - $totalBeban), 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@endsection
