<!DOCTYPE html>
<html lang="en">
<head>
  <style>
  .equal-width-table th,
  .equal-width-table td {
    width: 1%; /* Default to 100% width */
  }
  #file_top{
    margin-top:10px;
  }
  /* Hide default checkbox */
.toggle-switch input[type="checkbox"] {
    display: none;
}

/* Slider */
.slider {
    position: relative;
    display: inline-block;
    width: 40px;
    height: 20px;
    background-color: #ccc;
    border-radius: 20px;
    transition: background-color 0.3s;
}

/* Slider round button */
.slider:before {
    position: absolute;
    content: '';
    height: 16px;
    width: 16px;
    left: 2px;
    bottom: 2px;
    background-color: white;
    border-radius: 50%;
    transition: transform 0.3s;
}

/* Checked state */
.toggle-switch input[type="checkbox"]:checked + .slider {
    background-color: #2196F3;
}

.toggle-switch input[type="checkbox"]:checked + .slider:before {
    transform: translateX(20px);
}

</style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">


<div class="content-wrapper" style="min-height: 1302.4px;">

<?= $this->session->flashdata('error'); ?>
<?= $this->session->flashdata('created'); ?>
<?= $this->session->flashdata('updated'); ?>
<?= $this->session->flashdata('deleted'); ?>
<?= $this->session->flashdata('imported'); ?>

