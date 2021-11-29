<div class="main-sidebar">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="index.html">PointOfSales</a>
          </div>
          <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">POS</a>
          </div>
          <ul class="sidebar-menu">
              <li class="menu-header">MENU</li>
              <!-- <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Dashboard</span></a>
                <ul class="dropdown-menu">
                  <li><a class="nav-link" href="index-0.html">General Dashboard</a></li>
                  <li><a class="nav-link" href="index.html">Example Dashboard</a></li>
                </ul>
              </li> -->
              <li><a class="nav-link" href="index.php"><i class="fas fa-fire"></i> <span>Dashboard</span></a></li>
              <?php if($_SESSION['user_level']==1){?>
                <li class="nav-item dropdown">
                  <a href="#" class="nav-link has-dropdown"><i class="fas fa-shopping-bag"></i><span>Penjualan</span></a>
                  <ul class="dropdown-menu">
                    <li><a class="nav-link" href="penjualan_eceran.php">Eceran</a></li>
                    <li><a class="nav-link" href="penjualan_grosir.php">Grosir</a></li>
                  </ul>
                </li>
                <li><a class="nav-link" href="barang.php"><i class="fas fa-shopping-cart"></i> <span>Barang</span></a></li>
                <li><a class="nav-link" href="kategori.php"><i class="fas fa-sitemap"></i> <span>Kategori</span></a></li>
                <li><a class="nav-link" href="suplier.php"><i class="fas fa-truck"></i> <span>Suplier</span></a></li>
                <li><a class="nav-link" href="pembelian.php"><i class="fas fa-cubes"></i> <span>Pembelian</span></a></li>
                <li><a class="nav-link" href="laporan.php"><i class="fas fa-file"></i> <span>Laporan</span></a></li>
                <li><a class="nav-link" href="pengguna.php"><i class="fas fa-users"></i> <span>Pengguna</span></a></li>
              <?php ;}
              if($_SESSION['user_level']==2){?>
                <li><a class="nav-link" href="barang.php"><i class="fas fa-shopping-cart"></i> <span>Barang</span></a></li>
                <li><a class="nav-link" href="kategori.php"><i class="fas fa-sitemap"></i> <span>Kategori</span></a></li>
                <li><a class="nav-link" href="suplier.php"><i class="fas fa-truck"></i> <span>Suplier</span></a></li>
                <li><a class="nav-link" href="laporan.php"><i class="fas fa-file"></i> <span>Laporan</span></a></li>
                <?php ;};
              if($_SESSION['user_level']==3){?>
                <li class="nav-item dropdown">
                  <a href="#" class="nav-link has-dropdown"><i class="fas fa-shopping-bag"></i><span>Penjualan</span></a>
                  <ul class="dropdown-menu">
                    <li><a class="nav-link" href="penjualan_eceran.php">Eceran</a></li>
                    <li><a class="nav-link" href="penjualan_grosir.php">Grosir</a></li>
                  </ul>
                </li>
              <?php ;}?>
              <li><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
            </ul>
        </aside>
      </div>