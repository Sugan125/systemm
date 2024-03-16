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
<h4>Packing List</h4>
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
                $company_name = null;
                foreach($orders as $val => $row):  
                    $total_price += $row->amount; 
                    $company_name = $row->company_name;
                endforeach; 
                echo $total_price; 
                ?>
            </b></p>
            <p>Production Line (FES)</p>
        </div>
        <?php 
$current_company = null;
foreach($orders as $order):
    if ($order->company_name != $current_company):
        // If a new company, print its header and add an empty row
        if ($current_company !== null) {
            // Close the previous tbody if not the first company
            echo "</tbody></table>";
        }
        echo "<table border='1'>
                <thead>
                    <tr>
                        <th colspan='2'>{$order->company_name}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td colspan='2'></td></tr>"; // Empty row for gap
        $current_company = $order->company_name;
    endif;
    echo "<tr>
            <td>{$order->prod_id}</td>
            <td>{$order->qty} pc</td>
          </tr>";
endforeach; 
// Close the last tbody
echo "</tbody></table>";
?>

        </tbody>
    </table>
  </div>
  
  </div>
</div>

</body>
</html>
