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
      <h1>Kategori</h1>
      <div class="section-header-breadcrumb">
        <!-- <button class="btn btn-primary triger-sukses" onclick="sukses()">Launch</button> -->
          <button class="btn btn-primary btn-icon icon-left" data-toggle="modal" data-target="#AddStok"><i class="fas fa-plus"></i> Tambah Kategori</button>
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
                                    <th>Nama Kategori</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                        // function rupiah($angka){
                    
                                        //     $hasil_rupiah = "Rp " . number_format($angka,2,',','.');
                                        //     return $hasil_rupiah;
                                        
                                        // }
                                        $sql = "select * from tbl_kategori";
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
                                    <td><?php echo htmlentities($res->kategori_name);?></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#MyModal<?php echo $res->kategori_id;?>"><i class="fas fa-edit"></i></button>
                                        <a href="kategori.php?del=<?php echo $res->kategori_id;?>" onclick="return confirm('Do you want to delete');" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <div class="modal fade" id="MyModal<?php echo $res->kategori_id;?>">
                                    <div class="modal-dialog" >
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Update Kategori</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="">
                                                        <div class="form-group">
                                                            <label for="">Nama Kategori</label>
                                                            <input type="hidden" name="kategori_id" value="<?php echo $res->kategori_id;?>" class="form-control" required>
                                                            <input type="text" name="kategori_name" value="<?php echo $res->kategori_name;?>" class="form-control" required>
                                                            <button Type="submit" name="update" class="btn btn-primary mt-4">Simpan</button>
                                                        </div>
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
                    <h4 class="modal-title">Tambah Kategori</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                            <div class="form-group">
                                <label for="">Nama Kategori</label>
                                <input type="text" name="kategori_name" class="form-control" required>
                                <button Type="submit" name="tambah" class="btn btn-primary mt-4">Simpan</button>
                            </div>
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