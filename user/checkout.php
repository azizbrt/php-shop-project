<?php   
include '../config/config.php';
session_start();
$user_id = $_SESSION['user_id'] ;
if(!isset($user_id)){
   header('location:login.php');
}
if (isset($_POST['place_order'])) {
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $number = mysqli_real_escape_string($conn, $_POST['number']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $method = mysqli_real_escape_string($conn, $_POST['method']);
   $address = mysqli_real_escape_string($conn, 'flat no. '.$_POST['flat'].' ,street '.$_POST['street'].' ,city '.$_POST['city'].' ,state '.$_POST['state'].' ,country '.$_POST['country'].' - '.$_POST['pin_code']);
   $placed_on = date('d-M-Y');
   $cart_total = 0;
   $cart_products = [];
   $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed'); 
   if (mysqli_num_rows($select_cart) > 0) {
      while ($cart_item = mysqli_fetch_assoc($select_cart)) {
         $cart_products[] = $cart_item['name'].' ('.$cart_item['quantity'].') ';
         $sub_total = ($cart_item['price'] * $cart_item['quantity']);
         $cart_total += $sub_total;
      }
   }
   $total_products = implode(', ',$cart_products);
   $order_query = mysqli_query($conn, "select * from `orders` where name = '$name' and number = '$number' and email = '$email' and method = '$method' and address= '$address' and total_products = '$total_products' and total_price = '$cart_total'") or die('query failed');
   if ($cart_total ==0 ) {
      $message[] = 'your cart is empty';
   }else {
      if (mysqli_num_rows($order_query)> 0) {
         $message[] = 'order already placed!';
      }else {
         mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')") or die('query failed');
         $message[] = 'order placed successfully!';
         mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
         header('location:checkout.php');
         exit();
      }


   }
   $car_empty = true;
   $check_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   if (mysqli_num_rows($check_cart) > 0) {
      $car_empty = false;
   }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css ">
   <link rel="stylesheet" href="../css/style.css">
</head>
<body>
   <?php include 'user_header.php'; ?>
   <div class="heading">
   <h3>checkout</h3>
   <p><a href="home.php">home</a> <span> / checkout</span></p>
   </div>
   <section class="display-order">
      <?php
      $grand_total = 0;
      $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
      if (mysqli_num_rows($select_cart) >0) {
         while($fetch_cart = mysqli_fetch_assoc($select_cart)){
            $total_price = $fetch_cart['price'] * $fetch_cart['quantity'];
            $grand_total += $total_price;
         
      ?>
      <p><?php echo $fetch_cart['name']; ?><span>(<?php echo 'TND'.$fetch_cart['quantity']. ' x TND'.$fetch_cart['price'].''; ?>)</span></p>
      <?php
      }
         
      }else {
         echo '<p class="empty">your cart is empty</p>';
         
      } 
      ?>
      <div class="grand-total">
         <?php
         if ($grand_total > 0) {
            echo '<h3>grand total : <span>TND'.$grand_total.'/-</span></h3>';
         }else {
            echo '<h3>grand total : <span>TND0/-</span></h3>';
         }
         ?>
      </div>
   </section>
   <section class="checkout">
      <form action="" method="post">
         <h3>
            place your order
         </h3>
         <div class="flex">
            <div class="inputBox">
               <span>
                  your name:
               </span>
               <input type="text" name="name"  id="" required placeholder="enter your name">
            </div>
            <div class="inputBox">
               <span>
                  your number:
               </span>
               <input type="number" name="number"  id="" required placeholder="enter your number">
            </div>
            <div class="inputBox">
               <span>
                  your email:
               </span>
               <input type="email" name="email"  id="" required placeholder="enter your email">
            </div>
            <div class="inputBox">
               <span>
                  payment method:
               </span>
               <select name="method" id="" required>
                  <option value="cash on delivery">cash on delivery</option>
                  <option value="credit card">credit card</option>
                  <option value="paypal">paypal</option>
                  <option value="paytm">paytm</option>
               </select>
            </div>
            <div class="inputBox">
               <span>address line 01:</span>
               <input type="number" min="0" name="flat"  id="" required placeholder="e.g. flat no.">
            </div>
            <div class="inputBox">
               <span>address line 02:</span>
               <input type="text" name="street"  id="" required placeholder="e.g. street name">
            </div>
            <div class="inputBox">
               <span>city:</span>
               <input type="text" name="city"  id="" required placeholder="e.g. city name">
            </div>
            <div class="inputBox">
               <span>state:</span>
               <input type="text" name="state"  id="" required placeholder="e.g. state name">
            </div>
            <div class="inputBox">
               <span>country:</span>
               <input type="text" name="country"  id="" required placeholder="e.g. country name">
            </div>
            <div class="inputBox">
               <span>pin code:</span>
               <input type="number" min="0" name="pin_code"  id="" required placeholder="e.g. pin code">
            </div>
         </div>
         <input 
            type="submit" 
                            value="place order" 
   name="place_order" 
   class="btn"
   <?php if($cart_empty){ echo 'disabled'; } ?>
>

<?php if($cart_empty){ ?>
   <p style="color:red; font-size:1.5rem;">
      your cart is empty
   </p>
<?php } ?>
      </form>
   </section>









   <?php include 'footer.php'?>







   <script src="../js/script.js"></script>

   
</body>
</html>