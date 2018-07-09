<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Auth extends CI_Controller
{
	private $after_login_as_admin_link="/home";

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth', 'form_validation'));
		$this->load->helper(array('url', 'language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), 
			$this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth', 'indonesian');
	}

	public function test()
	{
		$this->load->view('login_form');
	}

	/**
	 * Redirect if needed, otherwise display the user list
	 */
	public function index()
	{

		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('auth/login', 'refresh');
			// echo "wew"
		}
		else if (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			return show_error('You must be an administrator to view this page.');
		}
		else
		{
			// // set the flash data error message if there is one
			// $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			// //list the users
			// $this->data['users'] = $this->ion_auth->users()->result();
			// foreach ($this->data['users'] as $k => $user)
			// {
			// 	$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
			// }

			// // $this->_render_page('auth/index', $this->data);
			redirect($this->after_login_as_admin_link, 'refresh');
		}
	}

	/**
	 * Log the user in
	 */
	public function login()
	{
		if ($this->ion_auth->logged_in())
		{
			redirect($this->after_login_as_admin_link, 'refresh');
		}

		$this->data['title'] = $this->lang->line('login_heading');

		// validate form input
		$this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
		$this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');

		if ($this->form_validation->run() === TRUE)
		{
			// check to see if the user is logging in
			// check for "remember me"
			// $remember = (bool)$this->input->post('remember');
			$remember = FALSE;

			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
			{
				//if the login is successful
				//redirect them back to the home page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect($this->after_login_as_admin_link, 'refresh');
			}
			else
			{
				// if the login was un-successful
				// redirect them back to the login page
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		}
		else
		{
			// the user is not logging in so display the login page
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->load->view('login_form', $this->data);
		}
	}

	/**
	 * Log the user out
	 */
	public function logout()
	{
		$this->data['title'] = "Logout";

		// log the user out
		$logout = $this->ion_auth->logout();

		// redirect them to the login page
		$this->session->set_flashdata('message', $this->ion_auth->messages());
		redirect('/', 'refresh');
	}

	/**
	 * Change password
	 */
	public function change_password()
	{
		$this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
		$this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}

		$user = $this->ion_auth->user()->row();

		if ($this->form_validation->run() === FALSE)
		{
			// display the form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');

			$this->data['csrf']  = array(
			        'name' => $this->security->get_csrf_token_name(),
			        'hash' => $this->security->get_csrf_hash()
			);

			$this->data['user_id2'] = $user->id;

			$this->data['old_password'] = array(
				'name' => 'old',
				'id' => 'old',
				'type' => 'password',
			);
			$this->data['new_password'] = array(
				'name' => 'new',
				'id' => 'new',
				'type' => 'password',
				'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
			);
			$this->data['new_password_confirm'] = array(
				'name' => 'new_confirm',
				'id' => 'new_confirm',
				'type' => 'password',
				'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
			);
			$this->data['user_id'] = array(
				'name' => 'user_id',
				'id' => 'user_id',
				'type' => 'hidden',
				'value' => $user->id,
			);

			// render
			// $this->_render_page('auth/change_password', $this->data);
			$this->load->view('auth/change_password', $this->data);
		}
		else
		{
			$identity = $this->session->userdata('identity');

			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change)
			{
				//if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				$this->logout();
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/change_password', 'refresh');
			}
		}
	}

	/**
	 * Forgot password
	 */
	public function forgot_password()
	{
		// setting validation rules by checking whether identity is username or email
		if ($this->config->item('identity', 'ion_auth') != 'email')
		{
			$this->form_validation->set_rules('identity', $this->lang->line('forgot_password_identity_label'), 'required');
		}
		else
		{
			$this->form_validation->set_rules('identity', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
		}


		if ($this->form_validation->run() === FALSE)
		{
			$this->data['type'] = $this->config->item('identity', 'ion_auth');
			// setup the input
			$this->data['identity'] = array('name' => 'identity',
				'id' => 'identity',
			);

			if ($this->config->item('identity', 'ion_auth') != 'email')
			{
				$this->data['identity_label'] = $this->lang->line('forgot_password_identity_label');
			}
			else
			{
				$this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
			}

			// set any errors and display the form
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page('auth/forgot_password', $this->data);
		}
		else
		{
			$identity_column = $this->config->item('identity', 'ion_auth');
			$identity = $this->ion_auth->where($identity_column, $this->input->post('identity'))->users()->row();

			if (empty($identity))
			{

				if ($this->config->item('identity', 'ion_auth') != 'email')
				{
					$this->ion_auth->set_error('forgot_password_identity_not_found');
				}
				else
				{
					$this->ion_auth->set_error('forgot_password_email_not_found');
				}

				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("auth/forgot_password", 'refresh');
			}

			// run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

			if ($forgotten)
			{
				// if there were no errors
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("auth/forgot_password", 'refresh');
			}
		}
	}

	/**
	 * Reset password - final step for forgotten password
	 *
	 * @param string|null $code The reset code
	 */
	public function reset_password($code = NULL)
	{
		if (!$code)
		{
			show_404();
		}

		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user)
		{
			// if the code is valid then display the password reset form

			$this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

			if ($this->form_validation->run() === FALSE)
			{
				// display the form

				// set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
				$this->data['new_password'] = array(
					'name' => 'new',
					'id' => 'new',
					'type' => 'password',
					'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
				);
				$this->data['new_password_confirm'] = array(
					'name' => 'new_confirm',
					'id' => 'new_confirm',
					'type' => 'password',
					'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
				);
				$this->data['user_id'] = array(
					'name' => 'user_id',
					'id' => 'user_id',
					'type' => 'hidden',
					'value' => $user->id,
				);
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;

				// render
				$this->_render_page('auth/reset_password', $this->data);
			}
			else
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
				{

					// something fishy might be up
					$this->ion_auth->clear_forgotten_password_code($code);

					show_error($this->lang->line('error_csrf'));

				}
				else
				{
					// finally change the password
					$identity = $user->{$this->config->item('identity', 'ion_auth')};

					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

					if ($change)
					{
						// if the password was successfully changed
						$this->session->set_flashdata('message', $this->ion_auth->messages());
						redirect("auth/login", 'refresh');
					}
					else
					{
						$this->session->set_flashdata('message', $this->ion_auth->errors());
						redirect('auth/reset_password/' . $code, 'refresh');
					}
				}
			}
		}
		else
		{
			// if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	/**
	 * Activate the user
	 *
	 * @param int         $id   The user ID
	 * @param string|bool $code The activation code
	 */
	public function activate($id, $code = FALSE)
	{
		if ($code !== FALSE)
		{
			$activation = $this->ion_auth->activate($id, $code);
		}
		else if ($this->ion_auth->is_admin())
		{
			$activation = $this->ion_auth->activate($id);
		}

		if ($activation)
		{
			// redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("account", 'refresh');
		}
		else
		{
			// redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	/**
	 * Deactivate the user
	 *
	 * @param int|string|null $id The user ID
	 */
	public function deactivate($id = NULL)
	{
		if (!$this->ion_auth->is_admin())
		{
			// redirect them to the home page because they must be an administrator to view this
			return show_error('You must be an administrator to view this page.');
		}

		$id = (int)$id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
		$this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

		if ($this->form_validation->run() === FALSE)
		{
			// insert csrf check
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$this->data['user'] = $this->ion_auth->user($id)->row();

			
			$this->load->view('template/header');
			$this->_render_page('auth/deactivate_user', $this->data);
			$this->load->view('template/footer');
		}
		else
		{
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes')
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
				{
					return show_error($this->lang->line('error_csrf'));
				}

				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
				{
					$this->ion_auth->deactivate($id);
					$this->session->set_flashdata('message', "deactivate successfully");
				}else
					$this->session->set_flashdata('message', "you must an administrator");
			}

			// redirect them back to the auth page
			redirect('account', 'refresh');
		}
	}

	/*
	register new user as member
	tambahan
	*/

	public function register()
	{
		$this->output->delete_cache();

		$data = array(
		    'title'     =>   'Register - Jaya Ekspress Transindo',
		    'home'   =>   1,
		    'navbar' => 1
		);

		$this->load->view('_template/head', $data);
		$this->load->view('auth/register');
		$this->load->view('_template/footer');
	}

	/**
	 * Create a new user
	 */
	public function create_user()
	{
		$this->data['title'] = $this->lang->line('create_user_heading');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		$tables = $this->config->item('tables', 'ion_auth');
		$identity_column = $this->config->item('identity', 'ion_auth');
		$this->data['identity_column'] = $identity_column;

		// validate form input
		// $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
		// $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'trim|required');
		$this->form_validation->set_rules('full_name', "Full name harus diisi", 'trim|required');
		if ($identity_column !== 'email')
		{
			$this->form_validation->set_rules('identity', $this->lang->line('create_user_validation_identity_label'), 'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']');
			$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email');
		}
		else
		{
			$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
		}
		$this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
		$this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'trim');
		// additional
		$this->form_validation->set_rules('nip', 'NIP', 'trim');
		$this->form_validation->set_rules('nrk', 'NRK', 'trim');
		$this->form_validation->set_rules('lokasi', 'Lokasi', 'trim');
		// additional end
		$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

		if ($this->form_validation->run() === TRUE)
		{
			$email = strtolower($this->input->post('email'));
			$identity = ($identity_column === 'email') ? $email : $this->input->post('identity');
			$password = $this->input->post('password');

			$additional_data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'full_name' => $this->input->post('full_name'),
				'company' => $this->input->post('company'),
				'phone' => $this->input->post('phone'),
				'nip' => $this->input->post('nip'),
				'nrk' => $this->input->post('nrk'),
				'lokasi' => $this->input->post('lokasi'),
			);
		}
		if ($this->form_validation->run() === TRUE && $this->ion_auth->register($identity, $password, $email, $additional_data))
		{
			// check to see if we are creating the user
			// redirect them back to the admin page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("account", 'refresh');
		}
		else
		{
			// display the create user form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['first_name'] = array(
				'name' => 'first_name',
				'id' => 'first_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('first_name'),
			);
			$this->data['last_name'] = array(
				'name' => 'last_name',
				'id' => 'last_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('last_name'),
			);
			$this->data['full_name'] = array(
				'name' => 'full_name',
				'id' => 'full_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('full_name'),
			);
			$this->data['identity'] = array(
				'name' => 'identity',
				'id' => 'identity',
				'type' => 'text',
				'value' => $this->form_validation->set_value('identity'),
			);
			$this->data['email'] = array(
				'name' => 'email',
				'id' => 'email',
				'type' => 'text',
				'value' => $this->form_validation->set_value('email'),
			);
			$this->data['company'] = array(
				'name' => 'company',
				'id' => 'company',
				'type' => 'text',
				'value' => $this->form_validation->set_value('company'),
			);
			// additional
			$this->data['nip'] = array(
				'name' => 'nip',
				'id' => 'nip',
				'type' => 'text',
				'value' => $this->form_validation->set_value('nip'),
			);
			$this->data['nrk'] = array(
				'name' => 'nrk',
				'id' => 'nrk',
				'type' => 'text',
				'value' => $this->form_validation->set_value('nrk'),
			);

			$this->data['lokasi'] = array(
				'name' => 'lokasi',
				'id' => 'lokasi',
				'selected' => $this->form_validation->set_value('lokasi'),
				'options' => array('PG' => 'PULO GADUNG', 'UM' => 'UJUNG MENTENG', 'CL' => 'CILINCING', 'KA' => 'KEDAUNG ANGKE'),
				'class' => 'form-control',
				'style' => 'width: 175px;'
			);
			$this->data['status_kepegawaian'] = array(
				'name' => 'status_kepegawaian',
				'id' => 'status_kepegawaian',
				'selected' => $this->form_validation->set_value('status_kepegawaian'),
				'options' => array('PNS' => 'PNS', 'NON PNS' => 'NON PNS'),
				'class' => 'form-control',
				'style' => 'width: 175px;'
			);

			// additional end
			$this->data['phone'] = array(
				'name' => 'phone',
				'id' => 'phone',
				'type' => 'text',
				'value' => $this->form_validation->set_value('phone'),
			);
			$this->data['password'] = array(
				'name' => 'password',
				'id' => 'password',
				'type' => 'password',
				'value' => $this->form_validation->set_value('password'),
			);
			$this->data['password_confirm'] = array(
				'name' => 'password_confirm',
				'id' => 'password_confirm',
				'type' => 'password',
				'value' => $this->form_validation->set_value('password_confirm'),
			);

			$this->load->view('template/header');
			$this->_render_page('auth/create_user', $this->data);
			$this->load->view('template/footer');
		}
	}

	/**
	 * Edit a user
	 *
	 * @param int|string $id
	 */
	public function edit_user($id)
	{
		$this->data['title'] = $this->lang->line('edit_user_heading');

		if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id)))
		{
			redirect('auth', 'refresh');
		}

		$user = $this->ion_auth->user($id)->row();
		$groups = $this->ion_auth->groups()->result_array();
		$currentGroups = $this->ion_auth->get_users_groups($id)->result();

		// validate form input
		// $this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'trim|required');
		// $this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'trim|required');
		$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'trim|required');
		$this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'trim');
		// additional
		$this->form_validation->set_rules('nip', 'NIP', 'trim');
		$this->form_validation->set_rules('nrk', 'NRK', 'trim');
		$this->form_validation->set_rules('lokasi', 'Lokasi', 'trim');
		// additional end
		// update proses cek
		if (isset($_POST) && !empty($_POST))
		{
			// do we have a valid request?
			if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
			{
				show_error($this->lang->line('error_csrf'));
			}

			// update the password if it was posted
			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
			}

			if ($this->form_validation->run() === TRUE)
			{
				$data = array(
					'first_name' => $this->input->post('first_name'),
					'last_name' => $this->input->post('last_name'),
					'full_name' => $this->input->post('full_name'),
					'company' => $this->input->post('company'),
					'phone' => $this->input->post('phone'),
					'nip' => $this->input->post('nip'),
					'nrk' => $this->input->post('nrk'),
					'lokasi' => $this->input->post('lokasi'),
				);

				// update the password if it was posted
				if ($this->input->post('password'))
				{
					$data['password'] = $this->input->post('password');
				}

				// Only allow updating groups if user is admin
				if ($this->ion_auth->is_admin())
				{
					// Update the groups user belongs to
					$groupData = $this->input->post('groups');

					if (isset($groupData) && !empty($groupData))
					{

						$this->ion_auth->remove_from_group('', $id);

						foreach ($groupData as $grp)
						{
							$this->ion_auth->add_to_group($grp, $id);
						}

					}
				}

				// check to see if we are updating the user
				if ($this->ion_auth->update($user->id, $data))
				{
					// redirect them back to the admin page if admin, or to the base url if non admin
					$this->session->set_flashdata('message', $this->ion_auth->messages());
					if ($this->ion_auth->is_admin())
					{
						redirect('account', 'refresh');
					}
					else
					{
						redirect('/', 'refresh');
					}

				}
				else
				{
					// redirect them back to the admin page if admin, or to the base url if non admin
					$this->session->set_flashdata('message', $this->ion_auth->errors());
					if ($this->ion_auth->is_admin())
					{
						redirect('auth', 'refresh');
					}
					else
					{
						redirect('/', 'refresh');
					}

				}

			}
		}

		// display the edit user form
		$this->data['csrf'] = $this->_get_csrf_nonce();

		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// pass the user to the view
		$this->data['user'] = $user;
		$this->data['groups'] = $groups;
		$this->data['currentGroups'] = $currentGroups;

		$this->data['first_name'] = array(
			'name'  => 'first_name',
			'id'    => 'first_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('first_name', $user->first_name),
		);
		$this->data['last_name'] = array(
			'name'  => 'last_name',
			'id'    => 'last_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('last_name', $user->last_name),
		);
		$this->data['full_name'] = array(
			'name'  => 'full_name',
			'id'    => 'full_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('full_name', $user->full_name),
		);
		$this->data['company'] = array(
			'name'  => 'company',
			'id'    => 'company',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('company', $user->company),
		);
		// additional
		$this->data['nip'] = array(
			'name' => 'nip',
			'id' => 'nip',
			'type' => 'text',
			'value' => $this->form_validation->set_value('nip', $user->nip),
		);
		$this->data['nrk'] = array(
			'name' => 'nrk',
			'id' => 'nrk',
			'type' => 'text',
			'value' => $this->form_validation->set_value('nrk', $user->nrk),
		);
		// $this->data['lokasi'] = array(
		// 	'name' => 'lokasi',
		// 	'id' => 'lokasi',
		// 	'type' => 'text',
		// 	'value' => $this->form_validation->set_value('lokasi', $user->lokasi),
		// );
		$this->data['lokasi'] = array(
			'name' => 'status',
			'id' => 'status',
			'selected' =>  $this->form_validation->set_value('lokasi', $user->lokasi),
			'options' => array('PG' => 'PULO GADUNG', 'UM' => 'UJUNG MENTENG', 'CL' => 'CILINCING', 'KA' => 'KEDAUNG ANGKE'),
			'class' => 'form-control',
			'style' => 'width: 175px;'
		);
		// additional end
		$this->data['phone'] = array(
			'name'  => 'phone',
			'id'    => 'phone',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('phone', $user->phone),
		);
		$this->data['password'] = array(
			'name' => 'password',
			'id'   => 'password',
			'type' => 'password'
		);
		$this->data['password_confirm'] = array(
			'name' => 'password_confirm',
			'id'   => 'password_confirm',
			'type' => 'password'
		);

		$this->load->view('template/header');
		$this->_render_page('auth/edit_user', $this->data);
		$this->load->view('template/footer');
		
	}

	/**
	* Display all menu
	* @author nabrih
	* Admin only
	*/
	public function menus()
	{
		if (!$this->ion_auth->logged_in() && !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		$this->output->delete_cache();
		$this->load->model('acl_model');

		$menus = $this->acl_model->menus();
		$this->data['menus'] = $menus->result_array();

		$this->data_head['page'] = "menus";

		$this->load->view('template/header', $this->data_head);
		$this->_render_page('account/v_data_menus', $this->data);
		$this->load->view('template/footer');
	}
	/**
	* Editing menu
	* @author nabrih
	* Admin only
	*/
	public function create_menu()
	{
		$this->data['title'] = "Create Menu item";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		// validate form input
		$this->form_validation->set_rules('menu_name', 'Name is required', 'trim|required|alpha_dash');
		$this->form_validation->set_rules('menu_desc', 'Description is required', 'trim|required');
		$this->form_validation->set_rules('url', 'URL is required', 'trim|required');
		$this->form_validation->set_rules('icon_name', 'Icon Name is required', 'trim|required');
		$this->form_validation->set_rules('status', 'Status is required', 'trim|required|alpha_dash');

		if ($this->form_validation->run() === TRUE)
		{
			$this->load->model('acl_model');
			$data_menu = array(
				'menu_name'		=>	$this->input->post('menu_name'),
				'menu_desc'		=>	$this->input->post('menu_desc'),
				'url'			=>	$this->input->post('url'),
				'icon_name'		=>	$this->input->post('icon_name'),
				'status'		=>	$this->input->post('status')
			);
			$menu_id = $this->acl_model->create_menu($data_menu);
			if ($menu_id) {
				$this->session->set_flashdata('message', 'Menu created successfully');

				redirect('auth/menus', 'refresh');
			}
		}
		else
		{
			// display the create group form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['menu_name'] = array(
				'name'  => 'menu_name',
				'id'    => 'menu_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('menu_name'),
				'class' => 'form-control'
			);
			$this->data['menu_desc'] = array(
				'name'  => 'menu_desc',
				'id'    => 'menu_desc',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('menu_desc'),
				'class' => 'form-control'
			);
			$this->data['url'] = array(
				'name' => 'url',
				'id' => 'url',
				'type' => 'text',
				'value' => $this->form_validation->set_value('url'),
				'class' => 'form-control'
			);
			$this->data['icon_name'] = array(
				'name' => 'icon_name',
				'id' => 'icon_name',
				'selected' => $this->form_validation->set_value('icon_name'),
				'options' => $this->icon_font_awe(),
				'class' => 'form-control'
			);
			$this->data['status'] = array(
				'name' => 'status',
				'id' => 'status',
				'selected' => $this->form_validation->set_value('status'),
				'options' => array('0' => 'Inactive', '1' => 'Active'),
				'class' => 'form-control'
			);

			
			$this->load->view('template/header');
			$this->_render_page('auth/create_menu', $this->data);
			$this->load->view('template/footer');
		}
	}

	/**
	* Delete group
	* @author nabrih
	* @param int|string $id
	* @return JSON
	*/
	public function delete_menu($id=false)
	{	
		$this->load->model('acl_model');
		//default return for json
		$data = array('status'=>'failed', 'error' => 'no data');
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()){
			if ($id!==false) {
				$deleted = $this->acl_model->deleteMenu($id);
				if ($deleted) {
					$data = array('status'=>'success', 'error' => '0');
				}else{
					$data['error'] = "Can't delete data";
				}
			}
		}else
		$data['error'] = 'You not have permission';

		$this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
	}


	/*
	*
	*/
	public function edit_menu($menu_id=false)
	{
		$this->load->model('acl_model');

		if (!$this->ion_auth->logged_in() && !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		$menu = $this->acl_model->menu($menu_id);

		$this->form_validation->set_rules('menu_name', 'Name is required', 'trim|required|alpha_dash');
		$this->form_validation->set_rules('menu_desc', 'Description is required', 'trim|required');
		$this->form_validation->set_rules('url', 'URL is required', 'trim|required');
		$this->form_validation->set_rules('icon_name', 'Icon Name is required', 'trim|required');
		$this->form_validation->set_rules('status', 'Status is required', 'trim|required');

		if ($this->form_validation->run() === TRUE){

			$data_menu = array(
				'id'			=>	$this->input->post('id'),
				'menu_name'		=>	$this->input->post('menu_name'),
				'menu_desc'		=>	$this->input->post('menu_desc'),
				'url'			=>	$this->input->post('url'),
				'icon_name'		=>	$this->input->post('icon_name'),
				'status'		=>	$this->input->post('status'),
				'ashead'		=>	$this->input->post('ashead'),
				'parent_id'		=>	$this->input->post('parent_id')
			);

			$result = $this->acl_model->edit_menu($data_menu);
			if ($result) {
				$this->session->set_flashdata('message', 'Data updated successfully');
				redirect('auth/menus', 'refresh');
			}
		}


		$this->data['menu_name'] = array(
			'name'  => 'menu_name',
			'id'    => 'menu_name',
			'type'  => 'text',
			'value' => $menu->menu_name,
			'class' => 'form-control'
		);
		$this->data['menu_desc'] = array(
			'name'  => 'menu_desc',
			'id'    => 'menu_desc',
			'type'  => 'text',
			'value' => $menu->menu_desc,
			'class' => 'form-control'
		);
		$this->data['url'] = array(
			'name' => 'url',
			'id' => 'url',
			'type' => 'text',
			'value' => $menu->url,
			'class' => 'form-control'
		);
		$this->data['icon_name'] = array(
			'name' => 'icon_name',
			'id' => 'icon_name',
			'selected' => $menu->icon_name,
			'options' => $this->icon_font_awe(),
			'class' => 'form-control'
		);
		$this->data['status'] = array(
			'name' => 'status',
			'id' => 'status',
			'selected' => $menu->status,
			'options' => array('0' => 'Inactive', '1' => 'Active'),
			'class' => 'form-control'
		);

		$this->data['ashead'] = array(
			'name' => 'ashead',
			'id' => 'ashead',
			'selected' => $menu->ashead,
			'options' => array('0' => 'No', '1' => 'Yes'),
			'class' => 'form-control'
		);

		$parents = $this->acl_model->parent_menus();

		$this->data['parent_id'] = array(
			'name' => 'parent_id',
			'id' => 'parent_id',
			'selected' => $menu->parent_id,
			'options' => $parents,
			'class' => 'form-control'
		);



		$this->data['menu'] = $menu;

		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		$this->load->view('template/header');
		$this->_render_page('auth/edit_menu', $this->data);
		$this->load->view('template/footer');
		
		
	}


	/**
	* Display all groups
	* @author nabrih
	* 
	*/
	public function groups()
	{

		if (!$this->ion_auth->logged_in() && !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		$groups = $this->ion_auth->groups()->result_array();
		$this->data['groups'] = $groups;

		$this->data_head['page'] = "groups";

		$this->load->view('template/header', $this->data_head);
		$this->_render_page('account/v_data_groups', $this->data);
		$this->load->view('template/footer');
	}

	/**
	 * Create a new group
	 */
	public function create_group()
	{
		$this->data['title'] = $this->lang->line('create_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		// validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'trim|required|alpha_dash');

		if ($this->form_validation->run() === TRUE)
		{
			$new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
			if ($new_group_id)
			{
				// check to see if we are creating the group
				// redirect them back to the admin page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth/groups", 'refresh');
			}
		}
		else
		{
			// display the create group form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['group_name'] = array(
				'name'  => 'group_name',
				'id'    => 'group_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('group_name'),
			);
			$this->data['description'] = array(
				'name'  => 'description',
				'id'    => 'description',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('description'),
			);


			$this->load->view('template/header');
			$this->_render_page('auth/create_group', $this->data);
			$this->load->view('template/footer');
		}
	}

	/**
	 * Edit a group
	 *
	 * @param int|string $id
	 */
	public function edit_group($id)
	{
		// bail if no group id given
		if (!$id || empty($id))
		{
			redirect('auth', 'refresh');
		}

		$this->data['title'] = $this->lang->line('edit_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		$group = $this->ion_auth->group($id)->row();

		// validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'required|alpha_dash');

		if (isset($_POST) && !empty($_POST))
		{
			if ($this->form_validation->run() === TRUE)
			{
				$group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);

				if ($group_update)
				{
					$this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));
				}
				else
				{
					$this->session->set_flashdata('message', $this->ion_auth->errors());
				}
				redirect("auth/groups", 'refresh');
			}
		}

		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// pass the user to the view
		$this->data['group'] = $group;

		$readonly = $this->config->item('admin_group', 'ion_auth') === $group->name ? 'readonly' : '';

		$this->data['group_name'] = array(
			'name'    => 'group_name',
			'id'      => 'group_name',
			'type'    => 'text',
			'value'   => $this->form_validation->set_value('group_name', $group->name),
			$readonly => $readonly,
		);
		$this->data['group_description'] = array(
			'name'  => 'group_description',
			'id'    => 'group_description',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_description', $group->description),
		);

		$this->load->view('template/header');
		$this->_render_page('auth/edit_group', $this->data);
		$this->load->view('template/footer');
		
	}

	/**
	* Registering menu into group
	* @author nabrih
	* @param int|string $group_id
	*/
	public function reg_group_menu($group_id)
	{
		$this->load->model('acl_model');

		// validate form input
		$this->form_validation->set_rules('group_id', 'Group id', 'trim|required');

		if (isset($_POST) && !empty($_POST))
		{
			if ($this->form_validation->run() === TRUE)
			{	
				$group_menu_update = $this->acl_model->update_group_menu($group_id, $this->input->post('menus'));

				if ($group_menu_update)
				{
					$this->session->set_flashdata('message', 'Group updated');
					redirect("auth/groups", 'refresh');
					// echo json_encode($group_menu_update);
				}
				else
				{
					$this->session->set_flashdata('message', 'Group updated');
					redirect(current_url(), 'refresh');
					// $this->session->set_flashdata('message', "Update failed");
				}
				
			}

			$this->data['bj'] = 'bjj';
		}else{
			
			$group = $this->ion_auth->group($group_id)->row();//group data

			$menus = $this->acl_model->menus();// all menu
			$reg_menus = $this->acl_model->group_menus($group_id);// registered menus to group

			$this->data['group'] = $group;
			$this->data['menus'] = $menus->result_array();
			$this->data['reg_menus'] = $reg_menus->result_array();

			$this->load->view('template/header');
	        $this->_render_page('auth/regist_menus.php', $this->data);
	        $this->load->view('template/footer');
    	}
		
	}

	public function icon_font_awe()
	{
		$fonts = array(
			'fa fa-adjust' => 'fa fa-adjust',
			'fa fa-anchor' => 'fa fa-anchor',
			'fa fa-archive' => 'fa fa-archive',
			'fa fa-area-chart' => 'fa fa-area-chart',
			'fa fa-arrows' => 'fa fa-arrows',
			'fa fa-arrows-h' => 'fa fa-arrows-h',
			'fa fa-arrows-v' => 'fa fa-arrows-v',
			'fa fa-asterisk' => 'fa fa-asterisk',
			'fa fa-at' => 'fa fa-at',
			'fa fa-car' => 'fa fa-car',
			'fa fa-ban' => 'fa fa-ban',
			'fa fa-university' => 'fa fa-university',
			'fa fa-bar-chart' => 'fa fa-bar-chart',
			'fa fa-bar-chart' => 'fa fa-bar-chart',
			'fa fa-barcode' => 'fa fa-barcode',
			'fa fa-bars' => 'fa fa-bars',
			'fa fa-beer' => 'fa fa-beer',
			'fa fa-bell' => 'fa fa-bell',
			'fa fa-bell-o' => 'fa fa-bell-o',
			'fa fa-bell-slash' => 'fa fa-bell-slash',
			'fa fa-bell-slash-o' => 'fa fa-bell-slash-o',
			'fa fa-bicycle' => 'fa fa-bicycle',
			'fa fa-binoculars' => 'fa fa-binoculars',
			'fa fa-birthday-cake' => 'fa fa-birthday-cake',
			'fa fa-bolt' => 'fa fa-bolt',
			'fa fa-bomb' => 'fa fa-bomb',
			'fa fa-book' => 'fa fa-book',
			'fa fa-bookmark' => 'fa fa-bookmark',
			'fa fa-bookmark-o' => 'fa fa-bookmark-o',
			'fa fa-briefcase' => 'fa fa-briefcase',
			'fa fa-bug' => 'fa fa-bug',
			'fa fa-building' => 'fa fa-building',
			'fa fa-building-o' => 'fa fa-building-o',
			'fa fa-bullhorn' => 'fa fa-bullhorn',
			'fa fa-bullseye' => 'fa fa-bullseye',
			'fa fa-bus' => 'fa fa-bus',
			'fa fa-taxi' => 'fa fa-taxi',
			'fa fa-calculator' => 'fa fa-calculator',
			'fa fa-calendar' => 'fa fa-calendar',
			'fa fa-calendar-o' => 'fa fa-calendar-o',
			'fa fa-camera' => 'fa fa-camera',
			'fa fa-camera-retro' => 'fa fa-camera-retro',
			'fa fa-car' => 'fa fa-car',
			'fa fa-caret-square-o-down' => 'fa fa-caret-square-o-down',
			'fa fa-caret-square-o-left' => 'fa fa-caret-square-o-left',
			'fa fa-caret-square-o-right' => 'fa fa-caret-square-o-right',
			'fa fa-caret-square-o-up' => 'fa fa-caret-square-o-up',
			'fa fa-cc' => 'fa fa-cc',
			'fa fa-certificate' => 'fa fa-certificate',
			'fa fa-check' => 'fa fa-check',
			'fa fa-check-circle' => 'fa fa-check-circle',
			'fa fa-check-circle-o' => 'fa fa-check-circle-o',
			'fa fa-check-square' => 'fa fa-check-square',
			'fa fa-check-square-o' => 'fa fa-check-square-o',
			'fa fa-child' => 'fa fa-child',
			'fa fa-circle' => 'fa fa-circle',
			'fa fa-circle-o' => 'fa fa-circle-o',
			'fa fa-circle-o-notch' => 'fa fa-circle-o-notch',
			'fa fa-circle-thin' => 'fa fa-circle-thin',
			'fa fa-clock-o' => 'fa fa-clock-o',
			'fa fa-times' => 'fa fa-times',
			'fa fa-cloud' => 'fa fa-cloud',
			'fa fa-cloud-download' => 'fa fa-cloud-download',
			'fa fa-cloud-upload' => 'fa fa-cloud-upload',
			'fa fa-code' => 'fa fa-code',
			'fa fa-code-fork' => 'fa fa-code-fork',
			'fa fa-coffee' => 'fa fa-coffee',
			'fa fa-cog' => 'fa fa-cog',
			'fa fa-cogs' => 'fa fa-cogs',
			'fa fa-comment' => 'fa fa-comment',
			'fa fa-comment-o' => 'fa fa-comment-o',
			'fa fa-comments' => 'fa fa-comments',
			'fa fa-comments-o' => 'fa fa-comments-o',
			'fa fa-compass' => 'fa fa-compass',
			'fa fa-copyright' => 'fa fa-copyright',
			'fa fa-credit-card' => 'fa fa-credit-card',
			'fa fa-crop' => 'fa fa-crop',
			'fa fa-crosshairs' => 'fa fa-crosshairs',
			'fa fa-cube' => 'fa fa-cube',
			'fa fa-cubes' => 'fa fa-cubes',
			'fa fa-cutlery' => 'fa fa-cutlery',
			'fa fa-tachometer' => 'fa fa-tachometer',
			'fa fa-database' => 'fa fa-database',
			'fa fa-desktop' => 'fa fa-desktop',
			'fa fa-dot-circle-o' => 'fa fa-dot-circle-o',
			'fa fa-download' => 'fa fa-download',
			'fa fa-pencil-square-o' => 'fa fa-pencil-square-o',
			'fa fa-ellipsis-h' => 'fa fa-ellipsis-h',
			'fa fa-ellipsis-v' => 'fa fa-ellipsis-v',
			'fa fa-envelope' => 'fa fa-envelope',
			'fa fa-envelope-o' => 'fa fa-envelope-o',
			'fa fa-envelope-square' => 'fa fa-envelope-square',
			'fa fa-eraser' => 'fa fa-eraser',
			'fa fa-exchange' => 'fa fa-exchange',
			'fa fa-exclamation' => 'fa fa-exclamation',
			'fa fa-exclamation-circle' => 'fa fa-exclamation-circle',
			'fa fa-exclamation-triangle' => 'fa fa-exclamation-triangle',
			'fa fa-external-link' => 'fa fa-external-link',
			'fa fa-external-link-square' => 'fa fa-external-link-square',
			'fa fa-eye' => 'fa fa-eye',
			'fa fa-eye-slash' => 'fa fa-eye-slash',
			'fa fa-eyedropper' => 'fa fa-eyedropper',
			'fa fa-fax' => 'fa fa-fax',
			'fa fa-female' => 'fa fa-female',
			'fa fa-fighter-jet' => 'fa fa-fighter-jet',
			'fa fa-file-archive-o' => 'fa fa-file-archive-o',
			'fa fa-file-audio-o' => 'fa fa-file-audio-o',
			'fa fa-file-code-o' => 'fa fa-file-code-o',
			'fa fa-file-excel-o' => 'fa fa-file-excel-o',
			'fa fa-file-image-o' => 'fa fa-file-image-o',
			'fa fa-file-video-o' => 'fa fa-file-video-o',
			'fa fa-file-pdf-o' => 'fa fa-file-pdf-o',
			'fa fa-file-image-o' => 'fa fa-file-image-o',
			'fa fa-file-image-o' => 'fa fa-file-image-o',
			'fa fa-file-powerpoint-o' => 'fa fa-file-powerpoint-o',
			'fa fa-file-audio-o' => 'fa fa-file-audio-o',
			'fa fa-file-video-o' => 'fa fa-file-video-o',
			'fa fa-file-word-o' => 'fa fa-file-word-o',
			'fa fa-file-archive-o' => 'fa fa-file-archive-o',
			'fa fa-film' => 'fa fa-film',
			'fa fa-filter' => 'fa fa-filter',
			'fa fa-fire' => 'fa fa-fire',
			'fa fa-fire-extinguisher' => 'fa fa-fire-extinguisher',
			'fa fa-flag' => 'fa fa-flag',
			'fa fa-flag-checkered' => 'fa fa-flag-checkered',
			'fa fa-flag-o' => 'fa fa-flag-o',
			'fa fa-bolt' => 'fa fa-bolt',
			'fa fa-flask' => 'fa fa-flask',
			'fa fa-folder' => 'fa fa-folder',
			'fa fa-folder-o' => 'fa fa-folder-o',
			'fa fa-folder-open' => 'fa fa-folder-open',
			'fa fa-folder-open-o' => 'fa fa-folder-open-o',
			'fa fa-frown-o' => 'fa fa-frown-o',
			'fa fa-futbol-o' => 'fa fa-futbol-o',
			'fa fa-gamepad' => 'fa fa-gamepad',
			'fa fa-gavel' => 'fa fa-gavel',
			'fa fa-cog' => 'fa fa-cog',
			'fa fa-cogs' => 'fa fa-cogs',
			'fa fa-gift' => 'fa fa-gift',
			'fa fa-glass' => 'fa fa-glass',
			'fa fa-globe' => 'fa fa-globe',
			'fa fa-graduation-cap' => 'fa fa-graduation-cap',
			'fa fa-users' => 'fa fa-users',
			'fa fa-hdd-o' => 'fa fa-hdd-o',
			'fa fa-headphones' => 'fa fa-headphones',
			'fa fa-heart' => 'fa fa-heart',
			'fa fa-heart-o' => 'fa fa-heart-o',
			'fa fa-history' => 'fa fa-history',
			'fa fa-home' => 'fa fa-home',
			'fa fa-picture-o' => 'fa fa-picture-o',
			'fa fa-inbox' => 'fa fa-inbox',
			'fa fa-info' => 'fa fa-info',
			'fa fa-info-circle' => 'fa fa-info-circle',
			'fa fa-university' => 'fa fa-university',
			'fa fa-key' => 'fa fa-key',
			'fa fa-keyboard-o' => 'fa fa-keyboard-o',
			'fa fa-language' => 'fa fa-language',
			'fa fa-laptop' => 'fa fa-laptop',
			'fa fa-leaf' => 'fa fa-leaf',
			'fa fa-gavel' => 'fa fa-gavel',
			'fa fa-lemon-o' => 'fa fa-lemon-o',
			'fa fa-level-down' => 'fa fa-level-down',
			'fa fa-level-up' => 'fa fa-level-up',
			'fa fa-life-ring' => 'fa fa-life-ring',
			'fa fa-life-ring' => 'fa fa-life-ring',
			'fa fa-life-ring' => 'fa fa-life-ring',
			'fa fa-life-ring' => 'fa fa-life-ring',
			'fa fa-lightbulb-o' => 'fa fa-lightbulb-o',
			'fa fa-line-chart' => 'fa fa-line-chart',
			'fa fa-location-arrow' => 'fa fa-location-arrow',
			'fa fa-lock' => 'fa fa-lock',
			'fa fa-magic' => 'fa fa-magic',
			'fa fa-magnet' => 'fa fa-magnet',
			'fa fa-share' => 'fa fa-share',
			'fa fa-reply' => 'fa fa-reply',
			'fa fa-reply-all' => 'fa fa-reply-all',
			'fa fa-male' => 'fa fa-male',
			'fa fa-map-marker' => 'fa fa-map-marker',
			'fa fa-meh-o' => 'fa fa-meh-o',
			'fa fa-microphone' => 'fa fa-microphone',
			'fa fa-microphone-slash' => 'fa fa-microphone-slash',
			'fa fa-minus' => 'fa fa-minus',
			'fa fa-minus-circle' => 'fa fa-minus-circle',
			'fa fa-minus-square' => 'fa fa-minus-square',
			'fa fa-minus-square-o' => 'fa fa-minus-square-o',
			'fa fa-mobile' => 'fa fa-mobile',
			'fa fa-mobile' => 'fa fa-mobile',
			'fa fa-money' => 'fa fa-money',
			'fa fa-moon-o' => 'fa fa-moon-o',
			'fa fa-graduation-cap' => 'fa fa-graduation-cap',
			'fa fa-music' => 'fa fa-music',
			'fa fa-bars' => 'fa fa-bars',
			'fa fa-newspaper-o' => 'fa fa-newspaper-o',
			'fa fa-paint-brush' => 'fa fa-paint-brush',
			'fa fa-paper-plane' => 'fa fa-paper-plane',
			'fa fa-paper-plane-o' => 'fa fa-paper-plane-o',
			'fa fa-paw' => 'fa fa-paw',
			'fa fa-pencil' => 'fa fa-pencil',
			'fa fa-pencil-square' => 'fa fa-pencil-square',
			'fa fa-pencil-square-o' => 'fa fa-pencil-square-o',
			'fa fa-phone' => 'fa fa-phone',
			'fa fa-phone-square' => 'fa fa-phone-square',
			'fa fa-picture-o' => 'fa fa-picture-o',
			'fa fa-picture-o' => 'fa fa-picture-o',
			'fa fa-pie-chart' => 'fa fa-pie-chart',
			'fa fa-plane' => 'fa fa-plane',
			'fa fa-plug' => 'fa fa-plug',
			'fa fa-plus' => 'fa fa-plus',
			'fa fa-plus-circle' => 'fa fa-plus-circle',
			'fa fa-plus-square' => 'fa fa-plus-square',
			'fa fa-plus-square-o' => 'fa fa-plus-square-o',
			'fa fa-power-off' => 'fa fa-power-off',
			'fa fa-print' => 'fa fa-print',
			'fa fa-puzzle-piece' => 'fa fa-puzzle-piece',
			'fa fa-qrcode' => 'fa fa-qrcode',
			'fa fa-question' => 'fa fa-question',
			'fa fa-question-circle' => 'fa fa-question-circle',
			'fa fa-quote-left' => 'fa fa-quote-left',
			'fa fa-quote-right' => 'fa fa-quote-right',
			'fa fa-random' => 'fa fa-random',
			'fa fa-recycle' => 'fa fa-recycle',
			'fa fa-refresh' => 'fa fa-refresh',
			'fa fa-times' => 'fa fa-times',
			'fa fa-bars' => 'fa fa-bars',
			'fa fa-reply' => 'fa fa-reply',
			'fa fa-reply-all' => 'fa fa-reply-all',
			'fa fa-retweet' => 'fa fa-retweet',
			'fa fa-road' => 'fa fa-road',
			'fa fa-rocket' => 'fa fa-rocket',
			'fa fa-rss' => 'fa fa-rss',
			'fa fa-rss-square' => 'fa fa-rss-square',
			'fa fa-search' => 'fa fa-search',
			'fa fa-search-minus' => 'fa fa-search-minus',
			'fa fa-search-plus' => 'fa fa-search-plus',
			'fa fa-paper-plane' => 'fa fa-paper-plane',
			'fa fa-paper-plane-o' => 'fa fa-paper-plane-o',
			'fa fa-share' => 'fa fa-share',
			'fa fa-share-alt' => 'fa fa-share-alt',
			'fa fa-share-alt-square' => 'fa fa-share-alt-square',
			'fa fa-share-square' => 'fa fa-share-square',
			'fa fa-share-square-o' => 'fa fa-share-square-o',
			'fa fa-shield' => 'fa fa-shield',
			'fa fa-shopping-cart' => 'fa fa-shopping-cart',
			'fa fa-sign-in' => 'fa fa-sign-in',
			'fa fa-sign-out' => 'fa fa-sign-out',
			'fa fa-signal' => 'fa fa-signal',
			'fa fa-sitemap' => 'fa fa-sitemap',
			'fa fa-sliders' => 'fa fa-sliders',
			'fa fa-smile-o' => 'fa fa-smile-o',
			'fa fa-futbol-o' => 'fa fa-futbol-o',
			'fa fa-sort' => 'fa fa-sort',
			'fa fa-sort-alpha-asc' => 'fa fa-sort-alpha-asc',
			'fa fa-sort-alpha-desc' => 'fa fa-sort-alpha-desc',
			'fa fa-sort-amount-asc' => 'fa fa-sort-amount-asc',
			'fa fa-sort-amount-desc' => 'fa fa-sort-amount-desc',
			'fa fa-sort-asc' => 'fa fa-sort-asc',
			'fa fa-sort-desc' => 'fa fa-sort-desc',
			'fa fa-sort-desc' => 'fa fa-sort-desc',
			'fa fa-sort-numeric-asc' => 'fa fa-sort-numeric-asc',
			'fa fa-sort-numeric-desc' => 'fa fa-sort-numeric-desc',
			'fa fa-sort-asc' => 'fa fa-sort-asc',
			'fa fa-space-shuttle' => 'fa fa-space-shuttle',
			'fa fa-spinner' => 'fa fa-spinner',
			'fa fa-spoon' => 'fa fa-spoon',
			'fa fa-square' => 'fa fa-square',
			'fa fa-square-o' => 'fa fa-square-o',
			'fa fa-star' => 'fa fa-star',
			'fa fa-star-half' => 'fa fa-star-half',
			'fa fa-star-half-o' => 'fa fa-star-half-o',
			'fa fa-star-half-o' => 'fa fa-star-half-o',
			'fa fa-star-half-o' => 'fa fa-star-half-o',
			'fa fa-star-o' => 'fa fa-star-o',
			'fa fa-suitcase' => 'fa fa-suitcase',
			'fa fa-sun-o' => 'fa fa-sun-o',
			'fa fa-life-ring' => 'fa fa-life-ring',
			'fa fa-tablet' => 'fa fa-tablet',
			'fa fa-tachometer' => 'fa fa-tachometer',
			'fa fa-tag' => 'fa fa-tag',
			'fa fa-tags' => 'fa fa-tags',
			'fa fa-tasks' => 'fa fa-tasks',
			'fa fa-taxi' => 'fa fa-taxi',
			'fa fa-terminal' => 'fa fa-terminal',
			'fa fa-thumb-tack' => 'fa fa-thumb-tack',
			'fa fa-thumbs-down' => 'fa fa-thumbs-down',
			'fa fa-thumbs-o-down' => 'fa fa-thumbs-o-down',
			'fa fa-thumbs-o-up' => 'fa fa-thumbs-o-up',
			'fa fa-thumbs-up' => 'fa fa-thumbs-up',
			'fa fa-ticket' => 'fa fa-ticket',
			'fa fa-times' => 'fa fa-times',
			'fa fa-times-circle' => 'fa fa-times-circle',
			'fa fa-times-circle-o' => 'fa fa-times-circle-o',
			'fa fa-tint' => 'fa fa-tint',
			'fa fa-caret-square-o-down' => 'fa fa-caret-square-o-down',
			'fa fa-caret-square-o-left' => 'fa fa-caret-square-o-left',
			'fa fa-toggle-off' => 'fa fa-toggle-off',
			'fa fa-toggle-on' => 'fa fa-toggle-on',
			'fa fa-caret-square-o-right' => 'fa fa-caret-square-o-right',
			'fa fa-caret-square-o-up' => 'fa fa-caret-square-o-up',
			'fa fa-trash' => 'fa fa-trash',
			'fa fa-trash-o' => 'fa fa-trash-o',
			'fa fa-tree' => 'fa fa-tree',
			'fa fa-trophy' => 'fa fa-trophy',
			'fa fa-truck' => 'fa fa-truck',
			'fa fa-tty' => 'fa fa-tty',
			'fa fa-umbrella' => 'fa fa-umbrella',
			'fa fa-university' => 'fa fa-university',
			'fa fa-unlock' => 'fa fa-unlock',
			'fa fa-unlock-alt' => 'fa fa-unlock-alt',
			'fa fa-sort' => 'fa fa-sort',
			'fa fa-upload' => 'fa fa-upload',
			'fa fa-user' => 'fa fa-user',
			'fa fa-users' => 'fa fa-users',
			'fa fa-video-camera' => 'fa fa-video-camera',
			'fa fa-volume-down' => 'fa fa-volume-down',
			'fa fa-volume-off' => 'fa fa-volume-off',
			'fa fa-volume-up' => 'fa fa-volume-up',
			'fa fa-exclamation-triangle' => 'fa fa-exclamation-triangle',
			'fa fa-wheelchair' => 'fa fa-wheelchair',
			'fa fa-wifi' => 'fa fa-wifi',
			'fa fa-wrench' => 'fa fa-wrench',
		);
		return $fonts;
	}

	/**
	* Delete group
	* @author nabrih
	* @param int|string $id
	* @return JSON
	*/
	public function delete_group($id=false)
	{	
		//default return for json
		$data = array('status'=>'failed', 'error' => 'no data');

		if ($this->ion_auth->is_admin()){
			if ($id!==false) {
				$deleted = $this->ion_auth->delete_group($id);
				if ($deleted) {
					$data = array('status'=>'success', 'error' => '0');
					$this->session->set_flashdata('message', 'Group deleted');
				}else{
					$data['error'] = "Can't delete data";
				}
			}
		}else
			$data['error'] = 'You not have permission';

		$this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
	}

	/**
	 * @return array A CSRF key-value pair
	 */
	public function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	/**
	 * @return bool Whether the posted CSRF token matches
	 */
	public function _valid_csrf_nonce()
	{
		$csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
		if ($csrfkey && $csrfkey === $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * @param string     $view
	 * @param array|null $data
	 * @param bool       $returnhtml
	 *
	 * @return mixed
	 */
	public function _render_page($view, $data = NULL, $returnhtml = FALSE)//I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data : $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		// This will return html on 3rd argument being true
		if ($returnhtml)
		{
			return $view_html;
		}
	}

}
