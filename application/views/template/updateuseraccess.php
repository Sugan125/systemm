<!DOCTYPE html>
<html lang="en">

<body class="hold-transition sidebar-mini layout-fixed">

<div class="content-wrapper" style="min-height: 1302.4px;">


    <section class="content">
    <div class="container-fluid">
    <div >
        <a href="<?php echo base_url('index.php/Userrolecontroller'); ?>" class="btn-sm btn btn-danger"><i class="fas fa-backward"></i> Back</a></td>
        </div> 
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Enter User Access</h3>
                    </div>
    
                    <?php foreach($query as $row): ?>
                    <form action="<?= base_url('index.php/Userrolecontroller/adduseraccess') ?>" method="POST">
                        <div class="card-body" style="height:450px;">

                            <label>User Name</label>
                            <div class="input-group mb-3" id="input_size">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">@</span>
                                </div>
                                <select name="name" class="form-control">
                                    <option value="<?= $row->name; ?>"><?= $row->name; ?></option>
                                    <?php foreach ($userss as $user) : ?>
                                        <option value="<?= $user->name; ?>" <?= ($user->name == $row->name) ? 'selected' : ''; ?>>
                                            <?= $user->name; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
    <label>Role</label>
    <div class="input-group mb-3" id="input_size">
        <?php
        $roles = array("Admin", "User", "Owner");
        $selectedRoles = explode(',', $row->role);

        foreach ($roles as $role) {
            $checked = in_array($role, $selectedRoles) ? 'checked' : '';

            if($role == 'Owner'){
                $rolee = 'Manager';
            }
            else{
                $rolee = $role;
            }

            echo '
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="role[]" value="' . $role . '" id="' . $role . 'Checkbox" ' . $checked . '>
                    <label class="form-check-label" for="' . $role . 'Checkbox">' . $rolee . '</label>
                </div>';
        }
        ?>
    </div>
</div>

                            <label>Access</label>
                            <div class="input-group mb-3" id="input_size">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="access[]" value="Add" <?= (in_array('Add', explode(',', $row->access))) ? 'checked' : ''; ?> id="adminCheckbox">
                                    <label class="form-check-label" for="adminCheckbox">Add</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="access[]" value="Edit" <?= (in_array('Edit', explode(',', $row->access))) ? 'checked' : ''; ?> id="userCheckbox">
                                    <label class="form-check-label" for="userCheckbox">Edit</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="access[]" value="Delete" <?= (in_array('Delete', explode(',', $row->access))) ? 'checked' : ''; ?> id="userCheckbox">
                                    <label class="form-check-label" for="userCheckbox">Delete</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="access[]" value="File Upload" <?= (in_array('File Upload', explode(',', $row->access))) ? 'checked' : ''; ?> id="userCheckbox">
                                    <label class="form-check-label" for="userCheckbox">File Upload</label>
                                </div>
                                <!-- Add more checkboxes as needed -->
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>Submit</button>
                            <button type="reset" class="btn btn-danger"><i class="fas fa-trash"></i>Reset</button>
                        </div>
                    </form>
                    <?php endforeach ?>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</section>


</div>

</body>
</html>