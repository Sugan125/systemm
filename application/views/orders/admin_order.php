
<head>
<style>
    input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

.number-input {
  position: relative;
  width: fit-content;
}

input {
  width: 60px;
}

.spinners {
  position: absolute;
  right: 0;
  top: 50%;
  display: flex;
  flex-direction: column;
  width: fit-content;
  margin: 1px;
  transform: translateX(-7px) translateY(-11px);
}

.spinner {
  font-size: 7px;
  border: none;
  padding: 0 1px;
}

.spinner:hover {
  background: lightgrey;
}
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
                    <tr id="row_1">
                        <td><input type="checkbox" id="sample_1" name="sample[]" value="1" style="width: 42px;height: 20px;margin-top: 6px;" onchange="handleSampleChange(1)"></td>
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
                                <option value="White full seed">White full seed</option>  
                                <option value="Black drizzle">Black drizzle</option>  
                                <option value="White black mix">White black mix</option>                               
                            </select>
                        </td>
                        <td>
                        <input type="hidden" name="minn" id="minn" class="form-control" autocomplete="off">
                        <input type="hidden" name="minns" id="minn_1" class="form-control" autocomplete="off">
                          <div class="number-input">
                            <input type="number" name="qty[]" id="qty_1" class="form-control" required onkeyup="getTotal(1)">
                            <div class="spinners">
                              <button class="spinner increment">&#9650;</button>
                              <button class="spinner decrement">&#9660;</button>
                            </div>
                          </div>
                        </td>
                          <input type="hidden" name="total_qty[]" id="total_qty_1" class="form-control" autocomplete="off">
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
                <label>Delivery Date (Mandatory)</label>
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
                <button id="submitBtn" type="submit" class="btn btn-success order_submit" id="order_submit">
                <span id="btnText">Create Order</span>
                <i id="btnSpinner" class="fa fa-spinner fa-spin d-none" aria-hidden="true"></i>
                </button>
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


<div class="modal fade" id="promotionModal" tabindex="-1" role="dialog" aria-labelledby="promotionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="promotionModalLabel">Special Promotion!</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="text-center">
          <h4>Exclusive Offer for You!</h4>
          <p id="promotionProductInfo" style="color:green;"></p>
          <p>Buy more and save more with our amazing promotion:</p>
          <div class="promotion-details" id="promotionDetails">
            <!-- Dynamic promotion details will be inserted here -->
          </div>
          <p class="text-muted">Hurry up! Offer valid for 6 Months Only.</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Great, Got it!</button>
      </div>
    </div>
  </div>
</div>

<script src="<?= base_url();?>public/plugins/pikaday/pikaday.js"></script>
<script src="<?= base_url();?>public/plugins/pikaday/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
    
$(document).ready(function () {
  
  // Use event delegation for dynamic rows
  $("#product_info_table").on("click", ".increment", function (event) {
      event.preventDefault();  // Prevent the form from submitting
      
      var row_id = $(this).closest("tr").attr("id").split("_")[1]; // Get row_id based on row's id attribute
      if(row_id == 1){
        var minus = 1;
      }
      else{
        var minus = 0;
      }
      var currentValue = parseInt($("#qty_" + row_id).val() - minus) || 0; // Get the current value of the qty input
      var step = parseInt($("#minn_" + row_id).val()) || 1; // Get the step value (default to 1 if undefined)
      var newValue = currentValue + step;
      
      $("#qty_" + row_id).val(newValue).trigger("change");
      subAmount();
      getTotal(row_id); 
  });

  $("#product_info_table").on("click", ".decrement", function (event) {
      event.preventDefault();  
      
      var row_id = $(this).closest("tr").attr("id").split("_")[1]; // Get row_id based on row's id attribute
      if(row_id == 1){
        var minus = 1;
      }
      else{
        var minus = 0;
      }
      var currentValue = parseInt($("#qty_" + row_id).val()) || 0; // Get the current value of the qty input
      var step = parseInt($("#minn_" + row_id).val()) || 1; // Get the step value (default to 1 if undefined)
      var newValue = (currentValue+minus) - step;
      var minValue = 0; // Minimum value (you can adjust this based on your needs)
      
      if (newValue < minValue) {
          newValue = minValue; 
      }

      $("#qty_" + row_id).val(newValue).trigger("change"); // Update the qty input for this row
      subAmount();
      getTotal(row_id); 
  });
});



  const input = document.querySelector('input[type=number]')

const increment = () => {
  input.value = Number(input.value) + 1
}
const decrement = () => {
  input.value = Number(input.value) - 1
}

