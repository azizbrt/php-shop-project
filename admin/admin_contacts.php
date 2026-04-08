<?php   
include '../config/config.php';
session_start();
$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){
   header('location:login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacts</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css ">
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
    <?php include 'admin_header.php'; ?>

    
    <script src="../js/admin_script.js"></script>
    
</body>
</html>