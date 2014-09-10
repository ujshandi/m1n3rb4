<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *INVISI
*/

class Spb_model extends CI_Model
{	
	/**
	* constructor
	*/
    public function __construct()    {
        parent::__construct();
		//$this->CI =& get_instance();
    }
	
    public function easyGrid($purpose=1,$tipeapproval,$filawal,$filakhir,$filbidang,$filkategori,$filNomor){
        $lastNo = isset($_POST['lastNo']) ? intval($_POST['lastNo']) : 0;
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;  
        $limit = isset($_POST['rows']) ? intval($_POST['rows']) : 10;  

        $count = $this->GetRecordCount($tipeapproval,$filawal,$filakhir,$filbidang,$filkategori,$filNomor);
        $response = new stdClass();
        $response->total = $count;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'tujuan';  
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';  
        $offset = ($page-1)*$limit;  
        $pdfdata = array();
		$viewname = 'v_spb_draft s';
          $jumlah =0;
        if ($count>0){
            switch ($tipeapproval){
                case "verifikasi" : /// adalah draft yg telah di buat tanda terima
					$viewname = 'v_spb_verifikasi s';
					//$this->db->where("((status_verifikasi is null) or (status_verifikasi=''))");break;
				break;	
                case "penguji" : 
                  //  $this->db->where("((status_verifikasi is not null) or (status_verifikasi<>''))");
                  //  $this->db->where("((status_spm is null) or (status_spm<>'') or (status_bendahara is null) or (status_bendahara<>''))");
					$viewname = 'v_spb_penguji s';
                break;
                case "spm" : 
                    //$this->db->where("((status_penguji is not null) or (status_penguji<>''))");
                    //$this->db->where("s.kategori_id in (select kategori_sppd from tbl_konstanta) ");
					$viewname = 'v_spb_spm s';
                break;
                case "bendahara" : 
                    //$this->db->where("((status_penguji is not null) or (status_penguji<>''))");
                    //$this->db->where("s.kategori_id in (select kategori_bahan from tbl_konstanta union "
                      //      . "select kategori_non_bahan from tbl_konstanta)");
					  $viewname = 'v_spb_bendahara s';
                break;
            }
            if($filawal != '' && $filawal != '-1' && $filawal != null) {
                $this->db->where("s.tanggal between '$filawal' and '$filakhir'");
            }
            if($filbidang != '' && $filbidang != '-1' && $filbidang != null) {
                $this->db->where("s.bidang_id",$filbidang);
            }
            if($filkategori != '' && $filkategori != '-1' && $filkategori != null) {
                $this->db->where("s.kategori_id",$filkategori);
            }
			if($filNomor != '' && $filNomor != '-1' && $filNomor != null) {
				$this->db->like("nomor",$filNomor);
			}
            $this->db->order_by($sort." ".$order );
            if($purpose==1){$this->db->limit($limit,$offset);}
			
           // $this->db->select("s.*,k.kategori,b.bidang ",false);
            //$this->db->from('tbl_spb s left join tbl_bidang b on b.bidang_id=s.bidang_id'
              //      . ' left join tbl_spb_kategori k on k.kategori_id = s.kategori_id',false);
			$this->db->select("* ",false);  
			$this->db->from($viewname,false);
            $query = $this->db->get();
          
            $i=0;
            $no =$lastNo;
            foreach ($query->result() as $row)            {
                $no++;
                $response->rows[$i]['no']= $no;
                $response->rows[$i]['spb_id']=$row->spb_id;
                $response->rows[$i]['nomor']=$row->nomor;
                $response->rows[$i]['tanggal']=$this->utility->ourFormatDate2($row->tanggal);
                $response->rows[$i]['bidang_id']=$row->bidang_id;
                $response->rows[$i]['bidang']=$row->bidang;
                $response->rows[$i]['kategori']=$row->kategori;
                $response->rows[$i]['kategori_id']=$row->kategori_id;
                $response->rows[$i]['tujuan']=$row->tujuan;
                $response->rows[$i]['untuk']=$row->untuk;
                $response->rows[$i]['beban_kegiatan']=$row->beban_kegiatan;
                $response->rows[$i]['beban_kode']=$row->beban_kode;
                $response->rows[$i]['kegiatan']='['.$row->beban_kode.']'.$row->beban_kegiatan;
                $response->rows[$i]['status_verifikasi']=$row->status_verifikasi;
                $response->rows[$i]['status_penguji']=$row->status_penguji;
                $response->rows[$i]['status_spm']=$row->status_spm;
                $response->rows[$i]['status_bendahara']=$row->status_bendahara;
                if ($row->status_verifikasi==null) {
                    $response->rows[$i]['status_verifikasi_tanggal']='';
                    $response->rows[$i]['status_verifikasi_oleh']='';
                } else                {
                    $response->rows[$i]['status_verifikasi_tanggal']=$this->utility->ourFormatDate2($this->utility->ourEkstrakString($row->status_verifikasi,';',1));
                    $response->rows[$i]['status_verifikasi_oleh']=$this->user_model->getFullName($this->utility->ourEkstrakString($row->status_verifikasi,';',0));                
                }
                if ($row->status_penguji==null) {
                    $response->rows[$i]['status_penguji_tanggal']='';
                    $response->rows[$i]['status_penguji_oleh']='';
                } else                {
                    $response->rows[$i]['status_penguji_tanggal']=$this->utility->ourFormatDate2($this->utility->ourEkstrakString($row->status_penguji,';',1));
                    $response->rows[$i]['status_penguji_oleh']=$this->user_model->getFullName($this->utility->ourEkstrakString($row->status_penguji,';',0));                
                }
                if ($row->status_spm==null) {
                    $response->rows[$i]['status_spm_tanggal']='';
                    $response->rows[$i]['status_spm_oleh']='';
                } else                {
                    $response->rows[$i]['status_spm_tanggal']=$this->utility->ourFormatDate2($this->utility->ourEkstrakString($row->status_spm,';',1));
                    $response->rows[$i]['status_spm_oleh']=$this->user_model->getFullName($this->utility->ourEkstrakString($row->status_spm,';',0));                
                }
                if ($row->status_bendahara==null) {
                    $response->rows[$i]['status_bendahara_tanggal']='';
                    $response->rows[$i]['status_bendahara_oleh']='';
                } else                {
                    $response->rows[$i]['status_bendahara_tanggal']=$this->utility->ourFormatDate2($this->utility->ourEkstrakString($row->status_bendahara,';',1));
                    $response->rows[$i]['status_bendahara_oleh']=$this->user_model->getFullName($this->utility->ourEkstrakString($row->status_bendahara,';',0));                
                }
                
                switch ($tipeapproval){
                    case "verifikasi" : 
                        $response->rows[$i]['pejabat_oleh'] = $response->rows[$i]['status_verifikasi_oleh']; 
                        $response->rows[$i]['pejabat_tanggal'] = $response->rows[$i]['status_verifikasi_tanggal']; 
                    break;
                    case "penguji" : 
                        $response->rows[$i]['pejabat_oleh'] = $response->rows[$i]['status_penguji_oleh']; 
                        $response->rows[$i]['pejabat_tanggal'] = $response->rows[$i]['status_penguji_tanggal']; 
                    break;
                default : 
                    $response->rows[$i]['pejabat_oleh'] = ""; 
                    $response->rows[$i]['pejabat_tanggal'] = ""; 
                   
                }
                
                $response->rows[$i]['jumlah']=$row->jumlah; //$this->utility->ourFormatNumber($row->jumlah);
                $jumlah += $row->jumlah;
                //utk kepentingan export pdf===================
                $pdfdata[] = array($no,$response->rows[$i]['nomor'],$response->rows[$i]['tujuan'],$response->rows[$i]['untuk'],$response->rows[$i]['beban_kegiatan']);
        //============================================================
                $i++;
            } 

            $response->lastNo = $no;
                //$query->free_result();
        }else {
            $response->rows[$count]['no']= '';
            $response->rows[$count]['sb_id']= '';
            $response->rows[$count]['nomor']='';
            $response->rows[$count]['tanggal']='';
            $response->rows[$count]['bidang_id']='';
            $response->rows[$count]['bidang']='';
            $response->rows[$count]['kategori']='';
            $response->rows[$count]['kategori_id']='';
            $response->rows[$count]['tujuan']='';
            $response->rows[$count]['untuk']='';
            $response->rows[$count]['beban_kegiatan']='';
            $response->rows[$count]['beban_kode']='';
            $response->rows[$count]['kegiatan']='';
            $response->rows[$count]['status_verifikasi']='';
            $response->rows[$count]['status_verifikasi_tanggal']='';
            $response->rows[$count]['status_verifikasi_oleh']='';
            $response->rows[$count]['status_penguji']='';
            $response->rows[$count]['status_penguji_tanggal']='';
            $response->rows[$count]['status_penguji_oleh']='';
            $response->rows[$count]['status_spm']='';
            $response->rows[$count]['status_spm_tanggal']='';
            $response->rows[$count]['status_spm_oleh']='';
            $response->rows[$count]['status_bendahara']='';
            $response->rows[$count]['status_bendahara_tanggal']='';
            $response->rows[$count]['status_bendahara_oleh']='';
            $response->rows[$count]['jumlah']='';
            $response->lastNo = 0;	
        }
        
        $response->footer[0]['no']='';
        $response->footer[0]['sb_id']='';        
        //$response->footer[0]['jumlah']='<b>'.$jumlah.'</b>';//$this->utility->cekNumericFmt(
        $response->footer[0]['jumlah']=$jumlah;//$this->utility->cekNumericFmt(

        if ($purpose==1) //grid normal
            return json_encode($response);
        else if($purpose==2){//pdf
            return $pdfdata;
        }
        else if($purpose==3){//to excel
                //tambahkan header kolom
            $colHeaders = array("Kode","Nama Kementerian","Singkatan","Nama Menteri");		
            //var_dump($query->result());die;
            to_excel($query,"Kementerian",$colHeaders);
        }
        else if ($purpose==4) { //WEB SERVICE
            return $response;
        }

    }
	
