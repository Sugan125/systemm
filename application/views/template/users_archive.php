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
        
     


<?php if( (isset($user->image)) || $loginuser['roles'] == 'Admin' || $loginuser['roles'] == 'Owner' || ((in_array('Admin', $loginuser['role']) && in_array('User', $loginuser['role'])))){ ?>
<!-- /.card-header -->
<div class="card-body">
<table class="table table-bordered table-striped table-responsive equal-width-table">
                           
  <thead>
    <tr class="text-center">
      <th>Name</th>
      <th>Record ID</th>
      <th>Company Name</th>
      <th>Brand Name</th>
      <th>Sales Person Name</th>
      <th>Company Emails</th>
      <th>Office Address</th>
      <!-- <th>Shipping Address</th>
      <th>Shipping Address2</th>
      <th>Shipping Address3</th>
      <th>Shipping Address4</th> -->
      <th>Mobile Number</th>
      <th>Role</th>
      <th>Payment Terms</th>
      <!-- <th>Driver Memo</th>
      <th>Packer Memo</th> -->
    
      <th>Revert Archive & Active</th>
     
      <!-- <th>File Upload</th>
      <th>View Files</th> -->
      <!-- <th>Action</th> -->
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
        <td><?= $row->record_id; ?></td>
        <td><?= $row->company_name; ?></td>
        <td><?= $row->brand_name; ?></td>
        <td><?= $row->sales_person; ?></td>
        <td><?= $row->email; ?><br><?= $row->primary_email; ?><br><?= $row->secondary_email; ?></td>
        <td><?= ($row->address ? $row->address . ', ' : '') . ($row->address_line2 ? $row->address_line2 . ', ' : '') . ($row->address_line3 ? $row->address_line3 . ', ' : '') . ($row->address_line4 ? $row->address_line4 . ', ' : '') . ($row->address_city ? $row->address_city . ', ' : '') . ($row->address_postcode ? $row->address_postcode : ''); ?></td>
        <!-- <td><= ($row->delivery_address ? $row->delivery_address . ', ' : '') . ($row->delivery_address_line2 ? $row->delivery_address_line2 . ', ' : '') . ($row->delivery_address_line3 ? $row->delivery_address_line3 . ', ' : '') . ($row->delivery_address_line4 ? $row->delivery_address_line4 . ', ' : '') . ($row->delivery_address_city ? $row->delivery_address_city . ', ' : '') . ($row->delivery_address_postcode ? $row->delivery_address_postcode : ''); ?></td>
        <td><= ($row->address2 ? $row->address2 . ', ' : '') . ($row->address2_line2 ? $row->address2_line2 . ', ' : '') . ($row->address2_line3 ? $row->address2_line3 . ', ' : '') . ($row->address2_line4 ? $row->address2_line4 . ', ' : '') . ($row->address2_city ? $row->address2_city . ', ' : '') . ($row->address2_postcode ? $row->address2_postcode : ''); ?></td>
        <td><= ($row->address3 ? $row->address3 . ', ' : '') . ($row->address3_line2 ? $row->address3_line2 . ', ' : '') . ($row->address3_line3 ? $row->address3_line3 . ', ' : '') . ($row->address3_line4 ? $row->address3_line4 . ', ' : '') . ($row->address3_city ? $row->address3_city . ', ' : '') . ($row->address3_postcode ? $row->address3_postcode : ''); ?></td>
        <td><= ($row->address4 ? $row->address4 . ', ' : '') . ($row->address4_line2 ? $row->address4_line2 . ', ' : '') . ($row->address4_line3 ? $row->address4_line3 . ', ' : '') . ($row->address4_line4 ? $row->address4_line4 . ', ' : '') . ($row->address4_city ? $row->address4_city . ', ' : '') . ($row->address4_postcode ? $row->address4_postcode : ''); ?></td> -->
        <td><?= $row->contact; ?></td>
        <td><?=  $rolee; ?></td>
        <td><?=  $row->payment_terms; ?></td>
        <!-- <td><=  $row->driver_memo; ?></td>
        <td><=  $row->packer_memo; ?></td> -->
        <!-- Change id="restrictCheckbox" to class="restrictCheckbox" -->
       <td>
  
    <button class="btn btn-sm btn-warning revert-archive changestatus" data-id="<?php echo $row->id; ?>" title="Revert Archive">
      <i class="fas fa-undo"></i>
    </button>
  
</td>

        <!-- <td>
        <a href="//base_url('index.php/Userscontroller/fileupload/' . $row->id) ?>" class="btn btn-sm"><i class="fas fa-upload"></i> </a>
        </td>
        <td>
        <a href=" //base_url('index.php/Userscontroller/viewfiles/' . $row->id) ?>" class="btn btn-sm"><i class="fa fa-folder-open">View Files</i> </a>
        
        </td> -->
        <!-- <td>
          <a href="<= base_url('index.php/Userscontroller/update/' . $row->id) ?>" class="btn  btn-sm"><i class="fas fa-edit"></i> </a>
          
          <a href="<= base_url('index.php/Userscontroller/deleteuser/' . $row->id) ?>" class="btn btn-sm" onclick='return confirm("Are you sure to delete this user?");'><i class="fas fa-trash"></i></a>
        </td> -->
      </tr>
     
    <?php } ?>
  </tbody>
