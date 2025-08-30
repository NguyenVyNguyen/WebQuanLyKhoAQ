<?php
include_once "class/product_class.php";
include "header.php";
include "leftside.php";

$product = new product();

if (isset($_GET['bienthe_id'])) {
    $bienthe_id = $_GET['bienthe_id'];
    $get_variant = $product->get_single_variant($bienthe_id);

    if ($get_variant) {
        $sanpham_id = $get_variant['sanpham_id'];
        $get_product_info = $product->get_sanpham_info_by_id($sanpham_id);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bienthe_id = $_POST['bienthe_id'];
    $soluong = $_POST['soluong'];
    $update_variant = $product->update_variant_quantity($bienthe_id, $soluong);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh Sửa Biến Thể Sản Phẩm</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7f9;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }

        .message.success {
            background-color: #e6f7ee;
            color: #28a745;
            border: 1px solid #c8e6c9;
        }

        .message.error {
            background-color: #fef4f4;
            color: #dc3545;
            border: 1px solid #f5c6cb;
        }

        .product-info {
            background-color: #f8f9fa;
            border-left: 5px solid #007bff;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 8px;
        }

        .product-info h2 {
            margin-top: 0;
            color: #007bff;
            font-size: 1.5em;
            font-weight: 500;
        }

        .product-info p {
            margin: 5px 0;
            font-size: 1em;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
            color: #555;
        }

        input[type="number"] {
            width: calc(100% - 20px);
            padding: 12px 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1em;
            transition: border-color 0.3s;
        }

        input[type="number"]:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.2);
        }

        .btn-update {
            display: block;
            width: 100%;
            padding: 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-update:hover {
            background-color: #0056b3;
        }


        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            text-decoration: underline;
            color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Chỉnh Sửa Biến Thể Sản Phẩm</h1>
    
    <?php if (isset($update_variant) && $update_variant): ?>
        <div class="message success">Cập nhật thành công!</div>
    <?php elseif (isset($update_variant) && !$update_variant): ?>
        <div class="message error">Cập nhật thất bại. Vui lòng thử lại.</div>
    <?php endif; ?>

    <?php if (isset($get_variant) && $get_variant && isset($get_product_info) && $get_product_info): ?>
        <div class="product-info">
            <?php $product_info = $get_product_info->fetch_assoc(); ?>
            <h2>Thông tin sản phẩm: <?php echo htmlspecialchars($product_info['sanpham_tieude']); ?></h2>
            <p><strong>Màu sắc:</strong> <?php echo htmlspecialchars($get_variant['mau_ten']); ?></p>
            <p><strong>Kích cỡ:</strong> <?php echo htmlspecialchars($get_variant['size_ten']); ?></p>
        </div>

        <form action="" method="post">
            <input type="hidden" name="bienthe_id" value="<?php echo htmlspecialchars($get_variant['bienthe_id']); ?>">
            <div class="form-group">
                <label for="soluong">Số Lượng Tồn Kho:</label>
                <input type="number" id="soluong" name="soluong" value="<?php echo htmlspecialchars($get_variant['soluong']); ?>" min="0" required>
            </div>
            <button type="submit" class="btn-update">Cập Nhật</button>
        </form>
    <?php else: ?>
        <div class="message error">Không tìm thấy biến thể sản phẩm.</div>
    <?php endif; ?>

    <a href="productlist.php" class="back-link">Quay lại danh sách sản phẩm</a>
</div>

</body>
</html>
