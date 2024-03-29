<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *INVISI
*/

class Rpt_spm_bendahara_model extends CI_Model
{	
	/**
	* constructor
	*/
    public function __construct()    {
        parent::__construct();
		//$this->CI =& get_instance();
    }
	
    public function easyGrid($purpose=1,$tipereport,$filawal,$filakhir,$filbidang,$filkategori,$filNomor,$tipeperiode){
        $lastNo = isset($_POST['lastNo']) ? intval($_POST['lastNo']) : 0;
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;  
        $limit = isset($_POST['rows']) ? intval($_POST['rows']) : 10;  

        $count = $this->GetRecordCount($tipereport,$filawal,$filakhir,$filbidang,$filkategori,$filNomor,$tipeperiode);
        $response = new stdClass();
        $response->total = $count;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'tujuan';  
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';  
        $offset = ($page-1)*$limit;  
        $pdfdata = array();
          $jumlah =0;
		  $viewname = '';
        if ($count>0){
            switch ($tipereport){
                
                case "spm" : 
					$viewname = 'v_spb_spm s'		;
                    /*$this->db->where("((status_penguji is not null) or (status_penguji<>''))");
                    $this->db->where("spm_bendahara = 'spm' ");*/
                break;
                case "bendahara" : 
					$viewname = 'v_spb_bendahara s'		;
                    /*$this->db->where("((status_penguji is not null) or (status_penguji<>''))");
                    $this->db->where("spm_bendahara = 'bendahara' "); */
                break;
                
            }
		switch ($tipeperiode) {
			case "1" : //verifikasi
				$fieldTgl = 'right(status_verifikasi,10)';
			break;
			case "2" : //penguji
				$fieldTgl = 'right(status_penguji,10)';
			break;
			default : $fieldTgl = 's.tanggal';
		}
            if($filawal != '' && $filawal != '-1' && $filawal != null) {
                $this->db->where($fieldTgl." between '$filawal' and '$filakhir'");
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
            $this->db->select("* ",false);
			$this->db->from($viewname,false);
            //$this->db->from('tbl_spb s left join tbl_bidang b on b.bidang_id=s.bidang_id'
              //      . ' left join tbl_spb_kategori k on k.kategori_id = s.kategori_id',false);
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
                //$response->rows[$i]['keterangan']=$row->keterangan;
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
                $response->rows[$i]['jumlah']=$row->jumlah; //$this->utility->ourFormatNumber($row->jumlah);
                $jumlah += $row->jumlah;
                //utk kepentingan export pdf===================
                $pdfdata[] = array($no,$response->rows[$i]['tanggal'],$response->rows[$i]['status_verifikasi_tanggal'],$response->rows[$i]['status_penguji_tanggal'],$response->rows[$i]['nomor'],$this->utility->ourFormatNumber($response->rows[$i]['jumlah']),$response->rows[$i]['bidang'],$response->rows[$i]['kategori'],$response->rows[$i]['untuk'],$response->rows[$i]['tujuan'],$response->rows[$i]['kegiatan']);
        //============================================================
			//utk kepentingan export excel ==========================
				$row->spb_id = $no;
				$row->tgl_verifikasi = $response->rows[$i]['status_verifikasi_tanggal'];
				$row->tgl_penguji = $response->rows[$i]['status_penguji_tanggal'];
				$row->bidang = $response->rows[$i]['bidang'];
				$row->kegiatan = $response->rows[$i]['kegiatan'];
				
				unset($row->log_insert);
				unset($row->log_update);
				unset($row->kategori_id);
				unset($row->bidang_id);
				unset($row->status_penguji);
				unset($row->status_spm);
				unset($row->status_bendahara);
				unset($row->status_verifikasi);
				unset($row->spm_bendahara);
				unset($row->beban_kode);
				unset($row->beban_kegiatan);
				
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
            $response->rows[$count]['keterangan']='';
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
            $colHeaders =  array('No.','Nomor','Tgl.Input','Kepada','Untuk Pembayaran','Jumlah','Bidang','Kategori','Tgl.Verifikasi','Tgl.Persetujuan','Kegiatan');		
            //var_dump($query->result());die;
            to_excel($query,"SPBY".strtoupper($tipereport),$colHeaders);
        }
        else if ($purpose==4) { //WEB SERVICE
            return $response;
        }

    }
	
	//jumlah data record buat paging
    public function GetRecordCount($tipereport,$filawal,$filakhir,$filbidang,$filkategori,$filNomor,$tipeperiode){
        switch ($tipereport){
                
                case "spm" : 
					$viewname = 'v_spb_spm s'		;
                    /*$this->db->where("((status_penguji is not null) or (status_penguji<>''))");
                    $this->db->where("spm_bendahara = 'spm' ");*/
                break;
                case "bendahara" : 
					$viewname = 'v_spb_bendahara s'		;
                    /*$this->db->where("((status_penguji is not null) or (status_penguji<>''))");
                    $this->db->where("spm_bendahara = 'bendahara' "); */
                break;
                
            }
		switch ($tipeperiode) {
			case "1" : //verifikasi
				$fieldTgl = 'right(status_verifikasi,10)';
			break;
			case "2" : //penguji
				$fieldTgl = 'right(status_penguji,10)';
			break;
			default : $fieldTgl = 'tanggal';
		}	
        if($filawal != '' && $filawal != '-1' && $filawal != null) {
            $this->db->where($fieldTgl." between '$filawal' and '$filakhir'");
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
        $query=$this->db->from($viewname,false);
        return $this->db->count_all_results();
        $this->db->free_result();
    }

	
}
?>
