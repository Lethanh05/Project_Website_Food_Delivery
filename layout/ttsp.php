<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Thông tin sản phẩm | 5AE WebShop</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <style>
    .product-container {
      max-width: 960px;
      margin: 40px auto;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      display: flex;
      flex-wrap: wrap;
      overflow: hidden;
      font-family: 'Roboto', sans-serif;
    }

    .product-image {
      flex: 1 1 40%;
      background: #f9f9f9;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .image-wrapper {
      position: relative;
      display: inline-block;
    }

    .discount-badge {
      position: absolute;
      top: 10px;
      left: 10px;
      background-color: red;
      color: white;
      font-size: 14px;
      padding: 4px 8px;
      border-radius: 6px;
      font-weight: bold;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
      z-index: 2;
    }

    .product-image img {
      max-width: 100%;
      max-height: 350px;
      object-fit: contain;
      border-radius: 8px;
    }

    .product-details {
      flex: 1 1 60%;
      padding: 30px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .product-details h2 {
      margin-bottom: 10px;
      font-size: 1.8rem;
      color: #333;
    }

    .product-details .price {
      font-size: 1.4rem;
      margin-bottom: 10px;
    }

    .product-details label {
      margin-top: 20px;
      font-weight: 500;
    }

    .product-details input[type="number"] {
      width: 80px;
      padding: 8px;
      margin-top: 8px;
      font-size: 1rem;
      text-align: center;
    }

    .total-price {
      margin-top: 16px;
      font-size: 1.1rem;
      font-weight: bold;
      color: #2e7d32;
    }

    .btn-submit {
      margin-top: 20px;
      padding: 12px 24px;
      background-color: var(--left-menu-color);
      border: none;
      color: #fff;
      font-size: 1rem;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .btn-submit:hover {
      background-color: #0056b3;
    }

    .back-link {
      display: inline-block;
      margin-bottom: 25px;
      color: var(--left-menu-color);
      text-decoration: none;
      font-size: 0.95rem;
    }

    .back-link i {
      margin-right: 4px;
    }

    @media (max-width: 768px) {
      .product-container {
        flex-direction: column;
      }
      .product-image, .product-details {
        flex: 1 1 100%;
      }
    }
  </style>  
</head>
<body>
<?php 
  include("../connect.php");
  include("include/left-menu.php");

  $sp = null;
  $phanTramGiam = 0;
  $giaSauGiam = 0;

  if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "
      SELECT s.*, d.phanTram, d.time_Start, d.time_End
      FROM sanpham s
      LEFT JOIN discount d ON s.idSanPham = d.idSanPham 
        AND d.time_Start <= NOW() 
        AND d.time_End >= NOW()
      WHERE s.idSanPham = $id
      ORDER BY d.time_End DESC LIMIT 1
    ";
    $result = mysqli_query($link, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
      $sp = mysqli_fetch_assoc($result);
      if (!empty($sp['phanTram'])) {
        $phanTramGiam = (int)$sp['phanTram'];
        $giaSauGiam = $sp['gia'] * (1 - $phanTramGiam / 100);
      } else {
        $giaSauGiam = $sp['gia'];
      }
    }
  }
?>
<div id="main">
  <?php include("include/navbar.php"); ?>
  <div id="main-content">
  <?php if ($sp): ?>
  <div class="product-container">
    <div class="product-image">
      <div class="image-wrapper">
        <?php if ($phanTramGiam > 0): ?>
          <div class="discount-badge">-<?php echo $phanTramGiam; ?>%</div>
        <?php endif; ?>
        <img src="../<?php echo htmlspecialchars($sp['hinhanh']); ?>" alt="<?php echo htmlspecialchars($sp['tenSanPham']); ?>">
      </div>
    </div>
    <div class="product-details">
      <a href="javascript:history.back()" class="back-link"><i class="fas fa-arrow-left"></i> Quay lại</a>
      <h2><?php echo htmlspecialchars($sp['tenSanPham']); ?></h2>
      <div class="price">
        <?php if ($phanTramGiam > 0): ?>
          <span style="color: #999; text-decoration: line-through; font-size: 1rem;">
            <?php echo number_format($sp['gia'], 0, ',', '.') ?>₫
          </span>
          <span style="color: #e53935; font-weight: bold; font-size: 1.4rem; margin-left: 10px;">
            <?php echo number_format($giaSauGiam, 0, ',', '.') ?>₫
          </span>
        <?php else: ?>
          <span style="color: #e53935; font-weight: bold; font-size: 1.4rem;">
            <?php echo number_format($sp['gia'], 0, ',', '.') ?>₫
          </span>
        <?php endif; ?>
      </div>

      <form method="post" action="xuly/them_giohang.php">
        <input type="hidden" name="idSanPham" value="<?php echo $sp['idSanPham']; ?>">
        <input type="hidden" name="tenSanPham" value="<?php echo htmlspecialchars($sp['tenSanPham']); ?>">
        <?php if (!is_null($sp['tonKho'])): ?>
          <p style="margin-bottom: 10px; color: #555;">
            <strong>Tồn kho:</strong> <?php echo $sp['tonKho']; ?> sản phẩm
          </p>
        <?php endif; ?>
        <input type="hidden" name="gia" id="unit-price" value="<?php echo $giaSauGiam; ?>">
        <input type="hidden" name="hinhanh" value="<?php echo htmlspecialchars($sp['hinhanh']); ?>">

        <label for="quantity">Số lượng:</label><br>
        <input type="number" name="soluong" id="quantity" value="1" min="1" required>

        <div class="total-price" id="total-display">
          Thành tiền: <?php echo number_format($giaSauGiam, 0, ',', '.') ?>₫
        </div>

        <button type="submit" class="btn-submit" id="submit-btn">
          Thêm vào giỏ hàng
        </button>
      </form>
    </div>
  </div>
  <?php else: ?>
    <p style="text-align: center; color: red; padding: 20px;">Sản phẩm không tồn tại hoặc không hợp lệ.</p>
  <?php endif; ?>
  </div>
  <?php include("include/footer.php"); ?>
</div>

<script>
  $(document).ready(function () {
        // Menu toggle
        $(".submenu").hide();
        $(".menu1").click(function (e) {
          e.preventDefault();
          const submenu = $(this).siblings(".submenu");
          submenu.slideToggle();
          $(this).find(".caret-icon").toggleClass("rotate");
        });
      });
  const price = parseFloat(document.getElementById('unit-price').value);
  const quantityInput = document.getElementById('quantity');
  const totalDisplay = document.getElementById('total-display');
  const submitBtn = document.getElementById('submit-btn');

  function updateTotal() {
    const quantity = parseInt(quantityInput.value) || 1;
    const total = price * quantity;
    totalDisplay.textContent = 'Thành tiền: ' + total.toLocaleString('vi-VN') + '₫';
    submitBtn.textContent = 'Thêm - ' + total.toLocaleString('vi-VN') + '₫';
  }

  quantityInput.addEventListener('input', updateTotal);
</script>

<?php if (isset($_SESSION['thongbao'])): ?>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
      Swal.fire({
          icon: '<?= $_SESSION['thongbao']['type'] ?>',
          title: '<?= $_SESSION['thongbao']['title'] ?>',
          text: '<?= $_SESSION['thongbao']['message'] ?>',
          timer: 2500,
          showConfirmButton: true
      });
  </script>
  <?php unset($_SESSION['thongbao']); ?>
<?php endif; ?>
</body>
</html>
