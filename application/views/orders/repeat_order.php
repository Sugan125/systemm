

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
          <?php elseif($this->session->flashdata('error')): ?>
            <div class="alert alert-error alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <?php echo $this->session->flashdata('error'); ?>
            </div>
          <?php endif; ?>

          <div class="box-header">
              <h3 class="box-title">Repeat Order</h3>
            </div>
          <div class="box" style="margin-top:20px;">
            
            <!-- /.box-header -->
            
            <form role="form" id="repeat_order"  action="<?php base_url('orders/repeat_order') ?>" method="post" class="form-horizontal" onsubmit="confirmSubmission(event)">
            
                  
                <div class="box-body pull-right">

                  <?php echo validation_errors(); ?>

                  <div class="form-group">
                    <label for="gross_amount" class="col-sm-12 control-label">Date: <?php echo date('Y-m-d') ?></label>
                  </div>
                  <div class="form-group">
                    <label for="gross_amount" class="col-sm-12 control-label">Time: <?php date_default_timezone_set('Asia/Singapore'); echo  date('h:i a'); ?></label>
                  </div>
                
                  
                </div>

                
                  <table class="table table-bordered table-hover" id="product_info_table">
                    <thead>
                      <tr>
                        <th style="width:15%">Category</th>
                        <th style="width:30%">Product / Packing Size</th>
                        <th style="width:10%">Pre-Slice</th>
                        <th style="width:10%">Seed</th>
                        <th style="width:10%">Qty (Pkt)</th>
                        <th style="width:10%">Rate</th>
                        <th style="width:5%">Service_charge</th>
                        <th style="width:10%">Amount</th>
                        <!-- <th style="width:10%"><button type="button" id="add_row" class="btn btn-info"><i class="fa fa-plus"></i></button></th> -->
                      </tr>
                    </thead>

                    <tbody>

                      <?php if(isset($order_data['order_item'])): ?>
                        <?php $x = 1; ?>
                        <?php foreach ($order_data['order_item'] as $key => $val): ?>
                      
                        <tr id="row_<?php echo $x; ?>">
                        <td>
                        <select class="form-control category_name" data-row-id="row_1" id="category_1" name="category[]" onmousedown="if(this.options.length>8){this.size=8;}" onchange='this.size=0;' onblur="this.size=0;" disabled>
                                  <option value="">Choose</option>
                                  <?php foreach ($category as $key => $v): ?>
                                      <option value="<?php echo $v['prod_category'] ?>" <?php if ($val['category'] == $v['prod_category']) { echo "selected='selected'"; } ?>><?php echo $v['prod_category'] ?></option>
                                  <?php endforeach ?>
                              </select>
                          </td>
                          
                          <td>
                            <select class="form-control select_group product" data-row-id="row_<?php echo $x; ?>" id="product_<?php echo $x; ?>" name="product[]" style="width:100%;" onchange="getProductData(<?php echo $x; ?>)" required disabled>
                                <option value=""></option>
                                <?php foreach ($products as $k => $v):
                                  if ($v['min_order'] > 1) {
                                    $qty_pkt = ' (' . $v['min_order'] . ' pcs/pkt)';
                                    } else {
                                        $qty_pkt = '';
                                    }
                                  ?>
                                  <option value="<?php echo $val['product_id'] ?>" <?php if($val['product_id'] == $v['id']) { echo "selected='selected'"; } ?> >
                                  <?php echo $v['product_id'] . ' - ' . $v['product_name'] .$qty_pkt; ?></option>
                                <?php endforeach ?>
                              </select>
                            </td>

                            <td>    
                              <select disabled class="form-control sliced" id="sliced_<?php echo $x; ?>" name="sliced[]" onmousedown="if(this.options.length>8){this.size=8;}" onchange='slicechange()' onblur="this.size=0;">
                                  <option value="">Choose</option> <!-- Add a default "Choose" option -->
                                  <option value="Unsliced" <?php if ($val['slice_type'] == "Unsliced") { echo "selected='selected'"; } ?>>Unsliced</option>
                                  <option value="12mm" <?php if ($val['slice_type'] == "12mm") { echo "selected='selected'"; } ?>>12mm</option> <!-- Set selected if slice_type is 12mm -->
                                  <option value="20mm" <?php if ($val['slice_type'] == "20mm") { echo "selected='selected'"; } ?>>20mm</option> <!-- Set selected if slice_type is 20mm -->
                              </select>

                              </td>
                              <td>    
                                  <select disabled class="form-control seed" id="seed_<?php echo $x; ?>" name="seed[]" onmousedown="if(this.options.length>8){this.size=8;}" onchange='seedchange()' onblur="this.size=0;">
                                      <option value="">Choose</option>
                                      <option value="Seedless" <?php if ($val['seed_type'] == "Seedless") { echo "selected='selected'"; } ?>>Seedless</option>
                                      <option value="White drizzle" <?php if ($val['seed_type'] == "White drizzle") { echo "selected='selected'"; } ?>>White drizzle</option>
                                      <option value="White full seed" <?php if ($val['seed_type'] == "White full seed") { echo "selected='selected'"; } ?>>White full seed</option>
                                      <option value="Black drizzle" <?php if ($val['seed_type'] == "Black drizzle") { echo "selected='selected'"; } ?>>Black drizzle</option>  
                                      <option value="White black mix" <?php if ($val['seed_type'] == "White black mix") { echo "selected='selected'"; } ?>>White black mix</option>   
                                  </select>
                              </td>
                          
                            <td><input type="number" disabled name="qty[]" id="qty_<?php echo $x; ?>" class="form-control" required onkeyup="getTotal(<?php echo $x; ?>)" value="<?php echo $val['qty'] ?>" autocomplete="off"></td>
                            <td>
                              <input type="text" name="rate[]" id="rate_<?php echo $x; ?>" class="form-control" disabled value="<?php echo $val['rate'] ?>" autocomplete="off">
                              <input type="hidden" name="rate_value[]" id="rate_value_<?php echo $x; ?>" class="form-control" value="<?php echo $val['rate'] ?>" autocomplete="off">
                            </td>
                            <td hidden>
                            <input type="text" name="gst_percent[]" id="gst_percent_1" class="form-control" disabled  value="<?php echo $val['gst_percent'] ?>" autocomplete="off">
                            <input type="hidden" name="gst_percent_val[]" id="gst_percent_val_1" class="form-control"  value="<?php echo $val['gst_percent'] ?>" autocomplete="off">
                        </td>
                        <td>
                            <input type="text" name="service_charge_lineitem[]" id="service_charge_lineitem_1" value="<?php echo $val['service_charge'] ?>"  class="form-control" disabled autocomplete="off">
                            <input type="hidden" name="service_charge_itemval[]" id="service_charge_itemval_1"  value="<?php echo $val['service_charge'] ?>" class="form-control" autocomplete="off">
                        </td>
                        <td>
                              <input type="text" name="amount[]" id="amount_<?php echo $x; ?>" class="form-control" disabled value="<?php echo $val['amount'] ?>" autocomplete="off">
                              <input type="hidden" name="amount_value[]" id="amount_value_<?php echo $x; ?>" class="form-control" value="<?php echo $val['amount'] ?>" autocomplete="off">
                            </td>
                        <td hidden>
                            <input type="text" name="gst_amount[]" id="gst_amount_1" class="form-control"  value="<?php echo $val['gst_amount'] ?>" disabled autocomplete="off">
                            <input type="hidden" name="gst_amount_value[]" id="gst_amount_value_1" class="form-control" value="<?php echo $val['gst_amount'] ?>" autocomplete="off">
                        </td>
              
                            <!-- <td><button type="button" class="btn btn-danger" onclick="removeRow('<?php echo $x; ?>')"><i class="fa fa-close"></i></button></td> -->
                        </tr>
                        <?php $x++; ?>
                      <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                  </table>

                  <?php foreach ($order_total as $key => $order_data): ?>

                  <div class="col-sm-12 col-md-12 col-xs-12 pull pull-right">
                  <div class="col-sm-6 col-md-6">
                  <div class="form-group">
                
                <label for="feed_back" class="control-label">Feedback</label>
                <textarea class="form-control" id="feed_back" name="feed_back" autocomplete="off"><?php echo $order_data['feed_back'] ?></textarea>
                <br>
               <label>Delivery Date (Mandatory)</label>
                <input type="text" name="pre_order_date" id="pre_order" class="form-control"  autocomplete="off" required>
                <br>
                <label for="packer_memo" class="control-label">Packer Memo</label>
                <textarea class="form-control" id="packer_memo" name="packer_memo" autocomplete="off"><?php echo $order_data['pmemo'] ?></textarea>
               
                <br>

                <label for="driver_memo" class="control-label">Driver Memo</label>
                <textarea class="form-control" id="driver_memo" name="driver_memo" autocomplete="off"><?php echo $order_data['memo'] ?></textarea>

                <br>

                <label for="po_ref" class="control-label">PO ref</label>
                <input type="text" class="form-control" id="po_ref" name="po_ref"  value="<?php echo $order_data['po_ref'] ?>" autocomplete="off">
             
              
                </div>
                  </div>
                  <div class="col-sm-6 col-md-6">
                
                    <div class="form-group" style="margin-bottom:30px;">
                      <div class="col-sm-4">
                      <label for="gross_amount" class="control-label">Total</label></div>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" id="gross_amount" value="<?php echo $order_data['gross_amount'] ?>" name="gross_amount" disabled autocomplete="off">
                        <input type="hidden" class="form-control" id="gross_amount_value" value="<?php echo $order_data['gross_amount'] ?>" name="gross_amount_value" autocomplete="off">
                      </div>
                    </div><br>
                    
                    <div class="form-group"  style="margin-bottom:30px;">
                      <div class="col-sm-4">
                      <label for="service_charge" class="control-label">Slicing Service:   </label>
                      </div>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" id="service_charge" value="<?php echo $order_data['service_charge_rate'] ?>"  name="service_charge" disabled autocomplete="off">
                        <input type="hidden" class="form-control" id="service_charge_value"  value="<?php echo $order_data['service_charge_rate'] ?>" name="service_charge_value" autocomplete="off">
                      </div>
                    </div><br>
                    <!-- <div class="form-group" style="margin-bottom:30px;">
                      <div class="col-sm-4">
                      <label for="discount" class="control-label">Discount</label></div>
                      <div class="col-sm-8">
                        <input type="text" disabled class="form-control" id="discount" name="discount" value="<?php echo $order_data['discount'] ?>" placeholder="Discount" onkeyup="subAmount()" autocomplete="off">
                      </div>
                    </div><br> -->
                    <div class="form-group" style="margin-bottom:30px;">
                      <div class="col-sm-4">
                      <label for="discount" class="control-label">Delivery Charge</label></div>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" id="delivery_charge" value="<?php echo $order_data['delivery_charge'] ?>" name="delivery_charge" disabled  autocomplete="off">
                        <input type="hidden" class="form-control" id="delivery_charge_value" value="<?php echo $order_data['delivery_charge'] ?>" name="delivery_charge_value" autocomplete="off">
                      </div>
                    </div><br>
                    <div class="form-group" style="margin-bottom:30px;">
                      <div class="col-sm-4">
                      <label for="gross_amount" class="control-label">GST (9%)</label></div>
                      <div class="col-sm-8">
                      <input type="text" class="form-control" id="gst" name="gst_amt" value="<?php echo $order_data['gst_amt'] ?>" disabled autocomplete="off">
                        <input type="hidden" class="form-control" id="gst_value"  value="<?php echo $order_data['gst_percent'] ?>"  name="gst_value" value="9" autocomplete="off">
                        <input type="hidden" class="form-control" id="gst_rate" value="<?php echo $order_data['gst_amt'] ?>" name="gst_rate" value="9" autocomplete="off">
                      </div>
                    </div><br>
                    <div class="form-group">
                    <div class="col-sm-4">
                      <label for="net_amount" class="control-label">Grand Total</label></div>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" id="net_amount"  value="<?php echo $order_data['net_amount'] ?>" name="net_amount" disabled autocomplete="off">
                        <input type="hidden" class="form-control" id="net_amount_value"  value="<?php echo $order_data['net_amount'] ?>" name="net_amount_value" autocomplete="off">
                      </div>
                    </div>
                    <input type="hidden" name="shipping_address" id="shipping_address">
                  <input type="hidden" name="shipping_address_line2" id="shipping_address_line2">
                  <input type="hidden" name="shipping_address_line3" id="shipping_address_line3">
                  <input type="hidden" name="shipping_address_line4" id="shipping_address_line4">
                  <input type="hidden" name="shipping_address_city" id="shipping_address_city">
                  <input type="hidden" name="shipping_address_postcode" id="shipping_address_postcode">
                  <input type="hidden" name="created_by" id="created_by" value="<?php echo $loginuser['name']; ?>">
                  
                    </div>
                  </div>
                </div>
                <!-- /.box-body -->

                <div class="box-footer col-sm-12 col-md-12 col-xs-12 pull pull-left" style="margin-bottom:30px;">
                  <input type="hidden" id="delivery_charge" name="delivery_charge" autocomplete="off">  
                  <input type="hidden" name="service_charge_rate"  autocomplete="off">
                  <!-- <a target="__blank" href="<?php echo base_url() . 'index.php/orders/printDiv/'.$order_data['id'] ?>" class="btn btn-default" >Print</a> -->
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
                          $btn = 'disabled';
                          $text = '<div class="heading">🚀 Orders Opening at 12:01 AM! 🚀</div>
                          <p>🔔 Please wait patiently! 🔔</p>
                          <p>Our ordering system is currently closed after 9:00 PM.</p>';
                      } else if ($restrict_time == 0 && (($current_time >= $start_time) && ($current_time <= $end_time))) {
                          // Time is outside the restricted range, allow creating orders
                          $btn = '';
                          $text = '';
                      }
                      else{
                        $btn = '';
                        $text = '';
                      }
                      if ($pay_restrict == 1) {
                        
                          $btn = 'disabled';
                          $text = '<div class="heading">🚫 Ordering Temporarily Disabled 🚫</div>
                                  <p>💳 Kindly clear your outstanding payment to resume ordering.</p>
                                  <p>📞 Please contact our Customer Service or Accounts Team for assistance.</p>';
                      }
                  
                    ?>
                    <?php echo $text; ?>
                    <?php
                      if ($loginuser['address2'] != NULL || $loginuser['address3'] != NULL) {
                        $modal_id = '<a class="galName" href="#" onclick="openModal()">'; // Change href to "#" and onclick to call openModal()
                        $end_id = '</a>';
                        $btns = "button";
                      } else {
                        $modal_id = '';
                        $end_id = '';
                        $btns = "submit";
                      }
                    ?>

                    <?php echo $modal_id; ?>
                     <button id="submitBtn" type="<?php echo $btns; ?>" class="btn btn-success create_order" <?php echo $btn; ?>>
                    <span id="btnText">Repeat Order</span>
                    <i id="btnSpinner" class="fa fa-spinner fa-spin d-none" aria-hidden="true"></i>
                    </button>
                    <?php echo $end_id; ?> 
                  <a href="<?php echo base_url('index.php/orders/') ?>" class="btn btn-danger">Back</a>
                </div>
                <?php endforeach; ?>
              </form>
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

  <div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md"> <!-- Changed modal-dialog size to modal-sm for a smaller modal -->
        <div class="modal-content">
            <div class="modal-header">
                
                <h3>Please choose shipping address</h3>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <form id="shippingAddressForm">
                <div class="form-group">
                <label for="shippingAddress1">
                    <input type="checkbox" id="shippingAddress1" name="shippingAddress1" class="shippingAddressCheckbox">
                    <?php echo !empty($loginuser['delivery_address']) ? $loginuser['delivery_address'] : ''; ?>
                    <?php echo !empty($loginuser['delivery_address_line2']) ? ', ' . $loginuser['delivery_address_line2'] : ''; ?>
                    <?php echo !empty($loginuser['delivery_address_line3']) ? ', ' . $loginuser['delivery_address_line3'] : ''; ?>
                    <?php echo !empty($loginuser['delivery_address_line4']) ? ', ' . $loginuser['delivery_address_line4'] : ''; ?>
                    <?php echo !empty($loginuser['delivery_address_city']) ? ', ' . $loginuser['delivery_address_city'] : ''; ?>
                    <?php echo !empty($loginuser['delivery_address_postcode']) ? ', ' . $loginuser['delivery_address_postcode'] : ''; ?>
                </label>
                </div>
                <div class="form-group">
                    <label for="shippingAddress2">
                        <?php echo !empty($loginuser['address2']) ? '<input type="checkbox" id="shippingAddress2" name="shippingAddress2" class="shippingAddressCheckbox">' : ''; ?>
                        <?php echo !empty($loginuser['address2']) ? $loginuser['address2'] : ''; ?>
                        <?php echo !empty($loginuser['address2_line2']) ? ', ' . $loginuser['address2_line2'] : ''; ?>
                        <?php echo !empty($loginuser['address2_line3']) ? ', ' . $loginuser['address2_line3'] : ''; ?>
                        <?php echo !empty($loginuser['address2_line4']) ? ', ' . $loginuser['address2_line4'] : ''; ?>
                        <?php echo !empty($loginuser['address2_city']) ? ', ' . $loginuser['address2_city'] : ''; ?>
                        <?php echo !empty($loginuser['address2_postcode']) ? ', ' . $loginuser['address2_postcode'] : ''; ?>
                    </label>
                </div>
                <div class="form-group">
                    <label for="shippingAddress3">
                        <?php echo !empty($loginuser['address3']) ? '<input type="checkbox" id="shippingAddress3" name="shippingAddress3" class="shippingAddressCheckbox">' : ''; ?>
                        <?php echo !empty($loginuser['address3']) ? $loginuser['address3'] : ''; ?>
                        <?php echo !empty($loginuser['address3_line2']) ? ', ' . $loginuser['address3_line2'] : ''; ?>
                        <?php echo !empty($loginuser['address3_line3']) ? ', ' . $loginuser['address3_line3'] : ''; ?>
                        <?php echo !empty($loginuser['address3_line4']) ? ', ' . $loginuser['address3_line4'] : ''; ?>
                        <?php echo !empty($loginuser['address3_city']) ? ', ' . $loginuser['address3_city'] : ''; ?>
                        <?php echo !empty($loginuser['address3_postcode']) ? ', ' . $loginuser['address3_postcode'] : ''; ?>
                    </label>
                </div>
                <div class="form-group">
                    <label for="shippingAddress4">
                        <?php echo !empty($loginuser['address4']) ? '<input type="checkbox" id="shippingAddress4" name="shippingAddress4" class="shippingAddressCheckbox">' : ''; ?>
                        <?php echo !empty($loginuser['address4']) ? $loginuser['address4'] : ''; ?>
                        <?php echo !empty($loginuser['address4_line2']) ? ', ' . $loginuser['address4_line2'] : ''; ?>
                        <?php echo !empty($loginuser['address4_line3']) ? ', ' . $loginuser['address4_line3'] : ''; ?>
                        <?php echo !empty($loginuser['address4_line4']) ? ', ' . $loginuser['address4_line4'] : ''; ?>
                        <?php echo !empty($loginuser['address4_city']) ? ', ' . $loginuser['address4_city'] : ''; ?>
                        <?php echo !empty($loginuser['address4_postcode']) ? ', ' . $loginuser['address4_postcode'] : ''; ?>
                    </label>
                </div>
                </form>
            </div><!-- /modal-body -->
            <div class="modal-footer">
                <!-- <p class="span3 resize">The following images are sized incorrectly. Click to edit</p> -->
                <button type="button" class="btn" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="handleNext()">Next</button>
            </div>
        </div><!-- /modal-content -->
    </div><!-- /modal-dialog -->