	//jumlah data record buat paging
    public function GetRecordCount($tipeapproval,$filawal,$filakhir,$filbidang,$filkategori,$filNomor){
		$viewname = 'v_spb_draft';
         switch ($tipeapproval){
                case "verifikasi" : /// adalah draft yg telah di buat tanda terima
					$viewname = 'v_spb_verifikasi';
					//$this->db->where("((status_verifikasi is null) or (status_verifikasi=''))");
				break;					
                case "penguji" : 
                  //  $this->db->where("((status_verifikasi is not null) or (status_verifikasi<>''))");
                  //  $this->db->where("((status_spm is null) or (status_spm<>'') or (status_bendahara is null) or (status_bendahara<>''))");
					$viewname = 'v_spb_penguji';
                break;
                case "spm" : 
                    //$this->db->where("((status_penguji is not null) or (status_penguji<>''))");
                    //$this->db->where("s.kategori_id in (select kategori_sppd from tbl_konstanta) ");
					$viewname = 'v_spb_spm';
                break;
                case "bendahara" : 
                    //$this->db->where("((status_penguji is not null) or (status_penguji<>''))");
                    //$this->db->where("s.kategori_id in (select kategori_bahan from tbl_konstanta union "
                      //      . "select kategori_non_bahan from tbl_konstanta)");
					  $viewname = 'v_spb_bendahara';
                break;
            }
        if($filawal != '' && $filawal != '-1' && $filawal != null) {
            $this->db->where("tanggal between '$filawal' and '$filakhir'");
        }
        if($filbidang != '' && $filbidang != '-1' && $filbidang != null) {
            $this->db->where("bidang_id",$filbidang);
        }
        if($filkategori != '' && $filkategori != '-1' && $filkategori != null) {
            $this->db->where("kategori_id",$filkategori);
        }
		if($filNomor != '' && $filNomor != '-1' && $filNomor != null) {
            $this->db->like("nomor",$filNomor);
        }
        $query=$this->db->from($viewname);
        return $this->db->count_all_results();
        $this->db->free_result();
    }

