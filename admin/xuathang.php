<?php
include "header.php";
include "leftside.php";
include "class/product_class.php";
include "class/khachhang_class.php";
include "class/nhanvien_class.php";

$product = new product();
$khachhang = new khachhang();
$nhanvien = new NhanVien();

$show_products_variants = $product->show_products_variant();
$show_khachhangs = $khachhang->show_khachhang();
$show_nhanviens = $nhanvien->show_nhanvien();

$grouped_products = [];
if ($show_products_variants) {
    while ($result = $show_products_variants->fetch_assoc()) {
        $sanpham_id = $result['sanpham_id'];
        if (!isset($grouped_products[$sanpham_id])) {
            $grouped_products[$sanpham_id] = [
                'sanpham_ma' => $result['sanpham_ma'],
                'sanpham_tieude' => $result['sanpham_tieude'],
                'sanpham_gia' => $result['sanpham_gia'],
                'variants' => []
            ];
        }
        $grouped_products[$sanpham_id]['variants'][] = [
            'bienthe_id' => $result['bienthe_id'],
            'mau_ten' => $result['mau_ten'],
            'size_ten' => $result['size_ten'],
            'soluong' => $result['soluong']
        ];
    }
}
?>
<div class="admin-content-right">
    <div class="admin-form-container">
        <h2>TẠO ĐƠN XUẤT HÀNG</h2>
        <form action="xemdonhang.php" method="POST">
            <div class="form-group">
                <label for="khachhang">Khách hàng mua</label>
                <select id="khachhang" name="khachhang_id" required>
                    <option value="#">-- Chọn --</option>
                    <?php
                    if ($show_khachhangs) {
                        while ($khachhang_result = $show_khachhangs->fetch_assoc()) {
                            echo '<option value="' . htmlspecialchars($khachhang_result['khachhang_id']) . '">' . htmlspecialchars($khachhang_result['khachhang_ten']) . '</option>';
                        }
                    } else {
                        echo '<option value="">Không có khách hàng</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="nhanvien">Nhân viên bán</label>
                <select id="nhanvien" name="nhanvien_id" required>
                    <option value="#">-- Chọn --</option>
                    <?php
                    if ($show_nhanviens) {
                        while ($nhanvien_result = $show_nhanviens->fetch_assoc()) {
                            echo '<option value="' . htmlspecialchars($nhanvien_result['nhanvien_id']) . '">' . htmlspecialchars($nhanvien_result['nhanvien_ten']) . '</option>';
                        }
                    } else {
                        echo '<option value="">Không có nhân viên</option>';
                    }
                    ?>
                </select>
            </div>

            <table class="product-table">
                <thead>
                    <tr>
                        <th>Chọn</th> <th>Mã SP</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Màu</th>
                        <th>Size</th>
                        <th>Số lượng tồn</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($grouped_products)) {
                        $i = 0;
                        foreach ($grouped_products as $sanpham_id => $product_data) {
                            $i++;
                            // Hiển thị các biến thể con để có thể chọn
                            foreach ($product_data['variants'] as $variant) {
                    ?>
                                <tr class="variant-row">
                                    <td>
                                        <input type="checkbox" name="bienthe_ids[]" value="<?php echo $variant['bienthe_id']; ?>">
                                    </td>
                                    <td><?php echo htmlspecialchars($product_data['sanpham_ma'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($product_data['sanpham_tieude'] ?? ''); ?></td>
                                    <td><?php echo number_format($product_data['sanpham_gia']); ?> VNĐ</td>
                                    <td><?php echo htmlspecialchars($variant['mau_ten'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($variant['size_ten'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($variant['soluong'] ?? ''); ?></td>
                                </tr>
                    <?php
                            }
                        }
                    } else {
                        echo '<tr><td colspan="7" style="text-align:center;">Không có sản phẩm nào.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
            
            <button class="btn-submit" type="submit">Xem đơn đang tạo</button>
        </form>
    </div>
</div>

</section>
</body>
</html>