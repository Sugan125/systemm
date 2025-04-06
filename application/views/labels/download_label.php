<!DOCTYPE html>
<html>
<head>
    <title>Generate Labels</title>
</head>
<body>
    <h2>Generate Product Labels</h2>
    <form method="post" action="<?php echo base_url('index.php/LabelController/generate_pdf'); ?>">
        <label>Number of Labels:</label>
        <input type="number" name="num_labels" ><br><br>

        <label>Product Name:</label>
        <select name="product_name" >
            <option value="Almond Chocolat">Almond Chocolat</option>
            <option value="Almond Croissant">Almond Croissant</option>
            <option value="Bagel">Bagel</option>
            <option value="Brioche Loaf">Brioche Loaf</option>
        </select><br><br>

        <label>Production Date:</label>
        <input type="date" name="production_date" ><br><br>

        <button type="submit">Generate PDF</button>
    </form>
</body>
</html>
