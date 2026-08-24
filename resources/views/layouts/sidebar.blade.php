<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('home') }}" class="brand-link">
        <img src="{{ asset('assets-template/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">CRUD PHP</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('assets-template/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::guard('akun')->user()->nama ?? 'User' }}</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-header">Daftar Menu</li>

                @php $levelUser = Auth::guard('akun')->user()->level; @endphp

                @if (in_array($levelUser, ['1', '2']))
                    <li class="nav-item">
                        <a href="{{ route('barang.index') }}"
                            class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-box"></i>
                            <p>Data Barang</p>
                        </a>
                    </li>
                @endif

                @if (in_array($levelUser, ['1', '3']))
                    <li class="nav-item">
                        <a href="{{ route('mahasiswa.index') }}"
                            class="nav-link {{ request()->routeIs('mahasiswa.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-graduate"></i>
                            <p>Data Mahasiswa</p>
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <a href="{{ route('pegawai.index') }}"
                        class="nav-link {{ request()->routeIs('pegawai.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Data Pegawai</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('akun.index') }}"
                        class="nav-link {{ request()->routeIs('akun.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-shield"></i>
                        <p>Data Akun</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>