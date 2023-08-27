<?php

// Check if POST data is set
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Decode the JSON string
    $data = json_decode($_POST['NoticeParams'], true);

    // Check if 'outTradeNo' and 'payCode' are set
    if (isset($data['outTradeNo']) && isset($data['payCode'])) {
        
        // Extract the order number and payment status
        $outTradeNo = explode('_', $data['outTradeNo'])[0]; // Get the part before '_'
        $payCode = $data['payCode'];

        // Check if the payment was successful
        if ($payCode === '0000') {

            // The URL of your script
            $url = '/wp-content/plugins/pay/0000.php';

            // Initialize cURL
            $ch = curl_init($url);

            // Set the options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, ['outTradeNo' => $outTradeNo]);

            // Execute the request and get the response
            $response = curl_exec($ch);

            // Close cURL
            curl_close($ch);

            // Print the response
            echo $response;

            // Print success message
            echo "SUCCESS";
        }
    }
}
?>