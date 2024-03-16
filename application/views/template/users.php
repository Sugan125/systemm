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
  .box-body {
    overflow-x: auto;
}

.table-wrapper {
    width: 100%; /* Ensure the wrapper takes full width */
    overflow-x: auto; /* Enable horizontal scrolling */
    white-space: nowrap; /* Prevent line breaks */
}

/* Ensure the table takes up the entire width */
.table-wrapper table {
    width: 100%;
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
        <div class="col-sm-3 col-md-3">
            <form action="<?= base_url('index.php/Userscontroller/search'); ?>" method="get">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search" name="keyword" value="<?= isset($keyword) ? $keyword : '' ?>">
                    <div class="input-group-append">
                        <button class="btn btn-sm" type="submit"><i class="fas fa-search"></i> Search</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-sm-6 col-md-6"></div>
        <div class="col-sm-3 col-md-3 d-flex justify-content-end">
            <a href="<?php echo base_url('index.php/Dashboardcontroller'); ?>" class="btn-sm btn btn-danger"><i class="fas fa-backward"></i> Back</a>
        </div>
    </div>
</div>


        <?php if( (isset($user->image)) || $loginuser['roles'] == 'Admin' || $loginuser['roles'] == 'Owner' || ((in_array('Admin', $loginuser['role']) && in_array('User', $loginuser['role'])))){ ?>
        <!-- /.card-header -->
        <div class="card-body">
       
       


<table id="manageTable" class="table table-bordered table-hover table-striped">
                           
  <thead>
    <tr class="text-center">
      <th>Name</th>
      <th>Company Name</th>
      <th>Company Email</th>
      <th>Office Address</th>
      <th>Delivery Address</th>
      <th>Mobile Number</th>
      <th>Role</th>
      <th>Status</th>
      <!-- <th>File Upload</th>
      <th>View Files</th> -->
      <th>Action</th>
    </tr>
  </thead>
  <tbody>

  
    <?php foreach ($userss as $row):
      if($row->role == 'Owner'){ 
        $rolee = 'Manager';
      }
      else{
        $rolee = $row->role;
      }
      if($row->status == 0 && $row->status != NULL){ 
        $status = 'In-Active';
      }
      else if($row->status == 1 && $row->status != NULL){ 
        $status = 'Active';
      }
      else
      {
        $status = '';
      }
      ?>
      <tr class="odd text-center">
        <td><?= $row->name; ?></td>
        <td><?= $row->company_name; ?></td>
        <td><?= $row->email; ?></td>
        <td><?= $row->address; ?></td>
        <td><?= $row->delivery_address; ?></td>
        <td><?= $row->contact; ?></td>
        <td><?=  $rolee; ?></td>
        <td><?= $status; ?></td>
        <!-- <td>
        <a href="//base_url('index.php/Userscontroller/fileupload/' . $row->id) ?>" class="btn btn-sm"><i class="fas fa-upload"></i> </a>
        </td>
        <td>
        <a href=" //base_url('index.php/Userscontroller/viewfiles/' . $row->id) ?>" class="btn btn-sm"><i class="fa fa-folder-open">View Files</i> </a>
        
        </td> -->
        <td>
          <a href="<?= base_url('index.php/Userscontroller/update/' . $row->id) ?>" class="btn  btn-sm"><i class="fas fa-edit"></i> </a>
          
          <a href="<?= base_url('index.php/Userscontroller/deleteuser/' . $row->id) ?>" class="btn btn-sm" onclick='return confirm("Are you sure to delete this user?");'><i class="fas fa-trash"></i></a>
        </td>
      </tr>
     
    <?php endforeach; ?>
  </tbody>
</table>

            <br>
            <!-- Pagination Links -->
            <div class="row">
            <div class="col-sm-10">
                <?php $total_rowss = $total_rows; echo "Showing 1 to 10 of ".$total_rowss." entries"; ?>
            </div>
                <div class="col-sm-2">
                    <?php echo $this->pagination->create_links(); ?>
                </div>
            </div>

            
        </div>
        <!-- /.card-body -->
        <?php } else {?>
             <!-- /.card-header -->
        <div class="card-body">
       
       

       <table class="table table-bordered table-striped table-responsive equal-width-table">
         <thead>
           <tr class="text-center">
             <th>User Name</th>
             <th>Company Name</th>
             <th>Company Email</th>
             <th>Office Address</th>
             <th>Delivery Address</th>
             <th>Mobile Number</th>
             <!-- <th>Status</th>
             <?php //if ((in_array('Admin', $loginuser['role']) || $loginuser['roles'] == 'Admin') || (in_array('File Upload', $loginuser['access']) == 'File Upload' && in_array('User', $loginuser['role'])) ||  ($loginuser['accesss'] == 'File Upload' && $loginuser['roles' == 'User'])) { ?>
             <th>Uploaded/View Files</th>
             <?php //} ?> -->
             <?php if ((in_array('Admin', $loginuser['role']) || $loginuser['roles'] == 'Admin') || (in_array('Edit', $loginuser['access']) == 'Edit' && in_array('User', $loginuser['role'])) ||  ($loginuser['accesss'] == 'Edit' && $loginuser['roles' == 'User']) || ((!$loginuser['accesss'] == 'Edit' && $loginuser['access'] == 'Delete'))) { ?>
             <th>Action</th>
             <?php } ?>
           </tr>
         </thead>
         <tbody>
             <tr class="odd text-center">
               <td><?=  $loginuser['name']; ?></td>
               <td><?=  $loginuser['company_name']; ?></td>
               <td><?= $loginuser['email']; ?></td>
               <td><?= $loginuser['address']; ?></td>
               <td><?= $loginuser['delivery_address']; ?></td>
               <td><?= $loginuser['contact']; ?></td>
               <?php if ((in_array('File Upload', $loginuser['access'])) ||  ($loginuser['accesss'] == 'File Upload')) { ?>
               <td>
               <a href="<?= base_url('index.php/Userscontroller/fileupload/' . $loginuser['id']) ?>" class="btn btn-warning"><i class="fas fa-upload"></i> </a> 
               <a href="<?= base_url('index.php/Userscontroller/viewfiles/' .$loginuser['id']) ?>" class="btn btn-primary"><i class="fa fa-folder-open">View Files</i> </a>
               </td>
               <?php } ?>

               <?php if ((in_array('Admin', $loginuser['role']) || $loginuser['roles'] == 'Admin') || (in_array('Edit', $loginuser['access']) == 'Edit' && in_array('User', $loginuser['role'])) ||  ($loginuser['accesss'] == 'Edit' && $loginuser['roles' == 'User']) || ((!$loginuser['accesss'] == 'Edit' && $loginuser['access'] == 'Delete')) || (in_array('Delete', $loginuser['access'])) ||  ($loginuser['accesss'] == 'Delete')) { ?>
               <td> <?php } ?>
               <?php if ((in_array('Edit', $loginuser['access'])) ||  ($loginuser['accesss'] == 'Edit')) { ?>
                 <a href="<?= base_url('index.php/Userscontroller/update/' . $loginuser['id']) ?>" class="btn btn-primary"><i class="fas fa-edit"></i> </a>
                 <?php }
                  if ( (in_array('Delete', $loginuser['access'])) ||  ($loginuser['accesss'] == 'Delete')) { 
                  ?>
                 <a href="<?= base_url('index.php/Userscontroller/deleteuser/' . $loginuser['id']) ?>" class="btn btn-danger btn-sm" onclick='return confirm("Are you sure to delete this user?");'><i class="fas fa-trash"></i></a>
                  <?php } ?>
                  <?php if ((in_array('Admin', $loginuser['role']) || $loginuser['roles'] == 'Admin') || (in_array('Edit', $loginuser['access']) == 'Edit' && in_array('User', $loginuser['role'])) ||  ($loginuser['accesss'] == 'Edit' && $loginuser['roles' == 'User']) || ((!$loginuser['accesss'] == 'Edit' && $loginuser['access'] == 'Delete')) ) { ?>
                </td>
                <?php } ?>
             </tr>
        
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
               <?php } ?>
    </div>

</div>
          
</body>
</html>
