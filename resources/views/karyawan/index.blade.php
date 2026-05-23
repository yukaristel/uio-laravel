@extends('layouts.app')

@section('title', 'Karyawan')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-people"></i> Daftar Karyawan</h2>
    <a href="{{ route('karyawan.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle"></i> Tambah Karyawan
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Lengkap</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($karyawanList as $karyawan)
                <tr>
                    <td style="color: var(--uio-text-muted);">{{ $loop->iteration }}</td>
                    <td><strong>{{ $karyawan->nama_lengkap }}</strong></td>
                    <td><code>{{ $karyawan->username }}</code></td>
                    <td style="color: var(--uio-text-muted);">{{ $karyawan->email }}</td>
                    <td>
                        @if($karyawan->role === 'admin')
                            <span class="badge badge-uio-danger">Admin</span>
                        @elseif($karyawan->role === 'kasir')
                            <span class="badge badge-uio-success">Kasir</span>
                        @else
                            <span class="badge badge-uio-info">Karyawan</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('karyawan.edit', $karyawan) }}"
                           class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        @if($karyawan->id !== auth()->id())
                        <form method="POST" action="{{ route('karyawan.destroy', $karyawan) }}"
                              style="display:inline;"
                              onsubmit="return confirm('Yakin hapus karyawan ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4" style="color: var(--uio-text-muted);">
                        <i class="bi bi-inbox"></i> Belum ada karyawan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($karyawanList->hasPages())
    <div class="card-footer" style="background: var(--uio-sidebar);">
        {{ $karyawanList->links() }}
    </div>
    @endif
</div>

@endsection
