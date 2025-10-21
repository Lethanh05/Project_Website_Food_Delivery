<?php
session_start();
include('../../connect.php');

// Xử lý cập nhật hoặc thêm mới giảm giá
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idSanPham = $_POST['idSanPham'];
    $phantram = intval($_POST['phantram']);
    $start = $_POST['time_Start'];
    $end = $_POST['time_End'];

    // Kiểm tra dữ liệu đầu vào
    if ($phantram < 0 || $phantram > 100) {
        $_SESSION['thongbao'] = [
            'type' => 'error',
            'title' => 'Dữ liệu không hợp lệ',
            'message' => 'Phần trăm giảm giá phải từ 0 đến 100.'
        ];
        header("Location: ../quanlygiamgia.php");
        exit;
    }

    if (strtotime($start) > strtotime($end)) {
        $_SESSION['thongbao'] = [
            'type' => 'error',
            'title' => 'Ngày không hợp lệ',
            'message' => 'Ngày bắt đầu phải nhỏ hơn hoặc bằng ngày kết thúc.'
        ];
        header("Location: ../quanlygiamgia.php");
        exit;
    }

    // Kiểm tra sản phẩm đã có giảm giá chưa
    $check = $link->prepare("SELECT idDiscount FROM discount WHERE idSanPham = ?");
    $check->bind_param("i", $idSanPham);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $update = $link->prepare("UPDATE discount SET phantram = ?, time_Start = ?, time_End = ? WHERE idSanPham = ?");
        $update->bind_param("issi", $phantram, $start, $end, $idSanPham);
        $update->execute();
        $update->close();
        $action = "Cập nhật";
    } else {
        $insert = $link->prepare("INSERT INTO discount (idSanPham, phantram, time_Start, time_End) VALUES (?, ?, ?, ?)");
        $insert->bind_param("iiss", $idSanPham, $phantram, $start, $end);
        $insert->execute();
        $insert->close();
        $action = "Thêm mới";
    }

    $check->close();

    $_SESSION['thongbao'] = [
        'type' => 'success',
        'title' => "$action giảm giá",
        'message' => "$action giảm giá thành công cho sản phẩm ID $idSanPham."
    ];
    header("Location: ../quanlygiamgia.php");
    exit;
}

// Lấy danh sách sản phẩm và giảm giá để hiển thị
$query = "SELECT sp.idSanPham, sp.tenSanPham, sp.gia, d.phantram, d.time_Start, d.time_End
          FROM sanpham sp
          LEFT JOIN discount d ON sp.idSanPham = d.idSanPham";
$result = $link->query($query);
