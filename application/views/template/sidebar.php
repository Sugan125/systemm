
    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">
            <div class="navbar-header">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="./"><img src="https://sourdoughfactory.com.sg/wp-content/uploads/2021/06/400x100px.png" alt="logo"></a>
            </div>
            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">
                
                    <li class="nav-item">
                
                    <a href="<?= base_url('index.php/Dashboardcontroller/index');?>" class="nav-link <?php if($this->uri->segment(1)== 'dashboard') echo 'active' ?>"> <i class="menu-icon fas fa-tachometer-alt"></i>Dashboard </a>
                    </li>
              <?php if(isset($loginuser['roles']) && !empty($loginuser['roles']) == 'Admin' || isset($loginuser['roles']) && !empty($loginuser['roles']) == 'Owner' || isset($loginuser['roles']) && !empty($loginuser['roles']) == 'Users' || (isset($user->image)) ){ ?>
              

              <li class="nav-item">
                
              <a href="<?= base_url('index.php/Userscontroller');?>" class="nav-link <?php if($this->uri->segment(1)== 'Userscontroller') echo 'active' ?>"> <i class="menu-icon fa fa-users"></i>Users </a>
                    </li>
              <?php }?>
            
       
       <?php if ((isset($user->image)) || 
        isset($loginuser['roles']) &&
        !empty($loginuser['roles']) &&
        (
            ($loginuser['roles'] == 'Admin' || $loginuser['roles'] == 'Owner') ||
            (strpos($loginuser['roles'], 'Admin') !== false && strpos($loginuser['roles'], 'User') !== false)
        )
    ): ?>
      

        <li class="nav-item">
                
        <a href="<?= base_url('index.php/Userrolecontroller');?>" class="nav-link <?php if($this->uri->segment(1) == 'Userrolecontroller') echo 'active' ?>"> <i class="menu-icon fa fa-user-circle"></i>Access </a>
                      </li>
    <?php endif; ?>

    <?php if ((isset($user->image)) || 
        isset($loginuser['roles']) &&
        !empty($loginuser['roles']) &&
        (
            ($loginuser['roles'] == 'Admin' || $loginuser['roles'] == 'Owner') ||
            (strpos($loginuser['roles'], 'Admin') !== false && strpos($loginuser['roles'], 'User') !== false)
        )
    ): ?>
      

        <li class="nav-item">
                
        <a href="<?= base_url('index.php/Productcontroller');?>" class="nav-link <?php if($this->uri->segment(1) == 'Productcontroller') echo 'active' ?>"> <i class="menu-icon fa fa-shopping-cart"></i>Products </a>
                      </li>
    <?php endif; ?>

    <?php if ((isset($user->image)) || 
        isset($loginuser['roles']) &&
        !empty($loginuser['roles']) &&
        (
            ($loginuser['roles'] == 'User' || $loginuser['roles'] == 'Owner') ||
            (strpos($loginuser['roles'], 'User') !== false && strpos($loginuser['roles'], 'User') !== false)
        )
    ): ?>
      

        <li class="nav-item">
                
        <a href="<?= base_url('index.php/Productcontroller/userproduct');?>" class="nav-link <?php if($this->uri->segment(1) == 'Productcontroller/userproduct') echo 'active' ?>"> <i class="menu-icon fa fa-shopping-cart"></i>Products Detail </a>
                      </li>
    <?php endif; ?>
    
                </ul>
            </div>
        </nav>
    </aside>


    <div id="right-panel" class="right-panel" style="display:block;">
    <header id="header" class="header">

    <div class="header-menu">

    <div class="col-sm-7">
        <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>
    </div>

    <div class="col-sm-5">
        <div class="user-area dropdown float-right">
           <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">
    
        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small"> <?php if (isset($user->name)): ?>
                    <?= $user->name ?>
                    <?php elseif ($users && isset($users->name)): ?>
                    <?= $users->name ?>
                    <?php else: ?>
                    <p>No user name available</p>
                    <?php endif; ?></span>
                    
                    <?php if (isset($loginuser['profile_img']) && !empty($loginuser['profile_img'])): ?>
                        <img src="<?= base_url('uploads/profile/' . $loginuser['profile_img']); ?>" class="img-profile rounded-circle" alt="User Image" style="height:2em;">
                        <?php elseif (isset($user->image)): ?>
                        <img src="  <?= $user->image ?>" class="img-circle elevation-2" class="img-profile rounded-circle" alt="Gmail Image" style="height:2em;">
                        <?php else: ?>
                        <img src="<?= base_url();?>uploads/profile/user.png" class="img-profile rounded-circle" alt="Default Image" style="height:2em;">
                    <?php endif; ?>
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in animated_dropdown"
                    aria-labelledby="userDropdown" >
                

                    <a class="dropdown-item" href="<?= base_url('index.php/Userscontroller/profileview');?>">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Profile
                    </a>
                    <?php if((isset($user->image)) || isset($loginuser['roles']) && !empty($loginuser['roles']) == 'Admin' || isset($loginuser['roles']) && !empty($loginuser['roles']) == 'Owner' || isset($loginuser['roles']) && !empty($loginuser['roles']) == 'Users' ){ ?>
                    <a class="dropdown-item" href="<?= base_url('index.php/Logincontroller/changepasswordview');?>">
                        <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                        Change Password
                    </a>
                    <?php } ?>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?= base_url('index.php/Logincontroller/logout');?>" >
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>
                </div>
            </li>

</ul>
</nav>


<!-- /.navbar -->
        </div>

    </div>
</div>

</header><!-- /header -->
<!-- Header-->
</div>
</div>
    

