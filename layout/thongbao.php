<?php
session_start();
if (!isset($_SESSION['thongbao'])) {
    header("Location: index.php");
    exit;
}
$tb = $_SESSION['thongbao'];
unset($_SESSION['thongbao']); // Xóa sau khi hiển thị 1 lần
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Thông báo</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<script>
  Swal.fire({
    icon: '<?= $tb['type'] ?>',     // 'success' | 'error' | 'warning' | 'info'
    title: '<?= $tb['title'] ?>',
    text: '<?= $tb['message'] ?>',
    confirmButtonText: 'OK'
  }).then(() => {
    window.location.href = "lichsudathang.php";
  });
</script>
</body>
</html>
