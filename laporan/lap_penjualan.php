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
    <title>Laporan data penjualan</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../assets/css/laporan.css"/>
</head>
<body onload="window.print()">
<div id="laporan">
<table align="center" style="width:900px; border-bottom:3px double;border-top:none;border-right:none;border-left:none;margin-top:5px;margin-bottom:20px;">
</table>

<table border="0" align="center" style="width:800px; border:none;margin-top:5px;margin-bottom:0px;">
<tr>
    <td colspan="2" style="width:800px;paddin-left:20px;"><center><h4>LAPORAN PENJUALAN BARANG</h4></center><br/></td>
</tr>
                       
</table>
 
<table border="0" align="center" style="width:900px;border:none;">
        <tr>
            <th style="text-align:left"></th>
        </tr>
</table>

<table border="1" align="center" style="width:900px;margin-bottom:20px;">
<thead>
    <tr>
        <th style="width:50px;">No</th>
        <th>No Faktur</th>
        <th>Tanggal</th>
        <th>Kode Barang</th>
        <th>Nama Barang</th>
        <th>Satuan</th>
        <th>Harga Jual</th>
        <th>Qty</th>
        <th>Diskon</th>
        <th>Total</th>
    </tr>
</thead>
<tbody>
<?php 
        function rupiah($angka){
                                
            $hasil_rupiah = "Rp " . number_format($angka,2,',','.');
            return $hasil_rupiah;

        }
        $no=0;
        $total=0;
        $sql1 = 'SELECT * FROM tbl_jual JOIN tbl_detail_jual WHERE tbl_jual.jual_nofak=tbl_detail_jual.d_jual_nofak';
        $query = $dbh -> prepare($sql1);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        foreach($results as $res){
        $no++;
        $nofak=$res->jual_nofak;
        $tgl=$res->jual_tanggal;
        $barang_id=$res->d_jual_barang_id;
        $barang_nama=$res->d_jual_barang_nama;
        $barang_satuan=$res->d_jual_barang_satuan;
        $barang_harjul=$res->d_jual_barang_harjul;
        $barang_qty=$res->d_jual_qty;
        $barang_diskon=$res->d_jual_diskon;
        $barang_total=$res->d_jual_total;
?>
    <tr>
        <td style="text-align:center;"><?php echo $no;?></td>
        <td style="padding-left:5px;"><?php echo $nofak;?></td>
        <td style="text-align:center;"><?php echo $tgl;?></td>
        <td style="text-align:center;"><?php echo $barang_id;?></td>
        <td style="text-align:left;"><?php echo $barang_nama;?></td>
        <td style="text-align:left;"><?php echo $barang_satuan;?></td>
        <td style="text-align:right;"><?php echo rupiah($barang_harjul);?></td>
        <td style="text-align:center;"><?php echo $barang_qty;?></td>
        <td style="text-align:right;"><?php echo rupiah($barang_diskon);?></td>
        <td style="text-align:right;"><?php echo rupiah($barang_total);?></td>
    </tr>
<?php $total+=$barang_total;}?>
</tbody>
<tfoot>
    <tr>
        <td colspan="9" style="text-align:center;"><b>Total</b></td>
        <td style="text-align:right;"><b><?php echo rupiah($total);?></b></td>
    </tr>
</tfoot>
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
</html>_
<?php } ?>