
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
            

        <!-- <div class="col-md-12 col-xs-12" style="margin-top:50px;">
    <div class="box">
        <div class="box-body" style="text-align: center;">
            <h5 class="box-title"><b>SALES SUMMARY ITEM WISE BY MONTH</b></h5>
            <form action="<?php //echo base_url('index.php/Dashboardcontroller/action'); ?>" method="post" target="_blank" style="display: inline-block;">
                <div class="form-group">
                    <?php
                    // Get the current month in YYYY-MM format
                    $currentMonth = date('Y-m');
                    ?>
                    <input type="month" style="width: 100%;" id="sales_month" name="sales_month" class="form-control" required value="<?php echo $currentMonth; ?>">
                </div><br>
                <button type="submit" class="btn btn-danger"><b>SALES SUMMARY ITEM WISE</b></button>
            </form>
        </div>
    </div>
</div> -->
<div class="col-md-6 col-xs-6" style="margin-top:50px;">
    <div class="box">
        <div class="box-body" style="text-align: center;">
            <h5 class="box-title"><b>SALES SUMMARY CATEGORY & ITEM WISE BY MONTH</b></h5>
            <form action="<?php echo base_url('index.php/Dashboardcontroller/action_category'); ?>" method="post" target="_blank" style="display: inline-block;">
                <div class="form-group">
                    <?php
                    // Get the current month in YYYY-MM format
                    $currentMonth = date('Y-m');
                    ?>
                    <input type="month" style="width: 100%;" id="sales_month" name="sales_month" class="form-control" required value="<?php echo $currentMonth; ?>">
                </div><br>
                <button type="submit" class="btn btn-danger"><b>SALES SUMMARY</b></button>
            </form>
        </div>
        

        
    </div>
</div>

<div class="col-md-6 col-xs-6" style="margin-top:50px;">
    <div class="box">
<div class="box-body" style="text-align: center;">
    <h5 class="box-title"><b>SALES SUMMARY CATEGORY & ITEM WISE BY DATE RANGE</b></h5>
    <form action="<?php echo base_url('index.php/Dashboardcontroller/action_date_range'); ?>" method="post" target="_blank" style="display: inline-block;">
        <div class="form-group">
            <?php
            // Get the current date in YYYY-MM-DD format
            $currentDate = date('Y-m-d');
            ?>
            <label for="start_date"><b>Start Date:</b></label>
            <input type="date" style="width: 100%;" id="start_date" name="start_date" class="form-control" required value="<?php echo $currentDate; ?>">
        </div><br>
        <div class="form-group">
            <label for="end_date"><b>End Date:</b></label>
            <input type="date" style="width: 100%;" id="end_date" name="end_date" class="form-control" required value="<?php echo $currentDate; ?>">
        </div><br>
        <button type="submit" class="btn btn-danger"><b>SALES SUMMARY</b></button>
    </form>
</div>
    </div></div>



        </div>
    </div>
    </section>
</div>
 
</body>
</html>