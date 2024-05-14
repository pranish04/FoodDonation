<?php
include 'connection.php';

if (isset($_POST['sign'])) {
    $username = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $gender = $_POST['gender'];
    $phoneno = $_POST['phoneno'];

    $pass = password_hash($password, PASSWORD_DEFAULT);

    // Check if the email or phone number is already registered
    $check_query = "SELECT * FROM login WHERE email='$email' OR phoneno='$phoneno'";
    $check_result = mysqli_query($connection, $check_query);
    $check_num = mysqli_num_rows($check_result);

    if ($check_num > 0) {
        echo "<h1><center>Account with this email or phone number already exists</center></h1>";
    } else {
        $query = "INSERT INTO login(name, email, password, gender, phoneno) VALUES('$username', '$email', '$pass', '$gender', '$phoneno')";

        $query_run = mysqli_query($connection, $query);

        if ($query_run) {
            header("location:signin.php");
        } else {
            echo '<script type="text/javascript">alert("Data not saved")</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Donate - Sign Up</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: white;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 400px;
        }

        .regform {
            text-align: center;
        }

        .logo {
            font-size: 30px;
            color: #333;
            margin-bottom: 20px;
        }

        #heading {
            font-size: 20px;
            color: #333;
            margin-bottom: 20px;
        }

        .input {
            margin-bottom: 15px;
        }

        .textlabel {
            font-size: 16px;
            color: #333;
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .password {
            position: relative;
        }

        #password {
            padding-right: 30px;
        }

        .showHidePw {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .radio {
            display: flex;
            align-items: left;
            margin-bottom: 10px;
            margin-top: 10px;
        }

        input[type="radio"] {
            margin-right: 50px;
        }

        .btn {
            margin-top: 20px;
        }

        button {
            background-color: #06C167;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .signin-up {
            margin-top: 15px;
            font-size: 16px;
        }

        a {
            color: #06C167;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="regform">
        <form action="" method="post">
            <h1 class="logo">Food <b style="color: #06C167;">Donate</b></h1>
            <h2 id="heading">Create your account</h2>

            <div class="input">
                <label class="textlabel" for="name">User name</label><br>
                <input type="text" id="name" name="name" required/>
            </div>

            <div class="input">
                <label class="textlabel" for="email">Email</label>
                <input type="email" id="email" name="email" required/>
            </div>

            <div class="input">
                <label class="textlabel" for="phoneno">Phone no</label>
                <input type="text" id="phoneno" name="phoneno" pattern="^98\d{8}$" title="Phone number should start with '98'" maxlength="10" required />
            </div>

            <label class="textlabel" for="password">Password</label>

            <div class="password">
                <input type="password" name="password" id="password" required/>
                <i class="uil uil-eye-slash showHidePw" id="showpassword"></i>
            </div>

            <div class="radio">
                <input type="radio" name="gender" id="male" value="male" required/>
                <label for="male">Male</label>
                <input type="radio" name="gender" id="female" value="female">
                <label for="female">Female</label>
            </div>

            <div class="btn">
                <button type="submit" name="sign">Continue</button>
            </div>

            <div class="signin-up">
                <p>Already have an account? <a href="signin.php">Sign in</a></p>
            </div>
        </form>
    </div>
</div>

<script src="admin/login.js"></script>

</body>
</html>
