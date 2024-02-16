<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
    <title>Uploaded Files</title>
    <style>
        .file-container {
            margin-right: 20px;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            display: inline-block;
        }

        
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="content-wrapper" style="min-height: 1302.4px;">

        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2 justify-content-center">
                    <div class="col-sm-6">
                        <?php $userName = isset($datanames['name']) ? $datanames['name'] : 'Unknown User'; ?>
                        <h1 class="m-0 text-center">Uploaded files of <?php echo $userName; ?></h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <!-- left column -->
                    <div class="col-md-6">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">View Documents</h3>
                            </div>
                            <div class="card-body" id="cardbody">
                                <?php if (empty($data['files'])): ?>
                                    <p>No files available.</p>
                                <?php else: ?>
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Document Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($data['files'] as $index => $row): ?>
                                                <tr>
                                                    <td><?= $row->img_path; ?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-light" data-toggle="modal" data-target="#showfile<?= $index ?>">
                                                            <a href="#" style="margin-right:10px;"><i class="fa fa-eye"></i></a>
                                                        </button>
                                                        <a href="<?= base_url('uploads/images/' . $row->img_path); ?>" download><i class="fa fa-download"></i></a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (!empty($data['files'])):
    foreach ($data['files'] as $index => $row): ?>
        <div class="modal fade modalview bd-example-modal-lg" id="showfile<?= $index ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?= $row->img_path; ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="overflow: auto;">
                          <?php
                          $fileExtension = pathinfo($row->img_path, PATHINFO_EXTENSION);
                          $fileUrl = base_url('uploads/images/' . $row->img_path);

                          if (in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif'])): ?>
                              <!-- Display image if it's an image file -->
                              <img src="<?= $fileUrl; ?>" alt="Image" width="100%" height="450px">
                          <?php elseif (strtolower($fileExtension) === 'pdf'): ?>
                              <!-- Display PDF viewer if it's a PDF file -->
                              <embed src="<?= $fileUrl; ?>" type="application/pdf" width="100%" height="450px">
                          <?php else: ?>
                              <!-- Display a message for other file types -->
                              <p>File type not supported.</p>
                          <?php endif; ?>
                      </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach;
endif; ?>
        </section>

    </div>
</body>

</html>
