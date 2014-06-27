<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of bidang
 *
 * @author chan
 */
class bidang extends CI_Controller {
    //put your code here
     function __construct()
    {
        parent::__construct();			

        //	$userdata = array ('userLogin' => $userLogin,'logged_in' => TRUE,'groupId'=>$this->sys_login_model->groupId,'fullName'=>$this->sys_login_model->fullName,'userId'=>$this->sys_login_model->userId,'groupLevel'=>$this->sys_login_model->level);

        $this->load->model('/security/sys_menu_model');
        $this->load->model('/rujukan/bidang_model');
        $this->load->library("utility");

    }

    function index(){
        $data['title'] = 'Bidang';		
        $data['objectId'] = 'BIDANG';
        $this->load->view('rujukan/bidang_v',$data);
    }

    function grid(){	
        echo $this->bidang_model->easyGrid();
    }

    private function get_form_values() {
            // XXS Filtering enforced for user input
        $data['bidang'] = $this->input->post("bidang", TRUE);
        $data['bidang_id'] = $this->input->post("bidang_id", TRUE);
        
        return $data;
    }

    function save($aksi="", $kode=""){
        $this->load->library('form_validation');
        $data = $this->get_form_values();
        $status = "";
        $result = false;

        //validasi form
        $this->form_validation->set_rules("bidang", 'Nama Bidang', 'trim|required|xss_clean');
        
        $data['pesan_error'] = '';
        if ($this->form_validation->run() == FALSE){
                //jika data tidak valid kembali ke view
                $data["pesan_error"].=(trim(form_error("bidang"," "," "))==""?"":form_error("bidang"," "," ")."<br/>");
                //$data["pesan_error"].=(trim(form_error("jumlah"," "," "))==""?"":form_error("jumlah"," "," ")."<br/>");
                $status = $data["pesan_error"];
        }else {
            if($aksi=="add"){ // add
                if (!$this->bidang_model->isExistKode($data['bidang'])){
                        $result = $this->bidang_model->InsertOnDb($data,$status);
                }
                else
                        $data['pesan_error'] .= 'Nama Bidang sudah ada';

            }else { // edit
                $result=$this->bidang_model->UpdateOnDb($data,$kode);
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
            if ($this->bidang_model->isSaveDelete($id))
                    $result = $this->bidang_model->DeleteOnDb($id);
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
        $pdfdata = $this->bidang_model->easyGrid(2);
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
            echo  $this->bidang_model->easyGrid(3);
    }
}
