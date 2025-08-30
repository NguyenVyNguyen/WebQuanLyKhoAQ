<?php
include "header.php";
include "leftside.php";
include "class/nhanvien_class.php";

$nhanvien = new nhanvien();

if (!isset($_GET['nhanvien_id']) || $_GET['nhanvien_id'] == NULL) {
    echo "<script>window.location = 'nhanvienlist.php';</script>";
} else {
    $nhanvien_id = $_GET['nhanvien_id'];
}
$get_nhanvien = $nhanvien->get_nhanvien($nhanvien_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten       = $_POST['nhanvien_ten'];
    $dienthoai = $_POST['nhanvien_dienthoai'];
    $email     = $_POST['nhanvien_email'];
    $diachi    = $_POST['nhanvien_diachi'];
    $username  = $_POST['account_username'];
    $password  = $_POST['account_password'];
    $role      = $_POST['account_role'];
    $isactive  = isset($_POST['nhanvien_isactive']) ? 1 : 0;

    $update = $nhanvien->update_nhanvien(
        $nhanvien_id, 
        $username, 
        $password, 
        $role, 
        $ten, 
        $dienthoai, 
        $email, 
        $diachi, 
        $isactive
    );
    echo "<script>window.location = 'nhanvienlist.php';</script>";
}
?>

<div class="admin-content-right">
    <div class="admin-add-content">
        <h2>Sửa thông tin nhân viên</h2>
        <?php if($get_nhanvien){ 
            $row = $get_nhanvien->fetch_assoc();
        ?>
        <form action="" method="POST">
            <label>Họ tên nhân viên<span style="color: red;">*</span></label> <br>
            <input type="text" name="nhanvien_ten" value="<?php echo $row['nhanvien_ten'] ?>"> <br>

            <label>Điện thoại<span style="color: red;">*</span></label> <br>
            <input type="text" name="nhanvien_dienthoai" value="<?php echo $row['nhanvien_dienthoai'] ?>"> <br>

            <label>Email<span style="color: red;">*</span></label> <br>
            <input type="email" name="nhanvien_email" value="<?php echo $row['nhanvien_email'] ?>"> <br>

            <label>Địa chỉ</label> <br>
            <input type="text" name="nhanvien_diachi" value="<?php echo $row['nhanvien_diachi'] ?>"> <br>

            <label>Tài khoản<span style="color: red;">*</span></label> <br>
            <input type="text" name="account_username" value="<?php echo $row['account_username'] ?>"> <br>

            <label>Mật khẩu<span style="color: red;">*</span></label> <br>
            <input type="password" name="account_password" value="<?php echo $row['account_password'] ?>"> <br>

            <label>Quyền</label> <br>
            <select name="account_role">
                <option value="admin" <?php echo ($row['account_role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                <option value="nhanvien" <?php echo ($row['account_role'] == 'nhanvien') ? 'selected' : ''; ?>>Nhân viên</option>
            </select> <br>

            <label>Hoạt động</label>
            <input type="checkbox" name="nhanvien_isactive" <?php echo $row['nhanvien_isactive'] ? 'checked' : '' ?>> <br><br>

            <button class="admin-btn" type="submit">Cập nhật</button>
        </form>
        <?php } ?>
    </div>
</div>
</section>
<script src="js/script.js"></script>
</body>
</html>
