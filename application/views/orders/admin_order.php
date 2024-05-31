
<head>
<style>
    .msg{
      margin-left: 440px; 
    }
   /* Media query for screens smaller than 768px (typical mobile devices) */
@media (max-width: 767px) {
    .table thead th {
      white-space: nowrap;
    }
    .form-control {
      white-space: nowrap;
      width: inherit;
    }
    .table {
      scrollbar-color: red orange;
      scrollbar-width: thin;
      overflow: auto;
    }
    .table::-webkit-scrollbar {
    -webkit-appearance: none;
}

.table::-webkit-scrollbar:vertical {
    width: 11px;
}

.table::-webkit-scrollbar:horizontal {
    height: 11px;
}

.table::-webkit-scrollbar-thumb {
    border-radius: 8px;
    border: 2px solid white; /* should match background, can't be transparent */
    background-color: rgba(0, 0, 0, .5);
}
.dropdown {
    overflow: scroll !important;
    width: 100%;
}
  }
</style> 
</head>

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
          <form role="form" id="admin_order" action="<?php base_url('orders/admin_orders') ?>" method="post" class="form-horizontal" onsubmit="confirmSubmission(event)">
           
                
              <div class="box-body pull-right">

                <?php echo validation_errors(); ?>

                <div class="form-group">
                  <label for="gross_amount" class="col-sm-12 control-label">Date: <?php echo date('Y-m-d') ?></label>
                </div>
                <div class="form-group">
                  <label for="gross_amount" class="col-sm-12 control-label">Time: <?php date_default_timezone_set('Asia/Singapore'); echo  date('h:i a'); ?></label>
                </div>
               
                 
               </div>

              


               
                <table class="table table-bordered table-hover table-responsive equal-width-table" id="product_info_table">
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
                        <select class="form-control category_name" data-row-id="1" id="category_1" name="category[]">

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
                <!-- <span id="msg" style="margin-left: 440px; color: red;"></span> -->

                <div class="col-sm-12 col-md-12 col-xs-12 pull pull-right">
                <div class="col-sm-6 col-md-6">
                <div class="input-group mb-3" id="input_size">
                    <div class="input-group-prepend">
                        <span class="input-group-text">@</span>
                    </div>
                    <select name="user_id" class="form-control" required>
                        <option value="">Select User Name</option>
                        <?php foreach ($userss as $row) :
                        if($row->role != 'Owner') {?>
                            <option name="user_id" value="<?= $row->id; ?>"><?= $row->name; ?></option>
                            <?php } ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <br>
                <label>Delivery Date</label>
                <input type="text" name="pre_order_date" id="pre_order" class="form-control"  autocomplete="off" style="width:100%;" required>
                
                <label for="packer_memo" class="control-label">Packer Memo</label>
                <textarea class="form-control" id="packer_memo" name="packer_memo" autocomplete="off"></textarea>
               
                <br>

                <label for="driver_memo" class="control-label">Driver Memo</label>
                <textarea class="form-control" id="driver_memo" name="driver_memo" autocomplete="off"></textarea>
                
                <br>
                <label for="po_ref" class="control-label">PO ref</label>
                <input type="text" class="form-control" id="po_ref" name="po_ref"  autocomplete="off">
              
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
                      <input type="checkbox" id="self_pickup" value="1" name="self_pickup" disabled  autocomplete="off">  Self Pick-Up
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

              <div class="box-footer col-sm-12 col-md-12 col-xs-12 pull pull-left" style="margin-bottom:30px;padding: 50px;">
                <button type="submit" class="btn btn-success order_submit" id="order_submit">Create Order</button>
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
<div class="modal fade hide modal-creator" id="myModal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Please choose shipping address</h3>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <form id="shippingAddressForm">
                    <div class="form-group">
                        <label for="shippingAddress1">
                            <input type="checkbox" id="shippingAddress1" name="shippingAddress" class="shippingAddressCheckbox">
                            <span></span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="shippingAddress2">
                            <input type="checkbox" id="shippingAddress2" name="shippingAddress" class="shippingAddressCheckbox">
                            <span></span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="shippingAddress3">
                            <input type="checkbox" id="shippingAddress3" name="shippingAddress" class="shippingAddressCheckbox">
                            <span></span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="shippingAddress4">
                            <input type="checkbox" id="shippingAddress4" name="shippingAddress" class="shippingAddressCheckbox">
                            <span></span>
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="handleNext()">Next</button>
            </div>
        </div>
    </div>
