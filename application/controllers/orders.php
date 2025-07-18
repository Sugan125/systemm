<?php
use Dompdf\Dompdf;
include APPPATH . 'third_party/tcpdf/PDFMerger.php';

use PDFMerger\PDFMerger;
defined('BASEPATH') OR exit('No direct script access allowed');

class orders extends CI_Controller {

	function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('order_model');
		$this->load->model('user_model');
		$this->load->model('excel_export_model');
        $this->load->library('pagination');
        $this->load->library('session');
		$this->load->library('email');
		
    }

    public function index() {
        $data['title'] = 'orders';
    
        $loginuser = $this->session->userdata('LoginSession');
        $data['user_id'] = $loginuser['id'];
    
        // Pagination configuration
        $config['base_url'] = site_url('orders/index');
        $config['total_rows'] = $this->order_model->count_user_orders($data['user_id']); // Adjust this function in your model to count orders for the specific user
        
        $data['total_rows'] = $this->order_model->count_user_orders($data['user_id']); // Adjust this function in your model to count orders for the specific user
       
      
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
    
        $this->pagination->initialize($config);
    
        // Fetch the paginated results
        $data['orders'] = $this->order_model->get_user_orders($data['user_id'], $config['per_page'], $offset); // Adjust this function in your model to fetch orders for the specific user with limit and offset
    
        // Pagination links
        $data['pagination'] = $this->pagination->create_links();
    
        // Load views
        $this->load->view('template/header.php', $data);
        $user = $this->session->userdata('user_register');
        $users = $this->session->userdata('normal_user');
        $loginuser = $this->session->userdata('LoginSession');
        $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data, 'loginuser' => $loginuser));
        $this->load->view('orders/view_order.php', $data);
        $this->load->view('template/footer.php');
    }
    
		public function create() {
			$data['title'] = 'Orders';
		
			$this->form_validation->set_rules('product[]', 'Product name', 'trim|required');
		
			if ($this->form_validation->run() == TRUE) {
				$order_id = $this->order_model->create();
				$bill_no = $order_id['bill_no'];
				$email = $order_id['email'];

					$order_id = $order_id['order_id'];
					
					$this->download($order_id); 
					//$this->send_invoice($bill_no,$email); 
					//exit;
					$this->session->set_flashdata('success', 'Order Placed');
        			redirect('orders', 'refresh');
					
					
				
			} 

			else {
				$data['products'] = $this->order_model->getActiveProductData();
				$data['category'] = $this->order_model->getActivecatergoryData();

				$this->load->view('template/header.php', $data);
				$user = $this->session->userdata('user_register');
				$users = $this->session->userdata('normal_user');
				$loginuser = $this->session->userdata('LoginSession');
		
				$this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
				$this->load->view('orders/create_order.php', $data);
				$this->load->view('template/footer.php');
			}
		}

        public function createfrozen() {
			$data['title'] = 'Orders';
		
			$this->form_validation->set_rules('product[]', 'Product name', 'trim|required');
		
			if ($this->form_validation->run() == TRUE) {
				$order_id = $this->order_model->create();
				$bill_no = $order_id['bill_no'];
				$email = $order_id['email'];

					$order_id = $order_id['order_id'];
					
					$this->download($order_id); 
					//$this->send_invoice($bill_no,$email); 
					//exit;
					$this->session->set_flashdata('success', 'Order Placed');
        			redirect('orders', 'refresh');
					
					
				
			} 

			else {
				$data['products'] = $this->order_model->getActiveProductData();
				$data['category'] = $this->order_model->getcategoryfrozen();

				$this->load->view('template/header.php', $data);
				$user = $this->session->userdata('user_register');
				$users = $this->session->userdata('normal_user');
				$loginuser = $this->session->userdata('LoginSession');
		
				$this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
				$this->load->view('orders/createfrozen.php', $data);
				$this->load->view('template/footer.php');
			}
		}
	
    public function getProductsByCategory() {
        $categoryId = $this->input->post('category_id');
        $products = $this->order_model->getProductsByCategory($categoryId);
        echo json_encode($products);
    }


    public function getProductsByCategoryadmin() {
        $categoryId = $this->input->post('category_id');
        $products = $this->order_model->getProductsByCategoryadmin($categoryId);
        echo json_encode($products);
    }

    public function getTableProductRow()
	{
		$products = $this->order_model->getActiveProductData();
		echo json_encode($products);
	}

    public function getProductValueById()
	{
		$product_id = $this->input->post('product_id');
		if($product_id) {
			$product_data = $this->order_model->getProductData($product_id);
			echo json_encode($product_data);
		}
	}
    public function update($id, $user_id)
	{

		$this->data['page_title'] = 'Update Order';

		$this->form_validation->set_rules('qty[]', 'qty', 'trim|required');


        if ($this->form_validation->run() == TRUE) {

        	$update = $this->order_model->update($id,$user_id);

			$bill_no = $update['bill_no'];

			$email = $update['email'];

			$order_id = $update['order_id'];

			
					
			$this->download($order_id); 
			
			//$this->send_invoice($bill_no,$email); 


			$this->session->set_flashdata('success', 'Order Updated Successfully');


			redirect('orders/manage_orders', 'refresh');
        }
        else {

        	// $loginuser = $this->session->userdata('LoginSession');
	
			// $data['user_id'] = $loginuser['id'];

			// $user_id = $data['user_id'];

			$orders_data = $this->order_model->getOrdersDatas($user_id,$id);

			foreach ($orders_data as $order) {
				$orders_item = $this->order_model->getOrdersItemDatasedit($id);
			}
			
			
			foreach ($orders_item as $k => $v) {
				$result['order_item'][] = $v;
			}

			$result['order'] = $orders_data;

			
			$data['order_data'] = $result;
			$data['order_total'] = $this->order_model->getOrdertotal($id);

			// echo '<pre>';
			// print_r($data['order_data']);
			
			
			// 	// Accessing the array at index 0
			$order_total_data = $data['order_total'][0];

			$data['products'] = $this->order_model->getActiveProductData();
            $data['category'] = $this->order_model->getActivecatergoryDataadmin();



            $this->load->view('template/header.php', $data);
            $user = $this->session->userdata('user_register');
            $users = $this->session->userdata('normal_user');
			$loginuser = $this->session->userdata('LoginSession');
			$loginusers = $this->order_model->getuseraddress($user_id);

			$this->load->view('template/header.php', $data);
			$this->load->view('template/sidebar.php', [
				'user' => $user,
				'users' => $users,
				'data' => $data,
				'loginuser' => $loginuser,
				'loginusers' => $loginusers,
			]);
			$this->load->view('orders/edit.php', array_merge($data, ['id' => $id, 'user_id' => $user_id]));
			$this->load->view('template/footer.php');
		
        }
	}

    public function fetchOrdersData()
	{

        
		$result = array('data' => array());

		$data = $this->order_model->getOrdersData();

		foreach ($data as $key => $value) {


			$count_total_item = $this->order_model->countOrderItem($value['id']);
			$date = date('d-m-Y', $value['date_time']);
			$time = date('h:i a', $value['date_time']);

			$date_time = $date . ' ' . $time;

			// button
			$buttons = '';

			if(in_array('viewOrder', $this->permission)) {
				$buttons .= '<a target="__blank" href="'.base_url('index.php/orders/printDiv/'.$value['id']).'" class="btn btn-warning"><i class="fa fa-print"></i></a>';
			}

			if(in_array('updateOrder', $this->permission)) {
				$buttons .= ' <a href="'.base_url('index.php/orders/update/'.$value['id']).'" class="btn btn-info"><i class="fa fa-pencil"></i></a>';
			}

			if(in_array('deleteOrder', $this->permission)) {
				$buttons .= ' <button type="button" class="btn btn-danger" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
			}

			// if($value['paid_status'] == 1) {
			// 	$paid_status = '<span class="label label-success">Paid</span>';
			// }
			// else {
			// 	$paid_status = '<span class="label label-danger">Not Paid</span>';
			// }

			$result['data'][$key] = array(
				$value['bill_no'],
				$date_time,
				$count_total_item,
				$value['net_amount'],
				// $paid_status,
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}

  
	public function printDiv($id)
	{
    $data['print'] = 'print';

	$loginuser = $this->session->userdata('LoginSession');
	
	$data['user_id'] = $loginuser['id'];

	$user_id = $data['user_id'];

    $orders_data = $this->order_model->getOrdersDatas($user_id,$id);

	foreach ($orders_data as $order) {
		$orders_item = $this->order_model->getOrdersItemDatas($id);
	}
	
	
    foreach ($orders_item as $k => $v) {
        $result['order_item'][] = $v;
    }

    $result['order'] = $orders_data;

	
    $data['order_data'] = $result;
    $data['order_total'] = $this->order_model->getOrdertotal($id);

	// echo '<pre>';
	// print_r($data['order_data']);
	
	
	// 	// Accessing the array at index 0
	$order_total_data = $data['order_total'][0];



	// Extracting datetime and discount values
	$order_date = '';
	if ($order_total_data['date_time'] !== null && is_numeric($order_total_data['date_time'])) {
		// Check if $order_total_data['date_time'] is a valid timestamp
		$order_date = date('d/m/Y', $order_total_data['date_time']);
	}

	
    $this->load->view('template/header.php', $data);
    $user = $this->session->userdata('user_register');
    $users = $this->session->userdata('normal_user');
    $loginuser = $this->session->userdata('LoginSession');
    $this->load->view('invoice/print_invoice.php', array('data' => $data, 'order_date' => $order_date, 'result'));
    $this->load->view('template/footer.php');
}

public function printadmin($id)
	{
    $data['print'] = 'print';

	$loginuser = $this->session->userdata('LoginSession');
	
	$data['user_id'] = $loginuser['id'];

	$user_id = $data['user_id'];

    $orders_data = $this->order_model->getOrdersadmin($id);

	

	foreach ($orders_data as $order) {
		$orders_item = $this->order_model->getadminorderdata($id);
	}
	
	
    foreach ($orders_item as $k => $v) {
        $result['order_item'][] = $v;
    }

    $result['order'] = $orders_data;

    $data['order_data'] = $result;
    $data['order_total'] = $this->order_model->getorderadmintotal($id);

	
	$order_total_data = $data['order_total'][0];


	$order_date = ($order_total_data['date_time'] !== null) ? date('d/m/Y', $order_total_data['date_time']) : '';
	
    $this->load->view('template/header.php', $data);
    $user = $this->session->userdata('user_register');
    $users = $this->session->userdata('normal_user');
    $loginuser = $this->session->userdata('LoginSession');
    
	$this->load->view('invoice/print_invoice.php', array('data' => $data, 'order_date' => $order_date, 'result'=>$result));
		
    $this->load->view('template/footer.php');
}
public function download($id)
{
    $data['print'] = 'print';

    $loginuser = $this->session->userdata('LoginSession');
    
    $data['user_id'] = $loginuser['id'];

    $user_id = $data['user_id'];

    $orders_data = $this->order_model->getOrdersadmin($id);

    foreach ($orders_data as $order) {
        $orders_item = $this->order_model->getadminorderdata($id);
    }
    
    foreach ($orders_item as $k => $v) {
        $result['order_item'][] = $v;
    }

    $result['order'] = $orders_data;

    $data['order_data'] = $result;
    $data['order_total'] = $this->order_model->getorderadmintotal($id);

    if (is_array($data['order_total']) && !empty($data['order_total'])) {
        $bill_no = $data['order_total'][0]['bill_no'];
        $do_bill_no = $data['order_total'][0]['do_bill_no'];
    }
    
    $order_total_data = $data['order_total'][0];

    $order_date = ($order_total_data['date_time'] !== null) ? date('d/m/Y', $order_total_data['date_time']) : '';
    
    $this->load->view('template/header.php', $data);
    $user = $this->session->userdata('user_register');
    $users = $this->session->userdata('normal_user');
    $loginuser = $this->session->userdata('LoginSession');
    
    $html = $this->load->view('invoice/download_invoice.php', array('data' => $data, 'order_date' => $order_date, 'result'=>$result), true);
    
    $html_do = $this->load->view('invoice/download_do.php', array('data' => $data, 'order_date' => $order_date, 'result'=>$result), true);
    
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper(array(0, 0, 840, 1188), 'portrait');    
    $dompdf->render();

    $filename = 'invoice_' . $bill_no . '.pdf';
    $filepath = FCPATH . 'files/' . $filename;
    file_put_contents($filepath, $dompdf->output());

    // Generate and download the DO invoice
    $dompdf_do = new Dompdf();
    $dompdf_do->loadHtml($html_do);
    $dompdf_do->setPaper(array(0, 0, 840, 1188), 'portrait');    
    $dompdf_do->render();

    $filename_do = $do_bill_no . '.pdf';
	$filepath_do = FCPATH . 'files/DO/' . $filename_do;
    file_put_contents($filepath_do, $dompdf_do->output());

    $this->load->view('template/footer.php');
}

public function manage_orders() {
    $data['title'] = 'orders';

    // Pagination configuration
    $config['base_url'] = site_url('orders/manage_orders');
    $config['total_rows'] = $this->order_model->count_all_orders(); // Ensure total rows are counted for the current month
    $config['per_page'] = 10;
    $config['uri_segment'] = 3;
    $config['use_page_numbers'] = TRUE;

    // Pagination styling
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

    // Initialize pagination
    $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
    $offset = ($page - 1) * $config['per_page'];

    $this->pagination->initialize($config);

    $loginuser = $this->session->userdata('LoginSession');
    $data['user_id'] = $loginuser['id'];

    // Fetch orders for the current month with pagination
    $data['orders'] = $this->order_model->getmanageorder($config['per_page'], $offset);
    $data['total_rows'] = $this->order_model->count_all_orders();
	$data['userss'] = $this->user_model->get_allusers($loginuser['id']);
    // Load views
    $this->load->view('template/header.php', $data);
    $user = $this->session->userdata('user_register');
    $users = $this->session->userdata('normal_user');
    $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data, 'loginuser' => $loginuser));
    $this->load->view('orders/manage_order.php', $data);
    $this->load->view('template/footer.php');
}



