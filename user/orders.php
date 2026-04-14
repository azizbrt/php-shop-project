<?php   
include '../config/config.php';
session_start();
$user_id = $_SESSION['user_id'] ;
if(!isset($user_id)){
   header('location:login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css ">
   <link rel="stylesheet" href="../css/style.css">
</head>
<body>
   <?php include 'user_header.php'; ?>
   <div class="heading">
   <h3>place orders</h3>
   <p><a href="home.php">home</a> <span> / checkout</span></p>
   </div>
   <section class="placed-orders">
      <h1 class="title">
         placed orders
      </h1>
      <div class="box-container">
         <?php
         $select_orders = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id'") or die('query failed');
         if(mysqli_num_rows($select_orders) > 0){
            while($fetch_orders = mysqli_fetch_assoc($select_orders)){
         
         ?>
         <div class="box">
            <p>
            placed on : <span><?php echo $fetch_orders['placed_on']; ?></span> <br>
         </p>
         <p>
            name : <span><?php echo $fetch_orders['name']; ?></span> <br>
         </p>
         <p>
            number : <span><?php echo $fetch_orders['number']; ?></span> <br> 
         </p>
         <p>
            email : <span><?php echo $fetch_orders['email']; ?></span> <br>
         </p>
         <p>
            address : <span><?php echo $fetch_orders['address']; ?></span> <br>
         </p>
         <p>
            payment method : <span><?php echo $fetch_orders['method']; ?></span> <br>
         </p>
         <p>
            your orders : <span><?php echo $fetch_orders['total_products']; ?></span> <br>
         </p>
         <p>
            total price : <span>$<?php echo $fetch_orders['total_price']; ?>/-</span> <br>
         </p>
         <p>
            payment status : <span style="color:<?php if ($fetch_orders['payment_status'] == 'pending') {
               echo 'red';
            }else {
               echo 'green';
            } ?>;"><?php echo $fetch_orders['payment_status']; ?></span> <br>
         </p>
         </div>
         <hr>
         <?php
            }
         }else {
            echo '<p class="empty">no orders placed yet!</p>';
         }

         ?>
      </div>
   </section>









   <?php include 'footer.php'?>







   <script src="../js/script.js"></script>

   
</body>
</html>