
<!DOCTYPE html>
<html lang="en">

<body class="hold-transition sidebar-mini layout-fixed">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="min-height: 1302.4px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2 justify-content-center">
          <div class="col-sm-6">
            <h1 class="text-center">User Profile</h1>
          </div>
          
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <!-- left column -->
                    <div class="col-md-6">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Edit Profile Pic</h3>
                            </div>
                            <div class="card-body" id="cardbody">
                            <div class="card-body pt-0">
                                <div class="row">
                                  <div class="col-7">
                                    <h2 class="large"><b> 
                                      <?php if (isset($loginuser['name'])): ?>
                                            <?= $loginuser['name']; ?>
                                            <?php elseif (isset($user->name)): ?>
                                                 <?= $user->name ?>
                                        <?php else: ?>
                                            <p>No user name available</p>
                                        <?php endif; ?></b></h2><br>
                                    <!-- <p class="text-muted text-sm"><i class="fas fa-lg fa-email"></i><b>About: </b> Web Designer / UX / Graphic Artist / Coffee Lover </p> -->
                                    <ul class="ml-4 mb-0 fa-ul text-muted">
                                      <li class="medium"><span class="fa-li"><i class="fas fa-lg fa-envelope"></i></span> Email:  
                                      <?php if (isset($loginuser['email'])): ?>
                                          <?= $loginuser['email']; ?>
                                            <?php elseif(isset($user->email)): ?>
                                                <?= $user->email ?>
                                                <?php else: ?>
                                                <p>No Email available</p>
                                            <?php endif; ?></li><br>
                                      <li class="medium"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> Address:  
                                      <?php if (isset($loginuser['address'])): ?>
                                                <?= $loginuser['address']; ?>
                                            <?php elseif(isset($user->image)): ?>
                                                <?php echo "Pondicherry"; ?>
                                                <?php else: ?>
                                                <p>No address available</p>
                                            <?php endif; ?></li><br>
                                      <li class="medium"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> Phone #: 
                                      <?php if (isset($loginuser['contact'])): ?>
                                              <?= $loginuser['contact']; ?>
                                              <?php elseif(isset($user->image)): ?>
                                                <?php echo "7897899"; ?>
                                          <?php else: ?>
                                              <p>No contact available</p>
                                          <?php endif; ?></li><br>
                                    </ul>
                                  <!-- <a href="<?= base_url('index.php/Userscontroller/update/' . $loginuser['id']) ?>" class="btn btn-primary" hidden><i class="fas fa-edit">EDIT</i> </a> -->
                                  </div>
                                  
                                  <div class="col-5 text-center position-relative">
    <form id="profilePicForm" enctype="multipart/form-data" method="post" action="<?= base_url('index.php/Userscontroller/update_profile_pic'); ?>">
        <input type="hidden" name="user_id" value="<?php if (isset($loginuser['id'])): $loginuser['id']; endif; ?>">
        <input type="hidden" name="MAX_FILE_SIZE" value="5242880"> <!-- Set your maximum file size in bytes -->

        <!-- Profile picture input -->
        <input type="file" id="profilePicInput" name="profile_pic" style="display: none;">

        <!-- Profile picture display with edit icon overlay -->
        <label for="profilePicInput">
            <?php if (isset($loginuser['profile_img']) && !empty($loginuser['profile_img'])): ?>
                <img src="<?= base_url('uploads/profile/' . $loginuser['profile_img']); ?>" alt="user-avatar" class="img-circle img-fluid">
                <?php elseif (isset($user->image)): ?>
                <img src="  <?= $user->image ?>" class="img-circle img-fluid" alt="Gmail Image" >
                <?php else: ?>
                <!-- Provide a default image or display a placeholder if 'profile_img' is not set -->
                <img src="<?= base_url('uploads/profile/bird.jpg'); ?>" alt="default-avatar" class="img-circle img-fluid" style="border-radius: 50%;">
             <?php endif; ?>

            <!-- Edit icon (pencil) overlay -->
            <?php if (!isset($user->image)): ?>
            <div class="edit-icon">
                <i class="fas fa-pencil-alt"></i>
            </div>
            <?php endif; ?>
        </label>

        <!-- Submit button -->
        <button type="submit" id="submitBtn" style="display: none;">Update Profile Picture</button>
    </form>
</div>
                                </div>
                              </div>                              
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
    </section>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- JavaScript code for handling the click event and form submission -->
<script>
    $(document).ready(function () {
        $('#editProfilePic').on('click', function (e) {
            e.preventDefault();
            // Trigger the click event on the hidden file input when the profile picture is clicked
            $('#profilePicInput').click();
        });
        
        // Handle file input change event
        $('#profilePicInput').on('change', function () {
            // Update the profile picture preview with the selected image
            var newImage = URL.createObjectURL(this.files[0]);
            $('#profileImg').attr('src', newImage);
            
            // Show the submit button
            $('#submitBtn').show();
        });

        // Submit form when the button is clicked
        $('#submitBtn').on('click', function () {
            $('#profilePicForm').submit();
        });
    });
</script>
</body>
</html>
