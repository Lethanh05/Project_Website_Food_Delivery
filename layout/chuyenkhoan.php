<?php
session_start();
if (!isset($_SESSION['user']) || empty($_SESSION['cart'])) {
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Thông tin chuyển khoản | 5AE WebShop</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <style>
    .info-box {
      max-width: 600px;
      margin: 40px auto;
      background: #fff;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      font-size: 1.1rem;
      font-family: 'Roboto', sans-serif;
    }
    .info-box h2 {
      margin-bottom: 20px;
      text-align: center;
      color: #2c3e50;
    }
    .info-box p {
      margin-bottom: 12px;
      line-height: 1.6;
    }
    .back-btn {
      display: inline-block;
      margin-top: 25px;
      padding: 10px 16px;
      background-color: var(--left-menu-color);
      color: white;
      text-decoration: none;
      border-radius: 6px;
    }
    .back-btn:hover {
      background-color: #2d3436;
    }
  </style>
</head>
<body>
  <?php 
    include("../connect.php");
    include("include/left-menu.php");
  ?>
  <div id="main">
    <?php include("include/navbar.php"); ?>
    <div id="main-content">
      <div class="info-box">
        <h2>Thông tin chuyển khoản ngân hàng</h2>
        <p>🔢 <strong>Số tài khoản:</strong> 123456789</p>
        <p>🏦 <strong>Ngân hàng:</strong> Vietcombank - Chi nhánh Bình Định</p>
        <p>👤 <strong>Chủ tài khoản:</strong> Nguyễn Anh Huy</p>
        <p>📝 <strong>Nội dung chuyển khoản:</strong> Thanh toan don hang <strong><?php echo $_SESSION['user']['username']; ?></strong></p>
        <p>💡 Sau khi chuyển khoản thành công, vui lòng giữ lại hóa đơn và chúng tôi sẽ liên hệ xác nhận đơn hàng.</p>

        <a href="giohang.php" class="back-btn"><i class="fas fa-arrow-left"></i> Quay lại giỏ hàng</a>
      </div>
    </div>
    <?php include("include/footer.php"); ?>
  </div>
</body>
</html>
