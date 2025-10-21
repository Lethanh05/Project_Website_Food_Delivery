<?php
session_start();

// Hủy toàn bộ session
session_unset();
session_destroy();

setcookie("remember", "", time() - 3600, "/");

// Chuyển hướng về trang đăng nhập
header("Location: ../../login.php");
exit();
?>
