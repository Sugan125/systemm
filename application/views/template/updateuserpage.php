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

                <label>Company Name</label>
                  <div class="input-group mb-3" id="input_size"> 
                  <div class="input-group-prepend">
                    <span class="input-group-text">@</span>
                  </div>
                  <input type="text" name="company_name" class="form-control" value="<?= $row->company_name; ?>" placeholder="Company Name">
                </div>

                <label>Brand Name</label>
                  <div class="input-group mb-3" id="input_size"> 
                  <div class="input-group-prepend">
                    <span class="input-group-text">@</span>
                  </div>
                  <input type="text" name="brand_name" class="form-control" value="<?= $row->brand_name; ?>" placeholder="Brand Name">
                </div>
                
                  <label>Company Email</label>
                  <div class="input-group mb-3" id="input_size" >
                    
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                  </div>
                  <input type="email" id="input_size" name="email" class="form-control" placeholder="Enter Email" value="<?= $row->email; ?>" required>
                </div>

                <div class="form-group">
                  <label>Office Address Line 1</label>
                      <input type="text"  id="input_size" name="address" class="form-control"  value="<?= $row->address; ?>" placeholder="Office Address Line 1">
                  </div>

                  <div class="form-group">
                  <label>Office Address Line 2</label>
                      <input type="text"  id="input_size" name="address_line2"  value="<?= $row->address_line2; ?>" class="form-control" placeholder="Office Address Line 2">
                  </div>
                  
                  <div class="form-group">
                  <label>Office City</label>
                      <input type="text"  id="input_size" name="address_city"  value="<?= $row->address_city; ?>" class="form-control" placeholder="Office City">
                  </div>

                  
                  <div class="form-group">
                  <label>Office Address Postcode</label>
                      <input type="text"  id="input_size" name="address_postcode"  value="<?= $row->address_postcode; ?>" class="form-control" placeholder="Office Postcode">
                  </div>

                  <div class="form-group">
                  <label>Delivery Address Line 1</label>
                      <input type="text"  id="input_size" name="delivery_address"   value="<?= $row->delivery_address; ?>" class="form-control" placeholder="Delivery Address Line 1">
                  </div>

                  <div class="form-group">
                  <label>Delivery Address Line 2</label>
                      <input type="text"  id="input_size" name="delivery_address_line2"  value="<?= $row->delivery_address_line2; ?>" class="form-control" placeholder="Delivery Address Line 2">
                  </div>


                  <div class="form-group">
                  <label>Delivery City</label>
                      <input type="text"  id="input_size" name="delivery_city"  value="<?= $row->delivery_address_city; ?>" class="form-control" placeholder="Delivery City">
                  </div>


                  <div class="form-group">
                  <label>Delivery Postcode</label>
                      <input type="text"  id="input_size" name="delivery_postcode"  value="<?= $row->delivery_address_postcode; ?>" class="form-control" placeholder="Delivery Postcode">
                  </div>

                  <div class="form-group">
                      <label for="status">Status</label>
                      <select id="status" name="status" class="form-control">
                          <option value="">Select Status</option>
                          <option value="1" <?php echo ($row->status == 1 && $row->status !== null) ? 'selected' : ''; ?>>Active</option>
                          <option value="0" <?php echo ($row->status == 0 && $row->status !== null) ? 'selected' : ''; ?>>Inactive</option>
                      </select>
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