    public function isExistKode($kode=null){	
        if ($kode!=null)//utk update
            $this->db->where('nomor',$kode); //buat validasi

        $this->db->select('*');
        $this->db->from('tbl_spb');

        $query = $this->db->get();
        $rs = $query->num_rows() ;		
        $query->free_result();
        return ($rs>0);
    }
	
    public function isSaveDelete($kode){			
        /*$this->db->where('nomor',$kode); //buat validasi		
        $this->db->select('*');
        $this->db->from('tbl_sasaran_kl');

        $query = $this->db->get();
        $rs = $query->num_rows() ;		
        $query->free_result();
        $isSave = ($rs==0);
        if ($isSave){
            $this->db->flush_cache();
            $this->db->where('nomor',$kode); //buat validasi		
            $this->db->select('*');
            $this->db->from('tbl_iku_kl');

            $query = $this->db->get();
            $rs = $query->num_rows() ;		
            $query->free_result();
            $isSave = ($rs==0);
        }*/
        $isSave = true;
        return $isSave;
    }
	
	//insert data
    public function InsertOnDb($data,& $error) {
            //query insert data		
        $this->db->set('nomor',$data['nomor']);
        $this->db->set('tanggal',$this->utility->ourDeFormatSQLDate($data['tanggal']));
        $this->db->set('bidang_id',$data['bidang_id']);
        $this->db->set('kategori_id',$data['kategori_id']);
        $this->db->set('tujuan',$data['tujuan']);
        $this->db->set('untuk',$data['untuk']);
        $this->db->set('beban_kegiatan',$data['beban_kegiatan']);
        $this->db->set('beban_kode',$data['beban_kode']);
        $this->db->set('jumlah',$data['jumlah']);
        $this->db->set('log_insert', 		$this->session->userdata('user_id').';'.date('Y-m-d H:i:s'));

        $result = $this->db->insert('tbl_spb');
        $errNo   = $this->db->_error_number();
        $errMess = $this->db->_error_message();
            $error = $errMess;
            //var_dump($errMess);die;
        log_message("error", "Problem Inserting to : ".$errMess." (".$errNo.")"); 
            //return
            if($result) {
                    return TRUE;
            }else {
                    return FALSE;
            }
    }

