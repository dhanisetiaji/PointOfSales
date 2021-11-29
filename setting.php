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
    if(isset($_POST['tambah'])){
        $target_dir = "image/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        // cek image/file gambar
        if(isset($_POST["tambah"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            $msg = "File adalah image - " . $check["mime"] . ".";
        $uploadOk = 1;
        } else {
            $msg = "File bukan image.";
        $uploadOk = 0;
        }
        }

        // Cek error
        if ($uploadOk == 0) {
            $msg = "file gagal di-upload";
            // jika ok, try to upload
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $id_login = $_POST['id'];
                $image_name = basename( $_FILES["fileToUpload"]["name"]);
                $qtambah = "UPDATE login SET gambar=:gambar WHERE id_login=:id_login";
                $tambah = $dbh->prepare($qtambah);
                $tambah -> bindParam(':id_login',$id_login, PDO::PARAM_STR);
                $tambah -> bindParam(':gambar',$image_name, PDO::PARAM_STR);
                $tambah -> execute();
                $msg = "File ". basename( $_FILES["fileToUpload"]["name"]). " sukses di-upload";

        } else {
            $msg = "Maaf, ada error...";
        }
        }
    }
?>
<?php include('./include/head.php');?>
<?php include('./include/navbar.php');?>
<?php include('./include/sidebar.php');?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Setting</h1>
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
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="section-title">Profile Picture</div>
                        <input type="hidden" name="id" value="<?= $_SESSION['id'] ?>">
                            <div class="custom-file">
                            <input type="file" name="fileToUpload" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile">Choose Image</label>
                        </div>
                        <button Type="submit" name="tambah" class="btn btn-primary mt-4">Simpan</button>
                    </form>
                    </div>
                </div>
            </div>
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