<!DOCTYPE html>
<html lang="en">

<body class="hold-transition sidebar-mini layout-fixed">

<div class="content-wrapper" style="min-height: 1302.4px;">



<section class="content">
      <div class="container-fluid">
      <div >
        <a href="<?php echo base_url('index.php/Productcontroller'); ?>" class="btn-sm btn btn-danger"><i class="fas fa-backward"></i> Back</a></td>
        </div> 
        <div class="row justify-content-center">
          <div class="col-md-6">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Update Product Details</h3>
              </div>
              <?php foreach($products as $row): ?>
              <form action="<?= base_url('index.php/Productcontroller/updateprods/'. $row->id) ?>" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                
                  <label>Product ID</label>
                  <div class="input-group mb-3" id="input_size"> 
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-shopping-cart"></i></span>
                  </div>
                  <input type="text" name="product_id" class="form-control" placeholder="Enter Product ID" value="<?= $row->product_id; ?>">
                </div>
                
                  <label>Product Name</label>
                  <div class="input-group mb-3" id="input_size">
                    
                  <div class="input-group-prepend">
                    <span class="input-group-text">@</span>
                  </div>
                  <input type="text" name="product_name" class="form-control" placeholder="Enter Product Name" value="<?= $row->product_name; ?>">
                </div>

                <div class="form-group">
                    <label for="input_size">Description</label>
                    <textarea id="input_size" name="product_desc" class="form-control" placeholder="Product Description"><?= $row->product_desc; ?></textarea>
                </div>
             
                <label>Active</label>
                <div class="input-group mb-3" id="input_size">
                    <div class="input-group-prepend">
                        <span class="input-group-text">@</span>
                    </div>
                    <select name="active" class="form-control">
                    <option value="1" <?= ($row->active == 1) ? "selected" : "" ?>>Active</option>
                    <option value="0" <?= ($row->active == 0) ? "selected" : "" ?>>Inactive</option>
                    </select>
                </div>

                <label>Minimum Order Count</label>
                <div class="input-group mb-3" id="input_size">
                    <div class="input-group-prepend">
                        <span class="input-group-text">@</span>
                    </div>
                    <input type="number" name="min_order" class="form-control" value="<?= $row->min_order; ?>" placeholder="Enter Min Order">
                </div>

                  <div class="form-group">
                  <label>Category</label>

                  <div class="input-group" id="input_size">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-solid fa-list"></i></span>
                    </div>
                    <input type="text" name="prod_category" class="form-control" placeholder="Product Category"  value="<?= $row->prod_category; ?>">
                  </div>
                </div>

                    <!-- <div class="form-group">
                <div class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox"  value="no_add_on" id="no_add_on">
                          <label class="form-check-label" for="no_add_on">No add-on</label>
                      </div>
                  </div> -->

                
<br><br>
<div class="input-group mb-3" id="input_size">
  <div id="seedOptions">
    <label>Seed</label><br>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="add_on_seed" id="seedYes" value="1" <?= ($row->add_on_seed != 0) ? 'checked' : '' ?>>
      <label class="form-check-label" for="seedYes">Yes</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="add_on_seed" id="seedNo" value="0" <?= ($row->add_on_seed== 0) ? 'checked' : '' ?>>
      <label class="form-check-label" for="seedNo">No</label>
    </div>
  </div>
 
</div>

