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
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Quản lý giảm giá | 5AE WebShop</title>

  <!-- Đường dẫn đúng vì file đang ở layoutmanager -->
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/manager.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
</head>

<body>
<?php include("include/left-menu.php"); 
include("../connect.php");
?>

<div id="main">
  <?php include("include/navbar.php"); ?>
  <div id="main-content">
    <h3><i class="fa-solid fa-tag"></i> Quản lý giảm giá sản phẩm</h3>

    <table id="bangGiamGia">
      <thead>
        <tr>
          <th>Tên sản phẩm</th>
          <th>Giá gốc</th>
          <th>Giảm (%)</th>
          <th>Bắt đầu</th>
          <th>Kết thúc</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $result = $link->query("SELECT sp.*, d.phantram, d.time_Start, d.time_End 
                                FROM sanpham sp 
                                LEFT JOIN discount d ON sp.idSanPham = d.idSanPham");
        while ($row = $result->fetch_assoc()): ?>
          <tr>
            <form method="POST" action="xuly/xulygiamgia.php">
              <td><?= htmlspecialchars($row['tenSanPham']) ?></td>
              <td><?= number_format($row['gia']) ?>₫</td>
              <td>
                <input type="hidden" name="idSanPham" value="<?= $row['idSanPham'] ?>">
                <input type="number" name="phantram" value="<?= $row['phantram'] ?? 0 ?>" min="0" max="100" />
              </td>
              <td>
                <input type="datetime-local" name="time_Start"
                       value="<?= isset($row['time_Start']) ? date('Y-m-d\TH:i', strtotime($row['time_Start'])) : '' ?>">
              </td>
              <td>
                <input type="datetime-local" name="time_End"
                       value="<?= isset($row['time_End']) ? date('Y-m-d\TH:i', strtotime($row['time_End'])) : '' ?>">
              </td>
              <td><button type="submit">Lưu</button></td>
            </form>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
  <?php include("include/footer.php"); ?>
</div>

<!-- Hiển thị thông báo nếu có -->
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
