<!DOCTYPE html>
<html lang="en">

<body class="hold-transition sidebar-mini layout-fixed">

<div class="content-wrapper" style="min-height: 1302.4px;">



<section class="content">
      <div class="container-fluid">
        <div class="row justify-content-center">
          <div class="col-md-6">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Enter Product Details</h3>
              </div>
              <form action="<?= base_url('index.php/Productcontroller/addproduct') ?>" method="POST">
                <div class="card-body">
                
                  <label>Product ID</label>
                  <div class="input-group mb-3" id="input_size"> 
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-shopping-cart"></i></span>
                  </div>
                  <input type="text" name="product_id" class="form-control" placeholder="Enter Product ID">
                </div>
                
                  <label>Product Name</label>
                  <div class="input-group mb-3" id="input_size">
                    
                  <div class="input-group-prepend">
                    <span class="input-group-text">@</span>
                  </div>
                  <input type="text" name="product_name" class="form-control" placeholder="Enter Product Name">
                </div>

                  <div class="form-group">
                  <label>Description</label>
                      <textarea type="textarea"  id="input_size" name="product_desc" class="form-control" placeholder="Product Description"></textarea>
                  </div>
                  

                  <div class="form-group">
                  <label>Category</label>

                  <div class="input-group" id="input_size">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-solid fa-list"></i></span>
                    </div>
                    <input type="text" name="prod_category" class="form-control" placeholder="Product Category">
                  </div>
                </div>
                <label>Add-on</label>
                  <div class="input-group mb-3" id="input_size">
                      <div class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" value="Sliced" id="slicedCheckbox">
                          <label class="form-check-label" for="slicedCheckbox">Pre Sliced</label>
                      </div>

                      <div class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" value="Unsliced" id="unslicedCheckbox">
                        <label class="form-check-label" for="unslicedCheckbox">Unsliced</label>
                    </div>

                    <div id="presliceOptionsyes" style="display: none; margin-top: 10px;margin-left: 50px;">
                        <label for="presliceThickness">Preslice Thickness</label>
                        <select class="form-select" id="presliceThickness" name="add_on_slice">
                            <option value="12mm">12mm</option>
                        </select>
                    </div>

                    <div id="presliceOptionsno" style="display: none; margin-top: 10px;margin-left: 50px;">
                        <label for="presliceThickness">Preslice Thickness</label>
                        <select class="form-select" id="presliceThickness" name="add_on_slice">
                            <option value="20mm">20mm</option>
                        </select>
                    </div>

                    <br><br>
                <div class="input-group mb-3" id="input_size">
                <div id="seedOptions">
                          <label>Seed</label><br>
                          <div class="form-check form-check-inline">
                              <input class="form-check-input" type="checkbox" value="Yes" id="seedYes">
                              <label class="form-check-label" for="seedYes">Yes</label>
                          </div>
                          <div class="form-check form-check-inline">
                              <input class="form-check-input" type="checkbox" value="No" id="seedNo">
                              <label class="form-check-label" for="seedNo">No</label>
                          </div>
                      
                          
                        
                      </div>

                      <div id="seedsoption" style="display:none;margin-left: 131px; margin-top: 25px;">
                      <label for="seedColor">Seed Color</label>
                          <select class="form-select" id="seedColor" name="add_on_seed">
                              <option value="Black">Black</option>
                              <option value="White">White</option>
                          </select> 
                    </div>
                </div>
                </div>


                  <div class="form-group">
                  <label>Product Image</label>
                  <input type="file" class="form-control"  name="prod_img" id="docpicker" accept=".jpg,.jpeg,.png" />
                  </div>

                  <div class="form-group">
                  <label>Price</label>

                  <div class="input-group" id="input_size">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-dollar"></i></span>
                    </div>
                    <input type="text" name="prod_rate" class="form-control" placeholder="Product Price">
                  </div>
                </div>

               

                <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>Submit</button>
                <button type="reset" class="btn btn-danger"><i class="fas fa-trash"></i>Reset</button>
                </div>
              </form>
            </div>
</div>
</div>
<section>

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('input[type="checkbox"]').click(function() {
            if ($(this).val() == "Sliced") {
                if ($(this).is(":checked")) {
                    $("#presliceOptionsyes").show();
                } else {
                    $("#presliceOptionsyes").hide();
                }
            }
            if ($(this).val() == "Unsliced") {
                if ($(this).is(":checked")) {
                    $("#presliceOptionsyes").hide();
                    $("#presliceOptionsno").show();
                }
                else{
                  $("#presliceOptionsno").hide();
                }
            }
            if ($(this).val() == "Yes") {
                if ($(this).is(":checked")) {
                    $("#seedsoption").show();
                } else {
                    $("#seedsoption").hide();
                }
            }

            if ($(this).val() == "No") {
                if ($(this).is(":checked")) {
                    $("#seedsoption").hide();
                } else {
                    $("#seedsoption").hide();
                }
            }
        });
    });
</script>


</body>
</html>