<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Productcontroller extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('url','html','form'));
        $this->load->model('user_model');
        $this->load->model('product_model');
        $this->load->library('pagination');
        $this->load->library('session');
        $this->load->library('upload');

    }

    public function index() {
        $data['title'] = 'Products';

        $config['base_url'] = site_url('Productcontroller/search');
        $config['total_rows'] = $this->product_model->count_all_products(); // Ensure at least 10 total rows
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
        
        $config['total_rows'] = $this->product_model->count_all_products(); 
    
        $this->pagination->initialize($config);

        $this->load->view('template/header.php', $data);
        $user = $this->session->userdata('user_register');
        $users = $this->session->userdata('normal_user');
        $loginuser = $this->session->userdata('LoginSession');
        $data['total_rows'] = $this->product_model->count_all_products();
        $data['products'] = $this->product_model->product_details();



        $this->load->view('template/header.php');
        $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
        $this->load->view('product/products.php', $data);
        $this->load->view('template/footer.php');
    }

    public function search() {
        $keyword = $this->input->get('keyword');
        $data['title'] = 'Dashboard';
    
        // Pagination Config for Search Results
        $config['base_url'] = site_url('Productcontroller/search');
        $config['total_rows'] = max($this->product_model->count_search_results($keyword), 10); // Ensure at least 10 total rows
        $config['per_page'] = 10;
        $config['use_page_numbers'] = TRUE;
        $config['reuse_query_string'] = TRUE;
    
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
        $data['products'] = $this->product_model->search_products($keyword, $config['per_page'], $offset);
        $data['total_rows'] = $this->product_model->count_search_results($keyword);
    
        $this->pagination->initialize($config);
        $data['keyword'] = $keyword;
    
        $this->load->view('template/header.php', $data);
        $user = $this->session->userdata('user_register');
        $users = $this->session->userdata('normal_user');
        $loginuser = $this->session->userdata('LoginSession');
        //var_dump($loginuser);
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

        date_default_timezone_set('UTC');

		// Create a DateTime object for the current time
		$current_date_time = new DateTime('now');
			
			// Add 8 hours to adjust to Singapore time (GMT+8)
		$current_date_time->modify('+8 hours');
			
			// Format the datetime
		$created_date = $current_date_time->format('Y-m-d H:i:s');
    
        $data = array(
            'product_id' => $this->input->post('product_id'),
            'product_name' => $this->input->post('product_name'),
            'product_desc' => $this->input->post('product_desc'),
            'prod_category' => $this->input->post('prod_category'),
            'add_on_slice' => $this->input->post('add_on_slice'),
            'add_on_seed' => $this->input->post('add_on_seed'),
            'active' => $this->input->post('active'),
            'min_order' => $this->input->post('min_order'),
            'prod_img' => $image_path, // Image path
            'prod_rate' => $this->input->post('prod_rate'),
            'deleted'=> '0',
            'created_by' => $this->input->post('created_by'),
            'created_date' => $created_date,            
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
            'id'=>$id, 
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

        date_default_timezone_set('UTC');

		// Create a DateTime object for the current time
		$current_date_time = new DateTime('now');
			
			// Add 8 hours to adjust to Singapore time (GMT+8)
		$current_date_time->modify('+8 hours');
			
			// Format the datetime
		$updated_date = $current_date_time->format('Y-m-d H:i:s');


        $data = array(
            'id'      => $id,
            'product_id' => $this->input->post('product_id'),
            'product_name' => $this->input->post('product_name'),
            'product_desc' => $this->input->post('product_desc'),
            'prod_category' => $this->input->post('prod_category'),
            'add_on_slice' => $this->input->post('add_on_slice'),
            'add_on_seed' => $this->input->post('add_on_seed'),
            'active' => $this->input->post('active'),
            'min_order' => $this->input->post('min_order'),
            'prod_img' => $image_path, // Image path
            'prod_rate' => $this->input->post('prod_rate'),
            'deleted'=> '0',
            'updated_by' =>  $this->input->post('updated_by'),
            'updated_date' => $updated_date,
        );
       
         $this->product_model->update_datas($data,'products');
         $this->session->set_flashdata('updated','<div class="alert alert-success alert-dismissible fade show" role="alert">Product Updated Successfully!
         <button type="button" class="close" data-dismiss="alert" arial-label="close"> <span aria-hidden="true">&times;</span></button></div>');
         redirect('productcontroller');
         }
         
         
         public function userproduct() {
            $data['title'] = 'Products';
            $this->load->view('template/header.php', $data);
            $user = $this->session->userdata('user_register');
            $users = $this->session->userdata('normal_user');
            $loginuser = $this->session->userdata('LoginSession');
            $data['products'] = $this->product_model->get_products();
            $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
            $this->load->view('user_prod/user_products.php',$data);
            $this->load->view('template/footer.php');
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
                            $inserdata[$i]['product_id'] = $value['A'];
                            $inserdata[$i]['product_name'] = $value['B'];
                            $inserdata[$i]['prod_category'] = $value['C'];
                            $inserdata[$i]['prod_rate'] = $value['D'];
                            $inserdata[$i]['active'] = $value['E'];
                            $inserdata[$i]['min_order'] = $value['F'];
                            $i++;
                        }
                        $result = $this->product_model->insert_import($inserdata);
                        if($result){
                            $this->session->set_flashdata('imported','<div class="alert alert-success alert-dismissible fade show" role="alert">Products Imported Successfully!
                            <button type="button" class="close" data-dismiss="alert" arial-label="close"> <span aria-hidden="true">&times;</span></button></div>');
                            redirect('productcontroller');
                        }else{
                            echo "ERROR !";
                        }
                    } catch (Exception $e) {
                        die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
                                . '": ' .$e->getMessage());
                    }
                }else{
                    echo $error['error'];
                }
            }
            $this->load->view('import');
        }

        public function update_slice(){
            $productid = $this->input->post('productid');
            $isChecked = $this->input->post('isChecked');
        
            // Update the restrict_time column in the database
            $this->db->set('add_on_slice', $isChecked);
            $this->db->where('id', $productid);
            $this->db->update('products');
        
            // Check if the update was successful
            if($this->db->affected_rows() > 0){
                // Return success response with isChecked value
                echo json_encode(array('status' => 'success', 'isChecked' => $isChecked));
            } else {
                // Return error response
                echo json_encode(array('status' => 'error'));
            }
        }


        public function update_seed(){
            $productid = $this->input->post('productid');
            $isChecked = $this->input->post('isChecked');
        
            // Update the restrict_time column in the database
            $this->db->set('add_on_seed', $isChecked);
            $this->db->where('id', $productid);
            $this->db->update('products');
        
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