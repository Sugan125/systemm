<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Userscontroller extends CI_Controller {

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
        $config['total_rows'] = $this->user_model->count_all_users(); 
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
       // $data['userss'] = $this->user_model->get_users($config['per_page'], $page);
       $data['userss'] = $this->user_model->get_users();
  
        // foreach ($data['userss'] as $user) {
            
        //     $name = $user->name;

        //     $data['files'] = $this->uploadmodel->get_file($name);
        
        // }
        
       
        
      //  var_dump($data['userss']);

        $this->load->view('template/header.php', $data);
        $user = $this->session->userdata('user_register');
        $users = $this->session->userdata('normal_user');
        $loginuser = $this->session->userdata('LoginSession');
        $data['total_rows'] = $this->user_model->count_all_users();

        if (isset($loginuser['roles']) && !empty($loginuser['roles']) && $loginuser['roles'] == 'Admin') {
            $data['total_rows'] = $this->user_model->count_all_users();
            $this->pagination->initialize($config);
        } elseif (isset($loginuser['roles']) && !empty($loginuser['roles']) && $loginuser['roles'] == 'User') {
            $data['total_rows'] = 1;
        } else {
            $data['total_rows'] = $this->user_model->count_all_users();
            $this->pagination->initialize($config);
        }
        

        //var_dump($loginuser);
        $this->load->view('template/header.php');
        $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
        $this->load->view('template/users.php', $data);
        $this->load->view('template/footer.php');
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
        $data['userss'] = $this->user_model->search_users($keyword, $config['per_page'], $offset);
        $data['total_rows'] = $this->user_model->count_search_results($keyword);
    
        $this->pagination->initialize($config);
        $data['keyword'] = $keyword;
    
        $this->load->view('template/header.php', $data);
        $user = $this->session->userdata('user_register');
        $users = $this->session->userdata('normal_user');
        $loginuser = $this->session->userdata('LoginSession');
        //var_dump($loginuser);
        $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
        $this->load->view('template/users.php', $data);
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
        $this->load->view('template/create_users.php');
        $this->load->view('template/footer.php');
    }


   



    public function adduser(){

        

       $data = array(
        'name'=>$this->input->post('name'),
       'email'=>$this->input->post('email'),
       'address'=>$this->input->post('address'),
       'contact'=>$this->input->post('contact'),
       'password'=>$this->input->post('password'),
       'role' => implode(',', $this->input->post('role'))
    );

    $status = $this->user_model->is_email_exists($data['email']);
        echo 'heoo'.$status;
        if($status!=false){
            // Email already exists, show an error message or take appropriate action
            $this->session->set_flashdata('error', '<div class="alert alert-danger alert-dismissible fade show" role="alert">Email already exists!
                <button type="button" class="close" data-dismiss="alert" aria-label="close"> <span aria-hidden="true">&times;</span></button></div>');
            redirect('Userscontroller');
        } 
        else{
    $this->user_model->insert_data($data,'user_register');
    $this->session->set_flashdata('created','<div class="alert alert-success alert-dismissible fade show" role="alert">User Created Successfully!
    <button type="button" class="close" data-dismiss="alert" arial-label="close"> <span aria-hidden="true">&times;</span></button></div>');
    redirect('Userscontroller');
        }
    }

    public function updateuser($id){

        
    $role = $this->input->post('role');

    // Check if $role is a string, convert it to an array if needed
    if (!is_array($role)) {
        $role = explode(',', $role);
    }

    $data = array(
        'id'      => $id,
        'name'    => $this->input->post('name'),
        'email'   => $this->input->post('email'),
        'address' => $this->input->post('address'),
        'contact' => $this->input->post('contact'),
        'password'=> $this->input->post('password'),
        'role'    => implode(',', $role),
    );
 
     $this->user_model->update_datas($data,'user_register');
     $this->session->set_flashdata('updated','<div class="alert alert-success alert-dismissible fade show" role="alert">User Updated Successfully!
     <button type="button" class="close" data-dismiss="alert" arial-label="close"> <span aria-hidden="true">&times;</span></button></div>');
     redirect('Userscontroller');
     }

     public function deleteuser($id){

        $data = array(
            'id'=>$id,
     );
 
 
     $this->user_model->delete_data($data,'user_register');
     $this->session->set_flashdata('deleted','<div class="alert alert-Warning alert-dismissible fade show" role="alert">User deleted Successfully!
     <button type="button" class="close" data-dismiss="alert" arial-label="close"> <span aria-hidden="true">&times;</span></button></div>');
     redirect('Userscontroller');
     }

     public function update($id){

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
    $this->load->view('template/updateuserpage.php',$data);
   $this->load->view('template/footer.php');
     }
    

     public function fileupload($id) {

        $datanames = array(); // Initialize the variable

     
        $status = $this->user_model->getusername($id);
       
       // var_dump($status);

        if ($status != false) {
                $datanames = array(
                'name' => $status->name,
						// 'email'=>$status->email,
						// 'id'=>$status->id,
					);
                
                   
                }

              //  var_dump($datanames);

        $data['title'] = 'Dashboard';
        $this->load->view('template/header.php', $data);
        $user = $this->session->userdata('user_register');
        $users = $this->session->userdata('normal_user');
        $loginuser = $this->session->userdata('LoginSession');
        //var_dump($loginuser);
        $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
         $this->load->view('files/fileupload.php',array('datanames' => $datanames));
       // $this->load->view('files/fileupload.php');
        $this->load->view('template/footer.php');
    }

    public function upload($name)
	{

		$countFiles = count($_FILES['uploadedFiles']['name']);
		$countUploadedFiles=0;
		$countErrorUploadFiles=0;
		for($i=0;$i<$countFiles;$i++)
		{
			$_FILES['uploadFile']['name'] = $_FILES['uploadedFiles']['name'][$i]; 
			$_FILES['uploadFile']['type'] = $_FILES['uploadedFiles']['type'][$i];
			$_FILES['uploadFile']['size'] = $_FILES['uploadedFiles']['size'][$i];
			$_FILES['uploadFile']['tmp_name'] = $_FILES['uploadedFiles']['tmp_name'][$i];
			$_FILES['uploadFile']['error'] = $_FILES['uploadedFiles']['error'][$i];

			$uploadStatus = $this->uploadFile('uploadFile');
			if($uploadStatus!=false)
			{
				$countUploadedFiles++;
				$this->load->model('uploadmodel');
				$data =array(
                    'name'=>$name,
					'img_path'=>$uploadStatus,
					'upload_time'=>date('Y-m-d H:i:s'),
				);
				$this->uploadmodel->upload_data($data);
			}
			else
			{
				$countErrorUploadFiles++;
			}

		}

		$this->session->set_flashdata('messgae','Files Uploaded. Successfull Files Uploaded:'.$countUploadedFiles. ' and Error Uploading Files:'.$countErrorUploadFiles);
		redirect(base_url('index.php/Userscontroller/fileupload/'.$name));
                 

	}

	function uploadFile($name)
	{
		$uploadPath='uploads/images/';
		// if(!is_dir($uploadPath))
		// {
		// 	mkdir($uploadPath,0777,TRUE);
		// }

		$config['upload_path'] = $uploadPath;
		$config['allowed_types']= 'jpeg|JPEG|JPG|jpg|png|PNG|pdf|doc';
		//$config['encrypt_name']=TRUE;
        $config['encrypt_name']=FALSE;
		$this->load->library('upload',$config);
		$this->upload->initialize($config);
		if($this->upload->do_upload($name))
		{
			$fileData = $this->upload->data();
			return $fileData['file_name'];
		}
		else
		{
			return false;
		}
	}

    public function viewfiles($id) {
        $this->load->model('uploadmodel'); // Load the model
    

        $datanames = array(); // Initialize the variable

     
        $status = $this->user_model->getusername($id);
       
       // var_dump($status);

        if ($status != false) {
                $datanames = array(
                'name' => $status->name,
						// 'email'=>$status->email,
						// 'id'=>$status->id,
					);
                
                   
                }

               // var_dump($datanames);
                
        $data['title'] = 'Dashboard';
        //$data['name'] = $name;
    
        $data['files'] = $this->uploadmodel->getfiles($datanames, 'images');
        
        //var_dump($data);

        $this->load->view('template/header.php');
        $user = $this->session->userdata('user_register');
        $users = $this->session->userdata('normal_user');
        $loginuser = $this->session->userdata('LoginSession');
        //var_dump($loginuser);
        $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
        $this->load->view('files/viewfiles', array('data' => $data, 'datanames' => $datanames));

        $this->load->view('template/footer.php');
    }

    public function profileview(){

        $loginuser = $this->session->userdata('LoginSession');

      // var_dump($loginuser);

        $this->load->view('template/header.php');
        $user = $this->session->userdata('user_register');
        $users = $this->session->userdata('normal_user');
        $loginuser = $this->session->userdata('LoginSession');
        //var_dump($loginuser);
        $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'loginuser' => $loginuser));
        $this->load->view('template/userprofile', array('loginuser' => $loginuser, 'user' => $user));

        $this->load->view('template/footer.php');
    }

    public function update_profile_pic(){
        $this->load->library('upload');
    
        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $this->input->post('user_id');
    
            // Configure the upload parameters
            $config['upload_path'] = './uploads/profile/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 2048; // 2MB
    
            $this->upload->initialize($config);
    
            // Perform the upload
            if ($this->upload->do_upload('profile_pic')) {
                $uploaded_data = $this->upload->data();
                $uploaded_filename = $uploaded_data['file_name'];
    
                // Update the profile picture in the database
                $this->load->model('user_model');
                $this->user_model->update_profile_pic($user_id, $uploaded_filename);
    
                // Update the session data with the new profile picture filename
                $loginuser = $this->session->userdata('LoginSession');
                $loginuser['profile_img'] = $uploaded_filename;
                $this->session->set_userdata('LoginSession', $loginuser);
    
                // Redirect to the profile page or another appropriate page
                redirect('Userscontroller/profileview');
            } else {
                // Handle upload failure
                $upload_error = $this->upload->display_errors();
                // Handle the error as needed
            }
        }
    }

   
}

?>