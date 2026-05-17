<?php

include 'config/config.php';
session_start();

if(isset($_POST['submit'])){

   
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
   
   $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'") or die('query failed');

   if(mysqli_num_rows($select_users) > 0){
    $row= mysqli_fetch_assoc($select_users);
      if($row['user_type'] == 'admin'){
        $_SESSION['admin_name'] = $row['name'];
        $_SESSION['admin_email'] = $row['email'];
        $_SESSION['admin_id'] = $row['id'];
        header('location:admin/admin_dashboard.php');
      }elseif($row['user_type'] == 'user'){
        $_SESSION['user_name'] = $row['name'];
        $_SESSION['user_email'] = $row['email'];
        $_SESSION['user_id'] = $row['id'];
        header('location:user/home.php');
      }
     
   }else{
      $message[]= 'email ou mot de passe incorrect !';
   }

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se connecter</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css ">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>




    <div class="form-container">
        <form id="login-form" action="" method="post">
            <h3>login now</h3>
         <input type="email" name="email" placeholder="entrez votre e-mail" class="box">
         <input type="password" name="password" placeholder="entrez votre mot de passe" class="box">
         <input type="submit" name="submit" value="se connecter maintenant" class="btn">
         <p>Vous n'avez pas de compte ? <a href="register.php">inscrivez-vous maintenant</a></p>            
        </form>
    </div>
    
</body>
</html>
