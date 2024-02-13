
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="yGCMjr2zLajzdPqM82dfIvJdccSkG6AerqHGPudk">
  <title>Bread&Hearth</title>
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

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <style>
    .login-page {
        background: url("https://sourdoughfactory.com.sg/wp-content/uploads/2018/07/restaurant-cover-e1532764381195.jpg") no-repeat center center fixed; 
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
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
    <p class="login-box-msg">Sign in to start your session</p>

    <form action="<?= base_url('Login/index'); ?>" method="post">
    <input type="hidden" name="_token" value="">    <span class="pb-4"><small>Login as Main Account</small></span>
    <div class="form-group has-feedback">
        <input id="email" type="text" class="form-control " name="name" value="" required  autofocus placeholder="username">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
    </div>
    <div class="form-group has-feedback">
        <input id="password" type="password" class="form-control " name="password" required autocomplete="current-password" placeholder="password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
    </div>
    <!-- <div class="row">
        <div class="col-xs-8">
            <div class="checkbox icheck">
                <label>
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" >

                    <label class="form-check-label" for="remember">
                        Remember Me
                    </label>
                </label>
            </div>
        </div>
    </div> -->
    <div class="row">
        <!-- /.col -->
        <div class="col-xs-12">
            <button type="submit" class="btn btn-primary btn-block btn-flat">
                Login
            </button>
                            <a class="btn btn-link text-center" href="">
                    Forgot Your Password?
                </a>
                <br>
                        <!-- <a class="btn btn-link text-center" href="">
                Login As Outlet Account
            </a> -->
        </div>
        <!-- /.col -->
    </div>
</form>

  </div>
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="https://cpbsvr2.cpbev.sg:1443/adminlte/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="https://cpbsvr2.cpbev.sg:1443/adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="https://cpbsvr2.cpbev.sg:1443/adminlte/plugins/iCheck/icheck.min.js"></script>
<script>
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
