<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class orders extends CI_Controller {

	function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('order_model');
        $this->load->library('pagination');
        $this->load->library('session');
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

        	if($order_id) {

        		$this->session->set_flashdata('success', 'Successfully created');
        		redirect('orders/update/'.$order_id, 'refresh');
        	}
        	else {
        		$this->session->set_flashdata('errors', 'Error occurred!!');
        		redirect('orders/create/', 'refresh');
        	}
        }

        else {
            // // false case
            // $data['table_data'] = $this->order_model->getActiveTable();
        	// $company = $this->model_company->getCompanyData(1);
        	// $data['company_data'] = $company;
        	// $data['is_vat_enabled'] = ($company['vat_charge_value'] > 0) ? true : false;
        	// $data['is_service_enabled'] = ($company['service_charge_value'] > 0) ? true : false;

        	$data['products'] = $this->order_model->getActiveProductData();
            $data['category'] = $this->order_model->getActivecatergoryData();

            $this->load->view('template/header.php', $data);
            $user = $this->session->userdata('user_register');
            $users = $this->session->userdata('normal_user');
            $loginuser = $this->session->userdata('LoginSession');
            //var_dump($loginuser);
            $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
            $this->load->view('orders/create_order.php', $data);
            $this->load->view('template/footer.php');
         }
    }

    public function getProductsByCategory() {
        $categoryId = $this->input->post('category_id');
        $products = $this->order_model->getProductsByCategory($categoryId);
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
    public function update($id)
	{

		$this->data['page_title'] = 'Update Order';

		$this->form_validation->set_rules('product[]', 'Product name', 'trim|required');


        if ($this->form_validation->run() == TRUE) {

        	$update = $this->order_model->update($id);

        	if($update == true) {
        		$this->session->set_flashdata('success', 'Successfully updated');
        		redirect('orders', 'refresh');
        	}
        	else {
        		$this->session->set_flashdata('errors', 'Error occurred!!');
        		redirect('orders/update/'.$id, 'refresh');
        	}
        }
        else {

        	$result = array();
        	$orders_data = $this->order_model->getOrdersData($id);

        	if(empty($orders_data)) {
        		$this->session->set_flashdata('errors', 'The request data does not exists');
        		redirect('index.php/orders', 'refresh');
        	}

			$result['order'] = $orders_data;


    		$data['order_data'] = $result;

			foreach ($orders_data as $order) {
				$orders_item = $this->order_model->getOrdersItemData($order->id);
				// Process $orders_item as needed
			}

    		foreach($orders_item as $k => $v) {
    			$result['order_item'][] = $v;
    		}
    		
			$result['order'] = $orders_data;

			$data['order_data'] = $result;

			$data['order_total'] = $this->order_model->getOrdertotal($id);

			$data['products'] = $this->order_model->getActiveProductData();
            $data['category'] = $this->order_model->getActivecatergoryData();



            $this->load->view('template/header.php', $data);
            $user = $this->session->userdata('user_register');
            $users = $this->session->userdata('normal_user');
            $loginuser = $this->session->userdata('LoginSession');
            //var_dump($loginuser);
            $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
            $this->load->view('orders/edit.php', $data);
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
    $orders_data = $this->order_model->getOrdersData($id);

    foreach ($orders_data as $order) {
        $orders_item = $this->order_model->getOrdersItemData($order->id);
    }

    foreach ($orders_item as $k => $v) {
        $result['order_item'][] = $v;
    }

    $result['order'] = $orders_data;

    $data['order_data'] = $result;
    $data['order_total'] = $this->order_model->getOrdertotal($id);


//	print_r($data['order_total']);

	// Accessing the array at index 0
$order_total_data = $data['order_total'][0];



// Extracting datetime and discount values
	$order_date = ($order_total_data['date_time'] !== null) ? date('d/m/Y', $order_total_data['date_time']) : '';
	
    $this->load->view('template/header.php', $data);
    $user = $this->session->userdata('user_register');
    $users = $this->session->userdata('normal_user');
    $loginuser = $this->session->userdata('LoginSession');
    $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data, 'loginuser' => $loginuser));
    $this->load->view('invoice/print_invoice.php', array('data' => $data, 'order_date' => $order_date));
    $this->load->view('template/footer.php');
}

}
