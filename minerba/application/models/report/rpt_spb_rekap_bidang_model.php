<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *INVISI
*/

class Rpt_spb_rekap_bidang_model extends CI_Model
{	
	/**
	* constructor
	*/
    public function __construct()    {
        parent::__construct();
		//$this->CI =& get_instance();
    }
	
    public function easyGrid($purpose=1,$filawal,$filakhir,$filbidang,$filkategori,$filproses=null){
        $lastNo = isset($_POST['lastNo']) ? intval($_POST['lastNo']) : 0;
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;  
        $limit = isset($_POST['rows']) ? intval($_POST['rows']) : 10;  

        $count = $this->GetRecordCount($filawal,$filakhir,$filbidang,$filkategori);
        $response = new stdClass();
        $response->total = $count;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'b.bidang';  
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';  
        $offset = ($page-1)*$limit;  
        $pdfdata = array();
          $draft_jumlah =0;
          $draft_count =0;
          $verifikasi_jumlah =0;
          $verifikasi_count =0;
          $penguji_jumlah =0;
          $penguji_count =0;
          $spm_jumlah =0;
          $spm_count =0;
          $bendahara_jumlah =0;
          $bendahara_count =0;
        if ($count>0){
            $filtanggal ='';
            $wherekategori ='';
            if($filawal != '' && $filawal != '-1' && $filawal != null) {
                //$this->db->where("s.tanggal between '$filawal' and '$filakhir'");
				$filtanggal = "tanggal between '$filawal' and '$filakhir'";
            }
			if($filbidang != '' && $filbidang != '-1' && $filbidang != null) {
                $this->db->where("b.bidang_id",$filbidang);
            }
            
            if($filkategori != '' && $filkategori != '-1' && $filkategori != null) {
                //$this->db->where("s.kategori_id",$filkategori);
				$wherekategori = "kategori_id=$filkategori";
            }
            $this->db->order_by($sort." ".$order );
            if($purpose==1){$this->db->limit($limit,$offset);}
            $this->db->select(" b.bidang, 
count(d.bidang_id) as draft_count, sum(ifnull(d.jumlah,0)) as draft_sum,
count(v.bidang_id) as verifikasi_count, sum(ifnull(v.jumlah,0)) as verifikasi_sum,
count(p.bidang_id) as penguji_count, sum(ifnull(p.jumlah,0)) as penguji_sum,
count(s.bidang_id) as spm_count, sum(ifnull(s.jumlah,0)) as spm_sum,
count(h.bidang_id) as bendahara_count, sum(ifnull(h.jumlah,0)) as bendahara_sum ",false);
            $this->db->from('tbl_bidang b
left join v_spb_draft d on b.bidang_id = d.bidang_id '.($filtanggal<>''?' and d.'.$filtanggal:'').($wherekategori<>''?' and d.'.$wherekategori:'').'
left join v_spb_verifikasi v on b.bidang_id = v.bidang_id '.($filtanggal<>''?' and v.'.$filtanggal:'').($wherekategori<>''?' and v.'.$wherekategori:'').'
left join v_spb_penguji p on b.bidang_id = p.bidang_id '.($filtanggal<>''?' and p.'.$filtanggal:'').($wherekategori<>''?' and p.'.$wherekategori:'').'
left join v_spb_spm s on b.bidang_id = s.bidang_id '.($filtanggal<>''?' and s.'.$filtanggal:'').($wherekategori<>''?' and s.'.$wherekategori:'').'
left join v_spb_bendahara h on b.bidang_id = h.bidang_id '.($filtanggal<>''?' and h.'.$filtanggal:'').($wherekategori<>''?' and h.'.$wherekategori:''),false);
			$this->db->group_by('b.bidang');
            $query = $this->db->get();
          
            $i=0;
            $no =$lastNo;
            foreach ($query->result() as $row)            {
                $no++;
                $response->rows[$i]['no']= $no;
               
               
                $response->rows[$i]['bidang']=$row->bidang;
                     
                
                $response->rows[$i]['draft_count']=$row->draft_count; //$this->utility->ourFormatNumber($row->jumlah);
				$response->rows[$i]['draft_sum']=$row->draft_sum;
				$response->rows[$i]['verifikasi_count']=$row->verifikasi_count;
				$response->rows[$i]['verifikasi_sum']=$row->verifikasi_sum;
				$response->rows[$i]['penguji_count']=$row->penguji_count;
				$response->rows[$i]['penguji_sum']=$row->penguji_sum;
				$response->rows[$i]['spm_count']=$row->spm_count;
				$response->rows[$i]['spm_sum']=$row->spm_sum;
				$response->rows[$i]['bendahara_count']=$row->bendahara_count;
				$response->rows[$i]['bendahara_sum']=$row->bendahara_sum;
                $draft_jumlah += $row->draft_sum;
                $draft_count += $row->draft_count;
                $verifikasi_count += $row->verifikasi_count;
                $verifikasi_jumlah += $row->verifikasi_sum;
                $penguji_count += $row->penguji_count;
                $penguji_jumlah += $row->penguji_sum;
                $spm_jumlah += $row->spm_sum;
                $spm_count += $row->spm_count;
                $bendahara_jumlah += $row->bendahara_sum;
                $bendahara_count += $row->bendahara_count;
                //utk kepentingan export pdf===================
                $pdfdata[] = array($no,$response->rows[$i]['bidang'],$response->rows[$i]['draft_count'],$response->rows[$i]['draft_sum'],$response->rows[$i]['verifikasi_count']);
        //============================================================
                $i++;
            } 

            $response->lastNo = $no;
                //$query->free_result();
        }else {
            $response->rows[$count]['no']= '';
            
            $response->rows[$count]['draft_count']='0';
			$response->rows[$count]['draft_sum']='0';
			$response->rows[$count]['verifikasi_count']='0';
			$response->rows[$count]['verifikasi_sum']='0';
			$response->rows[$count]['penguji_count']='0';
			$response->rows[$count]['penguji_sum']='0';
			$response->rows[$count]['spm_count']='0';
			$response->rows[$count]['spm_sum']='0';
			$response->rows[$count]['bendahara_count']='0';
			$response->rows[$count]['bendahara_sum']='0';
            
            $response->lastNo = 0;	
        }
        
        $response->footer[0]['no']='';
        $response->footer[0]['sb_id']='';        
        //$response->footer[0]['jumlah']='<b>'.$jumlah.'</b>';//$this->utility->cekNumericFmt(
        $response->footer[0]['draft_sum']=$draft_jumlah;//$this->utility->cekNumericFmt(
        $response->footer[0]['draft_count']=$draft_count;//$this->utility->cekNumericFmt(
        $response->footer[0]['verifikasi_sum']=$verifikasi_jumlah;//$this->utility->cekNumericFmt(
        $response->footer[0]['verifikasi_count']=$verifikasi_count;//$this->utility->cekNumericFmt(
        $response->footer[0]['penguji_sum']=$penguji_jumlah;//$this->utility->cekNumericFmt(
        $response->footer[0]['penguji_count']=$penguji_count;//$this->utility->cekNumericFmt(
        $response->footer[0]['spm_sum']=$spm_jumlah;//$this->utility->cekNumericFmt(
        $response->footer[0]['spm_count']=$spm_count;//$this->utility->cekNumericFmt(
        $response->footer[0]['bendahara_sum']=$bendahara_jumlah;//$this->utility->cekNumericFmt(
        $response->footer[0]['bendahara_count']=$bendahara_count;//$this->utility->cekNumericFmt(

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
    public function GetRecordCount($filawal,$filakhir,$filbidang,$filkategori,$filproses){
        
       /* if($filawal != '' && $filawal != '-1' && $filawal != null) {
            $this->db->where("tanggal between '$filawal' and '$filakhir'");
        }
      
        if($filkategori != '' && $filkategori != '-1' && $filkategori != null) {
            $this->db->where("kategori_id",$filkategori);
        }*/
		  if($filbidang != '' && $filbidang != '-1' && $filbidang != null) {
            $this->db->where("bidang_id",$filbidang);
        }
        $query=$this->db->from('tbl_bidang');
        return $this->db->count_all_results();
        $this->db->free_result();
    }

	
}
?>
