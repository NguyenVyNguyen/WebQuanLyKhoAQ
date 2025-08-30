<?php
include "header.php";
include "leftside.php";
include "class/cartegory_class.php";
?>
<?php
$cartegory = new cartegoty;
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $cartegory_name = $_POST['cartegory_name'];
	$insert_cartegory = $cartegory ->insert_cartegory($cartegory_name);

}
?>
        <div class="admin-content-right">
            <div class="admin-add-content">
                <h2>THÊM DANH MỤC</h2>
                <form action="" method="POST" enctype="multipart/form-data">
                    <label for="">Vui lòng nhập danh mục<span style="color: red;">*</span></label> <br>
                    <input type="text" name="cartegory_name">
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