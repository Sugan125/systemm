<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\RichText\RichText;


defined('BASEPATH') OR exit('No direct script access allowed');

class LabelController extends CI_Controller {

public function __construct() {
    parent::__construct();
    $this->load->model('Label_model');
}
 
public function index() {
    $data['title'] = 'Label Products';

    $config['base_url'] = site_url('LabelController/search');
    $config['total_rows'] = $this->Label_model->count_all_products(); // Ensure at least 10 total rows
    $config['per_page'] = 10;
    $config['uri_segment'] = 3;
    $config['use_page_numbers'] = TRUE;

    $config['full_tag_open'] = '<ul class="pagination">';
    $config['full_tag_close'] = '</ul>';
    $config['first_link'] = 'First';
    $config['last_link'] = 'Last';
    $config['first_tag_open'] = '<li class="page-item"><span class="page-link">';
    $config['first_tag_close'] = '</span></li>';
    $config['prev_link'] = 'Previous';
    $config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';
    $config['prev_tag_close'] = '</span></li>';
    $config['next_link'] = 'Next';
    $config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
    $config['next_tag_close'] = '</span></li>';
    $config['last_tag_open'] = '<li class="page-item"><span class="page-link">';
    $config['last_tag_close'] = '</span></li>';
    $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
    $config['cur_tag_close'] = '</span></li>';
    $config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
    $config['num_tag_close'] = '</span></li>'; 

    $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1; // Set the default page to 1
    $offset = ($page - 1) * $config['per_page'];
    
    $config['total_rows'] = $this->Label_model->count_all_products(); 

    $this->pagination->initialize($config);

    $this->load->view('template/header.php', $data);
    $user = $this->session->userdata('user_register');
    $users = $this->session->userdata('normal_user');
    $loginuser = $this->session->userdata('LoginSession');
    $data['total_rows'] = $this->Label_model->count_all_products();
    $data['products'] = $this->Label_model->product_details();



    $this->load->view('template/header.php');
    $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
    $this->load->view('labels/Labels.php', $data);
    $this->load->view('template/footer.php');
}

public function search() {
    $keyword = $this->input->get('keyword');
    $data['title'] = 'Dashboard';

    // Pagination Config for Search Results
    $config['base_url'] = site_url('LabelController/search');
    $config['total_rows'] = max($this->Label_model->count_search_results($keyword), 10); // Ensure at least 10 total rows
    $config['per_page'] = 10;
    $config['use_page_numbers'] = TRUE;
    $config['reuse_query_string'] = TRUE;

    $config['full_tag_open'] = '<ul class="pagination">';
    $config['full_tag_close'] = '</ul>';
    $config['first_link'] = 'First';
    $config['last_link'] = 'Last';
    $config['first_tag_open'] = '<li class="page-item"><span class="page-link">';
    $config['first_tag_close'] = '</span></li>';
    $config['prev_link'] = 'Previous';
    $config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';
    $config['prev_tag_close'] = '</span></li>';
    $config['next_link'] = 'Next';
    $config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
    $config['next_tag_close'] = '</span></li>';
    $config['last_tag_open'] = '<li class="page-item"><span class="page-link">';
    $config['last_tag_close'] = '</span></li>';
    $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
    $config['cur_tag_close'] = '</span></li>';
    $config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
    $config['num_tag_close'] = '</span></li>'; 

    $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1; // Set the default page to 1
    $offset = ($page - 1) * $config['per_page'];
    $data['products'] = $this->Label_model->search_products($keyword, $config['per_page'], $offset);
    $data['total_rows'] = $this->Label_model->count_search_results($keyword);

    $this->pagination->initialize($config);
    $data['keyword'] = $keyword;

    $this->load->view('template/header.php', $data);
    $user = $this->session->userdata('user_register');
    $users = $this->session->userdata('normal_user');
    $loginuser = $this->session->userdata('LoginSession');
    //var_dump($loginuser);
    $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
    $this->load->view('labels/Labels.php', $data);
    $this->load->view('template/footer.php');
}

 function Download_label()
 {
    $data['print'] = 'print';

    $loginuser = $this->session->userdata('LoginSession');
    
    $data['user_id'] = $loginuser['id'];
    
    $user_id = $data['user_id'];
    
    $this->load->model('Label_model');
    $data['products'] = $this->Label_model->get_all_products(); 
    
    $this->load->view('template/header.php', $data);
    $user = $this->session->userdata('user_register');
    $users = $this->session->userdata('normal_user');
    $loginuser = $this->session->userdata('LoginSession');
    $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
    $this->load->view('labels/download_label.php', $data);
    $this->load->view('template/footer.php');
 }

