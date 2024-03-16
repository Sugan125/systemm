<body onload="window.print();">
  <div class="container" >
    <section class="invoice">
      <div class="row" style="margin: 0px;">
        <div class="col-sm-12 col-xs-12" style="padding: 0px;">
          <h2 class="page-header">
          <img src="https://sourdoughfactory.com.sg/wp-content/uploads/2021/06/400x100px.png" height="70" width="auto">
          </h2>
        </div> 
      <div class="col-sm-7 col-xs-12" style="padding: 0px;">
        <b>Sourdough Factory LLP</b><br>
        <b>UEN No. :</b> T12LL1071J<br>
        <b>GST Reg. :</b> M90373683Y<br>
        <b>Address: </b> 5 Mandai Link #07-05 Mandai Foodlink Singapore 728654<br>
        <b>Tel: </b> 6957 3420<br>
        <b>Email: </b> accounts@breadhearth.com<br>
      </div>
        <div class="col-sm-5 col-xs-12" style="padding: 0px;float: right;">
          <div class="row invoice-info" style="margin: 0px; display: grid;float: right;">
        <?php foreach($order_total as $val => $order_data): ?>
                    <div class="invoice-col" style="margin: 0px;">
                    <b>Invoice No: </b> <?php echo $order_data['bill_no']; ?><br>
                    <b>Date: </b> <?php echo $order_date; ?><br>
                    <!-- <b>Bill ID: </b> <?php //echo $order_data['bill_no']; ?><br> -->
                    <!-- <b>Your Ref: </b> 123<br>
                    <b>D/O No.: </b> 24020699<br>
                    <b>Term: </b> Net 30<br> -->
                    </div>
    
      </div>
        </div> 
        <div class="col-sm-12 col-xs-12" style="padding: 0px;">
        <h2 class="border" style="margin: 20px 0px;"></h2>
        </div> 
        <div class="col-sm-3 col-xs-12" style="padding: 0px;float: right;display: grid;text-align: left;">
        <b>Bill To:</b><?php echo $order_data['address'];?> <br>
      </div>
      <div class="col-sm-6 col-xs-12" style="padding: 0px;float: right;display: grid;text-align: left;">
      
      </div>
      <div class="col-sm-3 col-xs-12 pull-right" style="padding: 0px;float: right;display: grid;text-align: right;">
        <b>Ship To:</b><?php echo $order_data['address'];?> <br>
      </div>
      <div class="col-sm-12 col-xs-12" style="padding: 30px 0px;">
      <b>Salesman:</b> Henri<br>
      <b>Delivery Date: </b><?php 
      $added_days = 2;
      $order_date_obj = DateTime::createFromFormat('d/m/Y', $order_date);
      $order_date_obj->modify("+$added_days days");
      $new_date = $order_date_obj->format('d/m/Y');
      echo $new_date; 
      ?>
      </div> 
      </div>
      <?php endforeach; ?>
      <table class="table table-bordered table-striped table-responsive equal-width-table" style="display: inline-table;">
         <thead>
           <tr class="text-center">
             <th>Qty</th>
             <th>Item No</th>
             <th>Description</th>
             <th>Price</th>
             <th>UOM</th>
             <th>Discount %</th>
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
        <td> </td>
        <td>$<?php echo isset($order['amount']) ? $order['amount'] : ''; ?></td>
    </tr>
<?php endforeach; ?>

         </tbody>
       </table>
       <div class="col-xs-12" style="padding: 10px;float: right;">
          <div class="row total-amount" style="margin: 0px; display: grid;">
        <?php foreach($order_total as $val => $order):
          ?>
                    <div class="total-amount" style="margin: 0px;text-align: right;">
                    <b>Gross Total: </b><?php echo isset($order['gross_amount']) ? $order['gross_amount'] : 0; ?><br>
                    <b>GST: </b><?php echo isset($order['gst_amt']) ? $order['gst_amt'] : 0; ?><br>
                    <b>Service Charge Rate: </b> <?php echo isset($order['service_charge_rate']) ? $order['service_charge_rate'] : 0; ?><br>
                    <b>Delivery Charge: </b><?php echo isset($order['delivery_charge']) ? $order['delivery_charge'] : 0; ?><br>
                    <b>Net Amount: </b><?php echo isset($order['net_amount']) ? $order['net_amount'] : 0; ?><br>  
                  </div>
        <?php endforeach; ?>
      </div>
        </div> 
        <div class="col-sm-12 col-xs-12" style="padding: 0px;">
          <p>Memo:</p>
          <p><b>Thank You!</b> We appreciate your business. For guaranteed freshness, please consume within 8 hours upon recieving of baked goods.</p>
        </div> 
        <table class="table table-bordered table-striped table-responsive equal-width-table" style="display: inline-table;">
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
       </table>
       <div class="col-sm-12 col-xs-12" style="padding: 0px;">
       All cheque should be made payable to "Sourdough Factory LLP"<br>Bank transfer: DBS Bank Account Number: 072 000 7590
       <br><img src="https://sourdoughfactory.com.sg/system/uploads/pay-now.jpg" height="70" width="auto"><br>Paynow: T12LL1071J</div>
       <div class="col-sm-12 col-xs-12" style="padding: 0px;">
        <h2 style="margin-bottom: 50px;"></h2>
        </div> 
      </section>
  </div>
</body>
