<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'libraries/REST_Controller.php');

class A_account extends REST_Controller {
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library(array('ion_auth'));

		if (!$this->ion_auth->is_admin()) {
			$data = array('status'=>'failed, no permission');
			$this->response($data);
		}
	}

	public function change_password_post()
	{
		$data = array('status'=>'failed');

		if ($this->ion_auth->logged_in()) {
			$identity = $this->session->userdata('identity');
			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change) {
				$data = array('status'=>'success');
			}else
			$data = array('status'=>'wrong old password');
    	}

    	$this->response($data);
	}

	public function delete_get($id)
	{
		if (!$this->ion_auth->is_admin()) {
			$data = array('status'=>'failed, no permission');
			$this->response($data);
		}

		if($this->ion_auth->delete_user($id)){
			$this->session->set_flashdata('message', "Account deleted");
			$data = array('status'=>'success', 'description' => " Account deleted");
			$this->response($data);
		}

	}
}