<!-- Search Form -->
<div class="card">
    <div class="card-header">
    <div class="d-flex justify-content-end">
            <a href="<?php echo base_url('index.php/Dashboardcontroller'); ?>" class="btn-sm btn btn-danger"><i class="fas fa-backward"></i> Back</a>
        </div>
        <div class="row">
            <?php if ((isset($user->image)) || (in_array('Admin', $loginuser['role']) || $loginuser['roles'] == 'Admin') || (in_array('Add', $loginuser['access']) == 'Add' && in_array('User', $loginuser['role'])) || ($loginuser['accesss'] == 'Add' && $loginuser['roles' == 'User']) || $loginuser['roles'] == 'Owner') { ?>
                <div class="col-sm-12 col-md-12 mb-3"><!-- Added mb-3 class for margin bottom -->
                    <div class="row justify-content-between">
                        <div class="col-sm-4 col-md-4">
                        <form action="<?= base_url('index.php/Productcontroller/search'); ?>" method="get">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search by Product/Category" name="keyword" value="<?= isset($keyword) ? $keyword : '' ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-sm" type="submit"><i class="fas fa-search"></i> Search</button>
                                </div>
                            </div>
                        </form>
                            <!-- <a href="<// base_url('index.php/Productcontroller/create') ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Create Product</a> -->
                        </div>
                        <div class="col-sm-8 col-md-8 text-right">
                            <form action="<?= base_url('index.php/Productcontroller/importfile') ?>" method="post" enctype="multipart/form-data">
                                <label for="uploadFile" class="btn btn-primary btn-sm" style="margin-bottom: 0px;width: 45%;background: none;border: none;color: black;">
                                    Import <input type="file" id="uploadFile" name="uploadFile">
                                </label>
                                <input type="submit" name="submit" class="btn btn-primary btn-sm" value="Upload" />
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>


<div class="card-body">
<table class="table table-bordered table-striped table-responsive equal-width-table">
  <thead>
    <tr class="text-center">
      <th>Product ID</th>
      <th>Product Name</th>
      <th>Description</th>
      <th>Category</th>
      <th>Image</th>
      <th>Active</th>
      <th>Minimum Order</th>
      <th>Slice</th>
      <th>Seed</th>
      <th>Price</th>
      <th>Created By</th>
      <th>Updated By</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>

  <?php foreach ($products as $row):
      if($row->deleted != '1'){
        if($row->add_on_slice != '' && $row->add_on_seed!=''){
          $comma = ',';
        }
        else{
          $comma = '';
        }
        ?>
      <tr class="odd text-center">
        <td><?= $row->product_id; ?></td>
        <td><?= $row->product_name; ?></td>
        <td><?= $row->product_desc; ?></td>
        <td><?= $row->prod_category; ?></td>
        <td> <img id="HideImg" src="<?= base_url();?>uploads/<?= $row->prod_img; ?>" alt="Image Not Found" onerror="this.src='<?= base_url();?>uploads/no_product.png';" style="width:100%; height:100%;"></td>
        <!-- <td> //$row->add_on_slice; ?>// echo //$comma ?> //$row->add_on_seed; ?></td> -->
        <td><?= $row->active; ?></td>
        <td><?= $row->min_order; ?></td>
        <td>
    <label class="toggle-switch">
        <input type="checkbox" class="toggleCheckbox add_on_slice" data-id="<?= $row->id ?>" <?= $row->add_on_slice ? 'checked' : '' ?>>
        <span class="slider"></span>
    </label>
</td>
<td>
    <label class="toggle-switch">
        <input type="checkbox" class="toggleCheckbox add_on_seed" data-id="<?= $row->id ?>" <?= $row->add_on_seed ? 'checked' : '' ?>>
        <span class="slider"></span>
    </label>
</td>

        <td>$<?= $row->prod_rate; ?></td>   
        <td><?= $row->created_by; ?></td>   
        <td><?= $row->updated_by; ?></td>   
        <td>
          <a href="<?= base_url('index.php/productcontroller/updateproduct/' . $row->id) ?>" class="btn  btn-sm"><i class="fas fa-edit"></i> </a>
          <a href="<?= base_url('index.php/productcontroller/deleteproduct/' . $row->id) ?>" class="btn btn-sm" onclick='return confirm("Are you sure to delete this product?");'><i class="fas fa-trash"></i></a>
        </td>    
      </tr>
     
    <?php } endforeach; ?>
  </tbody>
</table>
<br>
            <!-- Pagination Links -->
            <div class="row">
    <div class="col-sm-6 d-flex justify-content-start">
        <?php $total_rowss = $total_rows; echo "Showing 1 to 10 of ".$total_rowss." entries"; ?>
    </div>
    <div class="col-sm-6 d-flex justify-content-end">
        <?php echo $this->pagination->create_links(); ?>
    </div>
</div>



        
    </div>

</div>
<script>
$(document).ready(function(){
  $('.add_on_slice').click(function(){
  //  alert('Checkbox clicked');
    var productid = $(this).data('id');
    var isChecked = $(this).prop('checked') ? 1 : 0; 
    $.ajax({
        url: '<?php echo base_url('index.php/Productcontroller/update_slice'); ?>',
        method: 'POST',
        data: { productid: productid, isChecked: isChecked },
        dataType: 'json', 
        success: function(response){
            if(response.status === 'success'){
                if(response.isChecked === '1'){
                  $('.add_on_slice[data-id="' + productid + '"]').prop('checked', true);
                swal({
                    title: "Success",
                    text: "Slicing Enabled For this Product",
                    icon: "success",
                    buttons: {
                        confirm: {
                            text: "OK",
                            value: true,
                            visible: true,
                            className: "btn btn-primary",
                            closeModal: true
                        }
                    }
                }).then((value) => {
                    // Reload the page after success
                    window.location.reload();
                });
                  
                } else {
                  $('.add_on_slice[data-id="' + productid + '"]').prop('checked', false);
                swal({
                    title: "Success",
                    text: "Slicing Disabled For this Product",
                    icon: "success",
                    buttons: {
                        confirm: {
                            text: "OK",
                            value: true,
                            visible: true,
                            className: "btn btn-primary",
                            closeModal: true
                        }
                    }
                }).then((value) => {
                    // Reload the page after success
                    window.location.reload();
                });
                  
                }

                
            } else {
              
                alert('Error updating Enable/disable slice option.');
            }
        },
        error: function(xhr, status, error){
            console.log('AJAX Error:', error);
        }
    });
});



$('.add_on_seed').click(function(){
  //  alert('Checkbox clicked');
    var productid = $(this).data('id');
    var isChecked = $(this).prop('checked') ? 1 : 0; 
    $.ajax({
        url: '<?php echo base_url('index.php/Productcontroller/update_seed'); ?>',
        method: 'POST',
        data: { productid: productid, isChecked: isChecked },
        dataType: 'json', 
        success: function(response){
            if(response.status === 'success'){
                if(response.isChecked === '1'){
                  $('.add_on_seed[data-id="' + productid + '"]').prop('checked', true);
                swal({
                    title: "Success",
                    text: "Seed Enabled For this Product",
                    icon: "success",
                    buttons: {
                        confirm: {
                            text: "OK",
                            value: true,
                            visible: true,
                            className: "btn btn-primary",
                            closeModal: true
                        }
                    }
                }).then((value) => {
                    // Reload the page after success
                    window.location.reload();
                });
                  
                } else {
                  $('.add_on_seed[data-id="' + productid + '"]').prop('checked', false);
                swal({
                    title: "Success",
                    text: "Seed Disabled For this Product",
                    icon: "success",
                    buttons: {
                        confirm: {
                            text: "OK",
                            value: true,
                            visible: true,
                            className: "btn btn-primary",
                            closeModal: true
                        }
                    }
                }).then((value) => {
                    // Reload the page after success
                    window.location.reload();
                });
                  
                }

                
            } else {
              
                alert('Error updating Enable/disable seed option.');
            }
        },
        error: function(xhr, status, error){
            console.log('AJAX Error:', error);
        }
    });
});

});
</script>
</body>
</html>