</div><!-- /myModal -->

<script src="<?= base_url();?>public/plugins/pikaday/pikaday.js"></script>
<script src="<?= base_url();?>public/plugins/pikaday/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
 var today = new Date();

    // Calculate the date 3 days from now for the default value
    var defaultDate = new Date(today);
    defaultDate.setDate(today.getDate() + 3);

    // Set the minimum date for the pre-order input field (3 days from now)
    var minDate = new Date(today);
    minDate.setDate(today.getDate() + 3);

    // Set the maximum date for the pre-order input field (10 days from now)
    var maxDate = new Date(today);
    maxDate.setDate(today.getDate() + 10);

    // Function to check if a given date is a Sunday
    function isSunday(date) {
        return date.getDay() === 0; // 0 represents Sunday
    }

    var picker = new Pikaday({
        field: document.getElementById('pre_order'),
        minDate: minDate,
        maxDate: maxDate,
        defaultDate: !isSunday(defaultDate) ? defaultDate : null,
        setDefaultDate: true,
        format: 'YYYY-MM-DD',
        toString(date, format) {
            // Convert the date to the format YYYY-MM-DD
            return moment(date).format('YYYY-MM-DD');
        },
        parse(dateString, format) {
            // Parse the date from the format YYYY-MM-DD
            return moment(dateString, 'YYYY-MM-DD').toDate();
        },
        onSelect: function(date) {
            // Validate the selected date
            if (isSunday(date)) {
                alert('No delivery on Sunday.');
                picker.setDate(null); // Clear the invalid date
            } else if (date > maxDate) {
                alert('You can only select a date within the next 7 days.');
                picker.setDate(null); // Clear the invalid date
            } else {
                document.getElementById('pre_order').value = moment(date).format('YYYY-MM-DD');
            }
        }
    });

    // Ensure the default date is not set if it's a Sunday
    if (isSunday(defaultDate)) {
        picker.setDate(null);
    } else {
        document.getElementById('pre_order').value = moment(defaultDate).format('YYYY-MM-DD');
    }