public function printschedule()
	{
    $data['print'] = 'print';

	$loginuser = $this->session->userdata('LoginSession');
	
	$data['user_id'] = $loginuser['id'];

	$user_id = $data['user_id'];

	$data['orders'] = $this->order_model->getmanageorder();

    $this->load->view('template/header.php', $data);
    $user = $this->session->userdata('user_register');
    $users = $this->session->userdata('normal_user');
    $loginuser = $this->session->userdata('LoginSession');
	$this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
	$this->load->view('invoice/print_schedule.php', array('data' => $data,));
	$this->load->view('template/footer.php');
}


public function showschedule()
	{
    $data['print'] = 'print';

	$schedule_date = $this->input->post('schedule_date');

	$loginuser = $this->session->userdata('LoginSession');
	
	$data['user_id'] = $loginuser['id'];

	$user_id = $data['user_id'];

	$data['orders'] = $this->order_model->getscheduleorder($schedule_date);
	
	//print_r($data['orders']);
    $this->load->view('template/header.php', $data);
    $user = $this->session->userdata('user_register');
    $users = $this->session->userdata('normal_user');
    $loginuser = $this->session->userdata('LoginSession');
	$this->load->view('invoice/show_schedule.php', array('data' => $data, 'schedule_date' => $schedule_date));
	$this->load->view('template/footer.php');
}


