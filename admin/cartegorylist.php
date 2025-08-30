<?php
include "header.php";
include "leftside.php";
include "class/cartegory_class.php";
$cartegory = new cartegoty;
$show_cartegory = $cartegory -> show_cartegory()
?>
       <div class="admin-content-right">
            <div class="admin-form-container">
                <h1 style="text-align: center; font-size: 28px; margin-bottom: 20px;">DANH MỤC</h1>

                <div style="margin-bottom: 10px; text-align: left;">
                    <a href="cartegoryadd.php" 
                    style="background: #007bff; color: #fff; padding: 8px 16px; text-decoration: none; border-radius: 5px;">
                    Thêm
                    </a>
                </div>
                <div class="table-content">
                    <table class="a-table">
                        <tr>
                            <th>Stt</th>
                            <th>ID</th>
                            <th>Danh mục</th>
                            <th>Tùy chỉnh</th>
                        </tr>
                        <?php
                        if($show_cartegory){$i=0; while($result= $show_cartegory->fetch_assoc()){
                            $i++
                    
                        ?>
                        <tr>
                            <td> <?php echo $i ?></td>
                            <td> <?php echo $result['danhmuc_id'] ?></td>
                            <td> <?php echo $result['danhmuc_ten']  ?></td>
                            <td><a href="cartegoryedit.php?danhmuc_id=<?php echo $result['danhmuc_id'] ?>">Sửa</a> | <a href="cartegorydelete.php?danhmuc_id=<?php echo $result['danhmuc_id'] ?>">Xóa</a></td>
                        </tr>
                        <?php
                        }}
                        ?>
                    </table>
                </div>  
            </div>      
        </div>
    </section>
    <section>
    </section>
    <script src="js/script.js"></script>
</body>
</html>  