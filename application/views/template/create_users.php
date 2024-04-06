<!DOCTYPE html>
<html lang="en">

<body class="hold-transition sidebar-mini layout-fixed">

<div class="content-wrapper" style="min-height: 1302.4px;">



<section class="content">
      <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo base_url('index.php/Userscontroller'); ?>" class="btn-sm btn btn-danger"><i class="fas fa-backward"></i> Back</a></td>
        </div>  
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

                <label>Company Name</label>
                  <div class="input-group mb-3" id="input_size"> 
                  <div class="input-group-prepend">
                    <span class="input-group-text">@</span>
                  </div>
                  <input type="text" name="company_name" class="form-control" placeholder="Company Name">
                </div>
                
                  <label>Company Email</label>
                  <div class="input-group mb-3" id="input_size">
                    
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                  </div>
                  <input type="email" name="email" class="form-control" placeholder="Enter Email" required>
                </div>

                  <div class="form-group">
                  <label>Office Address Line 1</label>
                      <input type="text"  id="input_size" name="address" class="form-control" placeholder="Office Address Line 1">
                  </div>

                  <div class="form-group">
                  <label>Office Address Line 2</label>
                      <input type="text"  id="input_size" name="address_line2" class="form-control" placeholder="Office Address Line 2">
                  </div>
                  
                  <div class="form-group">
                  <label>Office City</label>
                      <input type="text"  id="input_size" name="address_city" class="form-control" placeholder="Office City">
                  </div>

                  
                  <div class="form-group">
                  <label>Office Address Postcode</label>
                      <input type="text"  id="input_size" name="address_postcode" class="form-control" placeholder="Office Postcode">
                  </div>

                  <div class="form-group">
                  <label>Delivery Address Line 1</label>
                      <input type="text"  id="input_size" name="delivery_address" class="form-control" placeholder="Delivery Address Line 1">
                  </div>

                  <div class="form-group">
                  <label>Delivery Address Line 2</label>
                      <input type="text"  id="input_size" name="delivery_address_line2" class="form-control" placeholder="Delivery Address Line 2">
                  </div>


                  <div class="form-group">
                  <label>Delivery City</label>
                      <input type="text"  id="input_size" name="delivery_city" class="form-control" placeholder="Delivery City">
                  </div>


                  <div class="form-group">
                  <label>Delivery Postcode</label>
                      <input type="text"  id="input_size" name="delivery_postcode" class="form-control" placeholder="Delivery Postcode">
                  </div>
                  
                  <div class="form-group">
                      <label for="status">Status</label>
                      <select id="status" name="status" class="form-control">
                          <option value="1">Active</option>
                          <option value="0">Inactive</option>
                      </select>
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


                      <div class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" name="role[]" value="Owner" id="managercheckbox">
                          <label class="form-check-label" for="managercheckbox">Manager</label>
                      </div>
                      <!-- Add more checkboxes as needed -->
                  </div> 

                  <div class="form-group">
                  <label>Password</label>
                      <input type="password"  id="password" name="password" class="form-control input_size" placeholder="Enter Password">
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