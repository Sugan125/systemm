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
</style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">


<div class="content-wrapper" style="min-height: 1302.4px;">

<?= $this->session->flashdata('error'); ?>
<?= $this->session->flashdata('created'); ?>
<?= $this->session->flashdata('updated'); ?>
<?= $this->session->flashdata('deleted'); ?>

<!-- Search Form -->
    <div class="card">
        <div class="card-header">
            <div class="row">
           
                <div class="col-sm-9 col-md-9">
                <?php if ((isset($user->image)) || (in_array('Admin', $loginuser['role']) || $loginuser['roles'] == 'Admin')  || (in_array('Add', $loginuser['access']) == 'Add' && in_array('User', $loginuser['role'])) ||  ($loginuser['accesss'] == 'Add' && $loginuser['roles' == 'User']) ||  $loginuser['roles'] == 'Owner') { ?>
                <a href="<?= base_url('index.php/Productcontroller/create') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Create Product</a>
                <?php } ?>

              </div>
               
      

            </div>
        </div>

       <div class="card-body">
       
       

<table class="table table-bordered table-striped table-responsive equal-width-table">
  <thead>
    <tr class="text-center">
      <th>Product ID</th>
      <th>Product Name</th>
      <th>Description</th>
      <th>Category</th>
      <th>Image</th>
      <th>Add-On</th>
      <th>Price</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>

  <?php foreach ($products as $row):?>
    
      <tr class="odd text-center">
        <td><?= $row->product_id; ?></td>
        <td><?= $row->product_name; ?></td>
        <td><?= $row->product_desc; ?></td>
        <td><?= $row->prod_category; ?></td>
        <td><?= $row->prod_img; ?></td>
        <td><?= $row->add_on_slice; ?>,<?= $row->add_on_seed; ?></td>
        <td>$<?= $row->prod_rate; ?></td>   
        <td>
          <a href="" class="btn  btn-sm"><i class="fas fa-edit"></i> </a>
          
          <a href="" class="btn btn-sm" onclick='return confirm("Are you sure to delete this user?");'><i class="fas fa-trash"></i></a>
        </td>    
      </tr>
     
    <?php endforeach; ?>
  </tbody>
</table>

        
    </div>

</div>
          
</body>
</html>
