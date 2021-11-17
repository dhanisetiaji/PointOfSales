<body>
  <div id="app">
    <div class="main-wrapper">
      <div class="navbar-bg"></div>
        <nav class="navbar navbar-expand-lg main-navbar">
                <form class="form-inline mr-auto">
                <ul class="navbar-nav mr-3">
                    <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
                    <!-- <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li> -->
                </ul>
                </form>
                <ul class="navbar-nav navbar-right">          
                <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                    <?php 
                        $id_login = $_SESSION['id'];
                        $sql = "select gambar from login WHERE id_login=:id_login";
                        $query = $dbh -> prepare($sql);
                        $query -> bindParam(':id_login',$id_login, PDO::PARAM_STR);
                        $query->execute();
                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                        $nmr=1;
                        // $ket = array();
                        if($query->rowCount() > 0){
                            foreach($results as $res){                  
                                if(!$res->gambar){
                                    echo "<img alt='image' src='./assets/img/avatar/avatar-1.png' class='rounded-circle mr-1'>";
                                }else{
                                    ?>
                                     <img alt="image" src="./image/<?= $res->gambar ;?>" class="rounded-circle mr-1">
                                    <?php
                                } 
                            } 
                        } ?>
                    <div class="d-sm-none d-lg-inline-block">Hi, <?= $_SESSION['username']?></div></a>
                    <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-title">Logged in</div>
                    <a href="setting.php" class="dropdown-item has-icon">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="logout.php" class="dropdown-item has-icon text-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                    </div>
                </li>
                </ul>
            </nav>