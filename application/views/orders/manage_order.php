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
            <form action="<?= base_url('index.php/Orders/searchinvoice'); ?>" method="get">
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
    <form id="searchorderdate" action="<?= base_url('index.php/Orders/searchorderdate'); ?>" method="get">
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
    <form id="searchForm" action="<?= base_url('index.php/Orders/searchdate'); ?>" method="get">
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

     

<div class="col-sm-4 col-md-4">
    <form action="<?= base_url('index.php/Orders/searchbycustomer'); ?>" method="get">
        <label><b>Search by Customer</b></label>
        <select name="user_id" class="form-control" required onchange="this.form.submit()">
            <option value="">Select User Name</option>
            <?php foreach ($userss as $row): ?>
                <?php if($row->role != 'Owner'): ?>
                    <option value="<?= $row->id; ?>" <?= ($user_id ?? '') == $row->id ? 'selected' : ''; ?>>
                        <?= $row->name; ?>
                    </option>
                <?php endif; ?>
            <?php endforeach; ?>
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
                                            <th>Invoice no</th>
                                            <th>Customer Name</th>
                                            <th>Order Date and Time</th>
                                            <th>Delivery Date</th>
                                            <th>Gross Amount</th>
                                            <th>Slicing Service</th>
                                            <th>Delivery Charge</th>
                                            <th>GST</th>
                                            <th>Net Amount</th>
                                            
                                            <?php if ($show_payment_column): ?>
                                            <th>Payment Paid</th>
                                            <?php endif; ?>
                                            <th>Created By</th>
                                            <th>Updated By</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <?php 
                                        if(!empty($orders)){
                                            foreach ($orders as $key => $val):
                                            
                                                $show_checkbox = ($val->check_paystatus == 1);
                                                  $highlight_row = ($loginuser['id'] == 1229 && $val->pay_restrict == 1);
                                            ?>
                                           <tr <?= $highlight_row ? 'style="background-color: #f8d7da;"' : '' ?>>
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
                                               <?php if ($show_payment_column): ?>
                                                   <td>
                                                    <?php if ($show_checkbox): ?>
                                                                <input type="checkbox" class="payment-checkbox"
                                                                    data-order-id="<?= $val->id ?>"
                                                                    data-customer-id="<?= $val->user_id ?>"
                                                                    <?= ($val->account_paid == '1') ? 'checked' : '' ?>>
                                                            <?php else: ?>
                                                                -
                 

                                           <?php endif; ?>
                                                        </td>
                                                 
                                                <?php endif; ?>



                                             <td><?php echo $val->created_by; ?></td>
                                            <td><?php echo $val->updated_by; ?></td>
                                            <td>
                                                <a target="__blank"
                                                    href="<?php echo base_url('index.php/orders/printadmin/'.$val->id); ?>"
                                                    class="btn-sm btn btn-warning"><i
                                                        class="fas fa-print"></i></a>
                                                        <a href="<?php echo base_url('index.php/orders/update/'.$val->id.'/'.$val->user_id); ?>" class="btn-sm btn btn-info"><i class="fas fa-edit"></i></a>

                                            <a href="<?php echo base_url('index.php/orders/deleteorder/'.$val->id); ?>"
                                                class="btn-sm btn btn-danger delete-order">
                                                <i class="fas fa-trash"></i>
                                                </a>



                                            </td>
                                           
                                        </tr>
                                        <?php endforeach; } 
                                    else{
                                        echo '<tr><td colspan="13" class="text-center">No orders found for this user</td></tr>';
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
    <div class="modal fade" tabindex="-1" role="dialog" id="removeModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Remove Order</h4>
                </div>

                <form role="form" action="<?php echo base_url('index.php/orders/remove') ?>" method="post"
                    id="removeForm">
                    <div class="modal-body">
                        <p>Do you really want to remove?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </form>


            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
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
                'ajax': base_url + 'orders/fetchOrdersData',
                'order': []
            });

        });

        // remove functions 
        function removeFunc(id) {
            if (id) {
                $("#removeForm").on('submit', function () {

                    var form = $(this);

                    // remove the text-danger
                    $(".text-danger").remove();

                    $.ajax({
                        url: form.attr('action'),
                        type: form.attr('method'),
                        data: {
                            order_id: id
                        },
                        dataType: 'json',
                        success: function (response) {

                            manageTable.ajax.reload(null, false);

                            if (response.success === true) {
                                $("#messages").html('<div class="alert alert-success alert-dismissible" role="alert">' +
                                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                                    '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>' + response.messages +
                                    '</div>');

                                // hide the modal
                                $("#removeModal").modal('hide');

                            } else {

                                $("#messages").html('<div class="alert alert-warning alert-dismissible" role="alert">' +
                                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                                    '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>' + response.messages +
                                    '</div>');
                            }
                        }
                    });

                    return false;
                });
            }
        }
       
    </script>
    
<script>
   document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.delete-order');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();

            Swal.fire({
                title: "Are you sure?",
                text: "You want to delete this order?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Ask for remarks
                    Swal.fire({
                        title: "Remarks",
                        input: "text",
                        inputLabel: "Enter reason for deletion",
                        inputPlaceholder: "Remarks...",
                        inputAttributes: {
                            maxlength: 100
                        },
                        showCancelButton: true,
                        confirmButtonText: "Submit",
                        cancelButtonText: "Cancel",
                        preConfirm: (remarks) => {
                            if (!remarks) {
                                Swal.showValidationMessage('Remarks are required!');
                            }
                            return remarks;
                        }
                    }).then((remarksResult) => {
                        if (remarksResult.isConfirmed && remarksResult.value) {
                            // Append remarks to URL
                            const url = new URL(button.href);
                            url.searchParams.append("remarks", remarksResult.value);
                            window.location.href = url.toString();
                        }
                    });
                }
            });
        });
    });
});



document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.payment-checkbox').forEach(function (checkbox) {
        checkbox.addEventListener('change', function (e) {
            const orderId = this.getAttribute('data-order-id');
            const customerId = this.getAttribute('data-customer-id');
            const isChecked = this.checked;

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to change paid status?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, confirm it!'
            }).then((result) => {
                if (result.isConfirmed) {

                    console.log('suanya');
                    // Make an AJAX call to update the order and check invoices
                  fetch('<?= base_url("index.php/orders/update_paid_status") ?>', {   // NEW

                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ order_id: orderId, customer_id: customerId, account_paid: isChecked ? 1 : 0  })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {

                            Swal.fire('Updated!', data.message, 'success');
                        } else {
                            Swal.fire('Error!', data.message, 'error');
                            checkbox.checked = !isChecked; // revert checkbox
                        }
                    });
                } else {
                    checkbox.checked = !isChecked; // revert checkbox
                }
            });
        });
    });
});
</script>
</body>

</html>
