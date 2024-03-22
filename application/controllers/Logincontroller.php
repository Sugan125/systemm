 

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Google\Client as GoogleClient;
use Google\Service\Oauth2;
class Logincontroller extends CI_Controller {

	function __construct() {
        parent::__construct();
		$this->load->model('user_model');
        $this->load->library('email');
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('facebook'); 
         
        // Load user model 
        $this->load->model('user'); 
    }
	
	public function index()
{
    $data = array();

    // Facebook Authentication
    $userData = array();

    // Authenticate user with Facebook
    if ($this->facebook->is_authenticated()) {
        // Get user info from Facebook
        $fbUser = $this->facebook->request('get', '/me?fields=id,name,email,link,image');

        // Preparing data for database insertion
        $userData['oauth_provider'] = 'facebook';
        $userData['oauth_uid'] = !empty($fbUser['id']) ? $fbUser['id'] : '';
       
        $userData['name'] = !empty($fbUser['last_name']) ? $fbUser['name'] : '';
        $userData['email'] = !empty($fbUser['email']) ? $fbUser['email'] : '';
        $userData['image'] = !empty($fbUser['image']['data']['url']) ? $fbUser['image']['data']['url'] : '';
        $userData['link'] = !empty($fbUser['link']) ? $fbUser['link'] : 'https://www.facebook.com/';

        // Insert or update user data to the database
        $userID = $this->user->checkUser($userData);

        // Check user data insert or update status
        if (!empty($userID)) {
            $data['userData'] = $userData;

            // Store the user profile info into session
            $this->session->set_userdata('userData', $userData);
        } else {
            $data['userData'] = array();
        }

        // Facebook logout URL
        $data['logoutURL'] = $this->facebook->logout_url();
    } else {
        // Facebook authentication URL
        $data['authURL'] =  $this->facebook->login_url();
    }

    // Form Submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $this->form_validation->set_rules("name", 'name', 'required');
        $this->form_validation->set_rules("password", 'password', 'required');

        if ($this->form_validation->run() == TRUE) {
            $name = $this->input->post('name');
            $password = $this->input->post('password');
            $this->load->model('user_model');
            $status = $this->user_model->checkUser($name, $password);

            if ($status != false) {
                $userData = array(
                    'id' => $status->id,
                    'name' => $status->name,
					'company_name' => $status->company_name,
                    'email' => $status->email,
                    'address' => $status->address,
					'delivery_address' => $status->delivery_address,
					'address' => $status->address,
                    'contact' => $status->contact,
					'status' => $status->status,
                    'profile_img' => $status->profile_img,
                    'access' => explode(',', $status->access),
                    'role' => explode(',', $status->role),
                    'accesss' => $status->access,
                    'roles' => $status->role,
                );

				//echo '<pre>';
			//	print_r($userData);
                $this->session->set_userdata('LoginSession', $userData);

                if ($user = $this->user_model->getNormalUser($status->email)) {
                    $this->session->set_userdata('normal_user', $user);
                }

                redirect(base_url('index.php/Dashboardcontroller'));
            } else {
                $this->session->set_flashdata('error', 'User Name or Password is Wrong');
                redirect(base_url('index.php/Logincontroller/index'));
            }
        } else {
            $this->load->view('login');
            return;
        }
    }

