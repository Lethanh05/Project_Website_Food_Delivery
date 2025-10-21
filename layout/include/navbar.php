<div id="navbar">
  <div id="search">
    <input type="text" placeholder="Nhập nội dung cần tìm kiếm..." />
    <button><i class="fa-solid fa-magnifying-glass"></i></button>
  </div>

  <?php if (isset($_SESSION['user'])): ?>
    <!-- Đã đăng nhập -->
    <div style="display: flex; align-items: center; margin-left: auto; gap: 15px;">
      <div id="cart-icon">
        <a href="giohang.php">
          <i class="fa-solid fa-cart-shopping fa-lg"></i>
          <?php if (!empty($_SESSION['cart'])): ?>
            <span class="cart-count"><?= array_sum(array_column($_SESSION['cart'], 'soluong')) ?></span>
          <?php endif; ?>
        </a>
      </div>
      <div id="profile" tabindex="0" aria-haspopup="true" aria-expanded="false" aria-label="User profile menu">
        <div class="myprofile">
        <img src="../<?php echo htmlspecialchars($_SESSION['user']['avatar']); ?>" alt="User avatar" />
          <div class="user-info">
            <p id="name"><i style="margin:0 5px;" class="fa-solid fa-user"></i>  <?= htmlspecialchars($_SESSION['user']['username']) ?></p>
            <p id="role"><i style="margin:0 5px;" class="fas fa-shield-alt"></i>  <?= htmlspecialchars($_SESSION['user']['role']) ?></p>
          </div>
        </div>
        <div id="profile-dropdown" role="menu" aria-hidden="true">
          <div class="header">
          <img src="../<?php echo htmlspecialchars($_SESSION['user']['avatar']); ?>" alt="User avatar" />
            <div class="info">
              <p class="name"><?= htmlspecialchars($_SESSION['user']['username']) ?></p>
              <p class="email"><?= htmlspecialchars($_SESSION['user']['email']) ?></p>
              <button type="button">Xem Profile</button>
            </div>
          </div>
          <ul>
            <li><a href="lichsudathang.php">Xem đơn đặt hàng</a></li>
            <li><a href="thongtincanhan.php">Cài đặt tài khoản</a></li>
            <li><a href="../layout/xuly/logout_xuly.php">Đăng xuất</a></li>
          </ul>
        </div>
      </div>
    </div>
  <?php else: ?>
    <!-- Chưa đăng nhập -->
    <div style="margin-left: auto; display: flex; gap: 10px;">
      <a href="../login.php" style="padding: 8px 14px; background-color: #2F2FA2; color: white; border-radius: 6px; text-decoration: none;">Đăng nhập</a>
      <a href="../signup.php" style="padding: 8px 14px; background-color: #FF8737; color: white; border-radius: 6px; text-decoration: none;">Đăng ký</a>
    </div>
  <?php endif; ?>
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