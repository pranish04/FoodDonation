<?php
session_start();
include '../connection.php';

$msg = '';

if (!isset($_SESSION['reset_email']) || !isset($_SESSION['reset_token'])) {
    header("location:deliverylogin.php");
}

if (isset($_POST['reset_password'])) {
    $email = $_SESSION['reset_email'];
    $token = $_SESSION['reset_token'];
    $password = $_POST['password'];

    // Check if the token matches the one stored in the delivery_persons table
    $check_query = "SELECT * FROM delivery_persons WHERE email='$email' AND reset_token='$token'";
    $check_result = mysqli_query($connection, $check_query);
    $check_num = mysqli_num_rows($check_result);

    if ($check_num == 1) {
        // Update the password and clear the reset token
        $new_password = password_hash($password, PASSWORD_DEFAULT);
        $update_query = "UPDATE delivery_persons SET password='$new_password', reset_token=NULL WHERE email='$email'";
        mysqli_query($connection, $update_query);

        $msg = "Password reset successful. You can now <a href='deliverylogin.php'>login</a> with your new password.";
        // Clear reset_email and reset_token from session
        unset($_SESSION['reset_email']);
        unset($_SESSION['reset_token']);
    } else {
        $msg = "Invalid or expired reset link.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Reset Password</title>
    <link rel="stylesheet" href="deliverycss.css">
</head>
<body>
    <div class="center">
        <h1>Reset Password</h1>
        <form method="post">
            <div class="txt_field">
                <input type="password" name="password" required/>
                <span></span>
                <label>New Password</label>
            </div>
            <?php if(!empty($msg)) echo '<p class="message">' . $msg . '</p>'; ?>
            <br>
            <input type="submit" value="Reset Password" name="reset_password">
        </form>
    </div>
</body>
</html>
