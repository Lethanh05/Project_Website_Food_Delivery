<?php
require_once __DIR__ . '/../../../connect.php';

$orderCountResult = mysqli_query($link, "SELECT COUNT(*) AS total FROM donhang WHERE trangThai = 'Chờ xác nhận'");
$orderCount = mysqli_fetch_assoc($orderCountResult)['total'] ?? 0;
$customerCountResult = mysqli_query($link, "SELECT COUNT(*) AS total FROM user WHERE role = 'user' AND created_at >= NOW() - INTERVAL 7 DAY");
$customerCount = mysqli_fetch_assoc($customerCountResult)['total'] ?? 0;
?>
