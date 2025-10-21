<?php
session_start();
include("../../connect.php");

$idUser = $_SESSION['user']['idUser'];
$oldPassword = $_POST['old_password'];
$newPassword1 = $_POST['new_password_1'];
$newPassword2 = $_POST['new_password_2'];

// Kiểm tra mật khẩu mới nhập lại
if ($newPassword1 !== $newPassword2) {
    $_SESSION['thongbao'] = [
        'type' => 'error',
        'title' => 'Không khớp!',
        'message' => 'Mật khẩu mới nhập lại không khớp.'
    ];
    header("Location: ../thongtincanhan.php");
    exit;
}

// Lấy mật khẩu cũ từ DB
$sql = "SELECT password FROM user WHERE idUser = ?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $idUser);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $hashedPassword = $row['password'];

    if (!password_verify($oldPassword, $hashedPassword)) {
        $_SESSION['thongbao'] = [
            'type' => 'error',
            'title' => 'Sai mật khẩu!',
            'message' => 'Mật khẩu cũ không chính xác.'
        ];
        header("Location: ../thongtincanhan.php");
        exit;
    }

    $newHashed = password_hash($newPassword1, PASSWORD_DEFAULT);
    $update = "UPDATE user SET password=? WHERE idUser=?";
    $stmt2 = mysqli_prepare($link, $update);
    mysqli_stmt_bind_param($stmt2, "si", $newHashed, $idUser);
    mysqli_stmt_execute($stmt2);

    $_SESSION['thongbao'] = [
        'type' => 'success',
        'title' => 'Thành công!',
        'message' => 'Mật khẩu đã được thay đổi.'
    ];
    header("Location: ../thongtincanhan.php");
    exit;
} else {
    $_SESSION['thongbao'] = [
        'type' => 'error',
        'title' => 'Không tìm thấy người dùng!',
        'message' => 'Hệ thống không xác định được tài khoản.'
    ];
    header("Location: ../thongtincanhan.php");
    exit;
}
?>