public function showpacking()
	{
    $data['print'] = 'print';

	$schedule_date = $this->input->post('schedule_date');

	$loginuser = $this->session->userdata('LoginSession');
	
	$data['user_id'] = $loginuser['id'];

	$user_id = $data['user_id'];

	$data['orders'] = $this->order_model->getpackingorder($schedule_date);
	
	//print_r($data['orders']);
    $this->load->view('template/header.php', $data);
    $user = $this->session->userdata('user_register');
    $users = $this->session->userdata('normal_user');
    $loginuser = $this->session->userdata('LoginSession');
	$this->load->view('invoice/show_packing.php', array('data' => $data, 'schedule_date' => $schedule_date));
	$this->load->view('template/footer.php');
}


public function printpacking()
	{
		$data['print'] = 'print';

		$loginuser = $this->session->userdata('LoginSession');
		
		$data['user_id'] = $loginuser['id'];
	
		$user_id = $data['user_id'];
	
		$data['orders'] = $this->order_model->getmanageorder();
		
		$this->load->view('template/header.php', $data);
		$user = $this->session->userdata('user_register');
		$users = $this->session->userdata('normal_user');
		$loginuser = $this->session->userdata('LoginSession');
		$this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
		$this->load->view('invoice/print_packing.php', array('data' => $data,));
		$this->load->view('template/footer.php');
}



// public function send_invoice($bill_no,$email)
// 	{
	

// 		//$bill_no = '24059014';
		
// 		$toemail= $email;
// 		//$cc_email = $email;
		
// 		$config['protocol']  = 'smtp';
// 		$config['smtp_host'] = 'ssl://smtp.gmail.com';
// 		$config['smtp_port'] = '465';
// 		$config['smtp_timeout'] = '7';
// 		$config['smtp_user']  = 'mailto:suganyaulagu8@gmail.com';
// 		$config['smtp_pass'] = 'qqcb mupl eyeb azdo';
// 		$config['charset'] = 'utf-8';
// 		$config['newline']  = "\r\n";
// 		$config['mailtype'] = 'text'; 
// 		$config['validation'] = TRUE;
// 		$this->email->initialize($config);
// 		$from_email = 'suganyaulagu8@gmail.com';
		
	
// 		$this->email->initialize($config);
	

// 		$subject = "Invoice Attached , Invoice No:  $bill_no";
		
// 		date_default_timezone_set('Asia/Singapore');


// 		$current_date_time = date('Y-m-d H:i:s');

// 		$msg = "Hi, Please find the attached invoice for your review and processing: Invoice No:  $bill_no, Date:  $current_date_time


// Best regards,
// The Sourdough Factory Team";

// 						$flashdataMessage = 'Please check your email for new password!';
// 						$this->email->from($from_email, 'Sourdough Factory');
// 						$this->email->to($toemail);
// 						//$this->email->cc($cc_email); 
// 						$this->email->subject($subject);
// 						$this->email->message($msg);
					
// 					//	$filepdath = FCPATH . 'files/' . $filename;
// 						$file_path = 'C:\xampp\htdocs\systemm\files\invoice_' . $bill_no . '.pdf';
// 						$this->email->attach($file_path);
// 						$this->email->send();


// 	}


	public function repeat_order($id)
	{

	
		$this->data['page_title'] = 'Update Order';

		$this->form_validation->set_rules('gross_amount_value', 'gross_amount_value', 'trim|required');


        if ($this->form_validation->run() == TRUE) {

        	$update = $this->order_model->repeat_order($id);

			$bill_no = $update['bill_no'];

			$email = $update['email'];

					$order_id = $update['order_id'];
					
					$this->download($order_id); 
					//$this->send_invoice($bill_no,$email); 

			$this->session->set_flashdata('success', 'Order Placed Successfully');
			redirect('orders', 'refresh');
        }
        else {

        	$loginuser = $this->session->userdata('LoginSession');
	
			$data['user_id'] = $loginuser['id'];

			$user_id = $data['user_id'];

			$orders_data = $this->order_model->getOrdersDatas($user_id,$id);

			foreach ($orders_data as $order) {
				$orders_item = $this->order_model->getOrdersItemDatasedit($id);
			}
			
			
			foreach ($orders_item as $k => $v) {
				$result['order_item'][] = $v;
			}

			$result['order'] = $orders_data;

			
			$data['order_data'] = $result;
			$data['order_total'] = $this->order_model->getOrdertotal($id);

			// echo '<pre>';
			// print_r($data['order_data']);
			
			
			// 	// Accessing the array at index 0
			$order_total_data = $data['order_total'][0];

			$data['products'] = $this->order_model->getActiveProductData();
            $data['category'] = $this->order_model->getActivecatergoryData();



            $this->load->view('template/header.php', $data);
            $user = $this->session->userdata('user_register');
            $users = $this->session->userdata('normal_user');
            $loginuser = $this->session->userdata('LoginSession');
            //var_dump($loginuser);
            $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
            $this->load->view('orders/repeat_order.php', $data);
            $this->load->view('template/footer.php');
        }
	}
	public function order_restrict(){
		$data['title'] = 'orders';
	
		$loginuser = $this->session->userdata('LoginSession');
	
		$data['user_id'] = $loginuser['id'];
	
		$data['orders'] = $this->order_model->getmanageorder();
	
		$this->load->view('template/header.php', $data);
		$user = $this->session->userdata('user_register');
		$users = $this->session->userdata('normal_user');
		$loginuser = $this->session->userdata('LoginSession');
		//var_dump($loginuser);
		$this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
		$this->load->view('orders/order_restrict.php', $data);
		$this->load->view('template/footer.php');
	}

	public function admin_orders() {
		$data['title'] = 'Orders';
	
		$this->form_validation->set_rules('product[]', 'Product name', 'trim|required');
	
		if ($this->form_validation->run() == TRUE) {
			$order_id = $this->order_model->admin_create();
			$bill_no = $order_id['bill_no'];

			$email = $order_id['email'];
			
				$order_id = $order_id['order_id'];
				
				$this->download($order_id); 
				//$this->send_invoice($bill_no,$email); 
				//exit;
				$this->session->set_flashdata('success', 'Order Placed Successfully');
				redirect('orders/manage_orders', 'refresh');
		} 

		else {
			$data['products'] = $this->order_model->getActiveProductData();
			$data['category'] = $this->order_model->getActivecatergoryDataadmin();

			$this->load->view('template/header.php', $data);
			$user = $this->session->userdata('user_register');
			$users = $this->session->userdata('normal_user');
            $loginuser = $this->session->userdata('LoginSession');
           
			$data['userss'] = $this->user_model->get_activeusers($loginuser['id']);
			$loginuser = $this->session->userdata('LoginSession');
	
			$this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
			$this->load->view('orders/admin_order.php', $data);
			$this->load->view('template/footer.php');
		}
	}

	public function manage_search() {
        $keyword = $this->input->get('keyword');
        $data['title'] = 'Orders';
    
        // Pagination Config for Search Results
        $config['base_url'] = site_url('orders/manage_search');
        $config['total_rows'] = max($this->order_model->count_search_results($keyword), 10); // Ensure at least 10 total rows
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
        $data['orders'] = $this->order_model->search_orders($keyword, $config['per_page'], $offset);
        $data['total_rows'] = $this->order_model->count_search_results($keyword);
    
        $this->pagination->initialize($config);
        $data['keyword'] = $keyword;
    
        $this->load->view('template/header.php', $data);
        $user = $this->session->userdata('user_register');
        $users = $this->session->userdata('normal_user');
        $loginuser = $this->session->userdata('LoginSession');
        //var_dump($loginuser);
        $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
        $this->load->view('orders/manage_order.php', $data);
        $this->load->view('template/footer.php');
    }

	public function export_sales()
	{
    $data['print'] = 'print';

	$loginuser = $this->session->userdata('LoginSession');
	
	$data['user_id'] = $loginuser['id'];

	$user_id = $data['user_id'];

	$data["employee_data"] = $this->excel_export_model->fetch_data();

    $this->load->view('template/header.php', $data);
    $user = $this->session->userdata('user_register');
    $users = $this->session->userdata('normal_user');
    $loginuser = $this->session->userdata('LoginSession');
	$this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
	$this->load->view('invoice/export_data.php', array('data' => $data,));
	$this->load->view('template/footer.php');
}


