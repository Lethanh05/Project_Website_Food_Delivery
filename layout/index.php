<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login | 5AE WebShop</title>
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
      /* Suggestions styles */
      .suggestions {
        display: flex;
        flex-wrap: nowrap;
        gap: 16px;
        margin: 20px -8px 0 -8px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
      }
      .suggestion-item {
        flex: 0 0 calc((100% / 6) - 13.33px);
        box-sizing: border-box;
        padding: 0 8px;
        min-width: 150px;
      }
      .card {
        border: 1px solid #ddd;
        border-radius: 6px;
        overflow: hidden;
        background-color: #fafafa;
        box-shadow: 0 2px 6px rgb(0 0 0 / 0.1);
        display: flex;
        flex-direction: column;
        height: 100%;
      }
      .card img {
        width: 100%;
        height: auto;
        object-fit: cover;
        display: block;
        border-bottom: 1px solid #ddd;
      }
      .card p {
        margin: 12px 0;
        text-align: center;
        font-size: 1rem;
        color: #333;
        flex-grow: 1;
        padding: 0 8px 12px;
      }
      /* Hide scrollbar for WebKit browsers */
      .suggestions::-webkit-scrollbar {
        display: none;
      }
      /* Hide scrollbar for IE, Edge and Firefox */
      .suggestions {
        -ms-overflow-style: none; /* IE and Edge */
        scrollbar-width: none; /* Firefox */
      }
      /* Slider styles */
      .slider {
        position: relative;
        width: 100%;
        height: 100%;
        overflow: hidden;
        margin: 20px 0;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
      }
      .slider-track {
        display: flex;
        height: 100%;
        transition: transform 0.5s ease-in-out;
      }
      .slider-item {
        flex: 0 0 100%;
        height: 100%;
      }
      .slider-item img {
        width: 100%;
        height: auto;
        object-fit: cover;
        display: block;
      }
      @media (max-width: 768px) {
        #left-menu {
          position: relative;
          width: 100%;
          height: auto;
        }
        #main {
          margin-left: 0;
        }
        #navbar {
          padding: 5px 10px;
          position: relative;
          z-index: 10;
        }
        #navbar #search input {
          width: 150px;
        }
        #profile-dropdown {
          width: 90vw;
          right: 5vw;
          top: 60px;
        }
        .suggestion-item {
          flex: 0 0 calc((100% / 3) - 10.66px);
          min-width: 150px;
        }
        .slider {
          height: 150px;
        }
      }
      @media (max-width: 480px) {
        .suggestion-item {
          flex: 0 0 calc((100% / 2) - 8px);
          min-width: 150px;
        }
        .slider {
          height: 120px;
        }
      }
      .banner-row {
        display: flex;
        gap: 20px;
        margin: 30px 10px;
        flex-wrap: wrap;
      }
      .banner-col {
        flex: 1 1 45%;
      }
      .banner-card img {
        width: 100%;
        height: auto;
        object-fit: cover;
        border-radius: 16px;
        display: block;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
      }
    </style>
  </head>
  <body>
    <?php 
    session_start();
    include("../connect.php");
    include("include/left-menu.php");
    ?>
    <div id="main">
      <?php include("include/navbar.php"); 
      ?>
      <div id="main-content">
        <!-- Slider -->
      <div class="slider">
          <div class="slider-track">
            <div class="slider-item">
              <img src="../assets/img/slider/slider1.jpg" alt="Slider Image 1" />
            </div>
            <div class="slider-item">
              <img src="../assets/img/slider/slider2.jpeg" alt="Slider Image 2" />
            </div>
            <div class="slider-item">
              <img src="../assets/img/slider/slider3.jpg" alt="Slider Image 3" />
            </div>
            <div class="slider-item">
              <img src="../assets/img/slider/slider4.jpg" alt="Slider Image 4" />
            </div>
            <div class="slider-item">
              <img src="../assets/img/slider/slider5.jpg" alt="Slider Image 5" />
            </div>
            <div class="slider-item">
              <img src="../assets/img/slider/slider6.jpg" alt="Slider Image 6" />
            </div>
          </div>
        </div>

        <!-- Banner 2 -->
        <div class="banner-row">
          <div class="banner-col">
            <div class="banner-card">
              <img src="../assets/img/goiychoban/1.jpg" alt="Banner 1" />
            </div>
          </div>
          <div class="banner-col">
            <div class="banner-card">
              <img src="../assets/img/goiychoban/2.jpg" alt="Banner 2" />
            </div>
          </div>
        </div>

        <!-- Gợi ý cho bạn -->
        <h4>Gợi ý cho bạn</h4>
        <div class="suggestions">
          <div class="suggestion-item">
            <div class="card">
              <img
                src="../assets/img/goiychoban/gy1.webp"
                alt="Món Ngon Đề Xuất banner image"
              />
              <p>Món Ngon Đề Xuất</p>
            </div>
          </div>
          <div class="suggestion-item">
            <div class="card">
              <img
                src="../assets/img/goiychoban/gy2.webp"
                alt="Lễ Hội Trà Mùa Hè banner image"
              />
              <p>Lễ Hội Trà Mùa Hè</p>
            </div>
          </div>
          <div class="suggestion-item">
            <div class="card">
              <img
                src="../assets/img/goiychoban/gy3.webp"
                alt="Lễ Hội Trái Cây banner image"
              />
              <p>Lễ Hội Trái Cây</p>
            </div>
          </div>
          <div class="suggestion-item">
            <div class="card">
              <img
                src="../assets/img/goiychoban/gy4.webp"
                alt="Mì Gà Siu Cay banner image"
              />
              <p>Mì Gà Siu Cay</p>
            </div>
          </div>
          <div class="suggestion-item">
            <div class="card">
              <img
                src="../assets/img/goiychoban/gy5.webp"
                alt="Mua Là Có Quà banner image"
              />
              <p>Mua Là Có Quà</p>
            </div>
          </div>
          <div class="suggestion-item">
            <div class="card">
              <img
                src="../assets/img/goiychoban/gy6.webp"
                alt="Toastie - Sandwich banner image"
              />
              <p>Toastie - Sandwich</p>
            </div>
          </div>
        </div>
        
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

        // Slider
        let currentIndex = 0;
        const slides = $(".slider-item");
        const totalSlides = slides.length;

        function showSlide(index) {
          const offset = -index * 100;
          $(".slider-track").css("transform", `translateX(${offset}%)`);
        }

        function nextSlide() {
          currentIndex = (currentIndex + 1) % totalSlides;
          showSlide(currentIndex);
        }

        // Auto slide every 1 second
        setInterval(nextSlide, 3000);
      });
    </script>
    <?php if (isset($_SESSION['thongbao'])): ?>
          <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
          <script>
            Swal.fire({
              icon: '<?= $_SESSION['thongbao']['type'] ?>',
              title: '<?= $_SESSION['thongbao']['title'] ?>',
              text: '<?= $_SESSION['thongbao']['message'] ?>',
              confirmButtonText: 'OK'
            });
          </script>
          <?php unset($_SESSION['thongbao']); ?>
        <?php endif; ?>
  </body>
</html>