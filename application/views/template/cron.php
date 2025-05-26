<?php
// cron_check_payment.php

// URL of the function to trigger
$url = 'https://sourdoughfactory.com.sg/system/index.php/Orders/cron_check_user_payment_status';

// Use cURL to make the request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Optional: Set a timeout
$output = curl_exec($ch);
curl_close($ch);

// Log the output or any errors if needed
$log_content = date('Y-m-d H:i:s') . " - Output: " . $output . " - Error: " . $curl_error . "\n";
file_put_contents('/home/customer/www/sourdoughfactory.com.sg/public_html/system/cron_check_user_payment_status.log', $log_content, FILE_APPEND);
?>