public function print_invoice_bydate()
	{
    $data['print'] = 'print';

	$loginuser = $this->session->userdata('LoginSession');
	
	$data['user_id'] = $loginuser['id'];

	$user_id = $data['user_id'];

	$data["employee_data"] = $this->excel_export_model->fetch_data();

    $this->load->view('template/header.php', $data);
    $user = $this->session->userdata('user_register');
    $users = $this->session->userdata('normal_user');
    $loginuser = $this->session->userdata('LoginSession');
	$this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
	$this->load->view('invoice/invoice_bydate.php', array('data' => $data,));
	$this->load->view('template/footer.php');
}



public function print_do()
	{
    $data['print'] = 'print';

	$loginuser = $this->session->userdata('LoginSession');
	
	$data['user_id'] = $loginuser['id'];

	$user_id = $data['user_id'];

	$data["employee_data"] = $this->excel_export_model->fetch_data();

    $this->load->view('template/header.php', $data);
    $user = $this->session->userdata('user_register');
    $users = $this->session->userdata('normal_user');
    $loginuser = $this->session->userdata('LoginSession');
	$this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
	$this->load->view('invoice/print_do.php', array('data' => $data,));
	$this->load->view('template/footer.php');
}

public function donwloadinvoice()
{

    $data['print'] = 'print';

    $invoice_date = $this->input->post('invoice_date');

    $loginuser = $this->session->userdata('LoginSession');

    $data['user_id'] = $loginuser['id'];

    $user_id = $data['user_id'];

    $orders_data = $this->order_model->getinvoice($invoice_date);

    // Initialize an array to store file paths
    $file_paths = array();

    foreach ($orders_data as $row) {
        $filename = 'invoice_' . $row->bill_no . '.pdf';
        $filepath = FCPATH . 'files/' . $filename;

        if (file_exists($filepath)) {
            // Add file path to the array
            $file_paths[] = $filepath;
        } else {
            // Handle the case where the file doesn't exist
            echo "File not found for bill number: " . $row->bill_no;
        }
    }

    // Combine PDFs into a single PDF
    $combined_pdf_path = FCPATH . 'files/' . 'combined_invoices_' . $invoice_date . '.pdf';

    $pdf = new PDFMerger;

    foreach ($file_paths as $file_path) {
        $pdf->addPDF($file_path, 'all');
    }

    $pdf->merge('file', $combined_pdf_path);

    // Set the filename for the ZIP archive
    $zip_filename = 'combinedinvoices_' . $invoice_date . '.zip';

    // Set the appropriate headers for ZIP download
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $zip_filename . '"');

    // Create a new ZIP archive
    $zip = new ZipArchive;
    if ($zip->open($zip_filename, ZipArchive::CREATE) === TRUE) {
        // Add the combined PDF to the ZIP archive
        $zip->addFile($combined_pdf_path, basename($combined_pdf_path));

        // Close the ZIP archive
        $zip->close();

        // Output the ZIP archive
        readfile($zip_filename);

        // Remove the temporary ZIP file
        unlink($zip_filename);

        // Remove the combined PDF file
        unlink($combined_pdf_path);
    } else {
        // Handle the case where ZIP archive creation fails
        echo "Failed to create ZIP archive";
    }
}



public function downaloaddo()
{
    $data['print'] = 'print';

    $invoice_date = $this->input->post('invoice_date');

    $loginuser = $this->session->userdata('LoginSession');

    $data['user_id'] = $loginuser['id'];

    $user_id = $data['user_id'];

    $orders_data = $this->order_model->getdo($invoice_date);

    // Initialize an array to store file paths
    $file_paths = array();

    foreach ($orders_data as $row) {
        $filename = $row->do_bill_no . '.pdf';
        $filepath = FCPATH . 'files/DO/' . $filename;

        if (file_exists($filepath)) {
            // Add file path to the array
            $file_paths[] = $filepath;
        } else {
            // Handle the case where the file doesn't exist
            echo "File not found for bill number: " . $row->do_bill_no;
        }
    }

    // Set the filename for the ZIP archive
    $zip_filename = 'DOs_' . $invoice_date . '.zip';

    // Set the appropriate headers for ZIP download
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $zip_filename . '"');

    // Create a new ZIP archive
    $zip = new ZipArchive;
    if ($zip->open($zip_filename, ZipArchive::CREATE) === TRUE) {
        // Add files to the ZIP archive
        foreach ($file_paths as $file_path) {
            $file_name_in_zip = basename($file_path);
            $zip->addFile($file_path, $file_name_in_zip);
        }

        // Close the ZIP archive
        $zip->close();

        // Output the ZIP archive
        readfile($zip_filename);

        // Remove the temporary ZIP file
        unlink($zip_filename);
    } else {
        // Handle the case where ZIP archive creation fails
        echo "Failed to create ZIP archive";
    }
}

public function deleteorder($id) {
    $remarks = $this->input->get('remarks'); // or use post() if needed

    $delete = $this->order_model->deleteorder($id, $remarks);

    if ($delete) {
        $this->session->set_flashdata('deleted', 'Order Deleted Successfully');
    } else {
        $this->session->set_flashdata('error', 'Failed to delete order. Make sure remarks are provided and user is logged in.');
    }

    redirect('orders/manage_orders', 'refresh');
}

public function searchinvoice() {
    $keyword = $this->input->get('keyword');
    $data['title'] = 'Dashboard';

    // Load pagination library
    $this->load->library('pagination');

    // Pagination Config for Search Results
    $config['base_url'] = site_url('Orders/searchinvoice');
    $config['total_rows'] = $this->order_model->count_search_orders($keyword);
    $config['per_page'] = 10;
    $config['uri_segment'] = 3;
    $config['use_page_numbers'] = TRUE;

    // Include the keyword in the query string for pagination links
    $config['suffix'] = '?keyword=' . $keyword;
    $config['first_url'] = $config['base_url'] . '/1' . $config['suffix'];

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

    $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
    $offset = ($page - 1) * $config['per_page'];

    $data['keyword'] = $keyword;
    $data['config'] = $config;
    $data['offset'] = $offset;

    $this->pagination->initialize($config);

    $loginuser = $this->session->userdata('LoginSession');
    $data['user_id'] = $loginuser['id'];

    $data['orders'] = $this->order_model->search_orders($keyword, $config['per_page'], $offset);
    $data['total_rows'] = $this->order_model->count_search_orders($keyword);
    $data['userss'] = $this->user_model->get_allusers($loginuser['id']);
 
    $this->load->view('template/header.php', $data);
    $user = $this->session->userdata('user_register');
    $users = $this->session->userdata('normal_user');
    $loginuser = $this->session->userdata('LoginSession');
    $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data, 'loginuser' => $loginuser));
    $this->load->view('orders/manage_order.php', $data);
    $this->load->view('template/footer.php');
}


public function searchdate() {
    $keyword = $this->input->get('date');
    $data['title'] = 'Dashboard';

    // Load pagination library
    $this->load->library('pagination');

    // Pagination Config for Search Results
    $config['base_url'] = site_url('Orders/searchdate');
    $config['total_rows'] = $this->order_model->count_search_date($keyword);
    $config['per_page'] = 10;
    $config['uri_segment'] = 3;
    $config['use_page_numbers'] = TRUE;
    
    // Include the keyword in the query string for pagination links
    $config['suffix'] = '?date=' . urlencode($keyword);
    $config['first_url'] = $config['base_url'] . '/1' . $config['suffix'];

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

    $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
    $offset = ($page - 1) * $config['per_page'];

    $data['date'] = $keyword;
    $data['config'] = $config;
    $data['offset'] = $offset;

    $this->pagination->initialize($config);

    $loginuser = $this->session->userdata('LoginSession');
    $data['user_id'] = $loginuser['id'];

    $data['orders'] = $this->order_model->search_date($keyword, $config['per_page'], $offset);
    $data['total_rows'] = $this->order_model->count_search_date($keyword);
    $data['userss'] = $this->user_model->get_allusers($loginuser['id']);
 
    $this->load->view('template/header.php', $data);
    $user = $this->session->userdata('user_register');
    $users = $this->session->userdata('normal_user');
    $loginuser = $this->session->userdata('LoginSession');
    $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data, 'loginuser' => $loginuser));
    $this->load->view('orders/manage_order.php', $data);
    $this->load->view('template/footer.php');
}

