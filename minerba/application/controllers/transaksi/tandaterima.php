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
        $data['editmode'] = false;
        $data['tipetandaterima'] = 'draft';
        $data['tanda_id'] = '0';
        $data['bidanglist'] = $this->bidang_model->getListBidang($data['objectId']);
        $this->load->view('transaksi/tandaterima_rec_v',$data);
    }
    
     public function edit($id,$forPrint=false){
        $data['title'] = 'Edit Tanda Terima SPB';	
        $data['objectId'] = "editTandaTerima";  
        $data['tipetandaterima'] = 'draft';
        $data['editmode'] = true;
        $data['bidanglist'] = $this->bidang_model->getListBidang($data['objectId']);
        $row=$this->tandaterima_model->selectById($id);
        $data['nomor'] = $row->nomor;
        $data['tanggal'] = $this->utility->ourFormatDate2($row->tanggal);
        $data['keterangan'] = $row->keterangan;
        $data['bidang_id'] = $row->bidang_id;
        $data['tanda_id'] = $row->tanda_id;
        if ($forPrint){
			$data['listSpb'] =  $this->tandaterima_model->easyGridDetail(2,$id);
			$this->load->view('transaksi/tandaterima_print_v',$data);
		}
		else
			$this->load->view('transaksi/tandaterima_rec_v',$data);
    }
  
    function grid($tipetandaterima,$periodeawal,$periodeakhir,$bidang,$nomor,$nomorTerima){	
        $periodeawal = $this->utility->ourDeFormatSQLDate($periodeawal);
	$periodeakhir = $this->utility->ourDeFormatSQLDate($periodeakhir);
        echo $this->tandaterima_model->easyGrid(1,$tipetandaterima,$periodeawal,$periodeakhir,$bidang,$nomor,$nomorTerima);
    }
    
    function griddetail($tandaid){	        
        echo $this->tandaterima_model->easyGridDetail(1,$tandaid);
    }
    
    function gridspb($tipetandaterima,$bidang,$tanda_id=null){	
        if (($tanda_id!=null)&&($tanda_id>0)){
            echo $this->tandaterima_model->easyGridDetail(1,$tanda_id,true);
        }
        else{
            echo $this->tandaterima_model->easyGridInput(1,$tipetandaterima,$bidang,$tanda_id);
        }
                
        
    }
    
    function get_new_number($bln,$tahun){
        
        echo $this->tandaterima_model->getNewNumber($bln,$tahun);
    }
   
    private function get_form_values() {
            // XXS Filtering enforced for user input
        $data['nomor'] = $this->input->post("nomor", TRUE);
        $data['tanda_id'] = $this->input->post("tanda_id", TRUE);
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
	//	var_dump($kode);die;
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
                        $result = $this->tandaterima_model->InsertOnDb($data,$status,$kode);
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
                echo json_encode(array('success'=>true,"tandaid"=>$kode));
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
	
	public function print_tandaterima_old($id){
		$this->edit($id,true);
	}

	public function print_tandaterima($id){
		$this->load->library('cezpdf');	
		
		$header = $this->tandaterima_model->selectById($id);
		$rowData= $this->tandaterima_model->easyGridDetail(2,$id);;
		if (isset($rowData)){
			foreach ($rowData as $r){
				
				$row[] = array($r[0],$r[1],$r[3],$r[4],$r[5],$r[6],$r[7]);
			}
		}
		
		$data[]=array('label'=>'Nomor','separator'=>":",'value' => $header->nomor);
		$data[]=array('label'=>'Tanggal','separator'=>":",'value' =>$this->utility->ourFormatDate2($header->tanggal));
		$data[]=array('label'=>'Keterangan','separator'=>":",'value' =>$header->keterangan);
		
		$columnHeader = array("<b>No</b>","<b>Nomor</b>","<b>Bidang</b>","<b>Kategori</b>","<b>Jumlah</b>","<b>Untuk Pembayaran</b>","<b>Tujuan Pembayaran</b>");
		
        $pdf = new $this->cezpdf($paper='A4',$orientation='potrait');
        $pdf->ezSetCmMargins(1,1,1,1);
        $pdf->selectFont( APPPATH."libraries/fonts/Helvetica.afm" );

		
        $pdf->ezText('<u><b>Bukti Tanda Terima SPBY</b></u>',12,array('justification'=>'center'));
		

        $pdf->ezText('');
        $pdf->ezText('');
       
		
		$pdf->ezTable($data,array('label'=>'Type','separator'=>':','value'=>'<i>Alias</i>'),'',array('showHeadings'=>0,'shaded'=>0,'showLines'=>0,'maxWidth'=>550, 'xPos' => 40,'xOrientation' => 'right'));
		$pdf->ezText('');
        $pdf->ezText('');
		$pdf->ezTable($row,$columnHeader,'',array('showHeadings'=>1,'shaded'=>0,'showLines'=>1,'maxWidth'=>530, 'xPos' => 40,'xOrientation' => 'right','cols'=>array(
			'1'=>array('width'=>90),
			'4'=>array('width'=>70,"justification"=>"right"),
			'5'=>array('width'=>100),	
			'6'=>array('width'=>100)
		)));
		$pdf->ezSetY(200);
        $footer[]=array('label'=>'','value'=>'');
        $footer[]=array('label'=>'','value'=>'');
        $footer[]=array('label'=>'','value'=>'');
        $footer[]=array('label'=>'','value'=>'');
        $footer[]=array('label'=>'','value'=>'');
        $footer[]=array('label'=>'','value'=>'');
		$pdf->ezTable($footer,array('label'=>'Yang Menyerahkan','value'=>'Penerima'),'',array('showHeadings'=>1,'shaded'=>0,'showLines'=>1, 'xPos' => 350,'xOrientation' => 'right','colGap' => 5 ,'cols'=>array(
								'label'=>array("width"=>100,'justification'=>'center'),
								'value'=>array("width"=>100,'justification'=>'center'))
								));
        //halaman 
        $pdf->ezStartPageNumbers(480,10,8,'right','Tgl.Cetak '.date('d-m-Y H:n:s'),1);//.'  Hal. {PAGENUM} dari {TOTALPAGENUM}',1);
		
       
		
        $opt['Content-Disposition'] = "TandaTerima.pdf";
        $pdf->ezStream($opt);
	}
	
    public function pdf($tipetandaterima,$periode_awal,$periode_akhir,$bidang,$nomor,$nomorTerima){
        $this->load->library('cezpdf');	
		 $periodeawal = $this->utility->ourDeFormatSQLDate($periode_awal);
		$periodeakhir = $this->utility->ourDeFormatSQLDate($periode_akhir);
        
        $pdfdata = $this->tandaterima_model->easyGrid(2,$tipetandaterima,$periodeawal,$periodeakhir,$bidang,$nomor,$nomorTerima);
	//	var_dump($pdfdata);die;
        if (count($pdfdata)==0){
                echo "Data Tidak Tersedia";
                return;
        }
        //$pdfdata = $pdfdata->rows;
        $pdfhead = array('No.','Tanggal','Nomor SPB', 'Jumlah','Kategori','Untuk Pembayaran');
        $pdf = new $this->cezpdf($paper='A4',$orientation='landscape');
        $pdf->ezSetCmMargins(1,1,1,1);
        $pdf->selectFont( APPPATH."libraries/fonts/Helvetica.afm" ); 
		// switch ($tipeapproval){
			// case "draft" : $purposeTitle = "(DRAFT)"; break;
			// case "verifikasi" : $purposeTitle = "Yang Harus di Verifikasi"; break;
			// case "penguji" : $purposeTitle = "Yang Akan di Periksa oleh Pejabat Penguji "; break;
			// default :$purposeTitle = "";
		// }
        $pdf->ezText('Daftar Tanda Terima '.$purposeTitle,12,array('left'=>'1'));
        $pdf->ezText('Periode : '.$periode_awal." s.d. ".$periode_akhir,12,array('left'=>'1')); 
        $pdf->ezText('');
        //halaman 
        $pdf->ezStartPageNumbers(650,10,8,'right','Tgl.Cetak '.date('d-m-Y H:n:s').'  Hal. {PAGENUM} dari {TOTALPAGENUM}',1);
		
		foreach ($pdfdata as $m){
			//var_dump($m);die;
			$pdf->ezText('Tanggal Tanda Terima 	: '.$m[1],12,array('left'=>'1')); 
			$pdf->ezText('No.Tanda Terima 		: '.$m[2],12,array('left'=>'1')); 
			$pdf->ezText('Bidang 				: '.$m[3],12,array('left'=>'1')); 
			$pdf->ezText('Keterangan			: '.$m[4],12,array('left'=>'1')); 
			 $pdf->ezText('');
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
                         1=>array('width'=>55), 
                         2=>array('width'=>100), 
                         3=>array('justification'=>'right','width'=>60), 
                         4=>array('width'=>155),
						 5=>array('width'=>200)),
                'width'=>'700'
				);
				$pdfdetail =  $this->tandaterima_model->easyGridDetail(2,$m[5]);;
				$pdf->ezTable( $pdfdetail, $pdfhead, NULL, $options );
				 $pdf->ezText('');
		}
		
       
		
        $opt['Content-Disposition'] = "rekapTandaTerima.pdf";
        $pdf->ezStream($opt);
    }

    public function excel($tipetandaterima,$periode_awal,$periode_akhir,$bidang,$nomor,$nomorTerima){
	 $periodeawal = $this->utility->ourDeFormatSQLDate($periode_awal);
		$periodeakhir = $this->utility->ourDeFormatSQLDate($periode_akhir);
        
            echo  $this->tandaterima_model->easyGrid(3,$tipetandaterima,$periodeawal,$periodeakhir,$bidang,$nomor,$nomorTerima);
    }

}
?>