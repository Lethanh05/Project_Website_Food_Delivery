<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: ../login.php");
  exit();
}
if (
  empty($_SESSION['user']['fullName']) ||
  empty($_SESSION['user']['phone']) ||
  empty($_SESSION['user']['address'])
) {
  $_SESSION['thongbao'] = [
      'type' => 'warning',
      'title' => 'Thiếu thông tin cá nhân!',
      'message' => 'Vui lòng bổ sung họ tên, số điện thoại và địa chỉ trước khi đặt hàng.'
  ];
  header("Location: thongtincanhan.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Giỏ hàng | 5AE WebShop</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <style>
    h1 {
      text-align: center;
      margin-bottom: 24px;
      color: #333;
    }

    .container {
      padding: 20px;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th,
    td {
      border: 1px solid #ddd;
      padding: 12px;
      text-align: center;
      vertical-align: middle;
    }

    th {
      background-color: #f2f2f2;
    }

    td img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 4px;
    }

    input[type="number"] {
      width: 60px;
      padding: 5px;
      text-align: center;
    }

    .delete-btn {
      padding: 6px 10px;
      background-color: #e74c3c;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .delete-btn:hover {
      background-color: #c0392b;
    }

    .delete-btn:focus {
      outline: none;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
    }

    .cart-total {
      text-align: right;
      margin-top: 20px;
      font-size: 1.2rem;
      font-weight: bold;
    }

    .checkout-btn {
      margin-top: 20px;
      width: 100%;
      padding: 12px;
      background-color: var(--left-menu-color);
      color: white;
      border: none;
      font-size: 1rem;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .checkout-btn:hover {
      background-color: var(--left-menu-color);
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
      <div class="container">
        <h1>Giỏ hàng của bạn</h1>
        <table>
          <thead>
            <tr>
              <th>STT</th>
              <th>Tên sản phẩm</th>
              <th>Hình ảnh</th>
              <th>Giá</th>
              <th>Số lượng</th>
              <th>Tổng</th>
              <th>Xoá</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $tongTien = 0;
            $stt = 1;

            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
              foreach ($_SESSION['cart'] as $item):
                $thanhTien = $item['gia'] * $item['soluong'];
                $tongTien += $thanhTien;
            ?>
                <tr>
                  <td><?php echo $stt++; ?></td>
                  <td><?php echo htmlspecialchars($item['ten']); ?></td>
                  <td><img src="../<?php echo htmlspecialchars($item['hinhanh']); ?>" alt="<?php echo htmlspecialchars($item['ten']); ?>"></td>
                  <td><?php echo number_format($item['gia'], 0, ',', '.') . 'đ'; ?></td>
                  <td><input type="number" value="<?php echo $item['soluong']; ?>" min="1" readonly /></td>
                  <td><?php echo number_format($thanhTien, 0, ',', '.') . 'đ'; ?></td>
                  <td><button class="delete-btn" onclick="window.location.href='xuly/xoa_giohang.php?id=<?php echo $item['id']; ?>'">Xoá</button></td>
                </tr>
              <?php
              endforeach;
            } else {
              ?>
              <tr>
                <td colspan="7">Giỏ hàng của bạn đang trống.</td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
        <div class="cart-total" style="display: flex; justify-content: space-between; align-items: flex-start; margin-top: 10px;">
          <form method="post" action="xuly/giohang_discount.php" style="display: flex; max-width: 320px; height: 40px;">
            
            <input type="text" name="discount_code" placeholder="Nhập mã giảm giá"
              style="flex: 1; padding: 0 12px; border: 1px solid #ccc;
                border-top-left-radius: 6px; border-bottom-left-radius: 6px;
                font-size: 1rem; height: 100%;">
            <button type="submit" name="apply_discount"
              style="padding: 0 16px; background-color: #2F2FA2; color: white; border: none;
                 border-top-right-radius: 6px; border-bottom-right-radius: 6px;
                 font-size: 1rem; height: 100%; cursor: pointer;">
              Sử dụng
            </button>
          </form>
          <div style="text-align: right; padding: 16px 0; flex: 1;">
            <?php
            $tongGoc = $tongTien;
            if (isset($_SESSION['discount_applied'])) {
              $tongTien -= $_SESSION['discount_applied']['value'];
              if ($tongTien < 0) $tongTien = 0;
            }
            ?>
            <div style="font-size: 1rem; margin-bottom: 8px;">
              <span style="color: #555;">Tổng tiền:</span>
              <strong style="color: #222;"><?= number_format($tongGoc, 0, ',', '.') ?>đ</strong>
            </div>
            <?php if (isset($_SESSION['discount_applied'])): ?>
              <div style="font-size: 0.95rem; color: green; margin-bottom: 8px;">
                <i class="fas fa-tag"></i> Đã áp dụng mã <strong><?= $_SESSION['discount_applied']['code'] ?></strong>:
                -<?= number_format($_SESSION['discount_applied']['value'], 0, ',', '.') ?>đ
              </div>
              <div style="font-size: 1.1rem; font-weight: bold;">
                <span style="color: #000;">Thành tiền còn lại:</span>
                <span style="color: #d62828;"><?= number_format($tongTien, 0, ',', '.') ?>đ</span>
              </div>
            <?php endif; ?>
            <form method="post" action="phuongthucthanhtoan.php" style="margin-top: 12px;">
              <input type="hidden" name="tongTien" value="<?= $tongTien ?>">
              <button class="checkout-btn" type="submit" name="dathang" style="width: auto;" onclick="openDeliveryForm()">Tiến hành thanh toán</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php include("include/footer.php"); ?>
  </div>


  <?php if (isset($_SESSION['thongbao'])): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      Swal.fire({
        icon: '<?= $_SESSION['thongbao']['type'] ?>',
        title: '<?= $_SESSION['thongbao']['title'] ?>',
        text: '<?= $_SESSION['thongbao']['message'] ?>',
        confirmButtonColor: '#2F2FA2'
      });
    </script>
    <?php unset($_SESSION['thongbao']); ?>
  <?php endif; ?>

</body>
<script>
  $(document).ready(function() {
    $(".submenu").hide();
    $(".menu1").click(function(e) {
      e.preventDefault();
      const submenu = $(this).siblings(".submenu");
      submenu.slideToggle();
      $(this).find(".caret-icon").toggleClass("rotate");
    });
  });
</script>

</html>