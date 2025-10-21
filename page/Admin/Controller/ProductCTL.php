<?php
require_once __DIR__ . '/../../../connect.php';

$action = $_REQUEST['action'] ?? '';

if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenSanPham = $_POST['tenSanPham'] ?? '';
    $loaiSanPham = $_POST['loaiSanPham'] ?? '';
    $gia = $_POST['gia'] ?? 0;
    $tonKho = $_POST['tonKho'];
    $hinhanh = '';
    if (isset($_FILES['hinhanh']) && $_FILES['hinhanh']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../../assets/img/upload/DoAn/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = pathinfo($_FILES['hinhanh']['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . uniqid() . '.' . $ext;
        $targetFile = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['hinhanh']['tmp_name'], $targetFile)) {
            $hinhanh = 'assets/img/upload/DoAn/' . $filename;
        }
    }
    $stmt = mysqli_prepare($link, "INSERT INTO sanpham (tenSanPham, loaiSanPham, gia, tonKho, hinhanh) VALUES (?, ?, ?, ?, ?)");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ssdss', $tenSanPham, $loaiSanPham, $gia, $tonKho, $hinhanh);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    header('Location: ../MnProduct.php');
    exit;
}

if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $idSanPham = $_POST['idSanPham'] ?? 0;
    $tenSanPham = $_POST['tenSanPham'] ?? '';
    $loaiSanPham = $_POST['loaiSanPham'] ?? '';
    $gia = $_POST['gia'] ?? 0;
    $tonKho = $_POST['tonKho'] ?? 0;
    $hinhanh = '';

    if (isset($_FILES['hinhanh']) && $_FILES['hinhanh']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../../assets/img/upload/DoAn/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = pathinfo($_FILES['hinhanh']['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . uniqid() . '.' . $ext;
        $targetFile = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['hinhanh']['tmp_name'], $targetFile)) {
            $hinhanh = 'assets/img/upload/DoAn/' . $filename;
        }
    }
    if (!empty($hinhanh)) {
        $sql = "UPDATE sanpham SET tenSanPham=?, loaiSanPham=?, gia=?, tonKho=?, hinhanh=? WHERE idSanPham=?";

        $stmt = mysqli_prepare($link, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ssdsii', $tenSanPham, $loaiSanPham, $gia, $tonKho, $hinhanh, $idSanPham);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    } else {
        $sql = "UPDATE sanpham SET tenSanPham=?, loaiSanPham=?, gia=?, tonKho=? WHERE idSanPham=?";
        $stmt = mysqli_prepare($link, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ssdii', $tenSanPham, $loaiSanPham, $gia, $tonKho, $idSanPham);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    header('Location: ../MnProduct.php');
    exit;
}

if ($action === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = mysqli_prepare($link, "DELETE FROM sanpham WHERE idSanPham = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    header('Location: ../MnProduct.php');
    exit;
}
if (isset($_GET['status']) && isset($_GET['msg'])) {
    echo "<script>window.addEventListener('DOMContentLoaded', () => {
      showToast('" . htmlspecialchars($_GET['msg']) . "', '" . ($_GET['status'] !== 'success' ? 'true' : 'false') . "');
    });</script>";
}