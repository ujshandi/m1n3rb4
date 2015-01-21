<script  type="text/javascript" >
$(function(){
    var url;
    var idTanda<?=$objectId;?>;
    var rowIndexDetail;
       

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
			$("#txtNomor<?=$objectId?>").val('');
			$("#txtNomorTerima<?=$objectId?>").val('');
//        $("#filter_alamat").val('');


        //$('#dg<=$objectId;?>').datagrid({url:"<=base_url()?>transaksi/tandaterima/grid/"+filnip+"/"+filnama+"/"+filalamat});
    }

        //tipe 1=grid, 2=pdf, 3=excel
    getUrl<?=$objectId;?> = function (tipe){
        var filawal =  $('#periodeawal<?=$objectId;?>').datebox('getValue');	
        var filakhir = $("#periodeakhir<?=$objectId;?>").datebox('getValue');	
        var filbidang = $("#filter_bidang_id<?=$objectId;?>").val();
		var filnomor = $("#txtNomor<?=$objectId;?>").val();
		var filnomorTerima = $("#txtNomorTerima<?=$objectId;?>").val();
//        var filkategori = $("#filter_kategori_id<?=$objectId;?>").val();
        

        filbidang = ((filbidang=="undefined")||(filbidang=="")||(filbidang==null))?"-1":filbidang;
        filnomor = ((filnomor=="undefined")||(filnomor=="")||(filnomor==null))?"-1":filnomor;
        filnomorTerima = ((filnomorTerima=="undefined")||(filnomorTerima=="")||(filnomorTerima==null))?"-1":filnomorTerima;
  //      filkategori = ((filkategori=="undefined")||(filkategori=="")||(filbidang==null))?"-1":filkategori;
        if (tipe==1){
                return "<?=base_url()?>transaksi/tandaterima/grid/<?=$tipetandaterima?>/"+filawal+"/"+filakhir+"/"+filbidang+"/"+filnomor+"/"+filnomorTerima;
        }
        else if (tipe==2){
                return "<?=base_url()?>transaksi/tandaterima/pdf/<?=$tipetandaterima?>/"+filawal+"/"+filakhir+"/"+filbidang+"/"+filnomor+"/"+filnomorTerima;
        }else if (tipe==3){
                return "<?=base_url()?>transaksi/tandaterima/excel/<?=$tipetandaterima?>/"+filawal+"/"+filakhir+"/"+filbidang+"/"+filnomor+"/"+filnomorTerima;
        }

    }

    searchData<?=$objectId;?> = function (){
        //ambil nilai-nilai filter
        $('#dg<?=$objectId;?>').datagrid({
            url:getUrl<?=$objectId;?>(1),
            view: detailview,
            detailFormatter:function(index,row){
               return '<div style="padding:2px"><table id="ddv<?=$objectId;?>-' + index + '"></table></div>';
           //  return "tes";
           },
           onExpandRow: function(index,row){
                           //	alert(row.id_pk_e1);

               $('#ddv<?=$objectId;?>-'+index).datagrid({
                   url:'<?=base_url()?>transaksi/tandaterima/griddetail/'+row.tanda_id+'/?parentIndex='+index,
                   fitColumns:true,
                   singleSelect:true,
                   rownumbers:true,
                   loadMsg:'',
                   height:'auto',
                   columns:[[
                       {field:'detail_id',title:'detail_id',hidden:true},
                       {field:'tanda_id',title:'tanda_id',hidden:true},
                       {field:'spb_id',title:'spb_id',hidden:true},
                       {field:'tanggal',title:'Tanggal',width:80},
                       {field:'nomor',title:'Nomor SPB',width:200},
                       {field:'jumlah',title:'Jumlah',width:100,align:'right'},
                       {field:'kategori',title:'Kategori',width:100},
                       {field:'untuk',title:'Untuk Pembayaran',width:200}
                       
                   ]],
                   onResize:function(){
                       $('#dg<?=$objectId;?>').datagrid('fixDetailRowHeight',index);
                   },
                  onClickCell:function(rowIndex, field, value){
                        $('#ddv<?=$objectId;?>-'+index).datagrid('selectRow', rowIndex);
                       var row = $('#ddv<?=$objectId;?>-'+index).datagrid('getSelected');
                       idTanda<?=$objectId;?> = row.tanda_id;
                       rowIndexDetail = index;
                                                   //alert(idTanda);
                    },
                   onLoadSuccess:function(){
                       setTimeout(function(){
                           $('#dg<?=$objectId;?>').datagrid('fixDetailRowHeight',index);
                       },0);
                   }
               });
               $('#dg<?=$objectId;?>').datagrid('fixDetailRowHeight',index);


           }
        });
    }
    //end searhData 

    editData<?=$objectId;?> = function (){
        var row = $('#dg<?=$objectId;?>').datagrid('getSelected');
        $('#fm<?=$objectId;?>').form('clear');  
        //alert(row.dokter_kode);
        if (row){
           //     $('#dlg<?=$objectId;?>').dialog('open').dialog('setTitle','Edit Tanda Terima SPBY');
             //   $('#fm<?=$objectId;?>').form('load',row);
              addTab("Edit Tanda Terima","transaksi/tandaterima/edit/"+row.tanda_id); 
            // url = base_url+'transaksi/tandaterima/save/edit/'+row.tanda_id;//+row.id;//'update_user.php?id='+row.id;
        }
    }
        //end editData

    deleteData<?=$objectId;?> = function (){
        var row = $('#dg<?=$objectId;?>').datagrid('getSelected');
        if(row){
            if(confirm("Apakah yakin akan menghapus data Tanda Terima dengan nomor '" + row.nomor + "'?")){
                var response = '';
                $.ajax({ type: "GET",
                    url: base_url+'transaksi/tandaterima/delete/' + row.tanda_id ,
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
		//alert("underconstruction");return;
        window.open(getUrl<?=$objectId;?>(2));;
    }

    toExcel<?=$objectId;?>=function(){
		//alert("underconstruction");return;
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
                        <td><input name="periodeawal" id="periodeawal<?=$objectId;?>" class="easyui-datebox" style="width:100px" data-options="formatter:myDateFormatter,parser:myDateParser"  > s.d. <input style="width:100px" name="periodeakhir" id="periodeakhir<?=$objectId;?>" class="easyui-datebox" data-options="formatter:myDateFormatter,parser:myDateParser"  ></td>
                        <td width="20px">&nbsp;</td>
                        <td>Bidang : &nbsp;</td>
                        <td> <?=$bidanglistFilter?>  </td>
                        
                    </tr>
					
					 <tr>
                        <td>No.SPBY : &nbsp;</td>
                        <td><input type="text" size="33px" name="txtNomor" style="padding:7px;font-size:14px" id="txtNomor<?=$objectId;?>" class="easyui-validatebox"/></td> 
						<td width="20px">&nbsp;</td>
						<td>No.Tanda Terima : &nbsp;</td>
                        <td><input type="text" size="33px" name="txtNomorTerima" style="padding:7px;font-size:14px" id="txtNomorTerima<?=$objectId;?>" class="easyui-validatebox"/></td>
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
		<? if($this->sys_menu_model->cekAkses('ADD;',22,$this->session->userdata('group_id'),$this->session->userdata('level_id'))){?>
			<a href="#" onclick="newData<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-add" plain="true">Tambah</a>  
		<?}?>
		<? if($this->sys_menu_model->cekAkses('EDIT;',22,$this->session->userdata('group_id'),$this->session->userdata('level_id'))){?>
			<a href="#" onclick="editData<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-edit" plain="true">Edit</a>
		<?}?>
		<? if($this->sys_menu_model->cekAkses('DELETE;',22,$this->session->userdata('group_id'),$this->session->userdata('level_id'))){?>
			<a href="#" onclick="deleteData<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-remove" plain="true">Hapus</a>
		<?}?>
		<? if($this->sys_menu_model->cekAkses('PRINT;',22,$this->session->userdata('group_id'),$this->session->userdata('level_id'))){?>
			<a href="#" onclick="printData<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-print" plain="true">Print</a>
		<?}?>
		<? if($this->sys_menu_model->cekAkses('EXCEL;',22,$this->session->userdata('group_id'),$this->session->userdata('level_id'))){?>
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
		
		<th halign="center" align="left" field="keterangan" sortable="true" width="500">Keterangan</th>
		
		<th halign="center" field="bidang_id" hidden="true"  width="0">bidang_id</th>	
		<th halign="center" field="tanda_id" hidden="true"  width="0">tanda_id</th>
	  </tr>
         
	  </thead> 
	</table>

	