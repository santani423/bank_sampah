 <ul class="sidebar-menu">
     <li class="sidebar-menu__item">
         <a href="{{ route('petugas.dashboard') }}" class="sidebar-menu__link">
             <span class="icon"><i class="ph ph-users-three"></i></span>
             <span class="text">Dashboard</span>
         </a>
     </li>

     <li class="sidebar-menu__item has-dropdown">
         <a href="javascript:void(0)" class="sidebar-menu__link">
             <span class="icon"><i class="ph ph-squares-four"></i></span>
             <span class="text">Data Master</span>
             {{-- <span class="link-badge">3</span> --}}
         </a>
         <!-- Submenu start -->
         <ul class="sidebar-submenu">
             <li class="sidebar-submenu__item">
                 <a href="{{ route('petugas.nasabah.index') }}" class="sidebar-submenu__link">
                     Data
                     Nasabah </a>
             </li>
             <li class="sidebar-submenu__item">
                 <a href="{{ route('petugas.rekanan.index') }}" class="sidebar-submenu__link">
                     Data
                     Rekanan </a>
             </li>
             <li class="sidebar-submenu__item">
                 <a href="{{ route('petugas.lapak.index') }}" class="sidebar-submenu__link">
                     Data
                     Lapak </a>
             </li>
         </ul>
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
                 <a href="{{ route('petugas.transaksi.index') }}" class="sidebar-submenu__link">
                     Transaksi Setoran </a>
             </li>
             <li class="sidebar-submenu__item">
                 <a href="{{ route('petugas.transaksi.top-up') }}" class="sidebar-submenu__link">
                     Top Up </a>
             </li>
             <li class="sidebar-submenu__item">
                 <a href="{{ route('petugas.pengiriman.index') }}" class="sidebar-submenu__link">
                     Pengiriman Sampah </a>
             </li>
         </ul>
     </li>
 </ul>
