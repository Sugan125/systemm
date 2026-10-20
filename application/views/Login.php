<?php $assets = base_url('assets/adminlte/'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Sourdough Factory</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <!-- Font only (your local file). Falls back to Helvetica/Arial if it is missing. -->
  <link rel="stylesheet" href="<?= $assets ?>css/css.css">

  <!--
    Same look as the live login page (AdminLTE 2 + Bootstrap 3 values),
    written directly here so the page does not need any theme files.
  -->
  <style>
    *, *:before, *:after { box-sizing: border-box; }
    html, body { height: 100%; margin: 0; }

    body {
      min-height: 100%;
      font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;
      font-size: 14px;
      line-height: 1.42857143;
      font-weight: 400;
      color: #666;
      background: #d2d6de url("https://sourdoughfactory.com.sg/wp-content/uploads/2018/07/restaurant-cover-e1532764381195.jpg") no-repeat center center fixed;
      background-size: cover;
    }

    a { color: #3c8dbc; text-decoration: none; }
    a:hover, a:focus, a:active { color: #72afd2; outline: none; text-decoration: none; }
    p { margin: 0 0 10px; }
    small { font-size: 85%; }

    /* Logo + card */
    .login-box { width: 360px; margin: 7% auto; }
    .login-logo { font-size: 35px; text-align: center; margin-bottom: 25px; font-weight: 300; }
    .login-logo img { width: 100% !important; display: block; border: 0; }

    .login-box-body { background: #fff; padding: 20px; border-top: 0; color: #666; }
    .login-box-msg { margin: 0; text-align: center; padding: 0 20px 20px 20px; }

    /* Messages */
    .text-danger { color: #a94442; }
    .text-center { text-align: center; }
    .alert {
      padding: 15px;
      margin-bottom: 20px;
      border: 1px solid #e08e0b;
      border-radius: 3px;
      background-color: #f39c12;
      color: #fff;
    }

    /* Fields */
    .form-group { margin-bottom: 15px; }
    .has-feedback { position: relative; }

    .form-control {
      display: block;
      width: 100%;
      height: 34px;
      padding: 6px 42.5px 6px 12px;
      font-family: inherit;
      font-size: 14px;
      line-height: 1.42857143;
      color: #555;
      background-color: #fff;
      background-image: none;
      border: 1px solid #d2d6de;
      border-radius: 0;
      box-shadow: none;
      transition: border-color ease-in-out .15s;
    }
    .form-control::placeholder { color: #999; opacity: 1; }
    .form-control:focus { border-color: #3c8dbc; outline: 0; box-shadow: none; }

    .form-control-feedback {
      position: absolute;
      top: 0;
      right: 0;
      z-index: 2;
      display: flex;
      align-items: center;
      justify-content: center;
      width: 34px;
      height: 34px;
      color: #777;
      pointer-events: none;
    }
    .form-control-feedback svg { width: 14px; height: 14px; }

    #togglePassword {
      position: absolute;
      top: 50%;
      right: 11px;
      transform: translateY(-50%);
      z-index: 3;
      display: flex;
      padding: 0;
      border: 0;
      background: transparent;
      color: #777;
      cursor: pointer;
    }
    #togglePassword svg { width: 15px; height: 15px; }
    #togglePassword .eye-off { display: none; }
    #togglePassword.is-on .eye { display: none; }
    #togglePassword.is-on .eye-off { display: block; }

    /* Button */
    .btn {
      display: block;
      width: 100%;
      padding: 6px 12px;
      margin-bottom: 0;
      font-family: inherit;
      font-size: 14px;
      font-weight: 400;
      line-height: 1.42857143;
      text-align: center;
      white-space: nowrap;
      vertical-align: middle;
      cursor: pointer;
      border: 1px solid #367fa9;
      border-radius: 0;
      box-shadow: none;
      background-color: #3c8dbc;
      color: #fff;
    }
    .btn:hover, .btn:active, .btn:focus { background-color: #367fa9; border-color: #204d74; color: #fff; outline: none; }
    .btn:disabled { opacity: .65; cursor: default; }

    .pb-4 { padding-bottom: 1.5rem; }
    .forgot { margin-top: 10px; text-align: center; }

    @media (max-width: 768px) {
      .login-box { width: 90%; margin-top: 20px; }
    }
  </style>
</head>
<body>
<div class="login-box">
  <div class="login-logo">
    <img src="https://sourdoughfactory.com.sg/wp-content/uploads/2021/06/400x100px.png" height="100" width="400" alt="Sourdough Factory">
  </div>

  <div class="login-box-body">
    <?php if ($this->session->userdata('error')) { ?>
      <p class="text-danger text-center"><?= $this->session->userdata('error') ?></p>
    <?php } ?>

    <?php if ($this->session->userdata('sucess')) { ?>
      <p class="text-danger text-center"><?= $this->session->userdata('sucess') ?></p>
    <?php } ?>

    <?php if ($this->session->flashdata('error')): ?>
      <div class="alert"><?= $this->session->flashdata('error'); ?></div>
    <?php endif; ?>

    <p class="login-box-msg">Sign in to start your session</p>

    <form id="loginForm" action="<?= base_url('index.php/Logincontroller/index'); ?>" method="post">
      <input type="hidden" name="_token" value="">
      <span class="pb-4"><small>Login as Main Account</small></span>

      <div class="form-group has-feedback">
        <input id="email" type="text" class="form-control" name="name" value="" required autofocus placeholder="username">
        <span class="form-control-feedback">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2.5" y="4.5" width="19" height="15" rx="1.5"/><path d="M3 6l9 7 9-7"/></svg>
        </span>
      </div>

      <div class="form-group has-feedback">
        <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password" placeholder="Password">
        <button type="button" id="togglePassword" aria-label="Show password">
          <svg class="eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1.5 12S5.5 5 12 5s10.5 7 10.5 7-4 7-10.5 7S1.5 12 1.5 12z"/><circle cx="12" cy="12" r="3"/></svg>
          <svg class="eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.9 17.9A10.8 10.8 0 0 1 12 19C5.5 19 1.5 12 1.5 12a19.7 19.7 0 0 1 5-5.6M9.9 5.2A10 10 0 0 1 12 5c6.5 0 10.5 7 10.5 7a19.6 19.6 0 0 1-2.2 3.1M1 1l22 22"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg>
        </button>
      </div>

      <button type="submit" class="btn" id="submitBtn">Login</button>
      <p class="forgot">
        <a href="<?= base_url('index.php/Logincontroller/forgotPassword') ?>">Forgot Password</a>
      </p>
    </form>
  </div>
</div>

<script>
  (function () {
    var pwd = document.getElementById('password');
    var btn = document.getElementById('togglePassword');

    btn.addEventListener('click', function () {
      var show = pwd.type === 'password';
      pwd.type = show ? 'text' : 'password';
      btn.classList.toggle('is-on', show);
      btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
    });

    // Avoid double clicks while the login request is sent
    document.getElementById('loginForm').addEventListener('submit', function () {
      var s = document.getElementById('submitBtn');
      s.disabled = true;
      s.textContent = 'Logging in...';
    });
    window.addEventListener('pageshow', function () {
      var s = document.getElementById('submitBtn');
      s.disabled = false;
      s.textContent = 'Login';
    });
  })();
</script>
</body>
</html>
