<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Quản lý mã giảm giá</title>
  <link rel="stylesheet" href="/ltw/assets/css/admin.css">
  <script src="../../assets/js/jquery-3.7.1.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
  <style>
    .modal {
      position: fixed;
      inset: 0;
      display: none;
      background: rgba(0, 0, 0, 0.4);
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }

    .modal-content {
      background: white;
      padding: 25px 30px;
      border-radius: 12px;
      width: 400px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      position: relative;
    }

    .modal-content h3 {
      text-align: center;
      margin-bottom: 20px;
    }

    .modal-content input,
    .modal-content select,
    .modal-content button {
      width: 100%;
      margin-bottom: 12px;
      padding: 10px;
      border-radius: 6px;
    }

    .modal-content button {
      background-color: #28a745;
      color: white;
      border: none;
    }

    .modal-close {
      position: absolute;
      top: 10px;
      right: 15px;
      cursor: pointer;
      font-size: 20px;
      color: #888;
    }

    .btn-danger {
      background: #dc3545;
      color: #fff;
    }

    .btn-sm {
      padding: 6px 10px;
      border-radius: 5px;
      font-size: 13px;
      text-decoration: none;
    }
  </style>
</head>
<?php
require_once __DIR__ . '/../../connect.php';
$result = mysqli_query($link, "SELECT * FROM DiscountCode ORDER BY id DESC");
ob_start();
?>

<body>
  <div class="page-discount-management">
    <div class="header">
      <input type="text" class="search-box" id="searchInput" placeholder="Tìm mã...">
      <a href="#" class="btn-add" onclick="showAddModal()"><i class="fa-solid fa-square-plus"></i> Thêm mã giảm giá</a>
    </div>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Mã Code</th>
          <th>Giá trị giảm</th>
          <th>Đơn tối thiểu</th>
          <th>Thời gian áp dụng</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody id="discountTableBody">
        <?php $i = 1;
        while ($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($row['code']) ?></td>
            <td>
              <?= number_format($row['discount_value'], 0, ',', '.') ?>
              <?= $row['discount_type'] === 'percent' ? '%' : '₫' ?>
            </td>
            <td><?= number_format($row['min_order_value'], 0, ',', '.') ?> VND</td>
            <td><?= date('d/m', strtotime($row['start_date'])) ?> - <?= date('d/m', strtotime($row['end_date'])) ?></td>
            <td>
              <a href="#" class="btn-sm btn-warning"
                onclick="openEditModal(
                  <?= $row['id'] ?>,
                  '<?= $row['code'] ?>',
                  '<?= $row['discount_type'] ?>',
                  <?= $row['discount_value'] ?>,
                  <?= $row['min_order_value'] ?>,
                  '<?= $row['start_date'] ?>',
                  '<?= $row['end_date'] ?>'
                )">
                Sửa
              </a>
              <a href="Controller/VoucherCTL.php?delete=<?= $row['id'] ?>" onclick="return confirm('Xác nhận xoá mã này?')" class="btn-sm btn-danger">Xoá</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>


  <div class="modal" id="addModal">
    <div class="modal-content">
      <span class="modal-close" onclick="closeAddModal()">×</span>
      <form action="Controller/VoucherCTL.php" method="POST">
        <h3>Thêm mã giảm giá</h3>
        <input type="hidden" name="action" value="add">
        <input type="text" name="code" placeholder="Mã CODE" required>
        <select name="discount_type" required>
          <option value="number">Số tiền (VND)</option>
          <option value="percent">Phần trăm (%)</option>
        </select>
        <input type="number" name="discount_value" placeholder="Nhập số tiền hoặc phần trăm" required>
        <input type="number" name="min_order_value" placeholder="Đơn tối thiểu" required>
        <input type="date" name="start_date" required>
        <input type="date" name="end_date" required>
        <input type="number" name="usage_limit" placeholder="Số lượt sử dụng" required>
        <div class="form-group" style="margin-bottom: 12px;">
          <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 14px;">
            <input type="checkbox" name="per_user_limit" value="1" style="margin: 0; width: auto; height: auto;">
            Chỉ sử dụng 1 lần
          </label>
        </div>
        
        <button type="submit">Lưu</button>
      </form>
    </div>
  </div>
  <div class="modal" id="editModal">
    <div class="modal-content">
      <span class="modal-close" onclick="closeEditModal()">×</span>
      <form action="Controller/VoucherCTL.php" method="POST">
        <h3>Sửa mã giảm giá</h3>
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="id" id="edit_id">
        <input type="text" name="code" id="edit_code" required>
        <select name="discount_type" id="edit_discount_type" required>
          <option value="number">Số tiền (VND)</option>
          <option value="percent">Phần trăm (%)</option>
        </select>
        <input type="number" name="discount_value" id="edit_discount_value" placeholder="Nhập số tiền hoặc phần trăm" required>
        <input type="number" name="min_order_value" id="edit_min_order" required>
        <input type="date" name="start_date" id="edit_start" required>
        <input type="date" name="end_date" id="edit_end" required>
        <input type="number" name="usage_limit" placeholder="Số lượt sử dụng" required>
        <div class="form-group" style="margin-bottom: 12px;">
          <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 14px;">
            <input type="checkbox" name="per_user_limit" value="1" style="margin: 0; width: auto; height: auto;">
            Chỉ sử dụng 1 lần
          </label>
        </div>
        <button type="submit">Cập nhật</button>
      </form>
    </div>
  </div>

  <script>
    function showAddModal() {
      document.getElementById('addModal').style.display = 'flex';
    }

    function closeAddModal() {
      document.getElementById('addModal').style.display = 'none';
    }

    function openEditModal(id, code, type, value, minOrder, start, end) {
      document.getElementById('edit_id').value = id;
      document.getElementById('edit_code').value = code;
      document.getElementById('edit_discount_type').value = type;
      document.getElementById('edit_discount_value').value = value;
      document.getElementById('edit_min_order').value = minOrder;
      document.getElementById('edit_start').value = start;
      document.getElementById('edit_end').value = end;
      document.getElementById('editModal').style.display = 'flex';
    }

    function closeEditModal() {
      document.getElementById('editModal').style.display = 'none';
    }

    $('#searchInput').on('input', function() {
      const keyword = $(this).val().toLowerCase();
      $('#discountTableBody tr').each(function() {
        const code = $(this).find('td:nth-child(2)').text().toLowerCase();
        $(this).toggle(code.includes(keyword));
      });
    });
  </script>
  <?php
  $pageContent = ob_get_clean();
  include(__DIR__ . '/../../components/layout/layoutadmin.php');
  ?>
</body>

</html>