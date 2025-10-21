<?php
require_once __DIR__ . '/../../../connect.php';

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(["error" => "Thiếu ID đơn hàng"]);
    exit;
}

$idDonHang = (int)$_GET['id'];
$data = [
    'items' => [],
    'discount_code' => null,
    'discount_value' => 0
];

$sql = "SELECT sp.tenSanPham, ct.giaMua, ct.soLuong
        FROM chitietdonhang ct
        JOIN sanpham sp ON ct.idSanPham = sp.idSanPham
        WHERE ct.idDonHang = ?";
$stmt = mysqli_prepare($link, $sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(["error" => "Truy vấn chi tiết đơn hàng thất bại"]);
    exit;
}

mysqli_stmt_bind_param($stmt, "i", $idDonHang);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {
    $data['items'][] = $row;
}
$sqlVoucher = "SELECT d.discount_code, d.discount_value
               FROM donhang d
               WHERE d.idDonHang = ?";
$stmtVoucher = mysqli_prepare($link, $sqlVoucher);

if ($stmtVoucher) {
    mysqli_stmt_bind_param($stmtVoucher, "i", $idDonHang);
    mysqli_stmt_execute($stmtVoucher);
    $resVoucher = mysqli_stmt_get_result($stmtVoucher);
    if ($rowVoucher = mysqli_fetch_assoc($resVoucher)) {
        $data['discount_code'] = $rowVoucher['discount_code'];
        $data['discount_value'] = (float)$rowVoucher['discount_value'];
    }
}

header('Content-Type: application/json');
echo json_encode($data);
?>
