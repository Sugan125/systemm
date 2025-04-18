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
            <form action="<?= base_url('index.php/Orders/searchdeletedinvoice'); ?>" method="get">
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
    <form id="searchorderdate" action="<?= base_url('index.php/Orders/searchdeletedorderdate'); ?>" method="get">
        <div class="col-sm-6 col-md-6">
            <label><b>Search by Order Date</b></label>
        </div>
        <div class="col-sm-6 col-md-6">
            <div class="input-group">
                <input type="date" class="form-control orderdatepicker" placeholder="Search by Order Date" name="orderdate" value="<?= $orderdate ?>">
            </div>
        </div>
    </form>
</div>

<div class="col-sm-4 col-md-4">
    <form id="searchForm" action="<?= base_url('index.php/Orders/searchdeletedate'); ?>" method="get">
        <div class="col-sm-7 col-md-7">
            <label><b>Search by Delivery Date</b></label>
        </div>
        <div class="col-sm-5 col-md-5" style="padding:0px;">
            <div class="input-group">
                <input type="date" class="form-control datepicker" placeholder="Search by Delivery Date" name="date" value="<?= $date ?>">
            </div>
        </div>
    </form>
</div>

     


 
                    </div>
                        <br>
                        
                        <div class="col-md-12">
                        <!-- /.box-header -->
                        <div class="box-body">
                        <div class="table-wrapper">
                            <table id="manageTable" class="table table-bordered table-hover table-striped">
                            <thead>
                             <tr>
                                            <th>Invoice no</th>
                                            <th>Customer Name</th>
                                            <th>Order Date and Time</th>
                                            <th>Delivery Date</th>
                                            <th>Gross Amount</th>
                                            <th>Slicing Service</th>
                                            <th>Delivery Charge</th>
                                            <th>GST</th>
                                            <th>Net Amount</th>
                                            <th>Created By</th>
                                            <th>Updated By</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                    if(!empty($orders)){
                                        foreach ($orders as $key => $val):
                                        ?>
                                        <tr>
                                            <td><?php echo $val->bill_no; ?></td>
                                            <td><?php echo $val->name; ?></td>
                                            <td><?php echo empty($val->created_date) ? date('Y-m-d', $val->date_time) : $val->created_date; ?></td>
                                            <td><?php echo $val->delivery_date; ?></td>
                                            <td><?php echo $val->gross_amount; ?></td>
                                            <td><?php if( $val->service_charge_rate == NULL){
                                                echo "No service charge";
                                            } else { echo $val->service_charge_rate; } ?></td>
                                            <td><?php echo $val->delivery_charge; ?></td>
                                            <td><?php echo $val->gst_amt; ?></td>
                                            <td><?php echo $val->net_amount; ?></td>
                                            <td><?php echo $val->created_by; ?></td>
                                            <td><?php echo $val->updated_by; ?></td>
                                            <td>
                                               
                                                       
                                                <a href="<?php echo base_url('index.php/orders/revertorder/'.$val->id); ?>"
                                                    class="btn-sm btn btn-danger delete-order">
                                                    <i class="fa fa-undo"></i>Revert Delete

                                                </a>


                                            </td>
                                           
                                        </tr>
                                        <?php endforeach; } 
                                    else{
                                        echo '<tr><td colspan="12" class="text-center">No orders found for this user</td></tr>';
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
   
    <!-- /.content-wrapper -->
    <!-- remove brand modal -->
   
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
  // Trigger form submission on date selection
  $('.datepicker').on('change', function() {
        $('#searchForm').submit(); // Submit the form
    });

    $('.orderdatepicker').on('change', function() {
        $('#searchorderdate').submit(); // Submit the form
    });
        var manageTable;
        var base_url = "<?php echo base_url(); ?>";

        $(document).ready(function () {

            $("#OrderMainNav").addClass('active');
            $("#manageOrderSubMenu").addClass('active');

            // initialize the datatable 
            manageTable = $('#manageTable').DataTable({
                'ajax': base_url + 'orders/fetchdeleteOrdersData',
                'order': []
            });

        });

        // remove functions 
        
       
    </script>
    
<script>
     document.addEventListener('DOMContentLoaded', function () {
        // Select the delete-order class
        const deleteButtons = document.querySelectorAll('.delete-order');

        // Add event listener to each delete button
        deleteButtons.forEach(button => {
    button.addEventListener('click', function (event) {
        event.preventDefault(); // Prevent the default action

        // Show SweetAlert confirmation dialog
        Swal.fire({
            title: "Are you sure?",
            text: "You want to revert this order?",
            icon: "warning",
            showCancelButton: true,  
            confirmButtonText: "Revert",  
            cancelButtonText: "Cancel",  
            confirmButtonColor: "#3085d6", 
            cancelButtonColor: "#d33",  
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with form submission
                deleteOrder(button.href);
            }
        });
            });
        });

        // Function to redirect to the delete URL
        function deleteOrder(url) {
            window.location.href = url;
        }
    });
</script>

</body>

</html>
