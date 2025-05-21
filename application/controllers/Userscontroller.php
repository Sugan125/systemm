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
        $config['base_url'] = site_url('Userscontroller/search');
        $config['total_rows'] = $this->user_model->count_all_users(); 
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

        $config['total_rows'] = $this->user_model->count_all_users();

	    $this->pagination->initialize($config);

       $data['userss'] = $this->user_model->get_users();

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

        $data['keyword'] = $keyword;

        $config['total_rows'] = $this->user_model->count_all_users();

	    $this->pagination->initialize($config);
        $data['userss'] = $this->user_model->search_users($keyword, $config['per_page'], $offset);
        $data['total_rows'] = $this->user_model->count_search_results($keyword);
        
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

        date_default_timezone_set('UTC');

		// Create a DateTime object for the current time
		$current_date_time = new DateTime('now');
			
			// Add 8 hours to adjust to Singapore time (GMT+8)
		$current_date_time->modify('+8 hours');
			
			// Format the datetime
		$created_date = $current_date_time->format('Y-m-d H:i:s');
$is_archieve = ($this->input->post('status') == 1) ? 0 : 1;
       $data = array(
        'name'=>$this->input->post('name'),
        'record_id'=>$this->input->post('record_id'),
        'company_name'=>$this->input->post('company_name'),
        'brand_name'=>$this->input->post('brand_name'),
        'sales_person'=>$this->input->post('sales_person'),
       'email'=>$this->input->post('email'),
       'primary_email'=>$this->input->post('primaryemail'),
       'secondary_email'=>$this->input->post('secondaryemail'),
       'address'=>$this->input->post('address'),
       'address_line2'=>$this->input->post('address_line2'),
       'address_line3'=>$this->input->post('address_line3'),
       'address_line4'=>$this->input->post('address_line4'),
       'address_city'=>$this->input->post('address_city'),
       'address_postcode'=>$this->input->post('address_postcode'),

       'delivery_address'=>$this->input->post('delivery_address'),
       'delivery_address_line2'=>$this->input->post('delivery_address_line2'),
       'delivery_address_line3'=>$this->input->post('delivery_address_line3'),
       'delivery_address_line4'=>$this->input->post('delivery_address_line4'),
       'delivery_address_city'=>$this->input->post('delivery_city'),
       'delivery_address_postcode'=>$this->input->post('delivery_postcode'),

       'address2'=>$this->input->post('address2'),
       'address2_line2'=>$this->input->post('address2_line2'),
       'address2_line3'=>$this->input->post('address2_line3'),
       'address2_line4'=>$this->input->post('address2_line4'),
       'address2_city'=>$this->input->post('address2_city'),
       'address2_postcode'=>$this->input->post('address2_postcode'),

       'address3'=>$this->input->post('address3'),
       'address3_line2'=>$this->input->post('address3_line2'),
       'address3_line3'=>$this->input->post('address3_line3'),
       'address3_line4'=>$this->input->post('address3_line4'),
       'address3_city'=>$this->input->post('address3_city'),
       'address3_postcode'=>$this->input->post('address3_postcode'),
       
       'address4'=>$this->input->post('address4'),
       'address4_line2'=>$this->input->post('address4_line2'),
       'address4_line3'=>$this->input->post('address4_line3'),
       'address4_line4'=>$this->input->post('address4_line4'),
       'address4_city'=>$this->input->post('address4_city'),
       'address4_postcode'=>$this->input->post('address4_postcode'),

       'driver_memo' => $this->input->post('driver_memo'),
       'packer_memo' => $this->input->post('packer_memo'),
       'payment_terms'=> $this->input->post('payment_terms'),
 'is_archieve' => $is_archieve, // <-- Add this line
       'status'=>$this->input->post('status'),
       'contact'=>$this->input->post('contact'),
       'password'=>$this->input->post('password'),
       'role' => implode(',', $this->input->post('role')),

       'created_by' => $this->input->post('created_by'),
       'created_datee' => $created_date,
    );

    $status = $this->user_model->is_email_exists($data['email']);
        // echo 'heoo'.$status;
        // if($status!=false){
        //     // Email already exists, show an error message or take appropriate action
        //     $this->session->set_flashdata('error', '<div class="alert alert-danger alert-dismissible fade show" role="alert">Email already exists!
        //         <button type="button" class="close" data-dismiss="alert" aria-label="close"> <span aria-hidden="true">&times;</span></button></div>');
        //     redirect('Userscontroller');
        // } 
        // else{
    $this->user_model->insert_data($data,'user_register');
    $this->session->set_flashdata('created','<div class="alert alert-success alert-dismissible fade show" role="alert">User Created Successfully!
    <button type="button" class="close" data-dismiss="alert" arial-label="close"> <span aria-hidden="true">&times;</span></button></div>');
    redirect('Userscontroller');
       // }
    }

    public function updateuser($id){

        
    $role = $this->input->post('role');

    // Check if $role is a string, convert it to an array if needed
    if (!is_array($role)) {
        $role = explode(',', $role);
    }


    date_default_timezone_set('UTC');

		// Create a DateTime object for the current time
		$current_date_time = new DateTime('now');
			
			// Add 8 hours to adjust to Singapore time (GMT+8)
		$current_date_time->modify('+8 hours');
			
			// Format the datetime
		$created_date = $current_date_time->format('Y-m-d H:i:s');

        $is_archieve = ($this->input->post('status') == 1) ? 0 : 1;

    $data = array(
        'id'      => $id,
        'name'=>$this->input->post('name'),
        'record_id'=>$this->input->post('record_id'),
        'company_name'=>$this->input->post('company_name'),
        'brand_name'=>$this->input->post('brand_name'),
        'sales_person'=>$this->input->post('sales_person'),
        'email'=>$this->input->post('email'),
        'primary_email'=>$this->input->post('primaryemail'),
        'secondary_email'=>$this->input->post('secondaryemail'),
        'address'=>$this->input->post('address'),
        'address_line2'=>$this->input->post('address_line2'),
        'address_line3'=>$this->input->post('address_line3'),
        'address_line4'=>$this->input->post('address_line4'),
        'address_city'=>$this->input->post('address_city'),
        'address_postcode'=>$this->input->post('address_postcode'),
        'delivery_address'=>$this->input->post('delivery_address'),
        'delivery_address_line2'=>$this->input->post('delivery_address_line2'),
        'delivery_address_line3'=>$this->input->post('delivery_address_line3'),
        'delivery_address_line4'=>$this->input->post('delivery_address_line4'),
        'delivery_address_city'=>$this->input->post('delivery_city'),
        'delivery_address_postcode'=>$this->input->post('delivery_postcode'),

        'address2'=>$this->input->post('address2'),
        'address2_line2'=>$this->input->post('address2_line2'),
        'address2_line3'=>$this->input->post('address2_line3'),
        'address2_line4'=>$this->input->post('address2_line4'),
        'address2_city'=>$this->input->post('address2_city'),
        'address2_postcode'=>$this->input->post('address2_postcode'),

        'address3'=>$this->input->post('address3'),
        'address3_line2'=>$this->input->post('address3_line2'),
        'address3_line3'=>$this->input->post('address3_line3'),
        'address3_line4'=>$this->input->post('address3_line4'),
        'address3_city'=>$this->input->post('address3_city'),
        'address3_postcode'=>$this->input->post('address3_postcode'),

        'address4'=>$this->input->post('address4'),
        'address4_line2'=>$this->input->post('address4_line2'),
        'address4_line3'=>$this->input->post('address4_line3'),
        'address4_line4'=>$this->input->post('address4_line4'),
        'address4_city'=>$this->input->post('address4_city'),
        'address4_postcode'=>$this->input->post('address4_postcode'),

        'driver_memo' => $this->input->post('driver_memo'),
        'packer_memo' => $this->input->post('packer_memo'),
        'payment_terms'=> $this->input->post('payment_terms'),

        'status'=>$this->input->post('status'),
         'is_archieve' => $is_archieve, // <-- Add this line
        'contact'=>$this->input->post('contact'),
        'password'=>$this->input->post('password'),
        'role' => implode(',', $this->input->post('role')),
        'updated_by' => $this->input->post('updated_by'),
        'updated_date'=> created_date,
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
    public function update_restrict_time(){
        $userId = $this->input->post('userId');
        $isChecked = $this->input->post('isChecked');
    
        // Update the restrict_time column in the database
        $this->db->set('restrict_time', $isChecked);
        $this->db->where('id', $userId);
        $this->db->update('user_register');
    
        // Check if the update was successful
        if($this->db->affected_rows() > 0){
            // Return success response with isChecked value
            echo json_encode(array('status' => 'success', 'isChecked' => $isChecked));
        } else {
            // Return error response
            echo json_encode(array('status' => 'error'));
        }
    }


    public function importfile(){
        if ($this->input->post('submit')) {
            $path = './uploads/';
            require_once APPPATH . "/third_party/PHPExcel.php";
            $config['upload_path'] = $path;
            $config['allowed_types'] = 'xlsx|xls|csv';
            $config['remove_spaces'] = TRUE;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('uploadFile')) {
                $error = array('error' => $this->upload->display_errors());
            } else {
                $data = array('upload_data' => $this->upload->data());
            }
            if(empty($error)){
                if (!empty($data['upload_data']['file_name'])) {
                    $import_xls_file = $data['upload_data']['file_name'];
                } else {
                    $import_xls_file = 0;
                }
                $inputFileName = $path . $import_xls_file;
                try {
                    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                    $objPHPExcel = $objReader->load($inputFileName);
                    $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
                    $flag = true;
                    $i=0;
                    foreach ($allDataInSheet as $value) {
                        if($flag){
                            $flag =false;
                            continue;
                        }   
                        $inserdata[$i]['company_name'] = $value['A'];
                        $inserdata[$i]['name'] = $value['A'];

                        $inserdata[$i]['address'] = $value['B'];
                        $inserdata[$i]['address_line2'] = $value['C'];

                        $inserdata[$i]['address_line3'] = $value['D'];
                        $inserdata[$i]['address_line4'] = $value['E'];

                        $inserdata[$i]['address_city'] = $value['F'];
                        $inserdata[$i]['address_postcode'] = $value['G'];

                        $inserdata[$i]['email'] = $value['H'];

                       
                        $inserdata[$i]['delivery_address'] = $value['I'];
                        $inserdata[$i]['delivery_address_line2'] = $value['J'];

                        $inserdata[$i]['delivery_address_line3'] = $value['K'];
                        $inserdata[$i]['delivery_address_line4'] = $value['L'];

                        $inserdata[$i]['delivery_address_city'] = $value['M'];
                        $inserdata[$i]['delivery_address_postcode'] = $value['N'];

                        $inserdata[$i]['address2'] = $value['O'];
                        $inserdata[$i]['address2_line2'] = $value['P'];

                        $inserdata[$i]['address2_line3'] = $value['Q'];
                        $inserdata[$i]['address2_line4'] = $value['R'];

                        $inserdata[$i]['address3'] = $value['S'];
                        $inserdata[$i]['address3_line2'] = $value['T'];

                        $inserdata[$i]['address3_line3'] = $value['U'];
                        $inserdata[$i]['address3_line4'] = $value['V'];

                        $inserdata[$i]['address4'] = $value['W'];
                        $inserdata[$i]['address4_line2'] = $value['X'];

                        $inserdata[$i]['address4_line3'] = $value['Y'];
                        $inserdata[$i]['address4_line4'] = $value['Z'];

                        $inserdata[$i]['payment_terms'] = $value['AA'];

                        $inserdata[$i]['sales_person'] = $value['AB'];
                        $inserdata[$i]['record_id'] = $value['AC'];

                        $inserdata[$i]['role'] = 'User';
                        $inserdata[$i]['status'] = '0';
                        $inserdata[$i]['restrict_time'] = '1';
                        $i++;
                    }
                    $result = $this->user_model->insert_import($inserdata);
                    if($result){
                        $this->session->set_flashdata('imported','<div class="alert alert-success alert-dismissible fade show" role="alert">Users Imported Successfully!
                        <button type="button" class="close" data-dismiss="alert" arial-label="close"> <span aria-hidden="true">&times;</span></button></div>');
                        redirect('Userscontroller');
                    }
                } catch (Exception $e) {
                    die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
                            . '": ' .$e->getMessage());
                }
            }
        }
        $this->load->view('import');
    }
    
     public function ArchiveUser() {
        $data['title'] = 'Dashboard';
        $config['base_url'] = site_url('Userscontroller/searchArchive');
        $config['total_rows'] = $this->user_model->count_all_users_archive(); 
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

        $config['total_rows'] = $this->user_model->count_all_users_archive();

	    $this->pagination->initialize($config);

       $data['userss'] = $this->user_model->get_users_archive();

        $this->load->view('template/header.php', $data);
        $user = $this->session->userdata('user_register');
        $users = $this->session->userdata('normal_user');
        $loginuser = $this->session->userdata('LoginSession');

        $data['total_rows'] = $this->user_model->count_all_users_archive();

      

        if (isset($loginuser['roles']) && !empty($loginuser['roles']) && $loginuser['roles'] == 'Admin') {
            $data['total_rows'] = $this->user_model->count_all_users_archive();
            $this->pagination->initialize($config);
        } elseif (isset($loginuser['roles']) && !empty($loginuser['roles']) && $loginuser['roles'] == 'User') {
            $data['total_rows'] = 1;
        } else {
            $data['total_rows'] = $this->user_model->count_all_users_archive();
            $this->pagination->initialize($config);
        }
       
        $this->load->view('template/header.php');
        $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
        $this->load->view('template/users_archive.php', $data);
        $this->load->view('template/footer.php');
    }
    
    public function searchArchive() {
        $keyword = $this->input->get('keyword');
        $data['title'] = 'Dashboard';
    
        // Pagination Config for Search Results
        $config['base_url'] = site_url('Userscontroller/searchArchive');
        $config['total_rows'] = max($this->user_model->count_search_results_archive($keyword), 10); // Ensure at least 10 total rows
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

        $data['keyword'] = $keyword;

        $config['total_rows'] = $this->user_model->count_all_users_archive();

	    $this->pagination->initialize($config);
        $data['userss'] = $this->user_model->search_users_archive($keyword, $config['per_page'], $offset);
        $data['total_rows'] = $this->user_model->count_search_results_archive($keyword);
        
        $this->load->view('template/header.php', $data);
        $user = $this->session->userdata('user_register');
        $users = $this->session->userdata('normal_user');
        $loginuser = $this->session->userdata('LoginSession');
        //var_dump($loginuser);
        $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
        $this->load->view('template/users_archive.php', $data);
        $this->load->view('template/footer.php');
    }
    
        public function update_status_active(){
        $userId = $this->input->post('userId');
        $isChecked = $this->input->post('isChecked');
    
        // Update the restrict_time column in the database
        $this->db->set('status', 1);
        $this->db->set('is_archieve', 0);
        $this->db->where('id', $userId);
        $this->db->update('user_register');
    
        // Check if the update was successful
        if($this->db->affected_rows() > 0){
            // Return success response with isChecked value
            echo json_encode(array('status' => 'success', 'isChecked' => $isChecked));
        } else {
            // Return error response
            echo json_encode(array('status' => 'error'));
        }
    }

     public function update_status_inactive(){
        $userId = $this->input->post('userId');
        $isChecked = $this->input->post('isChecked');
    
        // Update the restrict_time column in the database
        $this->db->set('status', 0);
        $this->db->set('is_archieve', 1);
        $this->db->where('id', $userId);
        $this->db->update('user_register');
    
        // Check if the update was successful
        if($this->db->affected_rows() > 0){
            // Return success response with isChecked value
            echo json_encode(array('status' => 'success', 'isChecked' => $isChecked));
        } else {
            // Return error response
            echo json_encode(array('status' => 'error'));
        }
    }

    
   
}

?>