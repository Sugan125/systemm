<!DOCTYPE html>
<html lang="en">

<body class="hold-transition sidebar-mini layout-fixed">

<div class="content-wrapper" style="min-height: 1302.4px;">



<section class="content">
      <div class="container-fluid">
      <div >
        <a href="<?php echo base_url('index.php/LabelController'); ?>" class="btn-sm btn btn-danger"><i class="fas fa-backward"></i> Back</a></td>
        </div> 
        <div class="row justify-content-center">
          <div class="col-md-6">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Update Label Details</h3>
              </div>
              <?php foreach($products as $row): ?>
              <form action="<?= base_url('index.php/LabelController/updatelabelss/'. $row->id) ?>" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                
                 
                
                  <label>Product Name</label>
                  <div class="input-group mb-3" id="input_size">
                    
                  <div class="input-group-prepend">
                    <span class="input-group-text">@</span>
                  </div>
                  <input type="text" name="product_name" class="form-control" placeholder="Enter Product Name" value="<?= $row->product_name; ?>">
                </div>
                <label>Ingredients</label>
                  <div class="input-group mb-3" id="input_size">
                    
                  <div class="input-group-prepend">
                    <span class="input-group-text">@</span>
                  </div>
                  <input type="text" name="ingredients" class="form-control" placeholder="Enter Ingredients" value="<?= $row->ingredients; ?>">
                </div>

                <input type="hidden" id="updated_by" name="updated_by" value="<?php echo $loginuser['name']; ?>">


                <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>Submit</button>
                </div>
              </form>    <?php endforeach; ?>
            
            </div>
</div>
</div>
<section>

</div>


</body>
</html>