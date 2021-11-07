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
        $sql = "delete from tbl_suplier  WHERE suplier_id=:id";
        $query = $dbh->prepare($sql);
        $query -> bindParam(':id',$id, PDO::PARAM_STR);
        $query -> execute();
        $msg = "Data Berhasil di hapus!";
      }
    if(isset($_POST['tambah'])){
        $nama_suplier = $_POST['suplier_name'];
        $suplier_alamat = $_POST['suplier_alamat'];
        $suplier_notlp = $_POST['suplier_notlp'];
        $qtambah = "INSERT INTO tbl_suplier(suplier_name,suplier_alamat,suplier_notlp) VALUES(:suplier_name,:suplier_alamat,:suplier_notlp)";
        $tambah = $dbh->prepare($qtambah);
        $tambah -> bindParam(':suplier_name',$nama_suplier, PDO::PARAM_STR);
        $tambah -> bindParam(':suplier_alamat',$suplier_alamat, PDO::PARAM_STR);
        $tambah -> bindParam(':suplier_notlp',$suplier_notlp, PDO::PARAM_STR);
        $tambah -> execute();
        $msg = "Data Berhasil di tambah!";

    }
    if(isset($_POST['update'])){
        $suplier_id = $_POST['suplier_id'];
        $nama_suplier = $_POST['suplier_name'];
        $suplier_alamat = $_POST['suplier_alamat'];
        $suplier_notlp = $_POST['suplier_notlp'];
        $qtambah = "UPDATE tbl_suplier SET suplier_name=:suplier_name,suplier_alamat=:suplier_alamat,suplier_notlp=:suplier_notlp WHERE suplier_id=:suplier_id";
        $tambah = $dbh->prepare($qtambah);
        $tambah -> bindParam(':suplier_id',$suplier_id, PDO::PARAM_STR);
        $tambah -> bindParam(':suplier_name',$nama_suplier, PDO::PARAM_STR);
        $tambah -> bindParam(':suplier_alamat',$suplier_alamat, PDO::PARAM_STR);
        $tambah -> bindParam(':suplier_notlp',$suplier_notlp, PDO::PARAM_STR);
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
      <h1>Suplier</h1>
      <div class="section-header-breadcrumb">
        <!-- <button class="btn btn-primary triger-sukses" onclick="sukses()">Launch</button> -->
          <button class="btn btn-primary btn-icon icon-left" data-toggle="modal" data-target="#AddStok"><i class="fas fa-plus"></i> Tambah Suplier</button>
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
                                    <th>Nama Suplier</th>
                                    <th>No Telp</th>
                                    <th>Alamat</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                        // function rupiah($angka){
                    
                                        //     $hasil_rupiah = "Rp " . number_format($angka,2,',','.');
                                        //     return $hasil_rupiah;
                                        
                                        // }
                                        $sql = "select * from tbl_suplier";
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
                                    <td><?php echo htmlentities($res->suplier_name);?></td>
                                    <td><?php echo htmlentities($res->suplier_notlp);?></td>
                                    <td><?php echo htmlentities($res->suplier_alamat);?></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#MyModal<?php echo $res->suplier_id;?>"><i class="fas fa-edit"></i></button>
                                        <a href="suplier.php?del=<?php echo $res->suplier_id;?>" onclick="return confirm('Do you want to delete');" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <div class="modal fade" id="MyModal<?php echo $res->suplier_id;?>">
                                    <div class="modal-dialog" >
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Update Suplier</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="">
                                                        <div class="form-group">
                                                            <label for="">Nama Suplier</label>
                                                            <input type="hidden" name="suplier_id" value="<?php echo $res->suplier_id;?>" class="form-control" required>
                                                            <input type="text" name="suplier_name" value="<?php echo $res->suplier_name;?>" class="form-control" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="">No Telepon</label>
                                                            <input type="text" name="suplier_notlp" value="<?php echo $res->suplier_notlp;?>" class="form-control" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="">Alamat Suplier</label>
                                                            <input type="text" name="suplier_alamat" value="<?php echo $res->suplier_alamat;?>" class="form-control" required>
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
                    <h4 class="modal-title">Tambah Suplier</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                            <div class="form-group">
                                <label for="">Nama Suplier</label>
                                <input type="text" name="suplier_name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="">No Telepon</label>
                                <input type="number" name="suplier_notlp" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="">Alamat Suplier</label>
                                <input type="text" name="suplier_alamat" class="form-control" required>
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
  <script type="text/javascript">
      function sukses(){
          iziToast.success({
            title: 'Success,',
            icon: 'far fa-check-circle',
            transitionIn: 'fadeInUp',
            transitionOut: 'fadeOut',
            position: 'topRight',
            message: 'Berhasil Menambahkan Data!',
        });

      }
  </script>
<script src="assets/js/page/modules-toastr.js"></script>
</body>
</html>


<?php } ?>