<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details Form</title>
</head>
<body class="hold-transition sidebar-mini layout-fixed">

<div class="content-wrapper" style="min-height: 1302.4px;">

    <section class="content">
        <div class="container-fluid">
            <div class="pull-right">
                <a href="<?php echo base_url('index.php/Userscontroller'); ?>" class="btn-sm btn btn-danger"><i class="fas fa-backward"></i> Back</a>
            </div>  
<div class="row justify-content-center">
    <!-- left column -->
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Enter User Details</h3>
            </div>

<form action="<?= base_url('index.php/Userscontroller/adduser') ?>" method="POST">
    <div class="card-body">
        <div class="row">
            <!-- First Column -->

<div class="col-md-6">
    <label>User Name</label>
    <div class="input-group mb-3" id="input_size"> 
        <div class="input-group-prepend">
            <span class="input-group-text">@</span>
        </div>
        <input type="text" name="name" class="form-control" placeholder="Enter Username">
    </div>

    <input type="hidden" id="created_by" name="created_by" value="<?php echo $loginuser['name']; ?>">


    <label>Record ID (If required)</label>
    <div class="input-group mb-3" id="input_size"> 
        <div class="input-group-prepend">
            <span class="input-group-text">#</span>
        </div>
        <input type="number" name="record_id" class="form-control" placeholder="Enter Record ID">
    </div>

    <label>Company Name</label>
    <div class="input-group mb-3" id="input_size"> 
        <div class="input-group-prepend">
            <span class="input-group-text">@</span>
        </div>
        <input type="text" name="company_name" class="form-control" placeholder="Company Name">
    </div>

                                     
    <label>Brand Name</label>
    <div class="input-group mb-3" id="input_size"> 
        <div class="input-group-prepend">
            <span class="input-group-text">@</span>
        </div>
        <input type="text" name="brand_name" class="form-control" placeholder="Brand Name">
    </div>
                
    <label>Company Email 1</label>
    <div class="input-group mb-3" id="input_size">    
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
        </div>
        <input type="email" name="email" class="form-control" placeholder="Enter Email 1" required>
    </div>

    <label>Company Email 2</label>
    <div class="input-group mb-3" id="input_size">    
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
        </div>
        <input type="email" name="primaryemail" class="form-control" placeholder="Enter Email 2" required>
    </div>

    <label>Company Email 3</label>
    <div class="input-group mb-3" id="input_size">    
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
        </div>
        <input type="email" name="secondaryemail" class="form-control" placeholder="Enter Email 3" required>
    </div>

    <label>Sales Person</label>
    <div class="input-group mb-3" id="input_size"> 
        <div class="input-group-prepend">
            <span class="input-group-text">@</span>
        </div>
        <input type="text" name="sales_person" class="form-control" placeholder="Enter Sales Person">
    </div>

    <div class="form-group">
        <label>Office Address Line 1</label>
        <input type="text" id="input_size" name="address" class="form-control" placeholder="Office Address Line 1">
    </div>

    <div class="form-group">
        <label>Office Address Line 2</label>
        <input type="text" id="input_size" name="address_line2" class="form-control" placeholder="Office Address Line 2">
    </div>

    <div class="form-group">
        <label>Office Address Line 3</label>
        <input type="text" id="input_size" name="address_line3" class="form-control" placeholder="Office Address Line 3">
    </div>

    <div class="form-group">
        <label>Office Address Line 4</label>
        <input type="text" id="input_size" name="address_line4" class="form-control" placeholder="Office Address Line 4">
    </div>
                  
    <div class="form-group">
        <label>Office City</label>
        <input type="text" id="input_size" name="address_city" class="form-control" placeholder="Office City">
    </div>

    <div class="form-group">
        <label>Office Address Postcode</label>
        <input type="text" id="input_size" name="address_postcode" class="form-control" placeholder="Office Postcode">
    </div>
    <div class="form-group" hidden>
    <label for="memo" class="control-label">Driver Memo</label>
  <textarea class="form-control" id="driver_memo" name="driver_memo" autocomplete="off"></textarea>
  </div>

  <div class="form-group" hidden>
    <label for="memo" class="control-label">Packer Memo</label>
  <textarea class="form-control" id="packer_memo" name="packer_memo" autocomplete="off"></textarea>
  </div>

  <div class="form-group">
    <label for="payment_terms" class="control-label">Payment Terms</label>
  <textarea class="form-control" id="payment_terms" name="payment_terms" autocomplete="off"></textarea>
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
<div class="col-md-6">
<div class="form-group">
<label>Shipping Address Line 1</label>
    <input type="text"  id="input_size" name="delivery_address" class="form-control" placeholder="Shipping Address Line 1">
