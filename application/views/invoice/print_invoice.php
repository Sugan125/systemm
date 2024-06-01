<body onload="window.print();">
  <div class="container" >
    <section class="invoice">
      <div class="row" style="margin: 0px;">
        <div class="col-sm-12 col-xs-12" style="padding: 0px;">
          <h2 class="page-header">
          <img src="<?php echo base_url('images/logo.jpg'); ?>" height="70" width="auto">
          </h2>
        </div> 
      <div class="col-sm-7 col-xs-12" style="padding: 0px;">
        <b>Sourdough Factory LLP</b><br>
        <b>UEN No. :</b> T12LL1071J<br>
        <b>GST Reg. :</b> M90373683Y<br>
        <b>Address: </b> 5 Mandai Link #07-05 Mandai Foodlink Singapore 728654<br>
        <b>Tel: </b> 6957 3420<br>
        <b>Email: </b> accounts@breadhearth.com<br>
        <b>SFA License No.: </b> PL18J0136<br>
      </div>
        <div class="col-sm-5 col-xs-12" style="padding: 0px;float: right;">
          <div class="row invoice-info" style="margin: 0px; display: grid;float: right;">
        <?php foreach($order_total as $val => $order_data):
            if($order_data['po_ref']){
                $po_ref = $order_data['po_ref'];
            }
            else{
              $po_ref = 'Nil';
            }
          ?>
                    <div class="invoice-col" style="margin: 0px;">
                    <b style="font-size:30px;font-family: Arial;">Tax Invoice </b> <br>
                    <b>Invoice No: </b> <?php echo $order_data['bill_no']; ?><br>
                    <b style="font-weight: bold;">Date and Time: </b> <?php echo empty($order_data['created_date']) ? $order_date : $order_data['created_date']; ?><br>
                    <!-- <b>Bill ID: </b> <br> -->
                    <b>PO Ref: </b> <?php echo $po_ref; ?><br>
                    <b>D/O No.: </b>  <?php echo $order_data['do_bill_no']; ?><br>
                    <b>Term: </b><?php echo $order_data['payment_terms'] ? $order_data['payment_terms'] : 'C.O.D'; ?><br>
                    </div>
    
      </div>
        </div> 
        <div class="col-sm-12 col-xs-12" style="padding: 0px;">
        <h2 class="border" style="margin: 20px 0px;"></h2>
        </div> 
        <div class="col-sm-4 col-xs-12" style="padding: 0px;float: right;display: grid;text-align: left;">
        <b>Bill To:</b><?php echo $order_data['company_name'];  ?><br><?php echo $order_data['address'] .' '. $order_data['address_line2']  .' '. $order_data['address_city'] .' '. $order_data['address_postcode'];;?> <br>
      </div>
      <div class="col-sm-5 col-xs-12" style="padding: 0px;float: right;display: grid;text-align: left;">
      
      </div>
      <div class="col-sm-3 col-xs-12 pull-right" style="padding: 0px;float: right;display: grid;text-align: right;">
      <b>Ship To:</b>
      <?php 
      if (!empty($order_data['shipping_address']) || !empty($order_data['shipping_address_line2']) || !empty($order_data['shipping_address_line3']) || !empty($order_data['shipping_address_line4']) || !empty($order_data['shipping_address_city']) || !empty($order_data['shipping_address_postcode'])) {
          echo $order_data['shipping_address'] .' '. $order_data['shipping_address_line2'] .' '. $order_data['shipping_address_line3'] .' '. $order_data['shipping_address_line4'] .' '. $order_data['shipping_address_city'] .' '. $order_data['shipping_address_postcode']; 
      } else {
          echo $order_data['delivery_address'] .' '. $order_data['delivery_address_line2'] .' '. $order_data['delivery_address_line3'] .' '. $order_data['delivery_address_line4'] .' '. $order_data['delivery_address_city'] .' '. $order_data['delivery_address_postcode'];
      }
      ?>
      <br>

      </div>
      <div class="col-sm-12 col-xs-12" style="padding: 30px 0px;">
      <b>Salesman:</b> Henri<br>
      <b>Delivery Date: </b><?php echo date('d/m/Y', strtotime($order_data['delivery_date'])); ?>
      </div> 
      </div>
      <?php endforeach; ?>
      <table class="table table-bordered table-striped table-responsive equal-width-table" style="display: inline-table;margin: 0px;">
         <thead>
           <tr class="text-center">
             <th>Qty</th>
             <th>Item No</th>
             <th>Description</th>
             <th>Price</th>
             <th>UOM</th>
             <!-- <th>Service Charge</th> -->
             <th>Amount (S$)</th>
           </tr>
         </thead>
         <tbody>
         <?php foreach ($data['order_data']['order_item'] as $order) : 
            $slice = $order['slice_type'];
            $qty = $order['slice_type'];
            $service_charge = $order['service_charge'];
           

            if($service_charge > 0){
            $ammount = $order['amount'] - $service_charge;
            }
            else{
              $ammount = $order['amount'];
            }

            // if($order['sample'] == 1){
            //   $samplee = '   (Sample)';
            // }
            // else{
            //   $samplee = '';
            // }
          ?>
         
        <tr class="odd text-center">
        <td><?php echo isset($order['qty']) ? $order['qty'] : ''; ?></td>
        <td><?php echo isset($order['product_id']) ? $order['product_id'] : ''; ?></td>
        <td>
            <?php 
                if(isset($order['product_name'])) {
                   // echo $order['product_name']. $samplee; 
                   echo $order['product_name'];
                    if(isset($order['slice_type']) && $order['slice_type'] !== '' || isset($order['seed_type']) && $order['seed_type'] !== '') {
                        echo ' (';
                        if(isset($order['slice_type'])) {
                            echo $order['slice_type'];
                            if(isset($order['seed_type']) && $order['seed_type'] !== '') {
                                echo ', ' . $order['seed_type'];
                            }
                        } elseif(isset($order['seed_type']) && $order['seed_type'] !== '') {
                            echo $order['seed_type'];
                        }
                        echo ')';
                    }
                } 
            ?>
        </td>
        <td>$<?php echo isset($order['rate']) ? $order['rate'] : ''; ?></td>
        <td>pc</td>
        <!-- <td>$<php //echo isset($order['service_charge']) ? $order['service_charge'] : 0; ?></td> -->
        <td>$<?php echo isset($order['amount']) ? $ammount : ''; ?></td>
    </tr>

    <?php
         if($slice == '12mm'){
        ?>
           <tr class="odd text-center">
        <td><?php echo isset($order['qty']) ? $order['qty'] : ''; ?></td>
        <td><?php echo  'SL012'; ?></td>
        <td><?php echo 'Slice 12mm Service Charge'; ?></td>
        <td>$<?php echo '0.50'; ?></td>
        <td>pc</td>
        <td>$<?php echo $service_charge; ?></td>
    </tr>
        <?php
         }
         ?>
           <?php
         if($slice == '20mm'){
        ?>
           <tr class="odd text-center">
        <td><?php echo isset($order['qty']) ? $order['qty'] : ''; ?></td>
        <td><?php echo  'SL020'; ?></td>
        <td><?php echo 'Slice 20mm Service Charge'; ?></td>
        <td>$<?php echo '0.50'; ?></td>
        <td>pc</td>
        <td>$<?php echo $service_charge; ?></td>
    </tr>
        <?php
         }
         ?>
