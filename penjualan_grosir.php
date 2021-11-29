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
if($_SESSION['user_level']==2){
    ?> <script language="JavaScript">alert('Anda tidak memiliki akses');</script>
    <a href="index.php">Go Back</a>
        <?php
        // header('Location:./dashboard.php');
    }
else{
    if(isset($_GET['del'])){
        $id=$_GET['del'];
        $sql = "delete from tbl_jual_tmp  WHERE barang_id=:id";
        $query = $dbh->prepare($sql);
        $query -> bindParam(':id',$id, PDO::PARAM_STR);
        $query -> execute();
        $msg = "Data Berhasil di hapus!";
      }
    if(isset($_POST['pesan'])){
        $barang_id = $_POST['barang_id'];
        $diskon = $_POST['diskon'];
        $jumlah = $_POST['jumlah'];
        $sql ="SELECT * FROM tbl_jual_tmp WHERE barang_id=:barang_id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':barang_id',$barang_id,PDO::PARAM_STR);
        $query->execute();
        $getbarangjumlah = $query->fetch();
        $jumlah1 = $getbarangjumlah['jumlah'];
        $jumlahtotal = $jumlah1+$jumlah;
        $sql1 = "SELECT barang_stok FROM tbl_barang WHERE barang_id=:barang_id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':barang_id',$barang_id,PDO::PARAM_STR);
        $query1->execute();
        $getbarangstok = $query1->fetch();
        $stok = $getbarangstok['barang_stok'];
        // var_dump($stok);
        // var_dump($jumlahtotal);
        // die();
        if($stok >= $jumlahtotal){
            if($query->rowCount()==0){
                $queryadd = "INSERT INTO tbl_jual_tmp(barang_id,diskon,jumlah,user_id) VALUES(:barang_id,:diskon,:jumlah,:user_id)";
                $add = $dbh->prepare($queryadd);
                $add->bindParam(':barang_id',$barang_id);
                $add->bindParam(':diskon',$diskon);
                $add->bindParam(':jumlah',$jumlah);
                $add->bindParam(':user_id',$_SESSION['id']);
                $add->execute();
                $msg = "Produk Berhasil ditambah!";
            }else{
                $update=$dbh->prepare("UPDATE tbl_jual_tmp SET jumlah=jumlah+:jumlah,diskon=:diskon where barang_id=:barang_id");
                $update ->bindParam(':barang_id',$barang_id, PDO::PARAM_STR);
                $update->bindParam(':diskon',$diskon);
                $update ->bindParam(':jumlah',$jumlah, PDO::PARAM_STR);
                $update -> execute(); 
                $msg = "Produk Berhasil ditambah!";
            }  
        }else{
            $error = "Jumlah Melebihi Stok!";
        }
    }
    if(isset($_POST['simpan'])){
        $sql = $dbh->prepare("SELECT max(d_jual_id) as id FROM tbl_detail_jual");
        $sql->execute();
        $hasil = $sql->fetch();    
        $kode = $hasil['id'];
        $noUrut = (int) substr($kode, 3);
        $noUrut++;
        $char = "JL";
        $newID = $char . sprintf("%04s", $noUrut);
        $nofak = $newID;
        $user_id = $_SESSION['id'];
        $total_bayar = $_POST['total2'];
        $jml_uang = $_POST['jml_uang'];
        $kembalian = $_POST['kembalian'];
        $keterangan = "Grosir";
        
        $queryadd = "INSERT INTO tbl_jual(jual_nofak,jual_total,jual_jml_uang,jual_kembalian,jual_user_id,jual_keterangan) VALUES(:jual_nofak,:jual_total,:jual_jml_uang,:jual_kembalian,:jual_user_id,:jual_keterangan)";
        $add = $dbh->prepare($queryadd);
        $add->bindParam(':jual_nofak',$nofak);
        $add->bindParam(':jual_total',$total_bayar);
        $add->bindParam(':jual_jml_uang',$jml_uang);
        $add->bindParam(':jual_kembalian',$kembalian);
        $add->bindParam(':jual_user_id',$user_id);
        $add->bindParam(':jual_keterangan',$keterangan);
        $add->execute();

        $sql1 = 'SELECT * FROM tbl_jual_tmp JOIN tbl_barang WHERE tbl_jual_tmp.barang_id=tbl_barang.barang_id AND tbl_jual_tmp.user_id=:id';
        $query = $dbh -> prepare($sql1);
        $query->bindParam(':id',$user_id);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        foreach($results as $res){
            $total1=$res->barang_harjul*$res->jumlah-$res->diskon;
            $update = $dbh->prepare("UPDATE tbl_barang SET barang_stok=barang_stok-:barang_stok WHERE barang_id=:barang_id");
            $update -> bindParam(':barang_id',$res->barang_id, PDO::PARAM_STR);
            $update -> bindParam(':barang_stok',$res->jumlah, PDO::PARAM_STR);
            $update -> execute();
            $queryd = "INSERT INTO tbl_detail_jual(d_jual_nofak,d_jual_barang_id,d_jual_barang_nama,d_jual_barang_satuan,d_jual_barang_harpok,d_jual_barang_harjul,d_jual_qty,d_jual_diskon,d_jual_total) VALUES(:d_jual_nofak,:d_jual_barang_id,:d_jual_barang_nama,:d_jual_barang_satuan,:d_jual_barang_harpok,:d_jual_barang_harjul,:d_jual_qty,:d_jual_diskon,:d_jual_total)";
            $add1 = $dbh->prepare($queryd);
            $add1->bindParam(':d_jual_nofak',$nofak);
            $add1->bindParam(':d_jual_barang_id',$res->barang_id, PDO::PARAM_STR);
            $add1->bindParam(':d_jual_barang_nama',$res->barang_nama, PDO::PARAM_STR);
            $add1->bindParam(':d_jual_barang_satuan',$res->barang_satuan, PDO::PARAM_STR);
            $add1->bindParam(':d_jual_barang_harpok',$res->barang_harpok, PDO::PARAM_STR);
            $add1->bindParam(':d_jual_barang_harjul',$res->barang_harjul, PDO::PARAM_STR);
            $add1->bindParam(':d_jual_qty',$res->jumlah, PDO::PARAM_STR);
            $add1->bindParam(':d_jual_diskon',$res->diskon, PDO::PARAM_STR);
            $add1->bindParam(':d_jual_total',$total1);
            $add1->execute();
        }

        $sql2 = "delete from tbl_jual_tmp  WHERE user_id=:id";
        $query2 = $dbh->prepare($sql2);
        $query2->bindParam(':id',$user_id);
        $query2 -> execute();
        $msg = "Transaksi Sukses Disimpan!";
    }
?>
<?php include('./include/head.php');?>
<?php include('./include/navbar.php');?>
<?php include('./include/sidebar.php');?>

<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Penjualan Grosir</h1>
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
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Satuan</th>
                                <th>Qty</th>
                                <th>Diskon</th>
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
                                    $id = $_SESSION['id'];
                                    $sql = 'SELECT * FROM tbl_jual_tmp JOIN tbl_barang WHERE tbl_jual_tmp.barang_id=tbl_barang.barang_id AND tbl_jual_tmp.user_id=:id';
                                    $query = $dbh -> prepare($sql);
                                    $query->bindParam(':id',$id);
                                    $query->execute();
                                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                                    $nmr=1;
                                    $totalbelanja = 0;
                                    // $ket = array();
                                    if($query->rowCount() > 0){
                                        foreach($results as $res){
                                        $total = $res->jumlah*$res->barang_harjul-$res->diskon;
                                        $totalbelanja += $total;
                                        // $ket[] = "$res->nama_produk ($res->qty)";                    
                                ?>
                            <tr>
                                
                                <td><?php echo htmlentities($nmr);?></td>
                                <td><?php echo htmlentities($res->barang_nama);?></td>
                                <td><?php echo htmlentities($res->barang_satuan);?></td>
                                <td><?php echo htmlentities($res->jumlah);?></td>
                                <td><?php echo htmlentities(rupiah($res->diskon));?></td>
                                <td><?php echo htmlentities(rupiah($res->barang_harjul));?></td>
                                <td><?php echo htmlentities(rupiah($total));?></td>
                                <td>
                                    <!-- <a type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#MyModal<?php echo $res->id_pasien;?>"><i class="fas fa-edit"></i></a> -->
                                    <a href="penjualan_eceran.php?del=<?php echo $res->barang_id;?>" onclick="return confirm('Do you want to delete');" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
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
        <div class="modal fade" id="AddProduk">
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
                              <label for="">Diskon</label>
                              <input type="text" name="diskon" class="form-control">
                          </div>
                          <div class="form-group">
                              <label for="">Jumlah</label>
                              <!-- <input type="hidden" name="id" class="form-control" value="<?php echo htmlentities($res->id)?>"> -->
                              <input type="number" name="jumlah" class="form-control" required>
                              <button Type="submit" name="pesan" class="btn btn-primary mt-4">Pesan</button>
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
  <!-- Page Specific JS File -->
  <script type="text/javascript">
        $('document').ready(function(){
            $(".chosen-select").chosen();
        })
        $(function(){
            $('#jml_uang').on("input",function(){
                var total=$('#total').val();
                var jumuang=$('#jml_uang').val();
                var hsl=jumuang.replace(/[^\d]/g,"");
                $('#jml_uang2').val(hsl);
                $('#kembalian').val(hsl-total);
            })
            
        });
    </script>
<script src="assets/js/page/modules-toastr.js"></script>
</body>
</html>


<?php } ?>