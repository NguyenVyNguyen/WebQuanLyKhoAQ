<?php
include "header.php";
include "leftside.php";
include "class/nhanvien_class.php";

$nhanvien = new nhanvien;

$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username   = $_POST['username'];
    $password   = $_POST['password'];
    $role       = $_POST['role']; 
    $ten        = $_POST['nhanvien_ten'];
    $dienthoai  = $_POST['nhanvien_dienthoai'];
    $email      = $_POST['nhanvien_email'];
    $diachi     = $_POST['nhanvien_diachi'];

    $result = $nhanvien->insert_nhanvien(
        $username,
        $password,
        $role,
        $ten,
        $dienthoai,
        $email,
        $diachi
    );
    if ($result === "Tài khoản đã tồn tại, vui lòng chọn username khác!") {
        echo '<script>alert("Tài khoản đã tồn tại, vui lòng chọn username khác!"); window.location.href="nhanvienadd.php";</script>';
    } else if ($result === true) {
        echo '<script>alert("Thêm nhân viên thành công!"); window.location.href="nhanvienlist.php";</script>';
    } else {
        echo '<script>alert("Đã xảy ra lỗi khi thêm nhân viên!"); window.location.href="nhanvienadd.php";</script>';
    }
}

?>
<div class="admin-content-right">
    <div class="admin-add-content">
        <h2>THÊM NHÂN VIÊN</h2>
        <form action="" method="POST">
            <label>Tài khoản đăng nhập<span style="color: red;">*</span></label>
            <input type="text" name="username" required><br>

            <label>Mật khẩu<span style="color: red;">*</span></label>
            <input type="password" name="password" required><br>

            <label>Quyền</label> <br>
            <select name="role" required>
                <option value="admin">Admin</option>
                <option value="nhanvien">Nhân viên</option>
            </select> <br>


            <label>Họ tên nhân viên<span style="color: red;">*</span></label>
            <input type="text" name="nhanvien_ten" required><br>

            <label>Điện thoại<span style="color: red;">*</span></label>
            <input type="text" name="nhanvien_dienthoai" required><br>

            <label>Email<span style="color: red;">*</span></label>
            <input type="email" name="nhanvien_email" required><br>

            <label>Địa chỉ</label>
            <input type="text" name="nhanvien_diachi"><br>

            <button class="admin-btn" type="submit">Thêm nhân viên</button>
        </form>
    </div>
</div>
</section>
<script src="js/script.js"></script>
</body>
</html>
