<?php
session_start();
// echo $_SESSION['username'];
// echo $_SESSION['user_level'];
error_reporting(0);
include('./include/koneksi.php');
if(strlen($_SESSION['username'])==0)
	{	
header('location:login.php');
}
else{
?>
<?php include('./include/head.php');?>
<?php include('./include/navbar.php');?>
<?php include('./include/sidebar.php');?>

<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Laporan</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Laporan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Laporan Data Barang</td>
                                    <td>
                                    <a class="btn btn-sm btn-warning" href="./laporan/lap_barang.php" target="_blank"><span class="fa fa-print"></span> Print</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Laporan Stok Barang</td>
                                    <td>
                                    <a class="btn btn-sm btn-warning" href="./laporan/lap_stok.php" target="_blank"><span class="fa fa-print"></span> Print</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Laporan Penjualan</td>
                                    <td>
                                    <a class="btn btn-sm btn-warning" href="./laporan/lap_penjualan.php" target="_blank"><span class="fa fa-print"></span> Print</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </section>
</div>
<?php include('./include/footer.php');?>
<?php include('./include/script.php');?>
</body>
</html>


<?php } ?>