public function searchorderdate() {
    $keyword = $this->input->get('orderdate');
    $data['title'] = 'Dashboard';

    // Load pagination library
    $this->load->library('pagination');

    // Pagination Config for Search Results
    $config['base_url'] = site_url('Orders/searchorderdate');
    $config['total_rows'] = $this->order_model->count_search_orderdate($keyword);
    $config['per_page'] = 10;
    $config['uri_segment'] = 3;
    $config['use_page_numbers'] = TRUE;

    // Include the keyword in the query string for pagination links
    $config['suffix'] = '?orderdate=' . urlencode($keyword);
    $config['first_url'] = $config['base_url'] . '/1' . $config['suffix'];

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

    $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
    $offset = ($page - 1) * $config['per_page'];

    $data['orderdate'] = $keyword;
    $data['config'] = $config;
    $data['offset'] = $offset;

    $this->pagination->initialize($config);

    $loginuser = $this->session->userdata('LoginSession');
    $data['user_id'] = $loginuser['id'];

    $data['orders'] = $this->order_model->search_orderdate($keyword, $config['per_page'], $offset);
    $data['total_rows'] = $this->order_model->count_search_orderdate($keyword);
    $data['userss'] = $this->user_model->get_allusers($loginuser['id']);
 
    $this->load->view('template/header.php', $data);
    $user = $this->session->userdata('user_register');
    $users = $this->session->userdata('normal_user');
    $loginuser = $this->session->userdata('LoginSession');
    $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data, 'loginuser' => $loginuser));
    $this->load->view('orders/manage_order.php', $data);
    $this->load->view('template/footer.php');
}



public function fetch_user_address() {
	
	$user_id = $this->input->post('user_id');

	if ($user_id) {
		$user = $this->order_model->getuseraddress($user_id);
		if ($user) {
			echo json_encode(['success' => true, 'data' => $user]);
		} else {
			echo json_encode(['success' => false, 'message' => 'No address found.']);
		}
	} else {
		echo json_encode(['success' => false, 'message' => 'Invalid user ID.']);
	}
}

public function downloadseparate()
{
    $data['print'] = 'print';

    $invoice_date = $this->input->post('invoicee_date');

    $loginuser = $this->session->userdata('LoginSession');

    $data['user_id'] = $loginuser['id'];

    $user_id = $data['user_id'];

    $orders_data = $this->order_model->getinvoice($invoice_date);

    // Initialize an array to store file paths
    $file_paths = array();

    foreach ($orders_data as $row) {
        $filename = 'invoice_' . $row->bill_no . '.pdf';
        $filepath = FCPATH . 'files/' . $filename;

        if (file_exists($filepath)) {
            // Add file path to the array
            $file_paths[] = $filepath;
        } else {
            // Handle the case where the file doesn't exist
            echo "File not found for bill number: " . $row->bill_no;
        }
    }

    // Set the filename for the ZIP archive
    $zip_filename = 'invoices_' . $invoice_date . '.zip';

    // Set the appropriate headers for ZIP download
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $zip_filename . '"');

    // Create a new ZIP archive
    $zip = new ZipArchive;
    if ($zip->open($zip_filename, ZipArchive::CREATE) === TRUE) {
        // Add files to the ZIP archive
        foreach ($file_paths as $file_path) {
            $file_name_in_zip = basename($file_path);
            $zip->addFile($file_path, $file_name_in_zip);
        }

        // Close the ZIP archive
        $zip->close();

        // Output the ZIP archive
        readfile($zip_filename);

        // Remove the temporary ZIP file
        unlink($zip_filename);
    } else {
        // Handle the case where ZIP archive creation fails
        echo "Failed to create ZIP archive";
    }
}

public function downloadcombined()
{
    $data['print'] = 'print';

    $invoice_date = $this->input->post('invoicee_date');

    $loginuser = $this->session->userdata('LoginSession');

    $data['user_id'] = $loginuser['id'];

    $user_id = $data['user_id'];

    $orders_data = $this->order_model->getdo($invoice_date);

    // Initialize an array to store file paths
    $file_paths = array();

    foreach ($orders_data as $row) {
        $filename = $row->do_bill_no . '.pdf';
        $filepath = FCPATH . 'files/DO/' . $filename;

        if (file_exists($filepath)) {
            // Add file path to the array
            $file_paths[] = $filepath;
        } else {
            // Handle the case where the file doesn't exist
            echo "File not found for bill number: " . $row->do_bill_no;
        }
    }

    // Use the helper function to merge PDFs and create a ZIP file
    $combined_pdf_path = FCPATH . 'files/DO' . 'combined_dos_' . $invoice_date . '.pdf';

    $pdf = new PDFMerger;

    foreach ($file_paths as $file_path) {
        $pdf->addPDF($file_path, 'all');
    }

    $pdf->merge('file', $combined_pdf_path);

    // Set the filename for the ZIP archive
    $zip_filename = 'CombinedDOs_' . $invoice_date . '.zip';

    // Set the appropriate headers for ZIP download
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $zip_filename . '"');

    // Create a new ZIP archive
    $zip = new ZipArchive;
    if ($zip->open($zip_filename, ZipArchive::CREATE) === TRUE) {
        // Add the combined PDF to the ZIP archive
        $zip->addFile($combined_pdf_path, basename($combined_pdf_path));

        // Close the ZIP archive
        $zip->close();

        // Output the ZIP archive
        readfile($zip_filename);

        // Remove the temporary ZIP file
        unlink($zip_filename);

        // Remove the combined PDF file
        unlink($combined_pdf_path);
    } else {
        // Handle the case where ZIP archive creation fails
        echo "Failed to create ZIP archive";
    }
}
public function send_invoices_for_today()
{
    date_default_timezone_set('Asia/Singapore'); // Set timezone to Singapore

    $today = date('Y-m-d');

    //echo $today;
    //exit;

    // Fetch orders with today's delivery date
    $this->db->select('orders.bill_no, user_register.email, user_register.primary_email, user_register.secondary_email');
    $this->db->from('orders');
    $this->db->join('user_register', 'orders.user_id = user_register.id');
    $this->db->where('orders.delivery_date', $today);
   
    $query = $this->db->get();
    $orders = $query->result();

    // Email configuration
    $config['protocol']  = 'smtp';
    $config['smtp_host'] = 'ssl://mail.sourdoughfactory.com.sg';
    $config['smtp_port'] = '465';
    $config['smtp_timeout'] = '180';
    $config['smtp_user']  = 'finance@sourdoughfactory.com.sg';
    $config['smtp_pass'] = 'Sourdough0705';
    $config['charset'] = 'utf-8';
    $config['newline']  = "\r\n";
    $config['mailtype'] = 'text'; // or html
    $config['validation'] = TRUE;

    $this->load->library('email', $config); // Load email library with configuration

    $from_email = 'finance@sourdoughfactory.com.sg'; // Set from email

    foreach ($orders as $order) {
        $bill_no = $order->bill_no;
        $toemail = $order->email;
        $primaryemail = $order->primary_email;
        $secondaryemail = $order->secondary_email;
       //$toemail = 'suganyaulagu8@gmail.com';

        $recipients = array_filter([$toemail, $primaryemail, $secondaryemail]);

        if (empty($recipients)) {
            log_message('error', "No valid email addresses for invoice $bill_no.");
            continue; // Skip this iteration if no valid emails
        }

        // Initialize email configuration for each iteration
        $this->email->initialize($config);

        $subject = "Invoice Attached, Invoice No: $bill_no";

        date_default_timezone_set('Asia/Singapore');
        $current_date_time = date('Y-m-d H:i:s');

        $msg = "Hi,\n\nPlease find the attached invoice for your review and processing:\nInvoice No: $bill_no\nDate: $current_date_time\n\nBest regards,\nThe Sourdough Factory Team";

        $this->email->from($from_email, 'Sourdough Factory');
      //  $this->email->to($toemail);
        $this->email->to($recipients); // Send to multiple emails
        $this->email->subject($subject);
        $this->email->message($msg);

        // Attach invoice PDF file
        $file_path = FCPATH . 'files/invoice_' . $bill_no . '.pdf';
        $this->email->attach($file_path);

        // Attempt to send the email with retry mechanism
        $max_retries = 3; // Maximum number of retries
        $retry_delay = 30; // Delay in seconds before retrying

        $retry_count = 0;
        while ($retry_count < $max_retries) {
            if ($this->email->send()) {
                // Log successful email sending
                log_message('info', "Invoice $bill_no sent to $toemail.");
                break; // Exit loop on successful send
            } else {
                // Log email sending failure with error message
                log_message('error', "Failed to send invoice $bill_no to $toemail: " . $this->email->print_debugger());

                // Increment retry count and wait before retrying
                $retry_count++;
                sleep($retry_delay);
            }
        }

        // Clear attachments and reset for the next iteration
        $this->email->clear(TRUE);
    }
}



