<!DOCTYPE html>
<html lang="en">

<body class="hold-transition sidebar-mini layout-fixed">


<div class="content-wrapper" style="min-height: 1302.4px;">


    <section class="content">
      <div class="container-fluid">
        <div >
        <a href="<?php echo base_url('index.php/Userscontroller'); ?>" class="btn-sm btn btn-danger"><i class="fas fa-backward"></i> Back</a></td>
        </div>  
        <div class="row justify-content-center">
       
        <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Update User Details</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <?php foreach($query as $row): ?>
              <form action="<?= base_url('index.php/Userscontroller/updateuser/'. $row->id) ?>" method="POST">
                <div class="card-body">
                
                  <label>User Name</label>
                  <div class="input-group mb-3" id="input_size" >
                  <div class="input-group-prepend">
                    <span class="input-group-text">@</span>
                  </div>
                  <input type="text"  id="input_size" name="name" class="form-control" placeholder="Enter Username" value="<?= $row->name; ?>">
                </div>
                
                  <label>Email</label>
                  <div class="input-group mb-3" id="input_size" >
                    
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                  </div>
                  <input type="email" id="input_size" name="email" class="form-control" placeholder="Enter Email" value="<?= $row->email; ?>" required>
                </div>

                  <div class="form-group">
                  <label>Address</label>
                      <input type="text"  id="input_size" name="address" class="form-control" placeholder="Enter Address" value="<?= $row->address; ?>">
                  </div>
                  
                  <div class="form-group">
    <label>Role</label>
    <div class="input-group mb-3" id="input_size">
        <?php
        $roles = array("Admin", "User","Owner");
        $selectedRoles = explode(',', $row->role);

        foreach ($roles as $role) {
          
            $checked = in_array($role, $selectedRoles) ? 'checked' : '';

            if($role == 'Owner'){
              $rolee = 'Manager';
            }
            else{
              $rolee = $role;
            }

            echo '
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="role[]" value="' . $role . '" id="' . $role . 'Checkbox" ' . $checked . '>
                    <label class="form-check-label" for="' . $role . 'Checkbox">' . $rolee . '</label>
                </div>';
        }
        ?>
    </div>
</div>

                  <div class="form-group">
                  <label>Contact</label>

                  <div class="input-group" id="input_size" >
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    </div>
                    <input type="text" name="contact" class="form-control" placeholder="Enter Contact" value="<?= $row->contact; ?>" style="width: 50%;">
                  </div>
                  <!-- /.input group -->
                </div>

        

                  <div class="form-group">
                  <label>Password</label>
                      <input type="password" id="password" name="password" class="form-control input_size" placeholder="Enter Password" value="<?= $row->password; ?>">
                      <input type="checkbox" onclick="viewpass()">Show Password
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>Submit</button>
                <button type="reset" class="btn btn-danger"><i class="fas fa-trash"></i>Reset</button>
               
                </div>
              </form>
              <?php endforeach; ?>
            </div>
            <!-- /.card -->
</div>
</div>
<section>
</div>
</div>

</body>
</html>