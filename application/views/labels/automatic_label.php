<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Generate Product Labels</title>
    <style>
        .box-body {
            overflow-x: auto;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            white-space: nowrap;
        }

        .table-wrapper table {
            width: 100%;
        }

        .form-group label {
            text-align: left;
            display: block;
            font-weight: bold;
        }

        .btn-danger {
            background-color: #d9534f;
            border-color: #d43f3a;
        }

        .form-control {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .content-wrapper {
            padding: 20px;
        }

        .btn {
            padding: 10px 20px;
            font-weight: bold;
        }

        h3.box-title {
            margin-top: 0;
        }

    </style>
</head>
<body>
    <div class="content-wrapper">
        <section class="content">
            <div class="row">
                <div class="col-md-12 col-xs-12">
                <?php if($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <?php echo $this->session->flashdata('success'); ?>
                    </div>
                    <?php elseif($this->session->flashdata('errors')): ?>
                    <div class="alert alert-error alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <?php echo $this->session->flashdata('errors'); ?>
                        
                    </div>
                    <?php elseif($this->session->flashdata('deleted')): ?>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <?php echo $this->session->flashdata('deleted'); ?>
                    </div>
                    <?php endif; ?>       
                    <!-- <a href="<?php //echo base_url('index.php/LabelController/create') ?>" class="btn btn-success">Add
                        Label</a>
                   <br /> <br /> -->
                    <div class="d-flex justify-content-end">
                        <a href="<?= base_url('index.php/Dashboardcontroller'); ?>" class="btn-sm btn btn-danger">
                            <i class="fas fa-backward"></i> Back
                        </a>
                    </div>
                    
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Generate Product Labels</h3>
                        </div>
                        <br>
                        <div class="box-body" style="text-align: center;">
                     <form method="post" action="<?= site_url('LabelController/generate_autolabels') ?>" 
      style="display: inline-block; text-align: left; max-width: 600px;">

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="prod_date">Production Date:</label>
                <input type="date" name="production_date" id="prod_date" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="new_row">Indicate New Printing Row:</label>
                <input type="number" value="1" id="new_row" name="new_row" class="form-control">
            </div>
        </div>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-danger">
            <i class="fas fa-tags"></i> Generate Labels
        </button>
    </div>
</form>

                    </div>

                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        document.getElementById('prod_date').addEventListener('change', function () {
            let prodDate = new Date(this.value);
            if (isNaN(prodDate)) return;

            let chilled = new Date(prodDate);
            chilled.setDate(chilled.getDate() + 5);
            let frozen = new Date(prodDate);
            frozen.setDate(frozen.getDate() + 14);

            document.getElementById('chilled_date').value = chilled.toLocaleDateString('en-GB');
            document.getElementById('frozen_date').value = frozen.toLocaleDateString('en-GB');
        });

        // Set today's date by default
        window.addEventListener('DOMContentLoaded', () => {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('prod_date').value = today;
            document.getElementById('prod_date').dispatchEvent(new Event('change'));
        });
    
    document.addEventListener('DOMContentLoaded', function () {
        const newRowInput = document.getElementById('new_row');
        const labelsInput = document.getElementById('no_of_labels');

        const maxLabelsByRow = {
            1: 24,
            2: 21,
            3: 18,
            4: 15,
            5: 12,
            6: 9,
            7: 6,
            8: 3
        };

        function updateLabelLimit() {
            const newRow = parseInt(newRowInput.value) || 1;
            const maxLabels = maxLabelsByRow[newRow] || 0;

            labelsInput.max = maxLabels;
            labelsInput.placeholder = `Max ${maxLabels} labels`;

            if (parseInt(labelsInput.value) > maxLabels) {
                labelsInput.value = maxLabels;
            }
        }

        // Initial limit set
        updateLabelLimit();

        // Update when new_row changes
        newRowInput.addEventListener('input', updateLabelLimit);
    });
</script>

</body>
</html>
        