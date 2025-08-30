<?php
include "header.php";
include "leftside.php";
?>
<style>

section {
    display: flex;
    min-height: 100vh;
}

.admin-content-right {
    flex: 1;
    position: relative; 
}

.admin-content-right-bg {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    overflow: hidden; 
}

.admin-content-right-bg img {
    width: 100%; 
    height: 100%; 
    object-fit: cover; 
    object-position: center; 
}
</style>
        <div class="admin-content-right">
           <div class="admin-content-right-bg">
               <img src="icon/nen.jpg" alt="">
           </div>
        </div>
    </section>
    <section>
    </section>
    <script src="js/script.js"></script>
</body>
</html>