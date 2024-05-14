







<?php
session_start();
 $connection=mysqli_connect("localhost:3306","root","");
 
 $db=mysqli_select_db($connection,'food');
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    // Remove this column t_email from user_feedback table later after this feedback data is stored and fetched
    $t_email = $_POST['email'];
    $message = $_POST['message'];
    $email = $_SESSION['email'];

    $sql = "INSERT INTO user_feedback (name, email, message, t_email) VALUES ('$name', '$email', '$message', '$t_email')";
    
    // Execute the query
    $result = mysqli_query($connection, $sql);

    if ($result) {
        // Feedback successfully inserted
        echo "Feedback inserted successfully!";
    } else {
        // Handle the case where the query fails
        echo "Error: " . mysqli_error($connection);
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Your Feedback</title>
</head>
<body>
    
    <div>
        <h1>Thank You for Your Feedback!</h1>
        <p>Your feedback has been received. We appreciate your input.</p>
        <p>Click <a href="home.html">here</a> to go back to the home page.</p>
    </div>
</body>
</html>
