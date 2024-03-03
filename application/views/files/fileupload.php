<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Uploaded Files</title>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="content-wrapper" style="min-height: 1302.4px;">
        <div class="content-header">
            <div class="container-fluid">
            <div >
        <a href="<?php echo base_url('index.php/Userscontroller'); ?>" class="btn-sm btn btn-danger"><i class="fas fa-backward"></i> Back</a></td>
        </div> 
                <div class="row mb-2 justify-content-center">
                    <div class="col-sm-6">
                        <h3 class="m-0 text-center">Upload files for <?= isset($datanames['name']) ? $datanames['name'] : 'Unknown User'; ?></h3>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Upload Files</h3>
                            </div>

                            <!-- /.card-header -->
                            <!-- form start -->
                            <?php
                            $userName = isset($datanames['name']) ? $datanames['name'] : 'Unknown User';
                            ?>
                            <form action="<?= base_url('index.php/Userscontroller/upload/' . $userName) ?>" enctype="multipart/form-data" method="post">

                                <div class="card-body" id="cardbodyy">
                                    <div class="form-group">
                                        <label class="form-label" id="uploadFile">Select Files</label><br><br>
                                        <input multiple="" type="file" name="uploadedFiles[]">
                                    </div>
                                    <br>

                                    <?php
                                    if ($this->session->flashdata('messgae')) {
                                    ?>
                                        <p class="text-success"> <?= $this->session->flashdata('messgae') ?></p>
                                    <?php } ?>

                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>
