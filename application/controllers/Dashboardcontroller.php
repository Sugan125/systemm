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
        
        $query =  $this->db->query("SELECT COUNT(id) as count,MONTH(created_datee) as month FROM user_register GROUP BY MONTH(created_datee)"); 
        
        $record = $query->result();

        $query2 =  $this->db->query("SELECT COUNT(id) as count FROM user_register "); 
        
        $record2 = $query2->result();

        $query3 =  $this->db->query("SELECT COUNT(id) as count FROM google_users "); 
        
        $record3 = $query3->result();

        $query4 =  $this->db->query("SELECT SUM(distinct_count) AS total_distinct_count FROM ( SELECT COUNT(DISTINCT name) AS distinct_count
            FROM images GROUP BY name) AS subquery; "); 
        
        $record4 = $query4->result();

      
    $data['chart_data'] = [];
        $data = [];
  
        foreach($record as $row) {

           $month = $row->month;

           if (is_null($month)) {
            $month = date('m'); // 'm' returns the current month as a two-digit number
        }
        
           if($month == 01){
            $month_name = 'Jan';
           }
           else
           if($month == 02){
            $month_name = 'Feb';
           }
           else
           if($month == 03){
            $month_name = 'March';
           }
           else
           if($month == 04){
            $month_name = 'Apr';
           }
           else
           if($month == 05){
            $month_name = 'May';
           }
           else
           if($month == 06){
            $month_name = 'June';
           }
           else
           if($month == 10){
            $month_name = 'Oct';
           }
           else
          
           if($month == 11){
            $month_name = 'Nov';
           }
           else
           if($month == 12){
            $month_name = 'Dec';
           }

              $data['label'][] = $month_name;
              $data['data'][] = (int) $row->count;
        }

       
        $data['chart_data'] = json_encode($data);
        
        foreach($record2 as $rows) {
            $data['totalcount'] =  $rows->count;
        }


        foreach($record3 as $row1) {
            $data['googleuser'] =  $row1->count;
        }

        foreach($record4 as $row2) {
            $data['filesuploadpersons'] =  $row2->total_distinct_count;
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