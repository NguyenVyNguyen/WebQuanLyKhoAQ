<?php
include "header.php";
include "leftside.php";
include "class/product_class.php";

$product = new product();

if (!isset($_GET['sanpham_id']) || $_GET['sanpham_id'] == null) {
    echo "<script>window.location.href = 'productlist.php'</script>";
    exit();
}
$sanpham_id = $_GET['sanpham_id'];

$sanpham_info_result = $product->get_sanpham_info_by_id($sanpham_id);
$sanpham_tieude = "Sản phẩm không xác định";
if ($sanpham_info_result && $sanpham_info_result->num_rows > 0) {
    $row = $sanpham_info_result->fetch_assoc();
    $sanpham_tieude = $row['sanpham_tieude'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $variants_to_insert = [];
    if (isset($_POST['variant_data'])) {
        foreach ($_POST['variant_data'] as $color_data) {
            $mau_ten = $color_data['mau_ten'];
            if (isset($color_data['sizes']) && isset($color_data['soluongs'])) {
                for ($i = 0; $i < count($color_data['sizes']); $i++) {
                    $variants_to_insert[] = [
                        'mau_ten'   => $mau_ten,
                        'size_ten'  => $color_data['sizes'][$i],
                        'soluong'   => $color_data['soluongs'][$i]
                    ];
                }
            }
        }
    }

    $insert_result = $product->insert_multiple_variants($sanpham_id, $variants_to_insert);

    if ($insert_result) {
        echo "<script>alert('Thêm biến thể mới thành công!'); window.location.href = 'productlist.php';</script>";
    } else {
        echo "<script>alert('Lỗi: Biến thể đã tồn tại hoặc có lỗi xảy ra! Vui lòng kiểm tra lại.');</script>";
    }
}
?>
<div class="admin-content-right">
    <div class="admin-form-container">
        <h2>THÊM BIẾN THỂ MỚI CHO SẢN PHẨM: <br> "<?php echo htmlspecialchars($sanpham_tieude); ?>"</h2>
        <form action="" method="POST">
            <input type="hidden" name="sanpham_id" value="<?php echo htmlspecialchars($sanpham_id); ?>">

            <h3>Thêm các biến thể</h3>
            <div id="variants-container">
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
            </div>
            <button type="button" id="add-color-btn">Thêm màu</button>
            <br>
            <button class="add-btn" type="submit">Thêm biến thể</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let colorIndex = 0;

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
