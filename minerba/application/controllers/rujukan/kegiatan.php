<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of kegiatan
 *
 * @author chan
 */
class kegiatan extends CI_Controller {
    //put your code here
     function __construct()
    {
        parent::__construct();			

        //	$userdata = array ('userLogin' => $userLogin,'logged_in' => TRUE,'groupId'=>$this->sys_login_model->groupId,'fullName'=>$this->sys_login_model->fullName,'userId'=>$this->sys_login_model->userId,'groupLevel'=>$this->sys_login_model->level);

        $this->load->model('/security/sys_menu_model');
        $this->load->model('/rujukan/kegiatan_model');
        $this->load->model('/rujukan/bidang_model');
        $this->load->library("utility");

    }

    function index(){
        $data['title'] = 'Kegiatan';		
        $data['objectId'] = 'KEGIATAN';
        $data['bidanglist'] = $this->bidang_model->getListBidang($data['objectId']);
        
        $this->load->view('rujukan/kegiatan_v',$data);
    }

    function grid(){	
        echo $this->kegiatan_model->easyGrid();
    }

    private function get_form_values() {
            // XXS Filtering enforced for user input
        $data['kode'] = $this->input->post("kode", TRUE);
        $data['nama'] = $this->input->post("nama", TRUE);
        $data['tahun'] = $this->input->post("tahun", TRUE);
        $data['pagu'] = $this->input->post("pagu", TRUE);
        $data['bidang_id'] = $this->input->post("bidang_id", TRUE);
        $data['kegiatan_id'] = $this->input->post("kegiatan_id", TRUE);
        
        return $data;
    }

    function save($aksi="", $kode=""){
        $this->load->library('form_validation');
        $data = $this->get_form_values();
        $status = "";
        $result = false;

        //validasi form
        $this->form_validation->set_rules("nama", 'Nama kegiatan', 'trim|required|xss_clean');
        
        $data['pesan_error'] = '';
        if ($this->form_validation->run() == FALSE){
                //jika data tidak valid kembali ke view
                $data["pesan_error"].=(trim(form_error("nama"," "," "))==""?"":form_error("nama"," "," ")."<br/>");
                //$data["pesan_error"].=(trim(form_error("jumlah"," "," "))==""?"":form_error("jumlah"," "," ")."<br/>");
                $status = $data["pesan_error"];
        }else {
            if($aksi=="add"){ // add
                if (!$this->kegiatan_model->isExistKode($data['kode'])){
                        $result = $this->kegiatan_model->InsertOnDb($data,$status);
                }
                else
                        $data['pesan_error'] .= 'Kode kegiatan sudah ada';

            }else { // edit
                $result=$this->kegiatan_model->UpdateOnDb($data,$kode);
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
            if ($this->kegiatan_model->isSaveDelete($id))
                    $result = $this->kegiatan_model->DeleteOnDb($id);
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
        $pdfdata = $this->kegiatan_model->easyGrid(2);
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
            echo  $this->kegiatan_model->easyGrid(3);
    }
    
    function import(){
            # --
        $error='';
        $result=true;
        $extensi = '';

        # load
        $this->load->helper('file');

        # upload filenya
        $fupload = $_FILES['datafile'];
        $nama = $_FILES['datafile']['name'];
        $extensi = $_FILES['datafile']['type'];
        //chan
        $allowedExtensions = array("xls"); 

        if (!in_array(end(explode(".", 
            strtolower($nama))), 
            $allowedExtensions)) { 
            echo json_encode(array('success'=>false, 'msg'=>'File yang akan disuport hanya untuk tipe Excel (xls)'));

            return;
         /* die($file['name'].' is an invalid file type!<br/>'. 
              '<a href="javascript:history.go(-1);">'. 
              '&lt;&lt Go Back</a>');  */
        } 


        if(isset($fupload)){
            $lokasi_file 	= $fupload['tmp_name'];
            $direktori		= "restore/$nama";

            if(move_uploaded_file($lokasi_file, $direktori)){ // proses upload


                $this->load->library('excel');
                $this->excel->setOutputEncoding('CP1251');
                $this->excel->read($direktori); // baca file

                # baca per baris
                for($i=2, $n=$this->excel->rowcount(0); $i<= $n; $i++){
                        # data
                    //
                    $data['kode'] 	= $this->excel->val($i, 1);
                    $data['nama'] 	= $this->excel->val($i, 2);
                    $data['bidang_id'] 	= $this->excel->val($i, 3);
                    $data['pagu'] 	= $this->excel->val($i, 4);
                    $data['tahun'] 	= $this->excel->val($i, 5);

                    # proses
                    $result=$this->kegiatan_model->importData($data);
                    if (!$result) $error = "Gagal Import, kemungkinan data yang diimport sudah ada pada database";
                }

                    /* }else{
                            $result=false;
                            $error = "Format File tidak diketahui.";
                    } */

                    # clear folder temporari
                delete_files('restore');

            }else{
                    $result=false;
                    $error = "Gagal upload";
            }
        }

        if ($result){
                echo json_encode(array('success'=>true, 'msg'=>'Data berhasil diimport'));
        } else {
                echo json_encode(array('msg'=>$error));
        }

    }
}
