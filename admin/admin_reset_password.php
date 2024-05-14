<?php
session_start();
include '../connection.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; 

$message = ''; // Variable to store messages

if (isset($_POST['reset_password'])) {
    $email = $_POST['email'];
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];

    // Check if the email and token match in the admin table
    $sql = "SELECT * FROM admin WHERE email='$email' AND reset_token='$token'";
    $result = mysqli_query($connection, $sql);
    $num = mysqli_num_rows($result);

    if ($num == 1) {
        // Update the admin table with the new password and remove the reset token
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $update_query = "UPDATE admin SET password='$new_password_hash', reset_token=NULL WHERE email='$email'";
        mysqli_query($connection, $update_query);

        $message = "Password reset successfully. You can now log in with your new password.";
    } else {
        $message = "Invalid email or token. Please check the link or request a new password reset.";
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
            <span class="title">Reset Password</span>
            <br>
            <br>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <div class="error"></div>
            </div>
            <div class="input-group">
                <label for="token">Token</label>
                <input type="text" id="token" name="token" required>
                <div class="error"></div>
            </div>
            <div class="input-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" required>
                <div class="error"></div>
            </div>
            <button type="submit" name="reset_password">Reset Password</button>
            <div class="message"><?php echo $message; ?></div>
        </form>
    </div>
</body>
</html>
