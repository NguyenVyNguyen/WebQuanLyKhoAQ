<?php
include "class/product_class.php";
include "class/database.php";

$product = new product();

if (isset($_GET['sanpham_id'])) {
    $sanpham_id = $_GET['sanpham_id'];
    $delete_result = $product->delete_product_all($sanpham_id);

    if ($delete_result) {
        echo "<script>alert('Xóa sản phẩm và tất cả biến thể thành công!'); window.location.href = 'productlist.php';</script>";
        exit;
    } else {
        echo "<script>alert('Lỗi: Không thể xóa sản phẩm. Vui lòng thử lại.'); window.location.href = 'productlist.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Lỗi: Thiếu ID sản phẩm!'); window.location.href = 'productlist.php';</script>";
    exit;
}
?>
