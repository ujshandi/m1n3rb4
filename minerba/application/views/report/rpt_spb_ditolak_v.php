<script  type="text/javascript" >
$(function(){
    var url;
 


    clearFilter<?=$objectId;?> = function (){
        //ambil nilai-nilai filter
            $("#filter_bidang_id<?=$objectId?>").val('-1');
            $("#filter_kategori_id<?=$objectId?>").val('-1');
            $('#periodeawal<?=$objectId;?>').datebox('setValue','<?=date('01-01-Y')?>');
            $('#periodeakhir<?=$objectId;?>').datebox('setValue','<?=date('d-m-Y')?>');
			$("#txtNomor<?=$objectId;?>").val();
    }

        //tipe 1=grid, 2=pdf, 3=excel
    getUrl<?=$objectId;?> = function (tipe){
        var filawal =  $('#periodeawal<?=$objectId;?>').datebox('getValue');	
        var filakhir = $("#periodeakhir<?=$objectId;?>").datebox('getValue');	
        var filbidang = $("#filter_bidang_id<?=$objectId;?>").val();
        var filkategori = $("#filter_kategori_id<?=$objectId;?>").val();
        var filnomor = $("#txtNomor<?=$objectId;?>").val();

        filbidang = ((filbidang=="undefined")||(filbidang=="")||(filbidang==null))?"-1":filbidang;
        filkategori = ((filkategori=="undefined")||(filkategori=="")||(filbidang==null))?"-1":filkategori;
		 filnomor = ((filnomor=="undefined")||(filnomor=="")||(filnomor==null))?"-1":filnomor;
        if (tipe==1){
                return "<?=base_url()?>report/rpt_spb_ditolak/grid/<?=$tipeapproval?>/"+filawal+"/"+filakhir+"/"+filbidang+"/"+filkategori+"/"+filnomor;
        }
        else if (tipe==2){
                return "<?=base_url()?>report/rpt_spb_ditolak/pdf/<?=$tipeapproval?>/"+filawal+"/"+filakhir+"/"+filbidang+"/"+filkategori+"/"+filnomor;
        }else if (tipe==3){
                return "<?=base_url()?>report/rpt_spb_ditolak/excel/<?=$tipeapproval?>/"+filawal+"/"+filakhir+"/"+filbidang+"/"+filkategori+"/"+filnomor;
        }

    }

    searchData<?=$objectId;?> = function (){
        //ambil nilai-nilai filter
        

        $('#dg<?=$objectId;?>').datagrid({url:getUrl<?=$objectId;?>(1)});
    }
    //end searhData 

    

    printData<?=$objectId;?>=function(){			
        //$.jqURL.loc(getUrl<?=$objectId;?>(2),{w:800,h:600,wintype:"_blank"});
		alert("underconstruction");return;
        window.open(getUrl<?=$objectId;?>(2));;
    }

    toExcel<?=$objectId;?>=function(){
		alert("underconstruction");return;
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
		
		
		
	</style>
	<div id="tb<?=$objectId;?>" style="height:auto">
            <table border="0" cellpadding="1" cellspacing="1" width="100%">
            <tr>
                <td>
                <div class="fsearch fieldset">  <h1><span>Kriteria Pencarian</span></h1>                   
                    <table border="0" cellpadding="1" cellspacing="4">				
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
						 <td>Nomor SPBY : &nbsp;</td>
                        <td colspan="2"><input name="txtNomor" style="padding:7px;font-size:14px" size="33px"  id="txtNomor<?=$objectId;?>" class="easyui-validatebox" /></td>
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
	
	<table id="dg<?=$objectId;?>" style="height:auto;width:auto" title="Data SPBY Yang Ditolak " toolbar="#tb<?=$objectId;?>" 
               fitColumns="false" singleSelect="true" rownumbers="true" pagination="true" noWrap="false" showFooter="true">
	  <thead>
	  <tr>
		<th halign="center" align="left" rowspan="2" field="keterangan" sortable="true" width="120">Keterangan Ditolak</th>
		<th halign="center" align="left" rowspan="2" field="tanggal" sortable="true" width="80">Tanggal</th>
		<th halign="center" align="center" rowspan="2" field="nomor" sortable="true" width="200">Nomor</th>
		<th halign="center" align="right" rowspan="2" field="jumlah" sortable="true" width="100" formatter="formatMoney">Jumlah</th>
		<th halign="center" align="left" rowspan="2" field="bidang" sortable="true" width="100">Bidang</th>
		<th halign="center" align="left" rowspan="2" field="kategori" sortable="true" width="100">Kategori</th>
		<th halign="center" align="left" rowspan="2" field="untuk" sortable="true" width="350">Untuk Pembayaran</th>
		<th halign="center" align="left" rowspan="2" field="tujuan" sortable="true" width="225">Kepada</th>
		<th halign="center" sortable="true" colspan="2" width="125">Dibebankan pada</th>
	  </tr>
          <tr>
              <th halign="center"  align="center" field="beban_kode" sortable="true" width="100">Kode</th>
		<th halign="center" align="left" field="beban_kegiatan" sortable="true" width="250">Kegiatan</th>		
	  </tr>
	  </thead> 
	</table>

	