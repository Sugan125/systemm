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
    background-color:white;
  }
  .container {
    max-width: 800px;
    padding: 20px;
  }
  table {
    width: 100%;
    border:none;
  }
  th, td {
    text-align: center;
    border:none;
  }

  th{
    background-color:#0065cc;
    color:white;
  }

   @media print {
    th {
      background-color: #0065cc !important;
      color: white !important;
    }
  }
</style>
</head>
<body onload="window.print();">
<div class="container" style="margin-top: 20px;">
    <div class="row">
    <div class="col-md-12 text-center"><p style="font-weight: bold;">Production Scedule</p></div>
        <div class="col-md-6">
            <?php
            date_default_timezone_set('Asia/Singapore');
            $schedule_date = $schedule_date; 
            
           

            $order_date = strtotime($schedule_date . ' -3 days');

            $formatted_date = date("d-m-Y", $order_date); // Use $order_date directly
            $day_of_week = date("l", $order_date); // Use $order_date directly
            $current_time = date("h:i A"); // Use $order_date directly
           /// echo "<p>Order Date: $formatted_date $day_of_week $current_time</p>";

           echo "<p><b>Order Date:</b> $formatted_date</p>";


         
            ?>
               <?phP
               
               $timestamp = strtotime($schedule_date);
               $delivery_date = date("d-m-Y", $timestamp);
               
               echo "<p><b>Delivery Date:</b> $delivery_date</p>";

               
               ?>
        </div>
        <div class="col-md-6 text-right"> 
        <p><b>Production Line (CK)</b></p>
             

            
          
        </div>
        <table border="1">
    <thead>
        <tr>
            <th style="background-color:white!important;" colspan="4"></th>
            <th style="background-color: var(--blue) !important;" colspan="2">Outlets</th>
            <th style="background-color:white!important;" colspan="3"></th>
        </tr>
        <tr>
            <th>Sno</th>
            <th>Item</th>
            <th style="width: 300px;">Description</th> <!-- Adjust the width here -->
            <th>Category</th>
            <th>Daily</th>
            <th>S/O</th>
            <th>Customers</th>
            <th>Total Qty</th>
            <th>UOM</th>
        </tr>
    </thead>
    <tbody>
    <?php 
        $total_qty = 0;
        $previous_category = '';
        foreach ($orders as $val => $row):
            // Detect category change
            if ($row->category !== $previous_category && $previous_category !== ''):
                // Add a separator row for category change
                echo '<tr><td colspan="9" style="border-top: 2px solid black;"></td></tr>';
            endif;

            $total_qty += $row->qty;
            $description_parts = [];
            if ($row->white_drizzle_qty > 0) {
                $description_parts[] = "{$row->white_drizzle_qty} White Drizzle";
            }
            if ($row->black_drizzle_qty > 0) {
                $description_parts[] = "{$row->black_drizzle_qty} Black Drizzle";
            }
            if ($row->white_full_seed > 0) {
                $description_parts[] = "{$row->white_full_seed} White Full Seed";
            }
            if ($row->white_black_mix > 0) {
                $description_parts[] = "{$row->white_black_mix} White Black Mix";
            }
            if ($row->seedless_qty > 0) {
                $description_parts[] = "{$row->seedless_qty} Seedless";
            }

            // Build the description string if there are parts
            $description = !empty($description_parts) ? '(' . implode(', ', $description_parts) . ')' : '';
            
            // Update previous category
            $previous_category = $row->category;
            ?>
            <tr>
                <td><?= $row->id; ?></td>
                <td><?= $row->product_id; ?></td>
                <td style="width: 300px;">
                    <?= $row->product_name; ?> <?= $description ?>
                </td>
                <td><?= $row->category; ?></td>
                <td>0</td>
                <td>0</td>
                <td><?= $row->qty; ?></td>
                <td><?= $row->qty; ?></td>
                <td>pc</td>
            </tr>
        <?php endforeach; ?>
        <!-- Total quantity row -->
        <tr>
            <td colspan="4"></td>
            <td style="border:1px solid black;"><b>0</b></td>
            <td style="border:1px solid black;"><b>0</b></td> <!-- Se/t Daily to 0 -->
            <td style="border:1px solid black;"><b><?= $total_qty; ?></b></td> <!-- Total quantity -->
            <td style="border:1px solid black;"><b><?= $total_qty; ?></b></td> <!-- Total quantity -->
            <td colspan="1"></td>
        </tr>
    </tbody>
</table>

  </div>
  
  </div>
</div>

</body>
</html>
