

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
            <h3 class="box-title">Create Order for any user</h3>
          </div>
        <div class="box" style="margin-top:20px;">
          
          <!-- /.box-header -->
          <form role="form" action="<?php base_url('orders/admin_orders') ?>" method="post" class="form-horizontal" onsubmit="confirmSubmission(event)">
           
                
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
                      <th style="width:10%"><button type="button" id="add_row" class="btn btn-info"><i class="fa fa-plus"></i></button></th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr id="row_1">
                        <td>    
                        <select class="form-control category_name" data-row-id="1" id="category_1" name="category[]" onchange="categoryChange(1)">

                                <option value="">Choose</option>
                                <?php foreach ($category as $key => $value): ?>
                                    <option value="<?php echo $value['prod_category'] ?>"><?php echo $value['prod_category'] ?></option>  
                                <?php endforeach ?>
                            </select>
                        </td>
                        <td>
                            <select class="form-control product_name select_group product_1 dropdown dropup" onmousedown="if(this.options.length>8){this.size=8;}" onchange="getProductData(1)" onblur="this.size=0;" data-row-id="row_1" id="product_1" name="product[]" style="width:100%;" required></select>
                        </td>
                        <td>    
                            <select class="form-control sliced" id="sliced_1" name="sliced[]" onmousedown="if(this.options.length>8){this.size=8;}" onchange='slicechange()' onblur="this.size=0;">
                                <option value="">Choose</option>
                                <option value="Unsliced">Unsliced</option>
                                <option value="12mm">12mm</option>
                                <option value="20mm">20mm</option>
                            </select>
                        </td>
                        <td>    
                            <select class="form-control seed" id="seed_1" name="seed[]" onmousedown="if(this.options.length>8){this.size=8;}" onchange='seedchange()' onblur="this.size=0;">
                                <option value="">Choose</option>
                                <option value="Seedless">Seedless</option>
                                <option value="White drizzle">White drizzle</option>
                                <option value="Black drizzle">Black drizzle</option>  
                                <option value="White black mix">White black mix</option>                               
                            </select>
                        </td>
                        <td>
                        <input type="hidden" name="minn" id="minn" class="form-control" autocomplete="off">
                          <input type="number" name="qty[]" id="qty_1" class="form-control" required onkeyup="getTotal(1)"></td>
                        <td>
                            <input type="text" name="rate[]" id="rate_1" class="form-control" disabled autocomplete="off">
                            <input type="hidden" name="rate_value[]" id="rate_value_1" class="form-control" autocomplete="off">
                        </td>
                        <td hidden>
                            <input type="text" name="gst_percent[]" id="gst_percent_1" class="form-control" disabled autocomplete="off">
                            <input type="hidden" name="gst_percent_val[]" id="gst_percent_val_1" class="form-control" autocomplete="off">
                        </td>
                        <td>
                            <input type="text" name="service_charge_lineitem[]" id="service_charge_lineitem_1" class="form-control" disabled autocomplete="off">
                            <input type="hidden" name="service_charge_itemval[]" id="service_charge_itemval_1" class="form-control" autocomplete="off">
                        </td>
                        <td>
                            <input type="text" name="amount[]" id="amount_1" class="form-control" disabled autocomplete="off">
                            <input type="hidden" name="amount_value[]" id="amount_value_1" class="form-control" autocomplete="off">
                        </td>
                        <td hidden>
                            <input type="text" name="gst_amount[]" id="gst_amount_1" class="form-control" disabled autocomplete="off">
                            <input type="hidden" name="gst_amount_value[]" id="gst_amount_value_1" class="form-control" autocomplete="off">
                        </td>
                        <td><button type="button" class="btn btn-danger" onclick="removeRow('1')"><i class="fa fa-close"></i></button></td>
                    </tr>
                </tbody>
               
                </table>
                <span id="msg" style="margin-left: 440px; color: red;"></span>

                <div class="col-sm-12 col-md-12 col-xs-12 pull pull-right">
                <div class="col-sm-6 col-md-6">
                <div class="input-group mb-3" id="input_size" style="width: 50%!important;">
                    <div class="input-group-prepend">
                        <span class="input-group-text">@</span>
                    </div>
                    <select name="user_id" class="form-control" required oninvalid="this.setCustomValidity('Please select a user')">
                        <option value="">Select User Name</option>
                        <?php foreach ($userss as $row) :
                        if($row->role != 'Owner') {?>
                            <option name="user_id" value="<?= $row->id; ?>"><?= $row->name; ?></option>
                            <?php } ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <br>
                <label>Pre Order Date (If required)</label>
                <input type="date" name="pre_order_date" id="pre_order" class="form-control"  autocomplete="off" style="width:100%;">
                </div>
                <div class="col-sm-6 col-md-6">
                <span style="margin-left: 250px;"><b>SGD($)</b></span>
                  <div class="form-group" style="margin-bottom:30px;">
                  
                    <div class="col-sm-4">
                    <label for="gross_amount" class="control-label">Total</label></div>
                    <div class="col-sm-8">
                      
                      <input type="text" class="form-control" id="gross_amount" name="gross_amount" disabled autocomplete="off">
                      <input type="hidden" class="form-control" id="gross_amount_value" name="gross_amount_value" autocomplete="off">
                    </div>
                  </div><br>
                  
   
                  <div class="form-group"  style="margin-bottom:30px;">
                    <div class="col-sm-4">
                    <label for="service_charge" class="control-label">Total Service charge: <?php //echo $company_data['service_charge_value'] ?> </label>
                    </div>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="service_charge"  name="service_charges" disabled autocomplete="off">
                      <input type="hidden" class="form-control" id="service_charge_value" name="service_charge_value" autocomplete="off">
                    </div>
                  </div><br>
             
                
                  <div class="form-group" style="margin-bottom:30px;">
                    <div class="col-sm-4">
                    <label for="discount" class="control-label">Delivery Charge</label></div>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="delivery_charge" name="delivery_charge" disabled  autocomplete="off">
                      <input type="hidden" class="form-control" id="delivery_charge_value" name="delivery_charge_value" autocomplete="off">
                    </div>
                  </div><br>
                  <div class="form-group" style="margin-bottom:30px;">
                    <div class="col-sm-4">
                    <label for="gross_amount" class="control-label">GST (9%)</label></div>
                    <div class="col-sm-8">
                        
                      <input type="text" class="form-control" id="gst" name="gst_amt" disabled autocomplete="off">
                      <input type="hidden" class="form-control" id="gst_value" name="gst_value" value="9" autocomplete="off">
                      <input type="hidden" class="form-control" id="gst_rate" name="gst_rate" value="9" autocomplete="off">
                    </div>
                  </div><br>
                  <div class="form-group">
                  <div class="col-sm-4">
                    <label for="net_amount" class="control-label">Grand Total</label></div>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="net_amount" name="net_amount" disabled autocomplete="off">
                      <input type="hidden" class="form-control" id="net_amount_value" name="net_amount_value" autocomplete="off">
                    </div>
                  </div>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer col-sm-12 col-md-12 col-xs-12 pull pull-left" style="margin-bottom:30px;">
            
                <button type="submit" class="btn btn-success">Create Order</button>
                <a href="<?php echo base_url('index.php/orders/') ?>" class="btn btn-danger">Back</a>
              </div>
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

