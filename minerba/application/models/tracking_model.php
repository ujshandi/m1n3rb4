<?php
class Tracking_model extends CI_Model{
	
	function __construct(){
		parent::__construct();	
	}

	function cek_spb($nospb){

		
		$this->db->select('u.user_name,u.full_name, u.passwd,u.group_id,u.user_id,g.app_type, u.unit_kerja_e1,u.unit_kerja_e2,l.level,l.level_id');
		$this->db->from('tbl_user u');
		$this->db->join('tbl_group_user g','g.group_id = u.group_id',"left");
		$this->db->join('tbl_group_level l','l.level_id = u.level_id',"left");
		$this->db->where('user_name', $nospb);
		
		//$this->db->where('disabled_date', null);
		//var_dump('kadieu');die;
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			 $row = $query->row_array();		   
			$this->groupId = $row['group_id'];
			//$this->fullName = $row['full_name'];
			$this->userId = $row['user_id'];
			$this->fullName = $row['full_name'];
			$this->level_id= $row['level_id'];
			//var_dump($this->groupId);die;
			$this->create_session($row['user_id'], $row['user_name'], (($row['user_name']=='superadmin')?'':$row['app_type']), $row['full_name'],true,$row['unit_kerja_e1'],$row['unit_kerja_e2'],$row['level'],$row['group_id'],$row['level_id']);
			$query->free_result();
			$data['user_id']=$row['user_id'];
			$data['user_name']=$row['user_name'];
			$data['unit_kerja_e1']=$row['unit_kerja_e1'];
			$data['unit_kerja_e2']=$row['unit_kerja_e2'];
			return $this->insertLoginLog($data);
			}else {
				$query->free_result();
			return FALSE;
		}
	 
		  
	}
	
	
}
?>