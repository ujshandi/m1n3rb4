<script  type="text/javascript" >
$(function(){
    var url;
    myformatter=function(date){
        var y = date.getFullYear();
        var m = date.getMonth()+1;
        var d = date.getDate();
        return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
    }
    myparser=function(s){
       if (!s) return new Date();
       var ss = (s.split('-'));
       var y = parseInt(ss[2],10);
       var m = parseInt(ss[1],10);
       var d = parseInt(ss[0],10);
       if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
           return new Date(y,m-1,d);
       } else {
           return new Date();
       }
   }
   
   
   importData<?=$objectId;?>=function(){			
        $('#fmimport<?=$objectId;?>').form('submit',{
            url: url,
            onSubmit: function(){
                    //return $(this).form('validate');
            },
            success: function(result){
                //alert(result);
                var result = eval('('+result+')');
                if (result.success){
                    $.messager.show({
                           title: 'Success',
                           msg: result.msg
                   }); 
                   $('#dlgimport<?=$objectId;?>').dialog('close');	// close the dialog
                   $('#dg<?=$objectId;?>').datagrid('reload');		// reload the user data
                } else {
                    $.messager.show({
                            title: 'Error',
                            msg: result.msg
                    });
                }
            }
        });
    }
			
    import<?=$objectId;?> = function (){  
            $('#dlgimport<?=$objectId;?>').dialog('open').dialog('setTitle','Import Data Kegiatan dari Excel');
            $('#fmimport<?=$objectId;?>').form('clear');  
            url = base_url+'rujukan/kegiatan/import'; 
    }
    
    newData<?=$objectId;?> = function (){  
        $('#dlg<?=$objectId;?>').dialog('open').dialog('setTitle','Tambah Kegiatan');  
        $('#fm<?=$objectId;?>').form('clear');  
        url = base_url+'rujukan/kegiatan/save/add';  
    }
    //end newData 


    clearFilter<?=$objectId;?> = function (){
        //ambil nilai-nilai filter
//        $("#filter_nip").val('');
//        $("#filter_nama").val('');
//        $("#filter_alamat").val('');


        //$('#dg<?=$objectId;?>').datagrid({url:"<?=base_url()?>rujukan/kegiatan/grid/"+filnip+"/"+filnama+"/"+filalamat});
    }

        //tipe 1=grid, 2=pdf, 3=excel
    getUrl<?=$objectId;?> = function (tipe){
        if (tipe==1){
                return "<?=base_url()?>rujukan/kegiatan/grid/";
        }
        else if (tipe==2){
                return "<?=base_url()?>rujukan/kegiatan/pdf/";
        }else if (tipe==3){
                return "<?=base_url()?>rujukan/kegiatan/excel/";
        }

    }

    searchData<?=$objectId;?> = function (){
      

        $('#dg<?=$objectId;?>').datagrid({url:"<?=base_url()?>rujukan/kegiatan/grid/"});
    }
    //end searhData 

    editData<?=$objectId;?> = function (){
        var row = $('#dg<?=$objectId;?>').datagrid('getSelected');
        $('#fm<?=$objectId;?>').form('clear');  
        //alert(row.dokter_kode);
        if (row){
                $('#dlg<?=$objectId;?>').dialog('open').dialog('setTitle','Edit Kegiatan');
                $('#fm<?=$objectId;?>').form('load',row);
                url = base_url+'rujukan/kegiatan/save/edit/'+row.kegiatan_id;//+row.id;//'update_user.php?id='+row.id;
        }
    }
        //end editData

    deleteData<?=$objectId;?> = function (){
        var row = $('#dg<?=$objectId;?>').datagrid('getSelected');
        if(row){
            if(confirm("Apakah yakin akan menghapus data '" + row.nama + "'?")){
                var response = '';
                $.ajax({ type: "GET",
                    url: base_url+'rujukan/kegiatan/delete/' + row.kegiatan_id ,
                    async: false,
                    success : function(response){
                        var response = eval('('+response+')');
                        if (response.success){
                            $.messager.show({
                                title: 'Success',
                                msg: 'Data Berhasil Dihapus'
                            });

                            // reload and close tab
                            $('#dg<?=$objectId;?>').datagrid('reload');
                        } else {
                            $.messager.show({
                                title: 'Error',
                                msg: response.msg
                            });
                        }
                    }
                });
            }
        }
    }
        //end deleteData 

    printData<?=$objectId;?>=function(){			
        //$.jqURL.loc(getUrl<?=$objectId;?>(2),{w:800,h:600,wintype:"_blank"});
        window.open(getUrl<?=$objectId;?>(2));;
    }

    toExcel<?=$objectId;?>=function(){

        window.open(getUrl<?=$objectId;?>(3));;
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
        //end saveData

        setTimeout(function(){
            var wHeight = $(window).height();			
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
	  <div style="margin-bottom:5px">
		<? if($this->sys_menu_model->cekAkses('ADD;',3,$this->session->userdata('group_id'),$this->session->userdata('level_id'))){?>
			<a href="#" onclick="newData<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-add" plain="true">Tambah</a>  
		<?}?>
		<? if($this->sys_menu_model->cekAkses('EDIT;',3,$this->session->userdata('group_id'),$this->session->userdata('level_id'))){?>
			<a href="#" onclick="editData<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-edit" plain="true">Edit</a>
		<?}?>
		<? if($this->sys_menu_model->cekAkses('DELETE;',3,$this->session->userdata('group_id'),$this->session->userdata('level_id'))){?>
			<a href="#" onclick="deleteData<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-remove" plain="true">Hapus</a>
		<?}?>
		<? if($this->sys_menu_model->cekAkses('IMPORT;',3,$this->session->userdata('group_id'),$this->session->userdata('level_id'))){?>
			<a href="#" onclick="import<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-import" plain="true">Import</a>
		<?}?>
	
	  </div>
	</div>
	
	<table id="dg<?=$objectId;?>" style="height:auto;width:auto" title="Data Kegiatan" toolbar="#tb<?=$objectId;?>" fitColumns="false" singleSelect="true" nowrap="false" rownumbers="true" pagination="true">
	  <thead>
	  <tr>
		  <th halign="center" align="left" field="tahun" sortable="true" width="60">Tahun</th>
		<th halign="center" align="left" field="kode" sortable="true" width="100">Kode Kegiatan</th>
		<th halign="center" align="left" field="nama" sortable="true" width="400">Nama Kegiatan</th>
		<th halign="center" align="left" field="bidang" sortable="true" width="200">Bidang</th>
		<th halign="center" align="right" field="pagu" sortable="true" width="150">Pagu</th>
		
		
	  </tr>
          
	  </thead> 
	</table>

	 <!-- AREA untuk Form Add/EDIT >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>  -->
	
	<div id="dlg<?=$objectId;?>" class="easyui-dialog" style="width:750px;height:300px;padding:10px 20px" closed="true" buttons="#dlg-buttons">
	  <div class="ftitle">Tambah/Edit Data Kegiatan</div>
	  <form id="fm<?=$objectId;?>" method="post">
		 <div class="fitem">
		  <label style="width:100px;vertical-align:top">Tahun :</label>
		  <input name="tahun" class="easyui-numberbox" size="10" required="true">		  
		</div>
         <div class="fitem">
		  <label style="width:100px;vertical-align:top">Kode Kegiatan :</label>
		  <input name="kode" class="easyui-validatebox" size="20" required="true">
		  <input name="kegiatan_id" type="hidden" required="true">
		</div>
		<div class="fitem">
		  <label style="width:100px;vertical-align:top">Nama Kegiatan :</label>
		  <input name="nama" class="easyui-validatebox" size="50" required="true">		  
		</div>
		<div class="fitem">
		  <label style="width:100px;vertical-align:top">Bidang :</label>
		 <?=$bidanglist?>  
		</div>
		<div class="fitem">
		  <label style="width:100px;vertical-align:top">Pagu :</label>
		  <input name="pagu" class="easyui-numberbox" data-options="precision:2,groupSeparator:'.',decimalSeparator:','" size="25" required="true">		  
		</div>
             
	  </form>
    </div>
    <div id="dlg-buttons">
	  <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveData<?=$objectId;?>()">Simpan</a>
	  <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg<?=$objectId;?>').dialog('close')">Batal</a>
    </div>


<!-- Form Import -->
	<div id="dlgimport<?=$objectId;?>" class="easyui-dialog" style="width:500px;height:200px;padding:10px 20px" closed="true" buttons="#dlg-buttons">
		<div class="ftitle">Pastikan format excel sesuai dengan format yang telah ditentukan</div>
		<?php 
			$attributes = array('id' => 'fmimport'.$objectId);
			echo form_open_multipart('', $attributes);
		?>
			<div class="fitem">
				<label style="width:80px;vertical-align:top">Nama File</label>
				<input type="file" name="datafile"/>
			</div>
		</form>
    	<div id="dlg-buttons">
			<a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="importData<?=$objectId;?>()">Import</a>
			<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlgimport<?=$objectId;?>').dialog('close')">Batal</a>
    	</div>
	</div>
