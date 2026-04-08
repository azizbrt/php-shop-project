<?php   
include '../config/config.php';
session_start();
$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){
   header('location:login.php');
}
if (isset($_POST['add_product'])) {
    $name= mysqli_real_escape_string($conn, $_POST['name']);
    $price=  $_POST['price'];
    $image= $_FILES['image']['name'];
    $image_size= $_FILES['image']['size'];
    $image_tmp_name= $_FILES['image']['tmp_name'];
    $image_folder= '../uploaded_img/'.$image;

    $select_product_name = mysqli_query($conn, "SELECT name FROM products WHERE name = '$name'") or die('query failed');
    if(mysqli_num_rows($select_product_name) > 0){
        $message[] = 'product name already exist!';
    }else{
        $add_product_query = mysqli_query($conn, "INSERT INTO products(name, price, image) VALUES('$name', '$price', '$image')") or die('query failed');
        if($add_product_query){
            if($image_size > 2000000){
                $message[] = 'image size is too large!';
            }else{
                move_uploaded_file($image_tmp_name, $image_folder);
                $message[] = 'product added successfully!';
            }
        }else{
            $message[] = 'product could not be added!';
        }
    }
}
if (isset($_GET['delete'])) {

    $delete_id = $_GET['delete'];

    $image_query = mysqli_query($conn, 
        "SELECT image FROM products WHERE id = '$delete_id'"
    ) or die('query failed');

    $fetch_image = mysqli_fetch_assoc($image_query);

    $image_path = '../uploaded_img/'.$fetch_image['image'];

    if(file_exists($image_path)){
        unlink($image_path);
    }

    mysqli_query($conn, 
        "DELETE FROM products WHERE id = '$delete_id'"
    ) or die('query failed');

    header('location:admin_products.php');
}
if (isset($_POST['update_product'])) {

    $update_id = $_POST['update_p_id'];
    $update_name = $_POST['update_name'];
    $update_price = $_POST['update_price'];

    mysqli_query($conn,
        "UPDATE products 
         SET name='$update_name', price='$update_price'
         WHERE id='$update_id'"
    ) or die('query failed');

    $update_image = $_FILES['update_image']['name'];
    $update_tmp = $_FILES['update_image']['tmp_name'];
    $update_size = $_FILES['update_image']['size'];
    $old_image = trim($_POST['update_old_id']);

    if(!empty($update_image)){

        if($update_size > 2000000){

            $message[] = 'image too large';

        }else{

            $new_path = '../uploaded_img/'.$update_image;

            move_uploaded_file($update_tmp, $new_path);

            mysqli_query($conn,
                "UPDATE products SET image='$update_image' WHERE id='$update_id'"
            );

            $old_path = '../uploaded_img/'.$old_image;

            if(file_exists($old_path)){
                unlink($old_path);
            }
        }
    }

    header('location:admin_products.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css ">
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
    <?php include 'admin_header.php'; ?>

    <section class="add-products">
        <h1 class="title">Shop Products</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <h3>add Product</h3>

            <input type="text" name="name" class="box" placeholder="enter product name" required>
            <input type="number" name="price" class="box" min="0" placeholder="enter product price" required>
            <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
            <input type="submit" value="add product" name="add_product" class="btn"> 
        </form>
       
    </section>
    
    <section class="show-products">
        <div class="box-container">
            <?php
            $select_product= mysqli_query($conn, "SELECT * FROM products") or die('query failed');
            if(mysqli_num_rows($select_product) > 0){
                while($fetch_product = mysqli_fetch_assoc($select_product)){
                
            ?>
            <div class="box">
                <img src="../uploaded_img/<?php echo $fetch_product['image']; ?>" alt="">
                <div class="name"><?php echo $fetch_product['name']; ?></div>
                <div class="price">$<?php echo $fetch_product['price']; ?>/-</div>
                <a href="admin_products.php?update=<?php echo $fetch_product['id']; ?>" class="option-btn">update</a>
                <a href="admin_products.php?delete=<?php echo $fetch_product['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a> 

            </div>
            <?php
                }
            }else{
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>
    </section>
    <section class="edit-product-form">
        <?php
        if (isset($_GET['update'])) {
            $update_id = $_GET['update'];
            $update_query = mysqli_query($conn, "SELECT * FROM products WHERE id = '$update_id'") or die('query failed');
            if (mysqli_num_rows($update_query) > 0) {
                while ($fetch_update = mysqli_fetch_assoc($update_query)) {
                
            
         ?>
         <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id'];?>">
            <input type="hidden" name="update_old_id" value="<?php echo $fetch_update['image']; ?>">
            <img src="../uploaded_img/<?php echo $fetch_update['image'] ?>" alt="">
            <input type="text" name="update_name" id="" value="<?php echo $fetch_update['name'] ?>" required class="box" placeholder="enter product name" > 
            <input type="number" name="update_price" id="" value="<?php echo $fetch_update['price'] ?>" required class="box" min="0" placeholder="enter product price" >
            <input type="file" name="update_image" id="" accept="image/jpg, image/jpeg, image/png" class="box" >
            <input type="submit" value="update" name="update_product" class="btn">
            <input type="reset" value="cancel" id="close-update" class="option-btn" onclick="document.querySelector('.edit-product-form').style.display = 'none';">
            </form>

         <?php
                }
            }
        }else{
            echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
        }
         ?>
    </section>

    
    <script src="../js/admin_script.js"></script>
    
</body>
</html>