<?php
require_once __DIR__ . '/../../../connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
  $action = $_POST['action'];
  $fullName = $_POST['fullName'] ?? '';
  $email = $_POST['email'] ?? '';
  $phone = $_POST['phone'] ?? '';
  $role = $_POST['role'] ?? 'user';
  if ($action === 'add') {
    $username = $_POST['username'] ?? '';
    $password = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);
    $sql = "INSERT INTO user (username, password, fullName, email, phone, role) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "ssssss", $username, $password, $fullName, $email, $phone, $role);
    mysqli_stmt_execute($stmt);
  } elseif ($action === 'edit' && isset($_POST['idUser'])) {
    $idUser = $_POST['idUser'];
    $sql = "UPDATE user SET fullName=?, email=?, phone=?, role=? WHERE idUser=?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "ssssi", $fullName, $email, $phone, $role, $idUser);
    mysqli_stmt_execute($stmt);
  } elseif ($action === 'delete' && isset($_POST['idUser'])) {
    $idUser = (int) $_POST['idUser'];
    $stmt = mysqli_prepare($link, "DELETE FROM user WHERE idUser = ?");
    mysqli_stmt_bind_param($stmt, "i", $idUser);
    mysqli_stmt_execute($stmt);
  }
  header("Location: ../MnCustomer.php");
  exit;
}
header("Location: ../MnCustomer.php");
exit;
