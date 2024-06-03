

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
              <h3 class="box-title">Update/Preview Order</h3>
            </div>
          <div class="box" style="margin-top:20px;">
            
            <!-- /.box-header -->
            
            <form role="form" id="update_orders" action="<?php echo base_url('index.php/orders/update/'.$id.'/'.$user_id); ?>" method="post" class="form-horizontal" onsubmit="confirmSubmission(event)">
               <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
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
                        <th>Sample</th>
                        <th style="width:15%">Category</th>
                        <th style="width:30%">Product / Packing Size</th>
                        <th style="width:10%">Pre-Slice</th>
                        <th style="width:10%">Seed</th>
                        <th style="width:10%">Qty (Pkt)</th>
                        <th style="width:10%">Rate</th>
                        <th style="width:5%">Service_charge</th>
                        <th style="width:10%">Amount</th>
                        <th style="width:10%"><button type="button" id="add_row" class="btn btn-info"><i class="fa fa-plus"></i></button></th>
                      </tr>
                    </thead>

                    <tbody>

                      <?php if(isset($order_data['order_item'])): ?>
                        <?php $x = 1; ?>
                        <?php foreach ($order_data['order_item'] as $key => $val): 
                          $sql = "SELECT add_on_slice, add_on_seed FROM products WHERE id = ".$val['product_id'];
                          $query = $this->db->query($sql);
                          $row = $query->row_array();
                  
                          $seed = "";
                          $slice = "";
                  
                          if($row['add_on_seed'] == 0){
                              $seed = "hidden";
                          }
                          if($row['add_on_slice'] == 0){
                              $slice = "hidden";
                          }

                          if($val['sample'] == 1){
                            $checked = 'checked';
                          }
                          else{
                            $checked = '';
                          }
                          ?>
                      
                        <tr id="row_<?php echo $x; ?>">
                        <td><input type="checkbox" id="sample_<?php echo $x; ?>" name="sample[]" value="1" <?php echo $checked; ?> style="width: 42px;height: 20px;margin-top: 6px;" onchange="handleSampleChange(1)"></td>
                        <td>
                        <select class="form-control category_name" data-row-id="row_1" id="category_1" name="category[]" onmousedown="if(this.options.length>8){this.size=8;}" onchange='this.size=0;' onblur="this.size=0;">
                                  <option value="">Choose</option>
                                  <?php foreach ($category as $key => $v): ?>
                                      <option value="<?php echo $v['prod_category'] ?>" <?php if ($val['category'] == $v['prod_category']) { echo "selected='selected'"; } ?>><?php echo $v['prod_category'] ?></option>
                                  <?php endforeach ?>
                              </select>
                          </td>
                          
                          <td>
                            <select class="form-control select_group product" data-row-id="row_<?php echo $x; ?>" id="product_<?php echo $x; ?>" name="product[]" style="width:100%;" required onchange="getProductData(<?php echo $x; ?>)">
                                <option value=""></option>
                                <?php foreach ($products as $k => $v): ?>
                                    <option value="<?php echo $v['id'] ?>" <?php if($val['product_id'] == $v['id']) { echo "selected='selected'"; } ?>>
                                        <?php echo $v['product_id'] . ' - ' . $v['product_name']; ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </td>


                              <td>    
                              <select class="form-control sliced" <?php echo $slice; ?> id="sliced_<?php echo $x; ?>" name="sliced[]" onmousedown="if(this.options.length>8){this.size=8;}" onchange='slicechange()' onblur="this.size=0;">
                                  <option value="">Choose</option> <!-- Add a default "Choose" option -->
                                  <option value="Unsliced" <?php if ($val['slice_type'] == "Unsliced") { echo "selected='selected'"; } ?>>Unsliced</option>
                                  <option value="12mm" <?php if ($val['slice_type'] == "12mm") { echo "selected='selected'"; } ?>>12mm</option> <!-- Set selected if slice_type is 12mm -->
                                  <option value="20mm" <?php if ($val['slice_type'] == "20mm") { echo "selected='selected'"; } ?>>20mm</option> <!-- Set selected if slice_type is 20mm -->
                              </select>

                              </td>
                              <td>    
                                  <select class="form-control seed" <?php echo $seed; ?> id="seed_<?php echo $x; ?>" name="seed[]" onmousedown="if(this.options.length>8){this.size=8;}" onchange='seedchange()' onblur="this.size=0;">
                                      <option value="">Choose</option>
                                      <option value="Seedless" <?php if ($val['seed_type'] == "Seedless") { echo "selected='selected'"; } ?>>Seedless</option>
                                      <option value="White drizzle" <?php if ($val['seed_type'] == "White drizzle") { echo "selected='selected'"; } ?>>White drizzle</option>
                                      <option value="Black drizzle" <?php if ($val['seed_type'] == "Black drizzle") { echo "selected='selected'"; } ?>>Black drizzle</option>  
                                      <option value="White black mix" <?php if ($val['seed_type'] == "White black mix") { echo "selected='selected'"; } ?>>White black mix</option>   
                                  </select>
                              </td>
                            <td><input type="number" name="qty[]" id="qty_<?php echo $x; ?>" class="form-control" required onkeyup="getTotal(<?php echo $x; ?>)" value="<?php echo $val['qty'] ?>" autocomplete="off"></td>
                            <td>
                              <input type="text" name="rate[]" id="rate_<?php echo $x; ?>" class="form-control" disabled value="<?php echo $val['rate'] ?>" autocomplete="off">
                              <input type="hidden" name="rate_value[]" id="rate_value_<?php echo $x; ?>" class="form-control" value="<?php echo $val['rate'] ?>" autocomplete="off">
                            </td>
                            <td hidden>
                            <input type="text" name="gst_percent[]" id="gst_percent_<?php echo $x; ?>" class="form-control" disabled autocomplete="off">
                            <input type="hidden" name="gst_percent_val[]" id="gst_percent_val_<?php echo $x; ?>" class="form-control" autocomplete="off">
                        </td>
                        <td>
                            <input type="text" name="service_charge_lineitem[]" id="service_charge_lineitem_<?php echo $x; ?>" class="form-control" disabled autocomplete="off">
                            <input type="hidden" name="service_charge_itemval[]" id="service_charge_itemval_<?php echo $x; ?>" class="form-control" autocomplete="off">
                        </td>
                        <td>
                            <input type="text" name="amount[]" id="amount_<?php echo $x; ?>" class="form-control" disabled autocomplete="off">
                            <input type="hidden" name="amount_value[]" id="amount_value_<?php echo $x; ?>" class="form-control" autocomplete="off">
                        </td>
                        <td hidden>
                            <input type="text" name="gst_amount[]" id="gst_amount_<?php echo $x; ?>" class="form-control" disabled autocomplete="off">
                            <input type="hidden" name="gst_amount_value[]" id="gst_amount_value_<?php echo $x; ?>" class="form-control" autocomplete="off">
                        </td>
                            <td><button type="button" class="btn btn-danger" onclick="removeRow('<?php echo $x; ?>')"><i class="fa fa-close"></i></button></td>
                        </tr>
                        <?php $x++; ?>
                      <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                  </table>

                  <br /> <br/>
                  <!-- <span id="msg" class="msg" style="color: red;"></span> -->
                  <?php foreach ($order_total as $key => $order_data):
                  
                    ?>
                  <div class="col-sm-12 col-md-12 col-xs-12">
                  <div class="form-group">
                  <div class="col-sm-4 col-md-4">
                  <label for="feed_back" class="control-label">Feedback</label>
                  <textarea class="form-control" id="feed_back" name="feed_back" autocomplete="off"><?php echo $order_data['feed_back']; ?></textarea>
                  <br>
                  <label>Pre Order Date (If required)</label>
                  <input type="date" name="pre_order_date" id="pre_order" class="form-control" value="<?php echo $order_data['delivery_date'] ?>"  autocomplete="off" required>
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
                  <div class="col-sm-7 col-md-7 col-xs-7 pull pull-right">
                
                  
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
                      <label for="service_charge" class="control-label">Total Service charge: <?php //echo $company_data['service_charge_value'] ?> </label>
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
                        <input type="text" class="form-control" id="discount" name="discount" value="<?php echo $order_data['discount'] ?>" placeholder="Discount" onkeyup="subAmount()" autocomplete="off">
                      </div>
                    </div><br> -->
                    <div class="form-group" style="margin-bottom:30px;">
                      <div class="col-sm-4">
                      <label for="discount" class="control-label">Delivery Charge</label></div>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" id="delivery_charge" value="<?php echo $order_data['delivery_charge'] ?>" name="delivery_charge" disabled  autocomplete="off">
                        <input type="hidden" class="form-control" id="delivery_charge_value" value="<?php echo $order_data['delivery_charge'] ?>" name="delivery_charge_value" autocomplete="off">
                        <input type="hidden" class="form-control" id="delivery_charge_valuee" value="<?php echo $order_data['delivery_charge'] ?>" name="delivery_charge_valuee" autocomplete="off">
                        <input type="checkbox" id="self_pickup" value="1" name="self_pickup" disabled  autocomplete="off">  Self Pick-Up
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
                  <input type="hidden" name="updated_by" id="updated_by" value="<?php echo $loginuser['name']; ?>">
                    </div>
                  </div>
                </div>
                <!-- /.box-body -->

                <div class="box-footer col-sm-12 col-md-12 col-xs-12 pull pull-left" style="margin-bottom:30px;">
                  <input type="hidden" id="delivery_charge" name="delivery_charge" autocomplete="off">  
                  <input type="hidden" name="service_charge_rate"  autocomplete="off">
                  <a target="__blank" href="<?php echo base_url() . 'index.php/orders/printDiv/'.$order_data['id'] ?>" class="btn btn-default" >Print</a>
                  <?php if($loginusers['address2'] != NULL || $loginusers['address3'] != NULL) {

                  $modal_id = '<a class="galName" href="#myModal" data-toggle="modal" >';
                  $end_id = ' </a>';

                  } 
                  else{
                  $modal_id = '';
                  $end_id = '';
                  }
                  ?>

                  <?php echo  $modal_id; ?><button type="submit" class="btn btn-success">Save Changes</button><?php echo  $end_id; ?>
                  <a href="<?php echo base_url('index.php/orders/manage_orders') ?>" class="btn btn-danger">Back</a>
                </div>
                <?php endforeach; ?>
              </form>
            <!-- /.box-body -->
          
        <!-- col-md-12 -->
      </div>
      <!-- /.row -->
      

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <div class="modal fade hide modal-creator" id="myModal" style="display: none;" aria-hidden="true">
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
                    <?php echo !empty($loginusers['delivery_address']) ? $loginusers['delivery_address'] : ''; ?>
                    <?php echo !empty($loginusers['delivery_address_line2']) ? ', ' . $loginusers['delivery_address_line2'] : ''; ?>
                    <?php echo !empty($loginusers['delivery_address_line3']) ? ', ' . $loginusers['delivery_address_line3'] : ''; ?>
                    <?php echo !empty($loginusers['delivery_address_line4']) ? ', ' . $loginusers['delivery_address_line4'] : ''; ?>
                    <?php echo !empty($loginusers['delivery_address_city']) ? ', ' . $loginusers['delivery_address_line4'] : ''; ?>
                    <?php echo !empty($loginusers['delivery_address_city']) ? ', ' . $loginusers['delivery_address_city'] : ''; ?>
                    <?php echo !empty($loginusers['delivery_address_postcode']) ? ', ' . $loginusers['delivery_address_postcode'] : ''; ?>
                </label>
                </div>
                <div class="form-group">
                    <label for="shippingAddress2">
                        <?php echo !empty($loginusers['address2']) ? '<input type="checkbox" id="shippingAddress2" name="shippingAddress2" class="shippingAddressCheckbox">' : ''; ?>
                        <?php echo !empty($loginusers['address2']) ? $loginusers['address2'] : ''; ?>
                        <?php echo !empty($loginusers['address2_line2']) ? ', ' . $loginusers['address2_line2'] : ''; ?>
                        <?php echo !empty($loginusers['address2_line3']) ? ', ' . $loginusers['address2_line3'] : ''; ?>
                        <?php echo !empty($loginusers['address2_line4']) ? ', ' . $loginusers['address2_line4'] : ''; ?>
                        <?php echo !empty($loginusers['address2_city']) ? ', ' . $loginusers['address2_city'] : ''; ?>
                        <?php echo !empty($loginusers['address2_postcode']) ? ', ' . $loginusers['address2_postcode'] : ''; ?>
                    </label>
                </div>
                <div class="form-group">
                    <label for="shippingAddress3">
                        <?php echo !empty($loginusers['address3']) ? '<input type="checkbox" id="shippingAddress3" name="shippingAddress3" class="shippingAddressCheckbox">' : ''; ?>
                        <?php echo !empty($loginusers['address3']) ? $loginusers['address3'] : ''; ?>
                        <?php echo !empty($loginusers['address3_line2']) ? ', ' . $loginusers['address3_line2'] : ''; ?>
                        <?php echo !empty($loginusers['address3_line3']) ? ', ' . $loginusers['address3_line3'] : ''; ?>
                        <?php echo !empty($loginusers['address3_line4']) ? ', ' . $loginusers['address3_line4'] : ''; ?>
                        <?php echo !empty($loginusers['address3_city']) ? ', ' . $loginusers['address3_city'] : ''; ?>
                        <?php echo !empty($loginusers['address3_postcode']) ? ', ' . $loginusers['address3_postcode'] : ''; ?>
                    </label>
                </div>
                <div class="form-group">
                    <label for="shippingAddress4">
                        <?php echo !empty($loginusers['address4']) ? '<input type="checkbox" id="shippingAddress4" name="shippingAddress4" class="shippingAddressCheckbox">' : ''; ?>
                        <?php echo !empty($loginusers['address4']) ? $loginusers['address4'] : ''; ?>
                        <?php echo !empty($loginusers['address4_line2']) ? ', ' . $loginusers['address4_line2'] : ''; ?>
                        <?php echo !empty($loginusers['address4_line3']) ? ', ' . $loginusers['address4_line3'] : ''; ?>
                        <?php echo !empty($loginusers['address4_line4']) ? ', ' . $loginusers['address4_line4'] : ''; ?>
                        <?php echo !empty($loginusers['address4_city']) ? ', ' . $loginusers['address4_city'] : ''; ?>
                        <?php echo !empty($loginusers['address4_postcode']) ? ', ' . $loginusers['address4_postcode'] : ''; ?>
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
  <script type="text/javascript">   
   
   var today = new Date();

