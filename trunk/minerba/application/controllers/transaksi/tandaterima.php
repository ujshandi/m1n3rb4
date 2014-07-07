<?php

class Tandaterima extends CI_Controller {

    function __construct()
    {
        parent::__construct();			

        //	$userdata = array ('userLogin' => $userLogin,'logged_in' => TRUE,'groupId'=>$this->sys_login_model->groupId,'fullName'=>$this->sys_login_model->fullName,'userId'=>$this->sys_login_model->userId,'groupLevel'=>$this->sys_login_model->level);

        $this->load->model('/security/sys_menu_model');
        $this->load->model('/transaksi/tandaterima_model');
        $this->load->model('/rujukan/bidang_model');
        $this->load->model('/rujukan/kegiatan_model');
        $this->load->model('/rujukan/tandaterima_model');
        $this->load->model('/admin/user_model');
        $this->load->library("utility");

    }

    function index(){
        $data['title'] = 'Tanda Terima';		
        $data['objectId'] = 'TandaTerima';
        $data['tipetandaterima'] = 'draft';
        $data['bidanglist'] = $this->bidang_model->getListBidang($data['objectId']);
        $data['bidanglistFilter'] = $this->bidang_model->getListBidangFilter($data['objectId']);
        
        $this->load->view('transaksi/tandaterima_v',$data);
    }
    
    function draft(){
        $data['title'] = 'Tanda Terima';		
        $data['objectId'] = 'ttdraft';
        $data['tipetandaterima'] = 'draft';
        
        $data['bidanglistFilter'] = $this->bidang_model->getListBidangFilter($data['objectId']);
        
        
        $this->load->view('transaksi/tandaterima_v',$data);
    }
    
    public function add(){
        $data['title'] = 'Tanda Terima SPB';	
        $data['objectId'] = "addTandaTerima";  
        $data['tipetandaterima'] = 'draft';
        $data['bidanglist'] = $this->bidang_model->getListBidang($data['objectId']);
        $this->load->view('transaksi/tandaterima_rec_v',$data);
    }
  
    function grid($tipetandaterima,$periodeawal,$periodeakhir,$bidang){	
        $periodeawal = $this->utility->ourDeFormatSQLDate($periodeawal);
	$periodeakhir = $this->utility->ourDeFormatSQLDate($periodeakhir);
        echo $this->tandaterima_model->easyGrid(1,$tipetandaterima,$periodeawal,$periodeakhir,$bidang);
    }
    
    function griddetail($tandaid){	        
        echo $this->tandaterima_model->easyGridDetail(1,$tandaid);
    }
    
    function gridspb($tipetandaterima,$bidang){	
        
        echo $this->tandaterima_model->easyGridInput(1,$tipetandaterima,$bidang);
    }
    
   
    private function get_form_values() {
            // XXS Filtering enforced for user input
        $data['nomor'] = $this->input->post("nomor", TRUE);
        $data['tanggal'] = $this->input->post("tanggal", TRUE);
        $data['tipe'] = $this->input->post("tanggal", TRUE);
        $data['keterangan'] = $this->input->post("keterangan", TRUE);       
        $data['bidang_id'] = $this->input->post("bidang_id", TRUE);
        $data['spb_ids'] = $this->input->post("spb_ids", TRUE);
        
        return $data;
    }

    
    
    function save($aksi="", $kode=""){
        $this->load->library('form_validation');
        $data = $this->get_form_values();
        $status = "";
        $result = false;

        //validasi form
        $this->form_validation->set_rules("nomor", 'Nomor Tanda Terima', 'trim|required|xss_clean');
        
        
        //$this->form_validation->set_rules("jumlah", 'Jumlah Uang', 'trim|required|xss_clean');

        $data['pesan_error'] = '';
        if ($this->form_validation->run() == FALSE){
                //jika data tidak valid kembali ke view
                $data["pesan_error"].=(trim(form_error("nomor"," "," "))==""?"":form_error("nomor"," "," ")."<br/>");
                //$data["pesan_error"].=(trim(form_error("jumlah"," "," "))==""?"":form_error("jumlah"," "," ")."<br/>");
                $status = $data["pesan_error"];
        }else {
            if($aksi=="add"){ // add
                if (!$this->tandaterima_model->isExistKode($data['nomor'])){
                        $result = $this->tandaterima_model->InsertOnDb($data,$status);
                }
                else
                        $data['pesan_error'] .= 'Nomor sudah ada';

            }else { // edit
                $result=$this->tandaterima_model->UpdateOnDb($data,$kode);
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
            if ($this->tandaterima_model->isSaveDelete($id))
                    $result = $this->tandaterima_model->DeleteOnDb($id);
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
        $pdfdata = $this->tandaterima_model->easyGrid(2);
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
            echo  $this->tandaterima_model->easyGrid(3);
    }

}
?>