function openModal() {
    var deliveryDateInput = document.getElementById('pre_order');
    if (!deliveryDateInput.value) {
      Swal.fire("Delivery Date", "Please select a delivery date before creating the order.", "warning");
    } else {
      $('#myModal').modal('show');
    }
  }


  function confirmSubmission(event) {
    event.preventDefault(); // Prevent the default form submission

    // Get the delivery charge value
    var deliveryCharge = parseFloat(document.getElementById('delivery_charge').value);

        // First check user payment status via AJAX
    fetch('<?= base_url("index.php/orders/check_user_can_order") ?>')
        .then(response => response.json())
        .then(data => {
            if (!data.status) {
               Swal.fire({
                    title: "🚫 Your last 2 invoices are not paid 🚫",
                    html: `
                        <p>💳 Kindly clear your outstanding payments to resume ordering.</p>
                        <p>📞 For assistance, please contact our Customer Service or Accounts Team.</p>
                    `,
                    icon: "error",
                    confirmButtonText: "OK"
                });
                return;
            }

            // Proceed with delivery charge check
    // Check if the delivery charge is below 80
    if (deliveryCharge === 20) {
        Swal.fire({
            title: "Warning",
            text: "Under $80 MOQ, a $20 fee will be imposed. Please add more items to avoid delivery charges.",
            icon: "info",
            confirmButtonText: "OK"
        }).then((willContinue) => {
            if (willContinue) {
                // Proceed with the rest of the confirmation
                //confirmOrder();
            }
        });
    }  else {
        // If delivery charge is not 20, proceed with the existing confirmation
        confirmOrder();
    }
      })
        .catch(error => {
            console.error("Error checking user status:", error);
            Swal.fire("Error", "Failed to verify your account status. Please try again.", "error");
        });
}
function confirmOrder() {
    var form = document.getElementById('repeat_order');
    var submitBtn = document.getElementById('submitBtn');
    var btnText = document.getElementById('btnText');
    var btnSpinner = document.getElementById('btnSpinner');

    Swal.fire({
        title: "You are about to confirm this order?",
        text: "An E-invoice will be sent to your Finance on the delivery day. A hard copy invoice will also be provided.",
        icon: "warning",
        showCancelButton: true,
        cancelButtonText: "Cancel",
        confirmButtonText: "Repeat Order",
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
    }).then((result) => {
        if (result.isConfirmed) {
            submitBtn.disabled = true;
            btnText.textContent = "Processing...";
            btnSpinner.classList.remove("d-none");
            form.submit();
        }
        // If cancelled, do nothing
    });
}



  $(document).ready(function() {

$('.product').each(function() {
    var row_id = $(this).data('row-id').replace('row_', ''); // Extract row id from data attribute
    getTotal(row_id);
    subAmount();
  //  getProductData(row_id);
});

$(document).on('change', '.sliced', function() {
var row = $(this).closest('tr'); // Get the closest row
var sliceSelected = row.find('.sliced').val(); // Get the value of .sliced within the same row
var seedSelected = row.find('.seed').val(); // Get the value of .seed within the same row
subAmount();
var rows = $(this).closest('tr').attr('id').split('_')[1]; // Get the row number from the closest row
    getTotal(rows); // Call getTotal function with row information
});

$(document).on('change', '.seed', function() {
var row = $(this).closest('tr'); // Get the closest row
var sliceSelected = row.find('.sliced').val(); // Get the value of .sliced within the same row
var seedSelected = row.find('.seed').val(); // Get the value of .seed within the same row
subAmount();
var rows= $(this).closest('tr').attr('id').split('_')[1]; // Get the row number from the closest row
    getTotal(rows); // Call getTotal function with row information

});

$("#add_row").unbind('click').bind('click', function() {
var table = $("#product_info_table");
var count_table_tbody_tr = $("#product_info_table tbody tr").length;
var row_id = count_table_tbody_tr + 1;

var html = '<tr id="row_'+row_id+'">' +
    '<td>'+ 
        '<select class="form-control category_name" data-row-id="'+row_id+'" id="category_'+row_id+'" name="category[]" style="width:100%;">'+
            '<option value="">Choose</option>';
            // Add options for categories here
            <?php foreach ($category as $key => $value): ?>
                html += '<option value="<?php echo $value['prod_category'] ?>"><?php echo $value['prod_category'] ?></option>';  
            <?php endforeach ?>
        html += '</select>'+
    '</td>'+
    '<td>'+ 
        '<select class="form-control select_group product" data-row-id="'+row_id+'" id="product_'+row_id+'" name="product[]" style="width:100%;" required onchange="getProductData('+row_id+')">'+
        '</select>'+
    '</td>'+ 
    '<td>'+ 
        '<select class="form-control select_group sliced" data-row-id="'+row_id+'" id="sliced_'+row_id+'" name="sliced[]" style="width:100%;" onchange="slicechange(this)">'+
            '<option value="">Choose</option>'+
            '<option value="Unsliced">Unsliced</option>'+
            '<option value="12mm">12mm</option>'+
            '<option value="20mm">20mm</option>'+
        '</select>'+
    '</td>'+
    '<td>'+ 
        '<select class="form-control select_group seed" data-row-id="'+row_id+'" id="seed_'+row_id+'" name="seed[]" style="width:100%;" onchange="seedchange(this)">'+
            '<option value="">Choose</option>'+
            '<option value="Seedless">Seedless</option>'+
            '<option value="White drizzle">White drizzle</option>'+
            '<option value="White full seed">White full seed</option>'+
            '<option value="Black drizzle">Black drizzle</option>'+
            '<option value="White black mix">White black mix</option>'+
        '</select>'+
    '</td>'+
    '<td><input type="hidden" name="minn" id="minn" class="form-control" autocomplete="off"><input type="number" name="qty[]" id="qty_'+row_id+'" class="form-control" onkeyup="getTotal('+row_id+')"><input type="hidden" name="total_qty[]" id="total_qty_'+row_id+'" class="form-control" autocomplete="off"></td>'+
    '<td><input type="text" name="rate[]" id="rate_'+row_id+'" class="form-control" disabled><input type="hidden" name="rate_value[]" id="rate_value_'+row_id+'" class="form-control"></td>'+
    '<td hidden><input type="text" name="gst_percent[]" id="gst_percent_'+row_id+'" class="form-control" disabled><input type="hidden" name="gst_percent_val[]" id="gst_percent_val_'+row_id+'" class="form-control"></td>'+
    '<td><input type="text" name="service_charge_lineitem[]" id="service_charge_lineitem_'+row_id+'" class="form-control" disabled><input type="hidden" name="service_charge_itemval[]" id="service_charge_itemval_'+row_id+'" class="form-control"></td>'+
    '<td><input type="text" name="amount[]" id="amount_'+row_id+'" class="form-control" disabled><input type="hidden" name="amount_value[]" id="amount_value_'+row_id+'" class="form-control"></td>'+
    '<td hidden><input type="text" name="gst_amount[]" id="gst_amount_'+row_id+'" class="form-control" disabled><input type="hidden" name="gst_amount_value[]" id="gst_amount_value_'+row_id+'" class="form-control"></td>'+
    '<td><button type="button" class="btn btn-danger" onclick="removeRow(\''+row_id+'\')"><i class="fa fa-close"></i></button></td>'+
'</tr>';

if(count_table_tbody_tr >= 1) {
    $("#product_info_table tbody tr:last").after(html);  
}
else {
    $("#product_info_table tbody").html(html);
}
});

// Event delegation to handle dynamically created elements
$('#product_info_table').on('change', '.category_name', function() {
var currentRow = $(this).closest('tr');
var categoryId = $(this).val();

// AJAX request to fetch products based on the selected category
$.ajax({
    url: '<?php echo base_url('index.php/orders/getProductsByCategory'); ?>',
    method: 'POST',
    data: { category_id: categoryId },
    dataType: 'json',
    success: function(response) {
        var options = '<option value=""></option>';
        $.each(response, function(index, product) {
            var qty_pkt = product.min_order > 1 ? ' (' + product.min_order + 'pcs/pkt)' : '';
            options += '<option value="' + product.id + '">' + product.product_id + '-' + product.product_name + qty_pkt + '</option>';
        });

        // Update the corresponding product select element in the same row
        currentRow.find('.product').html(options);
    }
});
});


$('#product_info_table').on('change', '.seed', function() {
var row = $(this).closest('tr').attr('id').split('_')[1]; // Get the row number from the closest row
    getTotal(row); // Call getTotal function with row information
    subAmount();
});
$('#product_info_table').on('change', '.sliced', function() {
var row = $(this).closest('tr').attr('id').split('_')[1]; // Get the row number from the closest row
    getTotal(row); // Call getTotal function with row information
subAmount();
});
}); // /document

  $(document).on('input', 'input[name^="qty"]', function() {
      var rowId = $(this).attr('id').split('_')[1];
      getTotal(rowId);
  });

  $('#product_info_table').on('change', 'input[name^="qty"]', function() {
      var rowId = $(this).attr('id').split('_')[1];
      var minOrder = parseInt($('#minn').val()); // Get the stored min_order value

      // If the input value is less than the min_order value, set it to min_order
      if ($(this).val() < minOrder) {
        $(this).val(minOrder);
        Swal.fire({
                title: "Minimum Order Quantity",
                text: "You cannot order less than the minimum quantity of " + minOrder + ".",
                icon: "warning",
                confirmButtonText: "OK"
          });
      }

      getTotal(rowId);
  });

  function removeRow(tr_id) {
    $("#product_info_table tbody tr#row_" + tr_id).remove();
    // Update row IDs for subsequent rows
    $("#product_info_table tbody tr").each(function(index) {
        $(this).attr('id', 'row_' + (index + 1));
        // Update row IDs for inputs within this row
        $(this).find('select, input').each(function() {
            var currentId = $(this).attr('id');
            if (currentId) {
                var newRowId = currentId.replace(/\d+$/, index + 1);
                $(this).attr('id', newRowId);
                $(this).attr('data-row-id', index + 1); // Update data-row-id attribute if needed
            }
        });
    });
    subAmount(); // Recalculate total amounts after row removal
}

    function getTotal(row = null) {
    if (row) {
        var service_charge = 0;
        var sliceSelected = $("#sliced_" + row).val();
      //  var seedSelected = $("#seed_" + row).val();
      //if (sliceSelected || seedSelected) {
        if (sliceSelected && sliceSelected != 'Unsliced') {
            service_charge = 0.5 * Number($("#qty_" + row).val());
        }

        var total = Number($("#rate_value_" + row).val()) * Number($("#qty_" + row).val());
        var total_amt = total + service_charge;
        var gst = total_amt * 9 / 100;

        $("#amount_" + row).val(total_amt.toFixed(2));
        $("#amount_value_" + row).val(total_amt.toFixed(2));

        $("#gst_percent_" + row).val('9');
        $("#gst_percent_val_" + row).val('9');

        $("#service_charge_lineitem_" + row).val(service_charge.toFixed(2));
        $("#service_charge_itemval_" + row).val(service_charge.toFixed(2));

        $("#gst_amount_" + row).val(gst.toFixed(2));
        $("#gst_amount_value_" + row).val(gst.toFixed(2));

        subAmount();
    } else {
        alert('no row !! please refresh the page');
    }
}


