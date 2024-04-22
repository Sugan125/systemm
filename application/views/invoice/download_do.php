<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
  <div style="max-width: 1000px; margin: 0 auto;">
    <section>
    <div style="margin: 0;">
            <div style="display: flex; flex-direction: row; align-items: flex-start;">
                <!-- Logo column -->
                
                    <?php
                    $image_path = base_url('images/logo.jpg');
                    $image_data = file_get_contents($image_path);
                    $base64_image = base64_encode($image_data);
                    ?>
                    <img src="data:image/jpeg;base64,<?php echo $base64_image; ?>" height="70" width="auto" style="margin-bottom: 0;">
                
                    <h2 style="text-align: right; margin: 0;">Delivery Order</h2>
             
            </div>
       
        <div style="box-sizing: border-box; width: 100%;">
          <div style="box-sizing: border-box; float: left; width: 50%;  ">
            <b style="font-weight: bold;">Sourdough Factory LLP</b><br>
            <b>UEN No. :</b> T12LL1071J<br>
            <b>GST Reg. :</b> M90373683Y<br>
            <b>Address: </b> 5 Mandai Link #07-05 Mandai Foodlink Singapore 728654<br>
            <b>Tel: </b> 6957 3420<br>
            <b>Email: </b> accounts@breadhearth.com<br>
            <b>SFA License No.: </b> PL18J0136<br>
          </div>
          <div style="box-sizing: border-box; float: right; width: 50%; text-align: right;">
            <?php foreach($order_total as $val => $order_data): ?>  
              <b style="font-weight: bold;">Invoice No: </b> <?php echo $order_data['bill_no']; ?><br>
              <b style="font-weight: bold;">Date: </b> <?php echo $order_date; ?><br>
                                  <!-- <b>Bill ID: </b> <br> -->
              <b>Your Ref: </b> 123<br>
              <b>D/O No.: </b>  <?php echo $order_data['do_bill_no']; ?><br>
              <b>Term: </b> C.O.D<br>
            <?php endforeach; ?>
          </div>
        </div>
        <div style="padding: 20px 0; border-bottom: 1px solid #ccc; margin-top:125px;"></div> 
        <div style="box-sizing: border-box; width: 100%; margin-bottom:15px; margin-top:15px; height:60px; ">
        <div style="box-sizing: border-box; float: left; width: 50%;">
            <b>Bill To:</b> <?php echo $order_data['company_name']; ?><br>
            <?php
          $address = $order_data['address'] .' '. $order_data['address_line2']  .' '. $order_data['address_city'] .' '. $order_data['address_postcode'];
            $max_length = 45; 
            $address_lines = wordwrap($address, $max_length, "<br>", true);
            echo $address_lines;
            ?>
            <br>
          </div>
          <div style="box-sizing: border-box; float: right; width: 50%; text-align: right; ">
            <b>Ship To:</b>  <?php
            $address = $order_data['delivery_address'] .' '. $order_data['delivery_address_line2'] .' '. $order_data['delivery_address_city'] .' '. $order_data['delivery_address_postcode'];
            $max_length = 45; 
            $address_lines = wordwrap($address, $max_length, "<br>", true);
            echo $address_lines;
            ?> <br>
          </div>
        </div>

        <div style="padding: 20px 0; text-align: left;">
          <b style="font-weight: bold;">Salesman:</b> Henri<br>
          <b style="font-weight: bold;">Delivery Date: </b><?php
           $added_days = 3;
           $order_date_obj = DateTime::createFromFormat('d/m/Y', $order_date);
           $order_date_obj->modify("+$added_days days");
           $new_date = $order_date_obj->format('d/m/Y'); echo $new_date; ?>
        </div> 
        
      </div>
      <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
          <tr style="background-color: #f5f5f5;">
            
            <th style="padding: 10px; border: 1px solid #ccc;">Item Code</th>
            <th style="padding: 10px; border: 1px solid #ccc;">Description</th>
            <th style="padding: 10px; border: 1px solid #ccc;">Qty</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($data['order_data']['order_item'] as $order) : ?>
          <tr>
           
            <td style="padding: 10px; border: 1px solid #ccc; text-align:center;"><?php echo isset($order['product_id']) ? $order['product_id'] : ''; ?></td>
            <td style="padding: 10px; border: 1px solid #ccc; text-align:center;"><?php echo isset($order['product_name']) ? $order['product_name'] : ''; ?></td>
            <td style="padding: 10px; border: 1px solid #ccc; text-align:center;"><?php echo isset($order['qty']) ? $order['qty'] : ''; ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div style="padding: 0;">
      
      <p><b>Remarks Open at 12pm</b></p>
          <p>We appreciate your business.</p>
          <p> For guaranteed freshness, please consume within 8 hours upon recieving of baked goods.</p>
      </div> 
    
      <div style="box-sizing: border-box;width: 100%;padding-bottom: 10px;">
      <p> </p>
      </div>
      <div style="box-sizing: border-box;float: left; width: 50%;text-align: left;">
        <p> Goods received in Good Order and Condition</p><br><br><br><br><br>
        <hr style="border: 1px solid #black; margin: 20px 0;">
        <p>Company Stamp and Signature</p>
      </div>
      <div style="box-sizing: border-box;float: right; width: 50%;text-align: right;display: grid;position: absolute;">

        <p><span style="float: right;">For  Sourdough Factory LLP</span></p>
      </div>
      <div style="padding-bottom: 50px;"></div> 
    </section>
  </div>
</body>