<?php endforeach; ?>

<?php
foreach($order_total as $val => $order):
$delivery_charge = $order['delivery_charge'];
if($delivery_charge > 0){

  ?>
      <tr class="odd text-center">
        <td><?php echo '1'; ?></td>
        <td><?php echo  'DS020'; ?></td>
        <td><?php echo 'Delivery Service'; ?></td>
        <td>$<?php echo '20.00'; ?></td>
        <td>pc</td>
        <td>$<?php echo '20.00'; ?></td>
    </tr>
  <?php
}
endforeach;
?>

         </tbody>
       </table>
       <div class="col-xs-12" style="padding: 0px;float: right;border: #e2e2e2 solid 1px;">
          <div class="row total-amount" style="margin: 0px;display: grid;padding: 10px;">
        <?php foreach($order_total as $val => $order):
          ?>
  <table class="col-xs-12 total-amount" style="margin-left: auto;">
  <!-- <tr>
    <th>Delivery Charge:</th>
    <td>< //echo isset($order['delivery_charge']) ? $order['delivery_charge'] . ".00" : "0.00"; ?></td>
  </tr> -->
  <tr>
    <th>Total:</th>
    <td><?php echo isset($order['gross_amount']) ? number_format($order['gross_amount'] + $order['delivery_charge'], 2) : "0.00"; ?></td>
  </tr>
  <tr>
    <th>GST@9%:</th>
    <td><?php echo isset($order['gst_amt']) ? $order['gst_amt'] : 0; ?></td>
  </tr>
  <tr>
    <th>Balance Due:</th>
    <td><?php echo isset($order['net_amount']) ? $order['net_amount'] : 0; ?></td>
  </tr>
</table>
<?php endforeach; ?>
      </div>
        </div> 
       <div class="col-sm-7 col-xs-12" style="padding: 0px;">
       <?php foreach($order_total as $val => $order_data): ?>
       <p><b><?php echo "Memo:".$order_data['memo']; ?></b></p>
       <?php endforeach; ?>
          <p>We appreciate your business. For guaranteed freshness, please consume within 8 hours upon recieving of baked goods.</p>
       </div>
       <div class="col-sm-12 col-xs-12" style="padding: 0px;">
       <p> </p>
       </div>

       <div class="col-sm-7 col-xs-12" style="padding: 0px;">
       <p>All cheque should be made payable to <b>"Sourdough Factory LLP"</b><br>Bank transfer: <b>DBS</b><br>Bank Account Number: <b>072 000 7590</b></p>
       </div>
       <div class="col-sm-5 col-xs-12" style="padding: 0px;float: right;display: grid;">
       <p><img src="<?php echo base_url('images/pay-now.jpg'); ?>" height="100" width="auto" style="float: right;"></p><p><span style="float: right;">Paynow: T12LL1071J</span></p></div>
       <div class="col-sm-12 col-xs-12" style="padding: 0px;">
        <h2 style="margin-bottom: 50px;"></h2>
        </div> 
      </section>
  </div>
</body>
