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
    border: 1px solid #ccc;
  }
  table {
    width: 100%;
  }
  th, td {
    padding: 8px;
    text-align: left;
  }

  th{
    background-color:blue;
    color:white;
  }
</style>
</head>
<body onload="window.print();">
<div class="container" style="margin-top: 100px;">
<h4>Schedule List</h4>
    <div class="row">
  
        <div class="col-md-6"> <!-- Left column for date -->
            <?php
            $schedule_date = $schedule_date; 
            $formatted_date = date("d/m/Y", strtotime($schedule_date));
            $day_of_week = date("l", strtotime($schedule_date));
            echo "<p>Date: $formatted_date $day_of_week</p>";
            ?>
        </div>
        <div class="col-md-6 text-right"> <!-- Right column for orders and outlet -->
            <p><b>Orders: $
                <?php 
                $total_price = 0; 
                foreach($orders as $val => $row):  
                    $total_price += $row->amount; 
                endforeach; 
                echo $total_price; 
                ?>
            </b></p>
            <p>Production Line (FES)</p>
        </div>
    <table border="1">
      <thead>
        <tr>
          <th colspan="4"></th>
          <th colspan="5">Outlets</th>
        </tr>
        <tr>
          <th>Sno</th>
          <th>Item</th>
          <th></th>
          <th>Category</th>
          <th>S/O</th>
          <th>Daily</th>
          <th>Customers</th>
          <th>Total Qty</th>
          <th>UOM</th>
        </tr>
      </thead>
      <tbody>
      <?php 
        $total_qty = 0;
        foreach($orders as $val => $row):
            $total_qty += $row->qty;
            ?>
            <tr>
                <td><?= $row->id; ?></td>
                <td><?= $row->product_id; ?></td>
                <td><?= $row->product_name; ?></td>
                <td><?= $row->category; ?></td>
                <td><?= $row->qty; ?></td>
                <td>0</td>
                <td>0</td>
                <td><?= $row->qty; ?></td>
                <td>pc</td>
            </tr>
        <?php endforeach; ?>
        <!-- Total quantity row -->
            <tr>
                <td colspan="4"  class="text-right"><b>Total</b></td> <!-- Set S/O to 0 -->
                <td><b><?= $total_qty; ?></b></td>
                <td><b>0</b></td> <!-- Se/t Daily to 0 -->
                <td><b>0</b></td> <!-- Set Customers to 0 -->
                <td><b><?= $total_qty; ?></b></td> <!-- Total quantity -->
                <td><b>pc</b></td>
            </tr>
    </tbody>
    </table>
  </div>
  
  </div>
</div>

</body>
</html>
