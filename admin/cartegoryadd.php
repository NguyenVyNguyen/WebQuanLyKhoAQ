<?php
include "header.php";
include "leftside.php";
include "class/cartegory_class.php";
?>
<?php
$cartegory = new cartegoty;
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $danhmuc_ten = $_POST['danhmuc_ten'];
    $insert_cartegory = $cartegory ->insert_cartegory($danhmuc_ten);

    if ($insert_cartegory === false) {
        echo '<script>alert("Danh mục này đã tồn tại, vui lòng nhập lại!"); window.location.href="cartegoryadd.php";</script>';
    } else if ($insert_cartegory === true) {
        echo '<script>alert("Thêm danh mục thành công!"); window.location.href="cartegorylist.php";</script>';
    }
}
?>
        <div class="admin-content-right">
            <div class="admin-add-content">
                <h2>THÊM DANH MỤC</h2>
                <form action="" method="POST" enctype="multipart/form-data">
                    <label for="">Vui lòng nhập danh mục<span style="color: red;">*</span></label> <br>
                    <input type="text" name="danhmuc_ten">
                    <button class="admin-btn" type="submit">Thêm danh mục</button>        
                </form>
            </div>        
        </div>
    </section>
    <section>
    </section>
    <script src="js/script.js"></script>
</body>
</html>