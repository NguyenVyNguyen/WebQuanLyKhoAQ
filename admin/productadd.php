<?php
include_once "header.php";
include_once "leftside.php";
include "class/product_class.php";

$product = new product();
$cartegory = $product->show_cartegory();

$message = ""; 
$sanpham_ma = "";
$sanpham_tieude = "";
$sanpham_gia = "";
$danhmuc_id = "";
$variants_data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sanpham_ma = $_POST['sanpham_ma'];
    $sanpham_tieude = $_POST['sanpham_tieude'];
    $sanpham_gia = $_POST['sanpham_gia'];
    $danhmuc_id = $_POST['danhmuc_id'];
    $variants_data = isset($_POST['variant_data']) ? $_POST['variant_data'] : [];

    if ($product->check_product_code($sanpham_ma)) {
        $message = "Lỗi: Mã sản phẩm đã tồn tại. Vui lòng nhập mã khác.";
    } else {
        $variants = [];
        foreach ($variants_data as $color_data) {
            $mau = $color_data['mau_ten'];
            if (isset($color_data['sizes']) && isset($color_data['soluongs'])) {
                for ($i = 0; $i < count($color_data['sizes']); $i++) {
                    $variants[] = [
                        'mau_ten'   => $mau,
                        'size_ten'  => $color_data['sizes'][$i],
                        'soluong'   => $color_data['soluongs'][$i]
                    ];
                }
            }
        }

        $product_data = [
            'sanpham_ma'     => $sanpham_ma,
            'danhmuc_id'     => $danhmuc_id,
            'sanpham_tieude' => $sanpham_tieude,
            'sanpham_gia'    => $sanpham_gia,
            'variants'       => $variants
        ];

        $insert_product = $product->insert_product($product_data);
        $message = "Thêm sản phẩm thành công!";

        $sanpham_ma = "";
        $sanpham_tieude = "";
        $sanpham_gia = "";
        $danhmuc_id = "";
        $variants_data = [];
    }
}
?>

