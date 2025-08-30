<?php
include "header.php";
include "leftside.php";
include "class/product_class.php";

$product = new product();

$items_per_page = 15;
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

$search_query = isset($_GET['q']) ? $_GET['q'] : '';

$total_products = $product->get_total_variants_count($search_query);

$total_pages = ceil($total_products / $items_per_page);

$show_products_variants = $product->show_products_variantP($search_query, $items_per_page, $offset);

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
            'mau_ten' => $result['mau_ten'],
            'size_ten' => $result['size_ten'],
            'soluong' => $result['soluong'],
            'bienthe_id' => $result['bienthe_id']
        ];
    }
}
?>
<div class="admin-content-right">
    <div class="admin-form-container">
        <h1>DANH SÁCH SẢN PHẨM</h1>
        <div class="actions-container">
            <div style="margin-bottom: 10px; text-align: left;">
                <a href="khachhangadd.php" 
                style="background: #007bff; color: #fff; padding: 8px 16px; text-decoration: none; border-radius: 5px;">
                    Thêm
                </a>
            </div>
            <div class="search-container">
                <form action="productlist.php" method="GET">
                    <input type="text" name="q" placeholder="Tìm kiếm sản phẩm..." value="<?php echo htmlspecialchars($search_query); ?>">
                    <button type="submit">Tìm kiếm</button>
                </form>
            </div>
        </div>
        <table class="a-table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Mã sản phẩm</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá</th>
                    <th>Màu</th>
                    <th>Size</th>
                    <th>Số lượng tồn</th>
                    <th>Hành động biến thể</th>
                    <th>Hành động sản phẩm</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($grouped_products)) {
                    $i = $offset;
                    foreach ($grouped_products as $sanpham_id => $product_data) {
                        $i++;
                        $row_count = count($product_data['variants']);
                        $first_variant = $product_data['variants'][0] ?? ['mau_ten' => null, 'size_ten' => null, 'soluong' => null, 'bienthe_id' => null];
                ?>
                        <tr>
                            <td class="rowspan-cell" rowspan="<?php echo $row_count; ?>"><?php echo htmlspecialchars($i); ?></td>
                            <td class="rowspan-cell" rowspan="<?php echo $row_count; ?>"><?php echo htmlspecialchars($product_data['sanpham_ma'] ?? ''); ?></td>
                            <td class="rowspan-cell" rowspan="<?php echo $row_count; ?>"><?php echo htmlspecialchars($product_data['sanpham_tieude'] ?? ''); ?></td>
                            <td class="rowspan-cell" rowspan="<?php echo $row_count; ?>"><?php echo number_format($product_data['sanpham_gia'] ?? 0); ?> VNĐ</td>
                            <td><?php echo htmlspecialchars($first_variant['mau_ten'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($first_variant['size_ten'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($first_variant['soluong'] ?? ''); ?></td>
                            <td>
                                <a href="productedit.php?bienthe_id=<?php echo htmlspecialchars($first_variant['bienthe_id'] ?? ''); ?>">Sửa</a> |
                                <a href="productdelete.php?bienthe_id=<?php echo htmlspecialchars($first_variant['bienthe_id'] ?? ''); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa biến thể này không?');">Xóa</a>
                            </td>
                            <td class="rowspan-cell" rowspan="<?php echo $row_count; ?>">
                                <a href="thembienthe.php?sanpham_id=<?php echo htmlspecialchars($sanpham_id ?? ''); ?>">Thêm biến thể</a> |
                                <a href="productdeleteall.php?sanpham_id=<?php echo htmlspecialchars($sanpham_id ?? ''); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa toàn bộ sản phẩm này không?');">Xóa sản phẩm</a>
                            </td>
                        </tr>

                        <?php
                        for ($j = 1; $j < $row_count; $j++) {
                            $variant = $product_data['variants'][$j];
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($variant['mau_ten'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($variant['size_ten'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($variant['soluong'] ?? ''); ?></td>
                                <td>
                                    <a href="productedit.php?bienthe_id=<?php echo htmlspecialchars($variant['bienthe_id'] ?? ''); ?>">Sửa</a> |
                                    <a href="productdelete.php?bienthe_id=<?php echo htmlspecialchars($variant['bienthe_id'] ?? ''); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa biến thể này không?');">Xóa</a>
                                </td>
                            </tr>
                        <?php
                        }
                    }
                } else {
                    echo '<tr><td colspan="9" style="text-align:center;">Không có sản phẩm nào.</td></tr>';
                }
                ?>
            </tbody>
        </table>
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
</body>
</html>
