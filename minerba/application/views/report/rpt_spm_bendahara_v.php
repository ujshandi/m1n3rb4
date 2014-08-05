<script  type="text/javascript" >
$(function(){
    var url;
       
  
    clearFilter<?=$objectId;?> = function (){
        //ambil nilai-nilai filter
            $("#filter_bidang_id<?=$objectId?>").val('-1');
            $("#filter_kategori_id<?=$objectId?>").val('-1');
            $('#periodeawal<?=$objectId;?>').datebox('setValue','<?=date('01-m-Y')?>');
            $('#periodeakhir<?=$objectId;?>').datebox('setValue','<?=date('d-m-Y')?>');
//        $("#filter_nama").val('');
//        $("#filter_alamat").val('');


        //$('#dg<=$objectId;?>').datagrid({url:"<=base_url()?>report/rpt_spm_bendahara/grid/"+filnip+"/"+filnama+"/"+filalamat});
    }

        //tipe 1=grid, 2=pdf, 3=excel
    getUrl<?=$objectId;?> = function (tipe){
        var filawal =  $('#periodeawal<?=$objectId;?>').datebox('getValue');	
        var filakhir = $("#periodeakhir<?=$objectId;?>").datebox('getValue');	
        var filbidang = $("#filter_bidang_id<?=$objectId;?>").val();
        var filkategori = $("#filter_kategori_id<?=$objectId;?>").val();
        

        filbidang = ((filbidang=="undefined")||(filbidang=="")||(filbidang==null))?"-1":filbidang;
        filkategori = ((filkategori=="undefined")||(filkategori=="")||(filbidang==null))?"-1":filkategori;
        if (tipe==1){
                return "<?=base_url()?>report/rpt_spm_bendahara/grid/<?=$tipereport?>/"+filawal+"/"+filakhir+"/"+filbidang+"/"+filkategori;
        }
        else if (tipe==2){
                return "<?=base_url()?>report/rpt_spm_bendahara/pdf/<?=$tipereport?>/"+filawal+"/"+filakhir+"/"+filbidang+"/"+filkategori;
        }else if (tipe==3){
                return "<?=base_url()?>report/rpt_spm_bendahara/excel/<?=$tipereport?>/"+filawal+"/"+filakhir+"/"+filbidang+"/"+filkategori;
        }

    }

    searchData<?=$objectId;?> = function (){
        //ambil nilai-nilai filter
        

        $('#dg<?=$objectId;?>').datagrid({url:getUrl<?=$objectId;?>(1)});
    }
    //end searhData 

  
    printData<?=$objectId;?>=function(){			
        //$.jqURL.loc(getUrl<?=$objectId;?>(2),{w:800,h:600,wintype:"_blank"});
        window.open(getUrl<?=$objectId;?>(2));;
    }

    toExcel<?=$objectId;?>=function(){

        window.open(getUrl<?=$objectId;?>(3));;
    }

  
        setTimeout(function(){
            var wHeight = $(window).height();
            clearFilter<?=$objectId?>();
            $("#dg<?=$objectId;?>").css('height',wHeight-156);    
            searchData<?=$objectId?>();
                
        },100);
 });
