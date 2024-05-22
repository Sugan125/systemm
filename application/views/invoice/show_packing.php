<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Printable Page</title>
<style>
  body {
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: white;
  }
  .container {
    max-width: 800px;
    padding: 20px;
  }
  table {
    width: 100%;
    border-collapse: collapse;
  }
  th, td {
    text-align: left;
    border: 1px solid black;
  }
  .col-1 {
    width: 20%;
  }
  .col-2 {
    width: 60%;
  }
  .col-3 {
    width: 20%;
    text-align: center;
  }
  .note {
    text-align: center;
  }
</style>
</head>
<body onload="window.print();">
<div class="container" style="margin-top: 100px;">
    <div class="row">
        <div class="col-md-2" style="padding-left: 0px;">
            <button class="btn" style="background-color: #0065cc; color: white;"><b>Delivery Date</b></button>
        </div>
        <div class="col-md-4" style="padding-right: 0px; padding-left: 0px;">
            <?php
            date_default_timezone_set('Asia/Singapore');
            $schedules_date = null; 
            foreach($orders as $order):
              $schedules_date = $order->delivery_date;
            endforeach;
            $schedule_dates = date("Y-m-d", strtotime($schedules_date));
            $formatted_date = date("d-m-Y", strtotime($schedule_dates));
            $day_of_week = date("l", strtotime($schedule_dates));
            $current_time = date("h:i A"); 
            echo "<p>$day_of_week, $formatted_date $current_time</p>";
            ?>
        </div>
        <div class="col-md-2">
            <b>Order Date</b>
        </div>
        <div class="col-md-4">
            <?php
            date_default_timezone_set('Asia/Singapore');
            foreach($orders as $order):
              $schedules_date = $order->delivery_date;
            endforeach;
            $schedule_date = date("Y-m-d", strtotime($schedules_date . " -3 days"));
            $formatted_date = date("d-m-Y", strtotime($schedule_date));
            $day_of_week = date("l", strtotime($schedule_date));
            echo "<p>$day_of_week, $formatted_date</p>";
            ?>
        </div>
    </div>
    <div class="row">
        <?php 
        $current_company = null;
        $current_order_id = null;
        $current_order = null;
        foreach($orders as $order): 
          if (!empty($order->shipping_address) || !empty($order->shipping_address_line2) || !empty($order->shipping_address_line3) || !empty($order->shipping_address_line4) || !empty($order->shipping_address_city) || !empty($order->shipping_address_postcode)) {
            $shipping = $order->shipping_address .' '. $order->shipping_address_line2 .' '. $order->shipping_address_line3 .' '. $order->shipping_address_line4 .' '. $order->shipping_address_city .' '. $order->shipping_address_postcode; 
        } else {
            $shipping = $order->delivery_address .' '. $order->delivery_address_line2 .' '. $order->delivery_address_line3 .' '. $order->delivery_address_line4 .' '. $order->delivery_address_city .' '. $order->delivery_address_postcode;
        }
            if ($order->company_name != $current_company):
                if ($current_company !== null) {
                    // Close the previous order's memo row
                    if ($current_order && $current_order->packer_memo) {
                        echo "<tr>
                                <td class='col-1' style='border-right: none;'></td>
                                <td class='col-2' style='border-left: none; border-right: none;'>Note: {$current_order->packer_memo}</td>
                                <td class='col-3' style='border-left: none; text-align: right;'></td>
                              </tr>";
                    }
                    // Close the last company's table
                    echo "</tbody></table>";
                }
                // Print the new company's header
                echo "<table border='1'>
                        <thead>
                            <tr><td colspan='3' style='height: 20px;'></td></tr>
                            <tr>
                                <th colspan='3'>{$order->company_name}<br>Brand : {$order->brand_name}<br>Ship To : {$shipping}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td colspan='3' style='height: 20px;'></td></tr>";
                $current_company = $order->company_name;
                $current_order_id = null; // Reset order ID for the new company
            endif;

            if ($order->order_id != $current_order_id):
                // Close the previous order's memo row if it's a different order within the same company
                if ($current_order_id !== null && $current_order->packer_memo) {
                    echo "<tr>
                            <td class='col-1' style='border-right: none;'>{$order->prod_id}</td>
                            <td class='col-2' style='border-left: none; border-right: none;'>Note: {$current_order->packer_memo}</td>
                            <td class='col-3' style='border-left: none; text-align: right;'></td>
                          </tr>";
                }
                $current_order_id = $order->order_id;
                $current_order = $order; // Track the current order
            endif;
            if (($order->slice_type === NULL || $order->slice_type === '') && ($order->seed_type === NULL || $order->seed_type === '')) {
              $add_on = ''; // Both values are NULL or empty, so no add-on
          } elseif ($order->slice_type !== NULL && $order->seed_type !== NULL && $order->slice_type !== '' && $order->seed_type !== '') {
              $add_on = ' ('.$order->slice_type.','.$order->seed_type.')'; // Both values present, separated by a comma
          } else {
              // Only one value is present, so check which one is not NULL or empty
              $add_on = ($order->slice_type !== NULL && $order->slice_type !== '') ? ' ('.$order->slice_type.')' : ' ('.$order->seed_type.')';
          }
            echo "<tr>
                    <td class='col-1' style='border-right: none;'>{$order->prod_id}</td>
                    <td class='col-2' style='border-left: none; border-right: none;'>{$order->product_name}{$add_on}</td>
                    <td class='col-3' style='border-left: none; text-align: right;'>{$order->qty} pc</td>
                  </tr>";
        endforeach;

        // Close the last order's memo row
        if ($current_order && $current_order->packer_memo) {
            echo "<tr>
                    <td class='col-1' style='border-right: none;'></td>
                    <td class='col-2' style='border-left: none; border-right: none;'>Note: {$current_order->packer_memo}</td>
                    <td class='col-3' style='border-left: none; text-align: right;'></td>
                  </tr>";
        }

        echo "</tbody></table>";
        ?>
    </div>
</div>
</body>
</html>
