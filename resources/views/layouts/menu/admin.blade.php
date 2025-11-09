<ul class="sidebar-menu">
    <li class="sidebar-menu__item">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-menu__link">
            <span class="icon"><i class="ph ph-users-three"></i></span>
            <span class="text">Dashboard</span>
        </a>
    </li>
    <li class="sidebar-menu__item has-dropdown">
        <a href="javascript:void(0)" class="sidebar-menu__link">
            <span class="icon"><i class="ph ph-squares-four"></i></span>
            <span class="text">Data Member</span>
            {{-- <span class="link-badge">3</span> --}}
        </a>
        <!-- Submenu start -->
        <ul class="sidebar-submenu">
            <li class="sidebar-submenu__item">
                <a href="{{ route('admin.nasabah.index') }}" class="sidebar-submenu__link"> Data
                    Nasabah </a>
            </li>
            <li class="sidebar-submenu__item">
                <a href="{{ route('admin.petugas.index') }}" class="sidebar-submenu__link"> Data
                    Petugas </a>
            </li>
            <li class="sidebar-submenu__item">
                <a href="{{ route('admin.sampah.index') }}" class="sidebar-submenu__link"> Data
                    Sampah
                </a>
            </li>
            <li class="sidebar-submenu__item">
                <a href="{{ route('admin.gudangs.index') }}" class="sidebar-submenu__link"> Data
                    Customer </a>
            </li>
            <li class="sidebar-submenu__item">
                <a href="{{ route('admin.cabang.index') }}" class="sidebar-submenu__link"> Data
                    Collaction Center </a>
            </li>
        </ul>
        <!-- Submenu End -->
    </li>
    <li class="sidebar-menu__item has-dropdown">
        <a href="javascript:void(0)" class="sidebar-menu__link">
            <span class="icon"><i class="ph ph-squares-four"></i></span>
            <span class="text">Manajemen Konten</span>
            {{-- <span class="link-badge">3</span> --}}
        </a>
        <!-- Submenu start -->
        <ul class="sidebar-submenu">
            {{-- <li class="sidebar-submenu__item">
                <a href="{{ route('admin.banner.index') }}" class="sidebar-submenu__link"> Data
                    Banner
                </a>
            </li>
            <li class="sidebar-submenu__item">
                <a href="{{ route('admin.artikel.index') }}" class="sidebar-submenu__link"> Data
                    Artikel </a>
            </li> --}}
            <li class="sidebar-submenu__item">
                <a href="{{ route('admin.time.index') }}" class="sidebar-submenu__link"> Tim </a>
            </li>
            <li class="sidebar-submenu__item">
                <a href="{{ route('admin.labels.index') }}" class="sidebar-submenu__link"> Label </a>
            </li>
            <li class="sidebar-submenu__item">
                <a href="{{ route('admin.activities.index') }}" class="sidebar-submenu__link"> Artikel </a>
            </li>
            <li class="sidebar-submenu__item">
                <a href="{{ route('admin.cleans.index') }}" class="sidebar-submenu__link"> Mitra </a>
            </li>
        </ul>
        <!-- Submenu End -->
    </li>
    <li class="sidebar-menu__item has-dropdown">
        <a href="javascript:void(0)" class="sidebar-menu__link">
            <span class="icon"><i class="ph ph-squares-four"></i></span>
            <span class="text">Transaksi</span>
            {{-- <span class="link-badge">3</span> --}}
        </a>
        <!-- Submenu start -->
        <ul class="sidebar-submenu">
            <li class="sidebar-submenu__item">
                <a href="{{ route('admin.transaksi.index') }}" class="sidebar-submenu__link">
                    Transaksi
                    Setoran </a>
            </li>
            <li class="sidebar-submenu__item">
                <a href="{{ route('admin.tarik-saldo.index') }}" class="sidebar-submenu__link">
                    Tarik
                    Sald </a>
            </li>
            <li class="sidebar-submenu__item">
                <a href="{{ route('admin.pengiriman.index') }}" class="sidebar-submenu__link">
                    Pengiriman Sampah </a>
            </li>
        </ul>
        <!-- Submenu End -->
    </li>
    <li class="sidebar-menu__item">
        <a href="{{ route('admin.topup.index') }}" class="sidebar-menu__link">
            <span class="icon"><i class="ph ph-wallet"></i></span>
            <span class="text">Top Up Saldo Utama</span>
        </a>
    </li>
    <li class="sidebar-menu__item">
        <a href="{{ route('admin.laporan.index') }}" class="sidebar-menu__link">
            <span class="icon"><i class="ph ph-users-three"></i></span>
            <span class="text">Cetak Laporan</span>
        </a>
    </li>
    <li class="sidebar-menu__item has-dropdown">
        <a href="javascript:void(0)" class="sidebar-menu__link">
            <span class="icon"><i class="ph ph-squares-four"></i></span>
            <span class="text">Pengaturan</span>
            {{-- <span class="link-badge">3</span> --}}
        </a>
        <!-- Submenu start -->
        <ul class="sidebar-submenu">
            <li class="sidebar-submenu__item">
                <a href="{{ route('admin.token-whatsapp.index') }}" class="sidebar-submenu__link">
                    Token WhatsApp </a>
            </li>
            {{-- <li class="sidebar-submenu__item">
                                <a href="{{ route('admin.aplikasi.index') }}" class="sidebar-submenu__link"> Update APK </a>
                            </li> --}}
            <li class="sidebar-submenu__item">
                <a href="{{ route('admin.tentang_kami.index') }}" class="sidebar-submenu__link">
                    Tentang Kami </a>
            </li>
            <li class="sidebar-submenu__item">
                <a href="{{ route('admin.settings.index') }}" class="sidebar-submenu__link">
                    Applikasi </a>
            </li>
            <li class="sidebar-submenu__item">
                <a href="{{ route('admin.faceUser') }}" class="sidebar-submenu__link">
                    Fack User </a>
            </li>
        </ul>
        <!-- Submenu End -->
    </li>
    {{-- <li class="sidebar-menu__item">
                        <a href="{{ route('admin.feedback.index') }}" class="sidebar-menu__link">
                            <span class="icon"><i class="ph ph-users-three"></i></span>
                            <span class="text">Feedback</span>
                        </a>
                    </li> --}}
</ul>
