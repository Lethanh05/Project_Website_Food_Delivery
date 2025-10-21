<?php
include("../../connect.php"); // kết nối database
session_start();

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm = $_POST['confirm_password'];

// 🔒 Kiểm tra password nhập lại
if ($password !== $confirm) {
    $_SESSION['thongbao'] = [
        'type' => 'error',
        'title' => 'Lỗi!',
        'message' => 'Mật khẩu không khớp, vui lòng nhập lại.'
    ];
    header("Location: ../../signup.php");
    exit();
}

// Băm mật khẩu
$hashedPassword = password_hash($password, PASSWORD_DEFAULT); 

// Kiểm tra username đã tồn tại chưa
$sql_check = "SELECT * FROM user WHERE username = ?";
$stmt_check = mysqli_prepare($link, $sql_check);
mysqli_stmt_bind_param($stmt_check, "s", $username);
mysqli_stmt_execute($stmt_check);
$result = mysqli_stmt_get_result($stmt_check);

if (mysqli_num_rows($result) > 0) {
    $_SESSION['thongbao'] = [
        'type' => 'error',
        'title' => 'Lỗi!',
        'message' => 'Tên người dùng đã tồn tại.'
    ];
    header("Location: ../../signup.php");
    exit();
}

// Thêm tài khoản mới
$sql_insert = "INSERT INTO user (username, email, password, created_at) VALUES (?, ?, ?, NOW())";
$stmt_insert = mysqli_prepare($link, $sql_insert);
mysqli_stmt_bind_param($stmt_insert, "sss", $username, $email, $hashedPassword);

if (mysqli_stmt_execute($stmt_insert)) {
    $_SESSION['thongbao'] = [
        'type' => 'success',
        'title' => 'Thành công!',
        'message' => 'Đăng ký tài khoản thành công. Mời bạn đăng nhập.'
    ];
    header("Location: ../../login.php");
    exit();
} else {
    echo "Lỗi: " . mysqli_error($link);
}
?>
