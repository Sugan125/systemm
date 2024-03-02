

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
            <h3 class="box-title">Add Order</h3>
          </div>
        <div class="box" style="margin-top:20px;">
          
          <!-- /.box-header -->
          <form role="form" action="<?php base_url('orders/create') ?>" method="post" class="form-horizontal">
           
                
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
                      <th style="width:25%">Product</th>
                      <th style="width:10%">Pre-Slice</th>
                      <th style="width:10%">Seed</th>
                      <th style="width:10%">Qty</th>
                      <th style="width:10%">Rate</th>
                      <th style="width:10%">Amount</th>
                      <th style="width:10%"><button type="button" id="add_row" class="btn btn-info"><i class="fa fa-plus"></i></button></th>
                    </tr>
                  </thead>

                  <tbody>

                    <?php if(isset($order_data['order_item'])): ?>
                      <?php $x = 1; ?>
                      <?php foreach ($order_data['order_item'] as $key => $val): ?>
                        <?php //print_r($v); ?>
                       <tr id="row_<?php echo $x; ?>">
                       <td>
                       <select class="form-control category_name" data-row-id="row_1" id="category_1" name="category[]" onmousedown="if(this.options.length>8){this.size=8;}" onchange='this.size=0;' onblur="this.size=0;">
                                <option value="">Choose</option>
                                <?php foreach ($category as $key => $v): ?>
                                    <option value="<?php echo $v['prod_category'] ?>" <?php if ($val['category'] == $v['prod_category']) { echo "selected='selected'"; } ?>><?php echo $v['prod_category'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </td>
                        
                        <td>
                          <select class="form-control select_group product" data-row-id="row_<?php echo $x; ?>" id="product_<?php echo $x; ?>" name="product[]" style="width:100%;" onchange="getProductData(<?php echo $x; ?>)" required>
                              <option value=""></option>
                              <?php foreach ($products as $k => $v): ?>
                                <option value="<?php echo $val['product_id'] ?>" <?php if($val['product_id'] == $v['id']) { echo "selected='selected'"; } ?>><?php echo $v['product_name'] ?></option>
                              <?php endforeach ?>
                            </select>
                          </td>

                            <td>    
                            <select class="form-control sliced" id="sliced_1" name="sliced[]" onmousedown="if(this.options.length>8){this.size=8;}" onchange='slicechange()' onblur="this.size=0;">
                                <option value="">Choose</option> <!-- Add a default "Choose" option -->
                                <option value="12mm" <?php if ($val['slice_type'] == "12mm") { echo "selected='selected'"; } ?>>12mm</option> <!-- Set selected if slice_type is 12mm -->
                                <option value="20mm" <?php if ($val['slice_type'] == "20mm") { echo "selected='selected'"; } ?>>20mm</option> <!-- Set selected if slice_type is 20mm -->
                            </select>

                            </td>
                            <td>    
                            <select class="form-control seed" id="seed_1" name="seed[]" onmousedown="if(this.options.length>8){this.size=8;}" onchange='seedchange()' onblur="this.size=0;">
                                <option value="">Choose</option> <!-- Add a default "Choose" option -->
                                <option value="white" <?php if ($val['seed_type'] == "white") { echo "selected='selected'"; } ?>>White</option> <!-- Set selected if seed_type is white -->
                                <option value="black" <?php if ($val['seed_type'] == "black") { echo "selected='selected'"; } ?>>Black</option> <!-- Set selected if seed_type is black -->
                                <option value="drizzle" <?php if ($val['seed_type'] == "drizzle") { echo "selected='selected'"; } ?>>Drizzle</option> <!-- Set selected if seed_type is drizzle -->
                            </select>

                            </td>
                         
                          <td><input type="text" name="qty[]" id="qty_<?php echo $x; ?>" class="form-control" required onkeyup="getTotal(<?php echo $x; ?>)" value="<?php echo $val['qty'] ?>" autocomplete="off"></td>
                          <td>
                            <input type="text" name="rate[]" id="rate_<?php echo $x; ?>" class="form-control" disabled value="<?php echo $val['rate'] ?>" autocomplete="off">
                            <input type="hidden" name="rate_value[]" id="rate_value_<?php echo $x; ?>" class="form-control" value="<?php echo $val['rate'] ?>" autocomplete="off">
                          </td>
                          <td>
                            <input type="text" name="amount[]" id="amount_<?php echo $x; ?>" class="form-control" disabled value="<?php echo $val['amount'] ?>" autocomplete="off">
                            <input type="hidden" name="amount_value[]" id="amount_value_<?php echo $x; ?>" class="form-control" value="<?php echo $val['amount'] ?>" autocomplete="off">
                          </td>
                          <td><button type="button" class="btn btn-danger" onclick="removeRow('<?php echo $x; ?>')"><i class="fa fa-close"></i></button></td>
                       </tr>
                       <?php $x++; ?>
                     <?php endforeach; ?>
                   <?php endif; ?>
                   </tbody>
                </table>

                <br /> <br/>

                <div class="col-sm-12 col-md-12 col-xs-12 pull pull-right">
                <div class="col-sm-6 col-md-6"></div>
                <div class="col-sm-6 col-md-6">
                  <div class="form-group" style="margin-bottom:30px;">
                    <div class="col-sm-4">
                    <label for="gross_amount" class="control-label">Gross Amount</label></div>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="gross_amount" value="<?php echo $order_data['order']['gross_amount'] ?>" name="gross_amount" disabled autocomplete="off">
                      <input type="hidden" class="form-control" id="gross_amount_value" value="<?php echo $order_data['order']['gross_amount'] ?>" name="gross_amount_value" autocomplete="off">
                    </div>
                  </div><br>
                  <div class="form-group" style="margin-bottom:30px;">
                    <div class="col-sm-4">
                    <label for="gross_amount" class="control-label">GST (9%)</label></div>
                    <div class="col-sm-8">
                    <input type="text" class="form-control" id="gst" name="gst_amt" value="<?php echo $order_data['order']['gst_amt'] ?>" disabled autocomplete="off">
                      <input type="hidden" class="form-control" id="gst_value"  value="<?php echo $order_data['order']['gst_percent'] ?>"  name="gst_value" value="9" autocomplete="off">
                      <input type="hidden" class="form-control" id="gst_rate" value="<?php echo $order_data['order']['gst_amt'] ?>" name="gst_rate" value="9" autocomplete="off">
                    </div>
                  </div><br>
                  <!-- <?php// if($is_service_enabled == true): ?> -->
                  <div class="form-group"  style="margin-bottom:30px;">
                    <div class="col-sm-4">
                    <label for="service_charge" class="control-label">Slicing Service:  <?php //echo $company_data['service_charge_value'] ?> </label>
                    </div>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="service_charge" value="<?php echo $order_data['order']['service_charge_rate'] ?>"  name="service_charge" disabled autocomplete="off">
                      <input type="hidden" class="form-control" id="service_charge_value"  value="<?php echo $order_data['order']['service_charge_rate'] ?>" name="service_charge_value" autocomplete="off">
                    </div>
                  </div><br>
                  <?php //endif; ?>
                  <?php// if($is_vat_enabled == true): ?>
                 <!--  <div class="form-group">
                    <label for="vat_charge" class="col-sm-5 control-label">Vat <?php //echo $company_data['vat_charge_value'] ?> %</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" id="vat_charge" name="vat_charge" disabled autocomplete="off">
                      <input type="hidden" class="form-control" id="vat_charge_value" name="vat_charge_value" autocomplete="off">
                    </div>
                  </div>
                  <?php// endif; ?> -->
                  <div class="form-group" style="margin-bottom:30px;">
                    <div class="col-sm-4">
                    <label for="discount" class="control-label">Discount</label></div>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="discount" name="discount" value="<?php echo $order_data['order']['discount'] ?>" placeholder="Discount" onkeyup="subAmount()" autocomplete="off">
                    </div>
                  </div><br>
                  <div class="form-group">
                  <div class="col-sm-4">
                    <label for="net_amount" class="control-label">Net Amount</label></div>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="net_amount"  value="<?php echo $order_data['order']['net_amount'] ?>" name="net_amount" disabled autocomplete="off">
                      <input type="hidden" class="form-control" id="net_amount_value"  value="<?php echo $order_data['order']['net_amount'] ?>" name="net_amount_value" autocomplete="off">
                    </div>
                  </div>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer col-sm-12 col-md-12 col-xs-12 pull pull-left" style="margin-bottom:30px;">
                <input type="hidden" id="delivery_charge" name="delivery_charge" autocomplete="off">  
                <input type="hidden" name="service_charge_rate"  autocomplete="off">
                 <a target="__blank" href="<?php echo base_url() . 'orders/printDiv/'.$order_data['order']['id'] ?>" class="btn btn-default" >Print</a>
                <button type="submit" class="btn btn-success">Save Changes</button>
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

$(document).ready(function() {

$("#add_row").unbind('click').bind('click', function() {
    var table = $("#product_info_table");
    var count_table_tbody_tr = $("#product_info_table tbody tr").length;
    var row_id = count_table_tbody_tr + 1;

    $.ajax({
        url: '<?php echo base_url('index.php/orders/getTableProductRow'); ?>',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            var html = '<tr id="row_'+row_id+'">'+
                '<td>'+ 
                    '<select class="form-control select_group category_name" data-row-id="'+row_id+'" id="category_'+row_id+'" name="category[]" style="width:100%;" onchange="getProductsByCategory(this)">'+
                        '<option value="">Select Category</option>';
                        // Add options for categories here
                        <?php foreach ($category as $key => $value): ?>
                            html += '<option value="<?php echo $value['prod_category'] ?>"><?php echo $value['prod_category'] ?></option>';  
                        <?php endforeach ?>
                    html += '</select>'+
                '</td>'+
                '<td>'+ 
                    '<select class="form-control select_group product_'+row_id+'" data-row-id="'+row_id+'" id="product_'+row_id+'" name="product[]" style="width:100%;"  required onchange="getProductData('+row_id+')">'+
                      
                    '</select>'+
                '</td>'+ 
                '<td>'+ 
                    '<select class="form-control select_group sliced" data-row-id="'+row_id+'" id="sliced_'+row_id+'" name="sliced[]" style="width:100%;" onchange="slicechange(this)">'+
                        '<option value="">Choose</option>'+
                        '<option value="12mm">12mm</option>'+
                        '<option value="20mm">20mm</option>'+
                    '</select>'+
                '</td>'+
                '<td>'+ 
                    '<select class="form-control select_group seed" data-row-id="'+row_id+'" id="seed_'+row_id+'" name="seed[]" style="width:100%;" onchange="seedchange(this)">'+
                        '<option value="">Choose</option>'+
                        '<option value="white">White</option>'+
                        '<option value="black">Black</option>'+
                        '<option value="drizzle">Drizzle</option>'+
                    '</select>'+
                '</td>'+
                '<td><input type="number" name="qty[]" id="qty_'+row_id+'" class="form-control" onkeyup="getTotal('+row_id+')"></td>'+
                '<td><input type="text" name="rate[]" id="rate_'+row_id+'" class="form-control" disabled><input type="hidden" name="rate_value[]" id="rate_value_'+row_id+'" class="form-control"></td>'+
                '<td><input type="text" name="amount[]" id="amount_'+row_id+'" class="form-control" disabled><input type="hidden" name="amount_value[]" id="amount_value_'+row_id+'" class="form-control"></td>'+
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
                options += '<option value="' + product.id + '">' + product.product_name + '</option>';
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
                options += '<option value="' + product.id + '">' + product.product_name + '</option>';
            });
            $('.product_1').html(options);
        }
    });
});

