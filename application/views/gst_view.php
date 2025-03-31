<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GST Master</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .form-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
            background: #f8f9fa;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .input-group-text {
            background-color: #007bff;
            color: white;
            border: none;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="form-container text-center">
        <h2>GST Percentage</h2>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <form method="post" action="<?php echo site_url('GstController/update_gst'); ?>">
            <div class="form-group">
                <label></label>
                <div class="input-group w-50 mx-auto">
                  
                    <input type="number" step="0.01" name="gst_percentage" class="form-control text-center" value="<?php echo $gst->gst_percentage; ?>" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"> Update GST</button>
        </form>
    </div>
</div>
</body>
</html>
