 <ul class="sidebar-menu">
     <li class="sidebar-menu__item">
         <a href="{{ route('nasabah.dashboard') }}" class="sidebar-menu__link">
             <span class="icon"><i class="bi bi-speedometer2"></i></span>
             <span class="text">Dashboard</span>
         </a>
     </li>

     <li class="sidebar-menu__item has-dropdown">
         <a href="javascript:void(0)" class="sidebar-menu__link">
             <span class="icon"><i class="bi bi-cash-stack"></i></span>
             <span class="text">Transaksi</span>
             {{-- <span class="link-badge">3</span> --}}
         </a>
         <!-- Submenu start -->
         <ul class="sidebar-submenu">
             <li class="sidebar-submenu__item">
                 <a href="{{ route('nasabah.transaksi.setoran') }}" class="sidebar-submenu__link">
                     Setoran Sampah</a>
             </li>
             <li class="sidebar-submenu__item">
                 <a href="{{ route('nasabah.transaksi.penarikan') }}" class="sidebar-submenu__link">
                     Tarik Saldo</a>
             </li>
             <li class="sidebar-submenu__item">
                 <a href="{{ route('nasabah.metode-penarikan.index') }}" class="sidebar-submenu__link">
                     Metode Penarikan</a>
             </li>
         </ul>
     </li>
     <li class="sidebar-menu__item">
         <a href="{{ route('nasabah.cabang.index') }}" class="sidebar-menu__link">
             <span class="icon"><i class="bi bi-geo-alt"></i></span>
             <span class="text">Cabang</span>
         </a>
     </li>
 </ul>
