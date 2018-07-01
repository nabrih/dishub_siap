<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('ion_auth'));

		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		}

		$this->ion_auth->check_access("account"); //access controll param(nama_menu)
	}

	public function index()
	{
		// set the flash data error message if there is one
		$this->data['message'] = '';

		//list the users
		$this->data['users'] = $this->ion_auth->users()->result();
		foreach ($this->data['users'] as $k => $user)
		{
			$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
		}

		// $this->data['acl'] = $this->ion_auth->allowed_menu();
		$this->data_head['page'] = "account";

		$this->load->view('template/header', $this->data_head);
		$this->load->view('account/v_data_accounts', $this->data);
		$this->load->view('template/footer');
	}

	public function test()
	{
		echo "access";
	}
}
