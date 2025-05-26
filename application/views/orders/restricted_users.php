<!DOCTYPE html>
<html lang="en">

<body class="hold-transition sidebar-mini layout-fixed">


<div class="content-wrapper" style="min-height: 1302.4px;">

<?= $this->session->flashdata('error'); ?>
<?= $this->session->flashdata('created'); ?>
<?= $this->session->flashdata('updated'); ?>
<?= $this->session->flashdata('deleted'); ?>

<!-- Search Form -->
    <div class="card">
        <h2 style="text-align:center;">Pending Payment Details</h2>
        <div class="card-header">
        <div class="pull-right">
        <a href="<?php echo base_url('index.php/Dashboardcontroller'); ?>" class="btn-sm btn btn-danger"><i class="fas fa-backward"></i> Back</a></td>
        </div>
            <div class="row">
                
                <!-- <div class="col-sm-9 col-md-9">
                <a href="// base_url('index.php/Userrolecontroller/createaccess') ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Access</a>
                </div> -->
                <div class="col-sm-3 col-md-3 ">
                        <form action="<?= base_url('index.php/Userrolecontroller/search'); ?>" method="get">
                            <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search" name="keyword" value="<?= isset($keyword) ? $keyword : '' ?>">
                                <div class="input-group-append">
                                <button class="btn btn-sm" type="submit"><i class="fas fa-search"></i> Search</button>
                                </div>
                            </div>
                        </form>
                </div>

            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
       
            <table class="table table-bordered table-striped">

            
                <thead>
                <tr class="text-center">
                    <th>Customer Name</th>
                    <th>Payment Terms</th>
                    <th>Unpaid Invoices</th>
                </tr>
                </thead>
                <tbody>
                        <?php if (!empty($report)) : ?>
                            <?php foreach ($report as $row): ?>
                            <tr class="odd text-center">
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['payment_terms']) ?></td>
                            <td><?= !empty($row['invoices']) ? implode(', ', $row['invoices']) : 'None' ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                </tbody>
                
            </table>
            <br>
            <!-- Pagination Links -->
            <div class="row">
            <div class="col-sm-10">
                <?php echo "Showing 1 to 10 of entries"; ?>
            </div>
                <div class="col-sm-2">
                    <?php echo $this->pagination->create_links(); ?>
                </div>
            </div>

            
        </div>
        <!-- /.card-body -->
    </div>

</div>
          
</body>
</html>
