<?php 
	use Dompdf\Dompdf;
	class PDF extends CI_Controller{
		public function __construct()
		{
			parent::__construct();
			$this->load->database();
			$this->load->model('user_model');
			$this->load->helper('url');
		}

		public function index(){
			$users = $this->user_model->get_users();
			$this->load->view('users',array('users'=>$users));
		}

		public function download($id){
			$user = $this->user_model->getUser($id);
			$html = $this->load->view('pdf',array('user'=>$user),true);
			$dompdf = new Dompdf();
			$dompdf->loadHtml($html);
			$dompdf->render();
			$dompdf->stream();
		}
	}

?>
