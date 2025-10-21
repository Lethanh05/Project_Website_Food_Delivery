<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['Admin'])) {
  $_SESSION['redirect_back'] = $_SERVER['REQUEST_URI'];
  $_SESSION['thongbao'] = [
      'type' => 'error',
      'title' => 'Truy cập bị từ chối',
      'message' => 'Bạn không có quyền truy cập vào trang này.'
  ];
  header("location: ../../login.php");
  exit();
}
require_once __DIR__ . '/../../page/Admin/Controller/LoadNoti.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Page</title>
  <link rel="stylesheet" href="../../assets/css/admin/admin.css">
  <link rel="stylesheet" href="../../assets/css/style.css">
  <script src="../../assets/js/jquery-3.7.1.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto&display=swap"
    rel="stylesheet" />
    <style>
  .menu-item {
    position: relative;
  }

  .menu-item .badge {
    position: absolute;
    top: 2px;
    right: 10px;
    background-color: red;
    color: white;
    font-size: 11px;
    padding: 3px 6px;
    border-radius: 50%;
    font-weight: bold;
    line-height: 1;
    min-width: 20px;
    text-align: center;
  }
  #footer {
      margin-top: 20px;
      background-color: var(--footer-color);
    }

    .footer-container {
      width: 100%;
      padding: 20px;
      background-color: var(--footer-color);
      color: #e6e6a7;
      font-family: 'Poppins', sans-serif;
    }
    /* Footer Top */
    .footer-top {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #5a7f2f;
      padding-bottom: 24px;
    }
    .logo-section {
      display: flex;
      align-items: center;
      min-width: 200px;
    }
    .logo-section img {
      width: 100px;
      height: auto;
      object-fit: contain;
    }
    .logo-section h1 {
      margin: 0;
      font-size: 1.3rem;
      font-weight: bold;
      color: #CCCCCC;
    }
    .social-icons {
      display: flex;
      gap: 24px;
      margin-top: 16px;
      margin-right: 50px;
    }
    @media (min-width: 768px) {
      .social-icons {
        margin-top: 0;
      }
    }
    .social-icons a {
      border: 1px solid #e6e6a7;
      border-radius: 9999px;
      width: 36px;
      height: 36px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #e6e6a7;
      font-size: 1rem;
      transition: all 0.3s ease;
    }
    .social-icons a:hover {
      border-color: #CCCCCC;
      color: #CCCCCC;
    }
    /* Footer Middle */
    .footer-middle {
      margin-top: 40px;
      display: flex;
      flex-wrap: wrap;
      gap: 32px;
      justify-content: space-between;
    }
    .colorlib-col {
      flex: 1 1 250px;
      min-width: 250px;
    }
    .colorlib-col h2 {
      color: #CCCCCC;
      font-size: 1.125rem;
      margin-bottom: 24px;
    }
    .colorlib-col ul {
      list-style: none;
      padding: 0;
      margin: 0;
      color: #a0a07a;
      font-size: 0.875rem;
    }
    .colorlib-col ul li {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 24px;
    }
    .colorlib-col ul li i {
      font-size: 16px;
      color: #a0a07a;
      min-width: 20px;
      text-align: center;
    }
    /* Best Sellers */
    .best-sellers {
      flex: 1 1 300px;
      min-width: 300px;
    }
    .best-sellers h2 {
      color: #CCCCCC;
      font-size: 1.125rem;
      margin-bottom: 24px;
    }
    .best-sellers img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      margin-bottom: 12px;
      border-radius: 4px;
    }
    .best-sellers p {
      margin: 0 0 8px 0;
      color: #e6e6a7;
      font-size: 0.875rem;
    }
    .stars {
      color: #e6e6a7;
      font-size: 1.25rem;
      letter-spacing: 2px;
    }
    /* Map Section */
    .map-section {
      flex: 1 1 300px;
      min-width: 300px;
    }
    .map-section h2 {
      color: #CCCCCC;
      font-size: 1.125rem;
      margin-bottom: 24px;
    }
    .map-container {
      width: 100%;
      height: 180px;
      border: 0;
      border-radius: 4px;
      box-shadow: 0 0 8px rgba(0,0,0,0.15);
    }
    /* Footer Bottom */
    .footer-bottom {
      border-top: 1px solid #5a7f2f;
      margin-top: 40px;
      padding-top: 24px;
      font-size: 0.875rem;
      color: #a0a07a;
      text-align: center;
    }
    /* Responsive adjustments */
    @media (max-width: 767px) {
      .footer-middle {
        flex-direction: column;
      }
      .best-sellers, .map-section, .colorlib-col {
        min-width: 100%;
      }
      .social-icons {
        justify-content: flex-start;
      }
    }
