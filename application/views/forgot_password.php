<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url();?>public/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?= base_url();?>public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url();?>public/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#">Forgot Password</a>
  </div>
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Enter your email</p>

      <?php echo form_open('/Logincontroller/send_password'); ?>

      <?php if ($this->session->flashdata('errorss')) { ?>
              <p class="text-danger text-center"><?= $this->session->flashdata('errorss') ?></p>
            <?php } ?>
        <div class="input-group mb-3">
          <?php
          $email_data = array(
            'name' => 'email',
            'type' => 'email',
            'required' => 'required',
            'class' => 'form-control',
            'placeholder' => 'Email'
          );
          echo form_input($email_data);
          ?>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-6">
           
          </div>
          <div class="col-6">
            <?php echo form_submit('submit', 'Send Reset Link', 'class="btn btn-primary btn-block"'); ?>
          </div>
        </div>
      <?php echo form_close(); ?>

    </div>
  </div>
</div>

<!-- Add your JS and other scripts here -->

</body>

<!-- jQuery -->
<script src="<?= base_url();?>public/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?= base_url();?>public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url();?>public/dist/js/adminlte.min.js"></script>
</body>
</html>
