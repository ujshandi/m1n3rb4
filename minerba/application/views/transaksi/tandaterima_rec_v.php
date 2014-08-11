<script  type="text/javascript" >
$(function(){
    var url=base_url+'transaksi/tandaterima/save/add';
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
               
                var ids = [];
                var rows = $("#dg<?=$objectId?>").datagrid('getSelections');
                for(var i=0; i<rows.length; i++){
                    ids.push(rows[i].spb_id);
                }
                //alert(ids.join(''));
                $("#spb_ids<?=$objectId?>").val(ids.join(','));
                
                return $(this).form('validate');
            },
            success: function(result){
               // alert(result);
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
          <?php if (!$editmode){?>
            $('#dg<?=$objectId;?>').datagrid({url:getUrl<?=$objectId;?>(1)});
           <?php }?>  
    }
    
    
      $("#bidang_id<?=$objectId?>").change(function(){
             searchData<?=$objectId;?>();   
           
        });

         onSelect<?=$objectId?> = function(){
            setNewNumber<?=$objectId?>();
        }
        
        function setNewNumber<?=$objectId?>(){
            <?php if (!$editmode){?>
            var tanggal =  $('#tanggal<?=$objectId;?>').datebox('getValue');
            var arrTgl  = tanggal.split("-");
            $.ajax({url:base_url+"transaksi/tandaterima/get_new_number/"+arrTgl[1]+"/"+arrTgl[2],
                success : function(data){
                $("#nomor<?=$objectId?>").val(data);
                }					
            })
            <?php }?>
        }
        
        loadData<?=$objectId?> = function(){
            <?php if ($editmode){?>
                 $("#nomor<?=$objectId?>").val("<?=$nomor?>");                  
                 $("#tanggal<?=$objectId?>").datebox('setValue','<?=$tanggal?>');    
                 $("#keterangan<?=$objectId?>").html("<?=$keterangan?>");    
                 $("#bidang_id<?=$objectId?>").val("<?=$bidang_id?>");   
                  $('#dg<?=$objectId;?>').datagrid({
                      url:"<?=base_url()?>transaksi/tandaterima/gridspb/<?=$tipetandaterima?>/<?=$bidang_id?>/<?=$tanda_id?>"
                  });
            <?php }?>        
        }
        
        setTimeout(function(){
            var wHeight = $(window).height();
             $("#dg<?=$objectId;?>").css('height',wHeight-400);  
             $('#tanggal<?=$objectId;?>').datebox('setValue','<?=date('d-m-Y')?>');
              searchData<?=$objectId;?>();   
              setNewNumber<?=$objectId?>();
              loadData<?=$objectId?>();
        },100);
 });
</script>
	<style type="text/css">
		#fm<?=$objectId;?>{
			margin:0;
			padding:10px 30px;
		}
		
	</style>
<!-- AREA untuk Form Add/EDIT >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>  -->
<div>
    <div class="ftitle" style="margin-top:10px;margin-left: 30px">Tambah/Edit Data Tanda Terima SPB</div>
    <form id="fm<?=$objectId;?>" method="post">
           <div class="fitem">
            <label style="width:150px;vertical-align:top">Tanggal :</label>
            <input name="tanggal" style="width:100px" id="tanggal<?=$objectId;?>" class="easyui-datebox" value="<?=date('d-m-Y')?>" data-options="formatter:myDateFormatter,parser:myDateParser,onSelect:onSelect<?=$objectId?>"  required="true">
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
            <textarea name="keterangan"  id="keterangan<?=$objectId?>" cols="50"></textarea>
            <input name="spb_ids" id="spb_ids<?=$objectId?>" type="hidden" > 
          </div>
        <table id="dg<?=$objectId;?>" style="height:auto;width:auto" title="Daftar SPB untuk dibuatkan tanda terima nya. "  
               fitColumns="false" singleSelect="false" rownumbers="true" noWrap="false" showFooter="true">
            <?php if (!$editmode){?>
            <thead data-options="frozen:true">
                <tr>
                <th  rowspan="2" data-options="field:'ck',checkbox:true"></th>
                </tr>
            </thead>
            <?php }?>
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
		<th halign="center" field="bidang_id" hidden="true"  width="0">bidang_id</th>
		<th halign="center" field="kategori_id" hidden="true" width="0">kategori_id</th>
		<th halign="center" field="kegiatan" hidden="true"  width="0">kegiatan</th>
		<th halign="center" field="spb_id" hidden="true"  width="0">spb_id</th>
	  </tr>
          <tr>
              <th halign="center"  align="center" field="beban_kode" sortable="true" width="100">Kode</th>
		<th halign="center" align="left" field="beban_kegiatan" sortable="true" width="250">Kegiatan</th>		
	  </tr>
         
	  </thead> 
	</table>
    </form>

    <div id="dlg-buttons"  style="margin-left:30px">
	  <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveData<?=$objectId;?>()">Simpan</a>
	  <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="cancel<?=$objectId;?>()">Batal</a>
    </div>
</div>	