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
                            <h3 class="box-title">Download Agreement By Customer</h3>
                        </div><br>
                      
                        <div class="box">
                       
                        <div class="box-body" style="text-align: center;">
                        <form action="<?php echo base_url('index.php/orders/downloadagreemnt/'); ?>" method="post" target="_blank" style="display: inline-block;">
                        <label for="schedule_date"><b>Select Customer:</b></label>
                        <div class="input-group mb-3" id="input_size">
                    <div class="input-group-prepend">
                        <span class="input-group-text">@</span>
                    </div>
                    <select name="user_name" class="form-control" required>
                        <option value="">Select Customer</option>
                        <?php foreach ($userss as $row) :
                        if($row->role != 'Owner') {?>
                            <option name="user_name" value="<?= $row->name; ?>"><?= $row->name; ?></option>
                            <?php } ?>
                        <?php endforeach; ?>
                    </select>
                </div><br>
                            
                           
                            
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-print"></i> <b>DOWNLOAD</b>
                            </button>
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
