
<html>
<head>
    <style>
        .box-title{
            padding:10px;
        }
    </style>
</head>
<body>
<!-- 
   <table class='table-bordered'>
    <tr>
     <th>Co./Last Name</th>
     <th>Addr 1 - Line 1</th>
     <th>- Line 2</th>
     <th>- Line 3</th>
     <th>- Line 4</th>
     <th>Invoice #</th>
     <th>Date</th>
     <th>Customer PO</th>
     <th>Item Number</th>
     <th>Quantity</th>
     <th>Description</th>
     <th>Price</th>
     <th>Inc-Tax Price</th>
     <th>Discount</th>
     <th>Total</th>
     <th>Inc-Tax Total</th>
     <th>Job</th>
     <th>Comment</th>
     <th>Journal Memo</th>
     <th>Salesperson Last Name</th>
     <th>Shipping Date</th>
     <th>Tax Code</th>
     <th>GST Amount</th>
     <th>Terms - Payment is Due</th>
     <th> - Balance Due Days</th>
     <th>Record ID</th>
    </tr>

/*
foreach($employee_data as $row)
{
    $schedule_date = $row->date_time;
    $date = date('d-m-Y', $schedule_date);

    $schedule_timestamp = $row->date_time;
    $shipping_date = date('d-m-Y', strtotime('+3 days', $schedule_timestamp));

    echo '
    <tr>
        <td>'.$row->name.'</td>
        <td>'.$row->address.'</td>
        <td>'.$row->line2.'</td>
        <td>'.$row->line3.'</td>
        <td>'.$row->line4.'</td>
        <td>'.$row->bill_no.'</td>
        <td>'.$date.'</td>
        <td></td>
        <td>'.$row->prod_id.'</td>
        <td>'.$row->qty.'</td>
        <td>'.$row->product_name.'</td>
        <td>'.$row->rate.'</td>
        <td></td>
        <td>0%</td>
        <td>'.$row->amount.'</td>
        <td></td>
        <td></td>
        <td></td>
        <td>Sale;'.$row->name.'</td>
        <td></td>
        <td>'.$shipping_date.'</td>
        <td>SR9</td>
        <td>'.$row->gst_amt.'</td>
        <td>'.$row->line4.'</td>
        <td>'.$row->line4.'</td>
        <td>'.$row->id.'</td>
    </tr>
    ';
}
*/


   </table> -->

   <div class="content-wrapper">
    <section class="content">  
    <div class="col-md-12 col-xs-12">  
    <div class="d-flex justify-content-end">
        <a href="<?php echo base_url('index.php/Dashboardcontroller'); ?>" class="btn-sm btn btn-danger"><i class="fas fa-backward"></i> Back</a>
    </div>
        <div class="row">
            <div class="col-md-4 col-xs-4">
               
                <div class="box">
                    <div class="box-header with-border text-center" style="margin-bottom:50px;">
                        <!-- <h3 class="box-title">Download Sales Report for the Current Month</h3> -->
                    </div>
                    <div class="box-body">
                        <div class="text-center">
                        <b><h5 class="box-title">Current Month Sales</h5></b>
                            <form method="post" action="<?php echo base_url(); ?>index.php/Excel_export/action">
                                <button type="submit" name="export" class="btn btn-danger btn-md"><b>SALES <?php echo strtoupper(date('F')); ?></b></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8 col-xs-8" style="margin-top:50px;">
                <div class="box">
                    <div class="box-body" style="text-align: center;">
                        <b><h5 class="box-title">Sales by date </h5></b>
                        <form action="<?php echo base_url(); ?>index.php/Excel_export/actiondate" method="post" target="_blank" style="display: inline-block;">
                            <div class="form-group"   >
                             
                                <input type="date" style="width: 100%;" id="sales_date" name="sales_date" class="form-control" required>
                            </div><br>
                            <button type="submit" class="btn btn-danger"><i class="fas fa-print"></i> <b>DOWNLOAD SALES</b></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
</div>
 
</body>
</html>