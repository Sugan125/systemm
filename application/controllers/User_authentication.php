<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class User_Authentication extends CI_Controller { 
    function __construct() { 
        parent::__construct(); 
         
    
        // Load user model 
        $this->load->model('user'); 
    } 
     
    public function index(){ 
        
      
        // Load login/profile view 
        $this->load->view('user_authentication/index'); 
    } 
 
    public function logout() { 
        $this->session->unset_userdata('userData'); 
        // Redirect to login page 
        redirect('user_authentication'); 
    } 
}