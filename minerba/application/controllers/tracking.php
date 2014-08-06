<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tracking extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('tracking_model','tracking');
		/* $this->load->model('admin/user_adm_model','users');
		if($this->uri->segment(1) != 'logout') {
			if($this->my_session->is_logged_in()) {
				redirect(base_url().'home');
			}
		} */
	}
	
	public function index($err_msg = '',$user_name='')
	{
		if(false == $user_name) $user_name = '';
		$data = array(
					'title_page'=>'Tracking SPB',
					'user_name'=>$user_name,
					'err_msg'=>$err_msg
				);
	//	$this->template->set_template('login');
		//$this->template->write_view('content','home',$data,TRUE);
		//$this->template->render();
		$this->load->view('tracking_v',$data);
	}
	
	public function go_tracking($form='') {
		//var_dump($this->input->post('txtUser'));die;
		$response = $this->tracking->cek_spb($this->input->post('nospb'));
		//var_dump($response);die;
		if(is_string($response) && $response == 'REQUIRED') {
			$this->index('Nomor SPB harus diisi',$this->input->post('nospb'));
		}else if($response == true) {			
				redirect(base_url().'tracking');
		}else {
			
			$this->index('Nomor SPB belum terdaftar');
			//$this->session->flash_data();
		}
	}
	

}

/* End of file widget.php */
/* Location: ./application/controllers/widget.php */
