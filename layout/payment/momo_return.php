<?php
session_start();
include("../../connect.php");

if (isset($_GET['resultCode']) && $_GET['resultCode'] == '0') {
    if (!isset($_SESSION['user']) || empty($_SESSION['cart'])) {
        $_SESSION['thongbao'] = [
            'type' => 'error',
            'title' => 'Thiếu thông tin!',
            'message' => 'Phiên làm việc đã hết hạn hoặc giỏ hàng trống. Không thể lưu đơn hàng.'
        ];
        header("Location: ../giohang.php");
        exit;
    }

    $idUser = $_SESSION['user']['idUser'];
    $ngayDat = date("Y-m-d H:i:s");

    // Lấy mã giảm giá nếu có
    $discountCode = $_SESSION['discount_applied']['code'] ?? null;
    $discountValue = $_SESSION['discount_applied']['value'] ?? 0;

    // Lưu đơn hàng (thêm mã giảm giá)
    $sql = "INSERT INTO donhang (idUser, ngayDat, ptThanhToan, discount_code, discount_value) VALUES (?, ?, 'Momo', ?, ?)";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "issd", $idUser, $ngayDat, $discountCode, $discountValue);
    mysqli_stmt_execute($stmt);
    $idDonHang = mysqli_insert_id($link);

    $idUser = $_SESSION['user']['idUser'];
    $fullName = $_SESSION['user']['fullName'] ?? '';
    $phone = $_SESSION['user']['phone'] ?? '';
    $address = $_SESSION['user']['address'] ?? '';
    // Lưu vào bảng giaohang
    $sql_gh = "INSERT INTO giaohang (idDonHang, fullName, phone, address) VALUES (?, ?, ?, ?)";
    $stmt_gh = mysqli_prepare($link, $sql_gh);
    mysqli_stmt_bind_param($stmt_gh, "isss", $idDonHang, $fullName, $phone, $address);
    mysqli_stmt_execute($stmt_gh);

    // Lưu chi tiết đơn hàng
    $sql_ct = "INSERT INTO chitietdonhang (idDonHang, idSanPham, soLuong, giaMua) VALUES (?, ?, ?, ?)";
    $stmt_ct = mysqli_prepare($link, $sql_ct);

    // Cập nhật tồn kho
    $sql_update_tonkho = "UPDATE sanpham SET tonKho = tonKho - ? WHERE idSanPham = ?";
    $stmt_update = mysqli_prepare($link, $sql_update_tonkho);

    foreach ($_SESSION['cart'] as $item) {
        $idSP = $item['id'];
        $soLuong = $item['soluong'];
        $giaMua = $item['gia'];

        mysqli_stmt_bind_param($stmt_ct, "iiid", $idDonHang, $idSP, $soLuong, $giaMua);
        mysqli_stmt_execute($stmt_ct);

        mysqli_stmt_bind_param($stmt_update, "ii", $soLuong, $idSP);
        mysqli_stmt_execute($stmt_update);
    }

    // Lưu mã giảm giá vào DiscountUsage nếu có
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

    // Xóa giỏ hàng và mã giảm giá
    unset($_SESSION['cart']);
    unset($_SESSION['discount_applied']);

    $_SESSION['thongbao'] = [
        'type' => 'success',
        'title' => 'Thanh toán MoMo thành công!',
        'message' => 'Đơn hàng đã được ghi nhận.'
    ];
} else {
    $_SESSION['thongbao'] = [
        'type' => 'error',
        'title' => 'Thanh toán thất bại!',
        'message' => 'Giao dịch qua MoMo không thành công.'
    ];
}

header("Location: ../thongbao.php");
exit;
