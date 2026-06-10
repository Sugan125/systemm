<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Excel_export extends CI_Controller {
 
    function index()
    {
        $this->load->model("excel_export_model");
        $data["employee_data"] = $this->excel_export_model->fetch_data();
        $this->load->view("excel_export_view", $data);
    }


    function action()
    {
        ini_set('memory_limit', '-1'); 
        ini_set('max_execution_time', 0); 

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

        $employee_data = $this->excel_export_model->fetch_data();

        $excel_row          = 2;
        $prev_name          = '__INITIAL_VALUE__';
        $prev_bill_no       = '__INITIAL_VALUE__';
        $prev_delivery_date = '__INITIAL_DATE__';

        $total_delivery_charge = 0;
        $delivery_charge_count = 0;
        $fuel_surcharge        = 0;

        $company_name      = '';
        $shipp_address     = '';
        $shipp_address_line2 = '';
        $shipp_address_line3 = '';
        $shipp_address_line4 = '';
        $bill_no           = '';
        $date              = '';
        $sales_person      = '';
        $driver_memo       = '';
        $payment_terms     = '';
        $record_id         = '';

        foreach ($employee_data as $row) {

            $schedule_date = strtotime($row->created_date);
            $date          = date('d-m-Y', strtotime($row->delivery_date));
            $rate          = $row->rate;
            $inc_tax       = $rate * 1.09;

            if (is_numeric($row->delivery_date)) {
                $delivery_date = date('d-m-Y', $row->delivery_date);
            } else {
                $timestamp = strtotime($row->delivery_date);
                $delivery_date = ($timestamp === false) ? 'Invalid date' : date('d-m-Y', $timestamp);
            }

            $slice           = $row->slice_type;
            $service_charge  = $row->service_charge;
            $ser_inc_tax     = $service_charge * 1.09;
            $service_charge_tax = $service_charge * 1.09;
            $ser_gst         = $service_charge * 0.09;
            $qty             = $row->qty;
            $aver_service_charge = $service_charge_tax * $qty;

            $ship_address      = $row->shipping_address      ? $row->shipping_address      : $row->delivery_address;
            $ship_address_line2 = $row->shipping_address_line2 ? $row->shipping_address_line2 : $row->delivery_address_line2;
            $ship_address_line3 = $row->shipping_address_line3 ? $row->shipping_address_line3 : $row->delivery_address_line3;
            $ship_address_line4 = $row->shipping_address_line4 ? $row->shipping_address_line4 : $row->delivery_address_line4;

            if ($service_charge > 0) {
                $amount    = $row->amount - $service_charge;
                $net_total = $inc_tax * $row->qty;
                $gst_amt   = $row->gst_amount - $ser_gst;
            } else {
                $amount    = $row->amount;
                $net_total = $inc_tax * $row->qty;
                $gst_amt   = $row->gst_amount;
            }

            if (($row->name != $prev_name && $prev_name != '__INITIAL_VALUE__') || ($row->name == $prev_name && $delivery_date != $prev_delivery_date) || ($row->bill_no != $prev_bill_no && $prev_bill_no != '__INITIAL_VALUE__')) {
                if ($delivery_charge_count > 0) {
                    

                    $average_delivery_charge = $total_delivery_charge / $delivery_charge_count;
                    $del_inc_tax = $average_delivery_charge * 1.09;
                    $del_gst     = $average_delivery_charge * 0.09;
                    $aver_gst    = $average_delivery_charge + $del_gst;

                    // ✅ Delivery Service row
                    if ($average_delivery_charge > 0) {
                        $object->getActiveSheet()->setCellValueByColumnAndRow(0,  $excel_row, $company_name);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(1,  $excel_row, $shipp_address);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(2,  $excel_row, $shipp_address_line2);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(3,  $excel_row, $shipp_address_line3);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(4,  $excel_row, $shipp_address_line4);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(5,  $excel_row, $bill_no);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(6,  $excel_row, $date);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(8,  $excel_row, 'DS020');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(9,  $excel_row, 1);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, 'Delivery Service');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $average_delivery_charge);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $del_inc_tax);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, '0');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $average_delivery_charge);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $aver_gst);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, 'Sale;' . $sales_person);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $driver_memo);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $sales_person);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $delivery_date);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $del_gst);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $payment_terms);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $record_id);
                        $excel_row++;
                    }

                    // ✅ Fuel Surcharge row
                    if ($fuel_surcharge > 0) {
                        $fuel_inc_tax   = $fuel_surcharge * 1.09;
                        $fuel_gst       = $fuel_surcharge * 0.09;
                        $fuel_inc_total = $fuel_surcharge + $fuel_gst;

                        $object->getActiveSheet()->setCellValueByColumnAndRow(0,  $excel_row, $company_name);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(1,  $excel_row, $shipp_address);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(2,  $excel_row, $shipp_address_line2);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(3,  $excel_row, $shipp_address_line3);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(4,  $excel_row, $shipp_address_line4);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(5,  $excel_row, $bill_no);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(6,  $excel_row, $date);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(8,  $excel_row, 'MISC');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(9,  $excel_row, 1);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, 'Fuel Surcharge');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $fuel_surcharge);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $fuel_inc_tax);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, '0');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $fuel_surcharge);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $fuel_inc_total);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, 'Sale;' . $sales_person);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $driver_memo);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $sales_person);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $delivery_date);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $fuel_gst);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $payment_terms);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $record_id);
                        $excel_row++;
                    }

                    $excel_row++; // empty separator row

                    // Reset for next group
                    $total_delivery_charge = 0;
                    $delivery_charge_count = 0;
                    $fuel_surcharge        = 0;
                }
            }

            // Main product row
            $object->getActiveSheet()->setCellValueByColumnAndRow(0,  $excel_row, $row->company_name);
            $object->getActiveSheet()->setCellValueByColumnAndRow(1,  $excel_row, $ship_address);
            $object->getActiveSheet()->setCellValueByColumnAndRow(2,  $excel_row, $ship_address_line2);
            $object->getActiveSheet()->setCellValueByColumnAndRow(3,  $excel_row, $ship_address_line3);
            $object->getActiveSheet()->setCellValueByColumnAndRow(4,  $excel_row, $ship_address_line4);
            $object->getActiveSheet()->setCellValueByColumnAndRow(5,  $excel_row, $row->bill_no);
            $object->getActiveSheet()->setCellValueByColumnAndRow(6,  $excel_row, $date);
            $object->getActiveSheet()->setCellValueByColumnAndRow(8,  $excel_row, $row->prod_id);
            $object->getActiveSheet()->setCellValueByColumnAndRow(9,  $excel_row, $row->qty);
            $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $row->product_name);
            $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $row->rate);
            $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $inc_tax);
            $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, '0');
            $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $amount);
            $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $net_total);
            $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, 'Sale;' . $row->sales_person);
            $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $row->driver_memo);
            $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $row->sales_person);
            $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $delivery_date);
            $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
            $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $gst_amt);
            $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $row->payment_terms);
            $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $row->record_id);

            // Slicing 12mm row
            if ($slice == '12mm') {
                $excel_row++;
                $object->getActiveSheet()->setCellValueByColumnAndRow(0,  $excel_row, $row->company_name);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1,  $excel_row, $ship_address);
                $object->getActiveSheet()->setCellValueByColumnAndRow(2,  $excel_row, $ship_address_line2);
                $object->getActiveSheet()->setCellValueByColumnAndRow(3,  $excel_row, $ship_address_line3);
                $object->getActiveSheet()->setCellValueByColumnAndRow(4,  $excel_row, $ship_address_line4);
                $object->getActiveSheet()->setCellValueByColumnAndRow(5,  $excel_row, $row->bill_no);
                $object->getActiveSheet()->setCellValueByColumnAndRow(6,  $excel_row, $date);
                $object->getActiveSheet()->setCellValueByColumnAndRow(8,  $excel_row, 'SL012');
                $object->getActiveSheet()->setCellValueByColumnAndRow(9,  $excel_row, $row->qty);
                $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, 'Slicing 12mm Service Charge');
                $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, '0.50');
                $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $service_charge_tax);
                $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, '0');
                $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $row->service_charge);
                $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $aver_service_charge);
                $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, 'Sale;' . $row->sales_person);
                $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $row->driver_memo);
                $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $row->sales_person);
                $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $delivery_date);
                $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
                $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $ser_gst);
                $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $row->payment_terms);
                $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $row->record_id);
            }

            // Slicing 20mm row
            if ($slice == '20mm') {
                $excel_row++;
                $object->getActiveSheet()->setCellValueByColumnAndRow(0,  $excel_row, $row->company_name);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1,  $excel_row, $ship_address);
                $object->getActiveSheet()->setCellValueByColumnAndRow(2,  $excel_row, $ship_address_line2);
                $object->getActiveSheet()->setCellValueByColumnAndRow(3,  $excel_row, $ship_address_line3);
                $object->getActiveSheet()->setCellValueByColumnAndRow(4,  $excel_row, $ship_address_line4);
                $object->getActiveSheet()->setCellValueByColumnAndRow(5,  $excel_row, $row->bill_no);
                $object->getActiveSheet()->setCellValueByColumnAndRow(6,  $excel_row, $date);
                $object->getActiveSheet()->setCellValueByColumnAndRow(8,  $excel_row, 'SL020');
                $object->getActiveSheet()->setCellValueByColumnAndRow(9,  $excel_row, $row->qty);
                $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, 'Slicing 20mm Service Charge');
                $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, '0.50');
                $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $service_charge_tax);
                $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, '0');
                $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $row->service_charge);
                $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $aver_service_charge);
                $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, 'Sale;' . $row->sales_person);
                $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $row->driver_memo);
                $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $row->sales_person);
                $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $delivery_date);
                $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
                $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $ser_gst);
                $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $row->payment_terms);
                $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $row->record_id);
            }

            // Accumulate delivery charge and store company details
            $total_delivery_charge += $row->delivery_charge;
            $delivery_charge_count++;

            $service_charge      = $row->service_charge;
            $fuel_surcharge      = $row->fuel_surcharge;
            $company_name        = $row->company_name;
            $shipp_address       = $row->shipping_address      ? $row->shipping_address      : $row->delivery_address;
            $shipp_address_line2 = $row->shipping_address_line2 ? $row->shipping_address_line2 : $row->delivery_address_line2;
            $shipp_address_line3 = $row->shipping_address_line3 ? $row->shipping_address_line3 : $row->delivery_address_line3;
            $shipp_address_line4 = $row->shipping_address_line4 ? $row->shipping_address_line4 : $row->delivery_address_line4;
            $sales_person        = $row->sales_person;
            $driver_memo         = $row->driver_memo;
            $bill_no             = $row->bill_no;
            $payment_terms       = $row->payment_terms;
            $record_id           = $row->record_id;

            $prev_name          = $row->name;
            $prev_bill_no       = $row->bill_no;
            $prev_delivery_date = $delivery_date;

            $excel_row++;
        }

        // Remaining last invoice group
        if ($delivery_charge_count > 0) {
            

            $service_charge_tax  = 0.50 * 1.09;
            $ser_gst             = $service_charge * 0.09;
            $aver_service_charge = $service_charge_tax + $ser_gst;

            $average_delivery_charge = $total_delivery_charge / $delivery_charge_count;
            $del_inc_tax = $average_delivery_charge * 1.09;
            $del_gst     = $average_delivery_charge * 0.09;
            $aver_gst    = $average_delivery_charge + $del_gst;

            // ✅ Delivery Service row
            if ($average_delivery_charge > 0) {
                $object->getActiveSheet()->setCellValueByColumnAndRow(0,  $excel_row, $company_name);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1,  $excel_row, $shipp_address);
                $object->getActiveSheet()->setCellValueByColumnAndRow(2,  $excel_row, $shipp_address_line2);
                $object->getActiveSheet()->setCellValueByColumnAndRow(3,  $excel_row, $shipp_address_line3);
                $object->getActiveSheet()->setCellValueByColumnAndRow(4,  $excel_row, $shipp_address_line4);
                $object->getActiveSheet()->setCellValueByColumnAndRow(5,  $excel_row, $bill_no);
                $object->getActiveSheet()->setCellValueByColumnAndRow(6,  $excel_row, $date);
                $object->getActiveSheet()->setCellValueByColumnAndRow(8,  $excel_row, 'DS020');
                $object->getActiveSheet()->setCellValueByColumnAndRow(9,  $excel_row, 1);
                $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, 'Delivery Service');
                $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $average_delivery_charge);
                $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $del_inc_tax);
                $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, '0');
                $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $average_delivery_charge);
                $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $aver_gst);
                $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, 'Sale;' . $sales_person);
                $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $driver_memo);
                $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $sales_person);
                $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $delivery_date);
                $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
                $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $del_gst);
                $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $payment_terms);
                $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $record_id);
                $excel_row++;
            }

            // ✅ Fuel Surcharge row
            if ($fuel_surcharge > 0) {
                $fuel_inc_tax   = $fuel_surcharge * 1.09;
                $fuel_gst       = $fuel_surcharge * 0.09;
                $fuel_inc_total = $fuel_surcharge + $fuel_gst;

                $object->getActiveSheet()->setCellValueByColumnAndRow(0,  $excel_row, $company_name);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1,  $excel_row, $shipp_address);
                $object->getActiveSheet()->setCellValueByColumnAndRow(2,  $excel_row, $shipp_address_line2);
                $object->getActiveSheet()->setCellValueByColumnAndRow(3,  $excel_row, $shipp_address_line3);
                $object->getActiveSheet()->setCellValueByColumnAndRow(4,  $excel_row, $shipp_address_line4);
                $object->getActiveSheet()->setCellValueByColumnAndRow(5,  $excel_row, $bill_no);
                $object->getActiveSheet()->setCellValueByColumnAndRow(6,  $excel_row, $date);
                $object->getActiveSheet()->setCellValueByColumnAndRow(8,  $excel_row, 'MISC');
                $object->getActiveSheet()->setCellValueByColumnAndRow(9,  $excel_row, 1);
                $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, 'Fuel Surcharge');
                $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $fuel_surcharge);
                $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $fuel_inc_tax);
                $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, '0');
                $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $fuel_surcharge);
                $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $fuel_inc_total);
                $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, 'Sale;' . $sales_person);
                $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $driver_memo);
                $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $sales_person);
                $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $delivery_date);
                $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
                $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $fuel_gst);
                $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $payment_terms);
                $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $record_id);
                $excel_row++;
            }

            $object->getActiveSheet()->insertNewRowBefore($excel_row, 1);
            $excel_row++;
        }

        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="SALE-ITEM.xls"');
        $object_writer->save('php://output');
    }


    function actiondate()
    {
        set_time_limit(300);
        ini_set('max_execution_time', 300);

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

        $excel_row          = 2;
        $prev_name          = '__INITIAL_VALUE__';
        $prev_bill_no       = '__INITIAL_VALUE__';
        $prev_delivery_date = '__INITIAL_DATE__';

        $total_delivery_charge = 0;
        $delivery_charge_count = 0;
        $fuel_surcharge        = 0;

        $company_name        = '';
        $shipp_address       = '';
        $shipp_address_line2 = '';
        $shipp_address_line3 = '';
        $shipp_address_line4 = '';
        $bill_no             = '';
        $date                = '';
        $sales_person        = '';
        $driver_memo         = '';
        $payment_terms       = '';
        $record_id           = '';

        foreach ($employee_data as $row) {

            $schedule_date = strtotime($row->created_date);
            $date          = date('d-m-Y', strtotime($row->delivery_date));
            $rate          = (float)$row->rate;
            $inc_tax       = $rate * 1.09;

            if (is_numeric($row->delivery_date)) {
                $delivery_date = date('d-m-Y', $row->delivery_date);
            } else {
                $timestamp     = strtotime($row->delivery_date);
                $delivery_date = ($timestamp === false) ? 'Invalid date' : date('d-m-Y', $timestamp);
            }

            $slice              = $row->slice_type;
            $service_charge     = $row->service_charge;
            $ser_inc_tax        = $service_charge * 1.09;
            $service_charge_tax = 0.50 * 1.09;
            $ser_gst            = $service_charge * 0.09;
            $qty                = $row->qty;
            $aver_service_charge = $service_charge_tax * $qty;

            $ship_address       = $row->shipping_address       ? $row->shipping_address       : $row->delivery_address;
            $ship_address_line2 = $row->shipping_address_line2 ? $row->shipping_address_line2 : $row->delivery_address_line2;
            $ship_address_line3 = $row->shipping_address_line3 ? $row->shipping_address_line3 : $row->delivery_address_line3;
            $ship_address_line4 = $row->shipping_address_line4 ? $row->shipping_address_line4 : $row->delivery_address_line4;

            if ($service_charge > 0) {
                $amount    = $row->amount - $service_charge;
                $net_total = $inc_tax * $row->qty;
                $gst_amt   = $row->gst_amount - $ser_gst;
            } else {
                $amount    = $row->amount;
                $net_total = $inc_tax * $row->qty;
                $gst_amt   = $row->gst_amount;
            }

            if (($row->name != $prev_name && $prev_name != '__INITIAL_VALUE__') || ($row->name == $prev_name && $delivery_date != $prev_delivery_date) || ($row->bill_no != $prev_bill_no && $prev_bill_no != '__INITIAL_VALUE__')) {
                if ($delivery_charge_count > 0) {
                   

                    $average_delivery_charge = $total_delivery_charge / $delivery_charge_count;
                    $del_inc_tax = $average_delivery_charge * 1.09;
                    $del_gst     = $average_delivery_charge * 0.09;
                    $aver_gst    = $average_delivery_charge + $del_gst;

                    // ✅ Delivery Service row
                    if ($average_delivery_charge > 0) {
                        $object->getActiveSheet()->setCellValueByColumnAndRow(0,  $excel_row, $company_name);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(1,  $excel_row, $shipp_address);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(2,  $excel_row, $shipp_address_line2);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(3,  $excel_row, $shipp_address_line3);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(4,  $excel_row, $shipp_address_line4);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(5,  $excel_row, $bill_no);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(6,  $excel_row, $date);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(8,  $excel_row, 'DS020');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(9,  $excel_row, 1);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, 'Delivery Service');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $average_delivery_charge);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $del_inc_tax);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, '0');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $average_delivery_charge);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $aver_gst);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, 'Sale;' . $sales_person);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $driver_memo);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $sales_person);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $delivery_date);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $del_gst);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $payment_terms);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $record_id);
                        $excel_row++;
                    }

                    // ✅ Fuel Surcharge row
                    if ($fuel_surcharge > 0) {
                        $fuel_inc_tax   = $fuel_surcharge * 1.09;
                        $fuel_gst       = $fuel_surcharge * 0.09;
                        $fuel_inc_total = $fuel_surcharge + $fuel_gst;

                        $object->getActiveSheet()->setCellValueByColumnAndRow(0,  $excel_row, $company_name);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(1,  $excel_row, $shipp_address);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(2,  $excel_row, $shipp_address_line2);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(3,  $excel_row, $shipp_address_line3);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(4,  $excel_row, $shipp_address_line4);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(5,  $excel_row, $bill_no);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(6,  $excel_row, $date);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(8,  $excel_row, 'MISC');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(9,  $excel_row, 1);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, 'Fuel Surcharge');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $fuel_surcharge);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $fuel_inc_tax);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, '0');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $fuel_surcharge);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $fuel_inc_total);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, 'Sale;' . $sales_person);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $driver_memo);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $sales_person);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $delivery_date);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $fuel_gst);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $payment_terms);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $record_id);
                        $excel_row++;
                    }

                    $excel_row++; // empty separator row

                    // Reset for next group
                    $total_delivery_charge = 0;
                    $delivery_charge_count = 0;
                    $fuel_surcharge        = 0;
                }
            }

            // Main product row
            $object->getActiveSheet()->setCellValueByColumnAndRow(0,  $excel_row, $row->company_name);
            $object->getActiveSheet()->setCellValueByColumnAndRow(1,  $excel_row, $ship_address);
            $object->getActiveSheet()->setCellValueByColumnAndRow(2,  $excel_row, $ship_address_line2);
            $object->getActiveSheet()->setCellValueByColumnAndRow(3,  $excel_row, $ship_address_line3);
            $object->getActiveSheet()->setCellValueByColumnAndRow(4,  $excel_row, $ship_address_line4);
            $object->getActiveSheet()->setCellValueByColumnAndRow(5,  $excel_row, $row->bill_no);
            $object->getActiveSheet()->setCellValueByColumnAndRow(6,  $excel_row, $date);
            $object->getActiveSheet()->setCellValueByColumnAndRow(8,  $excel_row, $row->prod_id);
            $object->getActiveSheet()->setCellValueByColumnAndRow(9,  $excel_row, $row->qty);
            $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $row->product_name);
            $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $row->rate);
            $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $inc_tax);
            $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, '0');
            $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $amount);
            $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $net_total);
            $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, 'Sale;' . $row->sales_person);
            $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $row->driver_memo);
            $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $row->sales_person);
            $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $delivery_date);
            $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
            $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $gst_amt);
            $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $row->payment_terms);
            $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $row->record_id);

            // Slicing 12mm row
            if ($slice == '12mm') {
                $excel_row++;
                $object->getActiveSheet()->setCellValueByColumnAndRow(0,  $excel_row, $row->company_name);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1,  $excel_row, $ship_address);
                $object->getActiveSheet()->setCellValueByColumnAndRow(2,  $excel_row, $ship_address_line2);
                $object->getActiveSheet()->setCellValueByColumnAndRow(3,  $excel_row, $ship_address_line3);
                $object->getActiveSheet()->setCellValueByColumnAndRow(4,  $excel_row, $ship_address_line4);
                $object->getActiveSheet()->setCellValueByColumnAndRow(5,  $excel_row, $row->bill_no);
                $object->getActiveSheet()->setCellValueByColumnAndRow(6,  $excel_row, $date);
                $object->getActiveSheet()->setCellValueByColumnAndRow(8,  $excel_row, 'SL012');
                $object->getActiveSheet()->setCellValueByColumnAndRow(9,  $excel_row, $row->qty);
                $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, 'Slicing 12mm Service Charge');
                $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, '0.50');
                $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $service_charge_tax);
                $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, '0');
                $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $row->service_charge);
                $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $aver_service_charge);
                $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, 'Sale;' . $row->sales_person);
                $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $row->driver_memo);
                $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $row->sales_person);
                $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $delivery_date);
                $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
                $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $ser_gst);
                $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $row->payment_terms);
                $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $row->record_id);
            }

            // Slicing 20mm row
            if ($slice == '20mm') {
                $excel_row++;
                $object->getActiveSheet()->setCellValueByColumnAndRow(0,  $excel_row, $row->company_name);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1,  $excel_row, $ship_address);
                $object->getActiveSheet()->setCellValueByColumnAndRow(2,  $excel_row, $ship_address_line2);
                $object->getActiveSheet()->setCellValueByColumnAndRow(3,  $excel_row, $ship_address_line3);
                $object->getActiveSheet()->setCellValueByColumnAndRow(4,  $excel_row, $ship_address_line4);
                $object->getActiveSheet()->setCellValueByColumnAndRow(5,  $excel_row, $row->bill_no);
                $object->getActiveSheet()->setCellValueByColumnAndRow(6,  $excel_row, $date);
                $object->getActiveSheet()->setCellValueByColumnAndRow(8,  $excel_row, 'SL020');
                $object->getActiveSheet()->setCellValueByColumnAndRow(9,  $excel_row, $row->qty);
                $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, 'Slicing 20mm Service Charge');
                $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, '0.50');
                $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $service_charge_tax);
                $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, '0');
                $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $row->service_charge);
                $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $aver_service_charge);
                $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, 'Sale;' . $row->sales_person);
                $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $row->driver_memo);
                $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $row->sales_person);
                $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $delivery_date);
                $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
                $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $ser_gst);
                $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $row->payment_terms);
                $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $row->record_id);
            }

            // Accumulate and store
            $total_delivery_charge += $row->delivery_charge;
            $delivery_charge_count++;

            $service_charge      = $row->service_charge;
            $fuel_surcharge      = $row->fuel_surcharge;
            $company_name        = $row->company_name;
            $shipp_address       = $row->shipping_address       ? $row->shipping_address       : $row->delivery_address;
            $shipp_address_line2 = $row->shipping_address_line2 ? $row->shipping_address_line2 : $row->delivery_address_line2;
            $shipp_address_line3 = $row->shipping_address_line3 ? $row->shipping_address_line3 : $row->delivery_address_line3;
            $shipp_address_line4 = $row->shipping_address_line4 ? $row->shipping_address_line4 : $row->delivery_address_line4;
            $bill_no             = $row->bill_no;
            $sales_person        = $row->sales_person;
            $driver_memo         = $row->driver_memo;
            $payment_terms       = $row->payment_terms;
            $record_id           = $row->record_id;

            $prev_name          = $row->name;
            $prev_bill_no       = $row->bill_no;
            $prev_delivery_date = $delivery_date;

            $excel_row++;
        }

        // Remaining last invoice group
        if ($delivery_charge_count > 0) {
           

            $service_charge_tax  = $service_charge * 1.09;
            $ser_gst             = $service_charge * 0.09;
            $aver_service_charge = $service_charge_tax + $ser_gst;

            $average_delivery_charge = $total_delivery_charge / $delivery_charge_count;
            $del_inc_tax = $average_delivery_charge * 1.09;
            $del_gst     = $average_delivery_charge * 0.09;
            $aver_gst    = $average_delivery_charge + $del_gst;

            // ✅ Delivery Service row
            if ($average_delivery_charge > 0) {
                $object->getActiveSheet()->setCellValueByColumnAndRow(0,  $excel_row, $company_name);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1,  $excel_row, $shipp_address);
                $object->getActiveSheet()->setCellValueByColumnAndRow(2,  $excel_row, $shipp_address_line2);
                $object->getActiveSheet()->setCellValueByColumnAndRow(3,  $excel_row, $shipp_address_line3);
                $object->getActiveSheet()->setCellValueByColumnAndRow(4,  $excel_row, $shipp_address_line4);
                $object->getActiveSheet()->setCellValueByColumnAndRow(5,  $excel_row, $bill_no);
                $object->getActiveSheet()->setCellValueByColumnAndRow(6,  $excel_row, $date);
                $object->getActiveSheet()->setCellValueByColumnAndRow(8,  $excel_row, 'DS020');
                $object->getActiveSheet()->setCellValueByColumnAndRow(9,  $excel_row, 1);
                $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, 'Delivery Service');
                $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $average_delivery_charge);
                $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $del_inc_tax);
                $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, '0');
                $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $average_delivery_charge);
                $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $aver_gst);
                $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, 'Sale;' . $sales_person);
                $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $driver_memo);
                $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $sales_person);
                $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $delivery_date);
                $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
                $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $del_gst);
                $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $payment_terms);
                $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $record_id);
                $excel_row++;
            }

            // ✅ Fuel Surcharge row
            if ($fuel_surcharge > 0) {
                $fuel_inc_tax   = $fuel_surcharge * 1.09;
                $fuel_gst       = $fuel_surcharge * 0.09;
                $fuel_inc_total = $fuel_surcharge + $fuel_gst;

                $object->getActiveSheet()->setCellValueByColumnAndRow(0,  $excel_row, $company_name);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1,  $excel_row, $shipp_address);
                $object->getActiveSheet()->setCellValueByColumnAndRow(2,  $excel_row, $shipp_address_line2);
                $object->getActiveSheet()->setCellValueByColumnAndRow(3,  $excel_row, $shipp_address_line3);
                $object->getActiveSheet()->setCellValueByColumnAndRow(4,  $excel_row, $shipp_address_line4);
                $object->getActiveSheet()->setCellValueByColumnAndRow(5,  $excel_row, $bill_no);
                $object->getActiveSheet()->setCellValueByColumnAndRow(6,  $excel_row, $date);
                $object->getActiveSheet()->setCellValueByColumnAndRow(8,  $excel_row, 'MISC');
                $object->getActiveSheet()->setCellValueByColumnAndRow(9,  $excel_row, 1);
                $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, 'Fuel Surcharge');
                $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $fuel_surcharge);
                $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $fuel_inc_tax);
                $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, '0');
                $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $fuel_surcharge);
                $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $fuel_inc_total);
                $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, 'Sale;' . $sales_person);
                $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $driver_memo);
                $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $sales_person);
                $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $delivery_date);
                $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
                $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $fuel_gst);
                $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $payment_terms);
                $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $record_id);
                $excel_row++;
            }

            $object->getActiveSheet()->insertNewRowBefore($excel_row, 1);
            $excel_row++;
        }

        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="SALE-ITEM.xls"');
        $object_writer->save('php://output');
    }


    function actiondaterange()
    {
        set_time_limit(300);
        ini_set('max_execution_time', 300);

        $start_date = $this->input->post('start_date');
        $end_date   = $this->input->post('end_date');
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

        $employee_data = $this->excel_export_model->fetch_data_by_date_range($start_date, $end_date);

        $excel_row          = 2;
        $prev_name          = '__INITIAL_VALUE__';
        $prev_bill_no       = '__INITIAL_VALUE__';
        $prev_delivery_date = '__INITIAL_DATE__';

        $total_delivery_charge = 0;
        $delivery_charge_count = 0;
        $fuel_surcharge        = 0;

        $company_name        = '';
        $shipp_address       = '';
        $shipp_address_line2 = '';
        $shipp_address_line3 = '';
        $shipp_address_line4 = '';
        $date                = '';
        $bill_no             = '';
        $sales_person        = '';
        $driver_memo         = '';
        $payment_terms       = '';
        $record_id           = '';

        foreach ($employee_data as $row) {

            $schedule_date = strtotime($row->created_date);
            $date          = date('d-m-Y', strtotime($row->delivery_date));
            $rate          = $row->rate;
            $inc_tax       = $rate * 1.09;

            if (is_numeric($row->delivery_date)) {
                $delivery_date = date('d-m-Y', $row->delivery_date);
            } else {
                $timestamp     = strtotime($row->delivery_date);
                $delivery_date = ($timestamp === false) ? 'Invalid date' : date('d-m-Y', $timestamp);
            }

            $slice              = $row->slice_type;
            $service_charge     = $row->service_charge;
            $ser_inc_tax        = $service_charge * 1.09;
            $service_charge_tax = 0.50 * 1.09;
            $ser_gst            = $service_charge * 0.09;
            $qty                = $row->qty;
            $aver_service_charge = $service_charge_tax * $qty;

            $ship_address       = $row->shipping_address       ? $row->shipping_address       : $row->delivery_address;
            $ship_address_line2 = $row->shipping_address_line2 ? $row->shipping_address_line2 : $row->delivery_address_line2;
            $ship_address_line3 = $row->shipping_address_line3 ? $row->shipping_address_line3 : $row->delivery_address_line3;
            $ship_address_line4 = $row->shipping_address_line4 ? $row->shipping_address_line4 : $row->delivery_address_line4;

            if ($service_charge > 0) {
                $amount    = $row->amount - $service_charge;
                $net_total = $inc_tax * $row->qty;
                $gst_amt   = $row->gst_amount - $ser_gst;
            } else {
                $amount    = $row->amount;
                $net_total = $inc_tax * $row->qty;
                $gst_amt   = $row->gst_amount;
            }

            if (($row->name != $prev_name && $prev_name != '__INITIAL_VALUE__') || ($row->name == $prev_name && $delivery_date != $prev_delivery_date) || ($row->bill_no != $prev_bill_no && $prev_bill_no != '__INITIAL_VALUE__')) {
                if ($delivery_charge_count > 0) {
                  

                    $average_delivery_charge = $total_delivery_charge / $delivery_charge_count;
                    $del_inc_tax = $average_delivery_charge * 1.09;
                    $del_gst     = $average_delivery_charge * 0.09;
                    $aver_gst    = $average_delivery_charge + $del_gst;

                    // ✅ Delivery Service row
                    if ($average_delivery_charge > 0) {
                        $object->getActiveSheet()->setCellValueByColumnAndRow(0,  $excel_row, $company_name);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(1,  $excel_row, $shipp_address);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(2,  $excel_row, $shipp_address_line2);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(3,  $excel_row, $shipp_address_line3);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(4,  $excel_row, $shipp_address_line4);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(5,  $excel_row, $bill_no);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(6,  $excel_row, $date);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(8,  $excel_row, 'DS020');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(9,  $excel_row, 1);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, 'Delivery Service');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $average_delivery_charge);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $del_inc_tax);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, '0');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $average_delivery_charge);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $aver_gst);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, 'Sale;' . $sales_person);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $driver_memo);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $sales_person);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $delivery_date);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $del_gst);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $payment_terms);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $record_id);
                        $excel_row++;
                    }

                    // ✅ Fuel Surcharge row
                    if ($fuel_surcharge > 0) {
                        $fuel_inc_tax   = $fuel_surcharge * 1.09;
                        $fuel_gst       = $fuel_surcharge * 0.09;
                        $fuel_inc_total = $fuel_surcharge + $fuel_gst;

                        $object->getActiveSheet()->setCellValueByColumnAndRow(0,  $excel_row, $company_name);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(1,  $excel_row, $shipp_address);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(2,  $excel_row, $shipp_address_line2);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(3,  $excel_row, $shipp_address_line3);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(4,  $excel_row, $shipp_address_line4);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(5,  $excel_row, $bill_no);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(6,  $excel_row, $date);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(8,  $excel_row, 'MISC');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(9,  $excel_row, 1);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, 'Fuel Surcharge');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $fuel_surcharge);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $fuel_inc_tax);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, '0');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $fuel_surcharge);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $fuel_inc_total);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, 'Sale;' . $sales_person);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $driver_memo);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $sales_person);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $delivery_date);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $fuel_gst);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $payment_terms);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $record_id);
                        $excel_row++;
                    }

                    $excel_row++; // empty separator row

                    // Reset for next group
                    $total_delivery_charge = 0;
                    $delivery_charge_count = 0;
                    $fuel_surcharge        = 0;
                }
            }

            // Main product row
            $object->getActiveSheet()->setCellValueByColumnAndRow(0,  $excel_row, $row->company_name);
            $object->getActiveSheet()->setCellValueByColumnAndRow(1,  $excel_row, $ship_address);
            $object->getActiveSheet()->setCellValueByColumnAndRow(2,  $excel_row, $ship_address_line2);
            $object->getActiveSheet()->setCellValueByColumnAndRow(3,  $excel_row, $ship_address_line3);
            $object->getActiveSheet()->setCellValueByColumnAndRow(4,  $excel_row, $ship_address_line4);
            $object->getActiveSheet()->setCellValueByColumnAndRow(5,  $excel_row, $row->bill_no);
            $object->getActiveSheet()->setCellValueByColumnAndRow(6,  $excel_row, $date);
            $object->getActiveSheet()->setCellValueByColumnAndRow(8,  $excel_row, $row->prod_id);
            $object->getActiveSheet()->setCellValueByColumnAndRow(9,  $excel_row, $row->qty);
            $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $row->product_name);
            $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $row->rate);
            $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $inc_tax);
            $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, '0');
            $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $amount);
            $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $net_total);
            $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, 'Sale;' . $row->sales_person);
            $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $row->driver_memo);
            $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $row->sales_person);
            $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $delivery_date);
            $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
            $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $gst_amt);
            $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $row->payment_terms);
            $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $row->record_id);

            // Slicing 12mm row
            if ($slice == '12mm') {
                $excel_row++;
                $object->getActiveSheet()->setCellValueByColumnAndRow(0,  $excel_row, $row->company_name);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1,  $excel_row, $ship_address);
                $object->getActiveSheet()->setCellValueByColumnAndRow(2,  $excel_row, $ship_address_line2);
                $object->getActiveSheet()->setCellValueByColumnAndRow(3,  $excel_row, $ship_address_line3);
                $object->getActiveSheet()->setCellValueByColumnAndRow(4,  $excel_row, $ship_address_line4);
                $object->getActiveSheet()->setCellValueByColumnAndRow(5,  $excel_row, $row->bill_no);
                $object->getActiveSheet()->setCellValueByColumnAndRow(6,  $excel_row, $date);
                $object->getActiveSheet()->setCellValueByColumnAndRow(8,  $excel_row, 'SL012');
                $object->getActiveSheet()->setCellValueByColumnAndRow(9,  $excel_row, $row->qty);
                $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, 'Slicing 12mm Service Charge');
                $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, '0.50');
                $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $service_charge_tax);
                $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, '0');
                $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $row->service_charge);
                $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $aver_service_charge);
                $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, 'Sale;' . $row->sales_person);
                $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $row->driver_memo);
                $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $row->sales_person);
                $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $delivery_date);
                $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
                $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $ser_gst);
                $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $row->payment_terms);
                $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $row->record_id);
            }

            // Slicing 20mm row
            if ($slice == '20mm') {
                $excel_row++;
                $object->getActiveSheet()->setCellValueByColumnAndRow(0,  $excel_row, $row->company_name);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1,  $excel_row, $ship_address);
                $object->getActiveSheet()->setCellValueByColumnAndRow(2,  $excel_row, $ship_address_line2);
                $object->getActiveSheet()->setCellValueByColumnAndRow(3,  $excel_row, $ship_address_line3);
                $object->getActiveSheet()->setCellValueByColumnAndRow(4,  $excel_row, $ship_address_line4);
                $object->getActiveSheet()->setCellValueByColumnAndRow(5,  $excel_row, $row->bill_no);
                $object->getActiveSheet()->setCellValueByColumnAndRow(6,  $excel_row, $date);
                $object->getActiveSheet()->setCellValueByColumnAndRow(8,  $excel_row, 'SL020');
                $object->getActiveSheet()->setCellValueByColumnAndRow(9,  $excel_row, $row->qty);
                $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, 'Slicing 20mm Service Charge');
                $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, '0.50');
                $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $service_charge_tax);
                $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, '0');
                $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $row->service_charge);
                $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $aver_service_charge);
                $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, 'Sale;' . $row->sales_person);
                $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $row->driver_memo);
                $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $row->sales_person);
                $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $delivery_date);
                $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
                $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $ser_gst);
                $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $row->payment_terms);
                $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $row->record_id);
            }

            // Accumulate and store
            $total_delivery_charge += $row->delivery_charge;
            $delivery_charge_count++;

            $service_charge      = $row->service_charge;
            $fuel_surcharge      = $row->fuel_surcharge;
            $company_name        = $row->company_name;
            $shipp_address       = $row->shipping_address       ? $row->shipping_address       : $row->delivery_address;
            $shipp_address_line2 = $row->shipping_address_line2 ? $row->shipping_address_line2 : $row->delivery_address_line2;
            $shipp_address_line3 = $row->shipping_address_line3 ? $row->shipping_address_line3 : $row->delivery_address_line3;
            $shipp_address_line4 = $row->shipping_address_line4 ? $row->shipping_address_line4 : $row->delivery_address_line4;
            $sales_person        = $row->sales_person;
            $bill_no             = $row->bill_no;
            $driver_memo         = $row->driver_memo;
            $payment_terms       = $row->payment_terms;
            $record_id           = $row->record_id;

            $prev_name          = $row->name;
            $prev_bill_no       = $row->bill_no;
            $prev_delivery_date = $delivery_date;

            $excel_row++;
        }

        // Remaining last invoice group
        if ($delivery_charge_count > 0) {
          

            $service_charge_tax  = $service_charge * 1.09;
            $ser_gst             = $service_charge * 0.09;
            $aver_service_charge = $service_charge_tax + $ser_gst;

            $average_delivery_charge = $total_delivery_charge / $delivery_charge_count;
            $del_inc_tax = $average_delivery_charge * 1.09;
            $del_gst     = $average_delivery_charge * 0.09;
            $aver_gst    = $average_delivery_charge + $del_gst;

            // ✅ Delivery Service row
            if ($average_delivery_charge > 0) {
                $object->getActiveSheet()->setCellValueByColumnAndRow(0,  $excel_row, $company_name);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1,  $excel_row, $shipp_address);
                $object->getActiveSheet()->setCellValueByColumnAndRow(2,  $excel_row, $shipp_address_line2);
                $object->getActiveSheet()->setCellValueByColumnAndRow(3,  $excel_row, $shipp_address_line3);
                $object->getActiveSheet()->setCellValueByColumnAndRow(4,  $excel_row, $shipp_address_line4);
                $object->getActiveSheet()->setCellValueByColumnAndRow(5,  $excel_row, $bill_no);
                $object->getActiveSheet()->setCellValueByColumnAndRow(6,  $excel_row, $date);
                $object->getActiveSheet()->setCellValueByColumnAndRow(8,  $excel_row, 'DS020');
                $object->getActiveSheet()->setCellValueByColumnAndRow(9,  $excel_row, 1);
                $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, 'Delivery Service');
                $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $average_delivery_charge);
                $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $del_inc_tax);
                $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, '0');
                $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $average_delivery_charge);
                $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $aver_gst);
                $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, 'Sale;' . $sales_person);
                $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $driver_memo);
                $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $sales_person);
                $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $delivery_date);
                $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
                $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $del_gst);
                $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $payment_terms);
                $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $record_id);
                $excel_row++;
            }

            // ✅ Fuel Surcharge row
            if ($fuel_surcharge > 0) {
                $fuel_inc_tax   = $fuel_surcharge * 1.09;
                $fuel_gst       = $fuel_surcharge * 0.09;
                $fuel_inc_total = $fuel_surcharge + $fuel_gst;

                $object->getActiveSheet()->setCellValueByColumnAndRow(0,  $excel_row, $company_name);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1,  $excel_row, $shipp_address);
                $object->getActiveSheet()->setCellValueByColumnAndRow(2,  $excel_row, $shipp_address_line2);
                $object->getActiveSheet()->setCellValueByColumnAndRow(3,  $excel_row, $shipp_address_line3);
                $object->getActiveSheet()->setCellValueByColumnAndRow(4,  $excel_row, $shipp_address_line4);
                $object->getActiveSheet()->setCellValueByColumnAndRow(5,  $excel_row, $bill_no);
                $object->getActiveSheet()->setCellValueByColumnAndRow(6,  $excel_row, $date);
                $object->getActiveSheet()->setCellValueByColumnAndRow(8,  $excel_row, 'MISC');
                $object->getActiveSheet()->setCellValueByColumnAndRow(9,  $excel_row, 1);
                $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, 'Fuel Surcharge');
                $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $fuel_surcharge);
                $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $fuel_inc_tax);
                $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, '0');
                $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $fuel_surcharge);
                $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $fuel_inc_total);
                $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, 'Sale;' . $sales_person);
                $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $driver_memo);
                $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $sales_person);
                $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $delivery_date);
                $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, 'SR9');
                $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $fuel_gst);
                $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $payment_terms);
                $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $record_id);
                $excel_row++;
            }

            $object->getActiveSheet()->insertNewRowBefore($excel_row, 1);
            $excel_row++;
        }

        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="SALE-ITEM.xls"');
        $object_writer->save('php://output');
    }


    public function sales_summary()
    {
        $data['print'] = 'print';

        $loginuser = $this->session->userdata('LoginSession');
        $data['user_id'] = $loginuser['id'];
        $user_id = $data['user_id'];

        $this->load->view('template/header.php', $data);
        $user      = $this->session->userdata('user_register');
        $users     = $this->session->userdata('normal_user');
        $loginuser = $this->session->userdata('LoginSession');
        $this->load->view('template/sidebar.php', array('user' => $user, 'users' => $users, 'data' => $data, 'loginuser' => $loginuser));
        $this->load->view('invoice/sales_summary.php', array('data' => $data));
        $this->load->view('template/footer.php');
    }
}
