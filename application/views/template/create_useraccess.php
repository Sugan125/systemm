<!DOCTYPE html>
<html lang="en">

<body class="hold-transition sidebar-mini layout-fixed">

<div class="content-wrapper" style="min-height: 1302.4px;">

<section class="content">
      <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo base_url('index.php/Userrolecontroller'); ?>" class="btn-sm btn btn-danger"><i class="fas fa-backward"></i> Back</a></td>
        </div> 
        <div class="row justify-content-center">
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Add User Access</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="<?= base_url('index.php/Userrolecontroller/adduseraccess') ?>" method="POST">
                <div class="card-body" style="height:450px;">
                
                <label>User Name</label>
<div class="input-group mb-3" id="input_size">
    <div class="input-group-prepend">
        <span class="input-group-text">@</span>
    </div>
    <select name="name" id="userSelect" class="form-control">
        <option value="">Select User Name</option>
        <?php foreach ($userss as $row) :
            if($row->role != 'Owner') {?>
                <option value="<?= $row->name; ?>" data-role="<?= $row->role; ?>"><?= $row->name; ?></option>
            <?php } ?>
        <?php endforeach; ?>
    </select>
</div>

<label>Role</label>
                  <div class="input-group mb-3" id="input_size">
                      <div class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" name="role[]" value="Admin" id="adminCheckbox">
                          <label class="form-check-label" for="adminCheckbox">Admin</label>
                      </div>

                      <div class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" name="role[]" value="User" id="userCheckbox">
                          <label class="form-check-label" for="userCheckbox">User</label>
                      </div>

                      <div class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" name="role[]" value="Owner" id="managercheckbox">
                          <label class="form-check-label" for="managercheckbox">Manager</label>
                      </div>
                      <!-- Add more checkboxes as needed -->
                  </div> 

<label>Access</label>
<div class="input-group mb-3" id="input_size">
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" name="access[]" value="Add" id="addcheck">
        <label class="form-check-label" for="adminCheckbox">Add</label>
    </div>

    <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" name="access[]" value="Edit" id="editcheck">
        <label class="form-check-label" for="userCheckbox">Edit</label>
    </div>

    <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" name="access[]" value="Delete" id="deletecheck">
        <label class="form-check-label" for="userCheckbox">Delete</label>
    </div>


    <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" name="access[]" value="File Upload" id="filecheck">
        <label class="form-check-label" for="userCheckbox">File Upload</label>
    </div>

    <div class="form-check form-check-inline"  id="create_order" style="display: none;">
        <input class="form-check-input" type="checkbox" name="access[]" value="Create Order" id="ordercheck">
        <label class="form-check-label" for="userCheckbox">Create Order</label>
    </div>
    <!-- Add more checkboxes as needed -->
</div>  
                <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>Submit</button>
                <button type="reset" class="btn btn-danger"><i class="fas fa-trash"></i>Reset</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
</div>
</div>
<section>

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
      $(document).ready(function() {
        $('#userSelect').change(function() {
            var selectedRole = $(this).find(':selected').data('role'); // Get the data-id value of the selected option
            var selectedAccess = $(this).find(':selected').data('access');

            if (selectedRole === 'Admin') {
                $('#create_order').show(); // Show the "Create Order" checkbox
            } else {
                $('#create_order').hide(); // Hide the "Create Order" checkbox for other roles
                $('#ordercheck').prop('checked', false); // Uncheck the checkbox if it was checked before
            }


            $('input[type="checkbox"]').prop('checked', false); // Uncheck all checkboxes first
            if (selectedRole === 'Admin') {
                $('#adminCheckbox').prop('checked', true);
            } else if (selectedRole === 'User') {
                $('#userCheckbox').prop('checked', true);
            } else if (selectedRole === 'Owner') {
                $('#managercheckbox').prop('checked', true);
            }
        });
    });
</script>
</body>
</html>