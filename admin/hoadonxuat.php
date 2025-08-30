<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php
include "header.php";
include "leftside.php";
include "class/hoadonxuat_class.php";

$hoadonxuat = new Hoadonxuat();

$records_per_page = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page <= 0) {
    $page = 1;
}
$start_from = ($page - 1) * $records_per_page;

$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';

$total_records = $hoadonxuat->get_all_donhang_count($search_query);
$total_pages = ceil($total_records / $records_per_page);

$all_hoadonxuat = $hoadonxuat->get_all_donhang($start_from, $records_per_page, $search_query);
?>
<div class="admin-content-right">
    <div class="admin-form-container">
        <h2>DANH SÁCH HÓA ĐƠN XUẤT</h2>

        <div class="search-container">
            <form action="hoadonxuat.php" method="get">
                <input type="text" name="search" placeholder="Tìm kiếm hóa đơn..." value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit">Tìm kiếm</button>
            </form>
        </div>

        <table class="product-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ngày Xuất</th>
                    <th>Khách Hàng</th>
                    <th>Nhân Viên</th>
                    <th>Tổng Tiền</th>
                    <th>Tùy Chọn</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($all_hoadonxuat && $all_hoadonxuat->num_rows > 0) {
                    while ($row = $all_hoadonxuat->fetch_assoc()) {
                ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['hoadonxuat_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['ngayxuat']); ?></td>
                            <td><?php echo htmlspecialchars($row['khachhang_ten']); ?></td>
                            <td><?php echo htmlspecialchars($row['nhanvien_ten']); ?></td>
                            <td><?php echo number_format($row['tongtien'], 0, ',', '.') ?> VNĐ</td>
                            <td>
                                <a href="hoadonchitiet.php?hoadonxuat_id=<?php echo htmlspecialchars($row['hoadonxuat_id']); ?>">Xem</a> | 
                                <a href="hoadonxuatdelete.php?hoadonxuat_id=<?php echo htmlspecialchars($row['hoadonxuat_id']); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa không?');">Xóa</a>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="6" style="text-align:center;">Không tìm thấy hóa đơn nào.</td></tr>';
                }
                ?>
            </tbody>
        </table>

        <div class="pagination">
            <?php if ($total_pages > 1) : ?>
                <?php if ($page > 1) : ?>
                    <a href="hoadonxuat.php?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search_query); ?>">Trang trước</a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                    <a class="<?php echo ($i == $page) ? 'active' : ''; ?>" href="hoadonxuat.php?page=<?php echo $i; ?>&search=<?php echo urlencode($search_query); ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
                <?php if ($page < $total_pages) : ?>
                    <a href="hoadonxuat.php?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search_query); ?>">Trang sau</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

</section>
</body>
</html>