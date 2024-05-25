<head>
    <style>
.box-body {
    overflow-x: auto;
}

.table-wrapper {
    width: 100%; /* Ensure the wrapper takes full width */
    overflow-x: auto; /* Enable horizontal scrolling */
    white-space: nowrap; /* Prevent line breaks */
}

/* Ensure the table takes up the entire width */
.table-wrapper table {
    width: 100%;
}


    </style>
</head>

<body>
    <div class="content-wrapper">

        <section class="content">    
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="d-flex justify-content-end">
                        <a href="<?php echo base_url('index.php/Dashboardcontroller'); ?>" class="btn-sm btn btn-danger"><i class="fas fa-backward"></i> Back</a>
                    </div>
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Download DO by date (Combined DO)</h3>
                        </div><br>
                        <div class="box-body" style="text-align: center;">
                        <form action="<?php echo base_url('index.php/orders/downloadcombined/'); ?>" method="post" target="_blank" style="display: inline-block;">
                            <div class="form-group"   >
                                <label for="schedule_date"><b>Select Date:</b></label>
                                <input type="date" style="width: 100%;" id="invoicee_date" name="invoicee_date" class="form-control" required>
                            </div><br>
                            <button type="submit" class="btn btn-danger"><i class="fas fa-print"></i> <b>DOWNLOAD COMBINED DO</b></button>
                        </form>

                        </div>    
                    </div>

<br><br><br>
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Download DO by date (Separated DO)</h3>
                        </div><br>
                        <div class="box-body" style="text-align: center;">
                        <form action="<?php echo base_url('index.php/orders/downaloaddo/'); ?>" method="post" target="_blank" style="display: inline-block;">
                            <div class="form-group"   >
                                <label for="schedule_date"><b>Select Date:</b></label>
                                <input type="date" style="width: 100%;" id="invoice_date" name="invoice_date" class="form-control" required>
                            </div><br>
                            <button type="submit" class="btn btn-danger"><i class="fas fa-print"></i> <b>DOWNLOAD SEPARATED DO</b></button>
                        </form>

                        </div>    
                    </div>

                </div>

            </div>
    
        </section>
  
    </div>
    <script>
   // Get today's date in local time zone
   var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
    var yyyy = today.getFullYear();

    today = yyyy + '-' + mm + '-' + dd;
    
    // Set the value of the date input field to today's date
    document.getElementById("invoice_date").value = today;
    document.getElementById("invoicee_date").value = today;
</script>
</body>

</html>
