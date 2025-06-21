<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="{{ route('home') }}" class="b-brand text-primary">
                <!-- ========   Change your logo from here   ============ -->
                <img src="../assets/images/logo-ajojing.png" class="img-fluid logo-lg" alt="">
            </a>
        </div>
        <div class="navbar-content">
            <ul class="pc-navbar">
                @role('admin')
                    <li class="pc-item">
                        <a href="{{ route('dashboard') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('admin.users.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-users"></i></span>
                            <span class="pc-mtext">Manajemen User</span>
                        </a>
                    </li>
                @endrole

                <li class="pc-item">
                    @role('admin')
                        <a href="{{ route('admin.barang.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-package"></i></span>
                            <span class="pc-mtext">Barang</span>
                        </a>
                        @elserole('pegawai')
                        <a href="{{ route('pegawai.barang.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-package"></i></span>
                            <span class="pc-mtext">Barang</span>
                        </a>
                    @endrole
                </li>

                <li class="pc-item">
                    @role('admin')
                        <a href="{{ route('admin.order.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-clipboard-list"></i></span>
                            <span class="pc-mtext">Pesanan</span>
                        </a>
                        @elserole('pegawai')
                        <a href="{{ route('pegawai.order.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-clipboard-list"></i></span>
                            <span class="pc-mtext">Pesanan Baru</span>
                        </a>
                    @endrole
                </li>

                @role('pegawai')
                    <li class="pc-item">
                        <a href="{{ route('pegawai.order.list') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-clipboard-list"></i></span>
                            <span class="pc-mtext">Pesanan Diproses</span>
                        </a>
                    </li>
                @endrole

                @role('admin')
                    <li class="pc-item pc-caption">
                        <label>Log Aktivitas</label>
                        <i class="ti ti-dashboard"></i>
                    </li>

                    {{-- <li class="pc-item">
                        <a href="{{ route('admin.activity-log.login-log.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-login"></i></span>
                            <span class="pc-mtext">User Logs</span>
                        </a>
                    </li> --}}
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-history"></i></span>
                            <span class="pc-mtext">Log Aktivitas</span>
                            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="pc-submenu">
                            <li class="pc-item">
                                <a class="pc-link" href="{{ route('admin.activity-log.login-log.index') }}">Log Login</a>
                            </li>
                            <li class="pc-item">
                                <a class="pc-link" href="{{ route('admin.activity-log.items-log.index') }}">Log Barang</a>
                            </li>
                        </ul>
                    </li>
                @endrole
            </ul>
        </div>
    </div>
</nav>
