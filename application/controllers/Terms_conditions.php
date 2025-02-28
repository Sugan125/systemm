<?php
use Dompdf\Dompdf;
defined('BASEPATH') OR exit('No direct script access allowed');

class Terms_conditions extends CI_Controller
{
    public function index()
    {
        $this->load->view('terms_conditions_view');
    }

    public function agree()
    {
        $sessionData = $this->session->userdata('LoginSession');

        if (!empty($sessionData) && isset($sessionData['id'])) {
            $user_id = $sessionData['id'];

            // Fetch user details
            $this->db->where('id', $user_id);
            $user = $this->db->get('user_register')->row();

            if (!$user) {
                redirect('auth/login');
                return;
            }

            $user_name = $user->name;
            $email = $user->email;

            // Update the database
            $this->db->where('id', $user_id);
            $this->db->update('user_register', ['terms_agreed' => 1]);
            date_default_timezone_set('Asia/Singapore');

            // Generate PDF content
            $html = '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Terms and Conditions</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
                    .container { width: 80%; margin: auto; padding: 40px; border: 3px solid #007BFF; text-align: center; }
                    h2 { color: #004080; font-size: 22px; font-weight: bold; text-transform: uppercase; margin-bottom: 20px; }
                    p, li { font-size: 15px; color: #333; line-height: 1.8; text-align: justify; }
                    ul { padding-left: 20px; text-align: left; }
                    .signature { margin-top: 30px; text-align: center; font-size: 18px; font-weight: bold; color: #004080; border-top: 2px solid #004080; padding-top: 10px; }
                </style>
            </head>
            <body>
            <div class="container">
                <h2>Terms and Conditions of Food Service Supply by Sourdough Factory LLP</h2>
                <p>By logging in for the first time, you confirm that you have read, understood, and agreed to our company policies. This agreement is a binding contract between you and <b>SOURDOUGH FACTORY LLP</b> regarding the use of our services. By checking the box, you accept these Terms & Conditions before proceeding with any transactions or placing orders via our EOS platform:
                    <a href="https://www.sourdoughfactory.com.sg/system" target="_blank">www.sourdoughfactory.com.sg/system</a>.
                </p>

                <ul>
                    <li><strong>Order Placement and Payment Obligations:</strong> Customers must verify all order details before confirmation. Payments must be made within the agreed terms as outlined in the account opening form. Failure to comply with payment terms may result in the suspension of EOS access. The company/business reserves the right to take legal action for debt recovery, and all associated recovery costs will be charged to the customer.</li>
                    <li><strong>Legal Recourse for Non-Payment:</strong> Failure to settle payments under COD (Cash on Delivery) terms may lead to legal action, including asset seizure. All legal costs incurred during the debt recovery process will be borne by the customer.</li>
                    <li><strong>Data Storage and Agreement Records:</strong> Your acceptance of these terms will be electronically recorded for legal reference.</li>
                    <li><strong>Modification of Terms:</strong> We reserve the right to update these terms at any time. Continued use of our services constitutes acceptance of any changes.</li>
                </ul>

                <div class="signature">
                    Agreed by: ' . htmlspecialchars($user_name) . ' <br>
                    Email: ' . $email . ' <br>
                    Date: ' . date("Y-m-d H:i:s") . '
                </div>
            </div>
            </body>
            </html>';

            // Initialize Dompdf and generate PDF
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');    
            $dompdf->render();

            $filename = "terms_agreed_user_" . $user_name . '.pdf';
            $filepath = FCPATH . 'agreements/' . $filename;
            file_put_contents($filepath, $dompdf->output());

            redirect(base_url('index.php/Dashboardcontroller'));
        } else {
            redirect('auth/login');
        }
    }
}
?>

