<?php
require 'config.php';
require 'vendor/autoload.php'; // Path to PHPMailer autoload.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

// Function to send email
function sendPasswordEmail($email, $password) {
    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com'; // Your SMTP server (e.g., Gmail)
        $mail->SMTPAuth = true;
        $mail->Username = 'khemnarst21.comp@coeptech.ac.in'; // Your email address
        $mail->Password = 'Sainath@1234'; // Your email password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587; // Gmail SMTP port

        // Recipients
        $mail->setFrom('khemnarst21.comp@coeptech.ac.in', 'Book Sharing Portal');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(false); // Set email format to plain text
        $mail->Subject = "Password Recovery";
        $mail->Body = "Dear User,\n\nYour password for the Book Sharing Portal is: $password\n\nPlease keep it safe and do not share it with anyone.\n\nRegards,\nThe Book Sharing Portal Team";

        // Send the email
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Function to handle login
function login() {
    global $conn;
    if (isset($_POST['login'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $select_users = $conn->query("SELECT * FROM users_info WHERE email = '$email' and password='$password' ") or die('query failed');

        if (mysqli_num_rows($select_users) == 1) {
            $row = mysqli_fetch_assoc($select_users);
            if ($row['user_type'] == 'Admin') {
                $_SESSION['admin_name'] = $row['name'];
                $_SESSION['admin_email'] = $row['email'];
                $_SESSION['admin_id'] = $row['Id'];
                header('location:admin_index.php');
                exit;
            } elseif ($row['user_type'] == 'User' || $row['user_type'] == 'NGO') {
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['user_email'] = $row['email'];
                $_SESSION['user_id'] = $row['Id'];
                header('location:index.php');
                exit;
            }
        } else {
            echo '<p style="color: red;">Incorrect Email Id or Password!</p>';
        }
    }
}

// Function to handle forgot password
function forgotPassword() {
    global $conn;
    if (isset($_POST['forgot_password'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $select_user = $conn->query("SELECT * FROM users_info WHERE email = '$email'") or die('query failed');

        if (mysqli_num_rows($select_user) == 1) {
            $row = mysqli_fetch_assoc($select_user);
            $password = $row['password'];
            if (sendPasswordEmail($email, $password)) {
                echo '<p style="color: green;">Password sent to your email.</p>';
            } else {
                echo '<p style="color: red;">Failed to send password email. Please try again later.</p>';
            }
        } else {
            echo '<p style="color: red;">Email not found.</p>';
        }
    }
}

// Call login and forgotPassword functions
login();
forgotPassword();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/register.css" />
    <title>Login Here</title>
    <style>
        .container form .link {
            text-decoration: none;
            color: white;
            border-radius: 17px;
            padding: 8px 18px;
            margin: 0px 10px;
            background: rgb(0, 0, 0);
            font-size: 20px;
        }

        .container form .link:hover {
            background: rgb(0, 167, 245);
        }
    </style>
</head>

<body>
    <div class="container">
        <form action="" method="post">
            <h3 style="color:white">login to <a href="index.php"><span style="color:red">Book Sharing Portal </span></a></h3>
            <input type="email" name="email" placeholder="Enter Email Id" required class="text_field">
            <input type="password" name="password" placeholder="Enter password" required class="text_field">
            <input type="submit" value="Login" name="login" class="btn text_field">
            <p>Don't have an Account? <br> <a class="link" href="Register.php">Sign Up</a><a class="link" href="index.php">Back</a></p>
            
        </form>
        <form action="" method="post">
            <label for="email" style="color: white; font-size: 30px">Forgot Password?</label>
            <input type="email" name="email" placeholder="Enter Email Id" required class="text_field">
            <input type="submit" value="Get Your Password" name="forgot_password" class="btn text_field">
        </form>
    </div>
</body>

</html>
