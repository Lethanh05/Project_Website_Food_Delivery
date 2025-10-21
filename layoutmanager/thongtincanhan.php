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
    <title>5AE - Online WebShop</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto&display=swap"
      rel="stylesheet"
    />
    <style>

      .container{
        display : flex;
        flex-direction: row;
        gap: 24px;
      }     

      .card {
      background-color: white;
      border-radius: 0.5rem;
      padding: 24px;
      box-shadow: rgba(0, 0, 0, 0.09) 0px 3px 12px;
      flex-shrink: 0;
      margin : 10px;
    }
    .left-card {
      width: 100%;
      max-width: 360px;
    }
    .right-card {
      width: 500px;
      flex-grow: 1;
    }
    .profile-img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      display: block;
      margin: 0 auto 12px auto;
      object-fit: cover;
    }
    .profile-name {
      font-weight: 600;
      font-size: 1.125rem;
      text-align: center;
      margin-bottom: 24px;
    }
    .info-group {
      margin-bottom: 24px;
    }
    .info-label {
      font-size: 0.75rem;
      color: #6b7280;
      margin-bottom: 4px;
    }
    .info-value {
      font-weight: 600;
      font-size: 1rem;
      word-break: break-word;
    }
    .divider {
      border-top: 1px solid #e5e7eb;
      margin-top: 16px;
    }
    .created-date {
      font-weight: 600;
      font-size: 1rem;
    }
    .btn {
      background-color: #6c5ce7;
      color: white;
      font-weight: 600;
      border: none;
      border-radius: 0.375rem;
      padding: 8px 16px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      font-size: 0.875rem;
      margin: 0 auto;
      max-width: 200px;
      transition: background-color 0.2s ease;
    }
    .btn:hover {
      background-color: #2563eb;
    }
    .btn i {
      font-style: normal;
      font-weight: 700;
      font-size: 1rem;
      display: inline-block;
      width: 16px;
      height: 16px;
      background: url('data:image/svg+xml;utf8,<svg fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M432 256c0 13.3-10.7 24-24 24H272v136c0 13.3-10.7 24-24 24s-24-10.7-24-24V280H40c-13.3 0-24-10.7-24-24s10.7-24 24-24h184V96c0-13.3 10.7-24 24-24s24 10.7 24 24v112h136c13.3 0 24 10.7 24 24z"/></svg>') no-repeat center;
      background-size: contain;
    }
    h3 {
      font-weight: 600;
      color: #6c5ce7;
      margin-bottom: 16px;
      font-size: 1.125rem;
    }
    form {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }
    .label-changepass {
      font-size: 0.75rem;
      color: #6b7280;
      margin-bottom: 4px;
      display: block;
    }
    input[type="password"] {
      width: 100%;
      border: 1px solid #d1d5db;
      border-radius: 0.375rem;
      padding: 8px 12px;
      font-size: 0.875rem;
      outline-offset: 2px;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    input[type="password"]:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 0 2px rgb(59 130 246 / 0.5);
    }
    button[type="submit"] {
      background-color: #6c5ce7;
      color: white;
      font-weight: 600;
      border: none;
      border-radius: 0.375rem;
      padding: 12px 0;
      cursor: pointer;
      font-size: 1rem;
      transition: background-color 0.2s ease;
      width: 100%;
    }
    button[type="submit"]:hover {
      background-color: #2563eb;
    }

    /* edit thong tin*/
    #edit-popup {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background-color: rgba(0, 0, 0, 0.4);
      z-index: 1000;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .popup-content {
      background-color: #fff;
      padding: 24px;
      border-radius: 12px;
      width: 90%;
      max-width: 420px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
      display: flex;
      flex-direction: column;
      gap: 16px;
      animation: fadeIn 0.3s ease;
    }

    .popup-content h3 {
      font-size: 1.25rem;
      text-align: center;
      color: #6c5ce7;
    }

    .popup-content input[type="text"],
    .popup-content input[type="email"],
    .popup-content input[type="file"] {
      padding: 8px 12px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1rem;
    }

    .popup-buttons {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin-top: 10px;
    }

    .popup-buttons button {
      padding: 8px 16px;
      border: none;
      border-radius: 6px;
      font-weight: 600;
      cursor: pointer;
    }

    .popup-buttons button[type="submit"] {
      background-color: #6c5ce7;
      color: white;
    }

    .popup-buttons button#close-popup {
      background-color: #ccc;
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
      <div id="main-content">
      <?php
      $user = $_SESSION['user'];
      ?>
      <div class="container">
        <div class="card left-card">
        <!-- Ảnh đại diện -->
        <img
          src="<?php echo !empty($user['avatar']) ? "../".$user['avatar'] : '../assets/img/avt/default.jpg'; ?>"
          alt="Avatar"
          class="profile-img"
        />

        <!-- Tên người dùng -->
        <h2 class="profile-name">
          <?php echo htmlspecialchars($user['fullName'] ?? 'Chưa có tên'); ?>
          <img src="../assets/img/png/tick.png" alt="Verified" style="width: 20px; height: 20px; margin-left: 3px; vertical-align: middle;" />
        </h2>

        <!-- Email -->
        <div class="info-group">
          <p class="info-label">Email</p>
          <p class="info-value"><?php echo htmlspecialchars($user['email']); ?></p>
          <div class="divider"></div>
        </div>

        <!-- Số điện thoại -->
        <div class="info-group">
          <p class="info-label">Phone</p>
          <p class="info-value"><?php echo !empty($user['phone']) ? htmlspecialchars($user['phone']) : 'Chưa cập nhật'; ?></p>
          <div class="divider"></div>
        </div>

        <!-- Tài khoản -->
        <div class="info-group">
          <p class="info-label">Tài khoản</p>
          <p class="info-value"><?php echo htmlspecialchars($user['username']); ?></p>
          <div class="divider"></div>
        </div>

        <!-- Địa chỉ -->
        <div class="info-group">
          <p class="info-label">Địa chỉ</p>
          <p class="info-value"><?php echo !empty($user['address']) ? htmlspecialchars($user['address']) : 'Chưa cập nhật'; ?></p>
          <div class="divider"></div>
        </div>

        <!-- Ngày tạo -->
        <div class="info-group">
          <p class="info-label">Ngày tạo</p>
          <p class="created-date"><?php echo htmlspecialchars($user['created_at'] ?? 'Không rõ'); ?></p>
        </div>

        <!-- Nút chỉnh sửa -->
        <button class="btn" type="button" id="edit-btn">
          <i class="fa fa-edit"></i> Chỉnh sửa thông tin
        </button>
      </div>

      <div class="card right-card">
        <h3>Đổi Mật Khẩu</h3>
        <form action="xuly/change_password.php" method="POST">
          <div>
            <label class="label-changepass" for="old-password">Mật khẩu cũ</label>
            <input type="password" id="old-password" name="old_password" required />
          </div>
          <div>
            <label class="label-changepass" for="new-password-1">Mật khẩu mới</label>
            <input type="password" id="new-password-1" name="new_password_1" required />
          </div>
          <div>
            <label class="label-changepass" for="new-password-2">Nhập lại mật khẩu mới</label>
            <input type="password" id="new-password-2" name="new_password_2" required />
          </div>
          <button type="submit">Đổi ngay</button>
        </form>
      </div>
      </div>
      </div>
      
      <?php include("include/footer.php"); ?>
    </div>
    <div id="edit-popup" style="display: none;">
  <div class="popup-content">
    <h3>Chỉnh sửa thông tin cá nhân</h3>
    <form action="xuly/update_info.php" method="POST" enctype="multipart/form-data">
      
      <!-- Hiển thị ảnh đại diện hiện tại nếu có -->
      <label>Ảnh đại diện:</label>
      <?php if (!empty($_SESSION['user']['avatar'])): ?>
        <img src="<?php echo "../".$_SESSION['user']['avatar']; ?>" alt="Avatar" width="100" style="display:block;margin-bottom:10px;">
      <?php endif; ?>
      <input type="file" name="avatar" accept="image/*" />

      <label>Họ và tên:</label>
      <input type="text" name="name" value="<?php echo $_SESSION['user']['fullName']; ?>" required />

      <label>Email:</label>
      <input type="email" name="email" value="<?php echo $_SESSION['user']['email']; ?>" required />

      <label>Số điện thoại:</label>
      <input type="text" name="phone" value="<?php echo $_SESSION['user']['phone'] ?? ''; ?>" />

      <label>Địa chỉ:</label>
      <input type="text" name="address" value="<?php echo $_SESSION['user']['address'] ?? ''; ?>" />

      <div class="popup-buttons">
        <button type="submit">Lưu</button>
        <button type="button" id="close-popup">Hủy</button>
      </div>
    </form>
  </div>
</div>



    <script>
      $(document).ready(function () {
        $(".submenu").hide();
        $(".menu1").click(function (e) {
          e.preventDefault();
          const submenu = $(this).siblings(".submenu");
          submenu.slideToggle();
          $(this).find(".caret-icon").toggleClass("rotate");
        });
        // chinh sua
        $(".btn:contains('Chỉnh sửa')").click(function () {
          $("#edit-popup").fadeIn();
        });

        $("#close-popup").click(function () {
          $("#edit-popup").fadeOut();
        });

        $("#edit-popup").click(function (e) {
          if ($(e.target).is("#edit-popup")) {
            $("#edit-popup").fadeOut();
          }
        });
      });
    </script>
          
  </body>
</html>
