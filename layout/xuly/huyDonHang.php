<?php
session_start();
include("../../connect.php");

if (isset($_GET['id']) && isset($_SESSION['user']['idUser'])) {
    $idDonHang = intval($_GET['id']);
    $idUser = $_SESSION['user']['idUser'];

    // Chỉ cập nhật trạng thái nếu đơn hàng thuộc về user và đang chờ xác nhận
    $sql = "
        UPDATE donhang 
        SET trangThai = 'Đã Hủy' 
        WHERE idDonHang = $idDonHang 
        AND idUser = $idUser 
        AND trangThai = 'Chờ Xác Nhận'
    ";
    mysqli_query($link, $sql);
}

header("Location: ../lichsudathang.php");
exit;
?>