$('#product_info_table').on('change', '.seed', function() {
        subAmount();
    });
$('#product_info_table').on('change', '.sliced', function() {
    subAmount();
});
}); // /document

function removeRow(tr_id)
  {
    $("#product_info_table tbody tr#row_"+tr_id).remove();
    
  }

  function getTotal(row = null) {
    if(row) {
      var total = Number($("#rate_value_"+row).val()) * Number($("#qty_"+row).val());
      total = total.toFixed(2);
      $("#amount_"+row).val(total);
      $("#amount_value_"+row).val(total);
      
      subAmount();

    } else {
      alert('no row !! please refresh the page');
    }
  }


  function getProductData(row_id)
  {

    var product_id = $("#product_"+row_id).val();  
   // alert(product_id);  
    if(product_id == "") {
      $("#rate_"+row_id).val("");
      $("#rate_value_"+row_id).val("");

      $("#qty_"+row_id).val("");           

      $("#amount_"+row_id).val("");
      $("#amount_value_"+row_id).val("");

    } else {
      $.ajax({
        url: '<?php echo base_url('index.php/orders/getProductValueById'); ?>',
        type: 'post',
        data: {product_id : product_id},
        dataType: 'json',
        success:function(response) {
          // setting the rate value into the rate input field
      
          $("#rate_"+row_id).val(response.prod_rate);
          $("#rate_value_"+row_id).val(response.prod_rate);

          $("#qty_"+row_id).val(1);
          $("#qty_value_"+row_id).val(1);

          var total = Number(response.prod_rate) * 1;
          total = total.toFixed(2);
          $("#amount_"+row_id).val(total);
          $("#amount_value_"+row_id).val(total);
          
          subAmount();
        } // /success
      }); // /ajax function to fetch the product data 
    }
  }

  function subAmount() {
    var service_charge = 0; // Initialize additional charge to 0

    // Check if either slice or seed is selected
    var sliceSelected = $("#sliced_1").val();
    var seedSelected = $("#seed_1").val();
    var deliverycharge = 0;

    if (sliceSelected || seedSelected) {
        // If either slice or seed is selected, set additional charge to 0.50
        service_charge = 0.50;
    }

    var tableProductLength = $("#product_info_table tbody tr").length;
    var totalSubAmount = 0;

    for (var x = 0; x < tableProductLength; x++) {
        var tr = $("#product_info_table tbody tr")[x];
        var count = $(tr).attr('id');
        count = count.substring(4);
        totalSubAmount += Number($("#amount_" + count).val());
    }

    totalSubAmount = totalSubAmount.toFixed(2);
    $("#gross_amount").val(totalSubAmount);
    $("#gross_amount_value").val(totalSubAmount);

    
    // Calculate GST
    var gstRate = 9; // Assuming a GST rate of 9%

    var gstAmount = totalSubAmount * gstRate / 100;
    var netAmountWithGST = Number(totalSubAmount) + gstAmount + Number(service_charge);

    
    if(netAmountWithGST < 20){
        deliverycharge = 20.00;
    }


    $('#delivery_charge').val(deliverycharge);
   // alert(gstAmount);
    // Update GST fields
    $("#gst").val(gstAmount.toFixed(2));
    $("#gst_rate").val(gstAmount);



    // Update net amount including GST
    var discount = $("#discount").val();
    var netAmount;
    if (discount) {
        netAmount = (netAmountWithGST - discount).toFixed(2);
    } else {
        netAmount = netAmountWithGST.toFixed(2);
    }
//alert(service_charge);
    $("#service_charge").val(service_charge.toFixed(2)); // Update additional charge field
    $("#service_charge_value").val(service_charge);

    $("#net_amount").val(netAmount);
    $("#net_amount_value").val(netAmount);
}


</script>