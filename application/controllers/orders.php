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

		$data['orders'] = $this->order_model->getorderuser($data['user_id']);

        $this->load->view('template/header.php', $data);
        $user = $this->session->userdata('user_register');
        $users = $this->session->userdata('normal_user');
        $loginuser = $this->session->userdata('LoginSession');
        //var_dump($loginuser);
        $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
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
					$this->send_invoice($bill_no,$email); 
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
					$this->send_invoice($bill_no,$email); 
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
			
			$this->send_invoice($bill_no,$email); 


			$this->session->set_flashdata('success', 'Order Updated Successfully');


			redirect('orders/manage_orders', 'refresh');
        }
        else {

        	// $loginuser = $this->session->userdata('LoginSession');
	
			// $data['user_id'] = $loginuser['id'];

			// $user_id = $data['user_id'];

			$orders_data = $this->order_model->getOrdersDatas($id,$user_id);

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

    $orders_data = $this->order_model->getOrdersDatas($id,$user_id);

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


public function manage_orders(){
	$data['title'] = 'orders';

	$config['base_url'] = site_url('orders/manage_search');
	$config['total_rows'] = $this->order_model->count_all_orders(); // Ensure at least 10 total rows
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
	
	$config['total_rows'] = $this->order_model->count_all_orders();

	$this->pagination->initialize($config);

	$loginuser = $this->session->userdata('LoginSession');

	$data['user_id'] = $loginuser['id'];

	$data['orders'] = $this->order_model->getmanageorder();

	$data['total_rows'] = $this->order_model->count_all_orders();

	$this->load->view('template/header.php', $data);
	$user = $this->session->userdata('user_register');
	$users = $this->session->userdata('normal_user');
	$loginuser = $this->session->userdata('LoginSession');
	//var_dump($loginuser);
	$this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
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


public function send_invoice($bill_no,$email)
	{
	

		//$bill_no = '24059014';
		
		$toemail= $email;
		//$cc_email = $email;
		
		$config['protocol']  = 'smtp';
		$config['smtp_host'] = 'ssl://smtp.gmail.com';
		$config['smtp_port'] = '465';
		$config['smtp_timeout'] = '7';
		$config['smtp_user']  = 'mailto:suganyaulagu8@gmail.com';
		$config['smtp_pass'] = 'qqcb mupl eyeb azdo';
		$config['charset'] = 'utf-8';
		$config['newline']  = "\r\n";
		$config['mailtype'] = 'text'; 
		$config['validation'] = TRUE;
		$this->email->initialize($config);
		$from_email = 'suganyaulagu8@gmail.com';
		
	
		$this->email->initialize($config);
	

		$subject = "Invoice Attached , Invoice No:  $bill_no";
		
		date_default_timezone_set('Asia/Singapore');


		$current_date_time = date('Y-m-d H:i:s');

		$msg = "Hi, Please find the attached invoice for your review and processing: Invoice No:  $bill_no, Date:  $current_date_time


Best regards,
The Sourdough Factory Team";

						$flashdataMessage = 'Please check your email for new password!';
						$this->email->from($from_email, 'Sourdough Factory');
						$this->email->to($toemail);
						//$this->email->cc($cc_email); 
						$this->email->subject($subject);
						$this->email->message($msg);
					
					//	$filepdath = FCPATH . 'files/' . $filename;
						$file_path = 'C:\xampp\htdocs\systemm\files\invoice_' . $bill_no . '.pdf';
						$this->email->attach($file_path);
						$this->email->send();


	}


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
					$this->send_invoice($bill_no,$email); 

			$this->session->set_flashdata('success', 'Order Placed Successfully');
			redirect('orders', 'refresh');
        }
        else {

        	$loginuser = $this->session->userdata('LoginSession');
	
			$data['user_id'] = $loginuser['id'];

			$user_id = $data['user_id'];

			$orders_data = $this->order_model->getOrdersDatas($id,$user_id);

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
				$this->send_invoice($bill_no,$email); 
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
			$data['userss'] = $this->user_model->get_activeusers();
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

public function deleteorder($id){
	$delete = $this->order_model->deleteorder($id);

	if($delete){
		$this->session->set_flashdata('deleted', 'Order Deleted Successfully');
		redirect('orders/manage_orders', 'refresh');
		
	}
	else{
		return false;
	}
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
    $today = date('Y-m-d');

    // Fetch orders with today's delivery date
    $this->db->select('orders.bill_no, user_register.email');
    $this->db->from('orders');
    $this->db->join('user_register', 'orders.user_id = user_register.id');
    $this->db->where('orders.delivery_date', $today);
    $query = $this->db->get();
    $orders = $query->result();

   // echo $this->db->last_query();
   // exit;

    // Email configuration
    $config['protocol']  = 'smtp';
    $config['smtp_host'] = 'ssl://smtp.gmail.com';
    $config['smtp_port'] = '465';
    $config['smtp_timeout'] = '7';
    $config['smtp_user']  = 'mailto:suganyaulagu8@gmail.com';
    $config['smtp_pass'] = 'qqcb mupl eyeb azdo';
    $config['charset'] = 'utf-8';
    $config['newline']  = "\r\n";
    $config['mailtype'] = 'text'; 
    $config['validation'] = TRUE;
    $this->email->initialize($config);
    $from_email = 'suganyaulagu8@gmail.com';

    foreach ($orders as $order) {
        $bill_no = $order->bill_no;
        $toemail = $order->email;

        $subject = "Invoice Attached , Invoice No:  $bill_no";
        
        date_default_timezone_set('Asia/Singapore');
        $current_date_time = date('Y-m-d H:i:s');

        $msg = "Hi, Please find the attached invoice for your review and processing: Invoice No:  $bill_no, Date:  $current_date_time

Best regards,
The Sourdough Factory Team";

        $this->email->from($from_email, 'Sourdough Factory');
        $this->email->to($toemail);
        $this->email->subject($subject);
        $this->email->message($msg);
        
        $file_path = 'C:\xampp\htdocs\systemm\files\invoice_' . $bill_no . '.pdf';
        $this->email->attach($file_path);

        if ($this->email->send()) {
            // Optionally, log successful email sending
            log_message('info', "Invoice $bill_no sent to $toemail.");
        } else {
            // Optionally, log email sending failure
            log_message('error', "Failed to send invoice $bill_no to $toemail.");
        }
    }
}

}
