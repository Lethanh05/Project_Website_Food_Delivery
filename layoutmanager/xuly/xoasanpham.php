<?php
session_start();
include('../../connect.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $query = $link->prepare("DELETE FROM sanpham WHERE idSanPham = ?");
    $query->bind_param("i", $id);

    if ($query->execute()) {
        $_SESSION['thongbao'] = [
            'type' => 'success',
            'title' => 'Xóa thành công',
            'message' => 'Đã xóa sản phẩm thành công.'
        ];
    } else {
        $_SESSION['thongbao'] = [
            'type' => 'error',
            'title' => 'Lỗi xóa',
            'message' => 'Lỗi khi xóa sản phẩm: ' . $query->error
        ];
    }

    $query->close();
} else {
    $_SESSION['thongbao'] = [
        'type' => 'error',
        'title' => 'Yêu cầu không hợp lệ',
        'message' => 'Không nhận được ID sản phẩm hợp lệ.'
    ];
}

header("Location: ../quanlysanpham.php");
exit;