function getProductData(row_id) {
    var product_id = $("#product_" + row_id).val();
    if (product_id == "") {
        $("#rate_" + row_id).val("");
        $("#rate_value_" + row_id).val("");
        $("#qty_" + row_id).val("");
        $("#amount_" + row_id).val("");
        $("#amount_value_" + row_id).val("");
    } else {
        $.ajax({
            url: '<?php echo base_url('index.php/orders/getProductValueById'); ?>',
            type: 'post',
            data: {
                product_id: product_id
            },
            dataType: 'json',
            success: function(response) {
                // setting the rate value into the rate input field
                $("#rate_" + row_id).val(response.prod_rate);
                $("#rate_value_" + row_id).val(response.prod_rate)

                if (response.add_on_slice == 0) {
                  $("#sliced_" + row_id).val("");
                    $("#sliced_" + row_id).prop('hidden', true);
                    $('#msg').html('Slice not available for ' + response.product_id + '-' + response.product_name);
                } else {
                  $("#sliced_" + row_id).prop('hidden', false);
                }

                if (response.add_on_seed == 0) {
                  $("#seed_" + row_id).val("");
                  $("#seed_" + row_id).prop('hidden', true);
                    $('#msg').html('Seed not available for ' + response.product_id + '-' + response.product_name);
                } else {
                  $("#seed_" + row_id).prop('hidden', false);
                }

                if (response.add_on_seed == 0 && response.add_on_slice == 0) {
                  $('#msg').html('Slice and Seed not available for ' + response.product_id + '-' + response.product_name);
                }

                // Check if min_order is not empty
                if (response.min_order !== undefined && response.min_order !== null && response.min_order !== "") {
                      $('#minn').val(response.min_order);
                      $("#qty_" + row_id).val(response.min_order);
                      $("#qty_" + row_id).prop('step', response.min_order);
                      $("#qty_value_" + row_id).val(response.min_order);
                      var total = Number(response.prod_rate) * Number(response.min_order);
                  } else {
                      $("#qty_" + row_id).val(1);
                      $("#qty_value_" + row_id).val(1);
                      var total = Number(response.prod_rate) * 1;
                  }

                  if(response.min_order == 0){
                      $("#qty_" + row_id).val(1);
                      $("#qty_value_" + row_id).val(1);
                      var total = Number(response.prod_rate) * 1;
                  }
                 

                  // Include delivery charge in the total amount
                  var deliveryCharge = parseFloat($("#delivery_charge").val()) || 0;
                  total += deliveryCharge;

                  total = total.toFixed(2);
                  $("#amount_" + row_id).val(total);
                  $("#amount_value_" + row_id).val(total);
      // Show promotion modal for product IDs 55 and 65
                  if (response.promotion == 1) {
                      $("#qty_" + row_id).on('change', function() {
                        var qty = $(this).val();
                        var promotionRuleN = response.promo_rule_buy; // Get the promotion rule N (Buy)
                        var promotionRuleM = response.promo_rule_free; // Get the promotion rule M (Free)
                        
                        if (promotionRuleN && promotionRuleM) {
                          var freeQty = Math.floor(qty / promotionRuleN) * promotionRuleM; // Calculate free items based on the promotion rules
                          var totalQty = parseInt(qty) + freeQty;

                          if(freeQty != 0){
                          $("#total_qty_" + row_id).val(totalQty); // Update the total quantity with free items
                          }
                          
                        }
                      });

                      // Construct dynamic promotion details
                      var promotionDetailsHtml = '';
                      if (response.promo_rule_buy && response.promo_rule_free) {
                        promotionDetailsHtml = `<p><span class="badge badge-success">Buy ${response.promo_rule_buy}</span> - Get <strong>${response.promo_rule_free}</strong> free</p>`;
                      } else {
                        promotionDetailsHtml = '<p>No promotion details available.</p>';
                      }

                      // Update the modal with promotion details and product info
                      $("#promotionDetails").html(promotionDetailsHtml);
                      var promotionProductInfo = `Special promotion for product ID ${response.product_id} - ${response.product_name}.`;
                      $("#promotionProductInfo").text(promotionProductInfo);

                      // Show the modal
                      $("#promotionModal").modal('show');
                }

                updatePromotionAcrossRows(row_id);
                subAmount();
                getTotal(row_id);
            } // /success
        }); // /ajax function to fetch the product data 
    }
    $("#product_" + row_id).blur();
}


