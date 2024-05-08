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
<?= $this->session->flashdata('imported'); ?>


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
        <?php if( (isset($user->image)) || $loginuser['roles'] == 'Admin' || $loginuser['roles'] == 'Owner' || ((in_array('Admin', $loginuser['role']) && in_array('User', $loginuser['role'])))){ ?>
        <div class="col-sm-12 col-md-12 text-right">
        <form action="<?= base_url('index.php/Userscontroller/importfile') ?>" method="post" enctype="multipart/form-data">
            <label for="uploadFile" class="btn btn-primary btn-sm" style="margin-bottom: 0px;width: 45%;background: none;border: none;color: black;">
                Import <input type="file" id="uploadFile" name="uploadFile">
            </label>
            <input type="submit" name="submit" class="btn btn-primary btn-sm" value="Upload" />
        </form>
        </div>
      <?php } ?>
    </div>
</div>


<?php if( (isset($user->image)) || $loginuser['roles'] == 'Admin' || $loginuser['roles'] == 'Owner' || ((in_array('Admin', $loginuser['role']) && in_array('User', $loginuser['role'])))){ ?>
<!-- /.card-header -->
<div class="card-body">
<table id="manageTable" class="table table-bordered table-responsive table-hover table-striped">
                           
  <thead>
    <tr class="text-center">
      <th>Name</th>
      <th>Company Name</th>
      <th>Brand Name</th>
      <th>Company Email</th>
      <th>Office Address</th>
      <th>Shipping Address</th>
      <th>Shipping Address2</th>
      <th>Shipping Address3</th>
      <th>Shipping Address4</th>
      <th>Mobile Number</th>
      <th>Role</th>
      <th>Payment Terms</th>
      <th>Driver Memo</th>
      <th>Packer Memo</th>
      <th>Restrict time</th>
      <th>Status</th>
      <!-- <th>File Upload</th>
      <th>View Files</th> -->
      <th>Action</th>
    </tr>
  </thead>
  <tbody>

  
    <?php foreach ($userss as $row){
   //  print_r($row);
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
        <td><?= $row->brand_name; ?></td>
        <td><?= $row->email; ?></td>
        <td><?= ($row->address ? $row->address . ', ' : '') . ($row->address_line2 ? $row->address_line2 . ', ' : '') . ($row->address_line3 ? $row->address_line3 . ', ' : '') . ($row->address_line4 ? $row->address_line4 . ', ' : '') . ($row->address_city ? $row->address_city . ', ' : '') . ($row->address_postcode ? $row->address_postcode : ''); ?></td>
        <td><?= ($row->delivery_address ? $row->delivery_address . ', ' : '') . ($row->delivery_address_line2 ? $row->delivery_address_line2 . ', ' : '') . ($row->delivery_address_line3 ? $row->delivery_address_line3 . ', ' : '') . ($row->delivery_address_line4 ? $row->delivery_address_line4 . ', ' : '') . ($row->delivery_address_city ? $row->delivery_address_city . ', ' : '') . ($row->delivery_address_postcode ? $row->delivery_address_postcode : ''); ?></td>
        <td><?= ($row->address2 ? $row->address2 . ', ' : '') . ($row->address2_line2 ? $row->address2_line2 . ', ' : '') . ($row->address2_line3 ? $row->address2_line3 . ', ' : '') . ($row->address2_line4 ? $row->address2_line4 . ', ' : '') . ($row->address2_city ? $row->address2_city . ', ' : '') . ($row->address2_postcode ? $row->address2_postcode : ''); ?></td>
        <td><?= ($row->address3 ? $row->address3 . ', ' : '') . ($row->address3_line2 ? $row->address3_line2 . ', ' : '') . ($row->address3_line3 ? $row->address3_line3 . ', ' : '') . ($row->address3_line4 ? $row->address3_line4 . ', ' : '') . ($row->address3_city ? $row->address3_city . ', ' : '') . ($row->address3_postcode ? $row->address3_postcode : ''); ?></td>
        <td><?= ($row->address4 ? $row->address4 . ', ' : '') . ($row->address4_line2 ? $row->address4_line2 . ', ' : '') . ($row->address4_line3 ? $row->address4_line3 . ', ' : '') . ($row->address4_line4 ? $row->address4_line4 . ', ' : '') . ($row->address4_city ? $row->address4_city . ', ' : '') . ($row->address4_postcode ? $row->address4_postcode : ''); ?></td>
        <td><?= $row->contact; ?></td>
        <td><?=  $rolee; ?></td>
        <td><?=  $row->payment_terms; ?></td>
        <td><?=  $row->driver_memo; ?></td>
        <td><?=  $row->packer_memo; ?></td>
        <!-- Change id="restrictCheckbox" to class="restrictCheckbox" -->
        <td> <input type="checkbox" class="restrictCheckbox" data-id="<?php echo $row->id; ?>" <?php if($row->restrict_time == 1){ echo 'checked'; } ?>></td>
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
     
    <?php } ?>
  </tbody>
</table>

            <br>
            <!-- Pagination Links -->
            <div class="row">
            <div class="col-sm-6">
                <?php $total_rowss = $total_rows; echo "Showing 1 to 10 of ".$total_rowss." entries"; ?>
            </div>
                <div class="col-sm-6">
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
             <th>Brand Name</th>
             <th>Company Email</th>
             <th>Office Address</th>
             <th>Shipping Address</th>
             <th>Shipping Address2</th>
             <th>Shipping Address3</th>
             <th>Shipping Address4</th>
             <th>Mobile Number</th>
             <th>Payment Terms</th>
             <th>Memo</th>
             <?php if ((in_array('Admin', $loginuser['role']) || $loginuser['roles'] == 'Admin') || (in_array('Edit', $loginuser['access']) == 'Edit' && in_array('User', $loginuser['role'])) ||  ($loginuser['accesss'] == 'Edit' && $loginuser['roles' == 'User']) || ((!$loginuser['accesss'] == 'Edit' && $loginuser['access'] == 'Delete'))) { ?>
             <th>Action</th>
             <?php } ?>
           </tr>
         </thead>
         <tbody>
             <tr class="odd text-center">
               <td><?=  $loginuser['name']; ?></td>
               <td><?=  $loginuser['company_name']; ?></td>
               <td><?=  $loginuser['brand_name']; ?></td>
               <td><?= $loginuser['email']; ?></td>
               <td><?= ($loginuser['address'] ? $loginuser['address'] . ', ' : '') . ($loginuser['address_line2'] ? $loginuser['address_line2'] . ', ' : '') . ($loginuser['address_line3'] ? $loginuser['address_line3'] . ', ' : '') . ($loginuser['address_line4'] ? $loginuser['address_line4'] . ', ' : '') . ($loginuser['address_city'] ? $loginuser['address_city'] . ', ' : '') . ($loginuser['address_postcode'] ? $loginuser['address_postcode'] : ''); ?></td>
              <td><?= ($loginuser['delivery_address'] ? $loginuser['delivery_address'] . ', ' : '') . ($loginuser['delivery_address_line2'] ? $loginuser['delivery_address_line2'] . ', ' : '') . ($loginuser['delivery_address_line3'] ? $loginuser['delivery_address_line3'] . ', ' : '') . ($loginuser['delivery_address_line4'] ? $loginuser['delivery_address_line4'] . ', ' : '') . ($loginuser['delivery_address_city'] ? $loginuser['delivery_address_city'] . ', ' : '') . ($loginuser['delivery_address_postcode'] ? $loginuser['delivery_address_postcode'] : ''); ?></td>
              <td><?= ($loginuser['address2'] ? $loginuser['address2'] . ', ' : '') . ($loginuser['address2_line2'] ? $loginuser['address2_line2'] . ', ' : '') . ($loginuser['address2_line3'] ? $loginuser['address2_line3'] . ', ' : '') . ($loginuser['address2_line4'] ? $loginuser['address2_line4'] . ', ' : '') . ($loginuser['address2_city'] ? $loginuser['address2_city'] . ', ' : '') . ($loginuser['address2_postcode'] ? $loginuser['address2_postcode'] : ''); ?></td>
              <td><?= ($loginuser['address3'] ? $loginuser['address3'] . ', ' : '') . ($loginuser['address3_line2'] ? $loginuser['address3_line2'] . ', ' : '') . ($loginuser['address3_line3'] ? $loginuser['address3_line3'] . ', ' : '') . ($loginuser['address3_line4'] ? $loginuser['address3_line4'] . ', ' : '') . ($loginuser['address3_city'] ? $loginuser['address3_city'] . ', ' : '') . ($loginuser['address3_postcode'] ? $loginuser['address3_postcode'] : ''); ?></td>
              <td><?= ($loginuser['address4'] ? $loginuser['address4'] . ', ' : '') . ($loginuser['address4_line2'] ? $loginuser['address4_line2'] . ', ' : '') . ($loginuser['address4_line3'] ? $loginuser['address4_line3'] . ', ' : '') . ($loginuser['address4_line4'] ? $loginuser['address4_line4'] . ', ' : '') . ($loginuser['address4_city'] ? $loginuser['address4_city'] . ', ' : '') . ($loginuser['address4_postcode'] ? $loginuser['address4_postcode'] : ''); ?></td>
              <td><?= $loginuser['contact']; ?></td>
              <td><?= $loginuser['payment_terms']; ?></td>
              <td><?= $loginuser['driver_memo']; ?></td>
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
                           <?php //echo $this->pagination->create_links(); ?>
                       </div>
                   </div>
       
                   
               </div>
               <!-- /.card-body -->
               <?php } ?>
    </div>

