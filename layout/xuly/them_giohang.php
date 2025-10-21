<?php
session_start();
include("../../connect.php");
if (!isset($_SESSION['user'])) {
    header("Location: ../../login.php");
    exit();
}

if (isset($_POST['idSanPham'], $_POST['tenSanPham'], $_POST['gia'], $_POST['hinhanh'], $_POST['soluong'])) {
    $id = intval($_POST['idSanPham']);
    $ten = $_POST['tenSanPham'];
    $gia = floatval($_POST['gia']);
    $img = $_POST['hinhanh'];
    $soluong = intval($_POST['soluong']);

    if ($soluong < 1) $soluong = 1;

    // ✅ Truy vấn tồn kho
    $query = mysqli_query($link, "SELECT tonKho FROM sanpham WHERE idSanPham = $id");

    $row = mysqli_fetch_assoc($query);
    $tonKho = intval($row['tonKho']);

    // ✅ Tính tổng số lượng sau khi thêm
    $currentInCart = isset($_SESSION['cart'][$id]) ? $_SESSION['cart'][$id]['soluong'] : 0;
    $totalQuantity = $currentInCart + $soluong;

    if ($totalQuantity > $tonKho) {
        $_SESSION['thongbao'] = [
            'type' => 'warning',
            'title' => 'Vượt quá số lượng tồn',
            'message' => "Chỉ còn $tonKho sản phẩm trong kho !"
        ];
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    // ✅ Thêm vào giỏ
    $sp = [
        'id' => $id,
        'ten' => $ten,
        'gia' => $gia,
        'hinhanh' => $img,
        'soluong' => $soluong
    ];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['soluong'] += $soluong;
    } else {
        $_SESSION['cart'][$id] = $sp;
    }

    $_SESSION['thongbao'] = [
        'type' => 'success',
        'title' => 'Đã thêm vào giỏ!',
        'message' => "Đã thêm \"{$ten}\" vào giỏ hàng."
    ];
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
