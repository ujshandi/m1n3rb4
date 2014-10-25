
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
                    <td><?=form_dropdown('tipe_periode',$tipePeriode,'0','id="tipe_periode'.$objectId.'" ')?></td>
                    <td><input name="periodeawal" style="width:100px" id="periodeawal<?=$objectId;?>" class="easyui-datebox"  data-options="formatter:myDateFormatter,parser:myDateParser"  > s.d. <input name="periodeakhir" style="width:100px" id="periodeakhir<?=$objectId;?>" class="easyui-datebox" data-options="formatter:myDateFormatter,parser:myDateParser"  ></td>
                    <td width="20px">&nbsp;</td>
                    <td>Bidang : &nbsp;</td>
                    <td> <?=$bidanglistFilter?>  </td>
                     <td width="20px">&nbsp;</td>
                    <td>Kategori : &nbsp;</td>
                    <td> <?=$kategorilistFilter?>  </td>
                </tr>
			
					 <tr>
                        <td align="right">No.SPBY : &nbsp;</td>
                        <td><input type="text" size="31px" name="txtNomor" style="padding:7px;font-size:14px" id="txtNomor<?=$objectId;?>" class="easyui-validatebox"/></td>
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
		<? if($this->sys_menu_model->cekAkses('APPROVAL;',(($tipeapproval=="penguji")?24:23),$this->session->userdata('user_id'),$this->session->userdata('level_id'))){?>
			<a href="#" onclick="approveData<?=$objectId;?>('<?=$tipeapproval?>');" class="easyui-linkbutton" iconCls="icon-ok" plain="true">Persetujuan</a>  
		<?}?>
		<? if($this->sys_menu_model->cekAkses('VIEW;',(($tipeapproval=="penguji")?24:23),$this->session->userdata('user_id'),$this->session->userdata('level_id'))){?>
			<a href="#" onclick="editData<?=$objectId;?>(true);" class="easyui-linkbutton" iconCls="icon-view" plain="true">Lihat</a>
		<?}?>
		<? if($this->sys_menu_model->cekAkses('DELETE;',(($tipeapproval=="penguji")?24:23),$this->session->userdata('user_id'),$this->session->userdata('level_id'))){?>
			<a href="#" onclick="deleteData<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-remove" plain="true">Hapus</a>
		<?}?>
		<? if($this->sys_menu_model->cekAkses('PRINT;',(($tipeapproval=="penguji")?24:23),$this->session->userdata('user_id'),$this->session->userdata('level_id'))){?>
			<a href="#" onclick="printData<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-print" plain="true">Print</a>
		<?}?>
		<? if($this->sys_menu_model->cekAkses('EXCEL;',(($tipeapproval=="penguji")?24:23),$this->session->userdata('user_id'),$this->session->userdata('level_id'))){?>
			<a href="#" onclick="toExcel<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-excel" plain="true">Excel</a>
		<?}?>
	  </div>
	</div>
	
	<table id="dg<?=$objectId;?>" style="height:auto;width:auto" title="Data <?=$title;?> " toolbar="#tb<?=$objectId;?>"
               fitColumns="false" singleSelect="true" rownumbers="true" pagination="true"noWrap="false" showFooter="true">
            <thead data-options="frozen:true">
                <tr>
                    <th halign="center" align="left" colspan="2" sortable="true" width="80">Persetujuan</th>
                    <th halign="center" align="center" rowspan="2" field="nomor" sortable="true" width="200">Nomor</th>   
                </tr>
                
                 
                     <tr>
		
		<th halign="center" align="center" field="pejabat_oleh" sortable="true" width="100">Oleh</th>
		<th halign="center" align="center" field="pejabat_tanggal" sortable="true" width="80">Tanggal</th>
                 </tr>
            </thead>
            
          <thead>
              <tr>
		<th halign="center" align="left" rowspan="2" field="tanggal" sortable="true" width="80">Tgl.SPBY</th>
		<th halign="center" align="right" rowspan="2" field="jumlah" formatter="formatMoney" sortable="true" width="100">Jumlah</th>
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
	  </tr>
          <tr>
              <th halign="center"  align="center" field="beban_kode" sortable="true" width="100">Kode</th>
		<th halign="center" align="left" field="beban_kegiatan" sortable="true" width="250">Kegiatan</th>		
	  </tr>
	  </thead> 
	</table>

	 <!-- AREA untuk Form Add/EDIT >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>  -->
	
	<div id="dlg<?=$objectId;?>" class="easyui-dialog" style="width:800px;height:450px;padding:10px 20px" closed="true" buttons="#dlg-buttons">
	  <div class="ftitle">Data SPBY</div>
	  <form id="fm<?=$objectId;?>" method="post">
		 <div class="fitem">                     
		  <label style="width:150px;vertical-align:top">Tanggal :</label>
		  <input name="tanggal" style="width:100px" id="tanggal<?=$objectId;?>" class="easyui-datebox" data-options="formatter:myDateFormatter,parser:myDateParser"  required="true">
                  <input name="spb_id" type="hidden" id="spb_id<?=$objectId;?>" >
                  <input name="keterangan" type="hidden" id="keterangan<?=$objectId;?>" >
		</div>
              <div class="fitem">
		  <label style="width:150px;vertical-align:top">No.SPBY :</label>
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
		  <input name="tujuan" class="easyui-validatebox" size="30" >
		</div>
		<div class="fitem">
		  <label style="width:150px;vertical-align:top">Tujuan Pembayaran :</label>
		  <input name="untuk" class="easyui-validatebox" size="50" >
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
		  <input name="jumlah" class="easyui-numberbox" style="text-align:right" data-options="precision:0,groupSeparator:'.',decimalSeparator:','"  >
		</div>
          <?php if ($tipeapproval=="penguji"){?>
                <div class="fitem">
		  <label style="width:150px;vertical-align:top">Proses Selanjutnya :</label>
                  <select name="spm_bendahara" id="spm_bendahara<?=$objectId?>" class="easyui-validatebox">
                      <option value="spm" >Pengajuan SPM</option>
                      <option value="bendahara" >Bendaharawan</option>
                  </select>
		</div>
          <?php }?>
	  </form>
    </div>
    <div id="dlg-buttons">
	  <a href="#" id="btnApprove<?=$objectId?>" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveData<?=$objectId;?>()">Setuju</a>
          <?php if ($tipeapproval=="verifikasi"){?>
	  <a href="#" id="btnTolak<?=$objectId?>" class="easyui-linkbutton" iconCls="icon-remove" onclick="tolakData<?=$objectId;?>('<?=$tipeapproval?>')">Tolak</a>
          <?php }?>
	  <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg<?=$objectId;?>').dialog('close')">Batal</a>
    </div>

	
	<script  type="text/javascript" >
