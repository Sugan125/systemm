<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Excel_export extends CI_Controller {
 
 function index()
 {
  $this->load->model("excel_export_model");
  $data["employee_data"] = $this->excel_export_model->fetch_data();
 // echo '<pre>';
  //print_r($data["employee_data"]);
  $this->load->view("excel_export_view", $data);
 }
 function action()
 {
     $this->load->model("excel_export_model");
     $this->load->library("excel");
     $object = new PHPExcel();
 
     $object->setActiveSheetIndex(0);
 
     $table_columns = array(
         "Co./Last Name", 
         "Addr 1 - Line 1", 
         "- Line 2", 
         "- Line 3", 
         "- Line 4", 
         "Invoice #", 
         "Date", 
         "Customer PO", 
         "Item Number", 
         "Quantity", 
         "Description", 
         "Price", 
         "Inc-Tax Price", 
         "Discount", 
         "Total", 
         "Inc-Tax Total", 
         "Job", 
         "Comment", 
         "Journal Memo", 
         "Salesperson Last Name", 
         "Shipping Date", 
         "Tax Code", 
         "GST Amount", 
         "Terms - Payment is Due", 
         "- Balance Due Days", 
         "Record ID"
     );
 
     $column = 0;
 
     foreach($table_columns as $field)
     {
         $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
         $column++;
     }
 
     $employee_data = $this->excel_export_model->fetch_data();
 
     $excel_row = 2;
     $prev_name = '__INITIAL_VALUE__'; // Initialize with a unique value
 
     foreach($employee_data as $row)
     {
         
      $schedule_date = $row->date_time;
      $date = date('d-m-Y', $schedule_date);

      $schedule_timestamp = $row->date_time;
      $shipping_date = date('d-m-Y', strtotime('+3 days', $schedule_timestamp));
      // Check if the current name is different from the previous name
         if ($row->name != $prev_name && $prev_name != '__INITIAL_VALUE__') {
             // If different and not the initial value, insert a blank row
             $excel_row++;
         }
 
         // Populate Excel row with data
         // Assuming $object is your PHPExcel object
$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->name);
$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->address);
$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->line2);
$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->line3);
$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->line4);
$object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->bill_no);
$object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $date);
$object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $row->prod_id);
$object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $row->qty);
$object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $row->product_name);
$object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $row->rate);
$object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $row->amount);
$object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, 'Sale;'.$row->name);
$object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $shipping_date);
$object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
$object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $row->gst_amount);
$object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $row->id);
         // Update the previous name to the current name for the next iteration
         $prev_name = $row->name;
 
         // Increment the Excel row counter
         $excel_row++;
     }
 
     $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
     header('Content-Type: application/vnd.ms-excel');
     header('Content-Disposition: attachment;filename="SALE-ITEM.xls"');
     $object_writer->save('php://output');
 }
 function actiondate()
{
    $sales_date = $this->input->post('sales_date');
    $this->load->model("excel_export_model");
    $this->load->library("excel");
    $object = new PHPExcel();
    $object->setActiveSheetIndex(0);
    $table_columns = array(
        "Co./Last Name", 
        "Addr 1 - Line 1", 
        "- Line 2", 
        "- Line 3", 
        "- Line 4", 
        "Invoice #", 
        "Date", 
        "Customer PO", 
        "Item Number", 
        "Quantity", 
        "Description", 
        "Price", 
        "Inc-Tax Price", 
        "Discount", 
        "Total", 
        "Inc-Tax Total", 
        "Job", 
        "Comment", 
        "Journal Memo", 
        "Salesperson Last Name", 
        "Shipping Date", 
        "Tax Code", 
        "GST Amount", 
        "Terms - Payment is Due", 
        "- Balance Due Days", 
        "Record ID"
    );
    $column = 0;
    foreach($table_columns as $field) {
        $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
        $column++;
    }
    $employee_data = $this->excel_export_model->fetch_data_by_date($sales_date);
    $excel_row = count($employee_data) > 0 ? 2 : 1; // Set initial row based on presence of data rows
    $prev_name = null; // Variable to store the previous name
    foreach($employee_data as $row) {
        $schedule_date = $row->date_time;
        $date = date('d-m-Y', $schedule_date);

        $schedule_timestamp = $row->date_time;
        $shipping_date = date('d-m-Y', strtotime('+3 days', $schedule_timestamp));
        
        // Check if the current name is different from the previous name
        if ($row->name != $prev_name) {
            // If different and not the first row, insert a blank row
            if ($prev_name !== null) {
                $excel_row++;
            }
        }
        // Populate Excel row with data
        // Assuming $object is your PHPExcel object
        $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->name);
        $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->address);
        $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->line2);
        $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->line3);
        $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->line4);
        $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->bill_no);
        $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $date);
        $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $row->prod_id);
        $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $row->qty);
        $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $row->product_name);
        $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $row->rate);
        $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $row->amount);
        $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, 'Sale;'.$row->name);
        $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $shipping_date);
        $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
        $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $row->gst_amount);
        $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $row->id);
        // Update the previous name to the current name for the next iteration
        $prev_name = $row->name;
        // Increment the Excel row counter
        $excel_row++;
    }
    $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="SALE-ITEM.xls"');
    $object_writer->save('php://output');
}

 

 
 
}