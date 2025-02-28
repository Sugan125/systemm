<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Conditions</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #eef5ff;
        }
        .header {
            width: 100%;
            position: absolute;
            top: 10px;
            right: 20px;
            text-align: right;
        }
        .logout-btn {
            background: rgb(43, 23, 130);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
        }
        .logout-btn:hover {
            background:rgb(43, 23, 130);
        }
        .container {
            max-width: 750px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            border: 3px solid #007BFF;
        }
        h2 { 
            text-align: center; 
            color: #004080; 
            font-size: 22px; 
            font-weight: bold; 
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        p, li { 
            font-size: 15px; 
            color: #333; 
            line-height: 1.8; 
        }
        ul { 
            padding-left: 20px; 
        }
        .agree-box { 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin-top: 20px; 
        }
        input[type="checkbox"] { 
            transform: scale(1.3); 
            margin-right: 10px;
        }
        label { 
            font-size: 16px; 
            font-weight: bold; 
            color: #004080;
        }
        button { 
            background: #004080; 
            color: white; 
            border: none; 
            padding: 12px 25px; 
            cursor: pointer; 
            border-radius: 5px; 
            font-size: 18px; 
            font-weight: bold;
            display: block; 
            margin: 20px auto 0; 
            width: 100%;
        }
        button:disabled { 
            background: #b3c6ff; 
            cursor: not-allowed; 
        }
        button:hover:not(:disabled) { 
            background: #002855;
        }
    </style>
</head>
<body>

    <!-- Logout Button -->
    <nav class="navbar navbar-light bg-light px-3">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold">Sourdough Factory LLP</a>
            <a class="logout-btn btn btn-primary" href="<?= base_url('index.php/Logincontroller/logout');?>">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </nav>
    <div class="container mt-5">
        <h2>Terms and Conditions of Food Service Supply by Sourdough Factory LLP</h2>
        <p>By logging in for the first time, you confirm that you have read, understood, and agreed to our company policies. This agreement is a binding contract between you and <b>SOURDOUGH FACTORY LLP</b> regarding the use of our services. By checking the box, you accept these Terms & Conditions before proceeding with any transactions or placing orders via our EOS platform: 
            <a href="https://www.sourdoughfactory.com.sg/system" target="_blank">www.sourdoughfactory.com.sg/system</a>.
        </p>

        <ul>
            <li><strong>Order Placement and Payment Obligations:</strong> Customers must verify all order details before confirmation. Payments must be made within the agreed terms as outlined in the account opening form. Failure to comply with payment terms may result in the suspension of EOS access. The company/business reserves the right to take legal action for debt recovery, and all associated recovery costs will be charged to the customer.</li>
            <li><strong>Legal Recourse for Non-Payment:</strong> Failure to settle payments under COD (Cash on Delivery) terms may lead to legal action, including asset seizure. All legal costs incurred during the debt recovery process will be borne by the customer.</li>
            <li><strong>Data Storage and Agreement Records:</strong> Your acceptance of these terms will be electronically recorded for legal reference.</li>
            <li><strong>Modification of Terms:</strong> We reserve the right to update these terms at any time. Continued use of our services constitutes acceptance of any changes.</li>
        </ul>

        <form action="<?= base_url('index.php/Terms_conditions/agree') ?>" method="post">
            <div class="agree-box">
                <input type="checkbox" id="agree" onchange="toggleButton()">
                <label for="agree">I agree to the Terms and Conditions</label>
            </div>
            <button type="submit" id="agreeBtn" disabled>Proceed</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function toggleButton() {
            document.getElementById('agreeBtn').disabled = !document.getElementById('agree').checked;
        }
    </script>

</body>
</html>