</div>

<script>
$(document).ready(function(){
  $('.restrictCheckbox').click(function(){
  //  alert('Checkbox clicked');
    var userId = $(this).data('id');
    var isChecked = $(this).prop('checked') ? 1 : 0; 
    $.ajax({
        url: '<?php echo base_url('index.php/Userscontroller/update_restrict_time'); ?>',
        method: 'POST',
        data: { userId: userId, isChecked: isChecked },
        dataType: 'json', 
        success: function(response){
            if(response.status === 'success'){
                if(response.isChecked === '1'){
                  $('.restrictCheckbox[data-id="' + userId + '"]').prop('checked', true);
                swal({
                    title: "Success",
                    text: "Restrict time Enabled.",
                    icon: "success",
                    buttons: {
                        confirm: {
                            text: "OK",
                            value: true,
                            visible: true,
                            className: "btn btn-primary",
                            closeModal: true
                        }
                    }
                }).then((value) => {
                    // Reload the page after success
                    window.location.reload();
                });
                  
                } else {
                  $('.restrictCheckbox[data-id="' + userId + '"]').prop('checked', false);
                swal({
                    title: "Success",
                    text: "Restrict time Disabled.",
                    icon: "success",
                    buttons: {
                        confirm: {
                            text: "OK",
                            value: true,
                            visible: true,
                            className: "btn btn-primary",
                            closeModal: true
                        }
                    }
                }).then((value) => {
                    // Reload the page after success
                    window.location.reload();
                });
                  
                }

                
            } else {
              
                alert('Error updating restrict time.');
            }
        },
        error: function(xhr, status, error){
            console.log('AJAX Error:', error);
        }
    });
});

});
</script>


</body>
</html>
