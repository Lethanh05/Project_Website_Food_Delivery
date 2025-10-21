<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="/ltw/assets/css/admin.css">
    <script src="../../assets/js/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto&display=swap"
      rel="stylesheet"
    />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
    }
  </style>
</head>
<body>
  <?php
require_once "../../connect.php";
ob_start();

$today = date('Y-m-d');
$sp = mysqli_query($link, "SELECT COUNT(*) AS total FROM sanpham");
$dhToday = mysqli_query($link, "SELECT COUNT(*) AS total FROM donhang WHERE DATE(ngayDat) = '$today'");
$khToday = mysqli_query($link, "SELECT COUNT(*) AS total FROM user WHERE DATE(created_at) = '$today'");
$revenueToday = mysqli_query($link, "
  SELECT SUM(ct.giaMua * ct.soLuong) AS total
  FROM donhang dh
  JOIN chitietdonhang ct ON dh.idDonHang = ct.idDonHang
  WHERE DATE(dh.ngayDat) = '$today'
    AND dh.trangThai = 'Giao hàng thành công'
");


$totalSP = mysqli_fetch_assoc($sp)['total'];
$totalDH = mysqli_fetch_assoc($dhToday)['total'];
$totalKH = mysqli_fetch_assoc($khToday)['total'];
$revenue = mysqli_fetch_assoc($revenueToday)['total'] ?? 0;

$topProducts = mysqli_query($link, "
  SELECT sp.tenSanPham, SUM(ct.soLuong) AS luotban
  FROM chitietdonhang ct
  JOIN sanpham sp ON ct.idSanPham = sp.idSanPham
  JOIN donhang dh ON ct.idDonHang = dh.idDonHang
  WHERE dh.trangThai = 'Giao hàng thành công'
  GROUP BY sp.tenSanPham
  ORDER BY luotban DESC
  LIMIT 5
");

$labels = []; 
$data = [];

for ($i = 6; $i >= 0; $i--) {
  $d = date('Y-m-d', strtotime("-$i days"));
  $labels[] = date('d/m', strtotime($d));

  $query = "
    SELECT SUM(ct.giaMua * ct.soLuong) AS total
    FROM donhang dh
    JOIN chitietdonhang ct ON dh.idDonHang = ct.idDonHang
    WHERE DATE(dh.ngayDat) = '$d'
      AND dh.trangThai = 'Giao hàng thành công'
  ";

  $res = mysqli_query($link, $query);
  if (!$res) {
    error_log("Lỗi SQL: " . mysqli_error($link));
    $data[] = 0;
    continue;
  }

  $row = mysqli_fetch_assoc($res);
  $data[] = (int)($row['total'] ?? 0);
}


$recentOrders = mysqli_query($link, "
  SELECT 
    dh.idDonHang, 
    u.fullName, 
    dh.ngayDat, 
    SUM(ct.giaMua * ct.soLuong) - dh.discount_value AS total_price,
    dh.trangThai
  FROM donhang dh
  JOIN user u ON dh.idUser = u.idUser
  JOIN chitietdonhang ct ON dh.idDonHang = ct.idDonHang
  GROUP BY dh.idDonHang
  ORDER BY dh.ngayDat DESC
  LIMIT 3
");

?>

<div class="admin-dashboard">
  <div class="dashboard-wrapper">
 <div class="row mb-4 text-white">
  <div class="col-md-3">
    <div class="p-4 bg-primary rounded">
      <h5>Tổng sản phẩm</h5>
      <h3><?= $totalSP ?></h3>
    </div>
  </div>
  <div class="col-md-3">
    <div class="p-4 bg-success rounded">
      <h5>Đơn hàng hôm nay</h5>
      <h3><?= $totalDH ?></h3>
    </div>
  </div>
  <div class="col-md-3">
    <div class="p-4 bg-warning rounded">
      <h5>Khách hàng mới</h5>
      <h3><?= $totalKH ?></h3>
    </div>
  </div>
  <div class="col-md-3">
    <div class="p-4 bg-danger rounded">
      <h5>Doanh thu hôm nay</h5>
      <h3><?= number_format($revenue, 0, ',', '.') ?> VND</h3>
    </div>
  </div>
</div>


  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-header">Top sản phẩm bán chạy</div>
        <ul class="list-group">
          <?php $i = 1; while ($row = mysqli_fetch_assoc($topProducts)) { ?>
            <li class="list-group-item"><?= $i++ ?>. <?= $row['tenSanPham'] ?> - <?= $row['luotban'] ?> lượt</li>
          <?php } ?>
        </ul>
      </div>
    </div>
    <div class="col">
      <div class="card">
        <div class="card-header">Biểu đồ doanh thu 7 ngày gần nhất</div>
        <div class="card-body">
          <canvas id="revenueChart" height="150"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="card mt-4">
    <div class="card-header">Đơn hàng gần đây</div>
    <div class="card-body">
      <table class="table">
        <thead>
          <tr>
            <th>Mã đơn</th>
            <th>Khách hàng</th>
            <th>Ngày đặt</th>
            <th>Tổng tiền</th>
            <th>Trạng thái</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($recentOrders)) { ?>
            <tr>
              <td>#DH<?= str_pad($row['idDonHang'], 4, "0", STR_PAD_LEFT) ?></td>
              <td><?= $row['fullName'] ?></td>
              <td><?= date('d/m/Y', strtotime($row['ngayDat'])) ?></td>
              <td><?= number_format($row['total_price'], 0, ',', '.') ?>₫</td>
              <td><span class="badge <?= getStatusClass($row['trangThai']) ?>"><?= $row['trangThai'] ?></span></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
  </div>
</div>
<script>
  const ctx = document.getElementById('revenueChart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: <?= json_encode($labels) ?>,
      datasets: [{
        label: 'Doanh thu (VND)',
        data: <?= json_encode($data) ?>,
        fill: true,
        borderColor: 'rgb(75, 192, 192)',
        tension: 0.3
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } }
    }
  });
</script>

<?php
function getStatusClass($status) {
  return match ($status) {
    'Chờ xác nhận' => 'bg-warning',
    'Đang giao hàng' => 'bg-info',
    'Giao hàng thành công' => 'bg-success',
    'Đã hủy' => 'bg-danger',
    default => 'bg-secondary'
  };
}
$pageContent = ob_get_clean();
include __DIR__ . '/../../components/layout/layoutadmin.php';
?>   
</body>
<html>