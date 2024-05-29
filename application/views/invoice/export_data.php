
<html>
<head>
    <style>
        .box-title{
            padding:10px;
        }
    </style>
</head>
<body>

   <div class="content-wrapper">
    <section class="content">  
    <div class="col-md-12 col-xs-12">  
    <div class="d-flex justify-content-end">
        <a href="<?php echo base_url('index.php/Dashboardcontroller'); ?>" class="btn-sm btn btn-danger"><i class="fas fa-backward"></i> Back</a>
    </div>
        <div class="row">
            <div class="col-md-3 col-xs-3">
               
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

            <div class="col-md-3 col-xs-3" style="margin-top:50px;">
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


            <div class="col-md-6 col-xs-6" style="margin-top:50px;">
                <div class="box">
                    <div class="box-body" style="text-align: center;">
                        <b><h5 class="box-title">Sales by date range</h5></b>
                        <form action="<?php echo base_url(); ?>index.php/Excel_export/actiondaterange" method="post" target="_blank" style="display: inline-block;">
                    <div class="form-group">
                        <label for="start_date">Start Date:</label>
                        <input type="date" style="width: 100%;" id="start_date" name="start_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="end_date">End Date:</label>
                        <input type="date" style="width: 100%;" id="end_date" name="end_date" class="form-control" required>
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