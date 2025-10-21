<?php
session_start();
if (!isset($_SESSION['user']) || empty($_SESSION['cart'])) {
    header("Location: ../index.php");
    exit;
}

// Cấu hình MoMo
$endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
$partnerCode = "MOMO2KAX20250505_TEST";
$accessKey = "96VLU5SQikbYKx8h";
$secretKey = "EAZqkJl5AHgiL2pl7bLhkeL2j8HJx92i";

// Tính tổng tiền
$orderInfo = "Thanh toán đơn hàng qua MoMo";
$amount = 0;

$amount += $_SESSION['tongtien_momo'];

$orderId = time() . "";
$redirectUrl = "http://localhost/LTW/layout/payment/momo_return.php";
$ipnUrl = "http://localhost/LTW/layout/payment/momo_return.php";
$requestId = time() . "";
$requestType = "captureWallet";
$extraData = ""; // Có thể truyền userId

// Tạo chữ ký
$rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl".
           "&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode".
           "&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";
$signature = hash_hmac("sha256", $rawHash, $secretKey);

// Dữ liệu gửi đi
$data = array(
    'partnerCode' => $partnerCode,
    'accessKey' => $accessKey,
    'requestId' => $requestId,
    'amount' => $amount,
    'orderId' => $orderId,
    'orderInfo' => $orderInfo,
    'redirectUrl' => $redirectUrl,
    'ipnUrl' => $ipnUrl,
    'lang' => 'vi',
    'extraData' => $extraData,
    'requestType' => $requestType,
    'signature' => $signature
);

// Gửi request bằng CURL
$ch = curl_init($endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$result = curl_exec($ch);
curl_close($ch);

$jsonResult = json_decode($result, true);

// Redirect sang trang thanh toán MoMo
header('Location: ' . $jsonResult['payUrl']);
exit;
?>
