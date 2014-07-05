<script  type="text/javascript" >
$(function(){
    var url;
       

    newData<?=$objectId;?> = function (){    
        addTab("Tambah Tanda Terima","transaksi/tandaterima/add");
    }
    //end newData 


    clearFilter<?=$objectId;?> = function (){
        //ambil nilai-nilai filter
            $("#filter_bidang_id<?=$objectId?>").val('-1');
            $("#filter_kategori_id<?=$objectId?>").val('-1');
            $('#periodeawal<?=$objectId;?>').datebox('setValue','<?=date('01-m-Y')?>');
            $('#periodeakhir<?=$objectId;?>').datebox('setValue','<?=date('d-m-Y')?>');
//        $("#filter_nama").val('');
//        $("#filter_alamat").val('');


        //$('#dg<=$objectId;?>').datagrid({url:"<=base_url()?>transaksi/tandaterima/grid/"+filnip+"/"+filnama+"/"+filalamat});
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
                return "<?=base_url()?>transaksi/tandaterima/grid/<?=$tipetandaterima?>/"+filawal+"/"+filakhir+"/"+filbidang+"/"+filkategori;
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
    //end searhData 

    editData<?=$objectId;?> = function (){
        var row = $('#dg<?=$objectId;?>').datagrid('getSelected');
        $('#fm<?=$objectId;?>').form('clear');  
        //alert(row.dokter_kode);
        if (row){
                $('#dlg<?=$objectId;?>').dialog('open').dialog('setTitle','Edit Tanda Terima');
                $('#fm<?=$objectId;?>').form('load',row);
                autoKegiatan<?=$objectId;?>('',row.kegiatan);
                url = base_url+'transaksi/tandaterima/save/edit/'+row.tanda_id;//+row.id;//'update_user.php?id='+row.id;
        }
    }
        //end editData

    deleteData<?=$objectId;?> = function (){
        var row = $('#dg<?=$objectId;?>').datagrid('getSelected');
        if(row){
            if(confirm("Apakah yakin akan menghapus data '" + row.nomor + "'?")){
                var response = '';
                $.ajax({ type: "GET",
                    url: base_url+'transaksi/tandaterima/delete/' + row.nomor ,
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

   
         setKegiatan<?=$objectId;?> = function (valu){
            //$('#kode_iku_e1<?=$objectId;?>').value = valu;
              $('textarea').autosize();  
        }
        $("#bidang_id<?=$objectId?>").change(function(){
            autoKegiatan<?=$objectId;?>("","")
           
        });
        function autoKegiatan<?=$objectId;?>(key,val){
           
            var tgl = $('#tanggal<?=$objectId;?>').datebox('getValue','<?=date('d-m-Y')?>');
   
            var tahun = parseInt(tgl.split('-')[2]);
            var bidang_id = $("#bidang_id<?=$objectId?>").val();
           
            if (tahun=="") tahun = "-1";
            if ((bidang_id==null)||(bidang_id=="")) bidang_id = "-1";
             $("#divKegiatan<?=$objectId?>").load(
                base_url+"transaksi/tandaterima/getListKegiatan/<?=$objectId;?>/"+tahun+"/"+bidang_id,
                function(){
                    $('textarea').autosize();   

                    $("#txtbeban_kegiatan<?=$objectId;?>").click(function(){
                       // alert('kadie=<?=$objectId;?>') ;
                        $("#drop<?=$objectId;?>").slideDown("slow");
                    });

                    $("#drop<?=$objectId;?> li").click(function(e){
                            var chose = $(this).text();
                            $("#txtbeban_kegiatan<?=$objectId;?>").val(chose);
                            $("#drop<?=$objectId;?>").slideUp("slow");
                    });
            //	alert(val);
//                    if (key!=null)
                            //$('#kode_sasaran_e2ListSasaran<?=$objectId;?>').val(key);
                    if (val!=null)
                            $('#txtbeban_kegiatan<?=$objectId;?>').val(val);
                }
            ); 
            //alert("here");

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
                        <td><input name="periodeawal" id="periodeawal<?=$objectId;?>" class="easyui-datebox" data-options="formatter:myDateFormatter,parser:myDateParser"  > s.d. <input name="periodeakhir" id="periodeakhir<?=$objectId;?>" class="easyui-datebox" data-options="formatter:myDateFormatter,parser:myDateParser"  ></td>
                        <td width="20px">&nbsp;</td>
                        <td>Bidang : &nbsp;</td>
                        <td> <?=$bidanglistFilter?>  </td>
                        
                    </tr>
                    <tr>
                            <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="right" colspan="5" valign="top">
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
		<? if($this->sys_menu_model->cekAkses('ADD;',2,$this->session->userdata('group_id'),$this->session->userdata('level_id'))){?>
			<a href="#" onclick="newData<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-add" plain="true">Tambah</a>  
		<?}?>
		<? if($this->sys_menu_model->cekAkses('EDIT;',2,$this->session->userdata('group_id'),$this->session->userdata('level_id'))){?>
			<a href="#" onclick="editData<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-edit" plain="true">Edit</a>
		<?}?>
		<? if($this->sys_menu_model->cekAkses('DELETE;',2,$this->session->userdata('group_id'),$this->session->userdata('level_id'))){?>
			<a href="#" onclick="deleteData<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-remove" plain="true">Hapus</a>
		<?}?>
		<? if($this->sys_menu_model->cekAkses('PRINT;',2,$this->session->userdata('group_id'),$this->session->userdata('level_id'))){?>
			<a href="#" onclick="printData<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-print" plain="true">Print</a>
		<?}?>
		<? if($this->sys_menu_model->cekAkses('EXCEL;',2,$this->session->userdata('group_id'),$this->session->userdata('level_id'))){?>
			<a href="#" onclick="toExcel<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-excel" plain="true">Excel</a>
		<?}?>
	  </div>
	</div>
	
	<table id="dg<?=$objectId;?>" style="height:auto;width:auto" title="Data Tanda Terima " toolbar="#tb<?=$objectId;?>" 
               fitColumns="true" singleSelect="true" rownumbers="true" pagination="true" noWrap="false" showFooter="true">
	  <thead>
	  <tr>
		<th halign="center" align="left" field="tanggal" sortable="true" width="80">Tanggal</th>
		<th halign="center" align="center" field="nomor" sortable="true" width="200">Nomor</th>
		
		<th halign="center" align="left" field="bidang" sortable="true" width="300">Bidang</th>
		
		<th halign="center" align="left" field="tujuan" sortable="true" width="500">Keterangan</th>
		
		<th halign="center" field="bidang_id" hidden="true"  width="0">bidang_id</th>	
		<th halign="center" field="tanda_id" hidden="true"  width="0">tanda_id</th>
	  </tr>
         
	  </thead> 
	</table>

	