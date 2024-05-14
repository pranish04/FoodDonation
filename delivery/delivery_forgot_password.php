<?php
session_start();
include '../connection.php';

$msg = '';

if (isset($_POST['forgot_password'])) {
    $email = $_POST['email'];

    // Check if the email exists in the delivery_persons table
    $sql = "SELECT * FROM delivery_persons WHERE email='$email'";
    $result = mysqli_query($connection, $sql);
    $num = mysqli_num_rows($result);

    if ($num == 1) {
        // Generate a unique token for password reset
        $reset_token = bin2hex(random_bytes(32));

        // Update the delivery_persons table with the reset token
        $update_query = "UPDATE delivery_persons SET reset_token='$reset_token' WHERE email='$email'";
        mysqli_query($connection, $update_query);

        // Store email and reset token in session for use in delivery_reset_password.php
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_token'] = $reset_token;

        header("location:delivery_reset_password.php");
    } else {
        $msg = "Email not found. Please enter a valid email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Forgot Password</title>
    <link rel="stylesheet" href="deliverycss.css">
</head>
<body>
    <div class="center">
        <h1>Forgot Password</h1>
        <form method="post">
            <div class="txt_field">
                <input type="email" name="email" required/>
                <span></span>
                <label>Email</label>
            </div>
            <?php if(!empty($msg)) echo '<p class="error">' . $msg . '</p>'; ?>
            <br>
            <input type="submit" value="Reset Password" name="forgot_password">
            <div class="signup_link">
                Remember your password? <a href="deliverylogin.php">Login</a>
            </div>
        </form>
    </div>
</body>
</html>
