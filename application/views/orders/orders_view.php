<!DOCTYPE html>
<html>
<head>
    <title>Order Report</title>
    <style>
        table.manage-style {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            font-size: 13px;
            margin-top :20px;
        }

        table.manage-style th, table.manage-style td {
            border: 1px solid #ccc;
            padding: 8px 10px;
            text-align: center;
        }

        table.manage-style thead {
            background-color: #f7f7f7;
        }

        table.manage-style th {
            font-weight: bold;
            color: #333;
        }

        table.manage-style tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table.manage-style tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        table.manage-style tfoot td {
            font-weight: bold;
        }
    </style>
</head>
<body>
 <div class="col-md-12" style="margin-bottom:20px;">
                        <div class="box-header">
                            <h3 class="box-title">Closed Invoices Report<h3>
                        </div>
                    </div>
<br>
<div class="container-fluid">
    <form method="get" action="<?= base_url('index.php/orders/get_closed_invoices') ?>" class="row align-items-end g-2">

        <!-- Customer Dropdown -->
        <div class="col-md-3">
            <label class="form-label text-center d-block"><b>Customer</b></label>
            <select name="user_id" class="form-control" required>
                <option value="">Select Customer</option>
                <?php foreach ($customers as $customer): ?>
                    <option value="<?= $customer->id ?>" <?= set_select('user_id', $customer->id, isset($_GET['user_id']) && $_GET['user_id'] == $customer->id) ?>>
                        <?= $customer->name ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Start Date -->
        <div class="col-md-3">
            <label class="form-label text-center d-block"><b>Start Date</b></label>
            <input type="date" name="from_date" class="form-control" value="<?= $_GET['from_date'] ?? '' ?>" required>
        </div>

        <!-- End Date -->
        <div class="col-md-3">
            <label class="form-label text-center d-block"><b>End Date</b></label>
            <input type="date" name="to_date" class="form-control" value="<?= $_GET['to_date'] ?? '' ?>" required>
        </div>

        <!-- Submit Button -->
        <div class="col-md-2 d-flex flex-column align-items-center">
            <label class="form-label text-center d-block"><b>&nbsp;</b></label> <!-- empty label for alignment -->
            <button type="submit" class="btn btn-primary w-100">Submit</button>
        </div>
    </form>
</div>

 

<?php if (!empty($orders)) : ?>
   <table class="manage-style">
        <thead>
            <tr>
                <th>Order Date</th>
                <th>Invoice No</th>
                <th>Customer PO</th>
                <th>Customer Name</th>
                <th>Amount</th>
                <th>Date Closed</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $row): ?>
                <tr>
                    <td><?= $row->created_date ?></td>
                    <td><?= $row->bill_no ?></td>
                    <td><?= $row->po_ref ?></td>
                    <td><?= $row->name ?></td>
                    <td><?= number_format($row->net_amount, 2) ?></td>
                    <td><?= ($row->pay_close_date !== null && trim($row->pay_close_date) !== '') ? $row->pay_close_date : 'Open' ?></td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <?php if (!empty($_GET['user_id']) && !empty($_GET['from_date']) && !empty($_GET['to_date'])): ?>
        <p style="text-align:center; font-weight:bold;">No orders found for the selected customer and date range.</p>
    <?php endif; ?>
<?php endif; ?>

</body>
</html>
