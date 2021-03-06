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
if($_SESSION['user_level']!=1){
    ?> <script language="JavaScript">alert('Anda tidak memiliki akses');</script>
    <a href="index.php">Go Back</a>
        <?php
        // header('Location:./dashboard.php');
    }
else{
    if(isset($_GET['del'])){
        $id=$_GET['del'];
        $sql = "delete from tbl_beli_tmp  WHERE barang_id=:id";
        $query = $dbh->prepare($sql);
        $query -> bindParam(':id',$id, PDO::PARAM_STR);
        $query -> execute();
        $msg = "Data Berhasil di hapus!";
      }
    if(isset($_POST['pesan'])){
        $barang_id = $_POST['barang_id'];
        $jumlah = $_POST['jumlah'];
        $harpok = $_POST['harpok'];

        $queryadd = "INSERT INTO tbl_beli_tmp(barang_id,harpok,jumlah,user_id) VALUES(:barang_id,:harpok,:jumlah,:user_id)";
        $add = $dbh->prepare($queryadd);
        $add->bindParam(':barang_id',$barang_id);
        $add->bindParam(':harpok',$harpok);
        $add->bindParam(':jumlah',$jumlah);
        $add->bindParam(':user_id',$_SESSION['id']);
        $add->execute();
        $msg = "Produk Berhasil ditambah!";

    }
    if(isset($_POST['simpan'])){
        $sql = $dbh->prepare("SELECT max(beli_kode) as id FROM tbl_beli");
        $sql->execute();
        $hasil = $sql->fetch();    
        $kode = $hasil['id'];
        $noUrut = (int) substr($kode, 3);
        $noUrut++;
        $char = "BL";
        $newID = $char . sprintf("%04s", $noUrut);
        $beli_kode = $newID;
        $user_id = $_SESSION['id'];
        $suplier_id = $_POST['suplier_id'];
        $nofak = $_POST['nofak'];
        $tgl = $_POST['tgl'];
        
        $queryadd = "INSERT INTO tbl_beli(beli_nofak,beli_tanggal,beli_suplier_id,beli_user_id,beli_kode) VALUES(:beli_nofak,:beli_tanggal,:beli_suplier_id,:beli_user_id,:beli_kode)";
        $add = $dbh->prepare($queryadd);
        $add->bindParam(':beli_nofak',$nofak);
        $add->bindParam(':beli_tanggal',$tgl);
        $add->bindParam(':beli_suplier_id',$suplier_id);
        $add->bindParam(':beli_user_id',$user_id);
        $add->bindParam(':beli_kode',$beli_kode);
        $add->execute();

        $sql1 = 'SELECT * FROM tbl_beli_tmp JOIN tbl_barang WHERE tbl_beli_tmp.barang_id=tbl_barang.barang_id AND tbl_beli_tmp.user_id=:id';
        $query = $dbh -> prepare($sql1);
        $query->bindParam(':id',$user_id);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        foreach($results as $res){
            $total1=$res->harpok*$res->jumlah;
            $totalbeli = $res->harpok*$res->jumlah;
            $update = $dbh->prepare("UPDATE tbl_barang SET barang_stok=barang_stok+:barang_stok,barang_harpok=:harpok WHERE barang_id=:barang_id");
            $update -> bindParam(':barang_id',$res->barang_id, PDO::PARAM_STR);
            $update -> bindParam(':barang_stok',$res->jumlah, PDO::PARAM_STR);
            $update -> bindParam(':harpok',$res->harpok, PDO::PARAM_STR);
            $update -> execute();
            $queryd = "INSERT INTO tbl_detail_beli(d_beli_nofak,d_beli_barang_id,d_beli_harga,d_beli_jumlah,d_beli_total,d_beli_kode) VALUES(:d_beli_nofak,:d_beli_barang_id,:d_beli_harga,:d_beli_jumlah,:d_beli_total,:d_beli_kode)";
            $add1 = $dbh->prepare($queryd);
            $add1->bindParam(':d_beli_nofak',$nofak);
            $add1->bindParam(':d_beli_barang_id',$res->barang_id);
            $add1->bindParam(':d_beli_harga',$res->harpok);
            $add1->bindParam(':d_beli_jumlah',$res->jumlah);
            $add1->bindParam(':d_beli_total',$total1);
            $add1->bindParam(':d_beli_kode',$beli_kode);
            $add1->execute();
        }

        $sql2 = "delete from tbl_beli_tmp  WHERE user_id=:id";
        $query2 = $dbh->prepare($sql2);
        $query2->bindParam(':id',$user_id);
        $query2 -> execute();
        $msg = "Transaksi Sukses Disimpan.";

    }
?>
<?php include('./include/head.php');?>
<?php include('./include/navbar.php');?>
<?php include('./include/sidebar.php');?>

<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Pembelian</h1>
      <div class="section-header-breadcrumb">
          </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col">
                    <div class="card">
                            <div class="card-body">
                                <?php 
                                    if($error){
                                ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert"><?php echo htmlentities($error); ?> 
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div><br>
                                <?php } 
                                    else if($msg){
                                ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert"><?php echo htmlentities($msg); ?> 
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div><br>
                                <?php }?>
                                <button class="btn btn-primary btn-icon icon-left" data-toggle="modal" data-target="#AddProduk"><i class="fas fa-plus"></i>Produk</button>
                                <table id="example3" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Kode Barang</th>
                                <th>Nama</th>
                                <th>Satuan</th>
                                <th>H Pokok</th>
                                <th>H Jual</th>
                                <th>Jumlah Beli</th>
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
                                    $id = $_SESSION['id'];
                                    $sql = 'SELECT * FROM tbl_beli_tmp JOIN tbl_barang WHERE tbl_beli_tmp.barang_id=tbl_barang.barang_id AND tbl_beli_tmp.user_id=:id';
                                    $query = $dbh -> prepare($sql);
                                    $query->bindParam(':id',$id);
                                    $query->execute();
                                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                                    $nmr=1;
                                    $totalbelanja = 0;
                                    // $ket = array();
                                    if($query->rowCount() > 0){
                                        foreach($results as $res){
                                        $total = $res->jumlah*$res->harpok;
                                        $totalbelanja += $total;
                                        // $ket[] = "$res->nama_produk ($res->qty)";                    
                                ?>
                            <tr>
                                
                                <td><?php echo htmlentities($res->barang_id);?></td>
                                <td><?php echo htmlentities($res->barang_nama);?></td>
                                <td><?php echo htmlentities($res->barang_satuan);?></td>
                                <td><?php echo htmlentities(rupiah($res->harpok));?></td>
                                <td><?php echo htmlentities(rupiah($res->barang_harjul));?></td>
                                <td><?php echo htmlentities($res->jumlah);?></td>
                                <td><?php echo htmlentities(rupiah($total));?></td>
                                <td>
                                    <!-- <a type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#MyModal<?php echo $res->id_pasien;?>"><i class="fas fa-edit"></i></a> -->
                                    <a href="pembelian.php?del=<?php echo $res->barang_id;?>" onclick="return confirm('Do you want to delete');" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php $nmr=$nmr+1; } } ?>
                            </tbody>
                            </table>
                            <form action="" method="post">
                                <table>
                                    <tr>
                                        <td style="width:760px;" rowspan="2"></td>
                                        <th style="width:100px;">No Faktur</th>
                                        <th style="text-align:right;width:180px;"><input type="text" id="nofak" name="nofak" class="form-control input-sm" style="text-align:right;margin-bottom:5px;" required></th>
                                        
                                    </tr>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th style="text-align:right;"><input type="date" name="tgl" class="form-control" required></th>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <th>Suplier</th>
                                        <th style="text-align:right;">
                                            <div class="form-group">
                                                <select name="suplier_id" class="selectpicker form-control" data-live-search="true" required>
                                                <option value="">-- Pilih Suplier --</option>
                                                <?php 
                                                    $sql = "select * from tbl_suplier";
                                                    $query = $dbh -> prepare($sql);
                                                    $query->execute();
                                                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                                                    if($query->rowCount() > 0){
                                                        foreach($results as $res){
                                                ?>
                                                <option value="<?= $res->suplier_id?>"><?= $res->suplier_name?></option>
                                                <?php }}?>
                                                </select>
                                            </div>
                                        </th>
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
        <div class="modal fade" id="AddProduk">
          <div class="modal-dialog" >
              <div class="modal-content">
                  <div class="modal-header">
                  <h4 class="modal-title">Pembelian</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
                  </div>
                  <div class="modal-body">
                      <form method="post">
                          <div class="form-group">
                              <label for="">Produk</label>
                              <select name="barang_id" class="selectpicker form-control" data-live-search="true" required>
                              <?php 
                                  $sql = "select * from tbl_barang";
                                  $query = $dbh -> prepare($sql);
                                  $query->execute();
                                  $results=$query->fetchAll(PDO::FETCH_OBJ);
                                  if($query->rowCount() > 0){
                                      foreach($results as $res){
                              ?>
                              <option value="<?= $res->barang_id?>"><?= $res->barang_nama?></option>
                              <?php }}?>
                              </select>
                          </div>
                          <div class="form-group">
                              <label for="">Harga Pokok</label>
                              <input type="text" name="harpok" class="form-control" required>
                          </div>
                          <div class="form-group">
                              <label for="">Jumlah</label>
                              <!-- <input type="hidden" name="id" class="form-control" value="<?php echo htmlentities($res->id)?>"> -->
                              <input type="number" name="jumlah" class="form-control" required>
                              <button Type="submit" name="pesan" class="btn btn-primary mt-4">Simpan</button>
                              <!-- <button Type="submit" name="updatestok" class="btn btn-primary mt-4">Update</button> -->
                          </div>
                      </form>
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
<script src="assets/js/page/modules-toastr.js"></script>
</body>
</html>


<?php } ?>