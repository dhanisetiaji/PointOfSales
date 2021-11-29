<?php
session_start();
// echo $_SESSION['username'];
// echo $_SESSION['user_level'];
error_reporting(0);
include('../include/koneksi.php');
if(strlen($_SESSION['username'])==0)
	{	
header('location:login.php');
}
if($_SESSION['user_level']==3){
    ?> <script language="JavaScript">alert('Anda tidak memiliki akses');</script>
    <a href="index.php">Go Back</a>
        <?php
        // header('Location:./dashboard.php');
    }
else{
?>
<html lang="en" moznomarginboxes mozdisallowselectionprint>
<head>
    <title>laporan data stok barang</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../assets/css/laporan.css"/>
</head>
<body onload="window.print()">
<div id="laporan">
<table align="center" style="width:900px; border-bottom:3px double;border-top:none;border-right:none;border-left:none;margin-top:5px;margin-bottom:20px;">
</table>

<table border="0" align="center" style="width:800px; border:none;margin-top:5px;margin-bottom:0px;">
<tr>
    <td colspan="2" style="width:800px;paddin-left:20px;"><center><h4>LAPORAN STOK BARANG PERKATEGORI</h4></center><br/></td>
</tr>
                       
</table>
 
<table border="0" align="center" style="width:900px;border:none;">
        <tr>
            <th style="text-align:left"></th>
        </tr>
</table>

<table border="1" align="center" style="width:900px;margin-bottom:20px;">
<?php 
    function rupiah($angka){
                        
        $hasil_rupiah = "Rp " . number_format($angka,2,',','.');
        return $hasil_rupiah;

    }
    $urut=0;
    $nomor=0;
    $group='-';
    $sql1 = 'SELECT * FROM tbl_barang JOIN tbl_kategori WHERE tbl_barang.barang_kategori_id=tbl_kategori.kategori_id';
    $query = $dbh -> prepare($sql1);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    foreach($results as $res){
    $nomor++;
    $urut++;
    if($group=='-' || $group!=$res->kategori_name){
        $kat=$res->kategori_name;
        // $query=$this->db->query("SELECT kategori_id,kategori_nama,barang_nama,SUM(barang_stok) AS tot_stok FROM tbl_kategori JOIN tbl_barang ON kategori_id=barang_kategori_id WHERE kategori_nama='$kat'");
        // $t=$query->row_array();
        $query1 = $dbh -> prepare('SELECT kategori_id,kategori_name,barang_nama,SUM(barang_stok) AS tot_stok FROM tbl_kategori JOIN tbl_barang ON kategori_id=barang_kategori_id WHERE kategori_name=:kategori_name');
        $query1 -> bindParam(':kategori_name',$kat, PDO::PARAM_STR);
        $query1->execute();
        $t = $query1->fetch();
        // var_dump($t['tot_stok']);
        // die();
        $tots=$t['tot_stok'];
        if($group!='-')
        echo "</table><br>";
        echo "<table align='center' width='900px;' border='1'>";
        echo "<tr><td colspan='2'><b>Kategori: $kat</b></td> <td style='text-align:center;'><b>Total Stok: $tots </b></td></tr>";
echo "<tr style='background-color:#ccc;'>
    <td width='4%' align='center'>No</td>
    <td width='60%' align='center'>Nama Barang</td>
    <td width='30%' align='center'>Stok</td>
    
    </tr>";
$nomor=1;
    }
    $group=$res->kategori_name;
        if($urut==500){
        $nomor=0;
            echo "<div class='pagebreak'> </div>";
            //echo "<center><h2>KALENDER EVENTS PER TAHUN</h2></center>";
            }
        ?>
        <tr>
                <td style="text-align:center;vertical-align:top;text-align:center;"><?php echo $nomor; ?></td>
                <td style="vertical-align:top;padding-left:5px;"><?= htmlentities($res->barang_nama); ?></td>
                <td style="vertical-align:top;text-align:center;"><?= htmlentities($res->barang_stok); ?></td>  
        </tr>
        

        <?php
        }
        ?>
</table>

</table>
<table align="center" style="width:800px; border:none;margin-top:5px;margin-bottom:20px;">
    <tr>
        <td></td>
</table>
<table align="center" style="width:800px; border:none;margin-top:5px;margin-bottom:20px;">
    <tr>
        <td align="right">Bandung, <?php echo date('d-M-Y')?></td>
    </tr>
    <tr>
        <td align="right"></td>
    </tr>
   
    <tr>
    <td><br/><br/><br/><br/></td>
    </tr>    
    <tr>
        <td align="right">( <?php echo $_SESSION['username'];?> )</td>
    </tr>
    <tr>
        <td align="center"></td>
    </tr>
</table>
<table align="center" style="width:800px; border:none;margin-top:5px;margin-bottom:20px;">
    <tr>
        <th><br/><br/></th>
    </tr>
    <tr>
        <th align="left"></th>
    </tr>
</table>
</div>
</body>
</html>
<?php } ?>