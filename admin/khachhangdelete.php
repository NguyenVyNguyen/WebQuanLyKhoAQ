<?php
include "header.php";
include "leftside.php";
include "class/khachhang_class.php";

$khachhang = new khachhang();

if (!isset($_GET['khachhang_id']) || $_GET['khachhang_id'] == NULL) {
    echo "<script>window.location='khachhanglist.php';</script>";
} else {
    $khachhang_id = $_GET['khachhang_id'];
    $delete = $khachhang->delete_khachhang($khachhang_id);
    echo "<script>window.location='khachhanglist.php';</script>";
}
?>
