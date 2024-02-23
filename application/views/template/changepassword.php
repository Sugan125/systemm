
  <!-- Content Wrapper. Contains page content -->
  <body class="hold-transition sidebar-mini layout-fixed">

  <div class="content-wrapper" style="min-height: 1302.4px;">
    <!-- Content Header (Page header) -->
   
    <section class="content">
    <div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
        <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Change Password</h3>
                </div>
                
              <!-- /.card-header -->
              <!-- form start -->
              <form method="post" action="<?=base_url('index.php/Logincontroller/changepasscontroller')?>">
                <div class="card-body">
                <div class="form-check">
                      <?php if($this->session->userdata('error')) { ?>
			              	<p class="text-danger"><?=$this->session->userdata('error')?></p>
			              	<?php } ?>
			              	 <?php if($this->session->userdata('success')) { ?>
			              	<p class="text-success"><?=$this->session->userdata('success')?></p>
			              	<?php } ?>
			              	<p class="text-danger"><?php echo validation_errors(); ?></p>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Current Password</label>
                    <input type="password" class="form-control" name="currentPassword" id="input_size" placeholder="Current Password">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">New Password</label>
                    <input type="password" class="form-control" name="password" id="input_size" placeholder="Confirm new Password">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Cofirm New Password</label>
                    <input type="password" class="form-control" id="input_size" name="cpassword" placeholder="Confirm new Password">
                  </div>
                  
                 
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
             </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- Main row -->
      
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
                       </body>
  <!-- /.content-wrapper -->