// Calculate the date 1 day from now for the default value
var defaultDate = new Date(today);
defaultDate.setDate(today.getDate() + 1);

// Set the minimum date for the pre-order input field (1 day from now)
var minDate = new Date(today);
minDate.setDate(today.getDate() + 1);

// Set the maximum date for the pre-order input field (9 days from now)
var maxDate = new Date(today);
maxDate.setDate(today.getDate() + 9);

// Set the min and max attributes for the input field
var preOrderInput = document.getElementById('pre_order');
preOrderInput.min = minDate.toISOString().split('T')[0];
preOrderInput.max = maxDate.toISOString().split('T')[0];

// Check if the value from PHP is valid
var phpValue = preOrderInput.value;
var phpDate = new Date(phpValue);
if (phpValue && !isNaN(phpDate) && phpDate >= minDate && phpDate <= maxDate) {
    preOrderInput.value = phpValue;
} else {
    preOrderInput.value = defaultDate.toISOString().split('T')[0];
}

// Disable input field for invalid dates
preOrderInput.addEventListener('input', function() {
    var selectedDate = new Date(preOrderInput.value);
    if (isNaN(selectedDate) || selectedDate > maxDate) {
        preOrderInput.value = ''; // Clear input if date is invalid
        alert('Please select a valid date within the allowed range.');
    }
});

  //   $(document).on('change', '.category_name', function() {
  //     var rowId = $(this).data('row-id');
  //     var categoryDropdown = document.getElementById('category_' + rowId);
  //     var sliceDropdown = document.getElementById('sliced_' + rowId);
  //     var seedDropdown = document.getElementById('seed_' + rowId);

  //     if (categoryDropdown.value.toLowerCase() === 'bun') {
  //         sliceDropdown.disabled = true;
        
  //         $('#msg').html('Slicing not available for Buns');
  //     } else {
  //         sliceDropdown.disabled = false;
      
  //         $('#msg').html('');
  //     }
  // });

  var userInteracted = false;  

  $(document).ready(function() {

    function checkSampleChecked() {
        var sampleChecked = false;
        $('input[name="sample[]"]').each(function() {
            if ($(this).is(':checked')) {
                sampleChecked = true;
                return false; // Exit the loop

            }
        });
        if (sampleChecked) {
            $('#delivery_charge').prop('disabled', false);
            $('#delivery_charge').val(0);
        } else {
            $('#delivery_charge').prop('disabled', true);
        }
    }

    // Call the function on page load
    checkSampleChecked();

    $("#self_pickup").change(function() {
            if ($(this).is(":checked")) {
                $("#delivery_charge").prop("disabled", false); // Enable the delivery charge input
            } else {
                $("#delivery_charge").prop("disabled", true);  // Disable the delivery charge input
            }
        });

        $("#delivery_charge").keyup(function() {

        userInteracted = true;
       
       
        // Call subAmount function whenever delivery charge is updated
        subAmount();
        });

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
    '<td><input type="checkbox" id="sample_'+row_id+'" name="sample[]" value="1" style="width: 42px;height: 20px;margin-top: 6px;" onchange="handleSampleChange('+row_id+')"></td>'+
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
                '<option value="Black drizzle">Black drizzle</option>'+
                '<option value="White black mix">White black mix</option>'+
            '</select>'+
        '</td>'+
        '<td><input type="hidden" name="minn" id="minn" class="form-control" autocomplete="off"><input type="number" name="qty[]" id="qty_'+row_id+'" class="form-control" onkeyup="getTotal('+row_id+')"></td>'+
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
        url: '<?php echo base_url('index.php/orders/getProductsByCategoryadmin'); ?>',
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

$(document).on('keyup', 'input[name="qty[]"]', function() {
        var row_id = $(this).attr('id').split('_')[1];
        var min_order = parseFloat($("#minn").val()) || 1;
        var qty = parseFloat($(this).val());

        // Sample selection check
        if ($('#sample_'+row_id).is(':checked')) {
            $(this).val($(this).val());
        }

        else if (isNaN(qty) || qty < min_order) {
            qty = min_order;
        
        } else if (qty % min_order !== 0) {
            qty = Math.floor(qty / min_order) * min_order;
            $(this).val(qty);
            swal({
                title: "Minimum Order Quantity",
                text: 'Quantity must be a multiple of the minimum order value (' + min_order + ').',
                icon: "warning",
                buttons: {
                    confirm: {
                        text: "OK",
                        value: true,
                        visible: true,
                        className: "btn btn-primary",
                        closeModal: true
                    }
                }
            });
        } else {
            $('#msg').html('');
        }

        subAmount();
        getTotal(row_id);
    });


$('#product_info_table').on('change', 'input[name^="qty"]', function() {
    var rowId = $(this).attr('id').split('_')[1];
    var minOrder = parseInt($('#minn').val()); // Get the stored min_order value

    if ($('#sample_'+rowId).is(':checked')) {
        $(this).val($(this).val());
        }

    // If the input value is less than the min_order value, set it to min_order
    else  if ($(this).val() < minOrder) {
      $(this).val(minOrder);
      swal({
          title: "Minimum Order Quantity",
          text: "You cannot order less than the minimum quantity.",
          icon: "warning",
          buttons: {
            confirm: {
              text: "OK",
              value: true,
              visible: true,
              className: "btn btn-primary",
              closeModal: true
            }
          }
        });
    }

    getTotal(rowId);
});

  function removeRow(tr_id)
    {
      $("#product_info_table tbody tr#row_"+tr_id).remove();
      subAmount();
    }

    function getTotal(row = null) {
    if (row) {
        var service_charge = 0;
        var sliceSelected = $("#sliced_" + row).val();

        if (!$("#sample_" + row).is(":checked")) {
            if (sliceSelected && sliceSelected != 'Unsliced') {
                service_charge = 0.5 * Number($("#qty_" + row).val());
            }
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
            data: { product_id: product_id },
            dataType: 'json',
            success: function(response) {
                if ($("#sample_" + row_id).is(":checked")) {
                    $("#rate_" + row_id).val(0);
                    $("#rate_value_" + row_id).val(0);
                    $("#service_charge_lineitem_" + row_id).val(0);
                    $("#service_charge_itemval_" + row_id).val(0);

                    if (response.add_on_slice == 0) {
                        $("#sliced_" + row_id).prop('hidden', true);
                        $('#msg').html('Slice not available for ' + response.product_id + '-' + response.product_name);
                    } else {
                        $("#sliced_" + row_id).prop('hidden', false);
                    }

                    if (response.add_on_seed == 0) {
                        $("#seed_" + row_id).prop('hidden', true);
                        $('#msg').html('Seed not available for ' + response.product_id + '-' + response.product_name);
                    } else {
                        $("#seed_" + row_id).prop('hidden', false);
                    }

                    if (response.add_on_seed == 0 && response.add_on_slice == 0) {
                        $('#msg').html('Slice and Seed not available for ' + response.product_id + '-' + response.product_name);
                    }

                    $("#qty_" + row_id).prop('step', 1);

                } else {
                    $("#rate_" + row_id).val(response.prod_rate);
                    $("#rate_value_" + row_id).val(response.prod_rate);

                    if (response.add_on_slice == 0) {
                        $("#sliced_" + row_id).prop('hidden', true);
                        $('#msg').html('Slice not available for ' + response.product_id + '-' + response.product_name);
                    } else {
                        $("#sliced_" + row_id).prop('hidden', false);
                    }

                    if (response.add_on_seed == 0) {
                        $("#seed_" + row_id).prop('hidden', true);
                        $('#msg').html('Seed not available for ' + response.product_id + '-' + response.product_name);
                    } else {
                        $("#seed_" + row_id).prop('hidden', false);
                    }

                    if (response.add_on_seed == 0 && response.add_on_slice == 0) {
                        $('#msg').html('Slice and Seed not available for ' + response.product_id + '-' + response.product_name);
                    }

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

                    if (response.min_order == 0) {
                        $("#qty_" + row_id).val(1);
                        $("#qty_value_" + row_id).val(1);
                        var total = Number(response.prod_rate) * 1;
                    }

                    var deliveryCharge = parseFloat($("#delivery_charge").val()) || 0;
                    total += deliveryCharge;
                    total = total.toFixed(2);
                    $("#amount_" + row_id).val(total);
                    $("#amount_value_" + row_id).val(total);
                }
                subAmount();
                getTotal(row_id);
            }
        });
    }
    $("#product_" + row_id).blur();
}


function subAmount() {
    var service_charge = 0; 
    var deliveryCharge;
    var tableProductLength = $("#product_info_table tbody tr").length;

    for (var x = 1; x <= tableProductLength; x++) {
        var sliceSelected = $("#sliced_" + x).val();
        var qty = $("#qty_" + x).val();

        if (!$("#sample_" + x).is(":checked")) {
            if (sliceSelected && sliceSelected != 'Unsliced') {
                service_charge += 0.5 * qty;
            }
        }
    }

    var totalSubAmount = 0;
    for (var x = 1; x <= tableProductLength; x++) {
        totalSubAmount += Number($("#amount_" + x).val());
    }

    var grossAmount = totalSubAmount;
    $("#gross_amount").val(grossAmount.toFixed(2));
    $("#gross_amount_value").val(grossAmount.toFixed(2));

    var discount = $("#discount").val() || 0;
    var netAmount = grossAmount;

    if (netAmount > 50 && netAmount < 80) {
        $("#self_pickup").prop("disabled", false);
    } else {
        $("#self_pickup").prop("disabled", true);
    }

    if (userInteracted) {
        deliveryCharge = parseFloat($("#delivery_charge").val()) || 0;
    } else {
        deliveryCharge = netAmount < 80 ? 20.00 : 0;
    }

    var totall = grossAmount + deliveryCharge;
    var gstRate = 9; 
    var gstAmount = totall * gstRate / 100;

    $("#gst").val(gstAmount.toFixed(2));
    $("#gst_rate").val(gstAmount.toFixed(2));

    $("#delivery_charge").val(deliveryCharge);
    $("#delivery_charge_value").val(deliveryCharge);

    $("#service_charge").val(service_charge.toFixed(2));
    $("#service_charge_value").val(service_charge.toFixed(2));

    var finalAmount = netAmount + gstAmount + deliveryCharge;

    $("#net_amount").val(finalAmount.toFixed(2));
    $("#net_amount_value").val(finalAmount.toFixed(2));
}

function handleSampleChange(row_id) {
    if ($("#sample_" + row_id).is(":checked")) {
        $("#rate_" + row_id).val(0);
        $("#rate_value_" + row_id).val(0);
        $("#service_charge_lineitem_" + row_id).val(0);
        $("#service_charge_itemval_" + row_id).val(0);
        $("#delivery_charge").prop("disabled", false);
    } else {
        // Recalculate the product data if needed when sample is unchecked
        getProductData(row_id);
        
    }
    getTotal(row_id);
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
        shipping_address = "<?php echo $loginusers['delivery_address']; ?>";
        shipping_address_line2 = "<?php echo !empty($loginusers['delivery_address_line2']) ? $loginusers['delivery_address_line2'] : ''; ?>";
        shipping_address_line3 = "<?php echo !empty($loginusers['delivery_address_line3']) ? $loginusers['delivery_address_line3'] : ''; ?>";
        shipping_address_line4 = "<?php echo !empty($loginusers['delivery_address_line4']) ? $loginusers['delivery_address_line4'] : ''; ?>";
        shipping_address_city = "<?php echo !empty($loginusers['delivery_address_city']) ? $loginusers['delivery_address_city'] : ''; ?>";
        shipping_address_postcode = "<?php echo !empty($loginusers['delivery_address_postcode']) ? $loginusers['delivery_address_postcode'] : ''; ?>";
    } else if ($('#shippingAddress2').is(':checked')) {
        shipping_address = "<?php echo $loginusers['address2']; ?>";
        shipping_address_line2 = "<?php echo !empty($loginusers['address2_line2']) ? $loginusers['address2_line2'] : ''; ?>";
        shipping_address_line3 = "<?php echo !empty($loginusers['address2_line3']) ? $loginusers['address2_line3'] : ''; ?>";
        shipping_address_line4 = "<?php echo !empty($loginusers['address2_line4']) ? $loginusers['address2_line4'] : ''; ?>";
        shipping_address_city = "<?php echo !empty($loginusers['address2_city']) ? $loginusers['address2_city'] : ''; ?>";
        shipping_address_postcode = "<?php echo !empty($loginusers['address2_postcode']) ? $loginusers['address2_postcode'] : ''; ?>";
    } else if ($('#shippingAddress3').is(':checked')) {
        shipping_address = "<?php echo $loginusers['address3']; ?>";
        shipping_address_line2 = "<?php echo !empty($loginusers['address3_line2']) ? $loginusers['address3_line2'] : ''; ?>";
        shipping_address_line3 = "<?php echo !empty($loginusers['address3_line3']) ? $loginusers['address3_line3'] : ''; ?>";
        shipping_address_line4 = "<?php echo !empty($loginusers['address3_line4']) ? $loginusers['address3_line4'] : ''; ?>";
        shipping_address_city = "<?php echo !empty($loginusers['address3_city']) ? $loginusers['address3_city'] : ''; ?>";
        shipping_address_postcode = "<?php echo !empty($loginusers['address3_postcode']) ? $loginusers['address3_postcode'] : ''; ?>";
    }
    else if ($('#shippingAddress4').is(':checked')) {
        shipping_address = "<?php echo $loginusers['address4']; ?>";
        shipping_address_line2 = "<?php echo !empty($loginusers['address4_line2']) ? $loginusers['address4_line2'] : ''; ?>";
        shipping_address_line3 = "<?php echo !empty($loginusers['address4_line3']) ? $loginusers['address4_line3'] : ''; ?>";
        shipping_address_line4 = "<?php echo !empty($loginusers['address4_line4']) ? $loginusers['address4_line4'] : ''; ?>";
        shipping_address_city = "<?php echo !empty($loginusers['address4_city']) ? $loginusers['address4_city'] : ''; ?>";
        shipping_address_postcode = "<?php echo !empty($loginusers['address4_postcode']) ? $loginusers['address4_postcode'] : ''; ?>";
    }

   
    $('#shipping_address').val(shipping_address);
    $('#shipping_address_line2').val(shipping_address_line2);
    $('#shipping_address_line3').val(shipping_address_line3);
    $('#shipping_address_line4').val(shipping_address_line4);
    $('#shipping_address_city').val(shipping_address_city);
    $('#shipping_address_postcode').val(shipping_address_postcode);

    $('#myModal').modal('hide');
    swal("Address Updated!", "You can now proceed to create the order.", "success").then((value) => {
        confirmSubmission(event);
    });
}

