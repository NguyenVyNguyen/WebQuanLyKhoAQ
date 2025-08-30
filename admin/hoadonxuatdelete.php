<?php
session_start();
include "class/hoadonxuat_class.php";

$hoadonxuat = new Hoadonxuat();
$hoadonxuat_id = isset($_GET['hoadonxuat_id']) ? intval($_GET['hoadonxuat_id']) : null;

if ($hoadonxuat_id === null || $hoadonxuat_id <= 0) {
    echo "<script>alert('ID hóa đơn không hợp lệ!'); window.location.href='hoadonxuat.php';</script>";
    exit;
}
$delete_result = $hoadonxuat->delete_hoadonxuat($hoadonxuat_id);

if ($delete_result) {
    echo "<script>alert('Xóa hóa đơn thành công!'); window.location.href='hoadonxuat.php';</script>";
} else {
    echo "<script>alert('Lỗi khi xóa hóa đơn hoặc chi tiết liên quan!'); window.location.href='hoadonxuat.php';</script>";
}
exit;
?>
