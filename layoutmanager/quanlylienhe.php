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
?>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login | 5AE WebShop</title>
    <link rel="stylesheet" href="../assets/css/style.css" />
    <link rel="stylesheet" href="../assets/css/manager.css">
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    <?php 
      include("../connect.php");
      include("include/left-menu.php");
    ?>
    <div id="main">
      <?php include("include/navbar.php"); ?>

      <div id="main-content">
        <h3><i class="fa-solid fa-envelope"></i> Danh sách liên hệ</h3>

        <table id="bangLienHe"> 
          <thead>
            <tr>
              <th>Mã Liên Hệ</th>
              <th>Họ tên</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Thời Gian</th>
              <th>Nội dung</th>
              <th>Trạng thái</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $result = $link->query("SELECT * FROM lienhe");
              while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['idLienHe']) . "</td>";
                echo "<td>" . htmlspecialchars($row['hoTen']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                echo "<td>" . htmlspecialchars($row['timeSubmit']) . "</td>";
                echo "<td>" . htmlspecialchars($row['noiDung']) . "</td>";
                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                echo "<td>";
                if ($row['status'] === 'Chưa xử lí') {
                  echo "<form method='post' action='xuly/xulylienhe.php'>
                          <input type='hidden' name='id' value='{$row['idLienHe']}'>
                          <button type='submit'>Đánh dấu đã xử lý</button>
                        </form>";
                } else {
                  echo "<span style='color: green;'>✔ Đã xử lí</span>";
                }
                echo "</td>";
                echo "</tr>";
              }
            ?>
          </tbody>
        </table>
      </div>
      <?php include("include/footer.php"); ?>
    </div>
  </body>
</html>