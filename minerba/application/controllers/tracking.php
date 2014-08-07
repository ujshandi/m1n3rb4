<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tracking extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('tracking_model','tracking');
		 $this->load->library("utility");
	}
	
	public function index($err_msg = '',$user_name='')
	{
	
		$data = array(
					'title_page'=>'Tracking SPB',
					'user_name'=>$user_name,
					'err_msg'=>$err_msg,
					'objectId'=>"tracking"
				);
	//	$this->template->set_template('login');
		//$this->template->write_view('content','home',$data,TRUE);
		//$this->template->render();
		$this->load->view('tracking_v',$data);
	}
	
	public function go_tracking($nospb) {
		
		/*if(is_string($response) && $response == 'REQUIRED') {
			$this->index('Nomor SPB harus diisi',$this->input->post('nospb'));
		}else if($response == true) {			
				redirect(base_url().'tracking');
		}else {
			
			$this->index('Nomor SPB belum terdaftar');
			//$this->session->flash_data();
		}*/
		$nospb = $this->utility->HexToAscii($nospb);
		$isAlreadyDraft = false;
		$isAlreadyTandaTerima = false;
		$isAlreadyVerifikasi = false;
		$isAlreadyPejabatPenguji = false;
		$isAlreadySPMBendahara = false;
		
		$data = $this->tracking->cek_spb($nospb);
		$success = $data->num_rows() > 0;
		if ($success){
			$isAlreadyDraft = true;
			$isAlreadyTandaTerima = $data->row()->tanda_terima > 0;
			$isAlreadyVerifikasi = $data->row()->status_verifikasi !='';
			$isAlreadyPejabatPenguji = $data->row()->status_penguji != '';
			$isAlreadySPMBendahara = $data->row()->spm_bendahara != '';
		}
		$rs = '<ol class="progtrckr" data-progtrckr-steps="5">
			<li class="progtrckr-'.($isAlreadyDraft?'done':'todo').'">Draft</li>
			<li class="progtrckr-'.($isAlreadyTandaTerima?'done':'todo').'">Tanda Terima</li>
			<li class="progtrckr-'.($isAlreadyVerifikasi?'done':'todo').'">Verifikasi</li>
			<li class="progtrckr-'.($isAlreadyPejabatPenguji?'done':'todo').'">Pejabat Penguji</li>
			<li class="progtrckr-'.($isAlreadySPMBendahara?'done':'todo').'">Pengajuan SPM/Bendaharawan</li>
		</ol>';
		echo json_encode(array('success'=>$success,'data'=>$rs,'msg'=>'Nomor SPB belum terdaftar'));
		
	}
	

}

/* End of file widget.php */
/* Location: ./application/controllers/widget.php */
