<?php
session_start();
include "header.php";
include "leftside.php";
include "class/hoadonxuat_class.php";
include "class/product_class.php";

$hoadonxuat = new Hoadonxuat();
$product = new product();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $khachhang_id = isset($_POST['khachhang_id']) ? intval($_POST['khachhang_id']) : null;
    $nhanvien_id = isset($_POST['nhanvien_id']) ? intval($_POST['nhanvien_id']) : null;
    $selected_variant_ids = isset($_POST['variant_ids']) ? $_POST['variant_ids'] : [];
    $quantities = isset($_POST['quantities']) ? $_POST['quantities'] : [];

    if ($khachhang_id === null || $nhanvien_id === null || empty($selected_variant_ids)) {
        echo "<script>alert('Lỗi: Thiếu thông tin khách hàng, nhân viên hoặc sản phẩm!'); window.location.href='xuathang_final.php';</script>";
        exit;
    }

    $selected_variants_data_result = $product->get_products_variants_by_ids($selected_variant_ids);
    $variant_details = [];
    if ($selected_variants_data_result) {
        while ($row = $selected_variants_data_result->fetch_assoc()) {
            $variant_details[$row['bienthe_id']] = $row; 
        }
    }

    $hoadonxuat_id = $hoadonxuat->insert_hoadonxuat($nhanvien_id, $khachhang_id);

    if ($hoadonxuat_id) {
        $all_details_inserted = true;
        foreach ($selected_variant_ids as $bienthe_id) {
            $soluong = isset($quantities[$bienthe_id]) ? intval($quantities[$bienthe_id]) : 0;
            

            $variant = $variant_details[$bienthe_id] ?? null;

            if ($variant) {
                $sanpham_id = $variant['sanpham_id']; 
                $dongia = $variant['sanpham_gia']; 
                $thanhtien = $soluong * $dongia; 

                if ($soluong > 0 && $dongia > 0) {
                    $result_update_stock = $hoadonxuat->update_soluong_tonkho($bienthe_id, $soluong);
                    $result_chitiet = $hoadonxuat->insert_chitiet($hoadonxuat_id, $bienthe_id, $sanpham_id, $soluong, $dongia, $thanhtien); 
                    if (!$result_chitiet) {
                        $all_details_inserted = false;
                        break;
                    }
                }
            } else {
                error_log("Không tìm thấy thông tin cho bienthe_id: " . $bienthe_id);
                $all_details_inserted = false;
                break;
            }
        }
        
        if ($all_details_inserted) {
            echo "<script>alert('Tạo đơn hàng thành công!'); window.location.href='hoadonxuat.php';</script>";
        } else {
            echo "<script>alert('Lỗi khi thêm chi tiết đơn hàng! Vui lòng kiểm tra log.'); window.location.href='xuathang.php';</script>";
        }
    } else {
        echo "<script>alert('Lỗi khi thêm hóa đơn xuất!'); window.location.href='xuathang.php';</script>";
    }
} else {
    echo "<script>alert('Truy cập không hợp lệ!'); window.location.href='xuathang.php';</script>";
}
?>

<div class="admin-content-right">
    <div class="admin-form-container">
        <h2>Đơn Hàng Đang Được Xử Lý...</h2>
    </div>
</div>
</section>
</body>
</html>
