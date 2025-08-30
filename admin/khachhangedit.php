<?php
include "header.php";
include "leftside.php";
include "class/khachhang_class.php";

$khachhang = new khachhang();

if (!isset($_GET['khachhang_id']) || $_GET['khachhang_id'] == NULL) {
    echo "<script>window.location='khachhanglist.php';</script>";
    exit();
} else {
    $khachhang_id = $_GET['khachhang_id'];
}

$get_khachhang = $khachhang->get_khachhang($khachhang_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten       = $_POST['khachhang_ten'];
    $dienthoai = $_POST['khachhang_dienthoai'];
    $email     = $_POST['khachhang_email'];
    $diachi    = $_POST['khachhang_diachi'];

    $update = $khachhang->update_khachhang($khachhang_id, $ten, $dienthoai, $email, $diachi);
    echo "<script>window.location='khachhanglist.php';</script>";
}
?>

<div class="admin-content-right">
    <div class="admin-add-content">
        <h2>Sửa thông tin khách hàng</h2>
        <?php if ($get_khachhang) {
            $row = $get_khachhang->fetch_assoc();
        ?>
        <form action="" method="POST">
            <label>Họ tên<span style="color: red;">*</span></label><br>
            <input type="text" name="khachhang_ten" value="<?php echo $row['khachhang_ten']; ?>" required><br>

            <label>Điện thoại<span style="color: red;">*</span></label><br>
            <input type="text" name="khachhang_dienthoai" value="<?php echo $row['khachhang_dienthoai']; ?>" required><br>

            <label>Email<span style="color: red;">*</span></label><br>
            <input type="email" name="khachhang_email" value="<?php echo $row['khachhang_email']; ?>" required><br>

            <label>Địa chỉ</label><br>
            <input type="text" name="khachhang_diachi" value="<?php echo $row['khachhang_diachi']; ?>"><br>

            <button class="admin-btn" type="submit">Cập nhật</button>
        </form>
        <?php } ?>
    </div>
</div>
</section>
<script src="js/script.js"></script>
</body>
</html>