	//update data
    public function UpdateOnDb($data, $kode) {
        $this->db->where('spb_id',$kode);
        //query insert data		
        $this->db->set('nomor',$data['nomor']);
        $this->db->set('tanggal',$this->utility->ourDeFormatSQLDate($data['tanggal']));
        $this->db->set('bidang_id',$data['bidang_id']);
        $this->db->set('kategori_id',$data['kategori_id']);
        $this->db->set('tujuan',$data['tujuan']);
        $this->db->set('untuk',$data['untuk']);
        $this->db->set('beban_kegiatan',$data['beban_kegiatan']);
        $this->db->set('beban_kode',$data['beban_kode']);
        $this->db->set('jumlah',$data['jumlah']);
        $this->db->set('log_update', 		$this->session->userdata('user_id').';'.date('Y-m-d H:i:s'));

        $result=$this->db->update('tbl_spb');

        $errNo   = $this->db->_error_number();
        $errMess = $this->db->_error_message();
            //var_dump($errMess);die;
            //return
        if($result) {
            return TRUE;
        }else {
            return FALSE;
        }
    }
    
    public function approveVerifikasi($id) {
        $this->db->where('spb_id',$id);
        $this->db->set('status_verifikasi', 		$this->session->userdata('user_id').';'.date('Y-m-d'));
        $result=$this->db->update('tbl_spb');
        if($result) {
            return TRUE;
        }else {
            return FALSE;
        }
    }
    
    public function approvePenguji($id,$spm_bendahara) {
        $this->db->where('spb_id',$id);
        $this->db->set('status_penguji', 		$this->session->userdata('user_id').';'.date('Y-m-d'));
        $this->db->set('spm_bendahara',$spm_bendahara);
        $result=$this->db->update('tbl_spb');
        if($result) {
            return TRUE;
        }else {
            return FALSE;
        }
    }
    
