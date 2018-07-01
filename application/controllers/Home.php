<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('ion_auth'));

		if (!$this->ion_auth->logged_in()) {
			redirect('/', 'refresh');
		}
	}

	public function index()
	{
		// $this->output->delete_cache();

		$this->data = array(
		    'page'     =>   'home'
		);

		$this->load->view('template/header', $this->data);
		$this->load->view('home');
		$this->load->view('template/footer');
	}
}
