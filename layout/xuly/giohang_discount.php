<?php
session_start();
require_once __DIR__ . '/../../connect.php';

$code = $_POST['discount_code'] ?? '';
$userId = $_SESSION['user']['idUser'] ?? null;

if (!$code || !$userId) {
    $_SESSION['thongbao'] = [
        'type' => 'error',
        'title' => 'Lỗi',
        'message' => 'Thiếu thông tin người dùng hoặc mã giảm giá.'
    ];
    header("Location: ../giohang.php");
    exit;
}

// 1. Lấy thông tin mã giảm giá
$sql = "SELECT * FROM DiscountCode WHERE code = ? AND start_date <= CURDATE() AND end_date >= CURDATE()";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "s", $code);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$voucher = mysqli_fetch_assoc($result);

if (!$voucher) {
    $_SESSION['thongbao'] = [
        'type' => 'warning',
        'title' => 'Mã không hợp lệ',
        'message' => 'Mã giảm giá không tồn tại hoặc đã hết hạn.'
    ];
    header("Location: ../giohang.php");
    exit;
}

$codeId = $voucher['id'];

// 2. Kiểm tra số lượt sử dụng tổng thể
if (!is_null($voucher['usage_limit'])) {
    $sql = "SELECT COUNT(*) AS used_count FROM DiscountUsage WHERE discount_code_id = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "i", $codeId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    $totalUsed = $row['used_count'] ?? 0;

    if ($totalUsed >= $voucher['usage_limit']) {
        $_SESSION['thongbao'] = [
            'type' => 'warning',
            'title' => 'Hết lượt dùng',
            'message' => 'Mã đã đạt giới hạn sử dụng.'
        ];
        header("Location: ../giohang.php");
        exit;
    }
}

// 3. Kiểm tra mỗi người dùng chỉ được dùng 1 lần (per_user_limit)
if (!empty($voucher['per_user_limit'])) {
    $sql = "SELECT COUNT(*) AS used_count FROM DiscountUsage WHERE user_id = ? AND discount_code_id = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $userId, $codeId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    if (($row['used_count'] ?? 0) >= 1) {
        $_SESSION['thongbao'] = [
            'type' => 'warning',
            'title' => 'Đã sử dụng',
            'message' => 'Bạn đã sử dụng mã này rồi.'
        ];
        header("Location: ../giohang.php");
        exit;
    }
}

// 4. Kiểm tra điều kiện giá trị đơn hàng tối thiểu
$tongTien = 0;
foreach ($_SESSION['cart'] as $item) {
    $tongTien += $item['gia'] * $item['soluong'];
}

if ($tongTien < $voucher['min_order_value']) {
    $_SESSION['thongbao'] = [
        'type' => 'warning',
        'title' => 'Không đủ điều kiện',
        'message' => 'Đơn hàng chưa đủ điều kiện để áp mã.'
    ];
    header("Location: ../giohang.php");
    exit;
}

// 5. Tính giá trị giảm
$giam = 0;
if ($voucher['discount_type'] === 'percent') {
    $giam = $tongTien * ($voucher['discount_value'] / 100);
} else {
    $giam = $voucher['discount_value'];
}
$giam = min($giam, $tongTien); // không thể giảm hơn tổng tiền

// 6. Ghi thông tin mã giảm vào session
$_SESSION['discount_applied'] = [
    'code' => $voucher['code'],
    'value' => $giam,
    'id' => $codeId
    
];
// Ghi tạm vào DiscountUsage để giữ lượt dùng
$stmt = $link->prepare("INSERT IGNORE INTO DiscountUsage (user_id, discount_code_id) VALUES (?, ?)");
$stmt->bind_param("ii", $userId, $codeId);
$stmt->execute();

$_SESSION['thongbao'] = [
    'type' => 'success',
    'title' => 'Mã hợp lệ',
    'message' => "Đã áp dụng mã giảm giá: {$voucher['code']}"
];

header("Location: ../giohang.php");
exit;