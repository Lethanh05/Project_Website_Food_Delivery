<?php
session_start();
include('../../connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idDonHang'], $_POST['trangThai'])) {
    $id = intval($_POST['idDonHang']);
    $trangThai = $_POST['trangThai'];

    $stmt = $link->prepare("UPDATE donhang SET trangThai = ? WHERE idDonHang = ?");
    $stmt->bind_param("si", $trangThai, $id);

    if ($stmt->execute()) {
        $_SESSION['thongbao'] = [
            'type' => 'success',
            'title' => 'Cập nhật thành công',
            'message' => "Đã cập nhật trạng thái đơn hàng DH$id thành công!"
        ];
    } else {
        $_SESSION['thongbao'] = [
            'type' => 'error',
            'title' => 'Lỗi cập nhật',
            'message' => "Lỗi: " . $stmt->error
        ];
    }

    $stmt->close();
} else {
    $_SESSION['thongbao'] = [
        'type' => 'error',
        'title' => 'Yêu cầu không hợp lệ',
        'message' => 'Thiếu thông tin cần thiết để cập nhật trạng thái.'
    ];
}

header("Location: ../quanlydonhang.php");
exit();
