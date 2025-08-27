<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="yGCMjr2zLajzdPqM82dfIvJdccSkG6AerqHGPudk">
  <title>Sourdough Factory</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="https://cpbsvr2.cpbev.sg:1443/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cpbsvr2.cpbev.sg:1443/adminlte/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cpbsvr2.cpbev.sg:1443/adminlte/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="https://cpbsvr2.cpbev.sg:1443/adminlte/dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="https://cpbsvr2.cpbev.sg:1443/adminlte/plugins/iCheck/square/blue.css">

  <style>
    .login-page {
        background: url("https://sourdoughfactory.com.sg/wp-content/uploads/2018/07/restaurant-cover-e1532764381195.jpg") no-repeat center center fixed; 
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }
    .login-logo img {
            width: 100% !important;
    }
    .input-group {
    position: relative;
}

#togglePassword {
    cursor: pointer;
    position: absolute;
    top: 50%;
    right: 11px; /* Adjust as needed */
    transform: translateY(-50%);
    z-index: 2; /* Ensure the icon is above the input */
    font-size: 12px;
}

   
  </style>

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <img src="https://sourdoughfactory.com.sg/wp-content/uploads/2021/06/400x100px.png" height="100" width="400">
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
  <?php if($this->session->userdata('error')) { ?>
              	<p class="text-danger text-center"><?=$this->session->userdata('error')?></p>
              	<?php } ?>

                <?php if($this->session->userdata('sucess')) { ?>
              	<p class="text-danger text-center"><?=$this->session->userdata('sucess')?></p>
              	<?php } ?>


                <?php if($this->session->flashdata('error')): ?>
    <div class="alert alert-warning">
        <?= $this->session->flashdata('error'); ?>
    </div>
<?php endif; ?>

    <p class="login-box-msg">Sign in to start your session</p>

    <form action="<?= base_url('index.php/Logincontroller/index'); ?>" method="post">
    <input type="hidden" name="_token" value="">    <span class="pb-4"><small>Login as Main Account</small></span>
    <div class="form-group has-feedback">
        <input id="email" type="text" class="form-control " name="name" value="" required  autofocus placeholder="username">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
    </div>
    <div class="form-group has-feedback">
        <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password" placeholder="Password">
     
            <span class="input-group-text" id="togglePassword">
                <i class="fas fa-eye" id="eyeIcon"></i>
            </span>

</div>


    <div class="row">
        <div class="col-xs-12">
            <button type="submit" class="btn btn-primary btn-block btn-flat">
                Login
            </button>
            <p class="col-12 text-center" style="margin-top:10px;">
      <a href="<?=base_url('index.php/Logincontroller/forgotPassword')?>">Forgot Password</a>
      </p>
       
        </div>
      
    </div>
</form>
 

  </div>
</div>

<!-- jQuery 3 -->
<script src="https://cpbsvr2.cpbev.sg:1443/adminlte/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="https://cpbsvr2.cpbev.sg:1443/adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="https://cpbsvr2.cpbev.sg:1443/adminlte/plugins/iCheck/icheck.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>

const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function (e) {
        // toggle icon and password visibility
        const icon = this.querySelector('#eyeIcon');
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
        
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
    });

    
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
</body>
</html>