</div>


<script src="<?= base_url();?>public/plugins/pikaday/pikaday.js"></script>
<script src="<?= base_url();?>public/plugins/pikaday/moment.min.js"></script>
<script type="text/javascript">
 document.addEventListener('DOMContentLoaded', function () {
    document.querySelector('select[name="user_id"]').addEventListener('change', function () {
        var userId = this.value;

        if (userId) {
            fetchUserAddress(userId);
        } else {
            clearAddressFields();
            updateCreateOrderButton(false);
        }
    });
});

function fetchUserAddress(userId) {
    var deliveryDateInput = document.getElementById('pre_order');
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '<?php echo base_url('index.php/orders/fetch_user_address') ?>', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.success && hasNonEmptyAddress(response.data)) {
                updateModal(response.data);
                if (!deliveryDateInput.value) {
                        swal("Delivery Date", "Please select a delivery date before creating the order.", "warning");   
                }
                updateCreateOrderButton(true);
            } 
            else{
              updateModal(response.data);
              updateCreateOrderButton(false);
            }
        }
    };
    xhr.send('user_id=' + encodeURIComponent(userId));
}

function hasNonEmptyAddress(data) {
    return data.address2 || data.address3 || data.address4;
}

function updateModal(data) {
    var address1 = data.delivery_address + 
        (data.delivery_address_line2 ? ', ' + data.delivery_address_line2 : '') + 
        (data.delivery_address_line3 ? ', ' + data.delivery_address_line3 : '') + 
        (data.delivery_address_line4 ? ', ' + data.delivery_address_line4 : '') + 
        (data.delivery_address_city ? ', ' + data.delivery_address_city : '') + 
        (data.delivery_address_postcode ? ', ' + data.delivery_address_postcode : '');
    var address2 = (data.address2 ? data.address2 : '') + 
        (data.address2_line2 ? ', ' + data.address2_line2 : '') + 
        (data.address2_line3 ? ', ' + data.address2_line3 : '') + 
        (data.address2_line4 ? ', ' + data.address2_line4 : '') + 
        (data.address2_city ? ', ' + data.address2_city : '') + 
        (data.address2_postcode ? ', ' + data.address2_postcode : '');
    var address3 = (data.address3 ? data.address3 : '') + 
        (data.address3_line2 ? ', ' + data.address3_line2 : '') + 
        (data.address3_line3 ? ', ' + data.address3_line3 : '') + 
        (data.address3_line4 ? ', ' + data.address3_line4 : '') + 
        (data.address3_city ? ', ' + data.address3_city : '') + 
        (data.address3_postcode ? ', ' + data.address3_postcode : '');
    var address4 = (data.address4 ? data.address4 : '') + 
        (data.address4_line2 ? ', ' + data.address4_line2 : '') + 
        (data.address4_line3 ? ', ' + data.address4_line3 : '') + 
        (data.address4_line4 ? ', ' + data.address4_line4 : '') + 
        (data.address4_city ? ', ' + data.address4_city : '') + 
        (data.address4_postcode ? ', ' + data.address4_postcode : '');

    setAddressField('shippingAddress1', address1);
    setAddressField('shippingAddress2', address2);
    setAddressField('shippingAddress3', address3);
    setAddressField('shippingAddress4', address4);
}

