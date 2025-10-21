<?php
session_start(); // ⚠️ Quan trọng: phải gọi đầu tiên

include("../../connect.php");

$hoTen = $_POST['hoTen'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$noiDung = $_POST['noiDung'];

$sql = "INSERT INTO lienhe (hoTen, email, phone, noiDung) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "ssss", $hoTen, $email, $phone, $noiDung);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['thongbao'] = [
        'type' => 'success',
        'title' => 'Thành công!',
        'message' => 'Gửi liên hệ thành công.'
    ];
} else {
    $_SESSION['thongbao'] = [
        'type' => 'error',
        'title' => 'Lỗi!',
        'message' => 'Gửi liên hệ thất bại: ' . mysqli_error($link)
    ];
}

mysqli_close($link);

// Redirect về trang chính hoặc trang form
header("Location: ../lienhe.php");
exit();
?>
