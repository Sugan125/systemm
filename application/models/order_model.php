<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class order_model extends CI_Model {
   public function getActiveProductData(){
            $sql = "SELECT * FROM products WHERE active = 1 ORDER BY product_name";
            $query = $this->db->query($sql, array(1));
            return $query->result_array();
   }
   public function getActivecatergoryData(){
            $sql = "SELECT prod_category FROM products WHERE active = 1 GROUP BY prod_category ASC";
            $query = $this->db->query($sql, array(1));
            return $query->result_array();
   }
   public function getProductsByCategory($categoryId) {
    $sql = "SELECT * FROM products WHERE active = 1 AND prod_category = ? ORDER BY product_id,product_name";
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

	if (date('H') >= 17) {
        // Redirect or show an error message indicating that orders cannot be placed after 5 PM
        $this->session->set_flashdata('error', 'Orders cannot be placed after 5 PM.');
        redirect('orders', 'refresh');
    }

	else{
    //$bill_no = 'CDSTRO-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
	$current_year_month = date('ym');

	$sql = "SELECT bill_no FROM orders ORDER BY bill_no DESC LIMIT 1";
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
	
    $data = array(
        'bill_no' => $bill_no,
        'date_time' => strtotime(date('Y-m-d h:i:s a')),
        'gross_amount' => $this->input->post('gross_amount_value'),
        'service_charge_rate' => $this->input->post('service_charge_value'),
        'delivery_charge' => $this->input->post('delivery_charge_value'),
        'net_amount' => $this->input->post('net_amount_value'),
        'discount' => $this->input->post('discount'),
        'gst_amt' => $this->input->post('gst_rate'),
        'gst_percent' => $this->input->post('gst_value'),
        'paid_status' => 2,
        'user_id' => $user_id,
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

        $items = array(
            'order_id' => $order_id,
            'category' => $category,
            'product_id' => $product_id,
            'qty' => $qty,
            'rate' => $rate,
            'amount' => $amount,
            'slice_type' => $slice_type,
            'seed_type' => $seed_type,
        );
        $this->db->insert('order_items', $items);
    }

    $query = $this->db->select('bill_no')->where('user_id', $user_id)->where('id', $order_id)->get('orders');
    $result = $query->row_array();
    $bill_no = $result['bill_no'];

    return array('id' => $order_id, 'order_id' => $order_id, 'bill_no' => $bill_no);
	}
}


public function update($id)
{
    if($id) {
        // Retrieve user information from session
        $user = $this->session->userdata('normal_user');
        $user_id = $user->id;

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
            'user_id' => $user_id,
        );

        // Update order data in the orders table
        $this->db->where('id', $id);
        $update_order = $this->db->update('orders', $order_data);

        if (!$update_order) {
            // Error occurred while updating order data
            $this->session->set_flashdata('error', 'Failed to update order. Please try again.');
            redirect('orders/update/' . $id, 'refresh');
        }


		$product = $this->input->post('product');
		if (is_array($product)) {
			$count_product = count($product);
		} else {
			// Handle the case when $product is not an array
			$count_product = 0; // or any default value you prefer
		}

        // Re-insert updated order items
        for ($x = 0; $x < $count_product; $x++) {
            $order_item_data = array(
                'order_id' => $id,
                'category' => $this->input->post('category')[$x],
                'product_id' => $this->input->post('product')[$x],
                'qty' => $this->input->post('qty')[$x],
                'rate' => $this->input->post('rate_value')[$x],
                'amount' => $this->input->post('amount_value')[$x],
                'slice_type' => $this->input->post('sliced')[$x],
                'seed_type' => $this->input->post('seed')[$x],
            );
            // Insert order item data into order_items table
            $this->db->insert('order_items', $order_item_data);
        }

        return array('update' => true);
    }
}

	public function getOrdertotal($order_id = null)
	{
		if(!$order_id) {
			return false;
		}

		$sql = "SELECT ord.*, user.name as name, user.address as address,user.company_name as company_name FROM orders ord join user_register user WHERE ord.id = ? and user.id=ord.user_id";
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
			$sql = "SELECT ord.*,user.id as user_id,user.name as name,user.address as address FROM orders ord join user_register user WHERE ord.user_id = user.id  and ord.id = ? and ord.user_id = '".$user_id."'";
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
	
			$sql = "SELECT ord.*,us.name as name FROM orders ord join user_register us WHERE us.id = ord.user_id";
			$query = $this->db->query($sql);
			return $query->result(); 
	
	}

	public function getOrdersadmin($id = null)
	{
		if($id) {
			$sql = "SELECT ord.*,user.id as user_id,user.name as name,user.address as address FROM orders ord join user_register user WHERE ord.user_id = user.id and ord.id = ? ";
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

		$sql = "SELECT ord.*, user.name as name, user.address as address, user.company_name as company_name FROM orders ord join user_register user WHERE ord.id = ? and user.id=ord.user_id";
		$query = $this->db->query($sql, array($order_id));
		return $query->result_array();
	}

	public function getscheduleorder($schedule_date) {
		$date = $schedule_date;
		$formatted_schedule_date = date("d/m/y", strtotime($date));
		$sql = "SELECT ordd.*,prod.product_id as product_id,prod.product_name as product_name FROM order_items ordd join products prod WHERE DATE(ordd.created_date) = '$schedule_date' and ordd.product_id=prod.id";
		$query = $this->db->query($sql);
		return $query->result(); 

}

public function getpackingorder($schedule_date) {
	$date = $schedule_date;
	$formatted_schedule_date = date("d/m/y", strtotime($date));
	$sql = "SELECT ord.*, orrr.*, uss.name, uss.company_name as company_name,prod.product_id as prod_id,prod.product_name as product_name 
	FROM orders ord 
	JOIN order_items orrr ON ord.id = orrr.order_id 
	JOIN user_register uss ON ord.user_id = uss.id 
	join products prod ON orrr.product_id=prod.id
	WHERE DATE(orrr.created_date) = '$schedule_date';";
	$query = $this->db->query($sql);
	return $query->result(); 

}


public function repeat_order($id)
{
    if($id) {
     
        $user = $this->session->userdata('normal_user');
        $user_id = $user->id;

		$current_year_month = date('ym');

		$sql = "SELECT bill_no FROM orders ORDER BY bill_no DESC LIMIT 1";
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

		$sql = "select * from orders where id=".$id;
		$query = $this->db->query($sql);
		$orderss = $query->result_array(); 

		foreach($orderss as $row){
		$data = array(
			'bill_no' => $bill_no,
			'date_time' => strtotime(date('Y-m-d h:i:s a')),
			'gross_amount' => $row['gross_amount'],
			'service_charge_rate' => $row['service_charge_rate'],
			'delivery_charge' => $row['delivery_charge'],
			'net_amount' => $row['net_amount'],
			'discount' => $row['discount'],
			'gst_amt' => $row['gst_amt'],
			'gst_percent' => $row['gst_percent'],
			'paid_status' => 2,
			'user_id' => $user_id,
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

			// Constructing the array for insertion
			$items = array(
				'order_id' => $order_id,
				'category' => $category,
				'product_id' => $product_id,
				'qty' => $qty,
				'rate' => $rate,
				'amount' => $amount,
				'slice_type' => $slice_type,
				'seed_type' => $seed_type,
			);

			// Inserting the constructed array into the database
			$this->db->insert('order_items', $items);
		}
		
		$query = $this->db->select('bill_no')->where('user_id', $user_id)->where('id', $order_id)->get('orders');
		$result = $query->row_array();
		$bill_no = $result['bill_no'];
	
		return array('id' => $order_id, 'order_id' => $order_id, 'bill_no' => $bill_no);
    }
}


}