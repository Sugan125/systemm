<html>
<head>
        <style>
        .box {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #fff;
            width: 100%;
            max-width: 500px; /* Adjust max-width */
            text-align: center;
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
    </div>
        
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="row w-100 justify-content-center">
        <div class="col-md-6">
            <div class="box">
                <h5 class="box-title"><b>SALES SUMMARY ITEM WISE WEEKLY REPORT</b></h5><br>
                <form action="<?php echo base_url('index.php/Dashboardcontroller/production_report_weekly'); ?>" method="post" target="_blank">
    <div class="form-group">
        <?php
        // Get current date and time
        $currentDate = date('Y-m-d');
        $currentTime = date('H:i:s');
        $currentDayOfWeek = date('w'); // 0=Sunday, 6=Saturday

        // Find last Monday
        $lastMonday = date('Y-m-d', strtotime('monday last week'));
        // Find last Saturday
        $lastSaturday = date('Y-m-d', strtotime($lastMonday . ' +5 days'));

        // Find next Monday (for date switch at 12:00 AM Sunday)
        $nextMonday = date('Y-m-d', strtotime('monday this week'));
        $nextSaturday = date('Y-m-d', strtotime($nextMonday . ' +5 days'));

        // If **current time is after Saturday 11:59 PM (Sunday 12:00 AM),** update to next week
        if ($currentDayOfWeek == 0 && $currentTime >= "00:00:00") { // If it's Sunday past midnight
            $weekStart = $nextMonday;
            $weekEnd = $nextSaturday;
        } else {
            $weekStart = $lastMonday;
            $weekEnd = $lastSaturday;
        }
        ?>

       
        <input type="date"  id="start_date" name="start_date" class="form-control" required 
               value="<?php echo $weekStart; ?>" >
    </div>
    <br>
    <div class="form-group">
        
        <input type="date"  id="end_date" name="end_date" class="form-control" required 
               value="<?php echo $weekEnd; ?>" >
    </div>
    <br>
    <button type="submit" class="btn btn-danger w-100"><b>WEEKLY REPORT</b></button>
</form>

            </div>
        </div>
    </div>
</div>

    <section>
</div>
</body>
</html>
