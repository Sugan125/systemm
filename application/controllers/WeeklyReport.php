<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WeeklyReport extends CI_Controller {
 
 function index()
 {
    $data['print'] = 'print';

    $loginuser = $this->session->userdata('LoginSession');
    
    $data['user_id'] = $loginuser['id'];
    
    $user_id = $data['user_id'];
    
    
    $this->load->view('template/header.php', $data);
    $user = $this->session->userdata('user_register');
    $users = $this->session->userdata('normal_user');
    $loginuser = $this->session->userdata('LoginSession');
    $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
    $this->load->view('invoice/weekly_report.php', array('data' => $data,));
    $this->load->view('template/footer.php');
 }

function ProdWeeklyReport(){
   $data['print'] = 'print';

   $loginuser = $this->session->userdata('LoginSession');
   
   $data['user_id'] = $loginuser['id'];
   
   $user_id = $data['user_id'];
   
   
   $this->load->view('template/header.php', $data);
   $user = $this->session->userdata('user_register');
   $users = $this->session->userdata('normal_user');
   $loginuser = $this->session->userdata('LoginSession');
   $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
   $this->load->view('invoice/prod_weekly_report.php', array('data' => $data,));
   $this->load->view('template/footer.php');
}
}

?>