<?php
session_start(); 
include('../../connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $ten = trim($_POST['tenSanPham'] ?? '');
    $gia = floatval($_POST['giaTien'] ?? 0);
    $tonKho = intval($_POST['tonKho'] ?? 0);
    $loaiSanPham = $_POST['loaiSanPham'] ?? '';
    $newImagePath = null;

    // Kiểm tra dữ liệu
    if ($id <= 0 || $ten === '' || $gia <= 0 || $loaiSanPham === '') {
        $_SESSION['thongbao'] = [
            'type' => 'error',
            'title' => 'Lỗi dữ liệu',
            'message' => 'Dữ liệu không hợp lệ.'
        ];
        header("Location: ../quanlysanpham.php");
        exit;
    }

    // Nếu có ảnh mới
    if (isset($_FILES['hinhAnh']) && $_FILES['hinhAnh']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../assets/img/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Xóa ảnh cũ
        $oldImageQuery = $link->prepare("SELECT hinhanh FROM sanpham WHERE idSanPham = ?");
        $oldImageQuery->bind_param("i", $id);
        $oldImageQuery->execute();
        $result = $oldImageQuery->get_result();
        if ($row = $result->fetch_assoc()) {
            $oldPath = '../../' . $row['hinhanh'];
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }
        $oldImageQuery->close();

        // Lưu ảnh mới
        $tmp = $_FILES['hinhAnh']['tmp_name'];
        $filename = time() . '_' . preg_replace('/\s+/', '_', basename($_FILES['hinhAnh']['name']));
        $target = $uploadDir . $filename;

        if (move_uploaded_file($tmp, $target)) {
            $newImagePath = 'assets/img/uploads/' . $filename;
        } else {
            $_SESSION['thongbao'] = [
                'type' => 'error',
                'title' => 'Lỗi ảnh',
                'message' => 'Không thể lưu ảnh mới.'
            ];
            header("Location: ../quanlysanpham.php");
            exit;
        }
    }

    // Cập nhật DB
    if ($newImagePath !== null) {
        $stmt = $link->prepare("UPDATE sanpham SET tenSanPham=?, gia=?,tonKho=?, hinhanh=?, loaiSanPham=? WHERE idSanPham=?");
        $stmt->bind_param("sdisi", $ten, $gia,$tonKho, $newImagePath, $loaiSanPham, $id);
    } else {
        $stmt = $link->prepare("UPDATE sanpham SET tenSanPham=?, gia=?,tonKho=?, loaiSanPham=? WHERE idSanPham=?");
        $stmt->bind_param("sdisi", $ten, $gia,$tonKho, $loaiSanPham, $id);
    }

    if ($stmt->execute()) {
        $_SESSION['thongbao'] = [
            'type' => 'success',
            'title' => 'Thành công',
            'message' => 'Đã cập nhật sản phẩm thành công.'
        ];
    } else {
        $_SESSION['thongbao'] = [
            'type' => 'error',
            'title' => 'Lỗi cập nhật',
            'message' => 'Lỗi khi cập nhật: ' . $link->error
        ];
    }

    $stmt->close();
    header("Location: ../quanlysanpham.php");
    exit;
} else {
    $_SESSION['thongbao'] = [
        'type' => 'error',
        'title' => 'Lỗi yêu cầu',
        'message' => 'Yêu cầu không hợp lệ.'
    ];
    header("Location: ../quanlysanpham.php");
    exit;
}
?>