    public function approveSpm($id) {
        $this->db->where('spb_id',$id);
        $this->db->set('status_spm', 		$this->session->userdata('user_id').';'.date('Y-m-d'));
        $result=$this->db->update('tbl_spb');
        if($result) {
            return TRUE;
        }else {
            return FALSE;
        }
    }
    public function approveBendahara($id) {
        $this->db->where('spb_id',$id);
        $this->db->set('status_bendahara', 		$this->session->userdata('user_id').';'.date('Y-m-d'));
        $result=$this->db->update('tbl_spb');
        if($result) {
            return TRUE;
        }else {
            return FALSE;
        }
    }
	
	//hapus data
    public function DeleteOnDb($id){
        $this->db->flush_cache();
        $this->db->where('spb_id', $id);
        $result = $this->db->delete('tbl_spb'); 

        $errNo   = $this->db->_error_number();
        $errMess = $this->db->_error_message();
        $error = $errMess;
        //var_dump($errMess);die;
       
		//return
		
        if($result) {
            return TRUE;
        }else {
             log_message("error", "Problem Update to : ".$errMess." (".$errNo.")"); 
            return FALSE;
        }
    }
	
    public function tolakVerifikasi($id,$data) {
         $this->db->trans_start();
        $this->db->flush_cache();
        $this->db->select("*");
        $this->db->from("tbl_spb");
        $this->db->where('spb_id',$id);
        $qt = $this->db->get();
       
        $this->db->flush_cache();
        $this->db->set('spb_id',$qt->row()->spb_id);
        $this->db->set('nomor',$qt->row()->nomor);
        $this->db->set('tanggal',$qt->row()->tanggal);
        $this->db->set('bidang_id',$qt->row()->bidang_id);
        $this->db->set('kategori_id',$qt->row()->kategori_id);
        $this->db->set('tujuan',$qt->row()->tujuan);
        $this->db->set('untuk',$qt->row()->untuk);
        $this->db->set('beban_kegiatan',$qt->row()->beban_kegiatan);
        $this->db->set('beban_kode',$qt->row()->beban_kode);
        $this->db->set('jumlah',$qt->row()->jumlah);
        $this->db->set('log_insert', 		$this->session->userdata('user_id').';'.date('Y-m-d H:i:s'));
        $this->db->set('keterangan',$data['keterangan']);
        $this->db->insert('tbl_spb_history');
        
        
        $this->db->flush_cache();
        $this->db->where('spb_id', $id);
        $result = $this->db->delete('tbl_spb');
        $this->db->trans_complete();
	return $this->db->trans_status();
    }
    
    public function tolakPenguji($id) {
        $this->db->where('spb_id',$id);
        $this->db->set('status_penguji', 		$this->session->userdata('user_id').';'.date('Y-m-d'));
        $result=$this->db->update('tbl_spb');
        if($result) {
            return TRUE;
        }else {
            return FALSE;
        }
    }
    
    public function tolakSpm($id) {
        $this->db->where('spb_id',$id);
        $this->db->set('status_spm', 		$this->session->userdata('user_id').';'.date('Y-m-d'));
        $result=$this->db->update('tbl_spb');
        if($result) {
            return TRUE;
        }else {
            return FALSE;
        }
    }
    public function tolakBendahara($id) {
        $this->db->where('spb_id',$id);
        $this->db->set('status_bendahara', 		$this->session->userdata('user_id').';'.date('Y-m-d'));
        $result=$this->db->update('tbl_spb');
        if($result) {
            return TRUE;
        }else {
            return FALSE;
        }
    }
    
    public function getListKL($objectId=""){		
        $this->db->flush_cache();
        $this->db->select('nomor,tujuan');
        $this->db->from('tbl_spb');
        $this->db->order_by('nomor');

        $que = $this->db->get();

        $out = '<select name="nomor" id="nomor'.$objectId.'" class="easyui-validatebox" required="true">';

        foreach($que->result() as $r){
                $out .= '<option value="'.$r->nomor.'">'.$r->tujuan.'</option>';
        }

        $out .= '</select>';

        echo $out;
    }
	
}
?>
