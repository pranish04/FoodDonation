<?php
session_start();
include 'connection.php';
require 'vendor/autoload.php';

$msg=0;
if (isset($_POST['sign'])) {
  $email =mysqli_real_escape_string($connection, $_POST['email']);
  $password =mysqli_real_escape_string($connection, $_POST['password']);


  $sql = "select * from login where email='$email'";
  $result = mysqli_query($connection, $sql);
  $num = mysqli_num_rows($result);
 
  if ($num == 1) {
    while ($row = mysqli_fetch_assoc($result)) {
      if (password_verify($password, $row['password'])) {
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $row['name'];
        $_SESSION['gender'] = $row['gender'];
        $_SESSION['phoneno'] = $row['phoneno'];
        header("location:home.html");
      } else {
        $msg = 1;
   
      }
    }
  } else {
    echo "<h1><center>Account does not exists </center></h1>";
  }

}
elseif (isset($_POST['forgotPassword'])) {
  $email = mysqli_real_escape_string($connection, $_POST['email']);

  $sql = "SELECT * FROM login WHERE email='$email'";
  $result = mysqli_query($connection, $sql);
  $num = mysqli_num_rows($result);

  if ($num == 1) {
      // Generate a unique token for password reset
      $token = bin2hex(random_bytes(32));

      // Store the token in the database along with the email and a timestamp
      $updateTokenQuery = "UPDATE login SET reset_token='$token', reset_timestamp=NOW() WHERE email='$email'";
      mysqli_query($connection, $updateTokenQuery);

      // Compose the password reset email
      $subject = "Password Reset";
      $body = "Click the following link to reset your password: http://localhost/FoodDonation/reset-password.php?email=$email&token=$token";

      // Send the email using PHPMailer

      $mail = new PHPMailer\PHPMailer\PHPMailer();
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->Username = "ramnepal00q@gmail.com";
      $mail->Password = "syzmhbaoqqislydl";
      $mail->SMTPAuth   = true;
      //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable implicit TLS encryption
      $mail->SMTPSecure = 'ssl';
      $mail->Port       = 465;

      $mail->setFrom('Ramnepal00q@gmail.com', 'Ram Nepal');
      $mail->addAddress($email);

      $mail->isHTML(true);
      $mail->Subject = $subject;
      $mail->Body = $body;

      if ($mail->send()) {
          echo "Check your email for a password reset link.";
      } else {
          echo "Error sending email: " . $mail->ErrorInfo;
      }
  } else {
      echo "Account does not exist.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="loginstyle.css">
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />

    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

</head>

<body>
    <style>
    .uil {

        top: 42%;
    }
    </style>
    <div class="container">
        <div class="regform">

            <form action=" " method="post">

                <p class="logo" style="">Food <b style="color:#06C167; ">Donate</b></p>
                <p id="heading" style="padding-left: 1px;"> Welcome User !!! <img src="" alt=""> </p>

                <div class="input">
                    <input type="email" placeholder="Email address" name="email" value="" required />
                </div>
                <div class="password">
                    <input type="password" placeholder="Password" name="password" id="password" required />
                    <i class="uil uil-eye-slash showHidePw"></i>
                    <?php
                    if($msg==1){
                        echo ' <i class="bx bx-error-circle error-icon"></i>';
                        echo '<p class="error">Password not match.</p>';
                    }
                    ?>
                
                </div>


                <div class="btn">
                    <button type="submit" name="sign"> Sign in</button>
                </div>
                <div class="signin-up">
                    <p id="signin-up">Don't have an account? <a href="signup.php">Register</a></p>
                </div>
                <div class="signin-up">
                    <p>Forgot your password? <a href="forgot-password.php">Reset it</a></p>
                </div>
            </form>
        </div>


    </div>
    <script src="login.js"></script>
    <script src="admin/login.js"></script>
</body>

</html>
