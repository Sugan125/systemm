<?php

class Dashboardcontroller extends CI_Controller{

    function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function index(){

        $data['title'] = 'Dashboard';
        $this->load->view('template/header.php',$data);
        
        $query_day = $this->db->query("
        SELECT DATE(delivery_date) as date, COUNT(bill_no) as count, SUM(net_amount) as total_amt 
        FROM orders 
        GROUP BY DATE(delivery_date)
        ");
        $data['day_data'] = $query_day->result();

        // Fetch data for orders grouped by month
        $query_month = $this->db->query("
            SELECT DATE_FORMAT(delivery_date, '%Y-%m') as month, COUNT(bill_no) as count, SUM(net_amount) as total_amt 
            FROM orders 
            GROUP BY DATE_FORMAT(delivery_date, '%Y-%m')
        ");
        $data['month_data'] = $query_month->result();

        // Fetch data for orders grouped by year
        $query_year = $this->db->query("
            SELECT YEAR(delivery_date) as year, COUNT(bill_no) as count, SUM(net_amount) as total_amt 
            FROM orders 
            GROUP BY YEAR(delivery_date)
        ");
        $data['year_data'] = $query_year->result();

     
        $query5 = $this->db->query("SELECT COUNT(bill_no) as count FROM orders WHERE DATE(created_date) = CURDATE()"); 

        $record5 = $query5->result();
    
        foreach($record5 as $row5) {
            $data['today_orders'] =  $row5->count;
        }

        $query6= $this->db->query("SELECT sum(net_amount) as total_amt FROM orders WHERE DATE(delivery_date) = CURDATE()"); 

        $record6 = $query6->result();
    
        foreach($record6 as $row6) {
            $data['total_amt_sales'] =  $row6->total_amt;
        }

        $query6 = $this->db->query("SELECT COUNT(id) as count FROM products WHERE active = 1"); 

        $record6 = $query6->result();
       
        foreach($record6 as $row6) {
            $data['total_prods'] =  $row6->count;
        }


        $query7 = $this->db->query("SELECT COUNT(id) as count FROM user_register WHERE status = 1"); 

        $record7 = $query7->result();

        foreach($record7 as $row7) {
            $data['total_cust'] =  $row7->count;
        }
        

        $user = $this->session->userdata('user_register');
        $users = $this->session->userdata('normal_user');
        $loginuser = $this->session->userdata('LoginSession');
        //var_dump($loginuser);
        $this->load->view('template/header.php');
        $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
        $this->load->view('template/dashboard.php',$data);
        $this->load->view('template/footer.php');

    }


    public function viewpage(){

        if($this->session->has_userdata('user_register')){
            $user = $this->session->userdata('user_register');
            $this->load->view('dashboard',array('user'=>$user));
        }else{
            redirect('dashboard');
        }
     
    }
}

?>