<?php
session_start();
include 'connection.php';
require 'vendor/autoload.php';

$message = ""; // Initialize message variable

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($connection, $_POST['email']);

    // Check if the email exists in the database
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
        
        // Set up your mailer configuration for XAMPP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Username = "ramnepal00q@gmail.com";
        $mail->Password = "syzmhbaoqqislydl";
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('ramnepal00q@gmail.com', 'Ram Nepal');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        if ($mail->send()) {
            $message = "Check your email for a password reset link.";
        } else {
            $message = "Error sending email: " . $mail->ErrorInfo;
        }
    } else {
        $message = "Email does not exist.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            text-align: center;
        }

        .regform {
            width: 300px;
            background-color: #f3f3f3;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .logo {
            color: #000;
            font-size: 24px;
            margin-bottom: 20px;
        }

        #heading {
            color: #06C167;
            margin-bottom: 30px;
        }

        .textlabel {
            display: block;
            font-size: 16px;
            color: #555;
            margin-bottom: 8px;
        }

        #email {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
        }

        .btn button {
            background-color: #06C167;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #message {
            color: #ff0000;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="regform">
            <form action="" method="post">
                <h1 class="logo">Food <b style="color: #06C167;">Donate</b></h1>
                <h2 id="heading">Forgot your password?</h2>

                <div class="input">
                    <label class="textlabel" for="email">Enter your email address</label>
                    <input type="email" id="email" name="email" required />
                </div>

                <div class="btn">
                    <button type="submit" name="submit">Submit</button>
                </div>

                <p id="message"><?php echo $message; ?></p>
            </form>
        </div>
    </div>
</body>
</html>
