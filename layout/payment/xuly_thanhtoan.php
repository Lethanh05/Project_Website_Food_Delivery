<?php
session_start();
if (!isset($_POST['method']) || empty($_SESSION['cart']) || !isset($_SESSION['user'])) {
    header("Location: ../giohang.php");
    exit;
}

$method = $_POST['method'];
$_SESSION['tongtien_momo'] = 0;
foreach ($_SESSION['cart'] as $item) {
    $_SESSION['tongtien_momo'] += $item['gia'] * $item['soluong']- $_SESSION['discount_applied']['value'];
}
if ($method == 'cod') {
    header("Location: cod_thanhtoan.php"); // xử lý đơn hàng COD
} elseif ($method == 'momo') {
    header("Location: momo_redirect.php"); // chuyển sang Momo
} else {
    header("Location: ../giohang.php");
}

exit;