</div>

<div class="form-group">
<label>Shipping Address Line 2</label>
    <input type="text"  id="input_size" name="delivery_address_line2" class="form-control" placeholder="Shipping Address Line 2">
</div>


<div class="form-group">
<label>Shipping Address Line 3</label>
    <input type="text"  id="input_size" name="delivery_address_line3" class="form-control" placeholder="Shipping Address Line 2">
</div>

<div class="form-group">
<label>Shipping Address Line 4</label>
    <input type="text"  id="input_size" name="delivery_address_line4" class="form-control" placeholder="Shipping Address Line 2">
</div>


<div class="form-group">
<label>Shipping City</label>
    <input type="text"  id="input_size" name="delivery_city" class="form-control" placeholder="Shipping City">
</div>


<div class="form-group">
<label>Shipping Postcode</label>
    <input type="text"  id="input_size" name="delivery_postcode" class="form-control" placeholder="Shipping Postcode">
</div>

<div class="form-group">
<label>Shipping Address 2 Line 1</label>
    <input type="text"  id="input_size" name="address2" class="form-control" placeholder="Shipping Address 2 Line 1">
</div>

<div class="form-group">
<label>Shipping Address 2 Line 2</label>
    <input type="text"  id="input_size" name="address2_line2" class="form-control" placeholder="Shipping Address 2 Line 2">
</div>


<div class="form-group">
<label>Shipping Address 2 Line 3</label>
    <input type="text"  id="input_size" name="address2_line3" class="form-control" placeholder="Shipping Address 2 Line 2">
</div>

<div class="form-group">
<label>Shipping Address 2 Line 4</label>
    <input type="text"  id="input_size" name="address2_line4" class="form-control" placeholder="Shipping Address 2 Line 2">
</div>


<div class="form-group">
<label>Shipping 2 City</label>
    <input type="text"  id="input_size" name="address2_city" class="form-control" placeholder="Shipping 2 City">
</div>


<div class="form-group">
<label>Shipping 2 Postcode</label>
    <input type="text"  id="input_size" name="address2_postcode" class="form-control" placeholder="Shipping 2 Postcode">
</div>


<div class="form-group">
<label>Shipping Address 3 Line 1</label>
    <input type="text"  id="input_size" name="address3" class="form-control" placeholder="Shipping Address 3 Line 1">
</div>

<div class="form-group">
<label>Shipping Address 3 Line 2</label>
    <input type="text"  id="input_size" name="address3_line2" class="form-control" placeholder="Shipping Address 3 Line 2">
</div>


<div class="form-group">
<label>Shipping Address 3 Line 3</label>
    <input type="text"  id="input_size" name="address3_line3" class="form-control" placeholder="Shipping Address 3 Line 2">
</div>

<div class="form-group">
<label>Shipping Address 3 Line 4</label>
    <input type="text"  id="input_size" name="address3_line4" class="form-control" placeholder="Shipping Address 3 Line 2">
</div>


<div class="form-group">
<label>Shipping 3 City</label>
    <input type="text"  id="input_size" name="address3_city" class="form-control" placeholder="Shipping 3 City">
</div>


<div class="form-group">
<label>Shipping 3 Postcode</label>
    <input type="text"  id="input_size" name="address3_postcode" class="form-control" placeholder="Shipping 3 Postcode">
</div>

<div class="form-group">
<label>Shipping Address 4 Line 1</label>
    <input type="text"  id="input_size" name="address4" class="form-control" placeholder="Shipping Address 4 Line 1">
</div>

<div class="form-group">
<label>Shipping Address 4 Line 2</label>
    <input type="text"  id="input_size" name="address4_line2" class="form-control" placeholder="Shipping Address 4 Line 2">
</div>


<div class="form-group">
<label>Shipping Address 4 Line 3</label>
    <input type="text"  id="input_size" name="address4_line3" class="form-control" placeholder="Shipping Address 4 Line 2">
</div>

<div class="form-group">
<label>Shipping Address 4 Line 4</label>
    <input type="text"  id="input_size" name="address4_line4" class="form-control" placeholder="Shipping Address 4 Line 2">
</div>


<div class="form-group">
<label>Shipping 4 City</label>
    <input type="text"  id="input_size" name="address4_city" class="form-control" placeholder="Shipping 4 City">
</div>


<div class="form-group">
<label>Shipping 4 Postcode</label>
    <input type="text"  id="input_size" name="address4_postcode" class="form-control" placeholder="Shipping 4 Postcode">
</div>
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