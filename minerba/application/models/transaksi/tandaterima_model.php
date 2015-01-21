<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *INVISI
*/

class Tandaterima_model extends CI_Model
{	
	/**
	* constructor
	*/
    public function __construct()    {
        parent::__construct();
		//$this->CI =& get_instance();
    }
	
    public function easyGrid($purpose=1,$tipeapproval,$filawal,$filakhir,$filbidang,$filSpb,$filNomor){
        $lastNo = isset($_POST['lastNo']) ? intval($_POST['lastNo']) : 0;
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;  
        $limit = isset($_POST['rows']) ? intval($_POST['rows']) : 10;  

        $count = $this->GetRecordCount($tipeapproval,$filawal,$filakhir,$filbidang,$filSpb,$filNomor);
        $response = new stdClass();
        $response->total = $count;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'nomor';  
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';  
        $offset = ($page-1)*$limit;  
        $pdfdata = array();
          $jumlah =0;
        if ($count>0){
            
            if($filawal != '' && $filawal != '-1' && $filawal != null) {
                $this->db->where("s.tanggal between '$filawal' and '$filakhir'");
            }
            if($filbidang != '' && $filbidang != '-1' && $filbidang != null) {
                $this->db->where("s.bidang_id",$filbidang);
            } 
			if($filbidang != '' && $filbidang != '-1' && $filbidang != null) {
                $this->db->where("s.bidang_id",$filbidang);
            }
			if($filNomor != '' && $filNomor != '-1' && $filNomor != null) {
				$this->db->like("nomor",$filNomor);
			}
			if($filSpb != '' && $filSpb != '-1' && $filSpb != null) {
				$this->db->where("tanda_id in (select tanda_id from tbl_tanda_terima_detail d inner join tbl_spb s on d.spb_id = s.spb_id and s.nomor like '%$filSpb%')");
			}
            $this->db->order_by($sort." ".$order );
            if($purpose==1){$this->db->limit($limit,$offset);}
            $this->db->select("s.*,b.bidang ",false);
            $this->db->from('tbl_tanda_terima s left join tbl_bidang b on b.bidang_id=s.bidang_id'
                    ,false);
            $query = $this->db->get();
          
            $i=0;
            $no =$lastNo;
            foreach ($query->result() as $row)            {
                $no++;
                $response->rows[$i]['no']= $no;
                $response->rows[$i]['tanda_id']=$row->tanda_id;
                $response->rows[$i]['nomor']=$row->nomor;
                $response->rows[$i]['tanggal']=$this->utility->ourFormatDate2($row->tanggal);
                $response->rows[$i]['bidang_id']=$row->bidang_id;
                $response->rows[$i]['bidang']=$row->bidang;                                
                $response->rows[$i]['keterangan']=$row->keterangan;
                
               
             //   $response->rows[$i]['jumlah']=$row->jumlah; //$this->utility->ourFormatNumber($row->jumlah);
             //   $jumlah += $row->jumlah;
                //utk kepentingan export pdf===================
                $pdfdata[] = array($no,$response->rows[$i]['tanggal'],$response->rows[$i]['nomor'],$response->rows[$i]['bidang'],$response->rows[$i]['keterangan'],$response->rows[$i]['tanda_id']);
        //============================================================
				//utk kepentingan export excel ==========================
				$row->tanda_id = $no;
				$row->bidang = $response->rows[$i]['bidang'];
				
				unset($row->tipe);
				unset($row->log_insert);
				unset($row->log_update);
				unset($row->bidang_id);
				
				
		
                $i++;
            } 

            $response->lastNo = $no;
                //$query->free_result();
        }else {
            $response->rows[$count]['no']= '';
            $response->rows[$count]['tanda_id']= '';
            $response->rows[$count]['nomor']='';
            $response->rows[$count]['tanggal']='';
            $response->rows[$count]['bidang_id']='';
            $response->rows[$count]['bidang']='';
            $response->rows[$count]['keterangan']='';
            
            $response->lastNo = 0;	
        }
        
        
        if ($purpose==1) //grid normal
            return json_encode($response);
        else if($purpose==2){//pdf
            return $pdfdata;
        }
        else if($purpose==3){//to excel
                //tambahkan header kolom
            $colHeaders = array("No","Tanggal","Nomor Tanda Terima","Bidang","Keterangan");		
        //    var_dump($query);die;
            to_excel($query,"TandaTerima",$colHeaders);
        }
        else if ($purpose==4) { //WEB SERVICE
            return $response;
        }

    }
    
