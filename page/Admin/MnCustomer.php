<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý khách hàng</title>
  <script src="../../assets/js/jquery-3.7.1.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
  <style>
    .modal {
      position: fixed;
      inset: 0;
      display: none;
      background: rgba(0, 0, 0, 0.5);
      justify-content: center;
      align-items: center;
      z-index: 1000;
      animation: fadeIn 0.2s ease-in-out;
    }

    .modal-buttons {
      display: flex;
      justify-content: center;
      margin-top: 15px;
    }

    .modal-content {
      background: #fff;
      padding: 25px 30px;
      border-radius: 12px;
      width: 100%;
      max-width: 420px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      animation: slideUp 0.3s ease-in-out;
      font-family: 'Roboto', sans-serif;
      position: relative;
    }

    .modal-content h4 {
      margin-bottom: 10px;
    }

    .modal-content h3 {
      margin-top: 0;
      font-size: 20px;
      color: #333;
      margin-bottom: 20px;
      text-align: center;
    }

    .modal-content input[type="text"],
    .modal-content input[type="number"],
    .modal-content input[type="password"],
    .modal-content input[type="email"],
    .modal-content select {
      width: 100%;
      padding: 10px 12px;
      margin-bottom: 15px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 14px;
      box-sizing: border-box;
    }

    .modal-content input:focus,
    .modal-content select:focus {
      border-color: #007bff;
      outline: none;
    }

    .modal-content button {
      padding: 10px 16px;
      margin-right: 10px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      font-size: 14px;
      transition: background 0.3s;
    }

    .modal-content button[type="submit"] {
      background-color: #28a745;
      color: #fff;
    }

    .modal-content button[type="button"],
    .modal-content .btn-cancel {
      background-color: #6c757d;
      color: #fff;
    }

    .modal-content .btn-danger {
      background-color: #dc3545;
      color: #fff;
    }

    .modal-content button:hover {
      opacity: 0.9;
    }

    .modal-close {
      position: absolute;
      top: 10px;
      right: 15px;
      font-size: 20px;
      cursor: pointer;
      color: #999;
    }

    .modal-close:hover {
      color: #333;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    @keyframes slideUp {
      from {
        transform: translateY(40px);
        opacity: 0;
      }

      to {
        transform: translateY(0);
        opacity: 1;
      }
    }
  </style>
</head>
<?php
require_once __DIR__ . '/../../connect.php';
$result = mysqli_query($link, "SELECT * FROM user ORDER BY idUser DESC");
ob_start();
?>

