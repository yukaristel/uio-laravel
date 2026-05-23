@extends('layouts.app')

@section('title', 'Stock Opname')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-clipboard-check"></i> Stock Opname</h2>
</div>

<div class="row g-3">

    {{-- Form Input Opname --}}
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-plus-circle"></i> Input Stock Opname
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('stock.opname.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Tanggal Opname <span class="text-danger">*</span></label>
                        <input type="date"
                               name="tanggal_opname"
                               class="form-control @error('tanggal_opname') is-invalid @enderror"
                               value="{{ old('tanggal_opname', now()->format('Y-m-d')) }}">
                        @error('tanggal_opname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bahan Baku <span class="text-danger">*</span></label>
                        <select name="bahan_id"
                                class="form-select @error('bahan_id') is-invalid @enderror"
                                id="bahanSelect">
                            <option value="">-- Pilih Bahan --</option>
                            @foreach($bahanList as $bahan)
                                <option value="{{ $bahan->id }}"
                                        data-stok="{{ $bahan->stok_tersedia }}"
                                        data-satuan="{{ $bahan->satuan }}"
                                        {{ old('bahan_id') == $bahan->id ? 'selected' : '' }}>
                                    {{ $bahan->nama_bahan }} (Stok: {{ $bahan->stok_tersedia }} {{ $bahan->satuan }})
                                </option>
                            @endforeach
                        </select>
                        @error('bahan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stok Sistem</label>
                        <input type="text" id="stokSistem" class="form-control"
                               value="-" readonly
                               style="background: var(--uio-bg);">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stok Fisik <span class="text-danger">*</span></label>
                        <input type="number"
                               name="stok_fisik"
                               class="form-control @error('stok_fisik') is-invalid @enderror"
                               value="{{ old('stok_fisik', 0) }}"
                               step="0.01" min="0">
                        @error('stok_fisik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jenis Selisih</label>
                        <select name="jenis_selisih" class="form-select">
                            <option value="">-- Pilih jika ada selisih --</option>
                            @foreach(['hilang','rusak','expired','tumpah','salah_hitung','lainnya'] as $j)
                                <option value="{{ $j }}" {{ old('jenis_selisih') === $j ? 'selected' : '' }}>
                                    {{ ucfirst($j) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2"
                                  placeholder="Keterangan tambahan">{{ old('keterangan') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-save"></i> Simpan Opname
                    </button>

                </form>
            </div>
        </div>
    </div>

    {{-- Riwayat Opname --}}
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history"></i> Riwayat Stock Opname
            </div>
            <div class="card-body p-0">
                <table class="table mb-0" style="font-size:0.85rem;">
                    <thead>
                        <tr>
                            <th>No. Opname</th>
                            <th>Tanggal</th>
                            <th>Bahan</th>
                            <th>Stok Sistem</th>
                            <th>Stok Fisik</th>
                            <th>Selisih</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($opnameList as $o)
                        <tr>
                            <td><code>{{ $o->no_opname }}</code></td>
                            <td style="color: var(--uio-text-muted);">
                                {{ $o->tanggal_opname->format('d/m/Y') }}
                            </td>
                            <td><strong>{{ $o->bahanBaku->nama_bahan }}</strong></td>
                            <td>{{ number_format($o->stok_sistem, 2, ',', '.') }}</td>
                            <td>{{ number_format($o->stok_fisik, 2, ',', '.') }}</td>
                            <td>
                                @if($o->selisih > 0)
                                    <span style="color: var(--uio-primary-dark);">
                                        +{{ number_format($o->selisih, 2, ',', '.') }}
                                    </span>
                                @elseif($o->selisih < 0)
                                    <span style="color: var(--uio-danger);">
                                        {{ number_format($o->selisih, 2, ',', '.') }}
                                    </span>
                                @else
                                    <span style="color: var(--uio-text-muted);">0</span>
                                @endif
                            </td>
                            <td>
                                @if($o->status === 'approved')
                                    <span class="badge badge-uio-success">Approved</span>
                                @else
                                    <span class="badge badge-uio-warning">Draft</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4" style="color: var(--uio-text-muted);">
                                <i class="bi bi-inbox"></i> Belum ada data opname
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($opnameList->hasPages())
            <div class="card-footer" style="background: var(--uio-sidebar);">
                {{ $opnameList->links() }}
            </div>
            @endif
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
document.getElementById('bahanSelect').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    document.getElementById('stokSistem').value =
        opt.dataset.stok ? opt.dataset.stok + ' ' + opt.dataset.satuan : '-';
});
</script>
@endpush