$(function(){
    var url;
    
       
    

    clearFilter<?=$objectId;?> = function (){
        $("#filter_bidang_id<?=$objectId?>").val('-1');
        $("#filter_kategori_id<?=$objectId?>").val('-1');
        $('#periodeawal<?=$objectId;?>').datebox('setValue','<?=date('01-01-Y')?>');
        $('#periodeakhir<?=$objectId;?>').datebox('setValue','<?=date('d-m-Y')?>');
		$("#txtNomor<?=$objectId?>").val('');
		$("#tipe_periode<?=$objectId?>").val('0');
        //$('#dg<?=$objectId;?>').datagrid({url:"<?=base_url()?>transaksi/spb/grid/"+filnip+"/"+filnama+"/"+filalamat});
    }

        //tipe 1=grid, 2=pdf, 3=excel
    getUrl<?=$objectId;?> = function (tipe){
        var filawal =  $('#periodeawal<?=$objectId;?>').datebox('getValue');	
        var filakhir = $("#periodeakhir<?=$objectId;?>").datebox('getValue');	
        var filbidang = $("#filter_bidang_id<?=$objectId;?>").val();
        var filkategori = $("#filter_kategori_id<?=$objectId;?>").val();
        var filnomor = $("#txtNomor<?=$objectId;?>").val();
		var tipeperiode = $("#tipe_periode<?=$objectId?>").val();
		
         filnomor = ((filnomor=="undefined")||(filnomor=="")||(filnomor==null))?"-1":filnomor;

        filbidang = ((filbidang=="undefined")||(filbidang=="")||(filbidang==null))?"-1":filbidang;
        filkategori = ((filkategori=="undefined")||(filkategori=="")||(filbidang==null))?"-1":filkategori;
        if (tipe==1){
                return "<?=base_url()?>transaksi/spb/grid/<?=$tipeapproval?>/"+filawal+"/"+filakhir+"/"+filbidang+"/"+filkategori+"/"+filnomor+"/"+tipeperiode;
        }
        else if (tipe==2){
                return "<?=base_url()?>transaksi/spb/pdf/<?=$tipeapproval?>/"+filawal+"/"+filakhir+"/"+filbidang+"/"+filkategori+"/"+filnomor+"/"+tipeperiode;
        }else if (tipe==3){
                return "<?=base_url()?>transaksi/spb/excel/<?=$tipeapproval?>/"+filawal+"/"+filakhir+"/"+filbidang+"/"+filkategori+"/"+filnomor+"/"+tipeperiode;
        }

    }

    searchData<?=$objectId;?> = function (){
        //ambil nilai-nilai filter
       

        $('#dg<?=$objectId;?>').datagrid({url:getUrl<?=$objectId;?>(1)});
    }
    //end searhData 
    approveData<?=$objectId;?> = function (tipeapprove){
        var row = $('#dg<?=$objectId;?>').datagrid('getSelected');
        $('#fm<?=$objectId;?>').form('clear');  
        //alert(row.dokter_kode);
        if (row){
                $('#dlg<?=$objectId;?>').dialog('open').dialog('setTitle',(tipeapprove=="verifikasi"?"Verifikasi":"Persetujuan")+' SPBY');
                $('#fm<?=$objectId;?>').form('load',row);
                autoKegiatan<?=$objectId;?>('',row.kegiatan);
                $('#btnApprove<?=$objectId?>').show();
                $('#btnTolak<?=$objectId?>').show();
                url = base_url+'transaksi/spb/approve/'+tipeapprove+'/'+row.spb_id;//+row.id;//'update_user.php?id='+row.id;
        }
    }
    
    editData<?=$objectId;?> = function (viewmode){
        var row = $('#dg<?=$objectId;?>').datagrid('getSelected');
        $('#fm<?=$objectId;?>').form('clear');  
        //alert(row.dokter_kode);
        if (row){
                $('#dlg<?=$objectId;?>').dialog('open').dialog('setTitle','Edit SPBY');
                $('#fm<?=$objectId;?>').form('load',row);
                autoKegiatan<?=$objectId;?>('',row.kegiatan);
                if (viewmode){
                    $('#btnApprove<?=$objectId?>').hide();
                    $('#btnTolak<?=$objectId?>').hide();
                }
                else{
                    $('#btnApprove<?=$objectId?>').show();
                    $('#btnTolak<?=$objectId?>').show();
                }    
                    
                url = base_url+'transaksi/spb/save/edit/'+row.spb_id;//+row.id;//'update_user.php?id='+row.id;
        }
    }
        //end editData

    deleteData<?=$objectId;?> = function (){
        var row = $('#dg<?=$objectId;?>').datagrid('getSelected');
        if(row){
            if(confirm("Apakah yakin akan menghapus data '" + row.nomor + "'?")){
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

     setKegiatan<?=$objectId;?> = function (valu){
            //$('#kode_iku_e1<?=$objectId;?>').value = valu;
              $('textarea').autosize();  
        }
   
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

    printData<?=$objectId;?>=function(){			
        //$.jqURL.loc(getUrl<?=$objectId;?>(2),{w:800,h:600,wintype:"_blank"});
		//alert("underconstruction");return;
        window.open(getUrl<?=$objectId;?>(2));;
    }

    toExcel<?=$objectId;?>=function(){
		//alert("underconstruction");return;
        window.open(getUrl<?=$objectId;?>(3));;
    }
    
    getKeterangan<?=$objectId;?>=function(){
        var keterangan = '';
       $.messager.prompt('Masukkan Alasan Penolakan', 'Alasan : ', function(r){
                        
                        if (r){
                            //alert('you type: '+r);
                            keterangan = r;
                            alert(keterangan);
                        }
                        
                    }); 
        return keterangan;            
        
    }
    
    tolakData<?=$objectId;?>=function(tipeapprove){
       $.messager.prompt('Masukkan Alasan Penolakan', 'Alasan : ', function(r){
                        
            if (r){
                //alert('you type: '+r);
                keterangan = r;
                if (keterangan!=""){
                    $('#fm<?=$objectId;?>').form('submit',{
                        url: base_url+'transaksi/spb/tolak/'+tipeapprove+'/'+$("#spb_id<?=$objectId?>").val(),      
                        onSubmit: function(){
                            $("#keterangan<?=$objectId?>").val(keterangan);
                            var isValid = $(this).form('validate');
                           return isValid;
                        },
                        success: function(result){
                          //  alert(result);
                            var result = eval('('+result+')');
                            if (result.success){
                                $.messager.show({
                                        title: 'Pesan',
                                        msg: 'Data berhasil ditolak'
                                });
								window.open(base_url+"transaksi/spb/print_tolak/"+$("#spb_id<?=$objectId?>").val());
								
                                $('#dlg<?=$objectId;?>').dialog('close');		// close the dialog
                                $('#dg<?=$objectId;?>').datagrid('reload');	// reload the user data
                            } else {
                                $.messager.show({
                                        title: 'Error',
                                        msg: result.msg
                                });
                            }
                        }
                    });//end form submit
                }else{
                    alert("Alasan harus diisi");
                }
            }
            else {
                alert("Alasan harus diisi");
            }            
        }); 
        
    }
    
    saveData<?=$objectId;?>=function(){
        var spm_bendahara= $("#spm_bendahara<?=$objectId?>").val();
        if (spm_bendahara==null) spm_bendahara = '';
        $('#fm<?=$objectId;?>').form('submit',{
            url: url+'/'+spm_bendahara,
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
                            msg: 'Data berhasil diapprove'
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
            clearFilter<?=$objectId?>();
            $("#dg<?=$objectId;?>").css('height',wHeight-156);    
            searchData<?=$objectId?>();
        },100);
 });
</script>