<?php
session_start();
include("../../connect.php");

$idUser = $_SESSION['user']['idUser'];
$fullName = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];

$avatarPath = null;

// Upload ảnh nếu có
if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['avatar']['tmp_name'];
    $fileName = $_FILES['avatar']['name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($fileExtension, $allowedExtensions)) {
        $newFileName = 'avatar_' . $idUser . '_' . time() . '.' . $fileExtension;
        $uploadFileDir = '../../assets/img/uploads/';
        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $avatarPath = 'assets/img/uploads/' . $newFileName;
        }
    }
}

if ($avatarPath !== null) {
    $sql = "UPDATE user SET fullName=?, email=?, phone=?, address=?, imgAvt=? WHERE idUser=?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "sssssi", $fullName, $email, $phone, $address, $avatarPath, $idUser);
} else {
    $sql = "UPDATE user SET fullName=?, email=?, phone=?, address=? WHERE idUser=?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "ssssi", $fullName, $email, $phone, $address, $idUser);
}

mysqli_stmt_execute($stmt);

// Cập nhật session
$_SESSION['user']['fullName'] = $fullName;
$_SESSION['user']['email'] = $email;
$_SESSION['user']['phone'] = $phone;
$_SESSION['user']['address'] = $address;
if ($avatarPath !== null) {
    $_SESSION['user']['avatar'] = $avatarPath;
}

// Gửi thông báo qua session để hiển thị bằng SweetAlert
$_SESSION['thongbao'] = [
    'type' => 'success',
    'title' => 'Thành công!',
    'message' => 'Thông tin cá nhân đã được cập nhật.'
];

// Chuyển hướng
header("Location: ../thongtincanhan.php");
exit;
?>