public function print_agreement()
	{
    $data['print'] = 'print';

	$loginuser = $this->session->userdata('LoginSession');
	
	$data['user_id'] = $loginuser['id'];

	$user_id = $data['user_id'];

	$data["employee_data"] = $this->excel_export_model->fetch_data();
	$data['userss'] = $this->user_model->get_agreedusers();
    $this->load->view('template/header.php', $data);
    $user = $this->session->userdata('user_register');
    $users = $this->session->userdata('normal_user');
    $loginuser = $this->session->userdata('LoginSession');
	$this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
	$this->load->view('agreement/download_agreement.php', array('data' => $data,));
	$this->load->view('template/footer.php');
}

public function downloadagreemnt()
{
    $this->load->helper('download'); // Load the download helper

    $user_name = $this->input->post('user_name'); // Ensure the correct input field name
    $loginuser = $this->session->userdata('LoginSession');

    $user_id = $loginuser['id'];
    
    $filename = '\Agreement_of_User_' . $user_name . '.pdf';
    $filepath = FCPATH . 'agreements' . $filename;

    if (file_exists($filepath)) {
        // Force file download
        force_download($filepath, NULL);
    } else {
        echo "File not found for Customer: " . $filepath;
    }
}

public function fetchdeleteOrdersData()
{

    
    $result = array('data' => array());

    $data = $this->order_model->getdeletedOrdersData();

    foreach ($data as $key => $value) {


        $count_total_item = $this->order_model->countOrderItem($value['id']);
        $date = date('d-m-Y', $value['date_time']);
        $time = date('h:i a', $value['date_time']);

        $date_time = $date . ' ' . $time;

        // button
        $buttons = '';

        if(in_array('viewOrder', $this->permission)) {
            $buttons .= '<a target="__blank" href="'.base_url('index.php/orders/printDiv/'.$value['id']).'" class="btn btn-warning"><i class="fa fa-print"></i></a>';
        }

        
        if(in_array('deleteOrder', $this->permission)) {
            $buttons .= ' <button type="button" class="btn btn-danger" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-undo"></i></button>';
        }

    
           $result['data'][$key] = array(
            $value['bill_no'],
            $date_time,
            $count_total_item,
            $value['net_amount'],
            // $paid_status,
            $buttons
        );
    }

    echo json_encode($result);
}

public function manage_delete_orders() {
    $data['title'] = 'orders';

    // Pagination configuration
    $config['base_url'] = site_url('orders/manage_delete_orders');
    $config['total_rows'] = $this->order_model->count_all_deleted_orders(); // Ensure total rows are counted for the current month
    $config['per_page'] = 10;
    $config['uri_segment'] = 3;
    $config['use_page_numbers'] = TRUE;

    // Pagination styling
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

    // Initialize pagination
    $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
    $offset = ($page - 1) * $config['per_page'];

    $this->pagination->initialize($config);

    $loginuser = $this->session->userdata('LoginSession');
    $data['user_id'] = $loginuser['id'];

    // Fetch orders for the current month with pagination
    $data['orders'] = $this->order_model->getmanagedeleteorder($config['per_page'], $offset);
    $data['total_rows'] = $this->order_model->count_all_deleted_orders();

    // Load views
    $this->load->view('template/header.php', $data);
    $user = $this->session->userdata('user_register');
    $users = $this->session->userdata('normal_user');
    $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data, 'loginuser' => $loginuser));
    $this->load->view('orders/manage_delete_orders.php', $data);
    $this->load->view('template/footer.php');
}

public function revertorder($id){
	$delete = $this->order_model->revertorder($id);

	if($delete){
		$this->session->set_flashdata('success', 'Order Reverted Successfully');
		redirect('orders/manage_delete_orders', 'refresh');
		
	}
	else{
		return false;
	}
}

public function searchdeletedinvoice() {
    $keyword = $this->input->get('keyword');
    $data['title'] = 'Dashboard';

    // Load pagination library
    $this->load->library('pagination');

    // Pagination Config for Search Results
    $config['base_url'] = site_url('Orders/searchdeletedinvoice');
    $config['total_rows'] = $this->order_model->count_search_deleted_orders($keyword);
    $config['per_page'] = 10;
    $config['uri_segment'] = 3;
    $config['use_page_numbers'] = TRUE;

    // Include the keyword in the query string for pagination links
    $config['suffix'] = '?keyword=' . $keyword;
    $config['first_url'] = $config['base_url'] . '/1' . $config['suffix'];

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

    $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
    $offset = ($page - 1) * $config['per_page'];

    $data['keyword'] = $keyword;
    $data['config'] = $config;
    $data['offset'] = $offset;

    $this->pagination->initialize($config);

    $loginuser = $this->session->userdata('LoginSession');
    $data['user_id'] = $loginuser['id'];

    $data['orders'] = $this->order_model->search_deleted_orders($keyword, $config['per_page'], $offset);
    $data['total_rows'] = $this->order_model->count_search_deleted_orders($keyword);

    $this->load->view('template/header.php', $data);
    $user = $this->session->userdata('user_register');
    $users = $this->session->userdata('normal_user');
    $loginuser = $this->session->userdata('LoginSession');
    $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data, 'loginuser' => $loginuser));
    $this->load->view('orders/manage_delete_orders.php', $data);
    $this->load->view('template/footer.php');
}


public function searchdeletedate() {
    $keyword = $this->input->get('date');
    $data['title'] = 'Dashboard';

    // Load pagination library
    $this->load->library('pagination');

    // Pagination Config for Search Results
    $config['base_url'] = site_url('Orders/searchdeletedate');
    $config['total_rows'] = $this->order_model->count_delete_search_date($keyword);
    $config['per_page'] = 10;
    $config['uri_segment'] = 3;
    $config['use_page_numbers'] = TRUE;

    // Include the keyword in the query string for pagination links
    $config['suffix'] = '?date=' . urlencode($keyword);
    $config['first_url'] = $config['base_url'] . '/1' . $config['suffix'];

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

    $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
    $offset = ($page - 1) * $config['per_page'];

    $data['date'] = $keyword;
    $data['config'] = $config;
    $data['offset'] = $offset;

    $this->pagination->initialize($config);

    $loginuser = $this->session->userdata('LoginSession');
    $data['user_id'] = $loginuser['id'];

    $data['orders'] = $this->order_model->search_date($keyword, $config['per_page'], $offset);
    $data['total_rows'] = $this->order_model->count_delete_search_date($keyword);

    $this->load->view('template/header.php', $data);
    $user = $this->session->userdata('user_register');
    $users = $this->session->userdata('normal_user');
    $loginuser = $this->session->userdata('LoginSession');
    $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data, 'loginuser' => $loginuser));
    $this->load->view('orders/manage_delete_orders.php', $data);
    $this->load->view('template/footer.php');
}

