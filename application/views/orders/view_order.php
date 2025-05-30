

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
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('success'); ?>
          </div>
        <?php elseif($this->session->flashdata('errors')): ?>
          <div class="alert alert-error alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('errors'); ?>
          </div>
        <?php endif;
         ?>
              <?php
                    $user = $this->session->userdata('normal_user');
                    $user_id = $user->id;
                    $sql = "select * from user_register where id=".$user_id;
                    $query = $this->db->query($sql);
                    $restrict_time = $query->row()->restrict_time;
                    $pay_restrict = $query->row()->pay_restrict;
                      // Get the current time
                      date_default_timezone_set('Asia/Singapore');
                      $current_time = date("H:i");

                      // Define the start and end times for the restriction (assuming 23:00 to 06:00 in this example)
                      $start_time = "21:00";
                      $end_time = "24:00"; 
                      
                    
                      if ($restrict_time == 1 && (($current_time >= $start_time) && ($current_time <= $end_time))) { 
                          // Time is within the restricted range, redirect to order_restrict page
                          $url = base_url('index.php/orders/order_restrict');
                        } else if ($restrict_time == 0 && (($current_time >= $start_time) && ($current_time <= $end_time))) {
                          // Time is outside the restricted range, allow creating orders
                          $url = base_url('index.php/orders/create');
                      }
                      else{
                        $url = base_url('index.php/orders/create');
                      }
                   
                    if ($pay_restrict == 1) {
                        
                          $url = base_url('index.php/orders/pay_restrict');
                      }
                    ?>
          <a href="<?php echo $url; ?>" class="btn btn-success">Add Order</a>
          <br /> <br />


        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Manage Orders</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
    <table id="manageTable" class="table table-bordered table-hover table-striped">
        <thead>
            <tr>
                <th>Invoice no</th>
                <th>Order Date</th>
                <th>Delivery Date</th>
                <th>Gross Amount</th>
                <th>Slicing Service</th>
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
            <td><?php echo $val->created_date; ?></td>
            <td><?php echo $val->delivery_date; ?></td>
            <td><?php echo $val->gross_amount; ?></td>
            <td><?php if( $val->service_charge_rate == NULL){
              echo "No service charge";
            } else { echo $val->service_charge_rate; } ?></td>          
            <td><?php echo $val->delivery_charge; ?></td>
            <td><?php echo $val->gst_amt; ?></td>
            <td><?php echo $val->net_amount; ?></td>
            <td><a target="__blank" href="<?php echo base_url('index.php/orders/printDiv/'.$val->id); ?>" class="btn-sm btn btn-warning"><i class="fas fa-print"></i></a>
            <!-- <a href="//echo base_url('index.php/orders/update/'.$val->id); ?>" class="btn-sm btn btn-info"><i class="fas fa-edit"></i></a> -->
            <?php
                    $user = $this->session->userdata('normal_user');
                    $user_id = $user->id;
                    $sql = "select * from user_register where id=".$user_id;
                    $query = $this->db->query($sql);
                    $restrict_time = $query->row()->restrict_time;

                      $pay_restrict = $query->row()->pay_restrict;
                      // Get the current time
                      date_default_timezone_set('Asia/Singapore');
                      $current_time = date("H:i");

                      // Define the start and end times for the restriction (assuming 23:00 to 06:00 in this example)
                      $start_time = "21:00";
                      $end_time = "24:00"; 
                      
                    
                      if ($restrict_time == 1 && (($current_time >= $start_time) && ($current_time <= $end_time))) { 
                          // Time is within the restricted range, redirect to order_restrict page
                          $url = base_url('index.php/orders/order_restrict');
                        } else if ($restrict_time == 0 && (($current_time >= $start_time) && ($current_time <= $end_time))) {
                          // Time is outside the restricted range, allow creating orders
                          $url = base_url('index.php/orders/repeat_order/'.$val->id);
                      }
                      else{
                        $url = base_url('index.php/orders/repeat_order/'.$val->id);
                      }
                   if ($pay_restrict == 1) { 
                          // Time is within the restricted range, redirect to order_restrict page
                          $url = base_url('index.php/orders/pay_restrict');
                        }
                  
                    ?>
            <a href="<?php echo $url; ?>" class="btn-sm btn btn-info"><i class="fas fa-repeat"></i> Repeat Order</a></td>
            <!--<button type="button" class="btn btn-danger" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>
          </td> -->
        </tr>
    <?php endforeach; } 
    else{
      echo '<tr><td colspan="9" class="text-center">No orders found for this user</td></tr>';

    }
    ?>
</tbody>

    </table>
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
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Remove Order</h4>
      </div>

      <form role="form" action="<?php echo base_url('index.php/orders/remove') ?>" method="post" id="removeForm">
        <div class="modal-body">
          <p>Do you really want to remove?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Save changes</button>
        </div>
      </form>


    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->




<script type="text/javascript">
var manageTable;
var base_url = "<?php echo base_url(); ?>";

$(document).ready(function() {

  $("#OrderMainNav").addClass('active');
  $("#manageOrderSubMenu").addClass('active');

  // initialize the datatable 
  manageTable = $('#manageTable').DataTable({
    'ajax': base_url + 'orders/fetchOrdersData',
    'order': []
  });

});

// remove functions 
function removeFunc(id)
{
  if(id) {
    $("#removeForm").on('submit', function() {

      var form = $(this);

      // remove the text-danger
      $(".text-danger").remove();

      $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        data: { order_id:id }, 
        dataType: 'json',
        success:function(response) {

          manageTable.ajax.reload(null, false); 

          if(response.success === true) {
            $("#messages").html('<div class="alert alert-success alert-dismissible" role="alert">'+
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
              '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>'+response.messages+
            '</div>');

            // hide the modal
            $("#removeModal").modal('hide');

          } else {

            $("#messages").html('<div class="alert alert-warning alert-dismissible" role="alert">'+
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
              '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>'+response.messages+
            '</div>'); 
          }
        }
      }); 

      return false;
    });
  }
}


</script>
