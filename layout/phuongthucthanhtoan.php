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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Phương thức thanh toán | 5AE WebShop</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <style>
    .payment-box {
      max-width: 600px;
      margin: 30px auto;
      padding: 25px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      font-size: 1.1rem;
    }
    .payment-box h2 {
      margin-bottom: 20px;
      color: var(--left-menu-color);
      font-weight: bold;
      text-align: center;
    }
    .payment-box label {
      display: block;
      margin: 15px 0;
      cursor: pointer;
    }
    .payment-box input[type="radio"] {
      margin-right: 10px;
    }
    .payment-box button {
      margin-top: 20px;
      padding: 10px 20px;
      font-size: 1rem;
      border: none;
      background-color: var(--left-menu-color);
      color: white;
      border-radius: 6px;
      cursor: pointer;
      width: 100%;
    }
    .payment-box button:hover {
      background-color: rgb(9, 0, 110);
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
      <div class="payment-box">
        <h2>Chọn phương thức thanh toán</h2>
        <form action="payment/xuly_thanhtoan.php" method="post">
          <label><input type="radio" name="method" value="cod" checked> Thanh toán khi nhận hàng (COD)</label>
          <label><input type="radio" name="method" value="momo"> Thanh toán qua Ví MoMo</label>
          <button type="submit">Tiếp tục</button>
        </form>
      </div>
    </div>
    <?php include("include/footer.php"); ?>
  </div>
</body>
</html>