function updatePromotionAcrossRows(changedRowId) {
    var product_id = $("#product_" + changedRowId).val();
    var totalQty = 0;
    var rows = [];
    var hasPromotion = false;

    // Step 1: Calculate the total quantity of the product across all rows and check if promotion exists
    $('input[name="qty[]"]').each(function() {
        var row_id = $(this).attr('id').split('_')[1];
        if ($("#product_" + row_id).val() == product_id) {
            var rowQty = parseFloat($(this).val());
            totalQty += rowQty;
            rows.push({ row_id: row_id, qty: rowQty });
        }
    });

    // Step 2: Fetch promotion details from the server
    $.ajax({
        url: '<?php echo base_url('index.php/orders/getProductValueById'); ?>',
        type: 'post',
        data: { product_id: product_id },
        dataType: 'json',
        success: function(response) {
            if (response.promotion == 1) {
                hasPromotion = true;

                // Calculate the free items based on accumulated quantity
                var freeQty = Math.floor(totalQty / response.promo_rule_buy) * response.promo_rule_free;

                // Distribute free items based on the scenario
                var remainingFreeQty = freeQty;

                // Scenario 1: Multiple rows with same product
                if (rows.length > 1) {
                  var freeQty = Math.floor(totalQty / response.promo_rule_buy) * response.promo_rule_free;

                  // Step 3: Distribute free items across the rows
                  var remainingFreeQty = freeQty;
                  for (var i = 0; i < rows.length; i++) {
                      var row = rows[i];
                      var rowFreeQty = Math.min(remainingFreeQty, response.promo_rule_free);
                      var rowTotalQty = row.qty + rowFreeQty;

                      // Update the total quantity for this row
                      $("#total_qty_" + row.row_id).val(rowTotalQty);
                      remainingFreeQty -= rowFreeQty;
                  }
                } else {
                    // Scenario 2: Single row with promotion
                    var row_id = rows[0].row_id;
                    var qty = rows[0].qty;
                    var rowFreeQty = Math.floor(qty / response.promo_rule_buy) * response.promo_rule_free;
                    var rowTotalQty = qty + rowFreeQty;

                    // Update the total quantity for this row
                    $("#total_qty_" + row_id).val(rowTotalQty);
                }

                
            }

            // Always call these functions to ensure the amounts are updated
            subAmount();
            getTotal(changedRowId);
        }
    });
}


  function subAmount() {
    var service_charge = 0; // Initialize additional charge to 0

    // Check if either slice or seed is selected for any row
    var tableProductLength = $("#product_info_table tbody tr").length;

    for (var x = 1; x <= tableProductLength; x++) {
        var sliceSelected = $("#sliced_" + x).val();
      //  var seedSelected = $("#seed_" + x).val();
        var qty = $("#qty_" + x).val();

       // if (sliceSelected || seedSelected) {
        if (sliceSelected && sliceSelected != 'Unsliced') {
            // If either slice or seed is selected for this row, add additional charge
            service_charge += 0.5*qty;
        }
    }

    // Calculate total amount
    var totalSubAmount = 0;

    for (var x = 1; x <= tableProductLength; x++) {
        totalSubAmount += Number($("#amount_" + x).val());
    }

    // Calculate gross amount
    var grossAmount = totalSubAmount;

    $("#gross_amount").val(grossAmount.toFixed(2));
    $("#gross_amount_value").val(grossAmount.toFixed(2));

    
    var discount = $("#discount").val() || 0;
    var netAmount = grossAmount;

    var deliveryCharge = netAmount < 80 ? 20.00 : 0;

    var totall = grossAmount + deliveryCharge;
    var gstRate = 9; 
    var gstAmount = totall * gstRate / 100;
    gstAmount = parseFloat(gstAmount.toFixed(3)); // Ensure gstAmount is a number with three decimal places

    // Round up if the third decimal place is 5 or greater
    if (gstAmount * 1000 % 10 >= 5) {
        gstAmount = Math.ceil(gstAmount * 100) / 100; // Round up to two decimal places
    } else {
        gstAmount = Math.floor(gstAmount * 100) / 100; // Round down to two decimal places
    }

    $("#gst").val(gstAmount.toFixed(2));
    $("#gst_rate").val(gstAmount.toFixed(2));

    $("#delivery_charge").val(deliveryCharge);
    $("#delivery_charge_value").val(deliveryCharge);

    $("#service_charge").val(service_charge.toFixed(2));
    $("#service_charge_value").val(service_charge.toFixed(2));

    var finalAmount = netAmount + gstAmount  + deliveryCharge;

    $("#net_amount").val(finalAmount.toFixed(2));
    $("#net_amount_value").val(finalAmount.toFixed(2));
}



