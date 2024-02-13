<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	

	public function index()
	{
		$this->load->view('includes/header.php');
		$this->load->view('includes/sidebar.php');
		$this->load->view('includes/navbar.php');
		$this->load->view('Home/home.php');
		$this->load->view('includes/footer.php');	
	}
}
	


