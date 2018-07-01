<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
	include 'Auth.php';
	class Fetch extends Auth {
		public function __construct()
		{
			parent::__construct();

			// $this->load->database();
			// $this->load->library(array('ion_auth'));

			if (!$this->ion_auth->logged_in())
			{
				$current = current_url();
				// redirect them to the login page
				redirect('auth/login?durl='.$current, 'refresh');
			}
		}

		public function index()
		{
			echo $this->input->post('phone');
			echo "dfd";
		}

		public function wkwk()
		{
			echo "wkwLan";
			echo current_url();
		}
	}
