<?php
session_start();
include("../connect.php");

if (!isset($_GET['idDonHang']) || !isset($_SESSION['user']['idUser'])) {
  header("Location: lichsudathang.php");
  exit;
}

$idDonHang = intval($_GET['idDonHang']);
$idUser = $_SESSION['user']['idUser'];

// Kiểm tra đơn hàng có thuộc user không
$sql_check = "SELECT * FROM donhang WHERE idDonHang = $idDonHang AND idUser = $idUser";
$result_check = mysqli_query($link, $sql_check);

if (mysqli_num_rows($result_check) === 0) {
  echo "Không tìm thấy đơn hàng hoặc bạn không có quyền truy cập.";
  exit;
}

$donHang = mysqli_fetch_assoc($result_check);

// Lấy thông tin giao hàng
$sql_gh = "SELECT * FROM giaohang WHERE idDonHang = $idDonHang";
$result_gh = mysqli_query($link, $sql_gh);
$giaoHang = mysqli_fetch_assoc($result_gh);

// Lấy chi tiết sản phẩm
$sql_ct = "
  SELECT s.tenSanPham, c.soLuong, c.giaMua
  FROM chitietdonhang c
  JOIN sanpham s ON c.idSanPham = s.idSanPham
  WHERE c.idDonHang = $idDonHang
";
$result_ct = mysqli_query($link, $sql_ct);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Chi tiết đơn hàng | 5AE WebShop</title>
  <link rel="stylesheet" href="../assets/css/style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"/>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet"/>
  <style>
    h3 {
      margin-top: 10px;
      text-align: center;
      font-weight: bold;
      padding: 15px;
      background-color: var(--left-menu-color);
      color: white;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      margin-bottom: 20px;
    }
    #lsdh {
      width: 100%;
      background-color: white;
      border-spacing: 0 5px;
    }
    #lsdh thead tr {
      background-color: var(--left-menu-color);
      color: white;
      font-weight: bold;
    }
    #lsdh th, #lsdh td {
      padding: 10px 12px;
      text-align: center;
    }
    #lsdh tbody tr {
      background-color: #ffffff;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      border-radius: 8px;
    }
    #lsdh tbody tr:hover {
      background-color: #f0f0ff;
    }
    .back-btn {
      display: inline-block;
      margin: 20px 0;
      padding: 10px 16px;
      background-color: #636e72;
      color: white;
      text-decoration: none;
      border-radius: 6px;
    }
    .back-btn:hover {
      background-color: #2d3436;
    }
    .total {
      text-align: right;
      font-weight: bold;
      font-size: 18px;
      margin-top: 20px;
    }
  </style>
</head>
<body>
<?php include("include/left-menu.php"); ?>
<div id="main">
  <?php include("include/navbar.php"); ?>
  <div id="main-content">
    <h3>CHI TIẾT ĐƠN HÀNG #<?= $idDonHang ?></h3>
    <p><strong>Ngày đặt:</strong> <?= date("d-m-Y H:i", strtotime($donHang['ngayDat'])) ?></p>
    <p><strong>Trạng thái:</strong> <?= htmlspecialchars($donHang['trangThai']) ?></p>

    <?php if ($giaoHang): ?>
      <p><strong>Người nhận:</strong> <?= htmlspecialchars($giaoHang['fullName']) ?></p>
      <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($giaoHang['phone']) ?></p>
      <p><strong>Địa chỉ giao hàng:</strong> <?= htmlspecialchars($giaoHang['address']) ?></p>
    <?php endif; ?>

    <table id="lsdh">
      <thead>
        <tr>
          <th>STT</th>
          <th>Tên sản phẩm</th>
          <th>Số lượng</th>
          <th>Đơn giá</th>
          <th>Thành tiền</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $i = 1;
        $tongTien = 0;
        while ($row = mysqli_fetch_assoc($result_ct)) {
          $thanhTien = $row['giaMua'] * $row['soLuong'];
          $tongTien += $thanhTien;
          echo "<tr>";
          echo "<td>" . $i++ . "</td>";
          echo "<td>" . htmlspecialchars($row['tenSanPham']) . "</td>";
          echo "<td>" . $row['soLuong'] . "</td>";
          echo "<td>" . number_format($row['giaMua'], 0, ',', '.') . "đ</td>";
          echo "<td>" . number_format($thanhTien, 0, ',', '.') . "đ</td>";
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>

    <?php if ($donHang['discount_value'] > 0): ?>
      <p class="total">
        Mã giảm giá: <strong><?= htmlspecialchars($donHang['discount_code']) ?></strong> <br>
        Giảm: <span style="color: green;">-<?= number_format($donHang['discount_value'], 0, ',', '.') ?>đ</span>
      </p>
      <p class="total">
        Thành tiền sau giảm: <span style="color: #e74c3c;"><?= number_format(max(0, $tongTien - $donHang['discount_value']), 0, ',', '.') ?>đ</span>
      </p>
    <?php else: ?>
      <p class="total">
        Tổng tiền đơn hàng: <span style="color: #e74c3c;"><?= number_format($tongTien, 0, ',', '.') ?>đ</span>
      </p>
    <?php endif; ?>

    <a class="back-btn" href="lichsudathang.php">← Quay lại</a>
  </div>
  <?php include("include/footer.php"); ?>
</div>
</body>
</html>
