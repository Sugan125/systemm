<head>
    <style>
    
.card {
  border: 1px solid #ddd;
  border-radius: 4px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.card-body {
  padding: 20px;
  display: flex;
  align-items: center; /* Align images vertically */
  justify-content: center; /* Align images horizontally */
}

.equal-image {
  max-width: 100%; /* Ensure the image does not exceed the container width */
  height: 150px; /* Set the fixed height for all images */
  object-fit: cover; /* Maintain aspect ratio and cover the container */
}

    </style>
</head>
<h3 class="text-center">Products</h3><br><br>

<div class="content-wrapper">
  <section>
    <div class="container">
    <div class="row">
    <?php foreach ($products as $row):
        if($row->prod_img == ""){
            $imgg = 'no_product.png';
        }
        else{
            $imgg = $row->prod_img;
        }
      ?>
      <div class="col-sm-3 col-md-3 col-xs-3">
        <div class="card">
          <div class="card-body">
            <img class="img-fluid equal-image" src="<?= base_url();?>uploads/<?= $imgg; ?>" alt="Image Not Found">
            
          </div>
          <label class="text-center"><b><?= $row->product_name; ?></b></label>
          <label class="text-center" style="margin-top:10px;"><b>Price  $<?= $row->prod_rate; ?></b></label>
        </div>
      </div>
      <?php
        
      endforeach;
      ?>
    </div>
    </div>
  </section>
</div>

    

