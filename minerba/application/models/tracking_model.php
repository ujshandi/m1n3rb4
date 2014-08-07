<?php
class Tracking_model extends CI_Model{
	
	function __construct(){
		parent::__construct();	
	}

	function cek_spb($nospb){

		
		$this->db->select("ifnull(status_verifikasi,'') status_verifikasi, ifnull(status_penguji,'') status_penguji,  
ifnull(spm_bendahara,'') spm_bendahara,ifnull(d.detail_id,0) tanda_terima",false);
		$this->db->from("tbl_spb s left join tbl_tanda_terima_detail d on s.spb_id = d.spb_id",false);
		
		$this->db->where('s.nomor', $nospb);
	
		$query = $this->db->get();
        return $query;
		  
	}
	
	
}
?>