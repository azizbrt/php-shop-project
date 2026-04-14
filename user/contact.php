<?php   
include '../config/config.php';
session_start();
$user_id = $_SESSION['user_id'] ;
if(!isset($user_id)){
   header('location:login.php');
}
if (isset($_POST['send_message'])) {
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $number = mysqli_real_escape_string($conn, $_POST['number']);
   $user_message = mysqli_real_escape_string($conn, $_POST['message']);
   $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$user_message'") or die('query failed');
   if(mysqli_num_rows($select_message) > 0){
      $message[] = 'message sent already!';
   }else{
      mysqli_query($conn, "INSERT INTO `message`(user_id, name, email, number, message) VALUES('$user_id', '$name', '$email', '$number', '$user_message')") or die('query failed');
      $message[] = 'message sent successfully!';
   }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contact</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css ">
   <link rel="stylesheet" href="../css/style.css">
</head>
<body>
   <?php include 'user_header.php'; ?>
   <div class="heading">
   <h3>contact us</h3>
   <p><a href="home.php">home</a> <span> / contact</span></p>
   </div>
   <section class="contact">
      <form action="" method="post">
         <h3>
            say something!
         </h3>
         <input type="text" placeholder="enter your name" class="box" name="name">
         <input type="email" placeholder="enter your email" class="box" name="email">
         <input type="number" placeholder="enter your number" class="box" name="number">
         <textarea placeholder="enter your message" class="box" name="message" id="" cols="30" rows="10"></textarea>
         <input type="submit" value="send message" class="btn" name="send_message">
      </form>
   </section>









   <?php include 'footer.php'?>







   <script src="../js/script.js"></script>

   
</body>
</html>