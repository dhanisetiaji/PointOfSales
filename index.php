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
      <h1>Blank Page</h1>
    </div>

    <div class="section-body">
    </div>
  </section>
</div>
<?php include('./include/footer.php');?>
<?php include('./include/script.php');?>
</body>
</html>


<?php } ?>