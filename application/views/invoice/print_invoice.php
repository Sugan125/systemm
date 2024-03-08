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
        <b>UEN No. :</b> T12LL1071J<br>
        <b>GST Reg. :</b> M90373683Y<br>
        <b>Address: </b> 5 Mandai Link #07-05 Mandai Foodlink Singapore 728654<br>
        <b>Tel: </b> 6957 3420<br>
        <b>Email: </b> accounts@breadhearth.com<br>
      </div>
        <div class="col-sm-5 col-xs-12" style="padding: 0px;">
          <div class="row invoice-info" style="margin: 0px; display: grid;">
        <?php foreach($order_total as $val => $order_data): ?>
                    <div class="invoice-col" style="margin: 0px;">
                    <b>Date: </b> <?php echo $order_date; ?><br>
                    <b>Bill ID: </b> <?php echo $order_data['bill_no']; ?><br>
                    <b>Your Ref: </b> 123<br>
                    <b>D/O No.: </b> 24020699<br>
                    <b>Term: </b> Net 30<br>
                    </div>
        <?php endforeach; ?>
      </div>
        </div> 
        <div class="col-sm-12 col-xs-12" style="padding: 0px;">
        <h2 class="border" style="margin: 20px 0px;"></h2>
        </div> 
        <div class="col-sm-7 col-xs-12" style="padding: 0px;">
        <b>Bill To:</b> JR F&B Concepts Pte Ltd<br>
blk 3B River Valley Road #01-04<br>
Singapore 179021<br>
      </div>
      <div class="col-sm-5 col-xs-12" style="padding: 0px;">
        <b>Ship To:</b> JR F&B Concepts Pte Ltd<br>
blk 3B River Valley Road #01-04<br>
Singapore 179021<br>
      </div>
      <div class="col-sm-12 col-xs-12" style="padding: 30px 0px;">
      <b>Salesman:</b> Henri<br>
      <b>Delivery Date:</b> 1/1/2024
      </div> 
      </div>
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
             <tr class="odd text-center">
               <td>4</td>
               <td>RB350</td>
               <td>Rustic Baguette</td>
               <td>$3.00</td>
               <td>pc</td>
               <td> </td>
               <td>$12.00</td>
             </tr>
             <tr class="odd text-center">
               <td>4</td>
               <td>RB350</td>
               <td>Rustic Baguette</td>
               <td>$3.00</td>
               <td>pc</td>
               <td> </td>
               <td>$12.00</td>
             </tr>
             <tr class="odd text-center">
               <td>4</td>
               <td>RB350</td>
               <td>Rustic Baguette</td>
               <td>$3.00</td>
               <td>pc</td>
               <td> </td>
               <td>$12.00</td>
             </tr>
             <tr class="odd text-center">
               <td>4</td>
               <td>RB350</td>
               <td>Rustic Baguette</td>
               <td>$3.00</td>
               <td>pc</td>
               <td> </td>
               <td>$12.00</td>
             </tr>
         </tbody>
       </table>
       <div class="col-xs-12" style="padding: 0px;float: right;">
          <div class="row total-amount" style="margin: 0px; display: grid;">
        <?php foreach($order_total as $val => $order_data): ?>
                    <div class="total-amount" style="margin: 0px;text-align: right;">
                    <b>Total: </b> 123<br>
                    <b>GST: </b> 123<br>
                    <b>Less: Payment: </b> 123<br>
                    <b>Balance Due: </b> 123<br>
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
