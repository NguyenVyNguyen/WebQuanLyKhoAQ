<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php
if (!isset($_SESSION['nhanvien_login']) || $_SESSION['nhanvien_login'] !== true) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://kit.fontawesome.com/54f0cb7e4a.js" crossorigin="anonymous"></script>
    <title>Admin-Store</title>
</head>
<body>
  