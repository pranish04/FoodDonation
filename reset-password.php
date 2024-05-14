<?php
session_start();
include 'connection.php';

$message = "";

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    $token = mysqli_real_escape_string($connection, $_POST['token']);

    // Check if the token is valid
    $sql = "SELECT * FROM login WHERE email='$email' AND reset_token='$token' AND TIMESTAMPDIFF(HOUR, reset_timestamp, NOW()) <= 24";
    $result = mysqli_query($connection, $sql);
    $num = mysqli_num_rows($result);

    if ($num == 1) {
        // Update the password and clear the reset token
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $updatePasswordQuery = "UPDATE login SET password='$hashedPassword', reset_token=NULL, reset_timestamp=NULL WHERE email='$email'";
        mysqli_query($connection, $updatePasswordQuery);

        $message = "Password reset successfully. <a href='Signin.php'>Login</a>";
    } else {
        $message = "Invalid or expired token.";
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

        #password {
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

        .message {
            color: #06C167;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="regform">
            <form action="" method="post">
                <h1 class="logo">Food <b style="color: #06C167;">Donate</b></h1>
                <h2 id="heading">Reset your password</h2>

                <div class="input">
                    <input type="hidden" name="email" value="<?php echo $_GET['email']; ?>" />
                    <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>" />
                    <label class="textlabel" for="password">Enter your new password</label>
                    <input type="password" id="password" name="password" required />
                </div>

                <div class="btn">
                    <button type="submit" name="submit">Reset Password</button>
                </div>

                <div class="message">
                    <?php echo $message; ?>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
