<?php
include "header.php";
include "leftside.php";
include "class/product_class.php";
include "class/khachhang_class.php";
include "class/nhanvien_class.php";

$product = new product();
$khachhang = new khachhang();
$nhanvien = new NhanVien();

$khachhang_id = $_POST['khachhang_id'] ?? null;
$nhanvien_id = $_POST['nhanvien_id'] ?? null;
$bienthe_ids = $_POST['bienthe_ids'] ?? [];

$khachhang_info = $khachhang->get_khachhang_by_id($khachhang_id);
$khachhang_ten = $khachhang_info ? $khachhang_info['khachhang_ten'] : 'Không xác định';

$nhanvien_info = $nhanvien->get_nhanvien_by_id($nhanvien_id); 
$nhanvien_ten = $nhanvien_info ? $nhanvien_info['nhanvien_ten'] : 'Không xác định';


$selected_variants_info = [];

if (!empty($bienthe_ids)) {
    foreach ($bienthe_ids as $bienthe_id) {
        $variant_info = $product->get_variant_info($bienthe_id); 
        if ($variant_info) {
            $selected_variants_info[] = $variant_info;
        }
    }
}
?>
<div class="admin-content-right">
    <div class="admin-form-container">
        <h2>ĐƠN HÀNG ĐANG TẠO</h2>
        <?php if (!empty($selected_variants_info)): ?>
            <div class="order-info">
                <p><strong>Khách hàng:</strong> <?php echo htmlspecialchars($khachhang_ten); ?></p>
                <p><strong>Nhân viên:</strong> <?php echo htmlspecialchars($nhanvien_ten); ?></p>
            </div>
            <form action="xacnhan.php" method="POST">
                <input type="hidden" name="khachhang_id" value="<?php echo htmlspecialchars($khachhang_id); ?>">
                <input type="hidden" name="nhanvien_id" value="<?php echo htmlspecialchars($nhanvien_id); ?>">

                <?php foreach ($selected_variants_info as $variant): ?>
                    <input type="hidden" name="variant_ids[]" value="<?php echo htmlspecialchars($variant['bienthe_id']); ?>">
                <?php endforeach; ?>

                <table class="product-table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Mã sản phẩm</th>
                            <th>Tên sản phẩm</th>
                            <th>Màu</th>
                            <th>Size</th>
                            <th>Giá</th>
                            <th>Số lượng tồn</th>
                            <th>Số lượng mua</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        foreach ($selected_variants_info as $variant) {
                            $i++;
                        ?>
                            <tr>
                                <td><?php echo $i ?></td>
                                <td><?php echo htmlspecialchars($variant['sanpham_ma'] ?? '') ?></td>
                                <td><?php echo htmlspecialchars($variant['sanpham_tieude'] ?? '') ?></td>
                                <td><?php echo htmlspecialchars($variant['mau_ten'] ?? '') ?></td>
                                <td><?php echo htmlspecialchars($variant['size_ten'] ?? '') ?></td>
                                <td><?php echo number_format($variant['sanpham_gia'] ?? 0) ?> VNĐ</td>
                                <td><?php echo htmlspecialchars($variant['soluong'] ?? '') ?></td>
                                <td>
                                    <input type="number" name="quantities[<?php echo $variant['bienthe_id'] ?>]" min="1" max="<?php echo htmlspecialchars($variant['soluong'] ?? '') ?>" value="1" required>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
                <button class="btn-submit" type="submit">Xác nhận đơn hàng</button>
            </form>
        <?php else: ?>
            <p style="text-align: center;">Không có sản phẩm nào được chọn.</p>
        <?php endif; ?>
    </div>
</div>

</section>
</body>
</html>