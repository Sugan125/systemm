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

.highlight-invoice {
    background-color: yellow;
    font-weight: bold;
    padding: 2px 4px;
    border-radius: 3px;
}

    </style>
</head>

<body>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        <!-- Main content -->
        <section class="content">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-md-12 col-xs-12">

                    <div id="messages"></div>

                    <?php if($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <?php echo $this->session->flashdata('success'); ?>
                    </div>
                    <?php elseif($this->session->flashdata('errors')): ?>
                    <div class="alert alert-error alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <?php echo $this->session->flashdata('errors'); ?>
                        
                    </div>
                    <?php elseif($this->session->flashdata('deleted')): ?>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <?php echo $this->session->flashdata('deleted'); ?>
                    </div>
                    <?php endif; ?>       
                    <!-- <a href="<?php echo base_url('index.php/orders/create') ?>" class="btn btn-success">Add
                        Order</a> -->
                    <!-- <br /> <br /> -->

                    <div class="d-flex justify-content-end">
            <a href="<?php echo base_url('index.php/Dashboardcontroller'); ?>" class="btn-sm btn btn-danger"><i class="fas fa-backward"></i> Back</a>
        </div>


                    <div class="box">

                    <div class="col-md-12" style="margin-bottom:20px;">
                        <div class="box-header">
                            <h3 class="box-title">Manage Orders</h3>
                        </div>
                    </div>
<br>
                    <div class="col-md-12" style="margin-bottom:20px;">
                    <div class="col-sm-4 col-md-4">
            <form action="<?= base_url('index.php/orders/searchpaymentinvoice'); ?>" method="get">
                 <label><b>Search by Invoice/Customer Name</b></label>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search by Invoice No. or Customer Name" name="keyword" value="<?= isset($keyword) ? $keyword : '' ?>">
                    <div class="input-group-append">
                        <button class="btn btn-sm" type="submit"><i class="fas fa-search"></i> Search</button>
                    </div>
                </div>

            </form>
            </div>
            <?php
                // Initialize variables and ensure the correct date format
                $date = isset($date) ? date('Y-m-d', strtotime($date)) : '';
                $orderdate = isset($orderdate) ? date('Y-m-d', strtotime($orderdate)) : '';
                ?>

                    
                <div class="col-sm-4 col-md-4">
                    <form action="<?= base_url('index.php/orders/get_restricted_users_with_invoices'); ?>" method="get">
                        <label><b>Search by Payment Terms</b></label>
                        <select name="payment_terms" class="form-control" onchange="this.form.submit()">
                            <option value="">Select Payment Terms</option>
                            <option value="COD" <?= ($payment_terms ?? '') == 'COD' ? 'selected' : ''; ?>>COD</option>
                            <option value="14 Days" <?= ($payment_terms ?? '') == '14 Days' ? 'selected' : ''; ?>>14 Days</option>
                            <option value="30 Days" <?= ($payment_terms ?? '') == '30 Days' ? 'selected' : ''; ?>>30 Days</option>
                        </select>
                    </form>
                </div>

 
                    </div>
                        <br>
                        
                        <div class="col-md-12">
                        <!-- /.box-header -->
                        <div class="box-body">
                        <div class="table-wrapper">
                             <?php
                                $allowed_ids = ['1234', '1238','1229'];

                              // print_r($loginuser);
                                $show_payment_column = in_array($loginuser['id'], $allowed_ids) && in_array('Owner', (array)$loginuser['role']);
                                

                            ?>
                            <table id="manageTable" class="table table-bordered table-hover table-striped">
                            <thead>
                             <tr>
                    <th>Customer Name</th>
                    <th>Payment Terms</th>
                    <th>Unpaid Invoices</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <?php 
                                        $keyword = strtolower(trim($keyword ?? '')); 
                                        if(!empty($orders)){
                                            foreach ($orders as $key => $val):
                                            
                                            ?>
                                           <tr>
                                              <td><?= htmlspecialchars($val['name']) ?></td>
                                                <td><?= htmlspecialchars($val['payment_terms']) ?></td>
                                           <td>
    <?php
    if (!empty($val['invoices'])) {
        $formatted_invoices = [];

        foreach ($val['invoices'] as $invoice) {
            $highlight = in_array($invoice, $val['highlight_invoices'] ?? []) 
                         ? 'style="background-color: yellow; font-weight: bold;"' 
                         : '';
            $formatted_invoices[] = "<span $highlight>$invoice</span>";
        }

        echo implode(', ', $formatted_invoices);
    } else {
        echo 'None';
    }
    ?>
</td>



                                                                
                                        </tr>
                                        <?php endforeach; } 
                                    else{
                                        echo '<tr><td colspan="13" class="text-center">No orders found</td></tr>';
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                                </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- col-md-12 -->
            </div>
            <!-- /.row -->
            <div class="col-md-12">
           
    <div class="col-sm-6 d-flex justify-content-start">
        <?php $total_rowss = $total_rows; echo "Showing 1 to 10 of ".$total_rowss." entries"; ?>
    </div>
    <div class="col-sm-6 d-flex justify-content-end">
        <?php echo $this->pagination->create_links(); ?>
    </div>
</div>

        </section>
        <!-- /.content -->
    </div>
   
    
 
</body>

</html>
