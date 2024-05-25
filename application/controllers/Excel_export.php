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

    // Define table columns
    $table_columns = array(
        "Co./Last Name", "Addr 1 - Line 1", "- Line 2", "- Line 3", "- Line 4", 
        "Invoice #", "Date", "Customer PO", "Item Number", "Quantity", "Description", 
        "Price", "Inc-Tax Price", "- % Discount", "Total", "Inc-Tax Total", 
        "Job", "Comment", "Journal Memo", "Salesperson Last Name", "Shipping Date", 
        "Tax Code", "GST Amount", "Terms - Payment is Due", "- Balance Due Days", 
        "Record ID"
    );

    // Set table headers
    $column = 0;
    foreach ($table_columns as $field) {
        $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
        $column++;
    }

    // Fetch employee data from model
    $employee_data = $this->excel_export_model->fetch_data();
    $excel_row = 2;
    $prev_name = '__INITIAL_VALUE__'; // Initialize with a unique value
    
    // Initialize variables for calculating average delivery charge
    $total_delivery_charge = 0;
    $delivery_charge_count = 0;

    // Initialize variables to hold company details for delivery charge row
    $company_details = [];

    foreach ($employee_data as $row) {
        $schedule_date = strtotime($row->created_date);
        $date = date('d-m-Y', $schedule_date);
        $rate = $row->rate;
        $inc_tax = $rate * 1.09;

        $delivery_date = is_numeric($row->delivery_date) 
            ? date('d-m-Y', $row->delivery_date) 
            : (strtotime($row->delivery_date) !== false 
                ? date('d-m-Y', strtotime($row->delivery_date)) 
                : 'Invalid date');

        $net_total = $row->amount + $row->gst_amount;
        $slice = $row->slice_type;
        $service_charge = $row->service_charge;

        // Check if company name changed and process previous company's delivery charges
        if ($row->name != $prev_name && $prev_name != '__INITIAL_VALUE__') {
            if ($delivery_charge_count > 0) {
                insert_delivery_charge_row($object, $excel_row, $company_details, $total_delivery_charge, $delivery_charge_count);
                $excel_row++; // Increment row counter
                $total_delivery_charge = 0;
                $delivery_charge_count = 0;
            }
        }

        // Populate Excel row with line item data
        populate_excel_row($object, $excel_row, $row, $date, $inc_tax, $net_total, $delivery_date);
        $excel_row++; // Increment row counter

        // Update the total delivery charge for the company
        $total_delivery_charge += $row->delivery_charge;
        $delivery_charge_count++;

        // Store company details for the delivery charge row
        $company_details = [
            'company_name' => $row->company_name,
            'delivery_address' => $row->delivery_address,
            'delivery_address_line2' => $row->delivery_address_line2,
            'delivery_address_line3' => $row->delivery_address_line3,
            'delivery_address_line4' => $row->delivery_address_line4,
            'sales_person' => $row->sales_person,
            'driver_memo' => $row->driver_memo,
            'payment_terms' => $row->payment_terms,
            'record_id' => $row->record_id,
            'bill_no' => $row->bill_no,
            'service_charge' => $service_charge,
            'slice' => $slice
        ];

        $prev_name = $row->name;

        if ($slice == '12mm' || $slice == '20mm') {
            // Add a new row for the slice
            $object->getActiveSheet()->insertNewRowBefore($excel_row, 1);
    
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $company_name);
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $delivery_address);
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $delivery_address_line2);
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $delivery_address_line3);
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $delivery_address_line4);
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->bill_no);
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $date);
            $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $slice == '12mm' ? 'SL012' : 'SL020');
            $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, 1);
            $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $slice == '12mm' ? "Slice 12mm Service Charge" : "Slice 20mm Service Charge");
            $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $service_charge);
            $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $service_charge_tax);
            $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, '0');
            $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $service_charge);
            $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $aver_service_charge);
            $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, 'Sale;'.$sales_person);
            $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $driver_memo);
            $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $sales_person);
            $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $delivery_date);
            $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
            $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $ser_gst);
            $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $payment_terms);
            $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $record_id);
    
            $excel_row++; // Increment the row counter
        }
    }

    // Process remaining delivery charges for the last company
    if ($delivery_charge_count > 0) {
        insert_delivery_charge_row($object, $excel_row, $company_details, $total_delivery_charge, $delivery_charge_count);
    }

    // Set filename and save the Excel file
    $filename = "EmployeeData_" . date('YmdHis') . ".xlsx";
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    $writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
    $writer->save('php://output');
}