document.querySelector('.spinner.increment').addEventListener('click', increment)
document.querySelector('.spinner.decrement').addEventListener('click', decrement)

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelector('select[name="user_id"]').addEventListener('change', function () {
        var userId = this.value;

      //  alert(userId);

        if (userId) {
            fetchUserAddress(userId);
            // Check if the selected user ID is 2402 or 2403
            if (userId == 2402 || userId == 2403) {
                document.getElementById('self_pickup').disabled = false;
            } 
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
                        Swal.fire("Delivery Date", "Please select a delivery date before creating the order.", "warning");   
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
        Swal.fire({
            title: "Address Updated!",
            text: "You can now proceed to create the order.",
            icon: "success"
            }).then((value) => {
                confirmSubmission(event);
            });
    } 
}

 var today = new Date();

// Calculate the date 3 days from now for the default value
var defaultDate = new Date(today);
defaultDate.setDate(today.getDate() + 3);

// Set the minimum date for the pre-order input field (tomorrow)
var minDate = new Date(today);
minDate.setDate(today.getDate() + 0);

// Set the maximum date for the pre-order input field (10 days from now)
var maxDate = new Date(today);
maxDate.setDate(today.getDate() + 20);

// Function to check if a given date is a Sunday (now unused)
function isSunday(date) {
    return date.getDay() === 0; // 0 represents Sunday
}

var picker = new Pikaday({
    field: document.getElementById('pre_order'),
    minDate: minDate,
    maxDate: maxDate,
    defaultDate: defaultDate,
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
        if (date > maxDate) {
            alert('You can only select a date within the next 10 days.');
            picker.setDate(null); // Clear the invalid date
        } else {
            document.getElementById('pre_order').value = moment(date).format('YYYY-MM-DD');
        }
    }
});

// Ensure the default date is set correctly
document.getElementById('pre_order').value = moment(defaultDate).format('YYYY-MM-DD');


function confirmSubmission(event) {
    event.preventDefault(); // Prevent the default form submission

    // Get the delivery charge value
    var deliveryCharge = parseFloat(document.getElementById('delivery_charge').value);

    // Check if the delivery charge is below 80
       if (deliveryCharge === 20) {
    Swal.fire({
        title: "Confirmation",
        text: "Under $80 MOQ, a $20 fee will be imposed.",
        icon: "info",
        showCancelButton: true,  // Show the cancel button
        confirmButtonText: "Continue",  // Text for the confirm button
        cancelButtonText: "Cancel",  // Text for the cancel button
        confirmButtonColor: "#3085d6", // Optional, color for the confirm button
        cancelButtonColor: "#d33"  // Optional, color for the cancel button
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with the rest of the confirmation
                confirmOrder();
            }
        });
    } else {
        // If delivery charge is not 20, proceed with the existing confirmation
        confirmOrder();
    }
}


