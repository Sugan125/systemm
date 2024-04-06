<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Userrolecontroller extends CI_Controller {

	function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('user_model');
        $this->load->library('pagination');
        $this->load->library('session');
    }
	
     public function index() {
        $data['title'] = 'Dashboard';
    
        // Pagination Config
        $config['base_url'] = site_url('Userscontroller/index');
        $config['total_rows'] = $this->user_model->count_all_user_role(); 
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $config['full_tag_open']= '<ul  class="pagination">';
		$config['full_tag_close']= '</ul'>
		$config['first_link']= 'First';
		$config['last_link']= 'Last';
		$config['first_tag_open']=  '<li  class="page-item"><spann class="page-link">';
		$config['first_tag_close'] = '</span></li>';
		$config['prev_link']= 'Previous';
		$config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';

		$config['prev_tag_close'] = '</span></li>';
		$config['next_link']= 'Next';
		$config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
		$config['next_tag_close'] = '</span></li>';
		$config['last_tag_open'] = '<li class="page-item"><span class="page-link">';
		$config['last_tag_close'] = '</span></li>';
		$config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
		$config['num_tag_close'] = '</span></li>'; 

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
        $data['userss'] = $this->user_model->get_roleuserss();

        $data['total_rows'] = $this->user_model->count_all_user_role();
        
        //var_dump($data['total_rows']);

        $this->pagination->initialize($config);

        $this->load->view('template/header.php', $data);
        $user = $this->session->userdata('user_register');
        $users = $this->session->userdata('normal_user');
        $loginuser = $this->session->userdata('LoginSession');
        //var_dump($loginuser);
        $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
        $this->load->view('template/usersaccess.php', $data);
        $this->load->view('template/footer.php');
    }

    public function createaccess() {
        $data['title'] = 'Dashboard';

        $loginuser = $this->session->userdata('LoginSession');
	
		$data['user_id'] = $loginuser['id'];

        $data['userss'] = $this->user_model->get_roleuser_access($data['user_id']);

        $data['total_rows'] = $this->user_model->count_all_user_role();
        
       // var_dump($data['userss'] );

       

        $this->load->view('template/header.php', $data);
        $user = $this->session->userdata('user_register');
        $users = $this->session->userdata('normal_user');
        $loginuser = $this->session->userdata('LoginSession');
        //var_dump($loginuser);
        $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
        $this->load->view('template/create_useraccess.php');
        $this->load->view('template/footer.php');
    }

    public function adduseraccess() {
        $name = $this->input->post('name');
        $role = $this->input->post('role');
        $access = $this->input->post('access');
    
        // Check if $role and $access are strings, convert them to arrays if needed
        if (!is_array($role)) {
            $role = explode(',', $role);
        }
    
        if (!is_array($access)) {
            $access = explode(',', $access);
        }
    
        $data = array(
            'name'   => $name,
            'role'   => implode(',', $role),
            'access' => implode(',', $access),
        );
    
        $this->user_model->createrole($data, 'user_register');
        $this->session->set_flashdata('updated', '<div class="alert alert-success alert-dismissible fade show" role="alert">User access Updated Successfully! Please logout and login if you set access of own account.
            <button type="button" class="close" data-dismiss="alert" aria-label="close"> <span aria-hidden="true">&times;</span></button></div>');
        redirect('Userrolecontroller');
    }
    


     public function updateaccess($id){

        $data = array(
            'id'=>$id
     );
 
        $title['title'] = 'Dashboard';
		
        $data['query'] = $this->user_model->update_data($data,'user_register');

        
     
        $this->load->view('template/header.php',$title);
        $user = $this->session->userdata('user_register');
        $users = $this->session->userdata('normal_user');
        $loginuser = $this->session->userdata('LoginSession');
        //var_dump($loginuser);
        $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'title' => $title,'loginuser' => $loginuser));
    $this->load->view('template/updateuseraccess.php',$data);
   $this->load->view('template/footer.php');
     }

     public function deleteaccess($id){

        $data = array(
            'id'=>$id,
     );
 
 
     $this->user_model->deleteaccess_data($data,'user_register');
     $this->session->set_flashdata('deleted','<div class="alert alert-Warning alert-dismissible fade show" role="alert">Access deleted Successfully!
     <button type="button" class="close" data-dismiss="alert" arial-label="close"> <span aria-hidden="true">&times;</span></button></div>');
     redirect('Userrolecontroller');
     }

     public function search() {
        $keyword = $this->input->get('keyword');
        $data['title'] = 'Dashboard';
    
        // Pagination Config for Search Results
        $config['base_url'] = site_url('Userscontroller/search');
        $config['total_rows'] = max($this->user_model->count_search_results($keyword), 10); // Ensure at least 10 total rows
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $config['use_page_numbers'] = TRUE;
    
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';
        $config['first_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['first_tag_close'] = '</span></li>';
        $config['prev_link'] = 'Previous';
        $config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['prev_tag_close'] = '</span></li>';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['next_tag_close'] = '</span></li>';
        $config['last_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['last_tag_close'] = '</span></li>';
        $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '</span></li>';
        $config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close'] = '</span></li>'; 
    
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1; // Set the default page to 1
        $offset = ($page - 1) * $config['per_page'];
        $data['userss'] = $this->user_model->searchaccess_users($keyword, $config['per_page'], $offset);
        $data['total_rows'] = $this->user_model->countaccess_search_results($keyword);
    
        $this->pagination->initialize($config);
        $data['keyword'] = $keyword;
    
        $this->load->view('template/header.php', $data);
        $user = $this->session->userdata('user_register');
        $users = $this->session->userdata('normal_user');
        $loginuser = $this->session->userdata('LoginSession');
        //var_dump($loginuser);
        $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
        $this->load->view('template/usersaccess.php', $data);
        $this->load->view('template/footer.php');
    }
}