function handleNext() {
    var selectedAddress = '';
    var shipping_address = '';
    var shipping_address_line2 = '';
    var shipping_address_line3 = '';
    var shipping_address_line4 = '';
    var shipping_address_city = '';
    var shipping_address_postcode = '';

    if ($('#shippingAddress1').is(':checked')) {
        shipping_address = "<?php echo $loginuser['delivery_address']; ?>";
        shipping_address_line2 = "<?php echo !empty($loginuser['delivery_address_line2']) ? $loginuser['delivery_address_line2'] : ''; ?>";
        shipping_address_line3 = "<?php echo !empty($loginuser['delivery_address_line3']) ? $loginuser['delivery_address_line3'] : ''; ?>";
        shipping_address_line4 = "<?php echo !empty($loginuser['delivery_address_line4']) ? $loginuser['delivery_address_line4'] : ''; ?>";
        shipping_address_city = "<?php echo !empty($loginuser['delivery_address_city']) ? $loginuser['delivery_address_city'] : ''; ?>";
        shipping_address_postcode = "<?php echo !empty($loginuser['delivery_address_postcode']) ? $loginuser['delivery_address_postcode'] : ''; ?>";
    } else if ($('#shippingAddress2').is(':checked')) {
        shipping_address = "<?php echo $loginuser['address2']; ?>";
        shipping_address_line2 = "<?php echo !empty($loginuser['address2_line2']) ? $loginuser['address2_line2'] : ''; ?>";
        shipping_address_line3 = "<?php echo !empty($loginuser['address2_line3']) ? $loginuser['address2_line3'] : ''; ?>";
        shipping_address_line4 = "<?php echo !empty($loginuser['address2_line4']) ? $loginuser['address2_line4'] : ''; ?>";
        shipping_address_city = "<?php echo !empty($loginuser['address2_city']) ? $loginuser['address2_city'] : ''; ?>";
        shipping_address_postcode = "<?php echo !empty($loginuser['address2_postcode']) ? $loginuser['address2_postcode'] : ''; ?>";
    } else if ($('#shippingAddress3').is(':checked')) {
        shipping_address = "<?php echo $loginuser['address3']; ?>";
        shipping_address_line2 = "<?php echo !empty($loginuser['address3_line2']) ? $loginuser['address3_line2'] : ''; ?>";
        shipping_address_line3 = "<?php echo !empty($loginuser['address3_line3']) ? $loginuser['address3_line3'] : ''; ?>";
        shipping_address_line4 = "<?php echo !empty($loginuser['address3_line4']) ? $loginuser['address3_line4'] : ''; ?>";
        shipping_address_city = "<?php echo !empty($loginuser['address3_city']) ? $loginuser['address3_city'] : ''; ?>";
        shipping_address_postcode = "<?php echo !empty($loginuser['address3_postcode']) ? $loginuser['address3_postcode'] : ''; ?>";
    }
    else if ($('#shippingAddress4').is(':checked')) {
        shipping_address = "<?php echo $loginuser['address4']; ?>";
        shipping_address_line2 = "<?php echo !empty($loginuser['address4_line2']) ? $loginuser['address4_line2'] : ''; ?>";
        shipping_address_line3 = "<?php echo !empty($loginuser['address4_line3']) ? $loginuser['address4_line3'] : ''; ?>";
        shipping_address_line4 = "<?php echo !empty($loginuser['address4_line4']) ? $loginuser['address4_line4'] : ''; ?>";
        shipping_address_city = "<?php echo !empty($loginuser['address4_city']) ? $loginuser['address4_city'] : ''; ?>";
        shipping_address_postcode = "<?php echo !empty($loginuser['address4_postcode']) ? $loginuser['address4_postcode'] : ''; ?>";
    }

   
    $('#shipping_address').val(shipping_address);
    $('#shipping_address_line2').val(shipping_address_line2);
    $('#shipping_address_line3').val(shipping_address_line3);
    $('#shipping_address_line4').val(shipping_address_line4);
    $('#shipping_address_city').val(shipping_address_city);
    $('#shipping_address_postcode').val(shipping_address_postcode);

    $('#myModal').modal('hide');
    Swal.fire({
    title: "Address Updated!",
    text: "You can now proceed to create the order.",
    icon: "success"
    }).then((value) => {
        confirmSubmission(event);
    });

}
  </script>