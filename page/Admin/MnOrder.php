
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Quản lý Đơn hàng</title>
  <link rel="stylesheet" href="/ltw/assets/css/admin.css" />
  <script src="../../assets/js/jquery-3.7.1.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
  <style>
    .order-detail-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .close-btn {
      font-size: 20px;
      font-weight: bold;
      cursor: pointer;
      padding: 5px 10px;
      color: #888;
      transition: color 0.3s;
    }

    .close-btn:hover {
      color: #e74c3c;
    }
  </style>
</head>
<?php
require_once __DIR__ . '/../../connect.php';
function convertStatusClass($status)
{
  return match ($status) {
    'Chờ xác nhận' => 'processing',
    'Đang giao hàng' => 'shipped',
    'Giao hàng thành công' => 'completed',
    'Đã hủy' => 'cancelled',
    default => 'processing'
  };
}

$sql = "SELECT 
  d.idDonHang, 
  u.fullName, 
  d.ngayDat, 
  d.discount_value,
  d.trangThai,
  gh.address AS diaChiGiaoHang,   -- Thêm dòng này
  IFNULL(SUM(ct.giaMua * ct.soLuong) - IFNULL(d.discount_value, 0), 0) AS total_price
FROM donhang d
JOIN user u ON d.idUser = u.idUser
JOIN chitietdonhang ct ON d.idDonHang = ct.idDonHang
LEFT JOIN giaohang gh ON d.idDonHang = gh.idDonHang  -- Thêm LEFT JOIN này
GROUP BY d.idDonHang, u.fullName, d.ngayDat, d.discount_value, d.trangThai, gh.address
ORDER BY d.idDonHang DESC";

$result = mysqli_query($link, $sql);

ob_start();
?>
<body>
  <div class="order-page">
    <div class="header"><h2>Quản lý đơn hàng</h2></div>

    <div class="filter-bar">
      <input type="text" id="searchBox" class="search-box" placeholder="Tìm kiếm theo mã đơn / tên khách hàng" />
      <select class="filter-select" id="statusFilter">
        <option value="all">Tất cả trạng thái</option>
        <?php foreach (['Chờ xác nhận', 'Đang giao hàng', 'Giao hàng thành công', 'Đã hủy'] as $s): ?>
          <option value="<?= strtolower(str_replace(' ', '', $s)) ?>"><?= $s ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <table class="table-order">
      <thead>
        <tr>
          <th>#</th>
          <th>Mã đơn hàng</th>
          <th>Khách hàng</th>
          <th>Địa chỉ giao hàng</th>
          <th>Ngày tạo</th>
          <th>Tổng tiền</th>
          <th>Trạng thái</th>
          <th>Chi tiết</th>
        </tr>
      </thead>
      <tbody id="orderTable">
        <?php $index = 1; while ($row = mysqli_fetch_assoc($result)): ?>
          <tr data-order-id="<?= $row['idDonHang'] ?>" data-status="<?= strtolower(str_replace(' ', '', $row['trangThai'])) ?>">
            <td><?= $index++ ?></td>
            <td>DH<?= $row['idDonHang'] ?></td>
            <td><?= htmlspecialchars($row['fullName']) ?></td>
            <td><?= !empty($row['diaChiGiaoHang']) ? htmlspecialchars($row['diaChiGiaoHang']) : '<em>Chưa cập nhật</em>' ?></td>
            <td><?= date('d/m/Y', strtotime($row['ngayDat'])) ?></td>
            <td><?= number_format($row['total_price'], 0, ',', '.') ?>₫</td>
            <td><span class="badge status-<?= convertStatusClass($row['trangThai']) ?>"><?= $row['trangThai'] ?></span></td>
            <td><button class="btn-sm btn-detail" onclick="showDetails(<?= $row['idDonHang'] ?>)">Xem</button></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <div class="order-detail-box" id="orderDetailBox" style="display:none">
      <div class="order-detail-header">
        <h3>Chi tiết đơn hàng <span id="orderIdLabel"></span></h3>
        <span class="close-btn" onclick="closeDetailBox()">×</span>
      </div>
      <table class="table-order-detail">
        <thead>
          <tr>
            <th>Sản phẩm</th>
            <th>Đơn giá</th>
            <th>Số lượng</th>
            <th>Thành tiền</th>
          </tr>
        </thead>
        <tbody id="orderDetailContent"></tbody>
      </table>
      <p class="total-label">Tổng cộng: <span id="orderTotal">0₫</span></p>
    </div>
  </div>

  <script>
    function showDetails(orderId) {
      const tbody = document.getElementById("orderDetailContent");
      const orderTotal = document.getElementById("orderTotal");
      tbody.innerHTML = "";
      orderTotal.innerText = "0₫";

      fetch('Controller/OrderDetailCTL.php?id=' + orderId)
        .then(res => res.json())
        .then(data => {
          const list = data.items;
          const code = data.discount_code;
          const discount = Number(data.discount_value || 0);
          let total = 0;

          list.forEach(item => {
            const sub = item.giaMua * item.soLuong;
            total += sub;
            tbody.innerHTML += `
              <tr>
                <td>${item.tenSanPham}</td>
                <td>${Number(item.giaMua).toLocaleString()}₫</td>
                <td>${item.soLuong}</td>
                <td>${sub.toLocaleString()}₫</td>
              </tr>`;
          });

          let htmlTotal = total.toLocaleString() + "₫";
          if (discount > 0) {
            htmlTotal += ` <br><small>(Giảm <strong>${discount.toLocaleString()}₫</strong>`;
            if (code) htmlTotal += ` bằng mã: <strong>${code}</strong>`;
            htmlTotal += `)</small>`;
            total -= discount;
          }

          document.getElementById("orderIdLabel").innerText = "ORD" + orderId;
          orderTotal.innerHTML = total.toLocaleString() + "₫ <br>" + htmlTotal;
          document.getElementById("orderDetailBox").style.display = "block";
        })
        .catch(err => {
          console.error(err);
          alert("Lỗi khi lấy chi tiết đơn hàng!");
        });
    }

    function closeDetailBox() {
      document.getElementById("orderDetailBox").style.display = "none";
    }

    document.getElementById("statusFilter").addEventListener("change", function () {
      const selected = this.value;
      document.querySelectorAll("#orderTable tr").forEach(row => {
        const status = row.dataset.status;
        row.style.display = (selected === "all" || selected === status) ? "" : "none";
      });
    });

    document.getElementById("searchBox").addEventListener("input", function () {
      const keyword = this.value.toLowerCase();
      document.querySelectorAll("#orderTable tr").forEach(row => {
        const orderId = row.children[1].textContent.toLowerCase();
        const customer = row.children[2].textContent.toLowerCase();
        row.style.display = orderId.includes(keyword) || customer.includes(keyword) ? "" : "none";
      });
    });
  </script>

  <?php
  $pageContent = ob_get_clean();
  include(__DIR__ . '/../../components/layout/layoutadmin.php');
  ?>
</body>
</html>
