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
        $this->load->library('upload');
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
     
        $this->load->library('upload');
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048; 
        $this->upload->initialize($config);
        if ($this->upload->do_upload('prod_img')) {
            $upload_data = $this->upload->data(); 
            $upload_data = $this->upload->data();

            // Get file name
            $file_name = $upload_data['file_name'];

            // Get full file path
            $full_path = $upload_data['full_path'];

            // Get file size
            $file_size = $upload_data['file_size'];

            // Get file type
            $file_type = $upload_data['file_type'];

            // Get file extension
            $file_ext = $upload_data['file_ext'];

            // Get raw file name (without extension)
            $raw_name = $upload_data['raw_name'];

            $image_width = $upload_data['image_width'];
            $image_height = $upload_data['image_height'];
            
            $image_path = $file_name;
            
        } else {
            
            $image_path = ''; 
        }
    
        $data = array(
            'product_id' => $this->input->post('product_id'),
            'product_name' => $this->input->post('product_name'),
            'product_desc' => $this->input->post('product_desc'),
            'prod_category' => $this->input->post('prod_category'),
            'add_on_slice' => $this->input->post('add_on_slice'),
            'add_on_seed' => $this->input->post('add_on_seed'),
            'prod_img' => $image_path, // Image path
            'prod_rate' => $this->input->post('prod_rate'),
            'deleted'=> '0',
        );
        
        
        $this->product_model->insert_product($data,'products');
    
        // Set flashdata for success message
        $this->session->set_flashdata('created', '<div class="alert alert-success alert-dismissible fade show" role="alert">Product Created Successfully!
        <button type="button" class="close" data-dismiss="alert" arial-label="close"> <span aria-hidden="true">&times;</span></button></div>');
    
        // Redirect to a specific page (change this as needed)
        redirect('Productcontroller');
    }
    

    public function deleteproduct($id){

        $data = array(
            'id'=>$id, 'deleted'=>'1',
     );
 
 
     $this->product_model->delete_data($data,'products');
     $this->session->set_flashdata('deleted','<div class="alert alert-Warning alert-dismissible fade show" role="alert">Product deleted Successfully!
     <button type="button" class="close" data-dismiss="alert" arial-label="close"> <span aria-hidden="true">&times;</span></button></div>');
     redirect('productcontroller');
     }

     public function updateproduct($id){

        $data = array(
            'id'=>$id
     );
 
        $title['title'] = 'Update';
		
        $data['products'] = $this->product_model->update_data($data,'products');
     
        $this->load->view('template/header.php',$title);
        $user = $this->session->userdata('user_register');
        $users = $this->session->userdata('normal_user');
        $loginuser = $this->session->userdata('LoginSession');
        //var_dump($loginuser);
        $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'title' => $title,'loginuser' => $loginuser));
        $this->load->view('product/update_product.php',$data);
        $this->load->view('template/footer.php');
     }

     
     public function updateprods($id){
        $this->load->library('upload');
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048; 
        $this->upload->initialize($config);
        if ($this->upload->do_upload('prod_img')) {
            $upload_data = $this->upload->data();

            // Get file name
            $file_name = $upload_data['file_name'];
            $image_path = $file_name;

        } else {
            
            $image_path = ''; 
        }
        $data = array(
            'id'      => $id,
            'product_id' => $this->input->post('product_id'),
            'product_name' => $this->input->post('product_name'),
            'product_desc' => $this->input->post('product_desc'),
            'prod_category' => $this->input->post('prod_category'),
            'add_on_slice' => $this->input->post('add_on_slice'),
            'add_on_seed' => $this->input->post('add_on_seed'),
            'prod_img' => $image_path, // Image path
            'prod_rate' => $this->input->post('prod_rate'),
            'deleted'=> '0',
        );
       
         $this->product_model->update_datas($data,'products');
         $this->session->set_flashdata('updated','<div class="alert alert-success alert-dismissible fade show" role="alert">User Updated Successfully!
         <button type="button" class="close" data-dismiss="alert" arial-label="close"> <span aria-hidden="true">&times;</span></button></div>');
         redirect('productcontroller');
         }
         

         
         
         public function userproduct() {
            $data['title'] = 'Products';
            $this->load->view('template/header.php', $data);
            $user = $this->session->userdata('user_register');
            $users = $this->session->userdata('normal_user');
            $loginuser = $this->session->userdata('LoginSession');
            $data['products'] = $this->product_model->product_details();

            //var_dump($loginuser);
            $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
            $this->load->view('user_prod/user_products.php',$data);
            $this->load->view('template/footer.php');
        }
     
    
}

?>