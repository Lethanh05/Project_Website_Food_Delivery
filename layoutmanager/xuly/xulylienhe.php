<?php
include('../../connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $link->query("UPDATE lienhe SET status = 'Đã xử lí' WHERE idLienHe = $id");
    header("Location: ../quanlylienhe.php");
    exit();
}
?>
