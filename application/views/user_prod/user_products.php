<head>
    <style>
        .search-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-container .form-control {
            width: 100%;
        }

        .search-container .btn {
            width: 100%;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 15px;
            text-align: center;
        }

        .card-body {
            padding: 15px;
        }

        .equal-image {
            max-width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 6px;
        }

        .pagination-container {
            margin-top: 20px;
        }
    </style>
</head>

<div class="container mt-4">
    <h3 class="text-center">Products</h3><br>

    <!-- Search Bar -->
    <form method="GET" action="<?= base_url('index.php/Productcontroller/userproduct'); ?>">
        <div class="row search-container">
            <div class="col-md-4">
                <input type="text" name="search_name" class="form-control" placeholder="Search by Name" value="<?= isset($search_name) ? $search_name : ''; ?>">
            </div>
            <div class="col-md-3">
                <input type="number" name="search_price" class="form-control" placeholder="Max Price" value="<?= isset($search_price) ? $search_price : ''; ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
            <div class="col-md-2">
                <a href="<?= base_url('index.php/Productcontroller/userproduct'); ?>" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>

    <!-- Product Listing -->
    <section>
        <div class="container">
            <div class="row">
                <?php foreach ($products as $row):
                    $imgg = empty($row->prod_img) ? 'no_product.png' : $row->prod_img;
                ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <img class="img-fluid equal-image" src="<?= base_url();?>uploads/<?= $imgg; ?>" alt="Image Not Found">
                        </div>
                        <label><b><?= $row->product_name; ?></b></label>
                        <label class="text-muted" style="display: block; margin-top: 5px;">Price: $<?= $row->prod_rate; ?></label>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <div class="pagination-container text-center">
                <?= $pagination_links; ?>
            </div>
        </div>
    </section>
</div>
