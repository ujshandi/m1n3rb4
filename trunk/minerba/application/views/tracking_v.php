<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Keuangan Minerba - Tracking</title>

    <style type="text/css">
		html {
			background-image: none;
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

    
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>public/css/windows8.css" />
    <!-- END General Styles -->
	
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
     <header>
   Penulusuran Dokumen SPB
 </header>
	<div id="alertMessage" class="error"/><?=$err_msg;?>
        <br></br>
        <center>
	<form id="formLogin" method="post" action="<?=base_url();?>security/login/login_usr">
					<input name="username" type="text"  id="username_id" title="Username"/>
			 		

				</form>
            </center>
            <br></br>
            <div class="dashboard clearfix">
                <ul class="tiles">
                    <div class="col1 clearfix">
                        
                    </div>
                    <div class="col2 clearfix">
                        
                        <br>
                         <li class="tile tile-big tile-6 slideTextLeft" data-page-type="r-page" data-page-name="random-r-page">
                            <div><p>Penulusuran</p></div>
                            <div><p>Telusuri!</p></div>
                          </li>
                     </div>
                </ul>
            </div>    
		
    	
			
				
			
		


	
		
	<!--Login div-->
	
	<div id="versionBar">
		<div class="copyright" > Copyright 2014  All Rights Reserved <span class="tip"><a href="#" style="color:#A31F1A" title="Kemenhub">Kementerian Energi Sumber Daya Dan Mineral</a></span></div>
  <!-- // copyright-->
	</div>

        
        <div class="r-page random-r-page">

    <div class="page-content">
      <h2 class="page-title">App Screen</h2>
      <p>Chew iPad power cord chew iPad power cord attack feet chase mice leave dead animals as gifts and stick butt in face chew iPad power cord. Chase mice. Run in circles use lap as chair why must they do that. Intrigued by the shower destroy couch leave hair everywhere sleep on keyboard chew iPad power cord. Use lap as chair. Missing until dinner time stand in front of the computer screen, intently sniff hand. Find something else more interesting. Destroy couch play time so inspect anything brought into the house hate dog burrow under covers. Sleep on keyboard destroy couch so hate dog so hide when guests come over. Chase mice destroy couch lick butt throwup on your pillow use lap as chair yet intrigued by the shower but climb leg. Stare at ceiling make muffins or hunt anything that moves claw drapes. Intently sniff hand intrigued by the shower. Why must they do that. Cat snacks leave dead animals as gifts or inspect anything brought into the house sweet beast so stare at ceiling give attitude. Flop over claw drapes but sun bathe lick butt, and chase mice. Rub face on everything lick butt leave hair everywhere lick butt, missing until dinner time for use lap as chair lick butt. Make muffins leave dead animals as gifts play time. Chew foot intrigued by the shower stare at ceiling inspect anything brought into the house yet hopped up on goofballs. 



      
      </p>
    </div>
    
    <div class="close-button r-close-button">x</div>
  </div>
        <script src="<?=base_url()?>public/js/jquery-easyui-1.3.3/jquery.min.js"></script>
        <script type="text/javascript">
            $('.tile').on('click',function(){
                $this= $(this),
                page = $this.data('page-name');
                bgcolor = $this.css('background-color');
                textColor = $this.css('color');
                //alert(page);
                $('.'+page).css({'background-color': bgcolor, 'color': textColor})
                     .find('.close-button').css({'background-color': textColor, 'color': bgcolor});
                fadeDashBoard();     
                $('.'+page).addClass('slidePageInFromLeft').removeClass('slidePageBackLeft');     
                $('.'+page).addClass('openpage');
        });
        
        $('.r-close-button').click(function(){
            $(this).parent().addClass('slidePageLeft')
                .one('webkitAnimationEnd oanimationend msAnimationEnd animationend', function(e) {
                      $(this).removeClass('slidePageLeft').removeClass('openpage');
                    });
            showDashBoard();
        });
     
        function showDashBoard(){
            for(var i = 1; i <= 3; i++) {
              $('.col'+i).each(function(){
                  $(this).addClass('fadeInForward-'+i).removeClass('fadeOutback');
              });
            }
          }
        function fadeDashBoard(){
            for(var i = 1; i <= 3; i++) {
              $('.col'+i).addClass('fadeOutback').removeClass('fadeInForward-'+i);
            }
          }
        </script>
</body>

</html>
