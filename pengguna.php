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
        $sql = "delete from login  WHERE id_login=:id";
        $query = $dbh->prepare($sql);
        $query -> bindParam(':id',$id, PDO::PARAM_STR);
        $query -> execute();
        $msg = "Data Berhasil di hapus!";
      }
    if(isset($_POST['tambah'])){
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $user_level = $_POST['user_level'];
        $qtambah = "INSERT INTO login(username,password,user_level) VALUES(:username,:password,:user_level)";
        $tambah = $dbh->prepare($qtambah);
        $tambah -> bindParam(':username',$username, PDO::PARAM_STR);
        $tambah -> bindParam(':password',$password, PDO::PARAM_STR);
        $tambah -> bindParam(':user_level',$user_level, PDO::PARAM_STR);
        $tambah -> execute();
        $msg = "Data Berhasil di tambah!";

    }
    if(isset($_POST['update'])){
        $id_login = $_POST['id_login'];
        $password = md5($_POST['password']);
        $qtambah = "UPDATE login SET password=:password WHERE id_login=:id_login";
        $tambah = $dbh->prepare($qtambah);
        $tambah -> bindParam(':id_login',$id_login, PDO::PARAM_STR);
        $tambah -> bindParam(':password',$password, PDO::PARAM_STR);
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
      <h1>Pengguna</h1>
      <div class="section-header-breadcrumb">
        <!-- <button class="btn btn-primary triger-sukses" onclick="sukses()">Launch</button> -->
          <button class="btn btn-primary btn-icon icon-left" data-toggle="modal" data-target="#AddStok"><i class="fas fa-plus"></i> Tambah Pengguna</button>
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
                                    <th>Username</th>
                                    <th>Level</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                        // function rupiah($angka){
                    
                                        //     $hasil_rupiah = "Rp " . number_format($angka,2,',','.');
                                        //     return $hasil_rupiah;
                                        
                                        // }
                                        $sql = "select * from login";
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
                                    <td><?php echo htmlentities($res->username);?></td>
                                    <td><?php echo htmlentities($res->user_level);?></td>
                                    <td><?php echo htmlentities($res->created_at);?></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#MyModal<?php echo $res->id_login;?>"><i class="fas fa-edit"></i></button>
                                        <a href="pengguna.php?del=<?php echo $res->id_login;?>" onclick="return confirm('Do you want to delete');" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <div class="modal fade" id="MyModal<?php echo $res->id_login;?>">
                                    <div class="modal-dialog" >
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Update Password</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="">
                                                        <div class="form-group">
                                                            <label for="">Masukan Password Baru</label>
                                                            <input type="hidden" name="id_login" value="<?php echo $res->id_login;?>" class="form-control" required>
                                                            <input type="password" name="password" class="form-control" required>
                                                            <button Type="submit" name="update" class="btn btn-primary mt-4">Update</button>
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
                    <h4 class="modal-title">Tambah Pengguna</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="">User Level</label>
                            <select name="user_level" class="form-control" required>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
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