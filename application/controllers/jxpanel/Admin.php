<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('ion_auth'));

		if (!$this->ion_auth->logged_in() && !$this->ion_auth->is_admin()) {
			redirect('auth/login', 'refresh');
		}
	}

	public function index()
	{
		$this->output->delete_cache();

		$data = array(
		    'title'     =>   'Admin Web',
		);

		$this->load->view('_template_admin/head_adm', $data);
		$this->load->view('_template_admin/content_adm');
		$this->load->view('_template_admin/footer_adm');
	}
}