</table>

            <br>
            <!-- Pagination Links -->
            <div class="row">
            <div class="col-sm-6">
                <?php $total_rowss = $total_rows; 
                echo "Showing 1 to 10 of ".$total_rowss." entries"; ?>
            </div>
                <div class="col-sm-6">
                    <?php echo $this->pagination->create_links(); ?>
                </div>
            </div>

            
        </div>
        <!-- /.card-body -->
        <?php } ?>
    </div>

</div>

<script>
$(document).ready(function(){
 $('.changestatus').click(function(e){
    e.preventDefault(); // prevent default checkbox toggle until confirmed

    var checkbox = $(this);
    var userId = checkbox.data('id');
    var isChecked = checkbox.prop('checked') ? 1 : 0;

    swal({
        title: "Are you sure?",
        text: isChecked 
              ? "Do you want to revert the archive and activate this user?" 
              : "Do you want to archive this user?",
        icon: "warning",
        buttons: {
           confirm: {
                text: "Yes",
                visible: true,
                className: "btn btn-primary"
            },
            cancel: {
                text: "Cancel",
                visible: true,
                className: "btn btn-secondary"
            }
        },
        dangerMode: true,
    }).then((confirmed) => {
        if (confirmed) {
            // Proceed with AJAX only if confirmed
            $.ajax({
                url: '<?php echo base_url('index.php/Userscontroller/update_status_active'); ?>',
                method: 'POST',
                data: { userId: userId, isChecked: isChecked },
                dataType: 'json',
                success: function(response){
                    if(response.status === 'success'){
                        if(response.isChecked === '1'){
                            $('.restrictCheckbox[data-id="' + userId + '"]').prop('checked', true);
                            swal({
                                title: "Success",
                                text: "Reverted the Archive. Check the User in Manage Users!",
                                icon: "success",
                                buttons: { confirm: { className: "btn btn-primary" } }
                            }).then(() => { window.location.reload(); });
                        } else {
                            $('.restrictCheckbox[data-id="' + userId + '"]').prop('checked', false);
                            swal({
                                title: "Success",
                                text:  "Reverted the Archive. Check the User in Manage Users!",
                                icon: "success",
                                buttons: { confirm: { className: "btn btn-primary" } }
                            }).then(() => { window.location.reload(); });
                        }
                    } else {
                        alert('Error updating restrict time.');
                    }
                },
                error: function(xhr, status, error){
                    console.log('AJAX Error:', error);
                }
            });
        } else {
            // If user cancels, revert checkbox state to previous state
            checkbox.prop('checked', !isChecked);
        }
    });
});


});
</script>


</body>
</html>
