<?php
include '../connection.php';

$msg = 0;
if (isset($_POST['sign'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $location = $_POST['district'];
    $phoneno = $_POST['phoneno']; // Updated to use 'phoneno' instead of 'phone'

    $pass = password_hash($password, PASSWORD_DEFAULT);
    
    // Check if the phone number is already registered
    $check_query = "SELECT * FROM delivery_persons WHERE phone='$phoneno'";
    $check_result = mysqli_query($connection, $check_query);
    $check_num = mysqli_num_rows($check_result);

    if ($check_num > 0) {
        echo "<h1><center>Account with this phone number already exists</center></h1>";
    } else {
        $query = "INSERT INTO delivery_persons(name, email, password, city, phone) VALUES('$username', '$email', '$pass', '$location', '$phoneno')";
        
        $query_run = mysqli_query($connection, $query);

        if ($query_run) {
            // Registration successful, proceed as needed
            header("location:delivery.php");
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
    <title>Delivery signup</title>
    <link rel="stylesheet" href="deliverycss.css">
</head>

<body>
    <div class="center">
        <h1>Register delivery person</h1>
        <form method="post" action="">
            <div class="txt_field">
                <input type="text" name="username" required />
                <span></span>
                <label>Username</label>
            </div>
            <div class="txt_field">
                <input type="password" name="password" required />
                <span></span>
                <label>Password</label>
            </div>
            <div class="txt_field">
                <input type="email" name="email" required />
                <span></span>
                <label>Email</label>
            </div>
            <div class="txt_field">
                <input type="text" name="phoneno" pattern="^98\d{8}$" title="Phone number should start with '98' " maxlength="10" required />
                <span></span>
                <label>Phone no</label>
            </div>
            <div class="">
                <select id="district" name="district" style="padding:10px; padding-left: 20px;">
                    <option value="Bhaktapur">Bhaktapur</option>
                    <option value="Kathmandu">Kathmandu</option>
                    <option value="Lalitpur" selected>Lalitpur</option>
                </select>
            </div>
            <br>
            <input type="submit" name="sign" value="Register">
            <div class="signup_link">
                Already a member? <a href="deliverylogin.php">Sign in</a>
            </div>
        </form>
    </div>
</body>

</html>
