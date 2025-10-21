<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
function checkRole($roles = []) {
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $roles)) {
        header("Location: /ltw/config/unauthorized.php");
        exit;
    }
}
?>