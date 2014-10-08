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
			  <td>Bukti Pengembalian SPBY</td>
			</tr>
		    <tr>
			  <td colspan="3" height="10px"><hr></td>
			</tr>
			<tr>
		      <td colspan="2">
				<table border="0" width="100%" cellpadding="1" cellspacing="0">				
				<tr>
				  <td width="100px">Tanggal </td>
				  <td width="5px">:</td>
				  <td><label><?=$tanggal?></label></td>
				</tr>
				
				<tr>
			  <td align="center" colspan="2" height="25px"></td>
			</tr>
				<tr>
				  
				  <td colspan="3"><label>Dengan ini dikembalikan berkas sebagai berikut :</label>
					<table>
					<tr>
						<td>Nomor SPBY</td>
						<td>:</td>
						<td><?=$nomor?></td>
					</tr>
					<tr>
						<td>Tanggal</td>
						<td>:</td>
						<td><?=$tanggal?></td>
					</tr>
					<tr>
						<td>Keterangan</td>
						<td>:</td>
						<td><?=$keterangan?></td>
					</tr>
					
					</table>
				  
				  </td>
				</tr>
				</table>
			  </td>
			</tr>
			<tr>
			  <td align="center" colspan="2" height="825px"></td>
			</tr>
			
			<tr>
			  <td colspan="2">
				<table class="table" style="margin-right:-5px" align="right" border="0" width="50%" cellpadding="3px" cellspacing="0">
				<tr>
					<th>Penerima Berkas</th>
					<th>Verifikator</th>
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