<?php

class Spb extends CI_Controller {

    function __construct()
    {
        parent::__construct();			

        //	$userdata = array ('userLogin' => $userLogin,'logged_in' => TRUE,'groupId'=>$this->sys_login_model->groupId,'fullName'=>$this->sys_login_model->fullName,'userId'=>$this->sys_login_model->userId,'groupLevel'=>$this->sys_login_model->level);

        $this->load->model('/security/sys_menu_model');
        $this->load->model('/transaksi/spb_model');
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
        $this->load->view('transaksi/spb_v',$data);
    }
    
    function approval($tipeapproval){
        $data['tipeapproval'] = $tipeapproval;	
        switch ($tipeapproval){
            case "verifikasi" : $data['title'] = 'SPB Yang Akan di Verifikasi';break;
            case "penguji" : $data['title'] = 'SPB Yang Akan di Periksa oleh Pejabat Penguji';break;
            case "spm" : $data['title'] = 'SPB Yang Akan dibuat Pengajuan SPM';break;
            case "bendahara" : $data['title'] = 'SPB Yang Akan ditindaklanjuti Bendaharawan';break;
            default : $data['title'] = 'SPB Approval';		
        }
        
        $data['objectId'] = 'SPBAPP'.$tipeapproval;
        $data['bidanglist'] = $this->bidang_model->getListBidang($data['objectId']);
        $data['bidanglistFilter'] = $this->bidang_model->getListBidangFilter($data['objectId']);
        $data['kategorilist'] = $this->spb_kategori_model->getListKategori($data['objectId']);
        $data['kategorilistFilter'] = $this->spb_kategori_model->getListKategoriFilter($data['objectId']);
        $this->load->view('transaksi/spb_approval_v',$data);
    }

    function grid($tipeapproval,$periodeawal,$periodeakhir,$bidang,$kategori){	
        $periodeawal = $this->utility->ourDeFormatSQLDate($periodeawal);
	$periodeakhir = $this->utility->ourDeFormatSQLDate($periodeakhir);
        echo $this->spb_model->easyGrid(1,$tipeapproval,$periodeawal,$periodeakhir,$bidang,$kategori);
    }
    
    function getListKegiatan($objectId,$tahun,$bidang){
		//$data = array('tahun' => $tahun,'kode' => "", 'deskripsi' => '');
        echo $this->kegiatan_model->getListKegiatan($objectId,$tahun,$bidang);
    }

    private function get_form_values() {
            // XXS Filtering enforced for user input
        $data['nomor'] = $this->input->post("nomor", TRUE);
        $data['tanggal'] = $this->input->post("tanggal", TRUE);
        $data['tujuan'] = $this->input->post("tujuan", TRUE);
        $data['untuk'] = $this->input->post("untuk", TRUE);
        $kegiatan  = $this->input->post("txtbeban_kegiatan", TRUE);
        
        //var_dump( $this->utility->ourEkstrakString($kegiatan,']',1));die;
        $data['beban_kegiatan'] = $this->utility->ourEkstrakString($kegiatan,']',1);
        //var_dump($data['beban_kegiatan']);die;
        $data['beban_kode'] = str_replace('[', '', $this->utility->ourEkstrakString($kegiatan,']',0)) ;
        $data['jumlah'] = $this->input->post("jumlah", TRUE);
        //var_dump($data['jumlah']);die;
        $data['bidang_id'] = $this->input->post("bidang_id", TRUE);
        $data['kategori_id'] = $this->input->post("kategori_id", TRUE);
        return $data;
    }

    function approve($tipeapprove,$id){
         if($id != ''){          
            switch ($tipeapprove) {
                case 'verifikasi' :$result = $this->spb_model->approveVerifikasi($id);break;
                case 'penguji' :$result = $this->spb_model->approvePenguji($id);break;
                case 'spm' :$result = $this->spb_model->approveSpm($id);break;
                case 'bendahara' :$result = $this->spb_model->approveBendahara($id);break;
             }    
          
            if ($result){
                    echo json_encode(array('success'=>true, 'haha'=>''));
            } else {
                    echo json_encode(array('msg'=>'Data tidak bisa diapprove.', 'data'=> ''));
            }
        }
    }
    
    function tolak($tipeapprove,$id){
         if($id != ''){   
            $data['keterangan'] = $this->input->post("keterangan", TRUE);
            switch ($tipeapprove) {
                case 'verifikasi' :$result = $this->spb_model->tolakVerifikasi($id,$data);break;
                case 'penguji' :$result = $this->spb_model->tolakPenguji($id);break;
                case 'spm' :$result = $this->spb_model->tolakSpm($id);break;
                case 'bendahara' :$result = $this->spb_model->tolakBendahara($id);break;
             }    
          
            if ($result){
                    echo json_encode(array('success'=>true, 'haha'=>''));
            } else {
                    echo json_encode(array('msg'=>'Data tidak bisa ditolak.', 'data'=> ''));
            }
        }
    }
    
    function save($aksi="", $kode=""){
        $this->load->library('form_validation');
        $data = $this->get_form_values();
        $status = "";
        $result = false;

        //validasi form
        $this->form_validation->set_rules("nomor", 'Nomor SPB', 'trim|required|xss_clean');
        $this->form_validation->set_rules("tujuan", 'Dibayarkan kepada', 'trim|required|xss_clean');
        $this->form_validation->set_rules("untuk", 'Tujuan Pembayaran', 'trim|required|xss_clean');
        //$this->form_validation->set_rules("jumlah", 'Jumlah Uang', 'trim|required|xss_clean');

        $data['pesan_error'] = '';
        if ($this->form_validation->run() == FALSE){
                //jika data tidak valid kembali ke view
                $data["pesan_error"].=(trim(form_error("nomor"," "," "))==""?"":form_error("nomor"," "," ")."<br/>");
                $data["pesan_error"].=(trim(form_error("tujuan"," "," "))==""?"":form_error("tujuan"," "," ")."<br/>");
                $data["pesan_error"].=(trim(form_error("untuk"," "," "))==""?"":form_error("untuk"," "," ")."<br/>");
                //$data["pesan_error"].=(trim(form_error("jumlah"," "," "))==""?"":form_error("jumlah"," "," ")."<br/>");
                $status = $data["pesan_error"];
        }else {
            if($aksi=="add"){ // add
                if (!$this->spb_model->isExistKode($data['nomor'])){
                        $result = $this->spb_model->InsertOnDb($data,$status);
                }
                else
                        $data['pesan_error'] .= 'Nomor sudah ada';

            }else { // edit
                $result=$this->spb_model->UpdateOnDb($data,$kode);
                $data['pesan_error'] .= 'Update : '.$kode;
            }
            $data['pesan_error'] .= $status;	
        }

        if ($result){
                echo json_encode(array('success'=>true));
        } else {
                echo json_encode(array('msg'=>$data['pesan_error']));
        }
//		echo $status;

    }

    function delete($id=''){
        if($id != ''){
            if ($this->spb_model->isSaveDelete($id))
                    $result = $this->spb_model->DeleteOnDb($id);
            else
                    $result = false;
            if ($result){
                    echo json_encode(array('success'=>true, 'haha'=>''));
            } else {
                    echo json_encode(array('msg'=>'Data tidak bisa dihapus karena sudah digunakan sebagai referensi data lainnya.', 'data'=> ''));
            }
        }
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