    // Load login/profile view
    $this->load->view('Login.php', $data);
}

    function logout()
	{
		session_unset();
		session_destroy();
		redirect(base_url('index.php/Logincontroller/index'));
	}


	public function forgotPassword(){
		$this->load->view('forgot_password');
	}

	


	public function changepasscontroller(){
		if($_SERVER['REQUEST_METHOD']=='POST')
		{
			$this->form_validation->set_rules('currentPassword','Current Password','required');
			$this->form_validation->set_rules('password','New Password','required');
			$this->form_validation->set_rules('cpassword','Confirm New Password','required|matches[password]');
			if($this->form_validation->run()==TRUE)
			{
				 $currentPassword = $this->input->post('currentPassword');
				// $encryptCurrentPassword = sha1($currentPassword);
				 $this->load->model('user_model');
				 $check = $this->user_model->checkCurrentPassword($currentPassword);

				 echo 'helooo'.$check;
				 if($check==true)
				 {
				 	$newPassword = $this->input->post('password');
				 	//$encryptPassword = sha1($newPassword);
				 	$this->user_model->updatePassword($newPassword);

				 	$this->session->set_flashdata('success','Password changed Successfully');
				 	redirect(base_url('index.php/Logincontroller/changepasswordview'));
				 }
				 else
				 {
				 	$this->session->set_flashdata('error','Current Password is wrong');
				 	redirect(base_url('index.php/Logincontroller/changepasswordview'));
				 }
			}
			else
			{
				$this->load->view('template/header.php');
				$user = $this->session->userdata('user_register');
				$users = $this->session->userdata('normal_user');
				
				$loginuser = $this->session->userdata('LoginSession');
        //var_dump($loginuser);
        $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
				$this->load->view('template/changepassword');
				$this->load->view('template/footer.php');
				
			}
		}
		else
		{
			$this->load->view('template/header.php');
			$user = $this->session->userdata('user_register');
			$users = $this->session->userdata('normal_user');
			$loginuser = $this->session->userdata('LoginSession');
        //var_dump($loginuser);
        $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
			$this->load->view('template/changepassword');
			$this->load->view('template/footer.php');
		}
	}
	
	public function changepasswordview(){
		
		
		$this->load->view('template/header.php');
        $user = $this->session->userdata('user_register');
        $users = $this->session->userdata('normal_user');
		$loginuser = $this->session->userdata('LoginSession');
        //var_dump($loginuser);
        $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users,'loginuser' => $loginuser));
        $this->load->view('template/changepassword');
        $this->load->view('template/footer.php');
	}

	public function send_password()
	{
		$toemail=$this->input->post('email');
		

		$config['protocol']  = 'smtp';
		$config['smtp_host'] = 'ssl://smtp.gmail.com';
		$config['smtp_port'] = '465';
		$config['smtp_timeout'] = '7';
		$config['smtp_user']  = 'mailto:suganyaulagu8@gmail.com';//host name
		$config['smtp_pass'] = 'qqcb mupl eyeb azdo';//host pswd
		$config['charset'] = 'utf-8';
		$config['newline']  = "\r\n";
		$config['mailtype'] = 'text'; // or html
		$config['validation'] = TRUE; // bool whether to validate email or not     
		$this->email->initialize($config);
		$from_email = $toemail;//from mail it can be any mail of user

		function pswd_gen()
		{
			$pswd='';
			$st='abcdefghijk';
			for($i=0;$i<5;$i++){
				$pswd.=$st[random_int(0,9)];
			}
			return $pswd;
		}

				$validateEmail = $this->user_model->validateEmail($from_email);
				if($validateEmail!=false)
				{

						$subject = "New password for your account";
						$message = pswd_gen();
						$msg = "Please login with your new password:  ".$message;

						//echo$message."<br>";
						$c=$this->user_model->changepswd($toemail,$message);

						

						// $data['name'] = $this->user_model->fecth_name($toemail);

						// $flashdataMessage = 'Please check your email for new password! User:' . $data['name'];

						$flashdataMessage = 'Please check your email for new password!';

						//add to session
						////echo$from_email;
						// //echo$to_email;
						////echo$subject;
						////echo$message;
						//Load email library
						$this->email->from($from_email, 'Sourdough Factory');//    from(from_mail,identification)
						$this->email->to($toemail);
						$this->email->subject($subject);
						$this->email->message($msg);
						//$this->email->attach('C:\Users\Suganya Arun\Downloads\invoice_1.pdf');
						$this->email->send();

						if ($c) 
						{
							$this->session->set_flashdata('sucess',$flashdataMessage);
							redirect(base_url('index.php/Logincontroller/index'));
						} 
				}

				else
					{
					
						$this->session->set_flashdata('errorss','Your email does not exist!');
						$this->load->view('forgot_password');	
					}

		
	}


	public function google_login()
	{
		
			$client = new GoogleClient();
			$client->setApplicationName('User google login');
			$client->setClientId('330578879977-lv6jgfkshm5pcrvdq19q847hka627724.apps.googleusercontent.com');
			$client->setClientSecret('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');
			$client->setRedirectUri('http://localhost/admin/index.php/Logincontroller/google_login');
			$client->addScope(['https://www.googleapis.com/auth/userinfo.email','https://www.googleapis.com/auth/userinfo.profile']);
			if($code = $this->input->get('code')){
				$token = $client->fetchAccessTokenWithAuthCode($code);
				$client->setAccessToken($token);
				$oauth = new Oauth2($client);
				
				$user_info = $oauth->userinfo->get();
				$data['name'] = $user_info->name;
				$data['email'] = $user_info->email;
				$data['image'] = $user_info->picture;
				
				if($user = $this->user_model->getUser($user_info->email)){
					$this->session->set_userdata('user_register',$user);
				}else{
					$this->user_model->createUser($data);
				}

				
				
				redirect('Dashboardcontroller');;


			}else{
				$url = $client->createAuthUrl();
				header('Location:'.filter_var($url,FILTER_SANITIZE_URL));
			}
	}

	

	
}
