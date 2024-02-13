<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	
	function __construct() {
        parent::__construct();
		$this->load->model('user_model');
        $this->load->library('email');
		$this->load->helper('url');
		$this->load->library('session');
	 }

	public function index()
	{
		$data = array();

		$userData = array();

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			
				$name = $this->input->post('name');
				$password = $this->input->post('password');
			
				$status = $this->user_model->checkUser($name, $password);
	
				if ($status != false) {
					$userData = array(
						'id' => $status->id,
						'name' => $status->name,
						'email' => $status->email,
						'address' => $status->address,
						'contact' => $status->contact,
						'profile_img' => $status->profile_img,
						'access' => explode(',', $status->access),
						'role' => explode(',', $status->role),
						'accesss' => $status->access,
						'roles' => $status->role,
					);
	
					$this->session->set_userdata('LoginSession', $userData);
					
					redirect(base_url('Dashboard'));
				} else {
					$this->session->set_flashdata('error', 'User Name or Password is Wrong');
					redirect(base_url('Login'));
				}
			} else {
				// Load login/profile view
				$this->load->view('Login/login.php');
				return;
			}
		}

		
		function logout()
		{
			session_unset();
			session_destroy();
			redirect(base_url('Login'));
		}
	
	}