function confirmOrder() {
    event.preventDefault(); // Prevent the default form submission

    // Find the closest form element to the clicked button
    var form = document.getElementById('admin_order');
    var submitBtn = document.getElementById('submitBtn');
    var btnText = document.getElementById('btnText');
    var btnSpinner = document.getElementById('btnSpinner');

    // Show SweetAlert confirmation dialog
    Swal.fire({
    title: "You are about to confirm this order?",
    text: "An E-invoice will be sent to your Finance on the delivery day. A hard copy invoice will also be provided.",
    icon: "warning",
    showCancelButton: true,  // Show the cancel button
    confirmButtonText: "Create Order",  // Text for the confirm button
    cancelButtonText: "Cancel",  // Text for the cancel button
    confirmButtonColor: "#3085d6", // Optional, color for the confirm button
    cancelButtonColor: "#d33"  // Optional, color for the cancel button
    }).then((result) => {
    if (result.isConfirmed) {
            // Proceed with form submission
            submitBtn.disabled = true;
            btnText.textContent = "Processing...";
            btnSpinner.classList.remove("d-none");
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
            // Check again if the user ID is 2402 or 2403 to decide whether to disable the input
            var userId = $('select[name="user_id"]').val();
            if (userId != 2402 && userId != 2403) {
                $("#delivery_charge").prop("disabled", true); // Disable the delivery charge input
            }
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
            '<td><input type="checkbox" id="sample_'+row_id+'" name="sample[]" value="1" style="width: 42px;height: 20px;margin-top: 6px;" onchange="handleSampleChange('+row_id+')"></td>'+
            '<td>'+
                '<select class="form-control select_group category_name" data-row-id="'+row_id+'" id="category_'+row_id+'" name="category[]" style="width:100%;" >'+
                    '<option value="">Choose</option>';
                    <?php foreach ($category as $key => $value): ?>
                        html += '<option value="<?php echo $value['prod_category'] ?>"><?php echo $value['prod_category'] ?></option>';  
                    <?php endforeach ?>
                html += '</select>'+
            '</td>'+
            '<td>'+ 
                '<select class="form-control select_group product_name product_'+row_id+'" data-row-id="'+row_id+'" id="product_'+row_id+'" name="product[]" style="width:100%;" required onchange="getProductData('+row_id+')">'+
                  
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
            '<td><input type="hidden" name="minn" id="minn" class="form-control" autocomplete="off"><input type="hidden" name="minns" id="minn_'+row_id+'" class="form-control" autocomplete="off">'+
                ' <div class="number-input"><input type="number" name="qty[]" id="qty_'+row_id+'" class="form-control" onkeyup="getTotal('+row_id+')">'+
                '<div class="spinners">'+
                '<button class="spinner increment">&#9650;</button>'+
                '<button class="spinner decrement">&#9660;</button>'+
                '</div></div>'+
            '<input type="hidden" name="total_qty[]" id="total_qty_'+row_id+'" class="form-control" autocomplete="off">'+
            '</td>'+
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
$(document).on('keyup change', 'input[name="qty[]"]', function() {
    var $this = $(this);
    var row_id = $this.attr('id').split('_')[1];
    var min_order = parseFloat($("#minn_" + row_id).val()) || 1;
    var qty = parseFloat($this.val());

    if (!qty || qty < min_order) {
      
        return;
    }

    // Only check and correct when user stops typing (use debounce or check on change)
    clearTimeout($this.data("timeout")); // clear previous timer
    var timeout = setTimeout(function () {
        if (qty % min_order !== 0) {
            qty = Math.floor(qty / min_order) * min_order;
            $this.val(qty);
            Swal.fire({
              title: "Minimum Order Quantity",
              text: 'Quantity must be a multiple of the minimum order value (' + min_order + ').',
              icon: "warning",
              confirmButtonText: "OK"
            });
        } else {
            $('#msg').html('');
        }

        // Apply promotion rules if applicable
        var response = getProductDatas(row_id);
        if (response && response.promotion == 1) {
            applyPromotionRule(row_id, qty, response.promo_rule_buy, response.promo_rule_free, response.product_id, response.product_name);
        }
        updatePromotionAcrossRows(row_id);
        subAmount();
        getTotal(row_id);

    }, 2000); // wait 0.5s after last keyup
    $this.data("timeout", timeout);
});

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


function applyPromotionRule(row_id, qty, promotionRuleN, promotionRuleM, product_id, product_name) {
    // Reset total quantity before recalculating
    $("#total_qty_" + row_id).val(qty);

    if (promotionRuleN && promotionRuleM) {
        var freeQty = Math.floor(qty / promotionRuleN) * promotionRuleM;
        var totalQty = parseInt(qty) + freeQty;
        if(freeQty != 0){
            $("#total_qty_" + row_id).val(totalQty);
        } 
        if (freeQty == 0) {
          $("#total_qty_" + row_id).val(''); // Clear the field with an empty string
      }
    }

    // Optional: Show promotion details in the modal if needed
    // Construct dynamic promotion details
}

function getProductDatas(row_id) {
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
                // Show promotion modal for product IDs 55 and 65
                if (response.promotion == 1) {
                    var qty = parseFloat($("#qty_" + row_id).val());
                    applyPromotionRule(row_id, qty, response.promo_rule_buy, response.promo_rule_free, response.product_id, response.product_name);
                }
                subAmount();
                getTotal(row_id);
            }
        });
    }
    $("#product_" + row_id).blur();
}



$(document).on('input', 'input[name^="qty"]', function() {
    var rowId = $(this).attr('id').split('_')[1];
    getTotal(rowId);
});

$('#product_info_table').on('input', 'input[name^="qty"]', function () {
    var $this = $(this);
    var rowId = $this.attr('id').split('_')[1];
    var minOrder = parseFloat($("#minn_" + rowId).val()) || 1;

    clearTimeout($this.data("timeout"));

    var timeout = setTimeout(function () {
        var qty = parseFloat($this.val());

        // Check for invalid or too small values
        if (isNaN(qty) || qty < minOrder) {
            $this.val(minOrder);
            Swal.fire({
                title: "Minimum Order Quantity",
                text: "You cannot order less than the minimum quantity of " + minOrder + ".",
                icon: "warning",
                confirmButtonText: "OK"
            });
            qty = minOrder;
        }

        // Check if quantity is not a multiple of minOrder
        if (qty % minOrder !== 0) {
            qty = Math.floor(qty / minOrder) * minOrder;
            $this.val(qty);
            Swal.fire({
                title: "Minimum Order Quantity",
                text: 'Quantity must be a multiple of the minimum order value (' + minOrder + ').',
                icon: "warning",
                confirmButtonText: "OK"
            });
        }

        // Promotion logic
        var response = getProductDatas(rowId);
        if (response && response.promotion == 1) {
            applyPromotionRule(rowId, qty, response.promo_rule_buy, response.promo_rule_free, response.product_id, response.product_name);
        }

        updatePromotionAcrossRows(rowId);
        subAmount();
        getTotal(rowId);

    }, 2000); // Wait for typing to stop
    $this.data("timeout", timeout);
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

                    $("#qty_" + row_id).prop('step', 1);

                } else {
                    $("#rate_" + row_id).val(response.prod_rate);
                    $("#rate_value_" + row_id).val(response.prod_rate);

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

                    if (response.min_order !== undefined && response.min_order !== null && response.min_order !== "") {
                        $('#minn').val(response.min_order);
                        $("#minn_" + row_id).val(response.min_order);
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

</script>