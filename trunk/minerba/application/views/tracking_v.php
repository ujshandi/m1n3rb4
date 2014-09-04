<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Keuangan Minerba - Tracking</title>

    <style type="text/css">
		html {
		  height: 100%;
		  overflow-y: scroll;
		  overflow-x: hidden
		}

		body {
		  width: 100%;
		  height: 100%;
		  line-height: 1.5;
		  font-family: 'Lato', sans-serif;
		  font-weight: 300;
		  font-size: 16px;
		  background-color: #eee;
		  /*background: url(../images/1.png) left top no-repeat;*/
		  /*background: url(../images/footer-bg.png) repeat;*/
		  background-size: 100% 100%
		}

		

		#versionBar {
			background-color:#ffffff;
			position:fixed;
			width:100%;
			height:35px;
			bottom:0;
			left:0;
			text-align:center;
			line-height:35px;
			z-index:11;
		}

		.copyright{
			text-align:center; font-size:10px; color:#A31F1A;
		}
		.copyright a{
			color:#A31F1A; text-decoration:none
		}
	</style>

    
    
    <!-- END General Styles -->
	<style type="text/css">
		ol.progtrckr {
			margin: 0;
			padding: 0;
			list-style-type none;
		}
		ol.progtrckr li {
			display: inline-block;
			text-align: center;
			line-height: 3em;
		}
		
		ol.progtrckr[data-progtrckr-steps="2"] li { width: 49%; }
		ol.progtrckr[data-progtrckr-steps="3"] li { width: 33%; }
		ol.progtrckr[data-progtrckr-steps="4"] li { width: 24%; }
		ol.progtrckr[data-progtrckr-steps="5"] li { width: 19%; }
		ol.progtrckr[data-progtrckr-steps="6"] li { width: 16%; }
		ol.progtrckr[data-progtrckr-steps="7"] li { width: 14%; }
		ol.progtrckr[data-progtrckr-steps="8"] li { width: 12%; }
		ol.progtrckr[data-progtrckr-steps="9"] li { width: 11%; }
		
		ol.progtrckr li.progtrckr-done {
			color: black;
			border-bottom: 4px solid yellowgreen;
		}
		ol.progtrckr li.progtrckr-todo {
			color: silver; 
			border-bottom: 4px solid silver;
		}
		
		ol.progtrckr li:after {
			content: "\00a0\00a0";
		}
		ol.progtrckr li:before {
			position: relative;
			bottom: -2.5em;
			float: left;
			left: 50%;
			line-height: 1em;
		}
		ol.progtrckr li.progtrckr-done:before {
			content: "\2713";
			color: white;
			background-color: yellowgreen;
			height: 1.2em;
			width: 1.2em;
			line-height: 1.2em;
			border: none;
			border-radius: 1.2em;
		}
		ol.progtrckr li.progtrckr-todo:before {
			content: "\039F";
			color: silver;
			background-color: #eee;
			font-size: 1.5em;
			bottom: -1.6em;
		}
		
		#nospb_id<?=$objectId?> {
			font-size: 25px;
			background-repeat:no-repeat;
		
			text-align:center;
			margin:auto;	
			height: 35px;
			background-position: 2px 3px;
		
			line-height: 35px;
			color: #999;
			width: 30%;
			margin-bottom: 10px;
			font-family:"Myriad Pro", Arial, Helvetica, sans-serif;
		}
		
		
		ol.progtrckr2 {
			margin-top: -45px;
			padding: 0;
			list-style-type none;
		}
		ol.progtrckr2 li {
			display: inline-block;
			text-align: center;
			line-height: 3em;
		}
		
		ol.progtrckr2[data-progtrckr-steps="2"] li { width: 49%; }
		ol.progtrckr2[data-progtrckr-steps="3"] li { width: 33%; }
		ol.progtrckr2[data-progtrckr-steps="4"] li { width: 24%; }
		ol.progtrckr2[data-progtrckr-steps="5"] li { width: 19%; }
		ol.progtrckr2[data-progtrckr-steps="6"] li { width: 16%; }
		ol.progtrckr2[data-progtrckr-steps="7"] li { width: 14%; }
		ol.progtrckr2[data-progtrckr-steps="8"] li { width: 12%; }
		ol.progtrckr2[data-progtrckr-steps="9"] li { width: 11%; }
	</style>
	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>public/admin/css/login.css" />
	
	<script type="text/javascript">var  base_url = "<?php echo base_url(); ?>"</script>
	
	<script>
	function runScript(e) {
		if (e.keyCode == 13) {
			document.getElementById('formLogin').submit();
		}
	}
	</script>
</head>

<body>	
     <header style="text-align:center;color:white;background-color:red">
   Penulusuran Dokumen SPB
 </header>
	
	
	 <br></br>
	
		
	 <div style="text-align:center">
	<!-- <form id="formLogin" method="post" action="<=base_url();?>tracking/go_tracking"> -->
					No.SPB : 
					<input name="nospb" type="text"  id="nospb_id<?=$objectId?>" title="Nomor SPB"/>
			 		
					
								
								<a class="uibutton normal" style="display: inline-block;padding:7px 12px 2px;font-size:19px" href="javascript:cari<?=$objectId?>();" id="but_login" >Cari</a>
					
								<div id="alertMessage<?=$objectId?>" class="error"/><?=$err_msg;?></div>
			<!--	</form> -->
				</div>
	
        <br></br>
    <center>    
	<div id="divProgress<?=$objectId?>">
		<ol class="progtrckr" data-progtrckr-steps="5">
			<li class="progtrckr-todo">Draft</li>
			<li class="progtrckr-todo">Tanda Terima</li> <!--done -->
			<li class="progtrckr-todo">Verifikasi</li>
			<li class="progtrckr-todo">Pejabat Penguji</li>
			<li class="progtrckr-todo">Pengajuan SPM/Bendaharawan</li>
		</ol>
	</div>
    </center>        
            
	<div id="versionBar">
		<div class="copyright" > Copyright 2014  All Rights Reserved <span class="tip"><a href="#" style="color:#A31F1A" title="Kemenhub">Kementerian Energi Sumber Daya Dan Mineral</a></span></div>
  <!-- // copyright-->
	</div>

        
        
        <script src="<?=base_url()?>public/js/jquery-easyui-1.3.3/jquery.min.js"></script>
        <script src="<?=base_url()?>public/js/uri_encode_decode.js"></script>
        <script type="text/javascript">
            
		  
		  $(window).load(function(){
			$("ol.progtrckr").each(function(){
				$(this).attr("data-progtrckr-steps", 
							 $(this).children("li").length);
			});
		})
		
		cari<?=$objectId?> = function(){
			var nospb =  $('#nospb_id<?=$objectId;?>').val();
            if (nospb.length==0) {
				alert('No SPB harus diisi');
				return;
			}
			nospb = DoAsciiHex(nospb,'A2H');
            $.ajax({url:base_url+"tracking/go_tracking/"+nospb,
                success : function(result){
					
					var result = eval('('+result+')');
					//alert(result.success);
					if (result.success){
						$("#divProgress<?=$objectId?>").html(result.data);
						$("#alertMessage<?=$objectId?>").html("");
					}
					else {
						$("#divProgress<?=$objectId?>").html(result.data);
						$("#alertMessage<?=$objectId?>").html(result.msg);
					}
					
                }					
            })
		}
        </script>
</body>

</html>