<body>
  <div class="customer-page">
    <div class="container">
      <div class="header d-flex justify-space-between align-items-center mb-3">
        <h2>Quản lý khách hàng</h2>
        <a href="#" class="btn-primary" onclick="openAddModal()"><i class="fa-solid fa-square-plus"></i> Thêm khách hàng</a>
      </div>

      <div class="filter-bar d-flex justify-space-between align-items-center mb-3">
        <input type="text" class="search-box" id="searchBox" placeholder="Tìm theo tên, email, SĐT...">
        <select class="filter-select" id="roleFilter">
          <option value="all">Tất cả</option>
          <option value="admin">Admin</option>
          <option value="manager">Quản lý</option>
          <option value="user">Khách hàng</option>
        </select>
      </div>

      <table class="table-customer">
        <thead>
          <tr>
            <th>#</th>
            <th>Username</th>
            <th>Họ tên</th>
            <th>Email</th>
            <th>Số điện thoại</th>
            <th>Quyền</th>
            <th>Hành động</th>
          </tr>
        </thead>

        <tbody id="customerTable">
          <?php $i = 1;
          while ($row = mysqli_fetch_assoc($result)): ?>
            <tr data-role="<?= $row['role'] ?>">
              <td><?= $i++ ?></td>
              <td><?= htmlspecialchars($row['username']) ?></td>
              <td><?= htmlspecialchars($row['fullName']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= htmlspecialchars($row['phone']) ?></td>
              <td><?= ucfirst($row['role']) ?></td>
              <td>
                <a href="#" class="btn-sm btn-warning" onclick='openEditModal(<?= json_encode($row) ?>)'>Sửa</a>
                <a href="#" class="btn-sm btn-danger" onclick='openDeleteModal(<?= $row['idUser'] ?>)'>Xoá</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>


  <div id="customerModal" class="modal">
    <div class="modal-content">
      <span class="modal-close" onclick="closeModal()">&times;</span>
      <form method="POST" action="Controller/UserCTL.php" id="customerForm">
        <input type="hidden" name="idUser" id="idUser">
        <input type="hidden" name="action" id="formAction">
        <input type="text" name="username" id="username" placeholder="Username">
        <input type="password" name="password" id="password" placeholder="Password">
        <input type="text" name="fullName" id="fullName" placeholder="Họ tên" required><br>
        <input type="email" name="email" id="email" placeholder="Email"><br>
        <input type="text" name="phone" id="phone" placeholder="Số điện thoại"><br>
        <select name="role" id="role">
          <option value="user">Khách hàng</option>
          <option value="admin">Admin</option>
          <option value="manager">Quản lý</option>
        </select><br>
        <div class="modal-buttons">
          <button type="submit">Lưu</button>
          <button type="button" onclick="closeModal()">Hủy</button>
        </div>
      </form>
    </div>
  </div>


  <div id="deleteModal" class="modal">
    <div class="modal-content">
      <span class="modal-close" onclick="closeDeleteModal()">&times;</span>
      <h3>Xác nhận xoá khách hàng</h3>
      <form method="POST" action="Controller/UserCTL.php">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="idUser" id="deleteUserId">
        <div class="modal-buttons">
          <button type="submit" class="btn-danger">Xoá</button>
          <button type="button" onclick="closeDeleteModal()" class="btn-cancel">Hủy</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openAddModal() {
      document.getElementById('customerForm').reset();
      document.getElementById('formAction').value = 'add';
      document.getElementById('username').style.display = 'block';
      document.getElementById('password').style.display = 'block';
      document.getElementById('customerModal').style.display = 'flex';
    }

    function openEditModal(data) {
      document.getElementById('formAction').value = 'edit';
      document.getElementById('idUser').value = data.idUser;
      document.getElementById('username').style.display = 'none';
      document.getElementById('password').style.display = 'none';
      document.getElementById('fullName').value = data.fullName;
      document.getElementById('email').value = data.email;
      document.getElementById('phone').value = data.phone;
      document.getElementById('role').value = data.role;
      document.getElementById('customerModal').style.display = 'flex';
    }

    function closeModal() {
      document.getElementById('customerModal').style.display = 'none';
    }

    function openDeleteModal(id) {
      document.getElementById('deleteUserId').value = id;
      document.getElementById('deleteModal').style.display = 'flex';
    }

    function closeDeleteModal() {
      document.getElementById('deleteModal').style.display = 'none';
    }

    document.getElementById("roleFilter").addEventListener("change", function() {
      const selected = this.value;
      document.querySelectorAll("#customerTable tr").forEach(row => {
        const role = row.dataset.role;
        row.style.display = (selected === "all" || role === selected) ? "" : "none";
      });
    });

    document.getElementById("searchBox").addEventListener("input", function() {
      const keyword = this.value.toLowerCase();
      document.querySelectorAll("#customerTable tr").forEach(row => {
        const username = row.children[1].textContent.toLowerCase();
        const name = row.children[1].textContent.toLowerCase();
        const email = row.children[2].textContent.toLowerCase();
        const phone = row.children[3].textContent.toLowerCase();
        row.style.display = name.includes(keyword) || email.includes(keyword) || phone.includes(keyword) ? "" : "none";
      });
    });
  </script>
  <?php
  $pageContent = ob_get_clean();
  include(__DIR__ . '/../../components/layout/layoutadmin.php');
  ?>
</body>

</html>