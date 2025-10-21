<?php
session_start(); // Bắt buộc
include('../../connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenSanPham = trim($_POST['tenSanPham'] ?? '');
    $giaTien = floatval($_POST['giaTien'] ?? 0);
    $tonKho = intval($_POST['tonKho'] ??0);
    $loaiSanPham = $_POST['loaiSanPham'] ?? '';
    $hinhAnhPath = '';

    // Kiểm tra dữ liệu
    if ($tenSanPham === '' || $giaTien <= 0 || $loaiSanPham === '') {
        $_SESSION['thongbao'] = [
            'type' => 'error',
            'title' => 'Lỗi dữ liệu',
            'message' => 'Vui lòng nhập đầy đủ thông tin hợp lệ.'
        ];
        header("Location: ../quanlysanpham.php");
        exit;
    }

    // Xử lý upload ảnh
    if (isset($_FILES['hinhAnh']) && $_FILES['hinhAnh']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../assets/img/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $tmpName = $_FILES['hinhAnh']['tmp_name'];
        $fileName = time() . '_' . preg_replace('/\s+/', '_', basename($_FILES['hinhAnh']['name']));
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($tmpName, $targetPath)) {
            $hinhAnhPath = 'assets/img/uploads/' . $fileName;
        } else {
            $_SESSION['thongbao'] = [
                'type' => 'error',
                'title' => 'Lỗi tải ảnh',
                'message' => 'Không thể tải ảnh lên.'
            ];
            header("Location: ../quanlysanpham.php");
            exit;
        }
    } else {
        $_SESSION['thongbao'] = [
            'type' => 'error',
            'title' => 'Chưa chọn ảnh',
            'message' => 'Bạn chưa chọn ảnh hợp lệ.'
        ];
        header("Location: ../quanlysanpham.php");
        exit;
    }

    // Thêm vào CSDL
    $stmt = $link->prepare("INSERT INTO sanpham (tenSanPham, gia,tonKho, hinhanh, loaiSanPham) VALUES (?, ?,?, ?, ?)");
    $stmt->bind_param("sdsss", $tenSanPham, $giaTien,$tonKho, $hinhAnhPath, $loaiSanPham);

    if ($stmt->execute()) {
        $_SESSION['thongbao'] = [
            'type' => 'success',
            'title' => 'Thành công',
            'message' => 'Đã thêm sản phẩm thành công!'
        ];
    } else {
        $_SESSION['thongbao'] = [
            'type' => 'error',
            'title' => 'Lỗi lưu',
            'message' => 'Lỗi khi lưu sản phẩm: ' . $link->error
        ];
    }

    $stmt->close();
    header("Location: ../quanlysanpham.php");
    exit;
} else {
    $_SESSION['thongbao'] = [
        'type' => 'error',
        'title' => 'Yêu cầu không hợp lệ',
        'message' => 'Phương thức gửi không hợp lệ.'
    ];
    header("Location: ../quanlysanpham.php");
    exit;
}
?>
