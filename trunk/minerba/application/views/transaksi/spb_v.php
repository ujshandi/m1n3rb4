
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
                    <table border="0" cellpadding="1" cellspacing="4" class="table">				
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
                        <td>No.SPBY : &nbsp;</td>
                        <td><input type="text" size="33px" name="txtNomor" style="padding:7px;font-size:14px" id="txtNomor<?=$objectId;?>" class="easyui-validatebox"/></td>
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
		<? if($this->sys_menu_model->cekAkses('ADD;',21,$this->session->userdata('group_id'),$this->session->userdata('level_id'))){?>
			<a href="#" onclick="newData<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-add" plain="true">Tambah</a>  
		<?}?>
		<? if($this->sys_menu_model->cekAkses('EDIT;',21,$this->session->userdata('group_id'),$this->session->userdata('level_id'))){?>
			<a href="#" onclick="editData<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-edit" plain="true">Edit</a>
		<?}?>
		<? if($this->sys_menu_model->cekAkses('DELETE;',21,$this->session->userdata('group_id'),$this->session->userdata('level_id'))){?>
			<a href="#" onclick="deleteData<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-remove" plain="true">Hapus</a>
		<?}?>
		<? if($this->sys_menu_model->cekAkses('PRINT;',21,$this->session->userdata('group_id'),$this->session->userdata('level_id'))){?>
			<a href="#" onclick="printData<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-print" plain="true">Print</a>
		<?}?>
		<? if($this->sys_menu_model->cekAkses('EXCEL;',21,$this->session->userdata('group_id'),$this->session->userdata('level_id'))){?>
			<a href="#" onclick="toExcel<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-excel" plain="true">Excel</a>
		<?}?>
	  </div>
	</div>
	
	<table id="dg<?=$objectId;?>" style="height:auto;width:auto" title="Data SPBY (Draft)" toolbar="#tb<?=$objectId;?>" 
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
	  <div class="ftitle">Tambah/Edit Data SPBY (Draft)</div>
	  <form id="fm<?=$objectId;?>" method="post">
		 <div class="fitem">
		  <label style="width:150px;vertical-align:top">Tanggal :</label>
		  <input name="tanggal" style="width:100px" id="tanggal<?=$objectId;?>" class="easyui-datebox" data-options="formatter:myDateFormatter,parser:myDateParser"  required="true">
		</div>
         <div class="fitem">
		  <label style="width:150px;vertical-align:top">No.SPBY :</label>
                 <input name="nomor" id="nomor<?=$objectId?>" class="easyui-validatebox"  style="text-transform: uppercase" required="true" >
					&nbsp;
                  <input type="checkbox" name="revisi" id="chkRevisi<?=$objectId?>" value="1"  />&nbsp;Revisi
				  
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
		  <input name="tujuan" id="tujuan<?=$objectId?>"  class="easyui-validatebox" size="30" >
		</div>
		<div class="fitem">
		  <label style="width:150px;vertical-align:top">Tujuan Pembayaran :</label>
		  <input name="untuk" id="untuk<?=$objectId?>"  class="easyui-validatebox" size="50" >
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
		  <input name="jumlah" id="jumlah<?=$objectId?>" class="easyui-numberbox" style="text-align:right" data-options="precision:0,groupSeparator:'.',decimalSeparator:','"  >
		</div>
	  </form>
    </div>
    <div id="dlg-buttons">
	  <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveData<?=$objectId;?>()">Simpan</a>
	  <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg<?=$objectId;?>').dialog('close')">Batal</a>
    </div>

	
	<script  type="text/javascript" >
