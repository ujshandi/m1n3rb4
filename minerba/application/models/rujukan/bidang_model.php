<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *INVISI
*/

class Bidang_model extends CI_Model
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
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'bidang';  
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';  
        $offset = ($page-1)*$limit;  
        $pdfdata = array();
        if ($count>0){
            $this->db->order_by($sort." ".$order );
            if($purpose==1){$this->db->limit($limit,$offset);}
            $this->db->select("*",false);
            $this->db->from('tbl_bidang');
            $query = $this->db->get();

            $i=0;
            $no =$lastNo;
            foreach ($query->result() as $row)            {
                $no++;
                $response->rows[$i]['no']= $no;
                $response->rows[$i]['bidang_id']=$row->bidang_id;
                $response->rows[$i]['bidang']=$row->bidang;
                

                //utk kepentingan export pdf===================
                $pdfdata[] = array($no,$response->rows[$i]['bidang']);
        //============================================================
                $i++;
            } 

            $response->lastNo = $no;
                //$query->free_result();
        }else {
            $response->rows[$count]['no']= '';
            $response->rows[$count]['sb_id']= '';
            $response->rows[$count]['bidang']='';
            
            $response->lastNo = 0;	
        }

        if ($purpose==1) //grid normal
            return json_encode($response);
        else if($purpose==2){//pdf
            return $pdfdata;
        }
        else if($purpose==3){//to excel
                //tambahkan header kolom
            $colHeaders = array("No.","Bidang");		
            //var_dump($query->result());die;
            to_excel($query,"Bidang",$colHeaders);
        }
        else if ($purpose==4) { //WEB SERVICE
            return $response;
        }

    }
	
	//jumlah data record buat paging
    public function GetRecordCount(){
        $query=$this->db->from('tbl_bidang');
        return $this->db->count_all_results();
        $this->db->free_result();
    }

    public function isExistKode($kode=null){	
        if ($kode!=null)//utk update
            $this->db->where('bidang',$kode); //buat validasi

        $this->db->select('*');
        $this->db->from('tbl_bidang');

        $query = $this->db->get();
        $rs = $query->num_rows() ;		
        $query->free_result();
        return ($rs>0);
    }
	
    public function isSaveDelete($kode){			
       /* $this->db->where('bidang',$kode); //buat validasi		
        $this->db->select('*');
        $this->db->from('tbl_kegiatan');

        $query = $this->db->get();
        $rs = $query->num_rows() ;		
        $query->free_result();
        $isSave = ($rs==0);
        if ($isSave){
            $this->db->flush_cache();
            $this->db->where('bidang',$kode); //buat validasi		
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
        $this->db->set('bidang',$data['bidang']);
        

        $result = $this->db->insert('tbl_bidang');
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
        $this->db->where('bidang_id',$kode);
        //query insert data		
        $this->db->set('bidang',$data['bidang']);
        
        $result=$this->db->update('tbl_bidang');

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
        $this->db->where('bidang_id', $id);
        $result = $this->db->delete('tbl_bidang'); 

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
	
    public function getListBidang($objectId="",$isAjax=false){		
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from('tbl_bidang');
        $this->db->order_by('bidang');

        $que = $this->db->get();

        $out = '<select name="bidang_id" id="bidang_id'.$objectId.'" class="easyui-validatebox" required="true">';

        foreach($que->result() as $r){
                $out .= '<option value="'.$r->bidang_id.'">'.$r->bidang.'</option>';
        }

        $out .= '</select>';
		if ($isAjax){
			echo $out;
		}
		else {
			return $out;
		}
    }
    
    public function getListBidangFilter($objectId="",$isAjax=false){		
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from('tbl_bidang');
        $this->db->order_by('bidang');

        $que = $this->db->get();

        $out = '<select name="filter_bidang_id" id="filter_bidang_id'.$objectId.'" class="easyui-validatebox" required="true">';
        $out .= '<option value="-1">Semua Bidang</option>';    
        foreach($que->result() as $r){
                $out .= '<option value="'.$r->bidang_id.'">'.$r->bidang.'</option>';
        }

        $out .= '</select>';
		if ($isAjax){
			echo $out;
		}
		else {
			return $out;
		}
    }
	
}
?>
