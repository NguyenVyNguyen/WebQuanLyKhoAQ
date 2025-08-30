<?php
session_start();
include "header.php";
include "leftside.php";
include "class/hoadonxuat_class.php";
include "class/product_class.php";

$hoadonxuat = new Hoadonxuat();
$product = new product(); 

$hoadonxuat_id = isset($_GET['hoadonxuat_id']) ? intval($_GET['hoadonxuat_id']) : null;

if ($hoadonxuat_id === null || $hoadonxuat_id <= 0) {
    echo "<script>alert('ID hóa đơn không hợp lệ!'); window.location.href='hoadonxuat.php';</script>";
    exit;
}

$hoadon_info = $hoadonxuat->get_donhang_info($hoadonxuat_id); 
$hoadon_details = $hoadonxuat->get_chitiet_donhang($hoadonxuat_id); 

if (!$hoadon_info) {
    echo "<script>alert('Không tìm thấy hóa đơn!'); window.location.href='hoadonxuat.php';</script>";
    exit;
}

$tong_tien_hoa_don = $hoadonxuat->get_tongtien_donhang($hoadonxuat_id);
?>
<div class="admin-content-right">
    <div class="admin-form-container">
        <h2>CHI TIẾT HÓA ĐƠN XUẤT #<?php echo htmlspecialchars($hoadonxuat_id); ?></h2>
        
        <div class="order-info">
            <p><strong>Ngày Xuất:</strong> <?php echo htmlspecialchars($hoadon_info['ngayxuat']); ?></p>
            <p><strong>Khách Hàng:</strong> <?php echo htmlspecialchars($hoadon_info['khachhang_ten']); ?></p>
            <p><strong>Nhân Viên Bán:</strong> <?php echo htmlspecialchars($hoadon_info['nhanvien_ten']); ?></p>
        </div>

        <h3>Sản phẩm trong hóa đơn:</h3>
        <table class="product-table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Mã SP</th>
                    <th>Tên Sản Phẩm</th>
                    <th>Màu Sắc</th>
                    <th>Kích Thước</th>
                    <th>Số Lượng</th>
                    <th>Đơn Giá</th>
                    <th>Thành Tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($hoadon_details && $hoadon_details->num_rows > 0) {
                    $i = 0;
                    while ($detail = $hoadon_details->fetch_assoc()) {
                        $i++;
                ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo htmlspecialchars($detail['sanpham_ma'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($detail['sanpham_tieude'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($detail['mau_ten'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($detail['size_ten'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($detail['soluong']); ?></td>
                            <td><?php echo number_format($detail['dongia'], 0, ',', '.') ?> VNĐ</td>
                            <td><?php echo number_format($detail['thanhtien'], 0, ',', '.') ?> VNĐ</td>
                        </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="8" style="text-align:center;">Không có chi tiết sản phẩm nào.</td></tr>';
                }
                ?>
            </tbody>
        </table>
        
        <div class="total-amount">
            Tổng Cộng: <?php echo number_format($tong_tien_hoa_don ?? 0, 0, ',', '.') ?> VNĐ
        </div>

        <a href="hoadonxuat.php" class="back-button">Quay lại danh sách hóa đơn</a>
    </div>
</div>

</section>
</body>
</html>
