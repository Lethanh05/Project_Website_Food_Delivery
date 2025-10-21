<?php 
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}
?>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Lịch sử đặt hàng | 5AE WebShop</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
  <style>
    h3 {
      margin-top: 10px;
      text-align: center;
      font-weight: bold;
      padding: 15px;
      background-color: var(--left-menu-color);
      color: white;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      margin-bottom: 20px;
    }
    #lsdh {
      width: 100%;
      background-color: white;
      border-spacing: 0 5px;
    }
    #lsdh thead tr {
      background-color: var(--left-menu-color);
      color: white;
      font-weight: bold;
    }
    #lsdh th, #lsdh td {
      padding: 10px 12px;
      text-align: center; 
    }
    #lsdh tbody tr {
      background-color: #ffffff;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      border-radius: 8px;
    }
    #lsdh tbody tr:hover {
      background-color: #f0f0ff;
    }
    .cancel-btn {
      font-size: 14px;
      padding: 8px 14px;
      background-color: #d63031;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    .cancel-btn:hover {
      background-color:rgb(230, 55, 35);
    }
    .show-btn {
      font-size: 14px;
      padding: 8px 14px;
      background-color: var(--left-menu-color);
      color: white;
      border: none;
      border-radius: 6px;
      text-decoration: none;
    }
    .show-btn:hover {
      background-color: rgb(12, 1, 135);
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
    <h3>LỊCH SỬ ĐẶT HÀNG</h3>
    <table id="lsdh">
      <thead>
        <tr>
          <th>STT</th>
          <th>Mã đơn hàng</th>
          <th>Ngày đặt hàng</th>
          <th>Sản phẩm</th>
          <th>Thanh Toán</th>
          <th>Trạng thái</th>
          <th>Hành động</th>
          <th>Chi tiết</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $idUser = $_SESSION['user']['idUser'];
        $sql_donhang = "SELECT * FROM donhang WHERE idUser = $idUser ORDER BY ngayDat DESC";
        $result_donhang = mysqli_query($link, $sql_donhang);
        $stt = 1;

        while ($donhang = mysqli_fetch_assoc($result_donhang)) {
          $idDonHang = $donhang['idDonHang'];
          $ngayDat = $donhang['ngayDat'];
          $trangThai = $donhang['trangThai'];
          $ptThanhToan = $donhang['ptThanhToan'];

          $sql_chitiet = "
            SELECT s.tenSanPham, c.soLuong, c.giaMua 
            FROM chitietdonhang c
            JOIN sanpham s ON c.idSanPham = s.idSanPham
            WHERE c.idDonHang = $idDonHang
          ";
          $result_ct = mysqli_query($link, $sql_chitiet);

          $spList = [];
          while ($row = mysqli_fetch_assoc($result_ct)) {
            $ten = htmlspecialchars($row['tenSanPham']);
            $sl = $row['soLuong'];
            $gia = number_format($row['giaMua'], 0, ',', '.') . 'đ';
            $spList[] = "$ten ($sl × $gia)";
          }
          $sanPhamStr = implode("<br>", $spList);

          echo "<tr>";
          echo "<td>" . $stt++ . "</td>";
          echo "<td>" . $idDonHang . "</td>";
          echo "<td>" . date("d-m-Y H:i", strtotime($ngayDat)) . "</td>";
          echo "<td>" . $sanPhamStr . "</td>";
          echo "<td>" . $ptThanhToan . "</td>";
          echo "<td>" . $trangThai . "</td>";

          if ($trangThai == 'Chờ Xác Nhận') {
            echo "<td><button class='cancel-btn' onclick='huyDon($idDonHang)'>Hủy đơn hàng</button></td>";
          } else {
            echo "<td>—</td>";
          }

          echo "<td><a href='chitietdonhang.php?idDonHang=$idDonHang' class='show-btn'>Xem chi tiết</a></td>";
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
  <?php include("include/footer.php"); ?>
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
  });

  function huyDon(id) {
    if (confirm("Bạn có chắc muốn hủy đơn hàng #" + id + " không?")) {
      window.location.href = "xuly/huyDonHang.php?id=" + id;
    }
  }
</script>
</body>
</html>
