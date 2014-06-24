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
	
    public function easyGrid($purpose=1){
        $lastNo = isset($_POST['lastNo']) ? intval($_POST['lastNo']) : 0;
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;  
        $limit = isset($_POST['rows']) ? intval($_POST['rows']) : 10;  

        $count = $this->GetRecordCount();
        $response = new stdClass();
        $response->total = $count;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'tujuan';  
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';  
        $offset = ($page-1)*$limit;  
        $pdfdata = array();
        if ($count>0){
            $this->db->order_by($sort." ".$order );
            if($purpose==1){$this->db->limit($limit,$offset);}
            $this->db->select("*",false);
            $this->db->from('tbl_spb');
            $query = $this->db->get();
            $jumlah =0;
            $i=0;
            $no =$lastNo;
            foreach ($query->result() as $row)            {
                $no++;
                $response->rows[$i]['no']= $no;
                $response->rows[$i]['spb_id']=$row->spb_id;
                $response->rows[$i]['nomor']=$row->nomor;
                $response->rows[$i]['tanggal']=$this->utility->ourFormatDate2($row->tanggal);
                $response->rows[$i]['tujuan']=$row->tujuan;
                $response->rows[$i]['untuk']=$row->untuk;
                $response->rows[$i]['beban_kegiatan']=$row->beban_kegiatan;
                $response->rows[$i]['beban_kode']=$row->beban_kode;
                $response->rows[$i]['pejabat1']=$row->pejabat1;
                if ($row->pejabat2==null) {
                    $response->rows[$i]['pejabat2_tanggal']='';
                    $response->rows[$i]['pejabat2_oleh']='';
                } else                {
                    $response->rows[$i]['pejabat2_tanggal']=$this->utility->ourEkstrakString($row->pejabat2,';',1);
                    $response->rows[$i]['pejabat2_oleh']=$this->utility->ourEkstrakString($row->pejabat2,';',2);
                
                }
                $response->rows[$i]['pejabat3']=$row->pejabat3;
                $response->rows[$i]['jumlah']=$this->utility->ourFormatNumber($row->jumlah);
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
            $response->rows[$count]['tujuan']='';
            $response->rows[$count]['untuk']='';
            $response->rows[$count]['beban_kegiatan']='';
            $response->rows[$count]['beban_kode']='';
            $response->rows[$count]['pejabat1']='';
            $response->rows[$count]['pejabat2']='';
            $response->rows[$count]['pejabat2_tanggal']='';
            $response->rows[$count]['pejabat2_oleh']='';
            $response->rows[$count]['pejabat3']='';
            $response->rows[$count]['jumlah']='';
            $response->lastNo = 0;	
        }
        
        $response->footer[0]['no']='';
        $response->footer[0]['sb_id']='';        
        $response->footer[0]['jumlah']='<b>'.$this->utility->cekNumericFmt($jumlah).'</b>';

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
    public function GetRecordCount(){
        $query=$this->db->from('tbl_spb');
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
        $this->db->where('nomor',$kode); //buat validasi		
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
        }
        return $isSave;
    }
	
	//insert data
    public function InsertOnDb($data,& $error) {
            //query insert data		
        $this->db->set('nomor',$data['nomor']);
        $this->db->set('tanggal',$this->utility->ourDeFormatSQLDate($data['tanggal']));
        $this->db->set('tujuan',$data['tujuan']);
        $this->db->set('untuk',$data['untuk']);
        $this->db->set('beban_kegiatan',$data['beban_kegiatan']);
        $this->db->set('beban_kode',$data['beban_kode']);
        $this->db->set('jumlah',$this->utility->ourDeFormatNumber2($data['jumlah']));
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
        $this->db->where('nomor',$kode);
        //query insert data		
        $this->db->set('nomor',$data['nomor']);
        $this->db->set('tanggal',$this->utility->ourDeFormatSQLDate($data['tanggal']));
        $this->db->set('tujuan',$data['tujuan']);
        $this->db->set('untuk',$data['untuk']);
        $this->db->set('beban_kegiatan',$data['beban_kegiatan']);
        $this->db->set('beban_kode',$data['beban_kode']);
        $this->db->set('jumlah',$this->utility->ourDeFormatNumber2($data['jumlah']));
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
	
	//hapus data
    public function DeleteOnDb($id){
        $this->db->flush_cache();
        $this->db->where('nomor', $id);
        $result = $this->db->delete('tbl_spb'); 

        $errNo   = $this->db->_error_number();
        $errMess = $this->db->_error_message();
        $error = $errMess;
        //var_dump($errMess);die;
        log_message("error", "Problem Update to : ".$errMess." (".$errNo.")"); 
		//return
		
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
