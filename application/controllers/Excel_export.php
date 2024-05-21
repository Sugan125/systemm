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
         "",
         "Service Charge",
         "Inc-Tax Price",
         "- % Discount",
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
 
     foreach ($table_columns as $field) {
         $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
         $column++;
     }
 
     $employee_data = $this->excel_export_model->fetch_data();
     $excel_row = 2;
     $prev_name = '__INITIAL_VALUE__'; // Initialize with a unique value
     
     // Initialize variables for calculating average delivery charge
     $total_delivery_charge = 0;
     $delivery_charge_count = 0;
 
     foreach ($employee_data as $row) {
         $schedule_date = strtotime($row->created_date);
         $date = date('d-m-Y', $schedule_date);
 
         if (is_numeric($row->delivery_date)) {
            // Assume it is a Unix timestamp
            $delivery_date = date('d-m-Y', $row->delivery_date);
        } else {
            // Assume it is a date string
            $timestamp = strtotime($row->delivery_date);
            if ($timestamp === false) {
                // Handle the error if the date string is not well-formed
                $delivery_date = 'Invalid date';
            } else {
                $delivery_date = date('d-m-Y', $timestamp);
            }
        }
         $net_total = $row->amount + $row->gst_amount;
 
         if ($row->name != $prev_name && $prev_name != '__INITIAL_VALUE__') {
             if ($delivery_charge_count > 0) {
                 $object->getActiveSheet()->insertNewRowBefore($excel_row, 1);
                 $excel_row++; // Increment the row counter
 
                 $average_delivery_charge = $total_delivery_charge / $delivery_charge_count;
                 $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, "Delivery charge:"); // Delivery Charge
                 $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, $average_delivery_charge); 
                 $excel_row++; // Increment the row counter
                 
                 // Reset variables for the next company
                 $total_delivery_charge = 0;
                 $delivery_charge_count = 0;
                 
                 // Add an empty row
                 $object->getActiveSheet()->insertNewRowBefore($excel_row, 1);
                 $excel_row++; // Increment the row counter
             }
         }
 
         // Populate Excel row with line item data
         $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->company_name);
         $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->delivery_address);
         $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->delivery_address_line2);
         $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->delivery_address_line3);
         $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->delivery_address_line4);
         $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->bill_no);
         $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $date);
         $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $row->prod_id);
         $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $row->qty);
         $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $row->product_name);
         $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $row->rate);
         $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, $row->service_charge);
         $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $row->gst_amount);
         $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, '0');
         $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, $row->amount);
         $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $net_total);
         $object->getActiveSheet()->setCellValueByColumnAndRow(18, $excel_row, 'Sale;'.$row->sales_person);
         $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $delivery_date);
         $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, 'SR9');
         $object->getActiveSheet()->setCellValueByColumnAndRow(24, $excel_row, $row->gst_amount);
         $object->getActiveSheet()->setCellValueByColumnAndRow(27, $excel_row, $row->record_id);
 
         // Update the total delivery charge for the company
         $total_delivery_charge += $row->delivery_charge;
         $delivery_charge_count++;
 
         $prev_name = $row->name;
 
         // Increment the Excel row counter
         $excel_row++;
     }
 
     // Check for any remaining delivery charges
     if ($delivery_charge_count > 0) {
         $object->getActiveSheet()->insertNewRowBefore($excel_row, 1);
         $excel_row++; // Increment the row counter
 
         $average_delivery_charge = $total_delivery_charge / $delivery_charge_count;
 
         if($average_delivery_charge > 0){
         $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, 'Delivery charge'); // Delivery Charge
         $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, $average_delivery_charge); // Delivery Charge
         }
         // Add an empty row
         $object->getActiveSheet()->insertNewRowBefore($excel_row, 1);
         $excel_row++; // Increment the row counter
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
         "",
         "Service Charge",
         "Inc-Tax Price",
         "- % Discount",
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
 
     foreach ($table_columns as $field) {
         $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
         $column++;
     }
 
     $employee_data = $this->excel_export_model->fetch_data_by_date($sales_date);
     $excel_row = 2;
     $prev_name = '__INITIAL_VALUE__'; // Initialize with a unique value
     
     // Initialize variables for calculating average delivery charge
     $total_delivery_charge = 0;
     $delivery_charge_count = 0;
 
     foreach ($employee_data as $row) {
         $schedule_date = strtotime($row->created_date);
         $date = date('d-m-Y', $schedule_date);
 
         if (is_numeric($row->delivery_date)) {
            // Assume it is a Unix timestamp
            $delivery_date = date('d-m-Y', $row->delivery_date);
        } else {
            // Assume it is a date string
            $timestamp = strtotime($row->delivery_date);
            if ($timestamp === false) {
                // Handle the error if the date string is not well-formed
                $delivery_date = 'Invalid date';
            } else {
                $delivery_date = date('d-m-Y', $timestamp);
            }
        }
        
         $net_total = $row->amount + $row->gst_amount;
 
         if ($row->name != $prev_name && $prev_name != '__INITIAL_VALUE__') {
             if ($delivery_charge_count > 0) {
                 $object->getActiveSheet()->insertNewRowBefore($excel_row, 1);
                 $excel_row++; // Increment the row counter
 
                 $average_delivery_charge = $total_delivery_charge / $delivery_charge_count;
                 $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, "Delivery charge:"); // Delivery Charge
                 $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, $average_delivery_charge); 
                 $excel_row++; // Increment the row counter
                 
                 // Reset variables for the next company
                 $total_delivery_charge = 0;
                 $delivery_charge_count = 0;
                 
                 // Add an empty row
                 $object->getActiveSheet()->insertNewRowBefore($excel_row, 1);
                 $excel_row++; // Increment the row counter
             }
         }
 
         // Populate Excel row with line item data
         $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->company_name);
         $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->delivery_address);
         $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->delivery_address_line2);
         $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->delivery_address_line3);
         $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->delivery_address_line4);
         $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->bill_no);
         $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $date);
         $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $row->prod_id);
         $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $row->qty);
         $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $row->product_name);
         $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $row->rate);
         $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, $row->service_charge);
         $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $row->gst_amount);
         $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, '0');
         $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, $row->amount);
         $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $net_total);
         $object->getActiveSheet()->setCellValueByColumnAndRow(18, $excel_row, 'Sale;'.$row->sales_person);
         $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $delivery_date);
         $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, 'SR9');
         $object->getActiveSheet()->setCellValueByColumnAndRow(24, $excel_row, $row->gst_amount);
         $object->getActiveSheet()->setCellValueByColumnAndRow(27, $excel_row, $row->record_id);
 
         // Update the total delivery charge for the company
         $total_delivery_charge += $row->delivery_charge;
         $delivery_charge_count++;
 
         $prev_name = $row->name;
 
         // Increment the Excel row counter
         $excel_row++;
     }
 
     // Check for any remaining delivery charges
     if ($delivery_charge_count > 0) {
         $object->getActiveSheet()->insertNewRowBefore($excel_row, 1);
         $excel_row++; // Increment the row counter
 
         $average_delivery_charge = $total_delivery_charge / $delivery_charge_count;
 
         if($average_delivery_charge > 0){
         $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, 'Delivery charge'); // Delivery Charge
         $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, $average_delivery_charge); // Delivery Charge
         }
         // Add an empty row
         $object->getActiveSheet()->insertNewRowBefore($excel_row, 1);
         $excel_row++; // Increment the row counter
     }
 
     $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
     header('Content-Type: application/vnd.ms-excel');
     header('Content-Disposition: attachment;filename="SALE-ITEM.xls"');
     $object_writer->save('php://output');
}

 

 
 
}
