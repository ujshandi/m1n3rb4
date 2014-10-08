<?php

class Rpt_spm_bendahara extends CI_Controller {

    function __construct()
    {
        parent::__construct();			

        //	$userdata = array ('userLogin' => $userLogin,'logged_in' => TRUE,'groupId'=>$this->sys_login_model->groupId,'fullName'=>$this->sys_login_model->fullName,'userId'=>$this->sys_login_model->userId,'groupLevel'=>$this->sys_login_model->level);

        $this->load->model('/security/sys_menu_model');
        $this->load->model('/transaksi/spb_model');
        $this->load->model('/report/rpt_spm_bendahara_model');
        $this->load->model('/rujukan/spb_kategori_model');
        $this->load->model('/rujukan/bidang_model');
        $this->load->model('/rujukan/kegiatan_model');
        $this->load->model('/admin/user_model');
        $this->load->library("utility");

    }

    function index(){
        $data['title'] = 'SPB';		
        $data['objectId'] = 'SPB';
        $data['tipeapproval'] = 'draft';
        $data['bidanglist'] = $this->bidang_model->getListBidang($data['objectId']);
        $data['bidanglistFilter'] = $this->bidang_model->getListBidangFilter($data['objectId']);
        $data['kategorilist'] = $this->spb_kategori_model->getListKategori($data['objectId']);
        $data['kategorilistFilter'] = $this->spb_kategori_model->getListKategoriFilter($data['objectId']);
		$data['tipePeriode'] = $this->spb_model->get_filter_periode_type();
        $this->load->view('report/rpt_spm_bendahara_v',$data);
    }
    
    function bendahara(){
        $data['title'] = 'Bendaharawan';		
        $data['objectId'] = 'bendaharawan';
        $data['tipereport'] = 'bendahara';
        $data['bidanglist'] = $this->bidang_model->getListBidang($data['objectId']);
        $data['bidanglistFilter'] = $this->bidang_model->getListBidangFilter($data['objectId']);
        $data['kategorilist'] = $this->spb_kategori_model->getListKategori($data['objectId']);
        $data['kategorilistFilter'] = $this->spb_kategori_model->getListKategoriFilter($data['objectId']);
		$data['tipePeriode'] = $this->spb_model->get_filter_periode_type();
        $this->load->view('report/rpt_spm_bendahara_v',$data);
    }
    
    function spm(){
        $data['title'] = 'SPB';		
        $data['objectId'] = 'spm';
        $data['tipereport'] = 'spm';
        $data['bidanglist'] = $this->bidang_model->getListBidang($data['objectId']);
        $data['bidanglistFilter'] = $this->bidang_model->getListBidangFilter($data['objectId']);
        $data['kategorilist'] = $this->spb_kategori_model->getListKategori($data['objectId']);
        $data['kategorilistFilter'] = $this->spb_kategori_model->getListKategoriFilter($data['objectId']);
		$data['tipePeriode'] = $this->spb_model->get_filter_periode_type();
        $this->load->view('report/rpt_spm_bendahara_v',$data);
    }
    
    

    function grid($tipereport,$periodeawal,$periodeakhir,$bidang,$kategori,$nomor,$tipeperiode){	
        $periodeawal = $this->utility->ourDeFormatSQLDate($periodeawal);
	$periodeakhir = $this->utility->ourDeFormatSQLDate($periodeakhir);
        echo $this->rpt_spm_bendahara_model->easyGrid(1,$tipereport,$periodeawal,$periodeakhir,$bidang,$kategori,$nomor,$tipeperiode);
    }
 
    
    
    public function pdf($tipereport,$periode_awal,$periode_akhir,$bidang,$kategori,$nomor,$tipeperiode){
        $this->load->library('cezpdf');	
		 $periodeawal = $this->utility->ourDeFormatSQLDate($periode_awal);
		$periodeakhir = $this->utility->ourDeFormatSQLDate($periode_akhir);
        
        $pdfdata = $this->rpt_spm_bendahara_model->easyGrid(2,$tipereport,$periodeawal,$periodeakhir,$bidang,$kategori,$nomor,$tipeperiode);
        if (count($pdfdata)==0){
                echo "Data Tidak Tersedia";
                return;
        }

        $pdfhead = array('No.','Tgl.Input','Tgl.Verifikasi','Tgl.Persetujuan','Nomor','Jumlah','Bidang','Kategori','Untuk Pembayaran','Kepada','Kegiatan');
        $pdf = new $this->cezpdf($paper='LEGAL',$orientation='landscape');
        $pdf->ezSetCmMargins(1,1,1,1);
        $pdf->selectFont( APPPATH."libraries/fonts/Helvetica.afm" );

		switch ($tipereport){
			case "spm" : $purposeTitle = "Untuk Pengajuan SPM "; break;
			
			case "bendahara" : $purposeTitle =  "Untuk Bendaharawan"; break;
			default :$purposeTitle = "";
		}
        $pdf->ezText('Daftar SPBY '.$purposeTitle,12,array('left'=>'1'));
        $pdf->ezText('Periode : '.$periode_awal." s.d. ".$periode_akhir,12,array('left'=>'1'));

        $pdf->ezText('');
        //halaman 
        $pdf->ezStartPageNumbers(650,10,8,'right','Tgl.Cetak '.date('d-m-Y H:n:s').'  Hal. {PAGENUM} dari {TOTALPAGENUM}',1);
		
        $options = array(
                'showLines' => 2,
                'showHeadings' => 1,
                'fontSize' => 8,
                'rowGap' => 1,
                'shaded' => 0,
                'colGap' => 5,
                'xPos' => 40,
                'xOrientation' => 'right',
                        'cols'=>array(
                         0=>array('justification'=>'center','width'=>25),
                         1=>array('width'=>60),
                         2=>array('width'=>60),
                         3=>array('width'=>70),
                         4=>array('width'=>80),
                         5=>array('width'=>65,'justification'=>'right'),
                         6=>array('width'=>130),
                         7=>array('width'=>60),
                         8=>array('width'=>100),
                         9=>array('width'=>100),
                         10=>array('width'=>175)),
                'width'=>'520'
        );
        $pdf->ezTable( $pdfdata, $pdfhead, NULL, $options );
		
        $opt['Content-Disposition'] = "SPBY.pdf";
        $pdf->ezStream($opt);
    }

    public function excel($tipereport,$periode_awal,$periode_akhir,$bidang,$kategori,$nomor,$tipeperiode){
		$periodeawal = $this->utility->ourDeFormatSQLDate($periode_awal);
		$periodeakhir = $this->utility->ourDeFormatSQLDate($periode_akhir);	
		echo  $this->rpt_spm_bendahara_model->easyGrid(3,$tipereport,$periodeawal,$periodeakhir,$bidang,$kategori,$nomor,$tipeperiode);
    }

}
?>