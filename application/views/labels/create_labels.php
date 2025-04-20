<!DOCTYPE html>
<html lang="en">

<body class="hold-transition sidebar-mini layout-fixed">

<div class="content-wrapper" style="min-height: 1302.4px;">



<section class="content">
      <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo base_url('index.php/Productcontroller'); ?>" class="btn-sm btn btn-danger"><i class="fas fa-backward"></i> Back</a></td>
        </div> 
        <div class="row justify-content-center">
          <div class="col-md-6">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Enter Label Details</h3>
              </div>
              <form action="<?= base_url('index.php/LabelController/addlabel') ?>" method="POST" enctype="multipart/form-data">
                <div class="card-body">
              
                
                  <label>Product Name</label>
                  <div class="input-group mb-3" id="input_size">
                    
                  <div class="input-group-prepend">
                    <span class="input-group-text">@</span>
                  </div>
                  <input type="text" name="product_name" class="form-control" placeholder="Enter Product Name">
                </div>

                  <div class="form-group">
                  <label>Ingredients</label>
                      <textarea type="textarea"  id="input_size" name="ingredients" class="form-control" placeholder="Ingredients"></textarea>
                  </div>
                  
               


                <input type="hidden" id="created_by" name="created_by" value="<?php echo $loginuser['name']; ?>">

               

                <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>Submit</button>
                </div>
              </form>
            </div>
</div>
</div>
<section>

</div>


</body>
</html>