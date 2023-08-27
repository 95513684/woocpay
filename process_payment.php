<?php

$totalAmount = isset($_POST['totalAmount']) ? $_POST['totalAmount'] : 0;
$orderId = isset($_POST['orderId']) ? $_POST['orderId'] : '';
$tradeIP = isset($_POST['clientIp']) ? $_POST['clientIp'] : '';

$params = array(
    "appID" => "1111",
    "currencyCode" => "INR",
    "notifyUrl" => "https://yoururl.com/wp-content/plugins/pay/callback.php",
    "outTradeNo" => $orderId,
    "payEmail" => "xiaoming@email.com",
    "payName" => "xiaoming",
    "payPhone" => "68660387",
    "productTitle" => "tongyong",
    "randomNo" => "6183",
    "totalAmount" => $totalAmount,
    "tradeCode" => "INR001",
    "tradeIP" => $tradeIP,
    "payBankCard" =>  "123456",
    "payBankCode" => "PAY" 
);

ksort($params);

$json_str = json_encode($params, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

$merchant_key = "yourkey";
$sign_data = $json_str . $merchant_key;

$sign = strtoupper(md5($sign_data));

$params["sign"] = $sign;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://yourapiurl");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('ApplyParams' => json_encode($params))));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

curl_close($ch);

$response_data = json_decode($response, true);

// 检查响应是否成功
if (isset($response_data['resultCode']) && $response_data['resultCode'] === '0000') {
    // 获取 payURL
    $payURL = isset($response_data['payURL']) ? $response_data['payURL'] : '';

    if ($payURL) {
        // 如果 payURL 存在，重定向用户到这个 URL
        header('Location: ' . $payURL);
        exit;
    } else {
        // 如果 payURL 不存在，显示一个错误消息
        echo "Error: Missing payURL";
        exit;
    }
} else {
    // 如果响应不成功，显示一个错误消息
    echo "Error: Invalid response";
    exit;
}