public function searchdeletedorderdate() {
    $keyword = $this->input->get('orderdate');
    $data['title'] = 'Dashboard';

    // Load pagination library
    $this->load->library('pagination');

    // Pagination Config for Search Results
    $config['base_url'] = site_url('Orders/searchdeletedorderdate');
    $config['total_rows'] = $this->order_model->count_search_delete_orderdate($keyword);
    $config['per_page'] = 10;
    $config['uri_segment'] = 3;
    $config['use_page_numbers'] = TRUE;

    // Include the keyword in the query string for pagination links
    $config['suffix'] = '?orderdate=' . urlencode($keyword);
    $config['first_url'] = $config['base_url'] . '/1' . $config['suffix'];

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

    $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
    $offset = ($page - 1) * $config['per_page'];

    $data['orderdate'] = $keyword;
    $data['config'] = $config;
    $data['offset'] = $offset;

    $this->pagination->initialize($config);

    $loginuser = $this->session->userdata('LoginSession');
    $data['user_id'] = $loginuser['id'];

    $data['orders'] = $this->order_model->search_orderdate($keyword, $config['per_page'], $offset);
    $data['total_rows'] = $this->order_model->count_search_delete_orderdate($keyword);

    $this->load->view('template/header.php', $data);
    $user = $this->session->userdata('user_register');
    $users = $this->session->userdata('normal_user');
    $loginuser = $this->session->userdata('LoginSession');
    $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data, 'loginuser' => $loginuser));
    $this->load->view('orders/manage_delete_orders.php', $data);
    $this->load->view('template/footer.php');
}
public function update_paid_status()
{
    $input = json_decode($this->input->raw_input_stream, true);
    $order_id = intval($input['order_id']);
    $customer_id = intval($input['customer_id']);
  $account_paid = isset($input['account_paid']) ? intval($input['account_paid']) : 0;

    $response = ['success' => false, 'message' => 'Invalid request'];

    if ($order_id && $customer_id) { 
        
          $updated = $this->order_model->mark_order_paid($order_id, $account_paid);

        if ($updated) {
           
            $response = ['success' => true, 'message' => 'Payment status updated successfully'];
        } else {
            $response['message'] = 'Failed to update order';
        }
    }

    echo json_encode($response);
}
public function cron_check_user_payment_status() 
{
    // Load the order model
    $this->load->model('order_model');

    // Get current day, current month, and next month
    $today = date('j');
    $current_day = (int)$today;
    $current_month = date('Y-m');
    $next_month = date('Y-m', strtotime('+1 month'));
    $last_day_of_month = date('t');

    // Fetch distinct user_ids from orders with check_paystatus enabled and not deleted
    $user_ids = $this->db->distinct()
                         ->select('user_id')
                         ->where('check_paystatus', 1)
                         ->where('isdeleted', 0)
                         ->get('orders')
                         ->result();

    // Loop through each user
    foreach ($user_ids as $row) {
        $user_id = $row->user_id;

        // Fetch user details
        $user = $this->db->where('id', $user_id)->get('user_register')->row();
        if (!$user) continue;

        // Skip if trusted customer
        if ($user->trust_customer == 1) {
            continue;
        }

        // Get user payment terms
        $terms = strtolower(trim($user->payment_terms));

        // ----------------------------------------------
        // 1. COD Terms Logic
        // ----------------------------------------------
        if ($terms == 'cod') {
            $last_two = $this->order_model->get_last_checkpay_invoices($user_id, 2);
            $all_paid = array_reduce($last_two, function ($carry, $invoice) {
                return $carry && $invoice->account_paid == 1;
            }, true);

            if ($all_paid || count($last_two) < 2) {
                $this->activate_user($user_id);
            } else {
                $this->deactivate_user($user_id);
            }

        // ----------------------------------------------
        // 2. 30 Days Payment Terms
        // ----------------------------------------------
        } elseif ($terms == '30') {
            $start_date = date('Y-m-01', strtotime('first day of -2 months'));
            $end_date = date('Y-m-t', strtotime('last day of -2 months'));

            $invoices = $this->order_model->get_invoices_between($user_id, $start_date, $end_date);
            $all_paid = empty($invoices)
                ? true
                : array_reduce($invoices, function ($carry, $invoice) {
                    return $carry && $invoice->account_paid == 1;
                }, true);

            if ($all_paid) {
                $this->activate_user($user_id);
            } else {
                $this->deactivate_user($user_id);
            }

        // ----------------------------------------------
        // 3. 14 or 15 Days Payment Terms
        // ----------------------------------------------
        } 
        elseif ($terms == '14' || $terms == '15') {
    $day = (int)date('d');
    $last_day_of_month = date('t');

    $this_month = date('Y-m', strtotime('-1 month')); // previous month

    // First half: 1st–15th
    $start_1 = "$this_month-01";
    $end_1   = "$this_month-15";

    // Second half: 16th–end
    $start_2 = "$this_month-16";
    $end_2   = date('Y-m-t', strtotime("$this_month-01"));

    if ($day >= 30 || $day <= 15) {
        // First half check only
        $invoices = $this->order_model->get_invoices_between($user_id, $start_1, $end_1);

    } elseif ($day >= 16 && $day <= $last_day_of_month) {
        // Both first and second half to be considered\

        $first_half  = $this->order_model->get_invoices_between($user_id, $start_1, $end_1);
        $second_half = $this->order_model->get_invoices_between($user_id, $start_2, $end_2);
        $invoices = array_merge($first_half, $second_half);

    } else {
        // Just in case
        $this->activate_user($user_id);
        continue;
    }

    // Final check unpaid
    $invoices = is_array($invoices) || is_object($invoices) ? $invoices : [];

    $all_paid = empty($invoices)
        ? true
        : array_reduce($invoices, function ($carry, $invoice) {
            return $carry && $invoice->account_paid == 1;
        }, true);

    if ($all_paid) {
        $this->activate_user($user_id);
    } else {
        $this->deactivate_user($user_id);
    }
}
         elseif (
            ($terms == '30' && $current_day != 30) ||
            ($terms == '15' && $current_day != 7 && $current_day != 21) ||
            ($terms == '14' && $current_day != 7 && $current_day != 21)
        ) {
            $this->activate_user($user_id);
        }
    }

    // ----------------------------------------------
    // 5. Activate trusted customers unconditionally
    // ----------------------------------------------
    $trusted_users = $this->db->where('trust_customer', 1)->get('user_register')->result();
    foreach ($trusted_users as $trusted_user) {
        $this->activate_user($trusted_user->id);
    }

    echo "Cron executed.";
}

private function deactivate_user($user_id)
{
    $this->db->where('id', $user_id)->update('user_register', [
        // 'status' => 0,
        // 'is_archieve' => 1,
        'pay_restrict' => 1
    ]);
}