$(function(){
    var url;
       
    newData<?=$objectId;?> = function (){  
        $('#dlg<?=$objectId;?>').dialog('open').dialog('setTitle','Tambah SPBY (Draft)');  
        $('#fm<?=$objectId;?>').form('clear');  
        $('#tanggal<?=$objectId;?>').datebox('setValue','<?=date('d-m-Y')?>');
        autoKegiatan<?=$objectId;?>('','');       
        $('#nomor<?=$objectId;?>').val('/PPK/<?=date('Y')?>');  
        
        url = base_url+'transaksi/spb/save/add';  
    }
    //end newData 
	
	$("#chkRevisi<?=$objectId?>").click(function(){
		if ($(this).is(':checked')) {
			$('#nomor<?=$objectId;?>').val('');  
			$('#nomor<?=$objectId;?>').bind('blur',loadDataTolak<?=$objectId?>);
		} else {
			$('#nomor<?=$objectId;?>').val('/PPK/<?=date('Y')?>');  
			$('#nomor<?=$objectId;?>').unbind('blur',loadDataTolak<?=$objectId?>);
		}
	})
	
	loadDataTolak<?=$objectId?> = function(){
		var nomor = $('#nomor<?=$objectId;?>').val();  
		var nomor_old = nomor;
		nomor = DoAsciiHex(nomor,'A2H');
		$.ajax({
			url : '<?=base_url()?>transaksi/spb/get_spb_ditolak/'+nomor,
			dataType:'json',
			success : function (data,status,Xhr){
				$('#fm<?=$objectId;?>').form('clear');  
				$('#nomor<?=$objectId;?>').val(nomor_old);  
				$('#chkRevisi<?=$objectId;?>').prop("checked",true);  
				$('#tanggal<?=$objectId;?>').datebox('setValue','<?=date('d-m-Y')?>');
				if (data=="") alert("Data SPBY "+nomor_old+" tidak ditemukan pada daftar SPBY yang pernah ditolak.");
				 $.each(data, function(index, element) {
					//alert(element.nomor);
					
					$("#bidang_id<?=$objectId?>").val(element.bidang_id);
					$("#kategori_id<?=$objectId?>").val(element.kategori_id);
					
					$("#jumlah<?=$objectId?>").numberbox({						
						precision:0,groupSeparator:'.',decimalSeparator:',',
						value :element.jumlah
					}); 
					$("#untuk<?=$objectId?>").val(element.untuk);
					$("#tujuan<?=$objectId?>").val(element.tujuan);
					//$("#bidang_id<?=$objectId?>").change(); 
					autoKegiatan<?=$objectId;?>('','['+element.beban_kode+']'+element.beban_kegiatan);
				//	$('#txtbeban_kegiatan<?=$objectId;?>').val('['+element.beban_kode+']'+element.beban_kegiatan);
					//$("#tujuan<?=$objectId?>").val(element.tujuan);
				});
			}
		});
	}
	


    clearFilter<?=$objectId;?> = function (){
        //ambil nilai-nilai filter
            $("#filter_bidang_id<?=$objectId?>").val('-1');
            $("#filter_kategori_id<?=$objectId?>").val('-1');
            $('#periodeawal<?=$objectId;?>').datebox('setValue','<?=date('01-01-Y')?>');
            $('#periodeakhir<?=$objectId;?>').datebox('setValue','<?=date('d-m-Y')?>');
			$("#txtNomor<?=$objectId?>").val('');
//        $("#filter_alamat").val('');


        //$('#dg<=$objectId;?>').datagrid({url:"<=base_url()?>transaksi/spb/grid/"+filnip+"/"+filnama+"/"+filalamat});
    }

        //tipe 1=grid, 2=pdf, 3=excel
    getUrl<?=$objectId;?> = function (tipe){
        var filawal =  $('#periodeawal<?=$objectId;?>').datebox('getValue');	
        var filakhir = $("#periodeakhir<?=$objectId;?>").datebox('getValue');	
        var filbidang = $("#filter_bidang_id<?=$objectId;?>").val();
        var filkategori = $("#filter_kategori_id<?=$objectId;?>").val();
        var filnomor = $("#txtNomor<?=$objectId;?>").val();
        var tipe_periode = '-1';

        filbidang = ((filbidang=="undefined")||(filbidang=="")||(filbidang==null))?"-1":filbidang;
        filkategori = ((filkategori=="undefined")||(filkategori=="")||(filbidang==null))?"-1":filkategori;
        filnomor = ((filnomor=="undefined")||(filnomor=="")||(filnomor==null))?"-1":filnomor;
        if (tipe==1){
                return "<?=base_url()?>transaksi/spb/grid/<?=$tipeapproval?>/"+filawal+"/"+filakhir+"/"+filbidang+"/"+filkategori+"/"+filnomor+"/"+tipe_periode;
        }
        else if (tipe==2){
                return "<?=base_url()?>transaksi/spb/pdf/<?=$tipeapproval?>/"+filawal+"/"+filakhir+"/"+filbidang+"/"+filkategori+"/"+filnomor+"/"+tipe_periode;
        }else if (tipe==3){
                return "<?=base_url()?>transaksi/spb/excel/<?=$tipeapproval?>/"+filawal+"/"+filakhir+"/"+filbidang+"/"+filkategori+"/"+filnomor+"/"+tipe_periode;
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
                $('#dlg<?=$objectId;?>').dialog('open').dialog('setTitle','Edit SPBY (Draft)');
                $('#fm<?=$objectId;?>').form('load',row);
                autoKegiatan<?=$objectId;?>('',row.kegiatan);
                url = base_url+'transaksi/spb/save/edit/'+row.spb_id;//+row.id;//'update_user.php?id='+row.id;
        }
    }
        //end editData

    deleteData<?=$objectId;?> = function (){
        var row = $('#dg<?=$objectId;?>').datagrid('getSelected');
        if(row){
            if(confirm("Apakah yakin akan menghapus data Draft SPBY dengan nomor '" + row.nomor + "'?")){
                var response = '';
                $.ajax({ type: "GET",
                    url: base_url+'transaksi/spb/delete/' + row.spb_id ,
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
	//	alert("underconstruction");return;
        window.open(getUrl<?=$objectId;?>(2));;
    }

    toExcel<?=$objectId;?>=function(){
		//alert("underconstruction");return;
        window.open(getUrl<?=$objectId;?>(3));;
    }

    saveData<?=$objectId;?>=function(){
        $('#fm<?=$objectId;?>').form('submit',{
            url: url,
            onSubmit: function(){
                var rs = false;				
					if ($("#bidang_id<?=$objectId?>").val()==null) {
						alert("Bidang harus dipilih");
						$("#bidang_id<?=$objectId?>").focus();
					}
					else if ($("#kategori_id<?=$objectId?>").val()==null) {
						alert("Kategori harus dipilih");
						$("#kategori_id<?=$objectId?>").focus();
					}
					else
					  rs = true;
				
				if (rs){
					rs = $(this).form('validate');
				}	
				return rs;
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
                            msg: result.msg,
							showType:'slide',
							style:{
								right:'',
								bottom:''
							}
                    });
                }
            }
        });
    }
        //end saveData
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
                base_url+"transaksi/spb/getListKegiatan/<?=$objectId;?>/"+tahun+"/"+bidang_id,
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