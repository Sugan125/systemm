<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Labels</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .label-sheet {
            display: grid;
            grid-template-columns: repeat(3, 68mm); /* Adjust width */
            grid-template-rows: repeat(8, 35mm); /* Adjust height */
            gap: 2mm;
            width: 210mm;
            height: 297mm;
            padding: 2mm;
            box-sizing: border-box;
        }
        .label {
            border: 1px dashed #000; /* Remove for final print */
            display: flex;
            flex-direction: column;
            /* justify-content: center; */
            align-items: flex-start;
            text-align: left;
            /* padding: 5px; */
            box-sizing: border-box;
            font-size: 10px; /* Adjust font size for fitting */
        }
        @font-face {
            font-family: 'Sourdough';
            src: url('<?= base_url();?>assets/font/sourdough/sourdough.ttf') format('truetype');
        }
        h3 {
            margin: 0;
            font-size: 12px; /* Scales with label size */
            text-align: left;
            width: 100%;
            font-family: 'Sourdough', sans-serif;
        }
        p {
            margin: 2px 0;
            font-size: 10px; /* Adjust font size */
        }
        .nowrap {
            white-space: nowrap; /* Prevents line break */
        }
        .best-before {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 10px;
        }
        .batch-no {
            white-space: nowrap; /* Ensures batch number stays inline */
        }
        @media print {
            .label { border: none; }
        }
    </style>
</head>
<body style="padding:0px; margin:0px;">

<div class="label-sheet">
    <?php 
    for ($i = 1; $i <= 24; $i++) { ?>
        <div class="label">
            <h3>SOURDOUGH FACTORY</h3>
            <p style="margin-bottom: 12px;"><strong>Add:</strong> 5 Mandai Link #07-05, S(725654)</p>
            <p style="margin-bottom: 12px;"><strong>Bagel</strong><br>Ingredients: Flour, Salt, Sugar, Yeast, Water</p>
            <p class="best-before">
                Best Before: 27/02/25 - Chilled 
                <span class="batch-no">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (Batch No.: 250222)</span>
            </p>
            <p class="best-before">
                Best Before: 08/03/25 - Frozen
            </p>
        </div>
    <?php } ?>
</div>

<script>
    window.print();
</script>

</body>
</html>