<div class="admin-content-right">
    <div class="admin-add-content">
        <h2>Thêm sản phẩm</h2>
        
        <?php if (!empty($message)) : ?>
            <div class="message" style="color: red; margin-bottom: 15px;">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="">Mã sản phẩm <span style="color:red">*</span></label>
            <input type="text" name="sanpham_ma" placeholder="Nhập mã sản phẩm" value="<?php echo htmlspecialchars($sanpham_ma); ?>" required>

            <label for="">Tên sản phẩm <span style="color:red">*</span></label>
            <input type="text" name="sanpham_tieude" placeholder="Nhập tên sản phẩm" value="<?php echo htmlspecialchars($sanpham_tieude); ?>" required>

            <label for="">Giá <span style="color:red">*</span></label>
            <input type="text" name="sanpham_gia" placeholder="Nhập giá sản phẩm" value="<?php echo htmlspecialchars($sanpham_gia); ?>" required>

            <label for="">Danh mục <span style="color:red">*</span></label>
            <select name="danhmuc_id" id="">
                <option value="#">-- Chọn danh mục --</option>
                <?php
                if ($cartegory) {
                    while ($result = $cartegory->fetch_assoc()) {
                ?>
                        <option value="<?php echo $result['danhmuc_id'] ?>" <?php echo ($result['danhmuc_id'] == $danhmuc_id) ? 'selected' : ''; ?>>
                            <?php echo $result['danhmuc_ten'] ?>
                        </option>
                <?php
                    }
                }
                ?>
            </select>
            
            <h3>Thêm các biến thể sản phẩm</h3>
            <div id="variants-container">
                <?php if (empty($variants_data)) : ?>
                    <div class="variant-group">
                        <div class="color-item">
                            <label for="">Màu sắc <span style="color:red">*</span></label>
                            <input type="text" name="variant_data[0][mau_ten]" placeholder="Nhập màu" required>
                            <div class="size-container">
                                <div class="size-item">
                                    <label for="">Kích thước <span style="color:red">*</span></label>
                                    <input type="text" name="variant_data[0][sizes][]" placeholder="Nhập size" required>
                                    <label for="">Số lượng <span style="color:red">*</span></label>
                                    <input type="number" name="variant_data[0][soluongs][]" placeholder="Nhập số lượng" required>
                                    <button type="button" class="remove-size-btn">Xóa size</button>
                                </div>
                            </div>
                            <button type="button" class="add-size-btn">Thêm size</button>
                            <button type="button" class="remove-color-btn">Xóa màu</button>
                        </div>
                    </div>
                <?php else : ?>
                    <?php foreach ($variants_data as $color_index => $color_data) : ?>
                        <div class="variant-group">
                            <div class="color-item">
                                <label for="">Màu sắc <span style="color:red">*</span></label>
                                <input type="text" name="variant_data[<?php echo $color_index; ?>][mau_ten]" placeholder="Nhập màu" value="<?php echo htmlspecialchars($color_data['mau_ten']); ?>" required>
                                <div class="size-container">
                                    <?php foreach ($color_data['sizes'] as $size_index => $size) : ?>
                                        <div class="size-item">
                                            <label for="">Kích thước <span style="color:red">*</span></label>
                                            <input type="text" name="variant_data[<?php echo $color_index; ?>][sizes][]" placeholder="Nhập size" value="<?php echo htmlspecialchars($size); ?>" required>
                                            <label for="">Số lượng <span style="color:red">*</span></label>
                                            <input type="number" name="variant_data[<?php echo $color_index; ?>][soluongs][]" placeholder="Nhập số lượng" value="<?php echo htmlspecialchars($color_data['soluongs'][$size_index]); ?>" required>
                                            <button type="button" class="remove-size-btn">Xóa size</button>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <button type="button" class="add-size-btn">Thêm size</button>
                                <button type="button" class="remove-color-btn">Xóa màu</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <button type="button" id="add-color-btn">Thêm màu</button>
            <br>
            <button class="add-btn" type="submit">Thêm</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let colorIndex = document.querySelectorAll('.variant-group').length - 1;

        function addColor() {
            colorIndex++;
            const container = document.getElementById('variants-container');
            const newColorGroup = document.createElement('div');
            newColorGroup.classList.add('variant-group');
            newColorGroup.innerHTML = `
                <div class="color-item">
                    <label for="">Màu sắc <span style="color:red">*</span></label>
                    <input type="text" name="variant_data[${colorIndex}][mau_ten]" placeholder="Nhập màu" required>
                    <div class="size-container">
                        <div class="size-item">
                            <label for="">Kích thước <span style="color:red">*</span></label>
                            <input type="text" name="variant_data[${colorIndex}][sizes][]" placeholder="Nhập size" required>
                            <label for="">Số lượng <span style="color:red">*</span></label>
                            <input type="number" name="variant_data[${colorIndex}][soluongs][]" placeholder="Nhập số lượng" required>
                            <button type="button" class="remove-size-btn">Xóa size</button>
                        </div>
                    </div>
                    <button type="button" class="add-size-btn">Thêm size</button>
                    <button type="button" class="remove-color-btn">Xóa màu</button>
                </div>
            `;
            container.appendChild(newColorGroup);
        }

        function addSize(colorItem) {
            const sizeContainer = colorItem.querySelector('.size-container');
            const colorInput = colorItem.querySelector('input[name*="[mau_ten]"]'); 
            const colorIndex = colorInput.name.match(/\[(\d+)\]/)[1];
            
            const newSizeItem = document.createElement('div');
            newSizeItem.classList.add('size-item');
            newSizeItem.innerHTML = `
                <label for="">Kích thước <span style="color:red">*</span></label>
                <input type="text" name="variant_data[${colorIndex}][sizes][]" placeholder="Nhập size" required>
                <label for="">Số lượng <span style="color:red">*</span></label>
                <input type="number" name="variant_data[${colorIndex}][soluongs][]" placeholder="Nhập số lượng" required>
                <button type="button" class="remove-size-btn">Xóa size</button>
            `;
            sizeContainer.appendChild(newSizeItem);
        }

        document.getElementById('add-color-btn').addEventListener('click', addColor);

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('add-size-btn')) {
                const colorItem = e.target.closest('.color-item');
                addSize(colorItem);
            } else if (e.target.classList.contains('remove-size-btn')) {
                const sizeItem = e.target.closest('.size-item');
                const sizeContainer = sizeItem.parentElement;
                if (sizeContainer.children.length > 1) {
                    sizeItem.remove();
                }
            } else if (e.target.classList.contains('remove-color-btn')) {
                const colorGroup = e.target.closest('.variant-group');
                if (document.querySelectorAll('.variant-group').length > 1) {
                    colorGroup.remove();
                }
            }
        });
    });
</script>

</section>
</body>
</html>
