<body onload="window.print();">
  <div class="container" >
    <section class="invoice">
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <small class="pull-right">Date: <?php echo $order_date; ?></small>
          </h2>
        </div>
      </div>
      <div class="row invoice-info">
        <?php foreach($order_total as $val => $order_data): ?>
                    <div class="col-sm-4 invoice-col">
                    <b>Bill ID: </b> <?php echo $order_data['bill_no']; ?><br>
                    </div>
        <?php endforeach; ?>
      </div>
    </section>
  </div>
</body>
