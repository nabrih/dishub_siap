<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banner extends CI_Controller {
	public function __construct()
	{
		parent::__construct();

		$this->load->library(array('ion_auth')); //load auth library
		$this->ion_auth->check_access("banner"); //access controll
	}

	public function index()
	{
		
		$data = array(
		    'title'     =>   'Admin Web',
		);
		echo "test page banner";
	}

	private function csrf()
	{
		$this->data['csrf']  = array(
	        'name' => $this->security->get_csrf_token_name(),
	        'hash' => $this->security->get_csrf_hash()
		);
	}

}
