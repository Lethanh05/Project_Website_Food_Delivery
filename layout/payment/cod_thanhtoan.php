<?php
session_start();
include("../../connect.php");

if (!isset($_SESSION['user']) || empty($_SESSION['cart'])) {
    header("Location: ../index.php");
    exit;
}

$idUser = $_SESSION['user']['idUser'];
$ngayDat = date("Y-m-d H:i:s");

// Lấy thông tin mã giảm giá nếu có
$discountCode = $_SESSION['discount_applied']['code'] ?? null;
$discountValue = $_SESSION['discount_applied']['value'] ?? 0;

// Lưu đơn hàng (có thêm discount_code và discount_value)
$sql = "INSERT INTO donhang (idUser, ngayDat, discount_code, discount_value) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "issd", $idUser, $ngayDat, $discountCode, $discountValue);
mysqli_stmt_execute($stmt);
$idDonHang = mysqli_insert_id($link);
// Ghi thông tin giao hàng
$fullName = $_SESSION['user']['fullName'] ?? '';
$phone = $_SESSION['user']['phone'] ?? '';
$address = $_SESSION['user']['address'] ?? '';

$sql_gh = "INSERT INTO giaohang (idDonHang, fullName, phone, address) VALUES (?, ?, ?, ?)";
$stmt_gh = mysqli_prepare($link, $sql_gh);
mysqli_stmt_bind_param($stmt_gh, "isss", $idDonHang, $fullName, $phone, $address);
mysqli_stmt_execute($stmt_gh);

// Lưu chi tiết đơn hàng và cập nhật tồn kho
$sql_ct = "INSERT INTO chitietdonhang (idDonHang, idSanPham, soLuong, giaMua) VALUES (?, ?, ?, ?)";
$stmt_ct = mysqli_prepare($link, $sql_ct);


$sql_update_tonkho = "UPDATE sanpham SET tonKho = tonKho - ? WHERE idSanPham = ?";
$stmt_update = mysqli_prepare($link, $sql_update_tonkho);

foreach ($_SESSION['cart'] as $item) {
    $idSP = $item['id'];
    $soLuong = $item['soluong'];
    $giaMua = $item['gia'];

    // Lưu chi tiết đơn hàng
    mysqli_stmt_bind_param($stmt_ct, "iiid", $idDonHang, $idSP, $soLuong, $giaMua);
    mysqli_stmt_execute($stmt_ct);

    // Giảm tồn kho
    mysqli_stmt_bind_param($stmt_update, "ii", $soLuong, $idSP);
    mysqli_stmt_execute($stmt_update);
}

// Nếu có áp mã thì lưu vào bảng DiscountUsage
if (isset($_SESSION['discount_applied'])) {
    $code = $_SESSION['discount_applied']['code'];
    $stmt = $link->prepare("SELECT id FROM DiscountCode WHERE code = ?");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $codeId = $row['id'] ?? null;

    if ($codeId) {
        $stmt = $link->prepare("INSERT IGNORE INTO DiscountUsage (user_id, discount_code_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $idUser, $codeId);
        $stmt->execute();
    }
}

// Xóa giỏ hàng và mã giảm giá đã dùng
unset($_SESSION['cart']);
unset($_SESSION['discount_applied']);

// Gán thông báo
$_SESSION['thongbao'] = [
    'type' => 'success',
    'title' => 'Đặt hàng thành công!',
    'message' => 'Bạn đã chọn thanh toán tiền mặt. Cảm ơn bạn!'
];

header("Location: ../thongbao.php");
exit;
