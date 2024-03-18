<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Google\Client as GoogleClient;
use Google\Service\Oauth2;
class Logincontroller extends CI_Controller {

	function __construct() {
        parent::__construct();
		$this->load->model('User_model');
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
            $this->load->model('User_model');
            $status = $this->User_model->checkUser($name, $password);

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

                $this->session->set_userdata('LoginSession', $userData);

                if ($user = $this->User_model->getNormalUser($status->email)) {
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
				 $this->load->model('User_model');
				 $check = $this->User_model->checkCurrentPassword($currentPassword);

				 echo 'helooo'.$check;
				 if($check==true)
				 {
				 	$newPassword = $this->input->post('password');
				 	//$encryptPassword = sha1($newPassword);
				 	$this->User_model->updatePassword($newPassword);

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
    $toemail = $this->input->post('email');

    $config['protocol']  = 'smtp';
    $config['smtp_host'] = 'ssl://mail.sourdoughfactory.com.sg';
    $config['smtp_port'] = '465';
    $config['smtp_timeout'] = '7';
    $config['smtp_user']  = 'finance@sourdoughfactory.com.sg'; // Remove 'mailto:' prefix
    $config['smtp_pass'] = 'achr3420';
    $config['charset'] = 'utf-8';
    $config['newline']  = "\r\n";
    $config['mailtype'] = 'text'; // or html
    $config['validation'] = TRUE;

    $this->load->library('email', $config); // Load email library with configuration

    $from_email = 'finance@sourdoughfactory.com.sg'; // Set from email

    // Generate password
    function pswd_gen()
    {
        $pswd = '';
        $st = 'abcdefghijk';
        for ($i = 0; $i < 5; $i++) {
            $pswd .= $st[random_int(0, 9)];
        }
        return $pswd;
    }

    // Validate email
    $validateEmail = $this->User_model->validateEmail($from_email);
    if ($validateEmail != false) {

        $subject = "Invoice testing";
        $message = pswd_gen();
        $msg = "test invoice";

        $c = $this->User_model->changepswd($toemail, $message);

        // Set flash data message
        $flashdataMessage = 'Please check your email for new password!';

        // Load email library
        $this->email->from($from_email, 'Sourdough Factory');
        $this->email->to($toemail);
        $this->email->subject($subject);
        $this->email->message($msg);

        // Send email
        if ($this->email->send()) {
            $this->session->set_flashdata('success', $flashdataMessage);
            redirect(base_url('index.php/Logincontroller/index'));
        } else {
            // Debugging statement
            $debug_output = $this->email->print_debugger();
            echo "Email sending failed: " . $debug_output;
            exit;
            // Uncomment the following lines if you want to redirect after debugging
            /*
            $this->session->set_flashdata('errorss', 'Email sending failed!');
            redirect(base_url('index.php/Logincontroller/index'));
            */
        }
    } else {
        // Debugging statement
        $debug_output = $this->email->print_debugger();
        echo "Email sending failed: " . $debug_output;
        
        $this->session->set_flashdata('errorss', 'Your email does not exist!');
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
				
				if($user = $this->User_model->getUser($user_info->email)){
					$this->session->set_userdata('user_register',$user);
				}else{
					$this->User_model->createUser($data);
				}

				
				
				redirect('Dashboardcontroller');;


			}else{
				$url = $client->createAuthUrl();
				header('Location:'.filter_var($url,FILTER_SANITIZE_URL));
			}
	}

	
}
?>