 public function deletelabel($id){

    $data = array(
        'id'=>$id, 
 );


 $this->Label_model->delete_data($data,'products_label');
 $this->session->set_flashdata('deleted','<div class="alert alert-Warning alert-dismissible fade show" role="alert">Label deleted Successfully!
 <button type="button" class="close" data-dismiss="alert" arial-label="close"> <span aria-hidden="true">&times;</span></button></div>');
 redirect('LabelController');
 }

 public function updatelabel($id){

    $data = array(
        'id'=>$id
 );

    $title['title'] = 'Update';
    
    $data['products'] = $this->Label_model->update_data($data,'products_label');
 
    $this->load->view('template/header.php',$title);
    $user = $this->session->userdata('user_register');
    $users = $this->session->userdata('normal_user');
    $loginuser = $this->session->userdata('LoginSession');
    //var_dump($loginuser);
    $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'title' => $title,'loginuser' => $loginuser));
    $this->load->view('labels/update_labels.php',$data);
    $this->load->view('template/footer.php');
 }
 public function updatelabelss($id){
    
    date_default_timezone_set('UTC');

    // Create a DateTime object for the current time
    $current_date_time = new DateTime('now');
        
        // Add 8 hours to adjust to Singapore time (GMT+8)
    $current_date_time->modify('+8 hours');
        
        // Format the datetime
    $updated_date = $current_date_time->format('Y-m-d H:i:s');


    $data = array(
        'id'      => $id,
        'product_name' => $this->input->post('product_name'),
        'ingredients' => $this->input->post('ingredients'),
        'updated_by' =>  $this->input->post('updated_by'),
        'updated_time' => $updated_date,
    );
   
     $this->Label_model->update_datas($data,'products_label');
     $this->session->set_flashdata('updated','<div class="alert alert-success alert-dismissible fade show" role="alert">Label Updated Successfully!
     <button type="button" class="close" data-dismiss="alert" arial-label="close"> <span aria-hidden="true">&times;</span></button></div>');
     redirect('LabelController');
     }

     public function create() {
        $data['title'] = 'Dashboard';
        $this->load->view('template/header.php', $data);
        $user = $this->session->userdata('user_register');
        $users = $this->session->userdata('normal_user');
        $loginuser = $this->session->userdata('LoginSession');
        //var_dump($loginuser);
        $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data,'loginuser' => $loginuser));
        $this->load->view('labels/create_labels.php');
        $this->load->view('template/footer.php');
    }

    public function addlabel(){
     
       

        date_default_timezone_set('UTC');

		// Create a DateTime object for the current time
		$current_date_time = new DateTime('now');
			
			// Add 8 hours to adjust to Singapore time (GMT+8)
		$current_date_time->modify('+8 hours');
			
			// Format the datetime
		$created_date = $current_date_time->format('Y-m-d H:i:s');
    
        $data = array(
            
            'product_name' => $this->input->post('product_name'),
            'ingredients' => $this->input->post('ingredients'),
            'created_by' => $this->input->post('created_by'),
            'created_time' => $created_date,            
        );
        
        
        $this->Label_model->insert_label($data,'products_label');
    
        // Set flashdata for success message
        $this->session->set_flashdata('created', '<div class="alert alert-success alert-dismissible fade show" role="alert">Label Created Successfully!
        <button type="button" class="close" data-dismiss="alert" arial-label="close"> <span aria-hidden="true">&times;</span></button></div>');
    
        // Redirect to a specific page (change this as needed)
        redirect('LabelController');
    }
    
public function generate_labels()
{
    try {
        // Clean any previous output buffer
        while (ob_get_level()) {
            ob_end_clean();
        }
        ob_start();

        // Enable error reporting
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $this->load->model('Label_model');

        // Get POST data
        $production_date = $this->input->post('production_date');
        $no_of_labels = $this->input->post('no_of_labels');
        $product_id = $this->input->post('product_id');

        if (!$product_id || !$production_date || !$no_of_labels) {
            throw new Exception("Missing POST data.");
        }

        $product = $this->Label_model->get_product_by_id($product_id);
        if (!$product) {
            throw new Exception("Product not found.");
        }

        $chilled_date = date('d/m/y', strtotime($production_date . ' +5 days'));
        $frozen_date = date('d/m/y', strtotime($production_date . ' +14 days'));

        // Create Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Labels');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(11);

        // Set column widths
       // Set column widths manually
       // Set column widths manually (adjusted to 0.69 for columns B and D)
$sheet->getColumnDimension('A')->setWidth(35.78);  // Set width for column A
$sheet->getColumnDimension('B')->setWidth(1.17); // Set width for column B (narrow column)
$sheet->getColumnDimension('C')->setWidth(38.89);  // Set width for column C
$sheet->getColumnDimension('D')->setWidth(1.17); // Set width for column D (narrow column)
$sheet->getColumnDimension('E')->setWidth(33.56);  // Set width for column E


        // Enable wrap text ONLY for heading cells
        $sheet->getStyle('A1')->getAlignment()->setWrapText(true);
        $sheet->getStyle('C1')->getAlignment()->setWrapText(true);
        $sheet->getStyle('E1')->getAlignment()->setWrapText(true);

        // Optional: Disable wrap text for other cells in those columns (e.g., rows 2 to 100)
        $sheet->getStyle('A2:A100')->getAlignment()->setWrapText(false);
        $sheet->getStyle('C2:C100')->getAlignment()->setWrapText(false);
        $sheet->getStyle('E2:E100')->getAlignment()->setWrapText(false);
    
        // Page layout
        $sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);

        //set print area


        $sheet->getPageSetup()->setPrintArea('A:E');

      // Margins in inches
        $sheet->getPageMargins()->setLeft(0.118110236220472);
        $sheet->getPageMargins()->setTop(0.15748031496063);
        $sheet->getPageMargins()->setHeader(0.31496062992126);
        $sheet->getPageMargins()->setRight(0);
        $sheet->getPageMargins()->setBottom(0);
        $sheet->getPageMargins()->setFooter(0.31496062992126);

        $sheet->getPageSetup()->setFitToPage(true);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(1);
         
    
        $row = 1;
        $colIndexes = ['A', 'C', 'E'];
    
        // $rowHeights = [
        //     24, 15.8, 30, 9.9, 9.9, 5.3, 15,
        //     24, 15.8, 30, 9.9, 9.9, 13.5, 13.5,
        //     22.8, 15.8, 30, 9.9, 9.9, 12, 12.8,
        //     22.8, 15.8, 30, 9.9, 9.9, 17.7, 15.8,
        //     22.8, 15.8, 30, 9.9, 9.9, 12.5, 12,
        //     22.8, 15.8, 30, 9.9, 9.9, 13.5, 17.3,
        //     22.8, 15.8, 30, 9.9, 9.9, 20.3, 9.8,
        //     22.8, 15.8, 30, 9.9, 9.9, 5.3,
        //     14.4
        // ];

        for ($i = 0; $i < $no_of_labels; $i++) {
            $col = $colIndexes[$i % 3];
    
            // Set row heights
            $sheet->getRowDimension(1)->setRowHeight(24);
            $sheet->getRowDimension(2)->setRowHeight(15.8);
            $sheet->getRowDimension(3)->setRowHeight(30);
            $sheet->getRowDimension(4)->setRowHeight(9.9);
            $sheet->getRowDimension(5)->setRowHeight(9.9);
            $sheet->getRowDimension(6)->setRowHeight(5.3);
            $sheet->getRowDimension(7)->setRowHeight(15);

            $sheet->getRowDimension(8)->setRowHeight(24);
            $sheet->getRowDimension(9)->setRowHeight(15.8);
            $sheet->getRowDimension(10)->setRowHeight(30);
            $sheet->getRowDimension(11)->setRowHeight(9.9);
            $sheet->getRowDimension(12)->setRowHeight(9.9);
            $sheet->getRowDimension(13)->setRowHeight(13.5);
            $sheet->getRowDimension(14)->setRowHeight(13.5);

            $sheet->getRowDimension(15)->setRowHeight(22.8);
            $sheet->getRowDimension(16)->setRowHeight(15.8);
            $sheet->getRowDimension(17)->setRowHeight(30);
            $sheet->getRowDimension(18)->setRowHeight(9.9);
            $sheet->getRowDimension(19)->setRowHeight(9.9);
            $sheet->getRowDimension(20)->setRowHeight(12);
            $sheet->getRowDimension(21)->setRowHeight(12.8);

            $sheet->getRowDimension(22)->setRowHeight(22.8);
            $sheet->getRowDimension(23)->setRowHeight(15.8);
            $sheet->getRowDimension(24)->setRowHeight(30);
            $sheet->getRowDimension(25)->setRowHeight(9.9);
            $sheet->getRowDimension(26)->setRowHeight(9.9);
            $sheet->getRowDimension(27)->setRowHeight(17.7);
            $sheet->getRowDimension(28)->setRowHeight(15.8);

            $sheet->getRowDimension(29)->setRowHeight(22.8);
            $sheet->getRowDimension(30)->setRowHeight(15.8);
            $sheet->getRowDimension(31)->setRowHeight(30);
            $sheet->getRowDimension(32)->setRowHeight(9.9);
            $sheet->getRowDimension(33)->setRowHeight(9.9);
            $sheet->getRowDimension(34)->setRowHeight(12.5);
            $sheet->getRowDimension(35)->setRowHeight(12);

            $sheet->getRowDimension(36)->setRowHeight(22.8);
            $sheet->getRowDimension(37)->setRowHeight(15.8);
            $sheet->getRowDimension(38)->setRowHeight(30);
            $sheet->getRowDimension(39)->setRowHeight(9.9);
            $sheet->getRowDimension(40)->setRowHeight(9.9);
            $sheet->getRowDimension(41)->setRowHeight(13.5);
            $sheet->getRowDimension(42)->setRowHeight(17.3);

            $sheet->getRowDimension(43)->setRowHeight(22.8);
            $sheet->getRowDimension(44)->setRowHeight(15.8);
            $sheet->getRowDimension(45)->setRowHeight(30);
            $sheet->getRowDimension(46)->setRowHeight(9.9);
            $sheet->getRowDimension(47)->setRowHeight(9.9);
            $sheet->getRowDimension(48)->setRowHeight(20.3);
            $sheet->getRowDimension(49)->setRowHeight(9.8);

            $sheet->getRowDimension(50)->setRowHeight(22.8);
            $sheet->getRowDimension(51)->setRowHeight(15.8);
            $sheet->getRowDimension(52)->setRowHeight(30);
            $sheet->getRowDimension(53)->setRowHeight(9.9);
            $sheet->getRowDimension(54)->setRowHeight(9.9);
            $sheet->getRowDimension(55)->setRowHeight(5.3);
            $sheet->getRowDimension(56)->setRowHeight(14.4);
    
            // Line 1: Header
            $richText = new \PhpOffice\PhpSpreadsheet\RichText\RichText();

            // First line: Sourdough Factory (custom font "Sourdough")
            $factoryText = $richText->createTextRun("Sourdough Factory\n");
            $factoryText->getFont()
                ->setName('Sourdough') // Make sure "Sourdough" font is installed
                ->setSize(11)
                ->setBold(false); // Based on your style
            
            // Second line: Address
            $addressText = $richText->createTextRun("Add: 5 Mandai Link #07-05 S(725654)");
            $addressText->getFont()
                ->setSize(6);
            
            // Assign rich text to cell
            $sheet->setCellValue("{$col}{$row}", $richText);
            
            // Set alignment and wrapping
            $sheet->getStyle("{$col}{$row}")->getAlignment()
                ->setWrapText(true)
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)
                ->setIndent(1);
                        
            
    
            // Line 2: Product Name
            $sheet->setCellValue("{$col}" . ($row + 1), $product['product_name']);
            $sheet->getStyle("{$col}" . ($row + 1))->getFont()
                ->setSize(11)->setBold(true);
                $sheet->getStyle("{$col}" . ($row + 1))->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)
                ->setIndent(1);
            
    
            // Line 3: Ingredients
            $sheet->setCellValue("{$col}" . ($row + 2), "Ingredients: {$product['ingredients']}");
            $sheet->getStyle("{$col}" . ($row + 2))->getFont()
                ->setSize(6);
                $sheet->getStyle("{$col}" . ($row + 2))->getAlignment()
                ->setWrapText(true)
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)
                ->setIndent(1);
            
    
            // Line 4: Best Before Chilled
            $sheet->setCellValue("{$col}" . ($row + 3), "Best Before: {$chilled_date} - Chilled                (Batch No. : 250329)");
            $sheet->getStyle("{$col}" . ($row + 3))->getFont()
               ->setSize(7)->setBold(true);
                $sheet->getStyle("{$col}" . ($row + 3))->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)
                ->setIndent(1);
            
    
            // Line 5: Best Before Frozen
            $sheet->setCellValue("{$col}" . ($row + 4), "Best Before: {$frozen_date} - Frozen");
            $sheet->getStyle("{$col}" . ($row + 4))->getFont()
           ->setSize(7)->setBold(true);
            $sheet->getStyle("{$col}" . ($row + 4))->getAlignment()
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)
            ->setIndent(1);
        
    
            // Wrap text for all 5 lines
            for ($r = $row; $r <= $row + 4; $r++) {
                $sheet->getStyle("{$col}{$r}")->getAlignment()->setWrapText(true);
            }
    
            if (($i + 1) % 3 == 0) {
                $row += 7;
            }
        }

        $filename = 'Labels_' . date('Ymd_His') . '.xlsm';

        // Headers for download
        header('Content-Type: application/vnd.ms-excel.sheet.macroEnabled.12');
        header("Content-Disposition: attachment;filename=\"$filename\"");
        header('Cache-Control: max-age=0');
        header('Expires: 0');
        header('Pragma: public');

        // Enable macro support (if required, otherwise remove these lines)
        $spreadsheet->setHasMacros(true);

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;

    } catch (Exception $e) {
        error_log('Label export error: ' . $e->getMessage());
        show_error("An error occurred while generating the label: " . $e->getMessage(), 500);
    }
}

}

?>