</style>
</head>
<body>
  <div id="left-menu">
    <div id="logo">
      <a href="Admin.php"><img src="../../assets/img/logo1.png" alt="logo" /></a>
    </div>
    <div id="menu">
      <div class="menu-item">
        <a href="Admin.php"><i class="fa-solid fa-house"></i> Home</a>
      </div>
      <div class="menu-item">
        <a href="MnProduct.php"><i class="fa-solid fa-box"></i> Quản lý Sản Phẩm</a>
      </div>
      <div class="menu-item">
        <a href="MnOrder.php">
          <i class="fa-solid fa-truck"></i> Quản lý Đơn hàng
          <?php if ($orderCount > 0): ?>
            <span class="badge"><?= $orderCount ?></span>
          <?php endif; ?>
        </a>
      </div>
      <div class="menu-item">
        <a href="MnCustomer.php">
          <i class="fa-solid fa-users"></i> Quản lý tài khoản
          <?php if ($customerCount > 0): ?>
            <span class="badge"><?= $customerCount ?></span>
          <?php endif; ?>
        </a>
      </div>
      <div class="menu-item">
        <a href="MnVoucher.php"><i class="fa-solid fa-ticket"></i>Khuyến Mãi</a>
      </div>
      <div class="menu-item">
        <a href="quanlylienhe.php"><i class="fa-solid fa-address-book"></i>Quản lý liên hệ</a>
      </div>
    </div>
  </div>
  <div id="main">
  <div id="navbar">
  <div id="search">
    <input type="text" placeholder="Nhập nội dung cần tìm kiếm..." />
    <button><i class="fa-solid fa-magnifying-glass"></i></button>
  </div>

  <?php if (isset($_SESSION['user'])): ?>
    <!-- Đã đăng nhập -->
    <div style="display: flex; align-items: center; margin-left: auto; gap: 15px;">
      <div id="cart-icon">
        <a href="../../layout/giohang.php">
          <i class="fa-solid fa-cart-shopping fa-lg"></i>
          <?php if (!empty($_SESSION['cart'])): ?>
            <span class="cart-count"><?= array_sum(array_column($_SESSION['cart'], 'soluong')) ?></span>
          <?php endif; ?>
        </a>
      </div>
      <div id="profile" tabindex="0" aria-haspopup="true" aria-expanded="false" aria-label="User profile menu">
        <div class="myprofile">
        <img src="../../<?php echo htmlspecialchars($_SESSION['user']['avatar']); ?>" alt="User avatar" />
          <div class="user-info">
            <p id="name"><i style="margin:0 5px;" class="fa-solid fa-user"></i>  <?= htmlspecialchars($_SESSION['user']['username']) ?></p>
            <p id="role"><i style="margin:0 5px;" class="fas fa-shield-alt"></i>  <?= htmlspecialchars($_SESSION['user']['role']) ?></p>
          </div>
        </div>
        <div id="profile-dropdown" role="menu" aria-hidden="true">
          <div class="header">
          <img src="../../<?php echo htmlspecialchars($_SESSION['user']['avatar']); ?>" alt="User avatar" />
            <div class="info">
              <p class="name"><?= htmlspecialchars($_SESSION['user']['username']) ?></p>
              <p class="email"><?= htmlspecialchars($_SESSION['user']['email']) ?></p>
              <button type="button">Xem Profile</button>
            </div>
          </div>
          <ul>
            <li><a href="../../layout/thongtincanhan.php">Cài đặt tài khoản</a></li>
            <li><a href="../../layout/xuly/logout_xuly.php">Đăng xuất</a></li>
          </ul>
        </div>
      </div>
    </div>
  <?php else: ?>
    <div style="margin-left: auto; display: flex; gap: 10px;">
      <a href="../login.php" style="padding: 8px 14px; background-color: #2F2FA2; color: white; border-radius: 6px; text-decoration: none;">Đăng nhập</a>
      <a href="../signup.php" style="padding: 8px 14px; background-color: #FF8737; color: white; border-radius: 6px; text-decoration: none;">Đăng ký</a>
    </div>
  <?php endif; ?>