    public function easyGridInput($purpose=1,$tipeapproval,$filbidang,$filtandaid=null){
        
        $response = new stdClass();
        
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'nomor';  
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';  
       
          $jumlah =0;
       
            
         
            if($filbidang != '' && $filbidang != '-1' && $filbidang != null) {
                $this->db->where("s.bidang_id",$filbidang);
            }
           $this->db->where("s.spb_id not in (select spb_id from tbl_tanda_terima_detail)");
            $this->db->order_by($sort." ".$order );
            //if($purpose==1){$this->db->limit($limit,$offset);}
             $this->db->select("s.*,k.kategori,b.bidang ",false);
            $this->db->from('tbl_spb s left join tbl_bidang b on b.bidang_id=s.bidang_id'
                    . ' left join tbl_spb_kategori k on k.kategori_id = s.kategori_id ',false);
            $query = $this->db->get();
          
            $i=0;
            $no =0;//$lastNo;
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
                
               
                $response->rows[$i]['jumlah']=$row->jumlah; //$this->utility->ourFormatNumber($row->jumlah);
                $jumlah += $row->jumlah;
                //utk kepentingan export pdf===================
              // $pdfdata[] = array($no,$response->rows[$i]['nomor'],$response->rows[$i]['tujuan'],$response->rows[$i]['untuk'],$response->rows[$i]['beban_kegiatan']);
        //============================================================
                $i++;
            } 
            if ($no==0){
                $count=0;
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
            $response->lastNo = $no;
                //$query->free_result();
       
        
        
       // if ($purpose==1) //grid normal
            return json_encode($response);
       

    }
    
