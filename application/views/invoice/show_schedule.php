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
<div class="container" style="margin-top: 100px;">
    <div class="row">
        <div class="col-md-6">
            <?php
            date_default_timezone_set('Asia/Singapore');
            $schedule_date = $schedule_date; 
            $formatted_date = date("d-m-Y", strtotime($schedule_date));
            $day_of_week = date("l", strtotime($schedule_date));
            $current_time = date("h:i A"); 
            echo "<p>Date: $formatted_date $day_of_week $current_time</p>";
            ?>
        </div>
        <div class="col-md-6 text-right"> 
            <p><b>Orders: $
                <?php 
                $total_price = 0; 
                foreach($orders as $val => $row):  
                    $total_price += $row->amount; 
                endforeach; 
                echo $total_price; 
                ?>
            </b></p>
            <p>Production Line (CK)</p>
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
