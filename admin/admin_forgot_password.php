<?php
session_start();
include '../connection.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; 

$message = ''; 

if (isset($_POST['forgot_password'])) {
    $email = $_POST['email'];

    // Check if the email exists in the admin table
    $sql = "SELECT * FROM admin WHERE email='$email'";
    $result = mysqli_query($connection, $sql);
    $num = mysqli_num_rows($result);

    if ($num == 1) {
        // Generate a unique token for password reset
        $reset_token = bin2hex(random_bytes(32));

        // Update the admin table with the reset token
        $update_query = "UPDATE admin SET reset_token='$reset_token' WHERE email='$email'";
        mysqli_query($connection, $update_query);

        // Send an email with a link to reset password using PHPMailer
        $reset_link = "http://localhost/FoodDonation/admin/admin_reset_password.php?email=$email&token=$reset_token";

        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ramnepal00q@gmail.com';
            $mail->Password = 'syzmhbaoqqislydl';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            // Recipients
            $mail->setFrom('Ramnepal00q@gmail.com', 'Ram Nepal');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset';
            $mail->Body = "Click the following link to reset your password: <a href='$reset_link'>$reset_link</a>";

            $mail->send();
            $message = "Password reset link sent to your email address.";
        } catch (Exception $e) {
            $message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $message = "Email not found. Please enter a valid email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            text-align: center;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .input-group {
            margin-top: 20px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #555;
        }

        input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            margin-top: 5px;
            font-size: 12px;
        }

        .message {
            margin-top: 10px;
            color: #333;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <form action="" id="form" method="post">
            <span class="title">Forgot Password</span>
            <br>
            <br>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <div class="error"></div>
            </div>
            <button type="submit" name="forgot_password">Reset Password</button>
            <div class="message"><?php echo $message; ?></div>
        </form>
    </div>
</body>
</html>
