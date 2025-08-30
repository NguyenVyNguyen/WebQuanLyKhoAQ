<?php
include "header.php";
include "leftside.php";
include "class/khachhang_class.php";

$khachhang = new khachhang();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten       = $_POST['khachhang_ten'];
    $dienthoai = $_POST['khachhang_dienthoai'];
    $email     = $_POST['khachhang_email'];
    $diachi    = $_POST['khachhang_diachi'];

    $insert = $khachhang->insert_khachhang($ten, $dienthoai, $email, $diachi);
    if ($insert) {
        echo "<script>window.location='khachhanglist.php';</script>";
    }
}
?>

<div class="admin-content-right">
    <div class="admin-add-content">
        <h2>THÊM KHÁCH HÀNG</h2>
        <form action="" method="POST">
            <label>Họ tên<span style="color: red;">*</span></label>
            <input type="text" name="khachhang_ten" required><br>

            <label>Điện thoại<span style="color: red;">*</span></label>
            <input type="text" name="khachhang_dienthoai" required><br>

            <label>Email<span style="color: red;">*</span></label>
            <input type="email" name="khachhang_email" required><br>

            <label>Địa chỉ</label>
            <input type="text" name="khachhang_diachi"><br>

            <button class="admin-btn" type="submit">Thêm khách hàng</button>
        </form>
    </div>
</div>
</section>
<script src="js/script.js"></script>
</body>
</html>
