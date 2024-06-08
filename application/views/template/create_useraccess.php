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
            <div class="pull-right">
                <a href="<?php echo base_url('index.php/Userrolecontroller'); ?>" class="btn-sm btn btn-danger"><i class="fas fa-backward"></i> Back</a>
            </div> 
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">ADD ACCESS</h3>
                        </div>
                        <form action="<?= base_url('index.php/Userrolecontroller/adduseraccess') ?>" method="POST">
                            <div class="card-body">
                                <label><b>ADMIN/MGR NAME</b></label>
                                <div class="input-group mb-3" id="input_size">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">@</span>
                                    </div>
                                    <select name="name" id="userSelect" class="form-control">
                                        <option value="">Select User Name</option>
                                        <?php foreach ($userss as $row) : ?>
                                            <option value="<?= $row->name; ?>" data-role="<?= $row->role; ?>"><?= $row->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <br>

                                <label><b>ROLE</b></label>
                                <div class="input-group mb-3" id="input_size">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="role[]" value="Admin" id="adminCheckbox">
                                        <label class="form-check-label" for="adminCheckbox">Admin</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="role[]" value="User" id="userCheckbox">
                                        <label class="form-check-label" for="userCheckbox">User</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="role[]" value="Owner" id="managerCheckbox">
                                        <label class="form-check-label" for="managerCheckbox">Manager</label>
                                    </div>
                                </div> 

                                <label><b>MENU ACCESS</b></label>
                                <div class="form-check-group">

                                <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="access[]" value="Dashboard" id="createUserCheck">
                                        <label class="form-check-label" for="createUserCheck">Dashboard</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="access[]" value="Create User" id="createUserCheck">
                                        <label class="form-check-label" for="createUserCheck">Create User</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="access[]" value="Manage User" id="manageUserCheck">
                                        <label class="form-check-label" for="manageUserCheck">Manage User</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="access[]" value="Set Access" id="setAccessCheck">
                                        <label class="form-check-label" for="setAccessCheck">Set User Access</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="access[]" value="Manage Access" id="manageAccessCheck">
                                        <label class="form-check-label" for="manageAccessCheck">Manage User Access</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="access[]" value="Create Products" id="createProductsCheck">
                                        <label class="form-check-label" for="createProductsCheck">Create Products</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="access[]" value="Manage Products" id="manageProductsCheck">
                                        <label class="form-check-label" for="manageProductsCheck">Manage Products</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="access[]" value="Create Order" id="createOrderCheck">
                                        <label class="form-check-label" for="createOrderCheck">Create Order For Any User</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="access[]" value="Manage Orders" id="manageOrdersCheck">
                                        <label class="form-check-label" for="manageOrdersCheck">Manage Orders</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="access[]" value="Packing List" id="printInvoiceCheck">
                                        <label class="form-check-label" for="printInvoiceCheck">Packing List</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="access[]" value="Production List" id="printInvoiceCheck">
                                        <label class="form-check-label" for="printInvoiceCheck">Production List</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="access[]" value="Export Sales" id="printInvoiceCheck">
                                        <label class="form-check-label" for="printInvoiceCheck">Export Sales</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="access[]" value="Print Invoice" id="printInvoiceCheck">
                                        <label class="form-check-label" for="printInvoiceCheck">Print Invoice</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="access[]" value="Print DO" id="printDOCheck">
                                        <label class="form-check-label" for="printDOCheck">Print DO</label>
                                    </div>
                                </div>
                            </div>  
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Submit</button>
                                <button type="reset" class="btn btn-danger"><i class="fas fa-trash"></i> Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Include jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $('#userSelect').select2({
            placeholder: "Select User Name",
            allowClear: true
        });

        $('#userSelect').change(function() {
            var selectedRole = $(this).find(':selected').data('role');
            var selectedAccess = $(this).find(':selected').data('access');

            // if (selectedRole === 'Admin' || selectedRole === 'Owner') {
            //     $('#createOrderCheck').prop('disabled', false);
            // } else {
            //     $('#createOrderCheck').prop('disabled', true);
            //     $('#createOrderCheck').prop('checked', false);
            // }

            $('input[type="checkbox"]').prop('checked', false);
            if (selectedRole === 'Admin') {
                $('#adminCheckbox').prop('checked', true);
            } else if (selectedRole === 'User') {
                $('#userCheckbox').prop('checked', true);
            } else if (selectedRole === 'Owner') {
                $('#managerCheckbox').prop('checked', true);
            }
        });
    });
</script>
</body>
</html>
