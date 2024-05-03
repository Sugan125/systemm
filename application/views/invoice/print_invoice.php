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
        <?php foreach($order_total as $val => $order_data): ?>
                    <div class="invoice-col" style="margin: 0px;">
                    <b>Invoice No: </b> <?php echo $order_data['bill_no']; ?><br>
                    <b style="font-weight: bold;">Date and Time: </b> <?php echo empty($order_data['created_date']) ? $order_date : $order_data['created_date']; ?><br>
                    <!-- <b>Bill ID: </b> <br> -->
                    <b>Your Ref: </b> 123<br>
                    <b>D/O No.: </b>  <?php echo $order_data['do_bill_no']; ?><br>
                    <b>Term: </b> C.O.D<br>
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
        <b>Ship To:</b><?php echo $order_data['delivery_address'] .' '. $order_data['delivery_address_line2'] .' '. $order_data['delivery_address_city'] .' '. $order_data['delivery_address_postcode']; ?> <br>
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
             <th>Service Charge</th>
             <th>Amount (S$)</th>
           </tr>
         </thead>
         <tbody>
         <?php foreach ($data['order_data']['order_item'] as $order) : ?>
        <tr class="odd text-center">
        <td><?php echo isset($order['qty']) ? $order['qty'] : ''; ?></td>
        <td><?php echo isset($order['product_id']) ? $order['product_id'] : ''; ?></td>
        <td><?php echo isset($order['product_name']) ? $order['product_name'] : ''; ?></td>
        <td>$<?php echo isset($order['rate']) ? $order['rate'] : ''; ?></td>
        <td>pc</td>
        <td>$<?php echo isset($order['service_charge']) ? $order['service_charge'] : 0; ?></td>
        <td>$<?php echo isset($order['amount']) ? $order['amount'] : ''; ?></td>
    </tr>
<?php endforeach; ?>

         </tbody>
       </table>
       <div class="col-xs-12" style="padding: 0px;float: right;border: #e2e2e2 solid 1px;">
          <div class="row total-amount" style="margin: 0px;display: grid;padding: 10px;">
        <?php foreach($order_total as $val => $order):
          ?>
  <table class="col-xs-12 total-amount" style="margin-left: auto;">
  <tr>
    <th>Total:</th>
    <td><?php echo isset($order['gross_amount']) ? $order['gross_amount'] : 0; ?></td>
  </tr>
  <tr>
    <th>GST:</th>
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
        <!-- <table class="table table-bordered table-striped table-responsive equal-width-table" style="display: inline-table;">
         <thead>
           <tr class="text-center">
             <th>RECEIVED BY</th>
             <th>FOR Sourdough Factory LLP</th>
                                     </tr>
         </thead>
         <tbody>
             <tr class="odd text-center">
               <td> </td>
               <td> </td>
             </tr>
             <tr class="odd text-center">
               <td> </td>
               <td> </td>
             </tr>
         </tbody>
       </table> -->
       <div class="col-sm-7 col-xs-12" style="padding: 0px;">
       <p><b>Memo: Open at 1.30pm</b></p>
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
