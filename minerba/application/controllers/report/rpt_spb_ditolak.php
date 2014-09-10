<?php

class Rpt_spb_ditolak extends CI_Controller {

    function __construct()
    {
        parent::__construct();			

        //	$userdata = array ('userLogin' => $userLogin,'logged_in' => TRUE,'groupId'=>$this->sys_login_model->groupId,'fullName'=>$this->sys_login_model->fullName,'userId'=>$this->sys_login_model->userId,'groupLevel'=>$this->sys_login_model->level);

        $this->load->model('/security/sys_menu_model');
        $this->load->model('/transaksi/spb_model');
        $this->load->model('/report/rpt_spb_ditolak_model');
        $this->load->model('/rujukan/spb_kategori_model');
        $this->load->model('/rujukan/bidang_model');
        $this->load->model('/rujukan/kegiatan_model');
        $this->load->model('/admin/user_model');
        $this->load->library("utility");

    }

    function index(){
        $data['title'] = 'SPB';		
        $data['objectId'] = 'SPBDitolak';
        $data['tipeapproval'] = 'draft';
        $data['bidanglist'] = $this->bidang_model->getListBidang($data['objectId']);
        $data['bidanglistFilter'] = $this->bidang_model->getListBidangFilter($data['objectId']);
        $data['kategorilist'] = $this->spb_kategori_model->getListKategori($data['objectId']);
        $data['kategorilistFilter'] = $this->spb_kategori_model->getListKategoriFilter($data['objectId']);
        $this->load->view('report/rpt_spb_ditolak_v',$data);
    }
    
    

    function grid($tipeapproval,$periodeawal,$periodeakhir,$bidang,$kategori,$nomor){	
        $periodeawal = $this->utility->ourDeFormatSQLDate($periodeawal);
	$periodeakhir = $this->utility->ourDeFormatSQLDate($periodeakhir);
        echo $this->rpt_spb_ditolak_model->easyGrid(1,$tipeapproval,$periodeawal,$periodeakhir,$bidang,$kategori,$nomor);
    }
 
    
    
    public function pdf(){
        $this->load->library('cezpdf');	
        $pdfdata = $this->spb_model->easyGrid(2);
        if (count($pdfdata)==0){
                echo "Data Tidak Tersedia";
                return;
        }
        //$pdfdata = $pdfdata->rows;
        $pdfhead = array('No.','Kode','Nama Kementerian','Singkatan','Nama Menteri');
        $pdf = new $this->cezpdf($paper='A4',$orientation='potrait');
        $pdf->ezSetCmMargins(1,1,1,1);
        $pdf->selectFont( APPPATH."libraries/fonts/Helvetica.afm" );
//	$pdf->ezText('Biroren Kemenhub',8,array('left'=>'1'));
        $pdf->ezText('Unit Kerja Kementerian',12,array('left'=>'1'));
//	if (($filtahun != null)&&($filtahun != "-1"))
        //$pdf->ezText('Tahun 2012',12,array('left'=>'1'));
//	$pdf->ezText('Tahun 2012',12,array('left'=>'1'));
        // if (($file1 != null)&&($file1 != "-1"))
                // $pdf->ezText($this->eselon1_model->getNamaE1($file1),12,array('left'=>'1'));
        $pdf->ezText('');
        //halaman 
        $pdf->ezStartPageNumbers(550,10,8,'right','',1);

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
                         1=>array('width'=>50),
                         2=>array('width'=>225),
                         3=>array('width'=>100),
                         4=>array('width'=>125)),
                'width'=>'520'
        );
        $pdf->ezTable( $pdfdata, $pdfhead, NULL, $options );
        $opt['Content-Disposition'] = "Kementerian.pdf";
        $pdf->ezStream($opt);
    }

    public function excel(){
            echo  $this->spb_model->easyGrid(3);
    }

}
?>