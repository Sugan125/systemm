<!DOCTYPE html>
<html lang="en">

<body class="hold-transition sidebar-mini layout-fixed">

<div class="content-wrapper" style="min-height: 1302.4px;">

<section class="content">
      <div class="container-fluid">
      <div >
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
    <select name="name" class="form-control">
        <option value="">Select User Name</option>
        <?php foreach ($userss as $row) :
          if($row->role != 'Owner') {?>
            <option value="<?= $row->name; ?>"><?= $row->name; ?></option>
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
        <input class="form-check-input" type="checkbox" name="access[]" value="Add" id="adminCheckbox">
        <label class="form-check-label" for="adminCheckbox">Add</label>
    </div>

    <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" name="access[]" value="Edit" id="userCheckbox">
        <label class="form-check-label" for="userCheckbox">Edit</label>
    </div>

    <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" name="access[]" value="Delete" id="userCheckbox">
        <label class="form-check-label" for="userCheckbox">Delete</label>
    </div>


    <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" name="access[]" value="File Upload" id="userCheckbox">
        <label class="form-check-label" for="userCheckbox">File Upload</label>
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

</body>
</html>