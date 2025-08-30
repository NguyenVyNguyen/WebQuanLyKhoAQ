<?php
include "header.php";
include "leftside.php";
include "class/khachhang_class.php";

$khachhang = new khachhang();

$items_per_page = 10;
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

$search_query = isset($_GET['q']) ? $_GET['q'] : '';

$total_khachhang = $khachhang->get_total_khachhang_count($search_query);
$total_pages = ceil($total_khachhang / $items_per_page);

$show_khachhang = $khachhang->show_khachhangP($search_query, $items_per_page, $offset);
?>
<div class="admin-content-right">
    <div class="admin-form-container">
        <h1 style="text-align: center; font-size: 28px; margin-bottom: 20px;">KHÁCH HÀNG</h1>

        <div class="actions-container">
            <div>
                <a href="khachhangadd.php" 
                   style="background: #007bff; color: #fff; padding: 8px 16px; text-decoration: none; border-radius: 5px;">
                    Thêm
                </a>
            </div>
            <div class="search-container">
                <form action="khachhanglist.php" method="GET">
                    <input type="text" name="q" placeholder="Tìm kiếm khách hàng..." value="<?php echo htmlspecialchars($search_query); ?>">
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
                    <th>Tùy chỉnh</th>
                </tr>
                <?php
                if ($show_khachhang && $show_khachhang->num_rows > 0) {
                    $i = $offset;
                    while ($result = $show_khachhang->fetch_assoc()) {
                        $i++;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($i); ?></td>
                    <td><?php echo htmlspecialchars($result['khachhang_id']); ?></td>
                    <td><?php echo htmlspecialchars($result['khachhang_ten']); ?></td>
                    <td><?php echo htmlspecialchars($result['khachhang_dienthoai']); ?></td>
                    <td><?php echo htmlspecialchars($result['khachhang_email']); ?></td>
                    <td><?php echo htmlspecialchars($result['khachhang_diachi']); ?></td>
                    <td>
                        <a href="khachhangedit.php?khachhang_id=<?php echo htmlspecialchars($result['khachhang_id']); ?>">Sửa</a> | 
                        <a href="khachhangdelete.php?khachhang_id=<?php echo htmlspecialchars($result['khachhang_id']); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa khách hàng này không?');">Xóa</a>
                    </td>
                </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="7" style="text-align:center;">Không có khách hàng nào được tìm thấy.</td></tr>';
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
