@extends('layouts.app')

@section('title', 'Jurnal Umum')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-journal-plus"></i> Jurnal Umum</h2>
    <a href="{{ route('jurnal.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle"></i> Input Jurnal
    </a>
</div>

{{-- Filter --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('jurnal.index') }}" class="row g-2 align-items-end">
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
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Rekening Debet</th>
                    <th>Rekening Kredit</th>
                    <th>Jumlah</th>
                    <th>Input Oleh</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jurnalList as $jurnal)
                <tr>
                    <td style="font-size:0.85rem; color: var(--uio-text-muted);">
                        {{ \Carbon\Carbon::parse($jurnal->tgl_transaksi)->format('d/m/Y') }}
                    </td>
                    <td>{{ $jurnal->keterangan_transaksi }}</td>
                    <td>
                        <span class="badge badge-uio-info">{{ $jurnal->rekening_debet }}</span>
                        <div style="font-size:0.78rem; color: var(--uio-text-muted);">
                            {{ $jurnal->rekeningDebet->nama_akun ?? '-' }}
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-uio-warning">{{ $jurnal->rekening_kredit }}</span>
                        <div style="font-size:0.78rem; color: var(--uio-text-muted);">
                            {{ $jurnal->rekeningKredit->nama_akun ?? '-' }}
                        </div>
                    </td>
                    <td><strong>Rp {{ number_format($jurnal->jumlah, 0, ',', '.') }}</strong></td>
                    <td style="font-size:0.85rem;">{{ $jurnal->user->nama_lengkap ?? '-' }}</td>
                    <td>
                        <a href="{{ route('jurnal.edit', $jurnal) }}"
                           class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('jurnal.destroy', $jurnal) }}"
                              style="display:inline;"
                              onsubmit="return confirm('Yakin hapus jurnal ini? Saldo akan ikut berubah!')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4" style="color: var(--uio-text-muted);">
                        <i class="bi bi-inbox"></i> Belum ada jurnal untuk periode ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($jurnalList->hasPages())
    <div class="card-footer" style="background: var(--uio-sidebar);">
        {{ $jurnalList->links() }}
    </div>
    @endif
</div>

@endsection
