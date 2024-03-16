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
                    <th>User Name</th>
                    <th>Role</th>
                    <th>Access</th>
                    <th>Action</th>
                    
                </tr>
                </thead>
                <tbody>
               
                <?php foreach ($userss as $row){ 
                    if ($row->access != '' && $row->role != ''){ ?>
                    <tr class="odd text-center">
                        <td><?= $row->name; ?></td>
                        <td><?= $row->role; ?></td>
                        <td><?= $row->access; ?></td>
                         <td>
                            <a href="<?= base_url('index.php/Userrolecontroller/updateaccess/' . $row->id) ?>" class="btn btn-sm"><i class="fas fa-edit"></i> </a>
                            <a href="<?= base_url('index.php/Userrolecontroller/deleteaccess/' . $row->id) ?>" class="btn btn-sm" onclick='return confirm("Are you sure to delete this user?");'><i class="fas fa-trash"></i></a>
                        </td>
                        </tr>
                <?php } } ?>
                <!-- <?php foreach ($files as $row): ?>
                        <td><?= $row->img_path; ?></td></tr>
                        
                    <?php endforeach; ?> -->
                </tbody>
                
            </table>
            <br>
            <!-- Pagination Links -->
            <div class="row">
            <div class="col-sm-10">
                <?php echo "Showing 1 to 10 of ".$total_rows." entries"; ?>
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
