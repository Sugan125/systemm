<!DOCTYPE html>
<html lang="en">
<head>
  <style>
  .equal-width-table th,
  .equal-width-table td {
    width: 1%; /* Default to 100% width */
  }
  #file_top{
    margin-top:10px;
  }
  /* Hide default checkbox */
.toggle-switch input[type="checkbox"] {
    display: none;
}

/* Slider */
.slider {
    position: relative;
    display: inline-block;
    width: 40px;
    height: 20px;
    background-color: #ccc;
    border-radius: 20px;
    transition: background-color 0.3s;
}

/* Slider round button */
.slider:before {
    position: absolute;
    content: '';
    height: 16px;
    width: 16px;
    left: 2px;
    bottom: 2px;
    background-color: white;
    border-radius: 50%;
    transition: transform 0.3s;
}

/* Checked state */
.toggle-switch input[type="checkbox"]:checked + .slider {
    background-color: #2196F3;
}

.toggle-switch input[type="checkbox"]:checked + .slider:before {
    transform: translateX(20px);
}

</style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">


<div class="content-wrapper" style="min-height: 1302.4px;">
    
<?= $this->session->flashdata('error'); ?>
<?= $this->session->flashdata('created'); ?>
<?= $this->session->flashdata('updated'); ?>
<?= $this->session->flashdata('deleted'); ?>
<?= $this->session->flashdata('imported'); ?>
<!-- Search Form -->
<div class="card">
    <div class="card-header">
    <div class="d-flex justify-content-end">
            <a href="<?php echo base_url('index.php/Dashboardcontroller'); ?>" class="btn-sm btn btn-danger"><i class="fas fa-backward"></i> Back</a>
        </div>
       
        <div class="col-md-12" style="margin-bottom:20px;">
                        <div class="box-header">
                            <h3 class="box-title">Manage Labels</h3>
                        </div>
                    </div>
<br>


        <div class="row">
            <?php if ((isset($user->image)) || (in_array('Admin', $loginuser['role']) || $loginuser['roles'] == 'Admin') || (in_array('Add', $loginuser['access']) == 'Add' && in_array('User', $loginuser['role'])) || ($loginuser['accesss'] == 'Add' && $loginuser['roles' == 'User']) || $loginuser['roles'] == 'Owner') { ?>
                <div class="col-sm-12 col-md-12 mb-3"><!-- Added mb-3 class for margin bottom -->
                    <div class="row justify-content-between">
                        <div class="col-sm-4 col-md-4">
                        <form action="<?= base_url('index.php/LabelController/search'); ?>" method="get">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search by Product/Category" name="keyword" value="<?= isset($keyword) ? $keyword : '' ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-sm" type="submit"><i class="fas fa-search"></i> Search</button>
                                </div>
                            </div>
                        </form>
                            <!-- <a href="<// base_url('index.php/Productcontroller/create') ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Create Product</a> -->
                        </div>
                       
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>


<div class="card-body">
<table class="table table-bordered table-striped table-responsive equal-width-table">
  <thead>
    <tr class="text-center">
      <th>Product Name</th>
      <th>Ingredients</th>
      <th>Created By</th>
      <th>Updated By</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>

  <?php foreach ($products as $row):
     
     
        ?>
      <tr class="odd text-center">
       
        <td><?= $row->product_name; ?></td>
        <td><?= $row->ingredients; ?></td>
        <td><?= $row->created_by; ?></td>   
        <td><?= $row->updated_by; ?></td>   
        <td>
          <a href="<?= base_url('index.php/LabelController/updatelabel/' . $row->id) ?>" class="btn  btn-sm"><i class="fas fa-edit"></i> </a>
          <a href="<?= base_url('index.php/LabelController/deletelabel/' . $row->id) ?>" class="btn btn-sm" onclick='return confirm("Are you sure to delete this product?");'><i class="fas fa-trash"></i></a>
        </td>    
      </tr>
     
    <?php endforeach; ?>
  </tbody>
</table>
<br>
            <!-- Pagination Links -->
            <div class="row">
    <div class="col-sm-6 d-flex justify-content-start">
        <?php $total_rowss = $total_rows; echo "Showing 1 to 10 of ".$total_rowss." entries"; ?>
    </div>
    <div class="col-sm-6 d-flex justify-content-end">
        <?php echo $this->pagination->create_links(); ?>
    </div>
</div>



        
    </div>

</div>
</body>
</html>
