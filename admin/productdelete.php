<?php
include "class/product_class.php";

$product = new product();

if (isset($_GET['bienthe_id'])) {
    $bienthe_id = $_GET['bienthe_id'];

    $delete = $product->delete_product($bienthe_id);
    
    if ($delete) {
        echo "<script>alert('Xóa sản phẩm thành công!'); window.location.href = 'productlist.php';</script>";
    } else {
        echo "<script>alert('Lỗi: Không thể xóa sản phẩm. Vui lòng thử lại.'); window.location.href = 'productlist.php';</script>";
    }
} else {
    echo "<script>alert('ID sản phẩm không hợp lệ!'); window.location.href = 'productlist.php';</script>";
}
?>
