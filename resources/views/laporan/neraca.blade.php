@extends('layouts.app')

@section('title', 'Neraca')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-journal-text"></i> Neraca</h2>
</div>

{{-- Filter --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('laporan.neraca') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-select form-select-sm">
                    <option value="0" {{ $bulan == 0 ? 'selected' : '' }}>-- Semua Bulan --</option>
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
            <div class="col-md-2">
                <a href="{{ route('laporan.neraca', array_merge(request()->all(), ['cetak' => 1])) }}"
                   target="_blank" class="btn btn-outline-secondary btn-sm w-100">
                    <i class="bi bi-printer"></i> Cetak
                </a>
            </div>
        </form>
    </div>
</div>

<div class="row g-3">

    {{-- Aset --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #E0DBFF, #EDE8FF);">
                <strong><i class="bi bi-box-seam"></i> ASET</strong>
                <span style="float:right; font-size:0.8rem; color: var(--uio-text-muted);">
                    Periode: {{ $bulan > 0 ? DateTime::createFromFormat('!m', $bulan)->format('F') . ' ' : '' }}{{ $tahun }}
                </span>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0" style="font-size:0.85rem;">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Akun</th>
                            <th class="text-end">Debet</th>
                            <th class="text-end">Kredit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalAsetDebet = 0; $totalAsetKredit = 0; @endphp
                        @foreach($data as $row)
                            @if($row['akun']->lev1 == 1)
                            <tr>
                                <td><code style="font-size:0.78rem;">{{ $row['akun']->kode_akun }}</code></td>
                                <td style="{{ $row['akun']->lev4 == 0 ? 'font-weight:600;' : 'padding-left:1.5rem;color:var(--uio-text-muted);' }}">
                                    {{ $row['akun']->nama_akun }}
                                </td>
                                <td class="text-end">
                                    {{ $row['total_debet'] > 0 ? number_format($row['total_debet'], 0, ',', '.') : '-' }}
                                </td>
                                <td class="text-end">
                                    {{ $row['total_kredit'] > 0 ? number_format($row['total_kredit'], 0, ',', '.') : '-' }}
                                </td>
                            </tr>
                            @php $totalAsetDebet += $row['total_debet']; $totalAsetKredit += $row['total_kredit']; @endphp
                            @endif
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background: var(--uio-bg); font-weight:700;">
                            <td colspan="2">Total Aset</td>
                            <td class="text-end">{{ number_format($totalAsetDebet, 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($totalAsetKredit, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- Kewajiban & Modal --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #D4ECD9, #E8F5EA);">
                <strong><i class="bi bi-bank"></i> KEWAJIBAN & MODAL</strong>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0" style="font-size:0.85rem;">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Akun</th>
                            <th class="text-end">Debet</th>
                            <th class="text-end">Kredit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalKewModalDebet = 0; $totalKewModalKredit = 0; @endphp
                        @foreach($data as $row)
                            @if(in_array($row['akun']->lev1, [2, 3]))
                            <tr>
                                <td><code style="font-size:0.78rem;">{{ $row['akun']->kode_akun }}</code></td>
                                <td style="{{ $row['akun']->lev4 == 0 ? 'font-weight:600;' : 'padding-left:1.5rem;color:var(--uio-text-muted);' }}">
                                    {{ $row['akun']->nama_akun }}
                                </td>
                                <td class="text-end">
                                    {{ $row['total_debet'] > 0 ? number_format($row['total_debet'], 0, ',', '.') : '-' }}
                                </td>
                                <td class="text-end">
                                    {{ $row['total_kredit'] > 0 ? number_format($row['total_kredit'], 0, ',', '.') : '-' }}
                                </td>
                            </tr>
                            @php $totalKewModalDebet += $row['total_debet']; $totalKewModalKredit += $row['total_kredit']; @endphp
                            @endif
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background: var(--uio-bg); font-weight:700;">
                            <td colspan="2">Total Kewajiban & Modal</td>
                            <td class="text-end">{{ number_format($totalKewModalDebet, 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($totalKewModalKredit, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection
