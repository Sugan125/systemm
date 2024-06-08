<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .content-wrapper {
            padding: 20px;
        }
        .card {
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            overflow: hidden;
        }
        .card-header {
           
            color: black;
            padding: 10px;
        }
        .card-body {
            padding: 20px;
        }
        .input-group-text {
           
            color: black;
        }
        .form-check-label {
            margin-left: 0.5rem;
        }
        .form-check-inline {
            margin-bottom: 10px;
        }
        .card-footer {
            display: flex;
            justify-content: space-between;
            padding: 10px;
        }
        .select2-container .select2-selection--single {
            height: calc(1.5em + .75rem + 2px) !important;
        }
        .select2-container .select2-selection--single .select2-selection__rendered {
            line-height: calc(1.5em + .75rem + 2px) !important;
        }
        .select2-container .select2-selection--single .select2-selection__arrow {
            height: calc(1.5em + .75rem + 2px) !important;
        }
        .form-check-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">

<div class="content-wrapper" style="min-height: 1302.4px;">
    <section class="content">
        <div class="container-fluid">
            <div>
                <a href="<?php echo base_url('index.php/Userrolecontroller'); ?>" class="btn-sm btn btn-danger">
                    <i class="fas fa-backward"></i> Back
                </a>
            </div> 
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">EDIT ACCESS</h3>
                        </div>
    
                        <?php foreach($query as $row): ?>
                        <form action="<?= base_url('index.php/Userrolecontroller/adduseraccess') ?>" method="POST">
                            <div class="card-body">

                            <label><b>ADMIN/MGR NAME</b></label>
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

                                <label><b>MENU ACCESS</b></label>
                                <div class="form-check-group">
                                    <?php
                                    $accesses = array("Dashboard","Create User", "Manage User", "Set Access", "Manage Access", "Create Products","Manage Products","Create Order","Manage Orders","Packing List","Production List","Export Sales","Print Invoice","Print DO");
                                    $selectedAccesses = explode(',', $row->access);

                                    foreach ($accesses as $access) {
                                        $checked = in_array($access, $selectedAccesses) ? 'checked' : '';

                                        echo '
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="access[]" value="' . $access . '" id="' . $access . 'Checkbox" ' . $checked . '>
                                                <label class="form-check-label" for="' . $access . 'Checkbox">' . $access . '</label>
                                            </div>';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Submit</button>
                                <button type="reset" class="btn btn-danger"><i class="fas fa-trash"></i> Reset</button>
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

<!-- Include jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('select[name="name"]').select2({
            placeholder: "Select User Name",
            allowClear: true
        });
    });
</script>
</body>
</html>
