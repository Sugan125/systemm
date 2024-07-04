<?php
// cron_send_invoices.php

// URL of the function to trigger
$url = 'https://sourdoughfactory.com.sg/system/index.php/Orders/send_invoices_for_today';

// Use cURL to make the request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Optional: Set a timeout
$output = curl_exec($ch);
curl_close($ch);

// Log the output or any errors if needed
file_put_contents('cron_send_invoices.log', date('Y-m-d H:i:s') . " - " . $output . "\n", FILE_APPEND);
?>
