<script  type="text/javascript" >
$(function(){
    var url;
     $('textarea').autosize(); 
   
   cancel<?=$objectId;?>=function(){
        $('#tt').tabs('close', 'Tambah Tanda Terima');
    }
		
    printData<?=$objectId;?>=function(){			
        //$.jqURL.loc(getUrl<?=$objectId;?>(2),{w:800,h:600,wintype:"_blank"});
        window.open(getUrl<?=$objectId;?>(2));;
    }

    
    saveData<?=$objectId;?>=function(){
        $('#fm<?=$objectId;?>').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                //alert(result);
                var result = eval('('+result+')');
                if (result.success){
                    $.messager.show({
                            title: 'Pesan',
                            msg: 'Data berhasil disimpan'
                    });
                    $('#dlg<?=$objectId;?>').dialog('close');		// close the dialog
                    $('#dg<?=$objectId;?>').datagrid('reload');	// reload the user data
                } else {
                    $.messager.show({
                            title: 'Error',
                            msg: result.msg
                    });
                }
            }
        });
    }
       
        getUrl<?=$objectId;?> = function (tipe){
        
        var filbidang = $("#bidang_id<?=$objectId;?>").val();
        

        
        if (tipe==1){
            return "<?=base_url()?>transaksi/tandaterima/gridspb/<?=$tipetandaterima?>/"+filbidang;
        }
        else if (tipe==2){
                return "<?=base_url()?>transaksi/tandaterima/pdf/<?=$tipetandaterima?>/"+filawal+"/"+filakhir+"/"+filbidang+"/"+filkategori;
        }else if (tipe==3){
                return "<?=base_url()?>transaksi/tandaterima/excel/<?=$tipetandaterima?>/"+filawal+"/"+filakhir+"/"+filbidang+"/"+filkategori;
        }

    }

    searchData<?=$objectId;?> = function (){
        //ambil nilai-nilai filter
        

        $('#dg<?=$objectId;?>').datagrid({url:getUrl<?=$objectId;?>(1)});
    }
    
    
      $("#bidang_id<?=$objectId?>").change(function(){
             searchData<?=$objectId;?>();   
           
        });

        setTimeout(function(){
            var wHeight = $(window).height();
             $("#dg<?=$objectId;?>").css('height',wHeight-400);  
             $('#tanggal<?=$objectId;?>').datebox('setValue','<?=date('d-m-Y')?>');
              searchData<?=$objectId;?>();   
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
<!-- AREA untuk Form Add/EDIT >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>  -->
<div>
    <div class="ftitle">Tambah/Edit Data Tanda Terima</div>
    <form id="fm<?=$objectId;?>" method="post">
           <div class="fitem">
            <label style="width:150px;vertical-align:top">Tanggal :</label>
            <input name="tanggal" id="tanggal<?=$objectId;?>" class="easyui-datebox" value="<?=date('d-m-Y')?>" data-options="formatter:myDateFormatter,parser:myDateParser"  required="true">
          </div>
        <div class="fitem">
            <label style="width:150px;vertical-align:top">No.Tanda Terima :</label>
            <input name="nomor" id="nomor<?=$objectId?>" class="easyui-validatebox"  style="text-transform: uppercase" required="true" >
          </div>
        <div class="fitem">
            <label style="width:150px;vertical-align:top">Bidang :</label>
           <?=$bidanglist?>  
          </div>

          <div class="fitem">
            <label style="width:150px;vertical-align:top" >Keterangan :</label>
            <textarea name="keterangan" cols="50"></textarea>
          <!--  <input name="beban_kode" class="easyui-validatebox" size="10" required="true"> -->
          </div>
        <table id="dg<?=$objectId;?>" style="height:auto;width:auto" title="Daftar SPB untuk dibuatkan tanda terima nya. " toolbar="#tb<?=$objectId;?>" 
               fitColumns="false" singleSelect="false" rownumbers="true" pagination="true" noWrap="false" showFooter="true">
	  <thead>
	  <tr>
                <th  rowspan="2" data-options="field:'ck',checkbox:true"></th>
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
    </form>

    <div id="dlg-buttons">
	  <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveData<?=$objectId;?>()">Simpan</a>
	  <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="cancel<?=$objectId;?>()">Batal</a>
    </div>
</div>	