<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Productcontroller extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('user_model');
        $this->load->model('product_model');
        $this->load->library('pagination');
        $this->load->library('session');
    }

    public function index() {
        $data['title'] = 'Products';

        $this->load->view('template/header.php', $data);
        $user = $this->session->userdata('user_register');
        $users = $this->session->userdata('normal_user');
        $loginuser = $this->session->userdata('LoginSession');
        $data['total_rows'] = $this->user_model->count_all_users();
        $data['products'] = $this->product_model->product_details();



        $this->load->view('template/header.php');
        $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
        $this->load->view('product/products.php', $data);
        $this->load->view('template/footer.php');
    }

    public function create() {
        $data['title'] = 'Dashboard';
        $this->load->view('template/header.php', $data);
        $user = $this->session->userdata('user_register');
        $users = $this->session->userdata('normal_user');
        $loginuser = $this->session->userdata('LoginSession');
        //var_dump($loginuser);
        $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
        $this->load->view('product/create_products.php');
        $this->load->view('template/footer.php');
    }

    public function addproduct(){

        

        $data = array(
         'product_id'=>$this->input->post('product_id'),
        'product_name'=>$this->input->post('product_name'),
        'product_desc'=>$this->input->post('product_desc'),
        'prod_category'=>$this->input->post('prod_category'),
        'add_on_slice'=>$this->input->post('add_on_slice'),
        'add_on_seed'=>$this->input->post('add_on_seed'),
        'prod_img'=>$this->input->post('prod_img'),
        'prod_rate'=>$this->input->post('prod_rate'),
     );
 
  
     $this->product_model->insert_product($data,'products');
     $this->session->set_flashdata('created','<div class="alert alert-success alert-dismissible fade show" role="alert">Product Created Successfully!
     <button type="button" class="close" data-dismiss="alert" arial-label="close"> <span aria-hidden="true">&times;</span></button></div>');
     redirect('Productcontroller');
         }
     
    
}

?>