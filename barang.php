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
        $sql = "delete from tbl_barang  WHERE barang_id=:id";
        $query = $dbh->prepare($sql);
        $query -> bindParam(':id',$id, PDO::PARAM_STR);
        $query -> execute();
        $msg = "Data Berhasil di hapus!";
      }
    if(isset($_POST['tambah'])){
        $sql = $dbh->prepare("SELECT max(barang_id) as id FROM tbl_barang");
        $sql->execute();
        $hasil = $sql->fetch();    
        $kode = $hasil['id'];
        $noUrut = (int) substr($kode, 3);
        $noUrut++;
        $char = "BR";
        $newID = $char . sprintf("%04s", $noUrut);
        $barang_id = $newID;
        $barang_nama = $_POST['barang_nama'];
        $barang_satuan = $_POST['barang_satuan'];
        $barang_harpok = $_POST['barang_harpok'];
        $barang_harjul = $_POST['barang_harjul'];
        $barang_harjul_grosir = $_POST['barang_harjul_grosir'];
        $barang_stok = $_POST['barang_stok'];
        $barang_min_stok = $_POST['barang_min_stok'];
        $barang_kategori_id = $_POST['barang_kategori_id'];
        $barang_user_id = $_SESSION['id'];
        $qtambah = "INSERT INTO tbl_barang(barang_id,barang_nama,barang_satuan,barang_harpok,barang_harjul,barang_harjul_grosir,barang_stok,barang_min_stok,barang_kategori_id,barang_user_id) VALUES(:barang_id,:barang_nama,:barang_satuan,:barang_harpok,:barang_harjul,:barang_harjul_grosir,:barang_stok,:barang_min_stok,:barang_kategori_id,:barang_user_id)";
        $tambah = $dbh->prepare($qtambah);
        $tambah -> bindParam(':barang_id',$barang_id, PDO::PARAM_STR);
        $tambah -> bindParam(':barang_nama',$barang_nama, PDO::PARAM_STR);
        $tambah -> bindParam(':barang_satuan',$barang_satuan, PDO::PARAM_STR);
        $tambah -> bindParam(':barang_harpok',$barang_harpok, PDO::PARAM_STR);
        $tambah -> bindParam(':barang_harjul',$barang_harjul, PDO::PARAM_STR);
        $tambah -> bindParam(':barang_harjul_grosir',$barang_harjul_grosir, PDO::PARAM_STR);
        $tambah -> bindParam(':barang_stok',$barang_stok, PDO::PARAM_STR);
        $tambah -> bindParam(':barang_min_stok',$barang_min_stok, PDO::PARAM_STR);
        $tambah -> bindParam(':barang_kategori_id',$barang_kategori_id, PDO::PARAM_STR);
        $tambah -> bindParam(':barang_user_id',$barang_user_id, PDO::PARAM_STR);
        $tambah -> execute();
        $msg = "Data Berhasil di tambah!";
    }
    if(isset($_POST['update'])){
        $barang_id = $_POST['barang_id'];
        $barang_nama = $_POST['barang_nama'];
        $barang_satuan = $_POST['barang_satuan'];
        $barang_harpok = $_POST['barang_harpok'];
        $barang_harjul = $_POST['barang_harjul'];
        $barang_harjul_grosir = $_POST['barang_harjul_grosir'];
        $barang_stok = $_POST['barang_stok'];
        $barang_min_stok = $_POST['barang_min_stok'];
        $barang_kategori_id = $_POST['barang_kategori_id'];
        $qtambah = "UPDATE tbl_barang SET barang_nama=:barang_nama,barang_satuan=:barang_satuan,barang_harpok=:barang_harpok,barang_harjul=:barang_harjul,barang_harjul_grosir=:barang_harjul_grosir,barang_stok=:barang_stok,barang_min_stok=:barang_min_stok,barang_kategori_id=:barang_kategori_id WHERE barang_id=:barang_id";
        $tambah = $dbh->prepare($qtambah);
        $tambah -> bindParam(':barang_id',$barang_id, PDO::PARAM_STR);
        $tambah -> bindParam(':barang_nama',$barang_nama, PDO::PARAM_STR);
        $tambah -> bindParam(':barang_satuan',$barang_satuan, PDO::PARAM_STR);
        $tambah -> bindParam(':barang_harpok',$barang_harpok, PDO::PARAM_STR);
        $tambah -> bindParam(':barang_harjul',$barang_harjul, PDO::PARAM_STR);
        $tambah -> bindParam(':barang_harjul_grosir',$barang_harjul_grosir, PDO::PARAM_STR);
        $tambah -> bindParam(':barang_stok',$barang_stok, PDO::PARAM_STR);
        $tambah -> bindParam(':barang_min_stok',$barang_min_stok, PDO::PARAM_STR);
        $tambah -> bindParam(':barang_kategori_id',$barang_kategori_id, PDO::PARAM_STR);
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
      <h1>Barang</h1>
      <div class="section-header-breadcrumb">
        <!-- <button class="btn btn-primary triger-sukses" onclick="sukses()">Launch</button> -->
          <button class="btn btn-primary btn-icon icon-left" data-toggle="modal" data-target="#AddStok"><i class="fas fa-plus"></i> Tambah Barang</button>
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
                            <div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="far fa-check-circle"></i> <?php echo htmlentities($error); ?> 
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <?php } 
                                else if($msg){
                            ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert"><i class="far fa-check-circle"></i> <?php echo htmlentities($msg); ?> 
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <?php }?>
                        <style>
                            table.table-bordered.dataTable tbody th,table.table-bordered.dataTable tbody td{border-bottom-width:1px}
                        </style>
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Satuan</th>
                                    <th>Harga Pokok</th>
                                    <th>Harga (Eceran)</th>
                                    <th>Harga (Grosir)</th>
                                    <th>Stok</th>
                                    <th>Min Stok</th>
                                    <th>Kategori</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                        function rupiah($angka){
                    
                                            $hasil_rupiah = "Rp " . number_format($angka,2,',','.');
                                            return $hasil_rupiah;
                                        
                                        }
                                        $sql = "SELECT * from tbl_barang JOIN tbl_kategori ON tbl_barang.barang_kategori_id=tbl_kategori.kategori_id";
                                        $query = $dbh -> prepare($sql);
                                        $query->execute();
                                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                                        $nmr=1;
                                        // $ket = array();
                                        if($query->rowCount() > 0){
                                            foreach($results as $res){                  
                                    ?>
                                <tr>
                                    
                                    <td><?php echo htmlentities($nmr);?></td>
                                    <td><?php echo htmlentities($res->barang_id);?></td>
                                    <td><?php echo htmlentities($res->barang_nama);?></td>
                                    <td><?php echo htmlentities($res->barang_satuan);?></td>
                                    <td><?php echo htmlentities(rupiah($res->barang_harpok));?></td>
                                    <td><?php echo htmlentities(rupiah($res->barang_harjul));?></td>
                                    <td><?php echo htmlentities(rupiah($res->barang_harjul_grosir));?></td>
                                    <td><?php echo htmlentities($res->barang_stok);?></td>
                                    <td><?php echo htmlentities($res->barang_min_stok);?></td>
                                    <td><?php echo htmlentities($res->kategori_name);?></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#MyModal<?php echo $res->barang_id;?>"><i class="fas fa-edit"></i></button>
                                        <a href="barang.php?del=<?php echo $res->barang_id;?>" onclick="return confirm('Do you want to delete');" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <div class="modal fade" id="MyModal<?php echo $res->barang_id;?>">
                                    <div class="modal-dialog" >
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Update Barang</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="">
                                                        <div class="form-group">
                                                            <label for="">Nama Barang</label>
                                                            <input type="hidden" name="barang_id" value="<?php echo $res->barang_id;?>" class="form-control" required>
                                                            <input type="text" name="barang_nama" value="<?php echo $res->barang_nama;?>" class="form-control" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="">Kategori</label>
                                                            <select name="barang_kategori_id" class="form-control" required>
                                                                <option value="<?= $res->kategori_id?>">--<?= $res->kategori_name?>--</option>
                                                                <?php 
                                                                    $sql1 = "select * from tbl_kategori";
                                                                    $query1 = $dbh -> prepare($sql1);
                                                                    $query1->execute();
                                                                    $results1=$query1->fetchAll(PDO::FETCH_OBJ);
                                                                    if($query1->rowCount() > 0){
                                                                        foreach($results1 as $res1){
                                                                ?>
                                                                <option value="<?= $res1->kategori_id?>"><?= $res1->kategori_name?></option>
                                                                <?php }}?>
                                                            </select>  
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="">Satuan</label>
                                                            <input type="text" name="barang_satuan" class="form-control" value="<?php echo $res->barang_satuan;?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="">Harga Pokok</label>
                                                            <input type="text" name="barang_harpok" class="form-control" value="<?php echo $res->barang_harpok;?>" required>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="">Harga (Eceran)</label>
                                                                    <input type="number" name="barang_harjul" class="form-control" value="<?php echo $res->barang_harjul;?>" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="">Harga (Grosir)</label>
                                                                    <input type="number" name="barang_harjul_grosir" class="form-control" value="<?php echo $res->barang_harjul_grosir;?>" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="">Stok</label>
                                                                    <input type="number" name="barang_stok" class="form-control" value="<?php echo $res->barang_stok;?>" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="">Minimal Stok</label>
                                                                    <input type="number" name="barang_min_stok" class="form-control" value="<?php echo $res->barang_min_stok;?>" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button Type="submit" name="update" class="btn btn-primary mt-4">Simpan</button>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                </div>
                                    <!-- /.modal -->
                                <?php $nmr=$nmr+1; } } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </section>
     <div class="modal fade" id="AddStok">
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Barang</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                            <div class="form-group">
                                <label for="">Nama Barang</label>
                                <input type="text" name="barang_nama" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="">Kategori</label>
                                <select name="barang_kategori_id" class="form-control" required>
                                    <?php 
                                        $sql1 = "select * from tbl_kategori";
                                        $query1 = $dbh -> prepare($sql1);
                                        $query1->execute();
                                        $results1=$query1->fetchAll(PDO::FETCH_OBJ);
                                        if($query1->rowCount() > 0){
                                            foreach($results1 as $res1){
                                    ?>
                                    <option value="<?= $res1->kategori_id?>"><?= $res1->kategori_name?></option>
                                    <?php }}?>
                                </select>  
                            </div>
                            <div class="form-group">
                                <label for="">Satuan</label>
                                <input type="text" name="barang_satuan" class="form-control" placeholder="PCS,Buah,Botol dll" required>
                            </div>
                            <div class="form-group">
                                <label for="">Harga Pokok</label>
                                <input type="text" name="barang_harpok" class="form-control" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Harga (Eceran)</label>
                                        <input type="number" name="barang_harjul" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Harga (Grosir)</label>
                                        <input type="number" name="barang_harjul_grosir" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Stok</label>
                                        <input type="number" name="barang_stok" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Minimal Stok</label>
                                        <input type="number" name="barang_min_stok" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <button Type="submit" name="tambah" class="btn btn-primary mt-4">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('./include/footer.php');?>
<?php include('./include/script.php');?>
  <!-- Page Specific JS File -->
<script src="assets/js/page/modules-toastr.js"></script>
</body>
</html>


<?php } ?>