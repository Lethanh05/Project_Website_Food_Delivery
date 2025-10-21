<?php session_start(); ?>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>5AE - Online WebShop</title>

    <!-- Thư viện dùng chung -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- CSS chung -->
    <link rel="stylesheet" href="../assets/css/style.css" />
    
    <!-- CSS nội bộ riêng cho trang này (nếu có) -->
    <style>
      .stack-container {
        position: relative;
        width: 100%;
        height: 100%;
        overflow: hidden;
      }
      .stack-container img {
        position: absolute;
        width: 80px;
        height: 90px;
        transition: all 0.4s ease;
        border: 1px solid #ddd;
        border-radius: 12px;
        object-fit: cover;
        cursor: pointer;
      }
      .img1 { top: 0; left: 0; }
      .img2 { top: 0; left: 40px; }
      .img3 { top: 20px; left: 30px; }
      .img4 { top: 10px; left: 40px; }
      .stack-container:hover .img1 { top: 30px; left: 100px; width: 160px; height: 190px; }
      .stack-container:hover .img2 { top: 30px; left: 280px; width: 160px; height: 190px; }
      .stack-container:hover .img3 { top: 30px; left: 460px; width: 160px; height: 190px; }
      .stack-container:hover .img4 { top: 30px; left: 640px; width: 160px; height: 190px; }
    </style>
  </head>
  <body>
    <?php include("../connect.php"); ?>
    <?php include("include/left-menu.php"); ?>

    <div id="main">
      <?php include("include/navbar.php"); ?>

      <div id="main-content">
        <!-- Giới thiệu -->
        <div class="about-section">
          <h1>
            <span style="color:rgb(241, 97, 19);">FIVE FRIEND</span>
          </h1>
          <div class="about-desc">
            <div>
              <p>Cửa hàng Five Friends được thành lập...</p>
            </div>
            <div>
              <p>Chúng tôi luôn đặt sự hài lòng của khách hàng lên hàng đầu...</p>
            </div>
          </div>

          <!-- Stack hình ảnh -->
          <div style="position: relative; width: 800px; height: 250px; margin: 50px auto;">
            <div class="stack-container">
              <img src="../assets/img/store/ff1.png" class="img1" alt="Store 1" />
              <img src="../assets/img/store/ff2.png" class="img2" alt="Store 2" />
              <img src="../assets/img/store/ff3.png" class="img3" alt="Store 3" />
              <img src="../assets/img/store/ff4.png" class="img4" alt="Store 4" />
            </div>
          </div>
        </div>

        <!-- Cam kết -->
        <div class="about-section">
          <h1>
            <span style="color:rgb(241, 97, 19);">FIVE FRIEND</span>
            <span style="color:rgb(5, 5, 5);">CAM KẾT</span>
          </h1>
          <div class="about-desc">
            <div>
              <p><strong>Cam kết dịch vụ</strong> của <strong>Five Friend</strong>...</p>
              <p><strong>Sứ mệnh</strong> của <strong>Five Friend</strong>...</p>
            </div>
            <div>
              <p><strong>Tầm nhìn</strong> của chúng tôi là...</p>
            </div>
          </div>
        </div>

        <?php include("include/footer.php"); ?>
      </div>
    </div>
  </body>
</html>