function confirmSubmission(event) {
    event.preventDefault(); // Prevent the default form submission

    // Get the delivery charge value
    var deliveryCharge = parseFloat(document.getElementById('delivery_charge').value);

   // alert(deliveryCharge);

    // Check if the delivery charge is 20
    if (deliveryCharge === 20) {
        swal({
            title: "Confirmation",
            text: "Under $80 MOQ, a $20 fee will be imposed.",
            icon: "info",
            buttons: ["Cancel", "Continue"],
        }).then((willContinue) => {
            if (willContinue) {
                // Proceed with the rest of the confirmation
                confirmOrder();
            }
        });
    }  else {
        // If delivery charge is not 20, proceed with the existing confirmation
        confirmOrder();
    }
}

function confirmOrder() {
    // Find the closest form element to the clicked button
    var form = document.getElementById('update_orders');

    // Show SweetAlert confirmation dialog
    swal({
        title: "You are about to confirm this order?",
        text: "An invoice will be sent to your Finance department",
        icon: "warning",
        buttons: {
            cancel: {
                text: "Cancel",
                value: false,
                visible: true,
                className: "btn btn-default",
                closeModal: true // This will close the modal if cancel is clicked
            },
            confirm: {
                text: "Create Order",
                value: true,
                visible: true,
                className: "btn btn-success",
                closeModal: true
            }
        }
    }).then((confirmed) => {
        if (confirmed) {
            // Proceed with form submission
            form.submit();
        }
    });
}
  </script>