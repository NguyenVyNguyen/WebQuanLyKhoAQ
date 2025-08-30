<?php
include "header.php";
include "leftside.php";
include "class/nhanvien_class.php";

$nhanvien = new nhanvien;

$items_per_page = 15;
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

$search_query = isset($_GET['q']) ? $_GET['q'] : '';

$total_nhanvien = $nhanvien->get_total_nhanvien_count($search_query);
$total_pages = ceil($total_nhanvien / $items_per_page);

$show_nhanvien = $nhanvien->show_nhanvienP($items_per_page, $offset, $search_query);
?>
<style>
    
</style>
<div class="admin-content-right">
    <div class="admin-form-container">
        <h1 style="text-align: center; font-size: 28px; margin-bottom: 20px;">NHÂN VIÊN</h1>

        <div class="actions-container">
            <div>
                <a href="nhanvienadd.php" 
                   style="background: #007bff; color: #fff; padding: 8px 16px; text-decoration: none; border-radius: 5px;">
                   Thêm
                </a>
            </div>
            <div class="search-container">
                <form action="nhanvien.php" method="GET">
                    <input type="text" name="q" placeholder="Tìm kiếm nhân viên..." value="<?php echo htmlspecialchars($search_query); ?>">
                    <button type="submit">Tìm kiếm</button>
                </form>
            </div>
        </div>

        <div class="table-content">
            <table class="a-table">
                <tr>
                    <th>Stt</th>
                    <th>ID</th>
                    <th>Họ tên</th>
                    <th>Điện thoại</th>
                    <th>Email</th>
                    <th>Địa chỉ</th>
                    <th>Tài khoản</th>
                    <th>Mật khẩu</th>
                    <th>Quyền</th>
                    <th>Trạng thái</th>
                    <th>Tùy chỉnh</th>
                </tr>
                <?php
                if ($show_nhanvien && $show_nhanvien->num_rows > 0) {
                    $i = $offset;
                    while ($result = $show_nhanvien->fetch_assoc()) {
                        $i++;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($i); ?></td>
                    <td><?php echo htmlspecialchars($result['nhanvien_id']); ?></td>
                    <td><?php echo htmlspecialchars($result['nhanvien_ten']); ?></td>
                    <td><?php echo htmlspecialchars($result['nhanvien_dienthoai']); ?></td>
                    <td><?php echo htmlspecialchars($result['nhanvien_email']); ?></td>
                    <td><?php echo htmlspecialchars($result['nhanvien_diachi']); ?></td>
                    <td><?php echo htmlspecialchars($result['account_username']); ?></td>
                    <td><?php echo htmlspecialchars($result['account_password']); ?></td>
                    <td><?php echo htmlspecialchars($result['account_role']); ?></td>
                    <td><?php echo htmlspecialchars($result['nhanvien_isactive']) ? "Hoạt động" : "Khóa"; ?></td>
                    <td>
                        <a href="nhanvienedit.php?nhanvien_id=<?php echo htmlspecialchars($result['nhanvien_id']); ?>">Sửa</a> | 
                        <a href="nhanviendelete.php?nhanvien_id=<?php echo htmlspecialchars($result['nhanvien_id']); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa nhân viên này không?');">Xóa</a>
                    </td>
                </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="11" style="text-align:center;">Không có nhân viên nào được tìm thấy.</td></tr>';
                }
                ?>
            </table>
        </div>
        <div class="pagination">
            <?php if ($total_pages > 1) : ?>
                <?php $query_params = http_build_query(['q' => $search_query]); ?>
                <?php if ($current_page > 1) : ?>
                    <a href="?page=<?php echo $current_page - 1; ?>&<?php echo $query_params; ?>">Trước</a>
                <?php endif; ?>

                <?php for ($p = 1; $p <= $total_pages; $p++) : ?>
                    <a href="?page=<?php echo $p; ?>&<?php echo $query_params; ?>" class="<?php echo $p === $current_page ? 'current-page' : ''; ?>"><?php echo $p; ?></a>
                <?php endfor; ?>

                <?php if ($current_page < $total_pages) : ?>
                    <a href="?page=<?php echo $current_page + 1; ?>&<?php echo $query_params; ?>">Sau</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
</section>
<script src="js/script.js"></script>
</body>
</html>