    public function easyGridDetail($purpose=1,$tandaid,$forInput=false){
        
        $response = new stdClass();
        
        
        $pdfdata = array();
        
            
        
        $this->db->where("s.tanda_id",$tandaid);
        
           
            $this->db->order_by("detail_id");
            
            $this->db->select("s.*,p.tanggal,p.nomor,p.jumlah,p.kategori_id,k.kategori,p.untuk,p.bidang_id,b.bidang,p.tujuan,p.beban_kode, p.beban_kegiatan",false);
            $this->db->from('tbl_tanda_terima_detail s inner join tbl_spb p on p.spb_id = s.spb_id left join tbl_spb_kategori k on p.kategori_id = k.kategori_id '
                    . 'left join tbl_bidang b on p.bidang_id = b.bidang_id ',false);
            $query = $this->db->get();
          
            $i=0;
            $no=0;
            foreach ($query->result() as $row)            {
                $no++;
                $response->rows[$i]['no']= $no;
                $response->rows[$i]['detail_id']=$row->detail_id;
                $response->rows[$i]['tanda_id']=$row->tanda_id;
                $response->rows[$i]['spb_id']=$row->spb_id;
                $response->rows[$i]['nomor']=$row->nomor;
                if ($forInput){
                    $response->rows[$i]['jumlah']=$row->jumlah;
                }
                else {
                    $response->rows[$i]['jumlah']=$this->utility->ourFormatNumber($row->jumlah);    
                }
                
                
                $response->rows[$i]['kategori']=$row->kategori;
                $response->rows[$i]['untuk']=$row->untuk;
                $response->rows[$i]['tanggal']=$this->utility->ourFormatDate2($row->tanggal);                
                $response->rows[$i]['bidang_id']=$row->bidang_id;
                $response->rows[$i]['bidang']=$row->bidang;                
                $response->rows[$i]['kategori_id']=$row->kategori_id;
                $response->rows[$i]['tujuan']=$row->tujuan;
                $response->rows[$i]['beban_kegiatan']=$row->beban_kegiatan;
                $response->rows[$i]['beban_kode']=$row->beban_kode;
                $response->rows[$i]['kegiatan']='['.$row->beban_kode.']'.$row->beban_kegiatan;
                
                //utk kepentingan export pdf===================
                $pdfdata[] = array($no,$response->rows[$i]['tanggal'],$response->rows[$i]['nomor'],$response->rows[$i]['jumlah'],$response->rows[$i]['kategori'],$response->rows[$i]['untuk']);
        //============================================================
                $i++;
            } 

            $response->lastNo = $no;
                //$query->free_result();
       if ($no==0) {
            $count =0;
            $response->rows[$count]['no']= '';
            $response->rows[$count]['detail_id']= '';
            $response->rows[$count]['tanda_id']= '';
            $response->rows[$count]['spb_id']= '';
            $response->rows[$count]['nomor']='';
            $response->rows[$count]['tanggal']='';
            $response->rows[$count]['jumlah']='';
            $response->rows[$count]['kategori']='';
            $response->rows[$count]['untuk']='';
            $response->rows[$count]['tanggal']='';                
            $response->rows[$count]['bidang_id']='';
            $response->rows[$count]['bidang']='';                
            $response->rows[$count]['kategori_id']='';
            $response->rows[$count]['tujuan']='';
            $response->rows[$count]['beban_kegiatan']='';
            $response->rows[$count]['beban_kode']='';
            $response->rows[$count]['kegiatan']='';
        }
        
        
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
    public function GetRecordCount($tipeapproval,$filawal,$filakhir,$filbidang,$filSpb,$filNomor){
       
        if($filawal != '' && $filawal != '-1' && $filawal != null) {
            $this->db->where("tanggal between '$filawal' and '$filakhir'");
        }
        if($filbidang != '' && $filbidang != '-1' && $filbidang != null) {
            $this->db->where("bidang_id",$filbidang);
        }
		
		if($filNomor != '' && $filNomor != '-1' && $filNomor != null) {
            $this->db->like("nomor",$filNomor);
        }
		if($filSpb != '' && $filSpb != '-1' && $filSpb != null) {
            $this->db->where("tanda_id in (select tanda_id from tbl_tanda_terima_detail d inner join tbl_spb s on d.spb_id = s.spb_id and s.nomor like '%$filSpb%')");
        }
     
        $query=$this->db->from('tbl_tanda_terima');
        return $this->db->count_all_results();
        $this->db->free_result();
    }
    
    

    public function isExistKode($kode=null){	
        if ($kode!=null)//utk update
            $this->db->where('nomor',$kode); //buat validasi

        $this->db->select('*');
        $this->db->from('tbl_tanda_terima');

        $query = $this->db->get();
        $rs = $query->num_rows() ;		
        $query->free_result();
        return ($rs>0);
    }
    
    public function selectById($id){
        $this->db->select("*", false);
        $this->db->from('tbl_tanda_terima');
        $this->db->where('tanda_id',$id);

        $query = $this->db->get();
        return $query->row();
    }
	
    public function isSaveDelete($kode){			
      /*  $this->db->where('nomor',$kode); //buat validasi		
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
    public function InsertOnDb($data,& $error, & $tandaid) {
            //query insert data		
        $this->db->trans_start();
        $this->db->set('nomor',$data['nomor']);
        $this->db->set('tanggal',$this->utility->ourDeFormatSQLDate($data['tanggal']));
        $this->db->set('bidang_id',$data['bidang_id']);
        
        $this->db->set('keterangan',$data['keterangan']);
      
        $this->db->set('log_insert', 		$this->session->userdata('user_id').';'.date('Y-m-d H:i:s'));

        $result = $this->db->insert('tbl_tanda_terima');
        $tandaid = $this->db->insert_id();
        $errNo   = $this->db->_error_number();
        $errMess = $this->db->_error_message();
        $dataspb = explode(',', $data['spb_ids']);
        for ($i=0;$i<count($dataspb);$i++){
            $this->db->flush_cache();
            $this->db->set('tanda_id',$tandaid);
            $this->db->set('spb_id',$dataspb[$i]);
            $this->db->insert('tbl_tanda_terima_detail');
        }
            $error = $errMess;
            //var_dump($errMess);die;
      //  log_message("error", "Problem Inserting to : ".$errMess." (".$errNo.")"); 
            //return
        $this->db->trans_complete();
	return $this->db->trans_status();
    }

	//update data
    public function UpdateOnDb($data, $kode) {
        $this->db->where('tanda_id',$kode);
        //query insert data		
        $this->db->set('nomor',$data['nomor']);
        $this->db->set('tanggal',$this->utility->ourDeFormatSQLDate($data['tanggal']));
        $this->db->set('bidang_id',$data['bidang_id']);
        $this->db->set('keterangan',$data['keterangan']);
      
        $this->db->set('log_update', 		$this->session->userdata('user_id').';'.date('Y-m-d H:i:s'));

        $result=$this->db->update('tbl_tanda_terima');

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
    
   
	
	//hapus data
    public function DeleteOnDb($id){
        $this->db->flush_cache();
        $this->db->where('tanda_id', $id);
        $result = $this->db->delete('tbl_tanda_terima'); 

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
	
   function getNewNumber($bln,$tahun){
       $prefix = 'TT'."/".$bln."/".$tahun.'/';
       $no= $this->utility->ourGetNextIDNum("nomor","tbl_tanda_terima"," and date_format(tanggal,'%m')= '$bln' and  date_format(tanggal,'%Y')='$tahun'",
               $prefix,"",4);
       echo $no;
   }
}
?>
