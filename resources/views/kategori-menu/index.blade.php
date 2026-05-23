@extends('layouts.app')

@section('title', 'Kategori Menu')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-tags"></i> Kategori Menu</h2>
    <a href="{{ route('kategori-menu.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle"></i> Tambah Kategori
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th>Jumlah Menu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kategoriList as $kategori)
                <tr>
                    <td style="color: var(--uio-text-muted);">{{ $loop->iteration }}</td>
                    <td><strong>{{ $kategori->nama_kategori }}</strong></td>
                    <td style="color: var(--uio-text-muted); font-size:0.88rem;">
                        {{ $kategori->deskripsi ?? '-' }}
                    </td>
                    <td>
                        <span class="badge badge-uio-info">{{ $kategori->menus_count }} menu</span>
                    </td>
                    <td>
                        <a href="{{ route('kategori-menu.edit', $kategori) }}"
                           class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('kategori-menu.destroy', $kategori) }}"
                              style="display:inline;"
                              onsubmit="return confirm('Yakin hapus kategori ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4" style="color: var(--uio-text-muted);">
                        <i class="bi bi-inbox"></i> Belum ada kategori menu
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($kategoriList->hasPages())
    <div class="card-footer" style="background: var(--uio-sidebar);">
        {{ $kategoriList->links() }}
    </div>
    @endif
</div>

@endsection
