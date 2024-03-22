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
                    <?php endif; ?>
                    <!-- <a href="<?php echo base_url('index.php/orders/create') ?>" class="btn btn-success">Add
                        Order</a> -->
                    <!-- <br /> <br /> -->

                    <div class="d-flex justify-content-end">
            <a href="<?php echo base_url('index.php/Dashboardcontroller'); ?>" class="btn-sm btn btn-danger"><i class="fas fa-backward"></i> Back</a>
        </div>
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Manage Orders</h3>
                        </div><br>
                        <!-- /.box-header -->
                        <div class="box-body">
                        <div class="table-wrapper">
                            <table id="manageTable" class="table table-bordered table-hover table-striped">
                            <thead>
                             <tr>
                                            <th>Invoice no</th>
                                            <th>Customer Name</th>
                                            <th>Date Time</th>
                                            <th>Gross Amount</th>
                                            <th>Slicing Service</th>
                                            <th>Discount</th>
                                            <th>Delivery Charge</th>
                                            <th>GST</th>
                                            <th>Net Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                    if(!empty($orders)){
                                        foreach ($orders as $key => $val): ?>
                                        <tr>
                                            <td><?php echo $val->bill_no; ?></td>
                                            <td><?php echo $val->name; ?></td>
                                            <td><?php echo date('Y-m-d', $val->date_time); ?></td>
                                            <td><?php echo $val->gross_amount; ?></td>
                                            <td><?php if( $val->service_charge_rate == NULL){
                                                echo "No service charge";
                                            } else { echo $val->service_charge_rate; } ?></td>

                                            <td><?php if($val->discount == '' || $val->discount == NULL || $val->discount == 0)
                                                { echo 'Discount not applied'; }else{
                                                    echo $val->discount;}?></td>
                                            <td><?php echo $val->delivery_charge; ?></td>
                                            <td><?php echo $val->gst_amt; ?></td>
                                            <td><?php echo $val->net_amount; ?></td>
                                            <td>
                                                <a target="__blank"
                                                    href="<?php echo base_url('index.php/orders/printadmin/'.$val->id); ?>"
                                                    class="btn-sm btn btn-warning"><i
                                                        class="fas fa-print"></i></a>
                                                <!-- <a href="<?php// echo base_url('index.php/orders/update/'.$val->id); ?>"
                                                    class="btn-sm btn btn-info"><i
                                                        class="fas fa-edit"></i></a> -->
                                                        <a target="__blank"
                                                    href="<?php echo base_url('index.php/orders/download/'.$val->id); ?>"
                                                    class="btn-sm btn btn-warning"><i
                                                        class="fas fa-download"></i></a>
                                            </td>
                                           
                                        </tr>
                                        <?php endforeach; } 
                                    else{
                                        echo '<tr><td colspan="9" class="text-center">No orders found for this user</td></tr>';
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- col-md-12 -->
            </div>
            <!-- /.row -->
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
    <!-- /.modal -->

    <script type="text/javascript">
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

</body>

</html>