</script>
	<style type="text/css">
		#fm<?=$objectId;?>{
			margin:0;
			padding:10px 30px;
		}
		.ftitle{
			font-size:14px;
			font-weight:bold;
			color:#666;
			padding:5px 0;
			margin-bottom:10px;
			border-bottom:1px solid #ccc;
		}
		.fitem{
			margin-bottom:5px;
		}
		.fitem label{
			display:inline-block;
			width:80px;
		}
	  .fsearch{
		background:#fafafa;
		border-radius:5px;
		-moz-border-radius:0px;
		-webkit-border-radius: 5px;
		-moz-box-shadow: 2px 2px 3px rgba(0, 0, 0, 0.2);
		-webkit-box-shadow: 2px 2px 3px rgba(0, 0, 0, 0.2);
		filter: progid:DXImageTransform.Microsoft.Blur(pixelRadius=2,MakeShadow=false,ShadowOpacity=0.2);
		margin-bottom:10px;
		border: 1px solid #99BBE8;
	    color: #15428B;
	    font-size: 11px;
	    font-weight: bold;
	    position: relative;
	  }
	  .fsearch div{
		background:url('<?=base_url();?>public/css/themes/gray/images/panel_title.gif') repeat-x;
		height:200%;
		border-bottom: 1px solid #99BBE8;
		color:#15428B;
		font-size:10pt;
		text-transform:uppercase;
	    font-weight: bold;
	    padding: 5px;
	    position: relative;
	  }
	  .fsearch table{
	    padding: 15px;
	  }
	  .fsearch label{
		display:inline-block;
		width:60px;
	  }
		.fitemArea{
			margin-bottom:5px;
			text-align:left;
			/* border:1px solid blue; */
		}
		.fitemArea label{
			display:inline-block;
			width:84px;
			margin-bottom:5px;
		}
	</style>
	<div id="tb<?=$objectId;?>" style="height:auto">
            <table border="0" cellpadding="1" cellspacing="1" width="100%">
            <tr>
                <td>
                <div class="fsearch">                    
                    <table border="0" cellpadding="1" cellspacing="1">				
                    <tr>
                        <td>Periode : &nbsp;</td>
                        <td><input name="periodeawal" style="width:100px" id="periodeawal<?=$objectId;?>" class="easyui-datebox" data-options="formatter:myDateFormatter,parser:myDateParser"  > s.d. <input  style="width:100px" name="periodeakhir" id="periodeakhir<?=$objectId;?>" class="easyui-datebox" data-options="formatter:myDateFormatter,parser:myDateParser"  ></td>
                        <td width="20px">&nbsp;</td>
                        <td>Bidang : &nbsp;</td>
                        <td> <?=$bidanglistFilter?>  </td>
                         <td width="20px">&nbsp;</td>
                        <td>Kategori : &nbsp;</td>
                        <td> <?=$kategorilistFilter?>  </td>
                    </tr>
                    <tr>
                            <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="right" colspan="8" valign="top">
                                <a href="#" class="easyui-linkbutton" onclick="clearFilter<?=$objectId;?>();" iconCls="icon-reset">Reset</a>
                                <a href="#" class="easyui-linkbutton" onclick="searchData<?=$objectId;?>();" iconCls="icon-search">Cari</a>
                        </td>
                    </tr>
                    </table>
                </div>
                </td>
            </tr>
            </table>

	  <div style="margin-bottom:5px">
		
		<? if($this->sys_menu_model->cekAkses('PRINT;',2,$this->session->userdata('group_id'),$this->session->userdata('level_id'))){?>
			<a href="#" onclick="printData<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-print" plain="true">Print</a>
		<?}?>
		<? if($this->sys_menu_model->cekAkses('EXCEL;',2,$this->session->userdata('group_id'),$this->session->userdata('level_id'))){?>
			<a href="#" onclick="toExcel<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-excel" plain="true">Excel</a>
		<?}?>
	  </div>
	</div>
	
	<table id="dg<?=$objectId;?>" style="height:auto;width:auto" title="Daftar SPB <?=($tipereport=="spm")?" Untuk Pengajuan SPM":" Untuk Bendaharawan"?> " toolbar="#tb<?=$objectId;?>" 
               fitColumns="false" singleSelect="true" rownumbers="true" pagination="true" noWrap="false" showFooter="true">
	  <thead>
	  <tr>
              <th halign="center" align="left" rowspan="2" field="tanggal" sortable="true" width="80">Tanggal</th>
		<th halign="center" align="center" rowspan="2" field="nomor" sortable="true" width="200">Nomor</th>
		<th halign="center" align="right" rowspan="2" field="jumlah" sortable="true" width="100" formatter="formatMoney">Jumlah</th>
		<th halign="center" align="left" rowspan="2" field="bidang" sortable="true" width="100">Bidang</th>
		<th halign="center" align="left" rowspan="2" field="kategori" sortable="true" width="100">Kategori</th>
		<th halign="center" align="left" rowspan="2" field="untuk" sortable="true" width="350">Untuk Pembayaran</th>
		<th halign="center" align="left" rowspan="2" field="tujuan" sortable="true" width="225">Kepada</th>
		<th halign="center" sortable="true" colspan="2" width="125">Dibebankan pada</th>
		<th halign="center" field="bidang_id" hidden="true" colspan="2" width="0">bidang_id</th>
		<th halign="center" field="kategori_id" hidden="true" colspan="2" width="0">kategori_id</th>
		<th halign="center" field="kegiatan" hidden="true" colspan="2" width="0">kegiatan</th>
		<th halign="center" field="spb_id" hidden="true" colspan="2" width="0">spb_id</th>
	  </tr>
          <tr>
              <th halign="center"  align="center" field="beban_kode" sortable="true" width="100">Kode</th>
		<th halign="center" align="left" field="beban_kegiatan" sortable="true" width="250">Kegiatan</th>		
	  </tr>
	  </thead> 
	</table>

	 <!-- AREA untuk Form Add/EDIT >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>  -->
	
	<div id="dlg<?=$objectId;?>" class="easyui-dialog" style="width:800px;height:450px;padding:10px 20px" closed="true" buttons="#dlg-buttons">
	  <div class="ftitle">Tambah/Edit Data SPB</div>
	  <form id="fm<?=$objectId;?>" method="post">
		 <div class="fitem">
		  <label style="width:150px;vertical-align:top">Tanggal :</label>
		  <input name="tanggal" style="width:100px" id="tanggal<?=$objectId;?>" class="easyui-datebox" data-options="formatter:myDateFormatter,parser:myDateParser"  required="true">
		</div>
              <div class="fitem">
		  <label style="width:150px;vertical-align:top">No.SPB :</label>
                  <input name="nomor" id="nomor<?=$objectId?>" class="easyui-validatebox"  style="text-transform: uppercase" required="true" >
		</div>
              <div class="fitem">
		  <label style="width:150px;vertical-align:top">Bidang :</label>
		 <?=$bidanglist?>  
		</div>
              <div class="fitem">
		  <label style="width:150px;vertical-align:top">Kategori :</label>
		 <?=$kategorilist?>  
		</div>
		<div class="fitem">
		  <label style="width:150px;vertical-align:top">Dibayarkan kepada :</label>
		  <input name="tujuan" class="easyui-validatebox" size="30" required="true">
		</div>
		<div class="fitem">
		  <label style="width:150px;vertical-align:top">Tujuan Pembayaran :</label>
		  <input name="untuk" class="easyui-validatebox" size="50" required="true">
		</div>
		<div class="fitem">
		  <label style="width:150px;vertical-align:top">Beban Kegiatan :</label>
                  <span id="divKegiatan<?=$objectId?>"></span>
                <!--  <input name="beban_kode" class="easyui-validatebox" size="10" required="true"> -->
		</div>
          <!--    <div class="fitem">
		  <label style="width:150px;vertical-align:top">Nama Beban Kegiatan :</label>
		  <input name="beban_kegiatan" class="easyui-validatebox" size="50" required="true">
		</div> -->
              <div class="fitem">
		  <label style="width:150px;vertical-align:top">Jumlah :</label>
		  <input name="jumlah" class="easyui-numberbox" style="text-align:right" data-options="precision:0,groupSeparator:'.',decimalSeparator:','"  required="true">
		</div>
	  </form>
    </div>
    <div id="dlg-buttons">
	  <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveData<?=$objectId;?>()">Simpan</a>
	  <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg<?=$objectId;?>').dialog('close')">Batal</a>
    </div>