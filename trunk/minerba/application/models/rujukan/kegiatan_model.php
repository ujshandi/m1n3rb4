<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *INVISI
*/

class Kegiatan_model extends CI_Model
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
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'nama';  
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';  
        $offset = ($page-1)*$limit;  
        $pdfdata = array();
        if ($count>0){
            $this->db->order_by($sort." ".$order );
            if($purpose==1){$this->db->limit($limit,$offset);}
            $this->db->select("k.*,b.bidang",false);
            $this->db->from('tbl_kegiatan k inner join tbl_bidang b on k.bidang_id=b.bidang_id	',false);
            $query = $this->db->get();

            $i=0;
            $no =$lastNo;
            foreach ($query->result() as $row)            {
                $no++;
                $response->rows[$i]['no']= $no;
                $response->rows[$i]['kegiatan_id']=$row->kegiatan_id;
                $response->rows[$i]['tahun']=$row->tahun;
                $response->rows[$i]['kode']=$row->kode;
                $response->rows[$i]['nama']=$row->nama;
                $response->rows[$i]['bidang_id']=$row->bidang_id;
                $response->rows[$i]['bidang']=$row->bidang;
                $response->rows[$i]['pagu']=$this->utility->ourFormatNumber($row->pagu);
                

                //utk kepentingan export pdf===================
                $pdfdata[] = array($no,$response->rows[$i]['kode'],$response->rows[$i]['nama'],
                $response->rows[$i]['bidang'],$response->rows[$i]['pagu']);
        //============================================================
                $i++;
            } 

            $response->lastNo = $no;
                //$query->free_result();
        }else {
            $response->rows[$count]['no']= '';
            $response->rows[$count]['tahun']= '';
            $response->rows[$count]['kegiatan_id']= '';
            $response->rows[$count]['kode']='';
            $response->rows[$count]['nama']='';
            $response->rows[$count]['bidang']='';
            $response->rows[$count]['bidang_id']='';
            $response->rows[$count]['pagu']='';
            
            $response->lastNo = 0;	
        }

        if ($purpose==1) //grid normal
            return json_encode($response);
        else if($purpose==2){//pdf
            return $pdfdata;
        }
        else if($purpose==3){//to excel
                //tambahkan header kolom
            $colHeaders = array("No.","kegiatan");		
            //var_dump($query->result());die;
            to_excel($query,"kegiatan",$colHeaders);
        }
        else if ($purpose==4) { //WEB SERVICE
            return $response;
        }

    }
	
	//jumlah data record buat paging
    public function GetRecordCount(){
        $query=$this->db->from('tbl_kegiatan');
        return $this->db->count_all_results();
        $this->db->free_result();
    }

    public function isExistKode($kode=null){	
        if ($kode!=null)//utk update
            $this->db->where('kode',$kode); //buat validasi

        $this->db->select('*');
        $this->db->from('tbl_kegiatan');

        $query = $this->db->get();
        $rs = $query->num_rows() ;		
        $query->free_result();
        return ($rs>0);
    }
	
    public function isSaveDelete($kode){			
       /* $this->db->where('kegiatan',$kode); //buat validasi		
        $this->db->select('*');
        $this->db->from('tbl_kegiatan');

        $query = $this->db->get();
        $rs = $query->num_rows() ;		
        $query->free_result();
        $isSave = ($rs==0);
        if ($isSave){
            $this->db->flush_cache();
            $this->db->where('kegiatan',$kode); //buat validasi		
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
        $this->db->set('kode',$data['kode']);
        $this->db->set('nama',$data['nama']);
        $this->db->set('bidang_id',$data['bidang_id']);
        $this->db->set('tahun',$data['tahun']);
        $this->db->set('pagu',$this->utility->ourDeFormatNumber2($data['pagu']));

        $result = $this->db->insert('tbl_kegiatan');
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
        $this->db->where('kegiatan_id',$kode);
        $this->db->set('kode',$data['kode']);
        $this->db->set('nama',$data['nama']);
        $this->db->set('bidang_id',$data['bidang_id']);
        $this->db->set('tahun',$data['tahun']);
        $this->db->set('pagu',$this->utility->ourDeFormatNumber2($data['pagu']));
        //query insert data		
        
        
        $result=$this->db->update('tbl_kegiatan');

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
        $this->db->where('kegiatan_id', $id);
        $result = $this->db->delete('tbl_kegiatan'); 

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
	
    public function getListkegiatan($objectId=""){		
        $this->db->flush_cache();
        $this->db->select('kegiatan');
        $this->db->from('tbl_kegiatan');
        $this->db->order_by('kegiatan');

        $que = $this->db->get();

        $out = '<select name="kegiatan" id="kegiatan'.$objectId.'" class="easyui-validatebox" required="true">';

        foreach($que->result() as $r){
                $out .= '<option value="'.$r->kegiatan_id.'">'.$r->kegiatan.'</option>';
        }

        $out .= '</select>';

        echo $out;
    }
	
}
?>
