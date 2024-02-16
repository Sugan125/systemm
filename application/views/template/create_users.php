<!DOCTYPE html>
<html lang="en">

<body class="hold-transition sidebar-mini layout-fixed">

<div class="content-wrapper" style="min-height: 1302.4px;">

<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2 justify-content-center">
          <div class="col-sm-6">
            <h1 class="m-0 text-center">Create User</h1>
          </div><!-- /.col -->
          <!-- <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Change Password</li>
            </ol>
          </div> -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>


<section class="content">
      <div class="container-fluid">
        <div class="row justify-content-center">
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Enter User Details</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="<?= base_url('index.php/Userscontroller/adduser') ?>" method="POST">
                <div class="card-body">
                
                  <label>User Name</label>
                  <div class="input-group mb-3" id="input_size"> 
                  <div class="input-group-prepend">
                    <span class="input-group-text">@</span>
                  </div>
                  <input type="text" name="name" class="form-control" placeholder="Enter Username">
                </div>
                
                  <label>Email</label>
                  <div class="input-group mb-3" id="input_size">
                    
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                  </div>
                  <input type="email" name="email" class="form-control" placeholder="Enter Email">
                </div>

                  <div class="form-group">
                  <label>Address</label>
                      <input type="text"  id="input_size" name="address" class="form-control" placeholder="Enter Address">
                  </div>
                  

                  <div class="form-group">
                  <label>Contact</label>

                  <div class="input-group" id="input_size">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    </div>
                    <input type="text" name="contact" class="form-control" placeholder="Enter Contact">
                  </div>
                  <!-- /.input group -->
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
                      <!-- Add more checkboxes as needed -->
                  </div> 

                  <div class="form-group">
                  <label>Password</label>
                      <input type="password" id="input_size" id="password" name="password" class="form-control" placeholder="Enter Password">
                      <input type="checkbox" onclick="viewpass()">Show Password
                  </div>
                </div>
                <!-- /.card-body -->

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