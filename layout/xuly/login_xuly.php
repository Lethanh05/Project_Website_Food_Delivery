<?php
session_start();
include("../../connect.php");

// Kiểm tra dữ liệu đầu vào
if (empty($_POST['username']) || empty($_POST['password'])) {
    $_SESSION['thongbao'] = [
        'type' => 'error',
        'title' => 'Thiếu thông tin!',
        'message' => 'Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.'
    ];
    header("Location: ../../login.php");
    exit();
}

$username = trim($_POST['username']); // trim loại bỏ khoảng trắng ở đầu và cuối chuỗi
$password = $_POST['password'];

// Truy vấn theo username
$stmt = $link->prepare("SELECT * FROM user WHERE username = ?");
if (!$stmt) {
    die("Lỗi prepare: " . mysqli_error($link));
}

$stmt->bind_param("s", $username); 
$stmt->execute();
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    if (password_verify($password, $row['password'])) {
        // ✅ Đăng nhập thành công
        $_SESSION['user'] = [
            'idUser' => $row['idUser'],
            'username' => $row['username'],
            'fullName'=> $row['fullName'],
            'role' => $row['role'],
            'email' => $row['email'],
            'phone' => $row['phone'],
            'avatar'=> $row['imgAvt'],
            'address'=> $row['address'],
            'created_at'=> $row['created_at'],
        ];
        if (isset($_POST['remember'])) {
            setcookie('remember', $row['username'], time() + (86400 * 30), "/"); // 30 ngày
        }
        switch ($row['role']) {
            case 'Admin':
                header("Location: ../../page/Admin/Admin.php");
                break;
            case 'Manager':
                header("Location: ../../layoutmanager/quanlysanpham.php");
                break;
            case 'User':
                header("Location: ../index.php");
                break;
        }
        exit();
    } else {
        // Sai mật khẩu
        $_SESSION['thongbao'] = [
            'type' => 'error',
            'title' => 'Sai mật khẩu!',
            'message' => 'Vui lòng kiểm tra lại mật khẩu.'
        ];
        header("Location: ../../login.php");
        exit();
    }
} else {
    // Username không tồn tại
    $_SESSION['thongbao'] = [
        'type' => 'error',
        'title' => 'Tài khoản không tồn tại!',
        'message' => 'Vui lòng kiểm tra lại tên đăng nhập.'
    ];
    header("Location: ../../login.php");
    exit();
}
?>
