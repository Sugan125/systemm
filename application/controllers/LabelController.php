<?php
use Dompdf\Dompdf;
use Dompdf\Options;
defined('BASEPATH') OR exit('No direct script access allowed');

class LabelController extends CI_Controller {
 
 function index()
 {
    $data['print'] = 'print';

    $loginuser = $this->session->userdata('LoginSession');
    
    $data['user_id'] = $loginuser['id'];
    
    $user_id = $data['user_id'];
    
    
    $this->load->view('template/header.php', $data);
    $user = $this->session->userdata('user_register');
    $users = $this->session->userdata('normal_user');
    $loginuser = $this->session->userdata('LoginSession');
    $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
    $this->load->view('labels/download_label.php', array('data' => $data,));
    $this->load->view('template/footer.php');
 }


public function generate_pdf() {
   
    $product_name = $this->input->get('product'); // Get product from URL
    
    if (empty($product_name)) {
        show_error("Invalid product name.");
    } else {
        // Pass product name to view
        $data['product_name'] = $product_name;
        $this->load->view('labels/labels_pdf.php', $data);
    }
}

}

?>