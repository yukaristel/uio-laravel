@extends('layouts.app')

@section('title', 'Menu Makanan')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-egg-fried"></i> Menu Makanan</h2>
    <div class="d-flex gap-2">
        <form method="POST" action="{{ route('menu-makanan.generate-hpp') }}"
              onsubmit="return confirm('Generate ulang HPP semua menu berdasarkan harga bahan baku terkini?')">
            @csrf
            <button type="submit" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-calculator"></i> Generate HPP
            </button>
        </form>
        <a href="{{ route('menu-makanan.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Tambah Menu
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Menu</th>
                    <th>Kategori</th>
                    <th>Harga Modal</th>
                    <th>Harga Jual</th>
                    <th>Margin</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($menuList as $menu)
                <tr>
                    <td><code>{{ $menu->kode_menu }}</code></td>
                    <td><strong>{{ $menu->nama_menu }}</strong></td>
                    <td>
                        <span class="badge badge-uio-info">{{ $menu->kategori->nama_kategori }}</span>
                    </td>
                    <td>Rp {{ number_format($menu->harga_modal, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($menu->harga_jual, 0, ',', '.') }}</td>
                    <td style="color: var(--uio-primary-dark);">
                        Rp {{ number_format($menu->margin_keuntungan, 0, ',', '.') }}
                    </td>
                    <td>
                        @if($menu->status === 'tersedia')
                            <span class="badge badge-uio-success">Tersedia</span>
                        @elseif($menu->status === 'habis')
                            <span class="badge badge-uio-danger">Habis</span>
                        @else
                            <span class="badge badge-uio-warning">Tidak Tersedia</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('menu-makanan.show', $menu) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('menu-makanan.edit', $menu) }}"
                           class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('menu-makanan.destroy', $menu) }}"
                              style="display:inline;"
                              onsubmit="return confirm('Yakin hapus menu ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4" style="color: var(--uio-text-muted);">
                        <i class="bi bi-inbox"></i> Belum ada menu makanan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($menuList->hasPages())
    <div class="card-footer" style="background: var(--uio-sidebar);">
        {{ $menuList->links() }}
    </div>
    @endif
</div>

@endsection
