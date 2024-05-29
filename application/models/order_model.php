<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class order_model extends CI_Model {
   public function getActiveProductData(){
            $sql = "SELECT * FROM products WHERE active = 1 ORDER BY product_name";
            $query = $this->db->query($sql, array(1));
            return $query->result_array();
   }
   public function getActivecatergoryData(){
            $sql = "SELECT prod_category FROM products WHERE active = 1 and prod_category != 'Frozen Dough' GROUP BY prod_category ASC";
            $query = $this->db->query($sql, array(1));
            return $query->result_array();
   }

   public function getActivecatergoryDataadmin(){
	$sql = "SELECT prod_category FROM products WHERE active = 1 GROUP BY prod_category ASC";
	$query = $this->db->query($sql, array(1));
	return $query->result_array();
}

   public function getProductsByCategoryadmin($categoryId) {
    $sql = "SELECT * FROM products WHERE active = 1 AND prod_category = ? ORDER BY product_id,product_name";
    $query = $this->db->query($sql, array($categoryId));
    return $query->result_array();
}

public function getProductsByCategory($categoryId) {
    $sql = "SELECT * FROM products 
            WHERE active = 1 
            AND prod_category = ? 
            AND prod_category != 'Frozen Dough' 
            ORDER BY product_id, product_name";
    $query = $this->db->query($sql, array($categoryId));
    return $query->result_array();
}


public function getProductData($id = null)
	{
	
			$sql = "SELECT * FROM products where id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
	

		// $user_id = $this->session->userdata('id');
		// if($user_id == 1) {
		// 	$sql = "SELECT * FROM products ORDER BY id DESC";
		// 	$query = $this->db->query($sql);
		// 	return $query->result_array(); 
		// }
		// else {
			
		// 	$user_data = $this->model_users->getUserData($user_id);
		// 	$sql = "SELECT * FROM products ORDER BY id DESC";
		// 	$query = $this->db->query($sql);

		// 	$data = array();
		// 	foreach ($query->result_array() as $k => $v) {
		// 		$store_ids = json_decode($v['store_id']);
		// 		if(in_array($user_data['store_id'], $store_ids)) {
		// 			$data[] = $v;
		// 		}
		// 	}

		// 	return $data;		
		// }
	}

	public function create()
{
    $user = $this->session->userdata('normal_user');
    $user_id = $user->id;

	$email = $user->email;
	// if (date('H') >= 17) {
    //     // Redirect or show an error message indicating that orders cannot be placed after 5 PM
    //     $this->session->set_flashdata('error', 'Orders cannot be placed after 5 PM.');
    //     redirect('orders', 'refresh');
    // }

	//else{
    //$bill_no = 'CDSTRO-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
	$current_year_month = date('ym');

	$sql = "SELECT bill_no FROM orders WHERE LENGTH(bill_no) = 8 ORDER BY bill_no DESC LIMIT 1;";
	$query = $this->db->query($sql);
	
	if ($query->num_rows() > 0) {
		$latest_invoice_number = $query->row()->bill_no;
		$latest_invoice_month = substr($latest_invoice_number, 0, 4); 
		$latest_invoice_counter = intval(substr($latest_invoice_number, 4)); 
		if ($latest_invoice_month == $current_year_month) {
			$invoice_counter = $latest_invoice_counter + 1;
		} else {
			
			$invoice_counter = 1;
		}
	} else {
		
		$invoice_counter = 1;
	}
	
	$bill_no = $current_year_month . sprintf('%04d', $invoice_counter);

	$date_time = strtotime(date('Y-m-d h:i:s a'));

	$delivery_date = strtotime('+3 days', $date_time);


	$delivery_date_formatted = date('Y-m-d h:i:s a', $delivery_date);

	$do_bill_no = 'DO'.$current_year_month . sprintf('%04d', $invoice_counter);
	date_default_timezone_set('Asia/Singapore');

	
	$pre_order = $this->input->post('pre_order_date');

	if($pre_order == '0000-00-00' || $pre_order == null || $pre_order == ''){
		
		$order_date = $delivery_date_formatted;

	}

	else{

		$order_date = $pre_order;
	}

	if (date('w', strtotime($order_date)) == 0) {
		// Adjust order_date to the following Monday
		$order_date = date('Y-m-d', strtotime($order_date . ' +1 day'));
	}
	
	
	date_default_timezone_set('UTC');

	// Create a DateTime object for the current time
	$current_date_time = new DateTime('now');
	
	// Add 8 hours to adjust to Singapore time (GMT+8)
	$current_date_time->modify('+8 hours');
	
	// Format the datetime
	$created_date = $current_date_time->format('Y-m-d H:i:s');

    $data = array(
        'bill_no' => $bill_no,
		'do_bill_no'=>$do_bill_no,
        'date_time' => $date_time,
		'delivery_date' =>$order_date,
        'gross_amount' => $this->input->post('gross_amount_value'),
        'service_charge_rate' => $this->input->post('service_charge_value'),

		'shipping_address' => $this->input->post('shipping_address'),
		'shipping_address_line2' => $this->input->post('shipping_address_line2'),
		'shipping_address_line3' => $this->input->post('shipping_address_line3'),
		'shipping_address_line4' => $this->input->post('shipping_address_line4'),
		'shipping_address_city' => $this->input->post('shipping_address_city'),
		'shipping_address_postcode' => $this->input->post('shipping_address_postcode'),

        'delivery_charge' => $this->input->post('delivery_charge_value'),
        'net_amount' => $this->input->post('net_amount_value'),
        'discount' => $this->input->post('discount'),
        'gst_amt' => $this->input->post('gst_rate'),
        'gst_percent' => $this->input->post('gst_value'),
		'feed_back' => $this->input->post('feed_back'),
		'packer_memo' => $this->input->post('packer_memo'),
		'driver_memo' => $this->input->post('driver_memo'),
		'po_ref' => $this->input->post('po_ref'),
        'paid_status' => 2,
        'user_id' => $user_id,
		'created_date' => $created_date,
    );



    // Insert order data into database
    $this->db->insert('orders', $data);
    $order_id = $this->db->insert_id(); // Get the last inserted order ID

    // Insert order items data into database
    $count_product = count($this->input->post('product'));
    for ($x = 0; $x < $count_product; $x++) {
        // Extract item details from POST data
        $category = !empty($this->input->post('category')[$x]) ? $this->input->post('category')[$x] : null;
        $product_id = !empty($this->input->post('product')[$x]) ? $this->input->post('product')[$x] : null;
        $qty = !empty($this->input->post('qty')[$x]) ? $this->input->post('qty')[$x] : null;
        $rate = !empty($this->input->post('rate_value')[$x]) ? $this->input->post('rate_value')[$x] : null;
        $amount = !empty($this->input->post('amount_value')[$x]) ? $this->input->post('amount_value')[$x] : null;
        $slice_type = !empty($this->input->post('sliced')[$x]) ? $this->input->post('sliced')[$x] : null;
        $seed_type = !empty($this->input->post('seed')[$x]) ? $this->input->post('seed')[$x] : null;

		$service_charge = !empty($this->input->post('service_charge_itemval')[$x]) ? $this->input->post('service_charge_itemval')[$x] : null;
        $gst_amt = !empty($this->input->post('gst_amount_value')[$x]) ? $this->input->post('gst_amount_value')[$x] : null;
		$gst_percent = 9;

		date_default_timezone_set('UTC');

		// Create a DateTime object for the current time
		$current_date_time = new DateTime('now');
		
		// Add 8 hours to adjust to Singapore time (GMT+8)
		$current_date_time->modify('+8 hours');
		
		// Format the datetime
		$created_date = $current_date_time->format('Y-m-d H:i:s');
		
		// Add 3 days to the current date and time
		$delivery_date = clone $current_date_time;
		$delivery_date->modify('+3 days');

		// Format the delivery date as desired
		$delivery_date_formatted = $delivery_date->format('Y-m-d H:i:s');

		$pre_order = $this->input->post('pre_order_date');

		if($pre_order == '0000-00-00' || $pre_order == null || $pre_order == ''){
			
			$order_date = $delivery_date_formatted;

		}

		else{

			$order_date = $pre_order;
		}

		if (date('w', strtotime($order_date)) == 0) {
			// Adjust order_date to the following Monday
			$order_date = date('Y-m-d', strtotime($order_date . ' +1 day'));
		}

        $items = array(
            'order_id' => $order_id,
            'category' => $category,
            'product_id' => $product_id,
            'qty' => $qty,
            'rate' => $rate,
            'amount' => $amount,
            'slice_type' => $slice_type,
            'seed_type' => $seed_type,
			'service_charge' => $service_charge,
			'gst_percent' => $gst_percent,
			'gst_amount' => $gst_amt,
			'created_date' => $created_date,
			'delivery_date' => $order_date,
        );
        $this->db->insert('order_items', $items);
    }

    $query = $this->db->select('bill_no')->where('user_id', $user_id)->where('id', $order_id)->get('orders');
    $result = $query->row_array();
    $bill_no = $result['bill_no'];

    return array('id' => $order_id, 'order_id' => $order_id, 'bill_no' => $bill_no,'email'=>$email);
	//}
}

public function update($id,$user_id)
{
    if ($id) {
        // Retrieve user information from session
        $user = $this->session->userdata('normal_user');
        $user_id = $user_id;
		$email = $user->email;

		$date_time = strtotime(date('Y-m-d h:i:s a'));

	$delivery_date = strtotime('+3 days', $date_time);


	$delivery_date_formatted = date('Y-m-d h:i:s a', $delivery_date);


	$pre_order = $this->input->post('pre_order_date');

		if($pre_order == '0000-00-00' || $pre_order == null || $pre_order == ''){
			
			$order_date = $delivery_date_formatted;

		}

		else{

			$order_date = $pre_order;
		}

		if (date('w', strtotime($order_date)) == 0) {
			// Adjust order_date to the following Monday
			$order_date = date('Y-m-d', strtotime($order_date . ' +1 day'));
		}

		date_default_timezone_set('UTC');

		// Create a DateTime object for the current time
		$current_date_time = new DateTime('now');
			
			// Add 8 hours to adjust to Singapore time (GMT+8)
		$current_date_time->modify('+8 hours');
			
			// Format the datetime
		$created_date = $current_date_time->format('Y-m-d H:i:s');

        // Data to update in the orders table

		
        $order_data = array(
            'gross_amount' => $this->input->post('gross_amount_value'),
            'service_charge_rate' => $this->input->post('service_charge_value'),
            'delivery_charge' => $this->input->post('delivery_charge_value'),
            'net_amount' => $this->input->post('net_amount_value'),
            'discount' => $this->input->post('discount'),
            'gst_amt' => $this->input->post('gst_rate'),
            'gst_percent' => $this->input->post('gst_value'),
            'paid_status' => 2, // Assuming 2 indicates confirmed/paid status
			'delivery_date' => $order_date,
			'feed_back' => $this->input->post('feed_back'),
			'packer_memo' => $this->input->post('packer_memo'),
			'driver_memo' => $this->input->post('driver_memo'),
			'po_ref' => $this->input->post('po_ref'),
			'shipping_address' => $this->input->post('shipping_address'),
			'shipping_address_line2' => $this->input->post('shipping_address_line2'),
			'shipping_address_line3' => $this->input->post('shipping_address_line3'),
			'shipping_address_line4' => $this->input->post('shipping_address_line4'),
			'shipping_address_city' => $this->input->post('shipping_address_city'),
			'shipping_address_postcode' => $this->input->post('shipping_address_postcode'),	
			'user_id' => $user_id,
			'created_date' => $created_date,
        );

        // Update order data in the orders table
        $this->db->where('id', $id);
        $update_order = $this->db->update('orders', $order_data);

        if (!$update_order) {
            // Error occurred while updating order data
            $this->session->set_flashdata('error', 'Failed to update order. Please try again.');
            redirect('orders/update/' . $id, 'refresh');
        }

        // Delete existing order items
        $this->db->where('order_id', $id);
        $this->db->delete('order_items');

        // Re-insert updated order items
        $product = $this->input->post('product');
        if (is_array($product)) {
            $count_product = count($product);
        } else {
            // Handle the case when $product is not an array
            $count_product = 0; // or any default value you prefer
        }

		date_default_timezone_set('UTC');

			// Create a DateTime object for the current time
		$current_date_time = new DateTime('now');
			
			// Add 8 hours to adjust to Singapore time (GMT+8)
		$current_date_time->modify('+8 hours');
			
			// Format the datetime
		$created_date = $current_date_time->format('Y-m-d H:i:s');

        for ($x = 0; $x < $count_product; $x++) {
            $order_item_data = array(
                'order_id' => $id,
                'category' => $this->input->post('category')[$x],
                'product_id' => $this->input->post('product')[$x],
                'qty' => $this->input->post('qty')[$x],
                'rate' => $this->input->post('rate_value')[$x],
                'amount' => $this->input->post('amount_value')[$x],
				'slice_type' => isset($this->input->post('sliced')[$x]) ? $this->input->post('sliced')[$x] : '',
				'seed_type' => isset($this->input->post('seed')[$x]) ? $this->input->post('seed')[$x] : '',
                'service_charge' => $this->input->post('service_charge_itemval')[$x],
                'gst_percent' => 9, // Assuming GST percent is fixed at 9%
                'gst_amount' => $this->input->post('gst_amount_value')[$x],
				'delivery_date' => $order_date,
                'created_date' => $created_date, // Assuming you want to update creation date
            );
            // Insert order item data into order_items table
            $this->db->insert('order_items', $order_item_data);
        }

		

        $query = $this->db->select('bill_no')->where('user_id', $user_id)->where('id', $id)->get('orders');
		$result = $query->row_array();
		$bill_no = $result['bill_no'];

		$query1 = $this->db->select('email')->where('id', $user_id)->get('user_register');
		$result1 = $query1->row_array();
		$user_email = $result1['email'];
		return array('id' => $id, 'order_id' => $id, 'bill_no' => $bill_no, 'email' => $user_email);
    }
}


	public function getOrdertotal($order_id = null)
	{
		if(!$order_id) {
			return false;
		}

		$sql = "SELECT ord.*, user.*, ord.driver_memo as memo, ord.packer_memo as pmemo ,  ord.po_ref as po_ref FROM orders ord join user_register user WHERE ord.id = ? and user.id=ord.user_id";
		$query = $this->db->query($sql, array($order_id));
		return $query->result_array();
	}
    

    public function getOrdersData() {
        //     $this->db->limit($limit, $offset);
             return $this->db->get('orders')->result();
         }

		 public function getorderuser($id) {
			if ($id) {
				$sql = "SELECT * FROM orders WHERE user_id = ?";
				$query = $this->db->query($sql, array($id));
				return $query->result(); // Use result() to fetch objects
			}
		}
		

    public function getOrdersDatas($id = null,$user_id)
	{
		if($id) {
			$sql = "SELECT ord.*,user.id as user_id,user.name as name,user.address as address, user.address_line2 as address_line2,
			user.address_city, user.address_postcode, user.delivery_address, user.delivery_address_line2, user.delivery_address_city,
			user.delivery_address_postcode  FROM orders ord join user_register user WHERE ord.user_id = user.id  and ord.id = ? and ord.user_id = '".$user_id."'";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}
	}

    public function getOrdersItemData($order_id = null)
	{
		if(!$order_id) {
			return false;
		}

		$sql = "SELECT * FROM order_items WHERE order_id = ?";
		$query = $this->db->query($sql, array($order_id));
		return $query->result_array();
	}

	public function getOrdersItemDatas($order_id = null)
	{
		if (!$order_id) {
			return false;
		}

		$sql = "SELECT ord.*, pd.product_name , pd.product_id as prod_id
				FROM order_items ord 
				LEFT JOIN products pd ON ord.product_id = pd.id 
				WHERE ord.order_id = ?";

		
		$query = $this->db->query($sql, array($order_id));
		return $query->result_array();
	}


    public function countOrderItem($order_id)
	{
		if($order_id) {
			$sql = "SELECT * FROM order_items WHERE order_id = ?";
			$query = $this->db->query($sql, array($order_id));
			return $query->num_rows();
		}
	}

	public function getmanageorder() {
	
			$sql = "SELECT ord.*,us.name as name FROM orders ord join user_register us WHERE us.id = ord.user_id limit 10";
			$query = $this->db->query($sql);
			
			
			return $query->result(); 
	
	}

	public function getOrdersadmin($id = null)
	{
		if($id) {
			$sql = "SELECT ord.*,user.* FROM orders ord join user_register user WHERE ord.user_id = user.id and ord.id = ? ";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}


	}

	public function getadminorderdata($order_id = null)
	{
		if (!$order_id) {
			return false;
		}

		$sql = "SELECT ord.*, pd.product_name , pd.product_id
				FROM order_items ord 
				LEFT JOIN products pd ON ord.product_id = pd.id 
				WHERE ord.order_id = ?";

		
		$query = $this->db->query($sql, array($order_id));
		return $query->result_array();
	}
	public function getorderadmintotal($order_id = null)
	{
		if(!$order_id) {
			return false;
		}

		$sql = "SELECT ord.*, user.*,ord.driver_memo as memo ,user.company_name as company_name FROM orders ord join user_register user WHERE ord.id = ? and user.id=ord.user_id";
		$query = $this->db->query($sql, array($order_id));
		return $query->result_array();
	}

	
public function getscheduleorder($schedule_date) {
	$sql = "SELECT prod.product_id as product_id,prod.product_name as product_name, SUM(ordd.qty) as qty, ordd.id as id, ordd.category as category 
			FROM order_items ordd 
			JOIN products prod ON ordd.product_id = prod.id 
			WHERE DATE(ordd.delivery_date) = '$schedule_date' 
			GROUP BY prod.product_name";

	$query = $this->db->query($sql);

	return $query->result(); 
}
public function getpackingorder($schedule_date) {
	$date = $schedule_date;
	$formatted_schedule_date = date("d/m/y", strtotime($date));
	$sql = "SELECT ord.*, orrr.*, uss.*,prod.product_id as prod_id,prod.product_name as product_name 
	FROM orders ord 
	JOIN order_items orrr ON ord.id = orrr.order_id 
	JOIN user_register uss ON ord.user_id = uss.id 
	join products prod ON orrr.product_id=prod.id
	WHERE DATE(orrr.delivery_date) = '$schedule_date';";
	$query = $this->db->query($sql);
	return $query->result(); 

}


public function repeat_order($id)
{

	$shipping_address = $this->input->post('shipping_address');
	$shipping_address_line2 = $this->input->post('shipping_address_line2');
	$shipping_address_line3 = $this->input->post('shipping_address_line3');
	$shipping_address_line4 = $this->input->post('shipping_address_line4');
	$shipping_address_city = $this->input->post('shipping_address_city');
	$shipping_address_postcode = $this->input->post('shipping_address_postcode');


    if($id) {
     
        $user = $this->session->userdata('normal_user');
        $user_id = $user->id;
		$email = $user->email;

		$current_year_month = date('ym');

		$sql = "SELECT bill_no FROM orders WHERE LENGTH(bill_no) = 8 ORDER BY bill_no DESC LIMIT 1;";
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0) {
			$latest_invoice_number = $query->row()->bill_no;
			$latest_invoice_month = substr($latest_invoice_number, 0, 4); 
			$latest_invoice_counter = intval(substr($latest_invoice_number, 4)); 
			if ($latest_invoice_month == $current_year_month) {
				$invoice_counter = $latest_invoice_counter + 1;
			} else {
				$invoice_counter = 1;
			}
		} else {
			$invoice_counter = 1;
		}
		
		$bill_no = $current_year_month . sprintf('%04d', $invoice_counter);
		$do_bill_no = 'DO'.$current_year_month . sprintf('%04d', $invoice_counter);
   
		$sql = "select * from orders where id=".$id;
		$query = $this->db->query($sql);
		$orderss = $query->result_array(); 

		$bill_no = $current_year_month . sprintf('%04d', $invoice_counter);

		$date_time = strtotime(date('Y-m-d h:i:s a'));

		$delivery_date = strtotime('+3 days', $date_time);

		$delivery_date_formatted = date('Y-m-d h:i:s a', $delivery_date);

		
		$pre_order = $this->input->post('pre_order_date');

		if($pre_order == '0000-00-00' || $pre_order == null || $pre_order == ''){
			
			$order_date = $delivery_date_formatted;

		}

		else{

			$order_date = $pre_order;
		}
		
		if (date('w', strtotime($order_date)) == 0) {
			// Adjust order_date to the following Monday
			$order_date = date('Y-m-d', strtotime($order_date . ' +1 day'));
		}

		date_default_timezone_set('UTC');

		// Create a DateTime object for the current time
		$current_date_time = new DateTime('now');
			
			// Add 8 hours to adjust to Singapore time (GMT+8)
		$current_date_time->modify('+8 hours');
			
			// Format the datetime
		$created_date = $current_date_time->format('Y-m-d H:i:s');
		
		foreach($orderss as $row){
		$data = array(
			'bill_no' => $bill_no,
			'do_bill_no'=>$do_bill_no,
			'date_time' => $date_time,
			'delivery_date' => $order_date,
			'gross_amount' => $row['gross_amount'],
			'service_charge_rate' => $row['service_charge_rate'],
			'delivery_charge' => $row['delivery_charge'],
			'net_amount' => $row['net_amount'],
			'discount' => $row['discount'],
			'gst_amt' => $row['gst_amt'],
			'gst_percent' => $row['gst_percent'],
			'feed_back' => $this->input->post('feed_back'),
			'driver_memo'=> $this->input->post('driver_memo'),
			'packer_memo' => $this->input->post('packer_memo'),
			'po_ref' => $this->input->post('po_ref'),
			'shipping_address' => $shipping_address,
			'shipping_address_line2' => $shipping_address_line2,
			'shipping_address_line3' => $shipping_address_line3,
			'shipping_address_line4' => $shipping_address_line4,
			'shipping_address_city' => $shipping_address_city,
			'shipping_address_postcode' => $shipping_address_postcode,
			'paid_status' => 2,
			'user_id' => $user_id,
			'created_date' => $created_date,
		);
	
		
		$this->db->insert('orders', $data);
		$order_id = $this->db->insert_id(); 
		}
		
		$sqll = "SELECT * FROM order_items WHERE order_id=" . $id;
		$queryy = $this->db->query($sqll);
		$orders_items = $queryy->result_array();

		foreach ($orders_items as $rows) {
			// Extracting fields from the current row
			$category = $rows['category'];
			$product_id = $rows['product_id'];
			$qty = $rows['qty'];
			$rate = $rows['rate'];
			$amount = $rows['amount'];
			$slice_type = $rows['slice_type'];
			$seed_type = $rows['seed_type'];
			$service_charge = $rows['service_charge'];
			$gst_amt = $rows['gst_amount'];
			$gst_percent = $rows['gst_percent'];


			date_default_timezone_set('UTC');

		// Create a DateTime object for the current time
			$current_date_time = new DateTime('now');
				
				// Add 8 hours to adjust to Singapore time (GMT+8)
			$current_date_time->modify('+8 hours');
				
				// Format the datetime
			$created_date = $current_date_time->format('Y-m-d H:i:s');

        $items = array(
            'order_id' => $order_id,
            'category' => $category,
            'product_id' => $product_id,
            'qty' => $qty,
            'rate' => $rate,
            'amount' => $amount,
            'slice_type' => $slice_type,
            'seed_type' => $seed_type,
			'service_charge' => $service_charge,
			'gst_percent' => $gst_percent,
			'gst_amount' => $gst_amt,
			'created_date' => $created_date,
			'delivery_date' => $order_date,
        );
			// Inserting the constructed array into the database
			$this->db->insert('order_items', $items);
		}
	
		$query = $this->db->select('bill_no')->where('user_id', $user_id)->where('id', $order_id)->get('orders');
		$result = $query->row_array();
		$bill_no = $result['bill_no'];
	
		return array('id' => $order_id, 'order_id' => $order_id, 'bill_no' => $bill_no, 'email' => $email);
    }
}

public function admin_create()
{

	$user = $this->session->userdata('normal_user');
   
	$email = $user->email;

	$user_id = $this->input->post('user_id');



  
	$current_year_month = date('ym');

	$sql = "SELECT bill_no FROM orders WHERE LENGTH(bill_no) = 8 ORDER BY bill_no DESC LIMIT 1;";
	$query = $this->db->query($sql);
	
	if ($query->num_rows() > 0) {
		$latest_invoice_number = $query->row()->bill_no;
		$latest_invoice_month = substr($latest_invoice_number, 0, 4); 
		$latest_invoice_counter = intval(substr($latest_invoice_number, 4)); 
		if ($latest_invoice_month == $current_year_month) {
			$invoice_counter = $latest_invoice_counter + 1;
		} else {
			
			$invoice_counter = 1;
		}
	} else {
		
		$invoice_counter = 1;
	}

	$date_time = strtotime(date('Y-m-d h:i:s a'));

	$delivery_date = strtotime('+3 days', $date_time);


	$delivery_date_formatted = date('Y-m-d h:i:s a', $delivery_date);


	$pre_order = $this->input->post('pre_order_date');

		if($pre_order == '0000-00-00' || $pre_order == null || $pre_order == ''){
			
			$order_date = $delivery_date_formatted;

		}

		else{

			$order_date = $pre_order;
		}

		if (date('w', strtotime($order_date)) == 0) {
			// Adjust order_date to the following Monday
			$order_date = date('Y-m-d', strtotime($order_date . ' +1 day'));
		}

	date_default_timezone_set('UTC');

		// Create a DateTime object for the current time
	$current_date_time = new DateTime('now');
		
		// Add 8 hours to adjust to Singapore time (GMT+8)
	$current_date_time->modify('+8 hours');
		
		// Format the datetime
	$created_date = $current_date_time->format('Y-m-d H:i:s');

	$bill_no = $current_year_month . sprintf('%04d', $invoice_counter);
	$do_bill_no = 'DO'.$current_year_month . sprintf('%04d', $invoice_counter);
    $data = array(
        'bill_no' => $bill_no,
		'do_bill_no'=>$do_bill_no,
        'date_time' => $date_time,
		'delivery_date' => $order_date,
        'gross_amount' => $this->input->post('gross_amount_value'),
        'service_charge_rate' => $this->input->post('service_charge_value'),
        'delivery_charge' => $this->input->post('delivery_charge_value'),
        'net_amount' => $this->input->post('net_amount_value'),
        'discount' => $this->input->post('discount'),
        'gst_amt' => $this->input->post('gst_rate'),
        'gst_percent' => $this->input->post('gst_value'),
		'shipping_address' => $this->input->post('shipping_address'),
		'shipping_address_line2' => $this->input->post('shipping_address_line2'),
		'shipping_address_line3' => $this->input->post('shipping_address_line3'),
		'shipping_address_line4' => $this->input->post('shipping_address_line4'),
		'shipping_address_city' => $this->input->post('shipping_address_city'),
		'shipping_address_postcode' => $this->input->post('shipping_address_postcode'),	
		'paid_status' => 2,
        'user_id' =>	$this->input->post('user_id'),
		'created_date' => $created_date,
		'driver_memo' => $this->input->post('driver_memo'),
		'packer_memo' => $this->input->post('packer_memo'),
		'po_ref' => $this->input->post('po_ref'),
    );

   
    $this->db->insert('orders', $data);
    $order_id = $this->db->insert_id(); 

  
    $count_product = count($this->input->post('product'));
    for ($x = 0; $x < $count_product; $x++) {
        $category = !empty($this->input->post('category')[$x]) ? $this->input->post('category')[$x] : null;
        $product_id = !empty($this->input->post('product')[$x]) ? $this->input->post('product')[$x] : null;
        $qty = !empty($this->input->post('qty')[$x]) ? $this->input->post('qty')[$x] : null;
        $rate = !empty($this->input->post('rate_value')[$x]) ? $this->input->post('rate_value')[$x] : null;
        $amount = !empty($this->input->post('amount_value')[$x]) ? $this->input->post('amount_value')[$x] : null;
        $slice_type = !empty($this->input->post('sliced')[$x]) ? $this->input->post('sliced')[$x] : null;
        $seed_type = !empty($this->input->post('seed')[$x]) ? $this->input->post('seed')[$x] : null;

		$service_charge = !empty($this->input->post('service_charge_itemval')[$x]) ? $this->input->post('service_charge_itemval')[$x] : null;
        $gst_amt = !empty($this->input->post('gst_amount_value')[$x]) ? $this->input->post('gst_amount_value')[$x] : null;
		$gst_percent = 9;

		date_default_timezone_set('UTC');

		// Create a DateTime object for the current time
		$current_date_time = new DateTime('now');
			
			// Add 8 hours to adjust to Singapore time (GMT+8)
		$current_date_time->modify('+8 hours');
			
			// Format the datetime
		$created_date = $current_date_time->format('Y-m-d H:i:s');
		
		// Add 3 days to the current date and time
		$delivery_date = clone $current_date_time;
		$delivery_date->modify('+3 days');

		// Format the delivery date as desired
		$delivery_date_formatted = $delivery_date->format('Y-m-d H:i:s');

		$pre_order = $this->input->post('pre_order_date');

		if($pre_order == '0000-00-00' || $pre_order == null || $pre_order == ''){
			
			$order_date = $delivery_date_formatted;

		}

		else{

			$order_date = $pre_order;
		}

		if (date('w', strtotime($order_date)) == 0) {
		// Adjust order_date to the following Monday
		$order_date = date('Y-m-d', strtotime($order_date . ' +1 day'));
	}

        $items = array(
            'order_id' => $order_id,
            'category' => $category,
            'product_id' => $product_id,
            'qty' => $qty,
            'rate' => $rate,
            'amount' => $amount,
            'slice_type' => $slice_type,
            'seed_type' => $seed_type,
			'service_charge' => $service_charge,
			'gst_percent' => $gst_percent,
			'gst_amount' => $gst_amt,
			'created_date' => $created_date,
			'delivery_date' => $order_date,
        );
        $this->db->insert('order_items', $items);
    }
   
	
	$query1 = $this->db->select('email')->where('id', $user_id)->get('user_register');
	$result1 = $query1->row_array();
	$user_email = $result1['email'];

    return array('id' => $order_id, 'order_id' => $order_id, 'bill_no' => $bill_no,'email' => $user_email);
	
}

public function count_all_orders() {
    return $this->db->count_all_results('orders');
}

public function count_search_results($keyword) {
	$this->db->like('bill_no', $keyword);
	return $this->db->count_all_results('orders');
}

public function getinvoice($date) {
	
	$sql = "SELECT bill_no FROM orders WHERE   DATE(delivery_date) = '$date'";
	$query = $this->db->query($sql);
	//echo $this->db->last_query();
	return $query->result(); 

}


public function getdo($date) {
	
	$sql = "SELECT do_bill_no FROM orders WHERE  DATE(delivery_date) = '$date'";
	$query = $this->db->query($sql);
	//echo $this->db->last_query();
	return $query->result(); 

}

public function deleteorder($id){
    // Delete order from 'orders' table
    $this->db->where('id', $id);
    $this->db->delete('orders');

    // Delete associated items from 'order_items' table
    $this->db->where('order_id', $id);
    $this->db->delete('order_items');

    // No need to return any data here since it's a deletion operation
    // Return true or false based on the success of deletion
    return ($this->db->affected_rows() > 0);
}


public function count_search_date($keyword) {
	$this->db->where('delivery_date', $keyword);
    return $this->db->count_all_results('orders');
}

public function search_date($keyword, $limit, $offset) {
    $this->db->where('delivery_date', $keyword);
    $this->db->limit($limit, $offset);
    $this->db->select('ord.*, us.name as name'); // Select fields from both tables

    $this->db->from('orders ord');
    $this->db->join('user_register us', 'us.id = ord.user_id');

    $query = $this->db->get();
	//var_dump($this->db->last_query()); // Check the generated SQL query
    return $query->result();
}



public function count_search_orderdate($keyword) {
	$this->db->where('DATE(created_date)', $keyword);
    return $this->db->count_all_results('orders');

	//var_dump($this->db->last_query()); // Check the generated SQL query
}

public function search_orderdate($keyword, $limit, $offset) {
	$this->db->where('DATE(created_date)', $keyword);
    $this->db->limit($limit, $offset);
    $this->db->select('ord.*, us.name as name'); // Select fields from both tables

    $this->db->from('orders ord');
    $this->db->join('user_register us', 'us.id = ord.user_id');

    $query = $this->db->get();
	//var_dump($this->db->last_query()); // Check the generated SQL query
    return $query->result();
}

public function getuseraddress($user_id){
	$this->db->where('id', $user_id);
    $this->db->select('*');
    $this->db->from('user_register');
    $query = $this->db->get();
    return $query->row_array();
}

public function count_search_orders($keyword) {
    $this->db->from('orders ord');
    $this->db->join('user_register us', 'us.id = ord.user_id');
    $this->db->like('ord.bill_no', $keyword);
    $this->db->or_like('us.name', $keyword);
    return $this->db->count_all_results();
}

public function search_orders($keyword, $limit, $offset) {
    $this->db->select('ord.*, us.name as name'); // Select fields from both tables
    $this->db->from('orders ord');
    $this->db->join('user_register us', 'us.id = ord.user_id');
    $this->db->like('ord.bill_no', $keyword);
    $this->db->or_like('us.name', $keyword);
    $this->db->limit($limit, $offset);
    
    $query = $this->db->get();
    // Uncomment the next line to debug the generated SQL query
    // var_dump($this->db->last_query()); // Check the generated SQL query
    return $query->result();
}


}