<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GstController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Gst_model');
    }

    public function index() {
        $data['gst'] = $this->Gst_model->get_gst();

        $this->load->view('template/header.php', $data);
        $user = $this->session->userdata('user_register');
        $users = $this->session->userdata('normal_user');
        $loginuser = $this->session->userdata('LoginSession');
        //var_dump($loginuser);
        $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
        $this->load->view('gst_view', $data);
        $this->load->view('template/footer.php');
      
    }

    public function update_gst() {
        $gst_percentage = $this->input->post('gst_percentage');
        
        if ($this->Gst_model->update_gst($gst_percentage)) {
            $this->session->set_flashdata('success', 'GST updated successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to update GST.');
        }

        redirect('GstController');
    }
}
?>
