<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
  <div style="max-width: 1000px; margin: 0 auto;">
    <section>
      <div style="margin: 0;">
        <div style="padding: 0;">
          <h2 style="text-align: left;">
          
          <?php
            $image_path = base_url('images/logo.jpg');
            $image_data = file_get_contents($image_path);
            $base64_image = base64_encode($image_data);
            ?>
            <img src="data:image/jpeg;base64,<?php echo $base64_image; ?>" height="70" width="auto" style="margin-bottom: 20px;">
          </h2>
        </div> 
        <div style="box-sizing: border-box; width: 100%;">
          <div style="box-sizing: border-box; float: left; width: 50%;  ">
            <b style="font-weight: bold;">Sourdough Factory LLP</b><br>
            <b>UEN No. :</b> T12LL1071J<br>
            <b>GST Reg. :</b> M90373683Y<br>
            <b>Address: </b> 5 Mandai Link #07-05 <br>
            Mandai Foodlink Singapore 728654<br>
            <b>Tel: </b> 6957 3420<br>
            <b>Email: </b> accounts@breadhearth.com<br>
          </div>
          <div style="box-sizing: border-box; float: right; width: 50%; text-align: right;">
            <?php foreach($order_total as $val => $order_data): ?>  
              <b style="font-weight: bold;">Invoice No: </b> <?php echo $order_data['bill_no']; ?><br>
              <b style="font-weight: bold;">Date: </b> <?php echo $order_date; ?><br>
            <?php endforeach; ?>
          </div>
        </div>
        <div style="padding: 20px 0; border-bottom: 1px solid #ccc; margin-top:125px;"></div> 
        <div style="box-sizing: border-box; width: 100%; margin-bottom:15px; margin-top:15px; height:60px; ">
        <div style="box-sizing: border-box; float: left; width: 50%;">
            <b>Bill To:</b> <?php echo $order_data['company_name']; ?><br>
            <?php
            $address = $order_data['address'];
            $max_length = 45; 
            $address_lines = wordwrap($address, $max_length, "<br>", true);
            echo $address_lines;
            ?>
            <br>
          </div>
          <div style="box-sizing: border-box; float: right; width: 50%; text-align: right; ">
            <b>Ship To:</b>  <?php
            $address = $order_data['address'];
            $max_length = 45; 
            $address_lines = wordwrap($address, $max_length, "<br>", true);
            echo $address_lines;
            ?> <br>
          </div>
        </div>

        <div style="padding: 20px 0; text-align: left;">
          <b style="font-weight: bold;">Salesman:</b> Henri<br>
          <b style="font-weight: bold;">Delivery Date: </b><?php
           $added_days = 2;
           $order_date_obj = DateTime::createFromFormat('d/m/Y', $order_date);
           $order_date_obj->modify("+$added_days days");
           $new_date = $order_date_obj->format('d/m/Y'); echo $new_date; ?>
        </div> 
        
      </div>
      <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
          <tr style="background-color: #f5f5f5;">
            <th style="padding: 10px; border: 1px solid #ccc;">Qty</th>
            <th style="padding: 10px; border: 1px solid #ccc;">Item No</th>
            <th style="padding: 10px; border: 1px solid #ccc;">Description</th>
            <th style="padding: 10px; border: 1px solid #ccc;">Price</th>
            <th style="padding: 10px; border: 1px solid #ccc;">UOM</th>
            <th style="padding: 10px; border: 1px solid #ccc;">Discount %</th>
            <th style="padding: 10px; border: 1px solid #ccc;">Amount (S$)</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($data['order_data']['order_item'] as $order) : ?>
          <tr>
            <td style="padding: 10px; border: 1px solid #ccc; text-align:center;"><?php echo isset($order['qty']) ? $order['qty'] : ''; ?></td>
            <td style="padding: 10px; border: 1px solid #ccc; text-align:center;"><?php echo isset($order['product_id']) ? $order['product_id'] : ''; ?></td>
            <td style="padding: 10px; border: 1px solid #ccc; text-align:center;"><?php echo isset($order['product_name']) ? $order['product_name'] : ''; ?></td>
            <td style="padding: 10px; border: 1px solid #ccc; text-align:center;">$<?php echo isset($order['rate']) ? $order['rate'] : ''; ?></td>
            <td style="padding: 10px; border: 1px solid #ccc; text-align:center;">pc</td>
            <td style="padding: 10px; border: 1px solid #ccc; text-align:center;"> </td>
            <td style="padding: 10px; border: 1px solid #ccc; text-align:center;">$<?php echo isset($order['amount']) ? $order['amount'] : ''; ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div style="padding: 20px 0; text-align: right;">
        <?php foreach($order_total as $val => $order): ?>
          <div style="text-align: right;">
            <b style="font-weight: bold;">Gross Total: </b><?php echo isset($order['gross_amount']) ? $order['gross_amount'] : 0; ?><br>
            <b style="font-weight: bold;">GST: </b><?php echo isset($order['gst_amt']) ? $order['gst_amt'] : 0; ?><br>
            <b style="font-weight: bold;">Service Charge Rate: </b> <?php echo isset($order['service_charge_rate']) ? $order['service_charge_rate'] : 0; ?><br>
            <?php if($order['discount']>0) { ?>
            <b style="font-weight: bold;">Discount: </b><?php echo isset($order['discount']) ? $order['discount'] : 0; ?><br>
            <?php } ?>
            <b style="font-weight: bold;">Delivery Charge: </b><?php echo isset($order['delivery_charge']) ? $order['delivery_charge'] : 0; ?><br>
            <b style="font-weight: bold;">Net Amount: </b><?php echo isset($order['net_amount']) ? $order['net_amount'] : 0; ?><br>  
          </div>
        <?php endforeach; ?>
      </div> 
      <div style="padding: 0;">
      
        <p><b style="font-weight: bold;">Thank You!</b> We appreciate your business. For guaranteed freshness, please consume within 8 hours upon receiving of baked goods.</p>
      </div> 
      <table style="width: 100%; border-collapse: collapse;">
        <tbody>
          <tr>
            <td style="padding: 10px; border: 1px solid #ccc;">RECEIVED BY</td>
            <td style="padding: 10px; border: 1px solid #ccc;">FOR Sourdough Factory LLP</td>
          </tr>
          <tr>
            <td style="padding: 10px; border: 1px solid #ccc;"> </td>
            <td style="padding: 10px; border: 1px solid #ccc;"> </td>
          </tr>
          <tr>
            <td style="padding: 10px; border: 1px solid #ccc;"> </td>
            <td style="padding: 10px; border: 1px solid #ccc;"> </td>
          </tr>
        </tbody>
      </table>
      <div style="padding: 20px 0;">
        All cheque should be made payable to "Sourdough Factory LLP"<br>
        Bank transfer: DBS Bank Account Number: 072 000 7590
        <br>
        <?php
            $image_path = base_url('images/pay-now.jpg');
            $image_data = file_get_contents($image_path);
            $base64_image = base64_encode($image_data);
            ?>
        <img src="data:image/jpeg;base64,<?php echo $base64_image; ?>" height="70" width="auto">
        <br>Paynow: T12LL1071J
      </div>
      <div style="padding-bottom: 50px;"></div> 
    </section>
  </div>
</body>