</div>
    <div id="main-content">
      <?= $pageContent ?? '<p>Không có nội dung.</p>' ?>
    </div>
    <div id="footer">
    <div class="footer-container">
    <div class="footer-top">
      <div class="logo-section">
        <img src="../../assets/img/logo1.png" alt="Five Friends Logo">
        <h1>Five Friends</h1>
      </div>
      <div class="social-icons" aria-label="Social media links">
        <a href="https://www.twitter.com" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
        <a href="https://www.facebook.com" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
        <a href="https://www.instagram.com" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
      </div>
    </div>
    <div class="footer-middle">
      <div class="colorlib-col">
        <h2>Về chúng tôi</h2>
        <ul>
          <li>
            <i class="fa-solid fa-location-dot"></i>
            <span>02 Võ Oanh, Phường 25, Bình Thạnh, HCM, VN</span>
          </li>
          <li>
            <i class="fa-solid fa-phone-volume"></i>
            <span>038 6699 723</span>
          </li>
          <li>
            <i class="fa-solid fa-envelope"></i>
            <span>admin@fivefriend.webshop</span>
          </li>
          <li>
            <i class="fa-solid fa-calendar"></i>
            <span>Thứ 2 - Chủ Nhật: 7:00 AM - 11:00 PM</span>
          </li>
        </ul>
      </div>
      <div class="best-sellers">
        <h2>Best Sellers</h2>
        <img src="../../assets/img/combo/best-seller.jpg" width="300" height="180" />
        <p>Combo Brew Tắc + Bánh Mì Que</p>
        <div class="stars" aria-label="5 star rating">
          <i class="fas fa-star" aria-hidden="true"></i>
          <i class="fas fa-star" aria-hidden="true"></i>
          <i class="fas fa-star" aria-hidden="true"></i>
          <i class="fas fa-star" aria-hidden="true"></i>
          <i class="fas fa-star" aria-hidden="true"></i>
        </div>
      </div>
      <div class="map-section">
        <h2>Google Map</h2>
        <iframe class="map-container" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.0887067578224!2d106.71414257480538!3d10.804517789345978!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3175293dceb22197%3A0x755bb0f39a48d4a6!2sHo%20Chi%20Minh%20City%20University%20of%20Transport!5e0!3m2!1sen!2sus!4v1751371344833!5m2!1sen!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    </div>
    <div class="footer-bottom">
        <p>© 2025 Five Friends WebShop. All rights reserved.</p>
    </div>
    </div>
</div>  
  </div>
  <script>
  const profile = $("#profile");
  const dropdown = $("#profile-dropdown");

  function closeDropdown() {
    dropdown.hide();
    profile.attr("aria-expanded", "false");
    dropdown.attr("aria-hidden", "true");
  }

  // Toggle dropdown
  profile.on("click keydown", function (e) {
    if (
      e.type === "click" ||
      (e.type === "keydown" && (e.key === "Enter" || e.key === " "))
    ) {
      e.preventDefault();
      if (dropdown.is(":visible")) {
        closeDropdown();
      } else {
        dropdown.show();
        profile.attr("aria-expanded", "true");
        dropdown.attr("aria-hidden", "false");
      }
    }
  });

  // ✅ Ngăn dropdown bị đóng khi click bên trong nó
  dropdown.on("click", function (e) {
    e.stopPropagation();
  });

  // ✅ Đóng dropdown khi click bên ngoài
  $(document).on("click", function (e) {
    if (!profile.is(e.target) && profile.has(e.target).length === 0) {
      closeDropdown();
    }
  });
</script>
</body>
<html>