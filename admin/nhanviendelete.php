<?php
include "header.php";
include "leftside.php";
include "class/nhanvien_class.php";

$nhanvien = new nhanvien();

if (!isset($_GET['nhanvien_id']) || $_GET['nhanvien_id'] == NULL) {
    echo "<script>window.location = 'nhanvienlist.php'</script>";
} else {
    $nhanvien_id = $_GET['nhanvien_id'];
    $delete_nhanvien = $nhanvien->delete_nhanvien($nhanvien_id);
    header('Location:nhanvienlist.php');
}
?>
