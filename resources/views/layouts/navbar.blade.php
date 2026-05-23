<nav class="navbar navbar-expand-lg navbar-uio">
    <div class="container-fluid px-4">

        {{-- Brand --}}
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
            <i class="bi bi-shop"></i> UIO
        </a>

        {{-- Toggler --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                {{-- Dashboard --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                       href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>

                {{-- Operasional --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('transaksi.*','pembelian-bahan.*','stock.*') ? 'active' : '' }}"
                       href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-activity"></i> Operasional
                    </a>
                    <ul class="dropdown-menu">
                        <li><h6 class="dropdown-header" style="font-size:0.72rem;">PENJUALAN</h6></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('transaksi.index') }}">
                                <i class="bi bi-receipt"></i> Riwayat Transaksi
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><h6 class="dropdown-header" style="font-size:0.72rem;">PEMBELIAN & STOK</h6></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('pembelian-bahan.index') }}">
                                <i class="bi bi-cart-plus"></i> Pembelian Bahan
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('stock.movement') }}">
                                <i class="bi bi-arrow-left-right"></i> Stock Movement
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('stock.opname') }}">
                                <i class="bi bi-clipboard-check"></i> Stock Opname
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Master Data --}}
                @if(auth()->user()->role === 'admin')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('bahan-baku.*','kategori-menu.*','menu-makanan.*','karyawan.*') ? 'active' : '' }}"
                       href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-grid"></i> Master Data
                    </a>
                    <ul class="dropdown-menu">
                        <li><h6 class="dropdown-header" style="font-size:0.72rem;">MENU & BAHAN</h6></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('bahan-baku.index') }}">
                                <i class="bi bi-box-seam"></i> Bahan Baku
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('kategori-menu.index') }}">
                                <i class="bi bi-tags"></i> Kategori Menu
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('menu-makanan.index') }}">
                                <i class="bi bi-egg-fried"></i> Menu Makanan
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><h6 class="dropdown-header" style="font-size:0.72rem;">SDM & ASET</h6></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('karyawan.index') }}">
                                <i class="bi bi-people"></i> Karyawan
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('aset-tetap.index') }}">
                                <i class="bi bi-building"></i> Aset Tetap
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                {{-- Keuangan --}}
                @if(in_array(auth()->user()->role, ['admin', 'karyawan']))
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('jurnal.*','laporan.*') ? 'active' : '' }}"
                       href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-cash-stack"></i> Keuangan
                    </a>
                    <ul class="dropdown-menu">
                        <li><h6 class="dropdown-header" style="font-size:0.72rem;">JURNAL</h6></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('jurnal.index') }}">
                                <i class="bi bi-journal-plus"></i> Jurnal Umum
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><h6 class="dropdown-header" style="font-size:0.72rem;">LAPORAN</h6></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('laporan.harian') }}">
                                <i class="bi bi-calendar-day"></i> Laporan Harian
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('laporan.bulanan') }}">
                                <i class="bi bi-calendar-month"></i> Laporan Bulanan
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('laporan.stok') }}">
                                <i class="bi bi-box"></i> Laporan Stok
                            </a>
                        </li>
                        @if(auth()->user()->role === 'admin')
                        <li><hr class="dropdown-divider"></li>
                        <li><h6 class="dropdown-header" style="font-size:0.72rem;">AKUNTANSI</h6></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('laporan.jurnal-transaksi') }}">
                                <i class="bi bi-journal-bookmark"></i> Jurnal Transaksi
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('laporan.neraca') }}">
                                <i class="bi bi-journal-text"></i> Neraca
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('laporan.labarugi') }}">
                                <i class="bi bi-graph-up"></i> Laba Rugi
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

            </ul>

            {{-- Tombol POS + User --}}
            <ul class="navbar-nav ms-auto align-items-center gap-2">

                {{-- Tombol POS --}}
                <li class="nav-item">
                    <a href="{{ route('transaksi.create') }}"
                       class="btn d-flex align-items-center gap-2"
                       style="background-color: var(--uio-accent); color: white; font-weight: 600; border-radius: 10px; padding: 0.45rem 1rem; font-size: 0.9rem; box-shadow: 0 2px 8px rgba(200,158,196,0.4);">
                        <i class="bi bi-lightning-charge-fill"></i> POS
                    </a>
                </li>

                {{-- User Info & Logout --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2"
                       href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i>
                        {{ auth()->user()->nama_lengkap }}
                        <span class="badge" style="background-color:rgba(255,255,255,0.25);font-size:0.7rem;">
                            {{ ucfirst(auth()->user()->role) }}
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>
