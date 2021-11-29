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
    <title>laporan data barang</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../assets/css/laporan.css"/>
</head>
<body onload="window.print()">
<div id="laporan">
<table align="center" style="width:900px; border-bottom:3px double;border-top:none;border-right:none;border-left:none;margin-top:5px;margin-bottom:20px;">
</table>

<table border="0" align="center" style="width:800px; border:none;margin-top:5px;margin-bottom:0px;">
<tr>
    <td colspan="2" style="width:800px;paddin-left:20px;"><center><h4>LAPORAN DATA BARANG</h4></center><br/></td>
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
        
        if($group!='-')
        echo "</table><br>";
        echo "<table align='center' width='900px;' border='1'>";
        echo "<tr><td colspan='6'><b>Kategori: $kat</b></td> </tr>";
echo "<tr style='background-color:#ccc;'>
    <td width='4%' align='center'>No</td>
    <td width='10%' align='center'>Kode Barang</td>
    <td width='40%' align='center'>Nama Barang</td>
    <td width='10%' align='center'>Satuan</td>
    <td width='20%' align='center'>Harga Jual</td>
    <td width='30%' align='center'>Stok</td>
    
    </tr>";
$nomor=1;
    }
    $group=$res->kategori_name;
        if($urut==500){
        $nomor=0;
            echo "<div class='pagebreak'> </div>";

            }
        ?>
        <tr>
                <td style="text-align:center;vertical-align:center;text-align:center;"><?php echo $nomor; ?></td>
                <td style="vertical-align:center;padding-left:5px;text-align:center;"><?= htmlentities($res->barang_id);?></td>
                <td style="vertical-align:center;padding-left:5px;"><?= htmlentities($res->barang_nama);?></td>
                <td style="vertical-align:center;text-align:center;"><?= htmlentities($res->barang_satuan); ?></td>
                <td style="vertical-align:center;padding-right:5px;text-align:right;"><?= htmlentities(rupiah($res->barang_harjul)); ?></td>
                <td style="vertical-align:center;text-align:center;text-align:center;"><?= htmlentities($res->barang_stok); ?></td>  
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