<script type="text/javascript">
var today = new Date();

// Calculate the date 7 days from now
var sevenDaysFromNow = new Date(today);
sevenDaysFromNow.setDate(sevenDaysFromNow.getDate() + 7);

// Set the minimum date for the pre-order input field
var preOrderInput = document.getElementById('pre_order');
preOrderInput.min = today.toISOString().split('T')[0];
preOrderInput.max = sevenDaysFromNow.toISOString().split('T')[0];

// Disable input field after the 7-day period
preOrderInput.addEventListener('input', function() {
    var selectedDate = new Date(preOrderInput.value);
    if (selectedDate > sevenDaysFromNow) {
        preOrderInput.value = ''; // Clear input if date is beyond the allowed range
        alert('You can only select a date within the next 7 days.');
    }
});
function confirmSubmission(event) {
    event.preventDefault(); // Prevent the default form submission

    // Find the closest form element to the clicked button
    var form = event.target.closest('form');

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
                closeModal: true
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


$(document).ready(function() {

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

        $.ajax({
            url: '<?php echo base_url('index.php/orders/getTableProductRow'); ?>',
            type: 'post',
            dataType: 'json',
            success: function(response) {
              var html = '<tr id="row_'+row_id+'">' +
                '<td>'+ 
                    '<select class="form-control select_group category_name" data-row-id="'+row_id+'" id="category_'+row_id+'" name="category[]" style="width:100%;" onchange="categoryChange('+row_id+')">'+
                        '<option value="">Choose</option>';
                        // Add options for categories here
                        <?php foreach ($category as $key => $value): ?>
                            html += '<option value="<?php echo $value['prod_category'] ?>"><?php echo $value['prod_category'] ?></option>';  
                        <?php endforeach ?>
                    html += '</select>'+
                '</td>'+
                '<td>'+ 
                    '<select class="form-control select_group product_name product_'+row_id+'" data-row-id="'+row_id+'" id="product_'+row_id+'" name="product[]" style="width:100%;"  required onchange="getProductData('+row_id+')">'+
                      
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

            // Trigger change event to populate products initially
            $("#category_" + row_id).trigger("change");
        }
    });

    return false;
});

$('#product_info_table').on('change', '.category_name', function() {
    var currentRow = $(this).closest('tr'); // Store the reference to 'this'
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

              if(product.min_order>1){
              var qty_pkt = ' ('+ product.min_order + 'pcs/pkt)';
              }
              else{
                var qty_pkt = '';
              }
              options += '<option value="' + product.id + '">' + product.product_id + '-' + product.product_name + qty_pkt + '</option>';
            });
            // Update the corresponding product select element using the stored reference
            currentRow.find('.product_' + currentRow.attr('id').split('_')[1]).html(options);
        }
    });
});

$('.category_name').on('change', function() {
  //  alert('t');
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
              if(product.min_order>1){
              var qty_pkt = ' ('+ product.min_order + 'pcs/pkt)';
              }
              else{
                var qty_pkt = '';
              }
              options += '<option value="' + product.id + '">' + product.product_id + '-' + product.product_name + qty_pkt +  '</option>';
            });
            $('.product_1').html(options);
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
         //  var seedSelected = $("#seed_" + row).val();
          //if (sliceSelected || seedSelected) {
            if (sliceSelected) {
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
                    $("#sliced_" + row_id).prop('disabled', true);
                    $('#msg').html('Slice not available for ' + response.product_id + '-' + response.product_name);
                } else {
                  $("#sliced_" + row_id).prop('disabled', false);
                }

                if (response.add_on_seed == 0) {
                  $("#seed_" + row_id).prop('disabled', true);
                    $('#msg').html('Seed not available for ' + response.product_id + '-' + response.product_name);
                } else {
                  $("#seed_" + row_id).prop('disabled', false);
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


                subAmount();
                getTotal(row_id);
            } // /success
        }); // /ajax function to fetch the product data 
    }
    $("#product_" + row_id).blur();
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
        if (sliceSelected) {
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

    var deliveryCharge = netAmount < 20 ? 20.00 : 0;

    var totall = grossAmount + deliveryCharge;
    var gstRate = 9; 
    var gstAmount = totall * gstRate / 100;

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



</script>