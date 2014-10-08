<html>
<head>
<style type="text/css">
      * {
        padding: 0;
        margin: 0;
      }
      body {
        font-family: arial, helvetica, sans-serif;
		margin-left:5px;
      }
      .table {
        border-collapse: collapse;
        margin: 5px;
		
      }
      .table td, table th {
        border: 1px solid black;
		padding:5px;
      }
      @media print {
        table td, table th {
          border: none;
		  padding:5px;
        }
        body {
          font-family: serif;
        }
      }
    </style>
</head>
<body >


		    <table cellpadding="0" cellspacing="0" width="1024px" border="0">
		    <tr>
			  <td class="boxLogoPrint"></td>
			  <td></td>
			</tr>
		    <tr style="text-align:center;height:20px;text-transform:uppercase;font-weight:bold;">
			  <td></td>
			  <td>Bukti Tanda Terima</td>
			</tr>
		    <tr>
			  <td colspan="3" height="10px"><hr></td>
			</tr>
			<tr>
		      <td colspan="2">
				<table border="0" width="100%" cellpadding="1" cellspacing="0">
				
				
				<tr>
				  <td width="100px">Nomor </td>
				  <td width="5px">:</td>
				  <td><label><?=$nomor?></label></td>
				</tr>
				<tr>
				  <td>Tanggal </td>
				  <td width="5px">:</td>
				  <td><label><?=$tanggal?></label></td>
				</tr>
				<tr>
				  <td>Keterangan</td>
				  <td width="5px">:</td>
				  <td><label><?=$keterangan?></label></td>
				</tr>
				</table>
			  </td>
			</tr>
			<tr>
			  <td align="center" colspan="2" height="25px"></td>
			</tr>
			<tr>
			  <td colspan="2">
			  Daftar SPBY yang diterima :
				<table class="table" border="0" width="100%" cellpadding="3px" cellspacing="0">
				<tr style="text-align:center;background-color:#fff;height:20px;text-transform:uppercase;font-weight:bold;">
				  <td style="width:10px;">No.</td>
				  <td style="width:100px;">Nomor</td>
				  <td style="width:125px;">Bidang</td>
				  <td style="width:125px;">Kategori</td>
				  <td style="width:125px;">Jumlah</td>
				  <td style="width:125px;">Untuk Pembayaran</td>
				  <td style="width:125px;">Tujuan Pembayaran</td>
				  
				</tr>
				<tr style="background-color:#fff;height:3px;">
				  <td colspan="2"></td>
				</tr>
				<?php
				   
				   
					foreach ($listSpb as $row	){
				  ?>
				<tr >
				  <td><?=$row[0]?></td>
				  <td><?=$row[1]?></td>
				  <td><?=$row[3]?></td>
				  <td><?=$row[4]?></td>
				  <td align="right"><?=$row[5]?></td>
				  <td><?=$row[6]?></td>
				  <td><?=$row[7]?></td>
				
				  
				</tr>
				
				<?php
				}//end for ?>
				</table>
			  </td>
			</tr>
			<tr>
			  <td align="center" colspan="2" height="5px"></td>
			</tr>
			<tr>
			  <td colspan="2">
				<table class="table" style="margin-right:-5px" align="right" border="0" width="50%" cellpadding="3px" cellspacing="0">
				<tr>
					<th>Yang Menyerahkan</th>
					<th>Penerima</th>
				</tr>
				<tr height="100px">
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				</table>
			  </td>
			</tr>
			</table>
	     
</body>
</html>