<br><br>
<div class="input-group mb-3" id="input_size">
  <div id="sliceOptions">
    <label>Slice</label><br>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="add_on_slice" id="sliceYes" value="1" <?= ($row->add_on_slice != 0) ? 'checked' : '' ?>>
      <label class="form-check-label" for="sliceYes">Yes</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="add_on_slice" id="sliceNo" value="0" <?= ($row->add_on_slice  == 0) ? 'checked' : '' ?>>
      <label class="form-check-label" for="sliceNo">No</label>
    </div>
  </div>
  </div>     
  
  

                    <div class="form-group">
                        <label for="prod_img">Product Image</label>
                        <?php if (!empty($row->prod_img))
                            {
                           ?>
                            <img id="HideImg" src="<?= base_url();?>uploads/<?= $row->prod_img; ?>" alt="Image Not Found" style="width:100px; height:100px;">
                        <?php } else { ?>
                            <img id="HideImg" src="<?= base_url();?>uploads/no_product.png" alt="Image Not Found" style="width:200px; height:100px;">
                        <?php  } ?>
                        <input type="file" class="form-control" name="prod_img" id="prod_img" accept=".jpg, .jpeg, .png"  value="<?= $row->prod_img; ?>" />
                    </div>


                  <div class="form-group">
                  <label>Price</label>

                  <div class="input-group" id="input_size">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-dollar"></i></span>
                    </div>
                    <input type="text" name="prod_rate" class="form-control" placeholder="Product Price" value="<?= $row->prod_rate; ?>">
                  </div>
                </div>
                    <div class="form-group">
                    <label>Promotion Rule (Buy N Get M Free)</label>
                    <div class="input-group" id="input_promotion_rule_n">
                        <div class="input-group-prepend">
                        <span class="input-group-text">Buy</span>
                        </div>
                        <input type="number" id="promotion_rule_n" name="promo_rule_buy" class="form-control" placeholder="Buy" value="<?= $row->promo_rule_buy; ?>">
                    </div>
                    <div class="input-group" id="input_promotion_rule_m" style="margin-top: 10px;">
                        <div class="input-group-prepend">
                        <span class="input-group-text">Free</span>
                        </div>
                        <input type="number" id="promotion_rule_m" name="promo_rule_free" class="form-control" placeholder="Free" value="<?= $row->promo_rule_free; ?>">
                    </div>
                    </div>
                </div>


                <input type="hidden" id="updated_by" name="updated_by" value="<?php echo $loginuser['name']; ?>">


                <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>Submit</button>
                <button type="reset" class="btn btn-danger"><i class="fas fa-trash"></i>Reset</button>
                </div>
              </form>
              <?php endforeach; ?>
            </div>
</div>
</div>
<section>

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Function to handle checkbox click event
        function handleCheckboxClick(checkbox) {
            if (checkbox.val() == "Sliced") {
                if (checkbox.is(":checked")) {
                    $("#presliceOptionsyes").show();
                } else {
                    $("#presliceOptionsyes").hide();
                }
            }
            if (checkbox.val() == "Unsliced") {
                if (checkbox.is(":checked")) {
                    $("#presliceOptionsyes").hide();
                    $("#presliceOptionsno").show();
                } else {
                    $("#presliceOptionsno").hide();
                }
            }
            if (checkbox.val() == "Yes") {
                if (checkbox.is(":checked")) {
                    $("#seedsoption").show();
                } else {
                    $("#seedsoption").hide();
                }
            }
            if (checkbox.val() == "No") {
                if (checkbox.is(":checked")) {
                    $("#seedsoption").hide();
                } else {
                    $("#seedsoption").hide();
                }
            }
        }

        // Trigger click event for relevant checkboxes on document ready
        handleCheckboxClick($("#slicedCheckbox"));
        handleCheckboxClick($("#unslicedCheckbox"));
        handleCheckboxClick($("#seedYes"));

        // Attach click event listener to checkboxes
        $('input[type="checkbox"]').click(function() {
            handleCheckboxClick($(this));
        });
    });

    $(document).ready(function() {
        // Initially check if "No add-on" checkbox is checked and hide the add-on section accordingly
        if ($("#no_add_on").is(":checked")) {
            $("#hide_add_on").hide();
        }

        // Toggle visibility of add-on section based on "No add-on" checkbox state
        $('#no_add_on').click(function() {
            if ($(this).is(":checked")) {
                $("#hide_add_on").hide();
            } else {
                $("#hide_add_on").show();
            }
        });
    });
</script>


</body>
</html>