function setAddressField(elementId, address) {
    var checkbox = document.getElementById(elementId);
    var label = checkbox.nextElementSibling;

    if (address.trim()) {
        checkbox.style.display = 'inline';
        label.innerText = address;
    } else {
        checkbox.style.display = 'none';
        label.innerText = '';
    }
}

function clearAddressFields() {
    document.querySelectorAll('.shippingAddressCheckbox').forEach(function (checkbox) {
        checkbox.style.display = 'none';
        checkbox.nextElementSibling.innerText = '';
    });
}

function updateCreateOrderButton(showModal) {

   
var createOrderButton = document.querySelector('.btn.btn-success');
var parent = createOrderButton.parentElement;

    

if (showModal) {
    if (!parent.querySelector('.galName')) {
        var modalLink = document.createElement('a');
        modalLink.className = 'galName';
        modalLink.href = '#myModal';
        modalLink.setAttribute('data-toggle', 'modal');
        parent.insertBefore(modalLink, createOrderButton);
        modalLink.appendChild(createOrderButton);
    }
} 
}

function handleNext() {
    var selectedAddress = document.querySelector('input[name="shippingAddress"]:checked');

    if (selectedAddress) {
        var addressParts = selectedAddress.nextElementSibling.innerText.split(', ');
        
        // Log the selected address for debugging
        console.log('Selected Address:', selectedAddress);
        console.log('Address Parts:', addressParts);

        // Populate the form fields with the address details
        document.getElementById('shipping_address').value = addressParts[0] || '';
        document.getElementById('shipping_address_line2').value = addressParts[1] || '';
        document.getElementById('shipping_address_line3').value = addressParts[2] || '';
        document.getElementById('shipping_address_line4').value = addressParts[3] || '';
        document.getElementById('shipping_address_city').value = addressParts[4] || '';
        document.getElementById('shipping_address_postcode').value = addressParts[5] || '';

        // Hide the modal
        $('#myModal').modal('hide');

        // Show success alert and handle submission
        swal("Address Updated!", "You can now proceed to create the order.", "success").then((value) => {
            confirmSubmission(event);
        });
    } 
}


 var today = new Date();

    // Calculate the date 3 days from now for the default value
    var defaultDate = new Date(today);
    defaultDate.setDate(today.getDate() + 3);

    // Set the minimum date for the pre-order input field (3 days from now)
    var minDate = new Date(today);
    minDate.setDate(today.getDate() + 1);

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
            } else if (date < minDate || date > maxDate) {
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



function confirmSubmission(event) {
    event.preventDefault(); // Prevent the default form submission

    // Get the delivery charge value
    var deliveryCharge = parseFloat(document.getElementById('delivery_charge').value);

    // Check if the delivery charge is below 80
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
    } else {
        // If delivery charge is not below 80, proceed with the existing confirmation
        confirmOrder();
    }
}


function confirmOrder() {
    event.preventDefault(); // Prevent the default form submission

    // Find the closest form element to the clicked button
    var form = document.getElementById('admin_order');

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

var userInteracted = false;  

$(document).ready(function() {


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
                    '<select class="form-control select_group category_name" data-row-id="'+row_id+'" id="category_'+row_id+'" name="category[]" style="width:100%;" >'+
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
        url: '<?php echo base_url('index.php/orders/getProductsByCategoryadmin'); ?>',
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
    var deliveryCharge;
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


    if (netAmount > 50 && netAmount < 80) {
        $("#self_pickup").prop("disabled", false); // Enable the element
    } else {
        $("#self_pickup").prop("disabled", true);  // Disable the element
    }

    if (userInteracted) {
        // If user has interacted with the delivery charge input, use the user-entered value
        deliveryCharge = parseFloat($("#delivery_charge").val()) || 0;
    } else {
        // Otherwise, use the default value based on netAmount
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

    var finalAmount = netAmount + gstAmount  + deliveryCharge;

    $("#net_amount").val(finalAmount.toFixed(2));
    $("#net_amount_value").val(finalAmount.toFixed(2));
}





</script>