// Function to populate Excel row with line item data
function populate_excel_row($object, $excel_row, $row, $date, $inc_tax, $net_total, $delivery_date) {
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
    $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $inc_tax);
    $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, '0');
    $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $row->amount);
    $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $net_total);
    $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, 'Sale;' . $row->sales_person);
    $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $row->driver_memo);
    $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $row->sales_person);
    $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $delivery_date);
    $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
    $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $row->gst_amount);
    $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $row->payment_terms);
    $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $row->record_id);
}

// Function to insert delivery charge row
function insert_delivery_charge_row($object, $excel_row, $company_details, $total_delivery_charge, $delivery_charge_count) {
    $average_delivery_charge = $total_delivery_charge / $delivery_charge_count;
    $del_inc_tax = $average_delivery_charge * 1.09;
    $del_gst = $average_delivery_charge * 0.09;

    if ($average_delivery_charge > 0) {
        $object->getActiveSheet()->insertNewRowBefore($excel_row, 1);
        $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $company_details['company_name']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $company_details['delivery_address']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $company_details['delivery_address_line2']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $company_details['delivery_address_line3']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $company_details['delivery_address_line4']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $company_details['bill_no']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $company_details['date']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, 'DS020');
        $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, 1);
        $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, "Delivery Service");
        $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $average_delivery_charge);
        $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $del_inc_tax);
        $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, '0');
        $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $average_delivery_charge);
        $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $average_delivery_charge + $del_gst);
        $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, 'Sale;' . $company_details['sales_person']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $company_details['driver_memo']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $company_details['sales_person']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $company_details['delivery_date']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
        $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $del_gst);
        $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $company_details['payment_terms']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $company_details['record_id']);
    }
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

    // Initialize variables to hold company details for delivery charge row
    $company_name = '';
    $delivery_address = '';
    $delivery_address_line2 = '';
    $delivery_address_line3 = '';
    $delivery_address_line4 = '';
    $date = '';
    $sales_person = '';
    $payment_terms = '';
    $record_id = '';

    foreach ($employee_data as $row) {

//    echo '<pre>';

//       print_r($row);
        
        $schedule_date = strtotime($row->created_date);
        $date = date('d-m-Y', $schedule_date);
        $rate = $row->rate;

        $inc_tax = $rate * 1.09;

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

        $slice = $row->slice_type;

        $service_charge = $row->service_charge;

        if ($row->name != $prev_name && $prev_name != '__INITIAL_VALUE__') {
            if ($delivery_charge_count > 0) {
                $object->getActiveSheet()->insertNewRowBefore($excel_row, 1);
                $excel_row++; // Increment the row counter

                $average_delivery_charge = $total_delivery_charge / $delivery_charge_count;

                $del_inc_tax = $average_delivery_charge * 1.09;

                $service_charge_tax = $service_charge * 1.09;

                $ser_gst = $service_charge * 0.09;
                

                $aver_service_charge = $service_charge_tax + $ser_gst;


                $del_gst = $average_delivery_charge * 0.09;

                $aver_gst = $average_delivery_charge + $del_gst;
                
                if ($average_delivery_charge > 0) {
                    $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $company_name);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $delivery_address);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $delivery_address_line2);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $delivery_address_line3);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $delivery_address_line4);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->bill_no);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $date);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, 'DS020');
                    $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, 1);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, "Delivery Service");
                    $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $average_delivery_charge);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $del_inc_tax);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, '0');
                    $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $average_delivery_charge);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $aver_gst);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, 'Sale;'.$sales_person);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $row->driver_memo);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $sales_person);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $delivery_date);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
                    $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $del_gst);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $payment_terms);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $record_id);

                    $excel_row++;
                }

                    if ($slice == '12mm') {
                        $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $company_name);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $delivery_address);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $delivery_address_line2);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $delivery_address_line3);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $delivery_address_line4);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->bill_no);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $date);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, 'SL012');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, 1);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, "Slice 12mm Service Charge");
                        $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $service_charge);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $service_charge_tax);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, '0');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $service_charge);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $aver_service_charge);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, 'Sale;'.$sales_person);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $row->driver_memo);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $sales_person);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $delivery_date);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $ser_gst);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $payment_terms);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $record_id);
                        $excel_row++;
                    }

                    if ($slice == '20mm') {
                        $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $company_name);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $delivery_address);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $delivery_address_line2);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $delivery_address_line3);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $delivery_address_line4);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->bill_no);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $date);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, 'SL020');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, 1);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, "Slice 20mm Service Charge");
                        $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $service_charge);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $service_charge_tax);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, '0');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $service_charge);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $aver_service_charge);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, 'Sale;'.$sales_person);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $row->driver_memo);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $sales_person);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $delivery_date);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $ser_gst);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $payment_terms);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $record_id);
                        $excel_row++;
                    }

                $excel_row++; // Increment the row counter

                // Reset variables for the next company
                $total_delivery_charge = 0;
                $delivery_charge_count = 0;

                // Add an empty row
               // $object->getActiveSheet()->insertNewRowBefore($excel_row, 1);
              
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
        $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $inc_tax);
        $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, '0');
        $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $row->amount);
        $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $net_total);
        $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, 'Sale;'.$row->sales_person);
        $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $row->driver_memo);
        $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $row->sales_person);
        $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $delivery_date);
        $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');        
        $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $row->gst_amount);
        $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $row->payment_terms);
        $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $row->record_id);

        // Update the total delivery charge for the company
        $total_delivery_charge += $row->delivery_charge;
        $delivery_charge_count++;

        // Store company details for the delivery charge row
        $service_charge = $row->service_charge;
        $company_name = $row->company_name;
        $delivery_address = $row->delivery_address;
        $delivery_address_line2 = $row->delivery_address_line2;
        $delivery_address_line3 = $row->delivery_address_line3;
        $delivery_address_line4 = $row->delivery_address_line4;
        $sales_person = $row->sales_person;
        $driver_memo = $row->driver_memo;
        $payment_terms = $row->payment_terms;
        $record_id = $row->record_id;

        $prev_name = $row->name;

        // Increment the Excel row counter
        $excel_row++;
    }

    // Check for any remaining delivery charges
    if ($delivery_charge_count > 0) {
        $object->getActiveSheet()->insertNewRowBefore($excel_row, 1);
        $excel_row++; // Increment the row counter

        
        $service_charge_tax = $service_charge * 1.09;

        $ser_gst = $service_charge * 0.09;
        

        $aver_service_charge = $service_charge_tax + $ser_gst;

        $average_delivery_charge = $total_delivery_charge / $delivery_charge_count;
        $del_inc_tax = $average_delivery_charge * 1.09;
      
        $del_gst = $average_delivery_charge * 0.09;

        $aver_gst = $average_delivery_charge + $del_gst;

        if ($average_delivery_charge > 0) {
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $company_name);
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $delivery_address);
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $delivery_address_line2);
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $delivery_address_line3);
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $delivery_address_line4);
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->bill_no);
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $date);
            $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, 'DS020');
            $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, 1);
            $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, "Delivery Service");
            $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $average_delivery_charge);
            $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $del_inc_tax);
            $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, '0');
            $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $average_delivery_charge);
            $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $aver_gst);
            $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, 'Sale;'.$sales_person);
            $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $driver_memo);
            $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $sales_person);
            $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $delivery_date);
            $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
            $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $del_gst);
            $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $payment_terms);
            $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $record_id);

            $excel_row++;
        }

        if ($slice == '12mm') {
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $company_name);
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $delivery_address);
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $delivery_address_line2);
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $delivery_address_line3);
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $delivery_address_line4);
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->bill_no);
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $date);
            $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, 'SL012');
            $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, 1);
            $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, "Slice 12mm Service Charge");
            $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $service_charge);
            $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $service_charge_tax);
            $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, '0');
            $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $service_charge);
            $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $aver_service_charge);
            $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, 'Sale;'.$sales_person);
            $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $driver_memo);
            $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $sales_person);
            $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $delivery_date);
            $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
            $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $ser_gst);
            $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $payment_terms);
            $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $record_id);

            $excel_row++;

        }

        if ($slice == '20mm') {
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $company_name);
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $delivery_address);
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $delivery_address_line2);
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $delivery_address_line3);
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $delivery_address_line4);
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->bill_no);
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $date);
            $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, 'SL020');
            $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, 1);
            $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, "Slice 20mm Service Charge");
            $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $service_charge);
            $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $service_charge_tax);
            $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, '0');
            $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $service_charge);
            $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $aver_service_charge);
            $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, 'Sale;'.$sales_person);
            $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $driver_memo);
            $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $sales_person);
            $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $delivery_date);
            $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
            $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $ser_gst);
            $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $payment_terms);
            $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $record_id);

            $excel_row++;
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
