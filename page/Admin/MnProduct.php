<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Quản Lý Sản Phẩm</title>
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
.modal-content input[type="file"],
.modal-content select {
  width: 100%;
  padding: 10px 12px;
  margin-bottom: 15px;
  border-radius: 8px;
  border: 1px solid #ccc;
  font-size: 14px;
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

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
@keyframes slideUp {
  from { transform: translateY(40px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

</style>
</head>
<?php
require_once __DIR__ . '/../../connect.php';
$sql = "SELECT * FROM sanpham ORDER BY idSanPham DESC";
$result = mysqli_query($link, $sql);
ob_start();
?>
<body>
<div class="product-page">
  <div class="container">
    <div class="header">
    <div class="filter-bar">
      <input type="text" id="searchInput" class="search-box" placeholder="Tìm kiếm sản phẩm...">
      <select class="filter-select" id="categoryFilter">
        <option value="all">Tất cả danh mục</option>
        <option value="Đồ ăn">Đồ ăn</option>
        <option value="Nước Uống">Nước Uống</option>
      </select>
    </div>
      <a href="#" onclick="openAddModal()" class="btn-primary" ><i class="fa-solid fa-square-plus"></i> Thêm sản phẩm </a>
    </div>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Hình ảnh</th>
          <th>Tên sản phẩm</th>
          <th>Giá bán</th>
          <th>Danh mục</th>
           <th>Tồn kho</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody id="productTable">
        <?php $index = 1; while ($row = mysqli_fetch_assoc($result)): ?>
          <tr data-category-id="<?= htmlspecialchars($row['loaiSanPham']) ?>">
            <td><?= $index++ ?></td>
            <td><img src="../../<?= htmlspecialchars($row['hinhanh']) ?>" class="table-img" width="60" onerror="this.src='https://via.placeholder.com/60';"></td>
            <td><?= htmlspecialchars($row['tenSanPham']) ?></td>
            <td><?= number_format($row['gia'], 0, ',', '.') ?>₫</td>
            <td><?= htmlspecialchars($row['loaiSanPham']) ?></td>
            <td><?= (int)$row['tonKho'] ?></td>
            <td class="action-buttons">
              <button class="btn-sm btn-warning" onclick="openEditModal(<?= htmlspecialchars(json_encode($row)) ?>)">Sửa</button>
              <button class="btn-sm btn-danger" onclick="confirmDelete(<?= $row['idSanPham'] ?>)">Xoá</button>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <div id="addProductModal" class="modal" style="display:none;">
  <div class="modal-content">
    <h3>Thêm sản phẩm</h3>
    <form action="Controller/ProductCTL.php?action=add" method="POST" enctype="multipart/form-data">
      <input type="text" name="tenSanPham" placeholder="Tên sản phẩm" required><br>
      <select name="loaiSanPham">
        <option value="Đồ ăn">Đồ ăn</option>
        <option value="Nước Uống">Nước Uống</option>
      </select><br>
      <input type="number" name="gia" placeholder="Giá" required><br>
      <input type="file" name="hinhanh"><br>
      <input type="number" name="tonKho" placeholder="Số lượng tồn kho" required><br>
      <div class="modal-buttons">
      <button type="submit">Thêm</button>
      <button type="button" onclick="closeModal()">Hủy</button>
      </div>
    </form>
  </div>  
  </div>

  <div id="editProductModal" class="modal" style="display:none;">
  <div class="modal-content">
    <h3>Sửa sản phẩm</h3>
    <form action="Controller/ProductCTL.php?action=edit" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="idSanPham" id="editId">
      <input type="text" name="tenSanPham" id="editTen" required><br>
      <select name="loaiSanPham" id="editLoai">
        <option value="Đồ ăn">Đồ ăn</option>
        <option value="Nước Uống">Nước Uống</option>
      </select><br>
      <input type="number" name="gia" id="editGia" required><br>
      <input type="file" name="hinhanh"><br>
      <input type="number" name="tonKho" id="editTonKho" required><br>
      <div class="modal-buttons">
      <button type="submit">Cập nhật</button>
      <button type="button" onclick="closeModal()">Hủy</button>
      </div>
    </form>
  </div>
  </div>

  <div id="deleteModal" class="modal" style="display:none;">
  <div class="modal-content">
    <h4>Bạn có chắc chắn muốn xóa sản phẩm này không?</h4>
    <div class="modal-buttons">
    <a id="deleteLink" href="#"><button class="btn btn-danger">Xoá</button></a>
    <button onclick="closeModal()">Hủy</button>
    </div>
  </div>
  </div>
<script>

function openAddModal() {
  document.getElementById("addProductModal").style.display = "flex";
}


function openEditModal(data) {
  document.getElementById("editId").value = data.idSanPham;
  document.getElementById("editTen").value = data.tenSanPham;
  document.getElementById("editLoai").value = data.loaiSanPham;
  document.getElementById("editGia").value = data.gia;
  document.getElementById("editTonKho").value = data.tonKho;
  document.getElementById("editProductModal").style.display = "flex";
}



function confirmDelete(id) {
  document.getElementById("deleteLink").href = `Controller/ProductCTL.php?action=delete&id=${id}`;
  document.getElementById("deleteModal").style.display = "flex";
}


function closeModal() {
  document.querySelectorAll(".modal").forEach(modal => modal.style.display = "none");
}


document.getElementById("categoryFilter").addEventListener("change", function () {
  const val = this.value;
  document.querySelectorAll("#productTable tr").forEach(row => {
    row.style.display = (val === "all" || row.dataset.categoryId === val) ? "" : "none";
  });
});


document.getElementById("searchInput").addEventListener("keyup", function () {
  const keyword = this.value.toLowerCase();
  document.querySelectorAll("#productTable tr").forEach(row => {
    const name = row.children[2].textContent.toLowerCase();
    row.style.display = name.includes(keyword) ? "" : "none";
  });
});
</script>

<?php
$pageContent = ob_get_clean();
include(__DIR__ . '/../../components/layout/layoutadmin.php');
?>
</body>
</html>