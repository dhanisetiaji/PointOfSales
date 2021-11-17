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
    if(isset($_GET['del'])){
        $id=$_GET['del'];
        $sql = "delete from tbl_kategori  WHERE kategori_id=:id";
        $query = $dbh->prepare($sql);
        $query -> bindParam(':id',$id, PDO::PARAM_STR);
        $query -> execute();
        $msg = "Data Berhasil di hapus!";
      }
    if(isset($_POST['tambah'])){
        $nama_kategori = $_POST['kategori_name'];
        $qtambah = "INSERT INTO tbl_kategori(kategori_name) VALUES(:kategori_name)";
        $tambah = $dbh->prepare($qtambah);
        $tambah -> bindParam(':kategori_name',$nama_kategori, PDO::PARAM_STR);
        $tambah -> execute();
        $msg = "Data Berhasil di tambah!";

    }
    if(isset($_POST['update'])){
        $kategori_id = $_POST['kategori_id'];
        $kategori_name = $_POST['kategori_name'];
        $qtambah = "UPDATE tbl_kategori SET kategori_name=:kategori_name WHERE kategori_id=:kategori_id";
        $tambah = $dbh->prepare($qtambah);
        $tambah -> bindParam(':kategori_id',$kategori_id, PDO::PARAM_STR);
        $tambah -> bindParam(':kategori_name',$kategori_name, PDO::PARAM_STR);
        $tambah -> execute();
        $msg = "Data Berhasil di update!";
    }
?>
<?php include('./include/head.php');?>
<?php include('./include/navbar.php');?>
<?php include('./include/sidebar.php');?>

<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Pembelian Eceran</h1>
      <div class="section-header-breadcrumb">
          </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <button class="btn btn-primary btn-icon icon-left" data-toggle="modal" data-target="#AddProduk"><i class="fas fa-plus"></i>Produk</button>
                                <table id="example3" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Satuan</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Sub Total</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php 
                                    function rupiah($angka){
                
                                        $hasil_rupiah = "Rp " . number_format($angka,2,',','.');
                                        return $hasil_rupiah;
                                    
                                    }
                                    $sql = "select * from keranjang_tmp";
                                    $query = $dbh -> prepare($sql);
                                    $query->execute();
                                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                                    $nmr=1;
                                    $totalbelanja = 0;
                                    // $ket = array();
                                    if($query->rowCount() > 0){
                                        foreach($results as $res){
                                        $total = $res->qty*$res->price;
                                        $totalbelanja += $total;
                                        // $ket[] = "$res->nama_produk ($res->qty)";                    
                                ?>
                            <tr>
                                
                                <td><?php echo htmlentities($nmr);?></td>
                                <td><?php echo htmlentities($res->nama_produk);?></td>
                                <td><?php echo htmlentities($res->qty);?></td>
                                <td><?php echo htmlentities(rupiah($res->price));?></td>
                                <td><?php echo htmlentities(rupiah($total));?></td>
                                <td>
                                    <!-- <a type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#MyModal<?php echo $res->id_pasien;?>"><i class="fas fa-edit"></i></a> -->
                                    <a href="order.php?del=<?php echo $res->id_produk;?>" onclick="return confirm('Do you want to delete');" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php $nmr=$nmr+1; } } ?>
                            </tbody>
                            </table>
                            <form action="" method="post">
                                <table>
                                    <tr>
                                        <td style="width:760px;" rowspan="2"></td>
                                        <th style="width:140px;">Total Belanja(Rp)</th>
                                        <th style="text-align:right;width:140px;"><input type="text" name="total2" value="<?= $totalbelanja?>" class="form-control input-sm" style="text-align:right;margin-bottom:5px;" readonly></th>
                                        <input type="hidden" id="total" name="total" value="<?= $totalbelanja?>" class="form-control input-sm" style="text-align:right;margin-bottom:5px;" readonly>
                                        
                                    </tr>
                                    <tr>
                                        <th>Tunai(Rp)</th>
                                        <th style="text-align:right;"><input type="text" id="jml_uang" name="jml_uang" class="jml_uang form-control input-sm" style="text-align:right;margin-bottom:5px;" required></th>
                                        <input type="hidden" id="jml_uang2" name="jml_uang2" class="form-control input-sm" style="text-align:right;margin-bottom:5px;" required>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <th>Kembalian(Rp)</th>
                                        <th style="text-align:right;"><input type="text" id="kembalian" name="kembalian" class="form-control input-sm" style="text-align:right;margin-bottom:5px;" readonly></th>
                                    </tr>
                                    <tr>
                                    <td></td>
                                    <th></th>
                                    <th style="text-align:right;"><button type="submit" name="simpan" class="btn btn-info btn-lg"> Simpan</button></th>
                                    </tr>
                                </table>
                            </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="AddPesan">
          <div class="modal-dialog" >
              <div class="modal-content">
                  <div class="modal-header">
                  <h4 class="modal-title">Order!</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
                  </div>
                  <div class="modal-body">
                      <form method="post">
                          <div class="form-group">
                              <label for="">Produk</label>
                              <select name="id_produk" class="form-control" required>
                              <?php 
                                  $sql = "select * from produk";
                                  $query = $dbh -> prepare($sql);
                                  $query->execute();
                                  $results=$query->fetchAll(PDO::FETCH_OBJ);
                                  if($query->rowCount() > 0){
                                      foreach($results as $res){
                              ?>
                              <option value="<?= $res->id_produk?>"><?= $res->nama_produk?></option>
                              <?php }}?>
                              </select>
                          </div>
                          <div class="form-group">
                              <label for="">Qty</label>
                              <!-- <input type="hidden" name="id" class="form-control" value="<?php echo htmlentities($res->id)?>"> -->
                              <input type="text" name="qty" class="form-control" required>
                              <button Type="submit" name="pesan" class="btn btn-primary mt-4">Pesan</button>
                              <!-- <button Type="submit" name="updatestok" class="btn btn-primary mt-4">Update</button> -->
                          </div>
                      </form>
                  </div>
                  <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  
                  </div>
              </div>
              <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
          </div>
    </div>
  </section>
</div>
<?php include('./include/footer.php');?>
<?php include('./include/script.php');?>
  <!-- Page Specific JS File -->
<script src="assets/js/page/modules-toastr.js"></script>
</body>
</html>


<?php } ?>