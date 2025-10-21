<?php
session_start();
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['Admin', 'Manager'])) {
  $_SESSION['thongbao'] = [
      'type' => 'error',
      'title' => 'Truy cập bị từ chối',
      'message' => 'Bạn không có quyền truy cập vào trang này.'
  ];
  header("Location: ../login.php");
  exit();
}
?>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quản lý đơn hàng | 5AE WebShop</title>
    <link rel="stylesheet" href="../assets/css/manager.css" />
    <link rel="stylesheet" href="../assets/css/style.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    <?php 
      include("../connect.php");
      include("include/left-menu.php");
    ?>
    <div id="main">
      <?php include("include/navbar.php"); ?>
      <div id="main-content">
        <h3><i class="fa-solid fa-receipt"></i> Danh sách đơn hàng</h3>

        <table id="bangDonHang">
          <thead>
            <tr>
              <th>Mã đơn hàng</th>
              <th>Sản phẩm</th>
              <th>Địa chỉ giao hàng</th>
              <th>Ngày đặt</th>
              <th>Tên khách hàng</th>
              <th>Trạng thái</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody id="order-table">
            <?php
              $sql = "SELECT 
                          dh.idDonHang,
                          dh.ngayDat,
                          dh.trangThai,
                          u.fullName AS tenKhachHang,
                          gh.address AS diaChiGiaoHang,
                          GROUP_CONCAT(CONCAT(sp.tenSanPham, ' (x', ctdh.soLuong, ')') SEPARATOR '<br>') AS sanPhamSoLuong
                      FROM donhang dh
                      JOIN chitietdonhang ctdh ON dh.idDonHang = ctdh.idDonHang
                      JOIN sanpham sp ON ctdh.idSanPham = sp.idSanPham
                      JOIN user u ON dh.idUser = u.idUser
                      LEFT JOIN giaohang gh ON dh.idDonHang = gh.idDonHang
                      GROUP BY dh.idDonHang
                      ORDER BY dh.ngayDat DESC";
  
    

              $result = $link->query($sql);
              while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>DH" . $row['idDonHang'] . "</td>";
                echo "<td>" . $row['sanPhamSoLuong'] . "</td>";
                echo "<td>" . (!empty($row['diaChiGiaoHang']) ? htmlspecialchars($row['diaChiGiaoHang']) : "<em>Chưa có</em>") . "</td>";
                echo "<td>" . $row['ngayDat'] . "</td>";
                echo "<td>" . $row['tenKhachHang'] . "</td>";
                echo "<td>" . $row['trangThai'] . "</td>";
                echo "<td>
                        <form method='post' action='xuly/capnhattrangthai.php' style='display:inline'>
                          <input type='hidden' name='idDonHang' value='" . $row['idDonHang'] . "'>
                          <select name='trangThai' onchange='this.form.submit()' class='status-dropdown'>
                            <option" . ($row['trangThai'] == 'Chờ Xác Nhận' ? " selected" : "") . ">Chờ Xác Nhận</option>
                            <option" . ($row['trangThai'] == 'Đã Xác Nhận' ? " selected" : "") . ">Đã Xác Nhận</option>
                            <option" . ($row['trangThai'] == 'Đang Giao Hàng' ? " selected" : "") . ">Đang Giao Hàng</option>
                            <option" . ($row['trangThai'] == 'Giao Hàng Thành Công' ? " selected" : "") . ">Giao Hàng Thành Công</option>
                            <option" . ($row['trangThai'] == 'Đã Hủy' ? " selected" : "") . ">Đã Hủy</option>
                          </select>
                        </form>
                      </td>";
                echo "</tr>";
              }
            ?>
          </tbody>
        </table>
      </div>
      <?php include("include/footer.php"); ?>
    </div>

    <!-- Hiển thị thông báo SweetAlert -->
    <?php if (isset($_SESSION['thongbao'])): ?>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script>
        Swal.fire({
          icon: '<?= $_SESSION['thongbao']['type'] ?>',
          title: '<?= $_SESSION['thongbao']['title'] ?>',
          text: '<?= $_SESSION['thongbao']['message'] ?>'
        });
      </script>
      <?php unset($_SESSION['thongbao']); ?>
    <?php endif; ?>
  </body>
</html>
