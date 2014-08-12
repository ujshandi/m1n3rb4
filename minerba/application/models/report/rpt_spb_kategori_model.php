<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *INVISI
*/

class Rpt_spb_kategori_model extends CI_Model
{	
	/**
	* constructor
	*/
    public function __construct()    {
        parent::__construct();
		//$this->CI =& get_instance();
    }
	
    public function easyGrid($purpose=1,$filawal,$filakhir,$filbidang,$filkategori){
        $lastNo = isset($_POST['lastNo']) ? intval($_POST['lastNo']) : 0;
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;  
        $limit = isset($_POST['rows']) ? intval($_POST['rows']) : 10;  

        $count = $this->GetRecordCount($filawal,$filakhir,$filbidang,$filkategori);
        $response = new stdClass();
        $response->total = $count;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'k.kategori';  
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
		   $total_jumlah =0;
          $total_count =0;
        if ($count>0){
            $filtanggal ='';
			$wherebidang ='';
            if($filawal != '' && $filawal != '-1' && $filawal != null) {
                //$this->db->where("s.tanggal between '$filawal' and '$filakhir'");
				$filtanggal = "tanggal between '$filawal' and '$filakhir'";
            }
			if($filbidang != '' && $filbidang != '-1' && $filbidang != null) {
                //$this->db->where("k.kategori_id",$filbidang);
				$wherebidang = "bidang_id = $filbidang";
            }
			
          
            if($filkategori != '' && $filkategori != '-1' && $filkategori != null) {
                $this->db->where("k.kategori_id",$filkategori);
            }
            $this->db->order_by($sort." ".$order );
            if($purpose==1){$this->db->limit($limit,$offset);}
            $this->db->select(" k.kategori, 
count(d.kategori_id) as draft_count, sum(ifnull(d.jumlah,0)) as draft_sum,
count(v.kategori_id) as verifikasi_count, sum(ifnull(v.jumlah,0)) as verifikasi_sum,
count(p.kategori_id) as penguji_count, sum(ifnull(p.jumlah,0)) as penguji_sum,
count(s.kategori_id) as spm_count, sum(ifnull(s.jumlah,0)) as spm_sum,
count(h.kategori_id) as bendahara_count, sum(ifnull(h.jumlah,0)) as bendahara_sum ",false);
            $this->db->from('tbl_spb_kategori k
left join v_spb_draft d on k.kategori_id = d.kategori_id '.($filtanggal<>''?' and d.'.$filtanggal:'').($wherebidang<>''?' and d.'.$wherebidang:'').'
left join v_spb_verifikasi v on k.kategori_id = v.kategori_id '.($filtanggal<>''?' and v.'.$filtanggal:'').($wherebidang<>''?' and v.'.$wherebidang:'').'
left join v_spb_penguji p on k.kategori_id = p.kategori_id '.($filtanggal<>''?' and p.'.$filtanggal:'').($wherebidang<>''?' and p.'.$wherebidang:'').'
left join v_spb_spm s on k.kategori_id = s.kategori_id '.($filtanggal<>''?' and s.'.$filtanggal:'').($wherebidang<>''?' and s.'.$wherebidang:'').'
left join v_spb_bendahara h on k.kategori_id = h.kategori_id '.($filtanggal<>''?' and h.'.$filtanggal:'').($wherebidang<>''?' and h.'.$wherebidang:''),false);
			$this->db->group_by('k.kategori');
            $query = $this->db->get();
          
            $i=0;
            $no =$lastNo;
            foreach ($query->result() as $row)            {
                $no++;
                $response->rows[$i]['no']= $no;
               
               
                $response->rows[$i]['kategori']=$row->kategori;
                     
                
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
				$response->rows[$i]['total_count']=($row->verifikasi_count+$row->penguji_count+$row->spm_count+$row->bendahara_count);
				$response->rows[$i]['total_sum']=($row->verifikasi_sum+$row->penguji_sum+$row->spm_sum+$row->bendahara_sum);
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
				$total_jumlah += $response->rows[$i]['total_sum'];
                $total_count += $response->rows[$i]['total_count'];
                //utk kepentingan export pdf===================
                $pdfdata[] = array($no,$response->rows[$i]['kategori'],$response->rows[$i]['draft_count'],$response->rows[$i]['draft_sum'],$response->rows[$i]['verifikasi_count']);
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
            $response->rows[$count]['total_count']='0';
			$response->rows[$count]['total_sum']='0';
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
		$response->footer[0]['total_sum']=$total_jumlah;//$this->utility->cekNumericFmt(
        $response->footer[0]['total_count']=$total_count;//$this->utility->cekNumericFmt(
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
    public function GetRecordCount($filawal,$filakhir,$filbidang,$filkategori){
        
       /* if($filawal != '' && $filawal != '-1' && $filawal != null) {
            $this->db->where("tanggal between '$filawal' and '$filakhir'");
        }
      */
        if($filkategori != '' && $filkategori != '-1' && $filkategori != null) {
            $this->db->where("kategori_id",$filkategori);
        }
		/*  if($filbidang != '' && $filbidang != '-1' && $filbidang != null) {
            $this->db->where("kategori_id",$filbidang);
        }*/
        $query=$this->db->from('tbl_spb_kategori');
        return $this->db->count_all_results();
        $this->db->free_result();
    }

	
}
?>
