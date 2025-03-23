<?php
use Dompdf\Dompdf;
use Dompdf\Options;
class Dashboardcontroller extends CI_Controller{

    function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function index(){

        $data['title'] = 'Dashboard';
        $this->load->view('template/header.php',$data);
        
        date_default_timezone_set('Asia/Singapore');

        $created_date = $this->input->post('created_date');
        if (!$created_date) {
            $created_date = date('Y-m-d'); // Default to today
        }
        $data['selected_date'] = $created_date;

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


        $query5 = $this->db->query("SELECT COUNT(bill_no) as count FROM orders WHERE DATE(created_date) = '$created_date'");
  

        $record5 = $query5->result();
    
        foreach($record5 as $row5) {
            $data['today_orders'] =  $row5->count;
        }

        $query6= $this->db->query("SELECT sum(net_amount) as total_amt FROM orders WHERE DATE(delivery_date) ='$created_date'"); 

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

    
//     function action()
// {

//       // Get the selected month from the form
//     $selected_month = $this->input->post('sales_month'); // format: YYYY-MM

//       // Calculate first and last day of the selected month
//     $first_day_of_month = date('Y-m-01', strtotime($selected_month));
//     $last_day_of_month = date('Y-m-t', strtotime($selected_month));

//     $formatted_month = date('F_Y', strtotime($selected_month)); // Format to "Month_Year"

//     $this->load->model("excel_export_model");
//     $this->load->library("excel");
//     $object = new PHPExcel();

//     $object->setActiveSheetIndex(0);

//     // Header styling
//     $header_styles = array(
//         'font' => array(
//             'name' => 'Times New Roman',
//             'size' => 10,
//             'color' => array('rgb' => 'C00000'), // Dark brown font color
//             'bold' => true
//         ),
//         'alignment' => array(
//             'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
//             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
//         )
//     );

//     // Address styling
//     $address_style = array(
//         'font' => array(
//             'name' => 'Times New Roman',
//             'size' => 9,
//             'color' => array('rgb' => '000000'), // Dark brown font color
//             'italic' => true
//         ),
//         'alignment' => array(
//             'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
//             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
//         )
//     );

//     // Leave 1 empty row at the beginning
//     $excel_row = 1;

//     // Merge and center header cells
//     $object->getActiveSheet()->mergeCells('B' . $excel_row . ':F' . $excel_row);
//     $object->getActiveSheet()->setCellValue('B' . $excel_row, 'ALMA FOODS LLP');
//     $object->getActiveSheet()->getStyle('B' . $excel_row)->applyFromArray($header_styles);

//     // Adjust row after header
//     $excel_row += 2;

//     // Merge and center address cells
//     $object->getActiveSheet()->mergeCells('B' . $excel_row . ':F' . $excel_row);
//     $object->getActiveSheet()->setCellValue('B' . $excel_row, '5 Mandai Link #07-05 Mandai Foodlink Singapore 728654');
//     $object->getActiveSheet()->getStyle('B' . $excel_row)->applyFromArray($address_style);

//     // Adjust row after address
//     $excel_row += 2;

//     // Apply sales summary styling
//     $summary_style = array(
//         'font' => array(
//             'name' => 'Times New Roman',
//             'size' => 16,
//             'color' => array('rgb' => 'C00000'), // Dark brown font color
//             'bold' => true
//         ),
//         'alignment' => array(
//             'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
//             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
//         )
//     );

//     // Apply current month and year styling
//     $month_year_style = array(
//         'font' => array(
//             'name' => 'Times New Roman',
//             'size' => 9,
//             'color' => array('rgb' => 'C00000'), // Dark brown font color
//             'bold' => true
//         ),
//         'alignment' => array(
//             'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
//             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
//         )
//     );

//     // Apply sales summary and current month/year headers
//     $object->getActiveSheet()->mergeCells('B' . $excel_row . ':F' . $excel_row);
//     $object->getActiveSheet()->setCellValue('B' . $excel_row, 'Sales [Item Summary]');
//     $object->getActiveSheet()->getStyle('B' . $excel_row)->applyFromArray($summary_style);

//     $excel_row += 2;
//     $object->getActiveSheet()->mergeCells('B' . $excel_row . ':F' . $excel_row);
//     $current_month = date('F Y'); // Example: June 2024
//     $object->getActiveSheet()->setCellValue('B' . $excel_row, $formatted_month);
//     $object->getActiveSheet()->getStyle('B' . $excel_row)->applyFromArray($month_year_style);

//     // Adjust row after headers
//     $excel_row += 2;

//     // Define table column headers
//     $table_columns = array(
//         "Product ID",
//         "Product Name",
//         "Company Name",
//         "Quantity",
//         "Total Amount"
//     );

//     // Apply style to header cells
//     $header_style = array(
//         'font' => array(
//             'name' => 'Times New Roman',
//             'size' => 10,
//             'color' => array('rgb' => 'FFFFFF'), // White font color
//             'bold' => true
//         ),
//         'fill' => array(
//             'type' => PHPExcel_Style_Fill::FILL_SOLID,
//             'color' => array('rgb' => '000080') // Dark blue fill color
//         ),
//         'borders' => array(
//             'allborders' => array(
//                 'style' => PHPExcel_Style_Border::BORDER_THIN,
//                 'color' => array('rgb' => 'FFFFFF') // White border color for header cells
//             )
//         ),
//         'alignment' => array(
//             'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
//             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
//         )
//     );

//     // Apply white border to all cells
//     $white_border_style = array(
//         'borders' => array(
//             'allborders' => array(
//                 'style' => PHPExcel_Style_Border::BORDER_THIN,
//                 'color' => array('rgb' => 'FFFFFF') // White border color
//             )
//         )
//     );

//     // Apply outer border style
//     $outer_border_style = array(
//         'borders' => array(
//             'outline' => array(
//                 'style' => PHPExcel_Style_Border::BORDER_THIN,
//                 'color' => array('rgb' => '000000') // Black border color for outer border
//             )
//         )
//     );

//     $column = 1; // Start from column B
//     foreach ($table_columns as $field) {
//         $object->getActiveSheet()->setCellValueByColumnAndRow($column, $excel_row, $field);
//         $object->getActiveSheet()->getStyleByColumnAndRow($column, $excel_row)->applyFromArray($header_style);
//         $column++;
//     }

//     $excel_row++;

//     // Fetch data from model
//     $employee_data = $this->excel_export_model->fetch_data_itemsed($first_day_of_month, $last_day_of_month);


//     // Group data by product ID and product name
//     $grouped_data = [];
//     foreach ($employee_data as $row) {
//         $key = $row->prod_id . '|' . $row->product_name;
//         if (!isset($grouped_data[$key])) {
//             $grouped_data[$key] = [];
//         }
//         $grouped_data[$key][] = $row;
//     }

//     // Generate the report
//     foreach ($grouped_data as $key => $rows) {
//         list($prod_id, $product_name) = explode('|', $key);

//         $total_quantity = 0;
//         $total_amount = 0;

//         // Add a heading row for each product
//         $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $prod_id);
//         $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $product_name);
//         $excel_row++;

//         // Group by company name within each product grouping
//         $company_grouped_data = [];
//         foreach ($rows as $row) {
//             if (!isset($company_grouped_data[$row->company_name])) {
//                 $company_grouped_data[$row->company_name] = [
//                     'quantity' => 0,
//                     'amount' => 0
//                 ];
//             }
//             $company_grouped_data[$row->company_name]['quantity'] += $row->qty;
//             $company_grouped_data[$row->company_name]['amount'] += $row->rate * $row->qty;
//         }

//         foreach ($company_grouped_data as $company_name => $data) {
//             $quantity = $data['quantity'];
//             $amount = $data['amount'];

//             // Set cell values and adjust alignment
//             $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $company_name);
//             $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $quantity);
//             $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, '$' . number_format($amount, 2));

//             // Adjust alignment for specific columns
//             $object->getActiveSheet()->getStyleByColumnAndRow(3, $excel_row)
//                 ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//             $object->getActiveSheet()->getStyleByColumnAndRow(4, $excel_row)
//                 ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//             $object->getActiveSheet()->getStyleByColumnAndRow(5, $excel_row)
//                 ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

//             // Increment total quantities and amounts
//             $total_quantity += $quantity;
//             $total_amount += $amount;

//             // Move to the next row
//             $excel_row++;
//         }
//          // Add a total row for each product
//          $excel_row++;
//          $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $product_name . ' Total:');
//          $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $total_quantity);
//          $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, '$' . number_format($total_amount, 2));
 
//        $object->getActiveSheet()->getStyleByColumnAndRow(3, $excel_row)
//                  ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//              $object->getActiveSheet()->getStyleByColumnAndRow(4, $excel_row)
//                  ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//              $object->getActiveSheet()->getStyleByColumnAndRow(5, $excel_row)
//                  ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                 
//          // Add an empty row after the total
//          $excel_row += 2;
//      }
//          // Apply white border to all cells
//          $highest_row = $object->getActiveSheet()->getHighestRow();
//          $highest_column = 'F'; // Since we are starting from column B, highest column would be F
     
//          // Apply white border before the content
//          $object->getActiveSheet()->getStyle("B1:$highest_column$highest_row")->applyFromArray($white_border_style);
     
//          // Add one empty row before the outer border
//          $excel_row++;
     
//          // Apply outer border to the entire content
//          $object->getActiveSheet()->getStyle("B1:$highest_column$highest_row")->applyFromArray($outer_border_style);
     
//          // Auto size columns to fit the content
//          foreach (range('B', $highest_column) as $columnID) {
//              $object->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
//          }
     
//          $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
//          header('Content-Type: application/vnd.ms-excel');
//          header('Content-Disposition: attachment;filename="Sales_summary_' . $formatted_month . '.xls"');
//          header('Cache-Control: max-age=0');
//          $object_writer->save('php://output');

         
//      }
     
     

     function action_category()
     {
         // Get the selected month from the form
         $selected_month = $this->input->post('sales_month'); // format: YYYY-MM
     
         // Calculate first and last day of the selected month
         $first_day_of_month = date('Y-m-01', strtotime($selected_month));
         $last_day_of_month = date('Y-m-t', strtotime($selected_month));
     
         $formatted_month = date('F_Y', strtotime($selected_month)); // Format to "Month_Year"
     
         $this->load->model("excel_export_model");
         $this->load->library("excel");
         $object = new PHPExcel();
     
         $object->setActiveSheetIndex(0);
     
         // Header styling
         $header_styles = array(
             'font' => array(
                 'name' => 'Times New Roman',
                 'size' => 10,
                 'color' => array('rgb' => 'C00000'), // Dark brown font color
                 'bold' => true
             ),
             'alignment' => array(
                 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
             )
         );
     
         // Address styling
         $address_style = array(
             'font' => array(
                 'name' => 'Times New Roman',
                 'size' => 9,
                 'color' => array('rgb' => '000000'), // Dark brown font color
                 'italic' => true
             ),
             'alignment' => array(
                 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
             )
         );
     
         // Leave 1 empty row at the beginning
         $excel_row = 1;
     
         // Merge and center header cells
         $object->getActiveSheet()->mergeCells('B' . $excel_row . ':G' . $excel_row);
         $object->getActiveSheet()->setCellValue('B' . $excel_row, 'ALMA FOODS LLP');
         $object->getActiveSheet()->getStyle('B' . $excel_row)->applyFromArray($header_styles);
     
         // Adjust row after header
         $excel_row += 2;
     
         // Merge and center address cells
         $object->getActiveSheet()->mergeCells('B' . $excel_row . ':G' . $excel_row);
         $object->getActiveSheet()->setCellValue('B' . $excel_row, '5 Mandai Link #07-05 Mandai Foodlink Singapore 728654');
         $object->getActiveSheet()->getStyle('B' . $excel_row)->applyFromArray($address_style);
     
         // Adjust row after address
         $excel_row += 2;
     
         // Apply sales summary styling
         $summary_style = array(
             'font' => array(
                 'name' => 'Times New Roman',
                 'size' => 16,
                 'color' => array('rgb' => 'C00000'), // Dark brown font color
                 'bold' => true
             ),
             'alignment' => array(
                 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
             )
         );
     
         // Apply current month and year styling
         $month_year_style = array(
             'font' => array(
                 'name' => 'Times New Roman',
                 'size' => 9,
                 'color' => array('rgb' => 'C00000'), // Dark brown font color
                 'bold' => true
             ),
             'alignment' => array(
                 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
             )
         );
     
         // Apply sales summary and current month/year headers
         $object->getActiveSheet()->mergeCells('B' . $excel_row . ':G' . $excel_row);
         $object->getActiveSheet()->setCellValue('B' . $excel_row, 'Sales [Item Summary]');
         $object->getActiveSheet()->getStyle('B' . $excel_row)->applyFromArray($summary_style);
     
         $excel_row += 2;
         $object->getActiveSheet()->mergeCells('B' . $excel_row . ':G' . $excel_row);
         $current_month = date('F Y'); // Example: June 2024
         $object->getActiveSheet()->setCellValue('B' . $excel_row, $formatted_month);
         $object->getActiveSheet()->getStyle('B' . $excel_row)->applyFromArray($month_year_style);
     
         // Adjust row after headers
         $excel_row += 2;
     
         // Define table column headers
         $table_columns = array(
             "Product Category",
             "Product ID",        
             "Product Name",
             "Company Name",
             "Quantity",
             "Total Amount"
         );
     
         // Apply style to header cells
         $header_style = array(
             'font' => array(
                 'name' => 'Times New Roman',
                 'size' => 10,
                 'color' => array('rgb' => 'FFFFFF'), // White font color
                 'bold' => true
             ),
             'fill' => array(
                 'type' => PHPExcel_Style_Fill::FILL_SOLID,
                 'color' => array('rgb' => '000080') // Dark blue fill color
             ),
             'borders' => array(
                 'allborders' => array(
                     'style' => PHPExcel_Style_Border::BORDER_THIN,
                     'color' => array('rgb' => 'FFFFFF') // White border color for header cells
                 )
             ),
             'alignment' => array(
                 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
             )
         );
     
         // Apply white border to all cells
         $white_border_style = array(
             'borders' => array(
                 'allborders' => array(
                     'style' => PHPExcel_Style_Border::BORDER_THIN,
                     'color' => array('rgb' => 'FFFFFF') // White border color
                 )
             )
         );
     
         // Apply outer border style
         $outer_border_style = array(
             'borders' => array(
                 'outline' => array(
                     'style' => PHPExcel_Style_Border::BORDER_THIN,
                     'color' => array('rgb' => '000000') // Black border color for outer border
                 )
             )
         );
     
         $column = 1; // Start from column B
         
         foreach ($table_columns as $field) {
             $object->getActiveSheet()->setCellValueByColumnAndRow($column, $excel_row, $field);
             $object->getActiveSheet()->getStyleByColumnAndRow($column, $excel_row)->applyFromArray($header_style);
             $column++;
         }
     
         $excel_row++;
     
         // Fetch data from model
         $employee_data = $this->excel_export_model->fetch_data_category($first_day_of_month, $last_day_of_month);
     
         // Group data by product category, product ID, and product name
         $grouped_data = [];
         foreach ($employee_data as $row) {
             $key = $row->prod_category . '|' . $row->prod_id . '|' . $row->product_name;
             if (!isset($grouped_data[$key])) {
                 $grouped_data[$key] = [];
             }
             $grouped_data[$key][] = $row;
         }
     
         // Initialize variables to track totals
         $current_category = null;
         $total_category_quantity = 0;
         $total_category_amount = 0;
         $first_category = true;
     
         foreach ($grouped_data as $key => $rows) {
             list($prod_category, $prod_id, $product_name) = explode('|', $key);

             $total_quantity = 0;
             $total_amount = 0;
     
             // Check if category has changed
             if ($prod_category !== $current_category) {
                 // Display totals for the previous category, if it exists

                 if (!$first_category) {
                     $excel_row++;
                     $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, 'Total for ' . $current_category . ':');
                     $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $total_category_quantity);
                     $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, '$' . number_format($total_category_amount, 2));
                    
                     $object->getActiveSheet()->getStyle('E' . $excel_row . ':G' . $excel_row)->applyFromArray($header_style);

                     
                     // Adjust alignment
                     $object->getActiveSheet()->getStyleByColumnAndRow(4, $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                     $object->getActiveSheet()->getStyleByColumnAndRow(5, $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                     $object->getActiveSheet()->getStyleByColumnAndRow(6, $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                     $excel_row++;
                     // Reset category totals
                     $total_category_quantity = 0;
                     $total_category_amount = 0;
                 } else {
                     $first_category = false;
                 }
     
                 // Update current category
                 $current_category = $prod_category;
     
                 // Add a heading row for the new category
                 $excel_row++;
                 $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $prod_category);
                 $object->getActiveSheet()->getStyleByColumnAndRow(1, $excel_row)->applyFromArray($header_style);
                 $excel_row++;
             }
     
             // Group by company name within each product grouping
             $company_grouped_data = [];
             foreach ($rows as $row) {
                 if (!isset($company_grouped_data[$row->company_name])) {
                     $company_grouped_data[$row->company_name] = [
                         'quantity' => 0,
                         'amount' => 0
                     ];
                 }
                 $company_grouped_data[$row->company_name]['quantity'] += $row->qty;
                 $company_grouped_data[$row->company_name]['amount'] += $row->rate * $row->qty;
             }
     
             foreach ($company_grouped_data as $company_name => $data) {
                 $quantity = $data['quantity'];
                 $amount = $data['amount'];
     

                 // Set cell values and adjust alignment
                 $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $prod_id);
                 $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $product_name);
                 $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $company_name);
                 $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $quantity);
                 $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, '$' . number_format($amount, 2));
     
                 // Adjust alignment for specific columns
                 $object->getActiveSheet()->getStyleByColumnAndRow(4, $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                 $object->getActiveSheet()->getStyleByColumnAndRow(5, $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                 $object->getActiveSheet()->getStyleByColumnAndRow(6, $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
     
                 // Increment category totals
                 $total_category_quantity += $quantity;
                 $total_category_amount += $amount;
                
                 $total_quantity += $quantity;
                 $total_amount += $amount;
                 // Move to the next row
                 $excel_row++;

                 
             }

             $excel_row++;
             $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $product_name . ' Total:');
             $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $total_quantity);
             $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, '$' . number_format($total_amount, 2));
             
            

           $object->getActiveSheet()->getStyleByColumnAndRow(4, $excel_row)
                     ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                 $object->getActiveSheet()->getStyleByColumnAndRow(5, $excel_row)
                     ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                 $object->getActiveSheet()->getStyleByColumnAndRow(6, $excel_row)
                     ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                     
             // Add an empty row after the total
             $excel_row += 2;
         }
     
         // Display totals for the last category
         if ($current_category !== null) {
             $excel_row++;
             $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, 'Total for ' . $current_category . ':');
             $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $total_category_quantity);
             $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, '$' . number_format($total_category_amount, 2));
     
             $object->getActiveSheet()->getStyle('E' . $excel_row . ':G' . $excel_row)->applyFromArray($header_style);

             // Adjust alignment
             $object->getActiveSheet()->getStyleByColumnAndRow(4, $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
             $object->getActiveSheet()->getStyleByColumnAndRow(5, $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
             $object->getActiveSheet()->getStyleByColumnAndRow(6, $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
             $excel_row++;
            }
     
         // Apply white border to all cells
         $highest_row = $object->getActiveSheet()->getHighestRow();
         $highest_column = 'G'; // Since we are starting from column B, highest column would be G
     
         // Apply white border before the content
         $object->getActiveSheet()->getStyle("B1:$highest_column$highest_row")->applyFromArray($white_border_style);
     
         // Add one empty row before the outer border
         $excel_row++;
     
         // Apply outer border to the entire content
         $object->getActiveSheet()->getStyle("B1:$highest_column$highest_row")->applyFromArray($outer_border_style);
     
         // Auto size columns to fit the content
         foreach (range('B', $highest_column) as $columnID) {
             $object->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
         }
     
         // Output the Excel file
         $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
         header('Content-Type: application/vnd.ms-excel');
         header('Content-Disposition: attachment;filename="Sales_summary_' . $formatted_month . '.xls"');
         header('Cache-Control: max-age=0');
         $object_writer->save('php://output');
     }
     
     
     

     function action_date_range()
     {
         // Get the selected month from the form
         $start_date = $this->input->post('start_date'); // format: YYYY-MM-DD
         $end_date = $this->input->post('end_date'); // format: YYYY-MM-DD
     
         // Format the dates for display
         $formatted_start_date = date('F d, Y', strtotime($start_date)); // Example: June 01, 2024
         $formatted_end_date = date('F d, Y', strtotime($end_date)); // Example: June 30, 2024
     
         $formatted_month = date('F Y', strtotime($start_date)); // Use start_date to maintain consistency

         $this->load->model("excel_export_model");
         $this->load->library("excel");
         $object = new PHPExcel();
     
         $object->setActiveSheetIndex(0);
     
         // Header styling
         $header_styles = array(
             'font' => array(
                 'name' => 'Times New Roman',
                 'size' => 10,
                 'color' => array('rgb' => 'C00000'), // Dark brown font color
                 'bold' => true
             ),
             'alignment' => array(
                 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
             )
         );
     
         // Address styling
         $address_style = array(
             'font' => array(
                 'name' => 'Times New Roman',
                 'size' => 9,
                 'color' => array('rgb' => '000000'), // Dark brown font color
                 'italic' => true
             ),
             'alignment' => array(
                 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
             )
         );
     
         // Leave 1 empty row at the beginning
         $excel_row = 1;
     
         // Merge and center header cells
         $object->getActiveSheet()->mergeCells('B' . $excel_row . ':G' . $excel_row);
         $object->getActiveSheet()->setCellValue('B' . $excel_row, 'ALMA FOODS LLP');
         $object->getActiveSheet()->getStyle('B' . $excel_row)->applyFromArray($header_styles);
     
         // Adjust row after header
         $excel_row += 2;
     
         // Merge and center address cells
         $object->getActiveSheet()->mergeCells('B' . $excel_row . ':G' . $excel_row);
         $object->getActiveSheet()->setCellValue('B' . $excel_row, '5 Mandai Link #07-05 Mandai Foodlink Singapore 728654');
         $object->getActiveSheet()->getStyle('B' . $excel_row)->applyFromArray($address_style);
     
         // Adjust row after address
         $excel_row += 2;
     
         // Apply sales summary styling
         $summary_style = array(
             'font' => array(
                 'name' => 'Times New Roman',
                 'size' => 16,
                 'color' => array('rgb' => 'C00000'), // Dark brown font color
                 'bold' => true
             ),
             'alignment' => array(
                 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
             )
         );
     
         // Apply current month and year styling
         $month_year_style = array(
             'font' => array(
                 'name' => 'Times New Roman',
                 'size' => 9,
                 'color' => array('rgb' => 'C00000'), // Dark brown font color
                 'bold' => true
             ),
             'alignment' => array(
                 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
             )
         );
     
         // Apply sales summary and current month/year headers
         $object->getActiveSheet()->mergeCells('B' . $excel_row . ':G' . $excel_row);
         $object->getActiveSheet()->setCellValue('B' . $excel_row, 'Sales [Item Summary]');
         $object->getActiveSheet()->getStyle('B' . $excel_row)->applyFromArray($summary_style);
     
         $excel_row += 2;
         $object->getActiveSheet()->mergeCells('B' . $excel_row . ':G' . $excel_row);
         $current_month = date('F Y'); // Example: June 2024
         $object->getActiveSheet()->setCellValue('B' . $excel_row, $formatted_month);
         $object->getActiveSheet()->getStyle('B' . $excel_row)->applyFromArray($month_year_style);
     
         // Adjust row after headers
         $excel_row += 2;
     
         // Define table column headers
         $table_columns = array(
             "Product Category",
             "Product ID",        
             "Product Name",
             "Company Name",
             "Quantity",
             "Total Amount"
         );
     
         // Apply style to header cells
         $header_style = array(
             'font' => array(
                 'name' => 'Times New Roman',
                 'size' => 10,
                 'color' => array('rgb' => 'FFFFFF'), // White font color
                 'bold' => true
             ),
             'fill' => array(
                 'type' => PHPExcel_Style_Fill::FILL_SOLID,
                 'color' => array('rgb' => '000080') // Dark blue fill color
             ),
             'borders' => array(
                 'allborders' => array(
                     'style' => PHPExcel_Style_Border::BORDER_THIN,
                     'color' => array('rgb' => 'FFFFFF') // White border color for header cells
                 )
             ),
             'alignment' => array(
                 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
             )
         );
     
         // Apply white border to all cells
         $white_border_style = array(
             'borders' => array(
                 'allborders' => array(
                     'style' => PHPExcel_Style_Border::BORDER_THIN,
                     'color' => array('rgb' => 'FFFFFF') // White border color
                 )
             )
         );
     
         // Apply outer border style
         $outer_border_style = array(
             'borders' => array(
                 'outline' => array(
                     'style' => PHPExcel_Style_Border::BORDER_THIN,
                     'color' => array('rgb' => '000000') // Black border color for outer border
                 )
             )
         );
     
         $column = 1; // Start from column B
         
         foreach ($table_columns as $field) {
             $object->getActiveSheet()->setCellValueByColumnAndRow($column, $excel_row, $field);
             $object->getActiveSheet()->getStyleByColumnAndRow($column, $excel_row)->applyFromArray($header_style);
             $column++;
         }
     
         $excel_row++;
     
         // Fetch data from model
         $employee_data = $this->excel_export_model->fetch_date_range_category($start_date, $end_date);
     
         // Group data by product category, product ID, and product name
         $grouped_data = [];
         foreach ($employee_data as $row) {
             $key = $row->prod_category . '|' . $row->prod_id . '|' . $row->product_name;
             if (!isset($grouped_data[$key])) {
                 $grouped_data[$key] = [];
             }
             $grouped_data[$key][] = $row;
         }
     
         // Initialize variables to track totals
         $current_category = null;
         $total_category_quantity = 0;
         $total_category_amount = 0;
         $first_category = true;
     
         foreach ($grouped_data as $key => $rows) {
             list($prod_category, $prod_id, $product_name) = explode('|', $key);

             $total_quantity = 0;
             $total_amount = 0;
     
             // Check if category has changed
             if ($prod_category !== $current_category) {
                 // Display totals for the previous category, if it exists

                 if (!$first_category) {
                     $excel_row++;
                     $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, 'Total for ' . $current_category . ':');
                     $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $total_category_quantity);
                     $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, '$' . number_format($total_category_amount, 2));
                    
                     $object->getActiveSheet()->getStyle('E' . $excel_row . ':G' . $excel_row)->applyFromArray($header_style);

                     
                     // Adjust alignment
                     $object->getActiveSheet()->getStyleByColumnAndRow(4, $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                     $object->getActiveSheet()->getStyleByColumnAndRow(5, $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                     $object->getActiveSheet()->getStyleByColumnAndRow(6, $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                     $excel_row++;
                     // Reset category totals
                     $total_category_quantity = 0;
                     $total_category_amount = 0;
                 } else {
                     $first_category = false;
                 }
     
                 // Update current category
                 $current_category = $prod_category;
     
                 // Add a heading row for the new category
                 $excel_row++;
                 $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $prod_category);
                 $object->getActiveSheet()->getStyleByColumnAndRow(1, $excel_row)->applyFromArray($header_style);
                 $excel_row++;
             }
     
             // Group by company name within each product grouping
             $company_grouped_data = [];
             foreach ($rows as $row) {
                 if (!isset($company_grouped_data[$row->company_name])) {
                     $company_grouped_data[$row->company_name] = [
                         'quantity' => 0,
                         'amount' => 0
                     ];
                 }
                 $company_grouped_data[$row->company_name]['quantity'] += $row->qty;
                 $company_grouped_data[$row->company_name]['amount'] += $row->rate * $row->qty;
             }
     
             foreach ($company_grouped_data as $company_name => $data) {
                 $quantity = $data['quantity'];
                 $amount = $data['amount'];
     

                 // Set cell values and adjust alignment
                 $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $prod_id);
                 $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $product_name);
                 $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $company_name);
                 $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $quantity);
                 $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, '$' . number_format($amount, 2));
     
                 // Adjust alignment for specific columns
                 $object->getActiveSheet()->getStyleByColumnAndRow(4, $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                 $object->getActiveSheet()->getStyleByColumnAndRow(5, $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                 $object->getActiveSheet()->getStyleByColumnAndRow(6, $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
     
                 // Increment category totals
                 $total_category_quantity += $quantity;
                 $total_category_amount += $amount;
                
                 $total_quantity += $quantity;
                 $total_amount += $amount;
                 // Move to the next row
                 $excel_row++;

                 
             }

             $excel_row++;
             $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $product_name . ' Total:');
             $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $total_quantity);
             $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, '$' . number_format($total_amount, 2));
             
            

           $object->getActiveSheet()->getStyleByColumnAndRow(4, $excel_row)
                     ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                 $object->getActiveSheet()->getStyleByColumnAndRow(5, $excel_row)
                     ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                 $object->getActiveSheet()->getStyleByColumnAndRow(6, $excel_row)
                     ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                     
             // Add an empty row after the total
             $excel_row += 2;
         }
     
         // Display totals for the last category
         if ($current_category !== null) {
             $excel_row++;
             $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, 'Total for ' . $current_category . ':');
             $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $total_category_quantity);
             $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, '$' . number_format($total_category_amount, 2));
     
             $object->getActiveSheet()->getStyle('E' . $excel_row . ':G' . $excel_row)->applyFromArray($header_style);

             // Adjust alignment
             $object->getActiveSheet()->getStyleByColumnAndRow(4, $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
             $object->getActiveSheet()->getStyleByColumnAndRow(5, $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
             $object->getActiveSheet()->getStyleByColumnAndRow(6, $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
             $excel_row++;
            }
     
         // Apply white border to all cells
         $highest_row = $object->getActiveSheet()->getHighestRow();
         $highest_column = 'G'; // Since we are starting from column B, highest column would be G
     
         // Apply white border before the content
         $object->getActiveSheet()->getStyle("B1:$highest_column$highest_row")->applyFromArray($white_border_style);
     
         // Add one empty row before the outer border
         $excel_row++;
     
         // Apply outer border to the entire content
         $object->getActiveSheet()->getStyle("B1:$highest_column$highest_row")->applyFromArray($outer_border_style);
     
         // Auto size columns to fit the content
         foreach (range('B', $highest_column) as $columnID) {
             $object->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
         }
     
         // Output the Excel file
         $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
         header('Content-Type: application/vnd.ms-excel');
         header('Content-Disposition: attachment;filename="Sales_summary.xls"');
         header('Cache-Control: max-age=0');
         $object_writer->save('php://output');
     }
     public function action_date_range_pdf()
     {
         $start_date = $this->input->post('start_date'); // YYYY-MM-DD
         $end_date = $this->input->post('end_date'); // YYYY-MM-DD
     
         $formatted_start_date = date('F d, Y', strtotime($start_date));
         $formatted_end_date = date('F d, Y', strtotime($end_date));
         $formatted_month = date('F Y', strtotime($start_date));
     
         $this->load->model("excel_export_model");
     
         $employee_data = $this->excel_export_model->fetch_date_range_category($start_date, $end_date);
     
         // Grouping data by category, product ID, and product name
         $grouped_data = [];
         foreach ($employee_data as $row) {
             $key = $row->prod_category . '|' . $row->prod_id . '|' . $row->product_name;
             if (!isset($grouped_data[$key])) {
                 $grouped_data[$key] = ['quantity' => 0, 'amount' => 0];
             }
             $grouped_data[$key]['quantity'] += $row->qty;
             $grouped_data[$key]['amount'] += $row->rate * $row->qty;
         }
     
         // Start HTML for PDF
         $html = '<style>
             table { border-collapse: collapse; width: 100%; font-size: 10pt; }
             th, td { border: 1px solid black; padding: 5px; text-align: center; }
             th { background-color: #000080; color: white; }
             .category-header { background-color: #ddd; font-weight: bold; text-align: left; padding: 5px; }
             .total-row { font-weight: bold; background-color: #f0f0f0; }
         </style>';
     
         $html .= '<h2 style="text-align: center;">Sales [Item Summary]</h2>
            <h3 style="text-align: center;>Weekly Report</h3>
         <h4 style="text-align: center;">' . $formatted_start_date . ' to ' .$formatted_end_date. '</h4>';
 
         $html .= '<table>
             <tr>
                 <th>Product Category</th>
                 <th>Product ID</th>
                 <th>Product Name</th>
                 <th>Quantity</th>
                 <th>Total Amount</th>
             </tr>';
     
         $current_category = null;
         $total_category_quantity = 0;
         $total_category_amount = 0;
         $first_category = true;
     
         foreach ($grouped_data as $key => $data) {
             list($prod_category, $prod_id, $product_name) = explode('|', $key);
     
             // Check if category has changed
             if ($prod_category !== $current_category) {
                 // Display totals for previous category
                 if (!$first_category) {
                     $html .= '<tr class="total-row">
                         <td colspan="3">Total for ' . $current_category . ':</td>
                         <td>' . $total_category_quantity . '</td>
                         <td>$' . number_format($total_category_amount, 2) . '</td>
                     </tr>';
                 } else {
                     $first_category = false;
                 }
     
                 // New category header
                 $html .= '<tr><td colspan="5" class="category-header">' . $prod_category . '</td></tr>';
     
                 // Reset category totals
                 $total_category_quantity = 0;
                 $total_category_amount = 0;
                 $current_category = $prod_category;
             }
     
             // Display product data
             $html .= '<tr>
                 <td>' . $prod_category . '</td>
                 <td>' . $prod_id . '</td>
                 <td>' . $product_name . '</td>
                 <td>' . $data['quantity'] . '</td>
                 <td>$' . number_format($data['amount'], 2) . '</td>
             </tr>';
     
             // Increment category totals
             $total_category_quantity += $data['quantity'];
             $total_category_amount += $data['amount'];
         }
     
         // Final total row for the last category
         $html .= '<tr class="total-row">
             <td colspan="3">Total for ' . $current_category . ':</td>
             <td>' . $total_category_quantity . '</td>
             <td>$' . number_format($total_category_amount, 2) . '</td>
         </tr>';
     
         $html .= '</table>';
     
         $dompdf = new Dompdf();
         $dompdf->loadHtml($html);
         $dompdf->setPaper(array(0, 0, 840, 1188), 'portrait');    
         $dompdf->render();
     
         // Output PDF
         $filename = 'WeeklyReport_' . date('d-M-Y', strtotime($start_date)) . '_to_' . date('d-M-Y', strtotime($end_date)) . '.pdf';
         $dompdf->stream($filename, ['Attachment' => 1]); // Download file
         
     }

     public function production_report_weekly()
     {
         $start_date = $this->input->post('start_date'); // YYYY-MM-DD
         $end_date = $this->input->post('end_date'); // YYYY-MM-DD
     
         $formatted_start_date = date('F d, Y', strtotime($start_date));
         $formatted_end_date = date('F d, Y', strtotime($end_date));
     
         $this->load->model("excel_export_model");
         $employee_data = $this->excel_export_model->fetch_date_range_category($start_date, $end_date);
     
         // Grouping data by category, product ID, and product name
         $grouped_data = [];
         foreach ($employee_data as $row) {
             $key = $row->prod_category . '|' . $row->prod_id . '|' . $row->product_name;
             if (!isset($grouped_data[$key])) {
                 $grouped_data[$key] = ['quantity' => 0];
             }
             $grouped_data[$key]['quantity'] += $row->qty;
         }
     
         // Start HTML for PDF
         $html = '<style>
             table { border-collapse: collapse; width: 100%; font-size: 10pt; }
             th, td { border: 1px solid black; padding: 5px; text-align: center; }
             th { background-color: #000080; color: white; }
             .category-header { background-color: #ddd; font-weight: bold; text-align: left; padding: 5px; }
             .total-row { font-weight: bold; background-color: #f0f0f0; }
         </style>';
     
         $html .= '<h2 style="text-align: center;">Sales [Item Summary]</h2>
             <h3 style="text-align: center;">Weekly Report</h3>
             <h4 style="text-align: center;">' . $formatted_start_date . ' to ' . $formatted_end_date . '</h4>';
     
         $html .= '<table>
             <tr>
                 <th>Product Category</th>
                 <th>Product ID</th>
                 <th>Product Name</th>
                 <th>Quantity</th>
             </tr>';
     
         $current_category = null;
         $total_category_quantity = 0;
         $first_category = true;
     
         foreach ($grouped_data as $key => $data) {
             list($prod_category, $prod_id, $product_name) = explode('|', $key);
     
             // Check if category has changed
             if ($prod_category !== $current_category) {
                 // Display totals for previous category
                 if (!$first_category) {
                     $html .= '<tr class="total-row">
                         <td colspan="3">Total for ' . $current_category . ':</td>
                         <td>' . $total_category_quantity . '</td>
                     </tr>';
                 } else {
                     $first_category = false;
                 }
     
                 // New category header
                 $html .= '<tr><td colspan="4" class="category-header">' . $prod_category . '</td></tr>';
     
                 // Reset category totals
                 $total_category_quantity = 0;
                 $current_category = $prod_category;
             }
     
             // Display product data
             $html .= '<tr>
                 <td>' . $prod_category . '</td>
                 <td>' . $prod_id . '</td>
                 <td>' . $product_name . '</td>
                 <td>' . $data['quantity'] . '</td>
             </tr>';
     
             // Increment category totals
             $total_category_quantity += $data['quantity'];
         }
     
         // Final total row for the last category
         $html .= '<tr class="total-row">
             <td colspan="3">Total for ' . $current_category . ':</td>
             <td>' . $total_category_quantity . '</td>
         </tr>';
     
         $html .= '</table>';
     
         $dompdf = new Dompdf();
         $dompdf->loadHtml($html);
         $dompdf->setPaper(array(0, 0, 840, 1188), 'portrait');    
         $dompdf->render();
     
         // Output PDF
         $filename = 'WeeklyReport_' . date('d-M-Y', strtotime($start_date)) . '_to_' . date('d-M-Y', strtotime($end_date)) . '.pdf';
         $dompdf->stream($filename, ['Attachment' => 1]); // Download file
     }
     
    }


?>