private function activate_user($user_id)
{
    $this->db->where('id', $user_id)->update('user_register', [
        // 'status' => 1,
        // 'is_archieve' => 0,
        'pay_restrict' => 0
    ]);
}
public function get_restricted_users_with_invoices()
{
    $keyword = strtolower(trim($this->input->get('payment_terms')));
    $data['payment_terms'] = $keyword;
    $data['title'] = 'orders';

    // Pagination config
    $config['base_url'] = site_url('orders/get_restricted_users_with_invoices');
    $config['total_rows'] = $this->order_model->count_filtered_restricted_users($keyword);
    $config['per_page'] = 10;
    $config['uri_segment'] = 3;
    $config['use_page_numbers'] = TRUE;
    $config['suffix'] = '?payment_terms=' . urlencode($keyword);
    $config['first_url'] = $config['base_url'] . '/1' . $config['suffix'];

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

    $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
    $offset = ($page - 1) * $config['per_page'];
    $this->pagination->initialize($config);

    $loginuser = $this->session->userdata('LoginSession');
    $data['user_id'] = $loginuser['id'];
    $data['orders'] = $this->order_model->get_restricted_users_with_unpaid_invoices($config['per_page'], $offset, $keyword);
    $data['total_rows'] = $config['total_rows'];
    $data['userss'] = $this->user_model->get_allusers($loginuser['id']);

    $this->load->view('template/header.php', $data);
    $user = $this->session->userdata('user_register');
    $users = $this->session->userdata('normal_user');
    $this->load->view('template/sidebar.php', compact('user', 'users', 'loginuser'));
    $this->load->view('orders/restricted_users.php', $data);
    $this->load->view('template/footer.php');
}

    public function pay_restrict(){
		$data['title'] = 'orders';
	
		$loginuser = $this->session->userdata('LoginSession');
	
		$data['user_id'] = $loginuser['id'];
	
		$data['orders'] = $this->order_model->getmanageorder();
	
		$this->load->view('template/header.php', $data);
		$user = $this->session->userdata('user_register');
		$users = $this->session->userdata('normal_user');
		$loginuser = $this->session->userdata('LoginSession');
		//var_dump($loginuser);
		$this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
		$this->load->view('orders/pay_restrict.php', $data);
		$this->load->view('template/footer.php');
	}

    public function check_user_can_order()
{
    $loginuser = $this->session->userdata('LoginSession'); // get current logged-in user

    $user_id = $loginuser['id'];
    if (!$user_id) {
        echo json_encode(['status' => false, 'message' => 'User not logged in']);
        return;
    }

    // Get user info
    $user = $this->db->where('id', $user_id)->get('user_register')->row();
    if (!$user) {
        echo json_encode(['status' => false, 'message' => 'User not found']);
        return;
    }

    $terms = strtolower(trim($user->payment_terms));

    if ($terms === 'cod') {
        $last_two = $this->order_model->get_last_checkpay($user_id, 2);

       if (count($last_two) == 2) {
            echo json_encode([
                'status' => false,
                'message' => 'Your last 2 invoices are not paid. You are restricted from placing new orders.'
            ]);
            return;
        }
    }

    // If not COD or all invoices are paid
    echo json_encode(['status' => true]);
}

public function searchbycustomer() {
    $user_id = $this->input->get('user_id');
    $data['title'] = 'Dashboard';

    $this->load->library('pagination');

    $config['base_url'] = site_url('Orders/searchbycustomer');
    $config['total_rows'] = $this->order_model->count_orders_by_user($user_id);
    $config['per_page'] = 10;
    $config['uri_segment'] = 3;
    $config['use_page_numbers'] = TRUE;

    $config['suffix'] = '?user_id=' . urlencode($user_id);
    $config['first_url'] = $config['base_url'] . '/1' . $config['suffix'];

    // pagination styling (optional)
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

    $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
    $offset = ($page - 1) * $config['per_page'];

    $this->pagination->initialize($config);

    $loginuser = $this->session->userdata('LoginSession');

    $data['user_id'] = $user_id;
    $data['orders'] = $this->order_model->get_orders_by_user($user_id, $config['per_page'], $offset);
    $data['total_rows'] = $config['total_rows'];

    // Get active users
    $data['userss'] = $this->user_model->get_activeusers($loginuser['id']);

    $this->load->view('template/header.php', $data);
    $user = $this->session->userdata('user_register');
    $users = $this->session->userdata('normal_user');
    $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data, 'loginuser' => $loginuser));
    $this->load->view('orders/manage_order.php', $data);
    $this->load->view('template/footer.php');
}
public function searchpaymentinvoice()
{
    $keyword = trim(strtolower($this->input->get('keyword', true)));
      if ($keyword === null || $keyword === '') {
        redirect('orders/get_restricted_users_with_invoices');
        return;
    }

    $current_day = (int)date('j');
    $current_month = date('Y-m');
    $filtered_data = [];
    // Get all users with pay_restrict
    $users = $this->db->where('pay_restrict', 1)->get('user_register')->result();

    foreach ($users as $user) {
        $user_name = strtolower($user->name);
        $terms = strtolower(trim($user->payment_terms));

        $name_match = strpos($user_name, $keyword) !== false;
        $highlighted = [];

        // Start invoice query
        $this->db->reset_query();
        $this->db->select('bill_no, delivery_date')
                 ->from('orders')
                 ->where('user_id', $user->id)
                 ->where('check_paystatus', 1)
                 ->where('account_paid', 0)
                 ->where('isdeleted', 0);

        // Filter invoices by payment term condition
        if ($terms === 'cod') {
            // No delivery_date filter — show all unpaid
        } elseif ($terms == '30') {
            if ($current_day == 29) {
                $start_date = date('Y-m-01', strtotime('first day of last month'));
                $end_date = date('Y-m-t', strtotime('last day of last month'));
                $this->db->where('delivery_date >=', $start_date)
                         ->where('delivery_date <=', $end_date);
            } else {
                continue;
            }
        } elseif ($terms == '14' || $terms == '15') {
            if ($current_day == 15) {
                $start_date = date('Y-m-16', strtotime('first day of last month'));
                $end_date = date('Y-m-t', strtotime('last day of last month'));
                $this->db->where('delivery_date >=', $start_date)
                         ->where('delivery_date <=', $end_date);
            } elseif ($current_day == 29 || $current_day == 1) {
                $start_date = "$current_month-01";
                $end_date = "$current_month-15";
                $this->db->where('delivery_date >=', $start_date)
                         ->where('delivery_date <=', $end_date);
            } else {
                continue;
            }
        } else {
            continue; // Unknown term
        }

        $invoices = $this->db->get()->result();

        foreach ($invoices as $invoice) {
            if (strpos(strtolower($invoice->bill_no), $keyword) !== false) {
                $highlighted[] = $invoice->bill_no;
            }
        }

        if ($name_match || !empty($highlighted)) {
            $filtered_data[] = [
                'user_id' => $user->id,
                'name' => $user->name,
                'payment_terms' => $user->payment_terms,
                'invoices' => array_map(fn($i) => $i->bill_no, $invoices),
                'highlight_invoices' => $highlighted
            ];
        }
    }

    $data['orders'] = $filtered_data;
    $data['keyword'] = $this->input->get('keyword');
    $data['total_rows'] = count($filtered_data);

    $this->load->view('template/header', $data);
    $user = $this->session->userdata('user_register');
    $users = $this->session->userdata('normal_user');
    $loginuser = $this->session->userdata('LoginSession');
    $this->load->view('template/sidebar', compact('user', 'users', 'loginuser'));
    $this->load->view('orders/restricted_users', $data);
    $this->load->view('template/footer');
}

public function searchbyterms()
{
    $payment_terms = $this->input->get('payment_terms');

    $this->db->where('payment_terms', $payment_terms);
    $data['users'] = $this->db->get('user_register')->result();

    $data['payment_terms'] = $payment_terms;

    $this->load->view('template/header', $data);
    $this->load->view('orders/payment_terms_results', $data);
    $this->load->view('template/footer');
}

public function get_closed_invoices() {
        $data['customers'] = $this->order_model->get_customers();

        $user_id = $this->input->get('user_id');
        $from_date = $this->input->get('from_date');
        $to_date = $this->input->get('to_date');

        if ($user_id && $from_date && $to_date) {
            $data['orders'] = $this->order_model->get_orders($user_id, $from_date, $to_date);
        } else {
            $data['orders'] = [];
        }

       
       $this->load->view('template/header', $data);
    $user = $this->session->userdata('user_register');
    $users = $this->session->userdata('normal_user');
    $loginuser = $this->session->userdata('LoginSession');
    $this->load->view('template/sidebar', compact('user', 'users', 'loginuser'));
    $this->load->view('orders/orders_view', $data);
    $this->load->view('template/footer');
    }

 

}
