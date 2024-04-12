<?php
require 'config.php';
require 'vendor/autoload.php'; // Path to PHPMailer autoload.php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to send password email
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

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $select_users = $conn->query("SELECT * FROM users_info WHERE email = '$email' and password='$password' ") or die('query failed');

    if (mysqli_num_rows($select_users) == 1) {
        $row = mysqli_fetch_assoc($select_users);
        if ($row['user_type'] == 'NGO') {
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_id'] = $row['Id'];
            header('location:index.php');
            exit;
        } else {
            $message[] = 'Incorrect Email Id or Password!';
        }
    }
}

// Handle forgot password functionality
if (isset($_POST['forgot_password'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $select_user = $conn->query("SELECT * FROM users_info WHERE email = '$email'") or die('query failed');

    if (mysqli_num_rows($select_user) == 1) {
        $row = mysqli_fetch_assoc($select_user);
        $password = $row['password'];
        // Send password email
        if (sendPasswordEmail($email, $password)) {
            $message[] = 'Password sent to your email.';
        } else {
            $message[] = 'Failed to send password email. Please try again later.';
        }
    } else {
        $message[] = 'Email is not registered with the portal.';
    }
}
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
    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '<p style="color: red;">' . $msg . '</p>';
        }
    }
    ?>
    <div class="container">
        <form action="" method="post">
            <h3 style="color:white">Login for NGO to <a href="index.php"><span style="color:red">Book Sharing Portal </span></a></h3>
            <input type="email" name="email" placeholder="Enter Email Id" required class="text_field">
            <input type="password" name="password" placeholder="Enter password" required class="text_field">
            <input type="submit" value="Login" name="login" class="btn text_field">
            <p>Don't have an Account? <br> <a class="link" href="Register.php">Sign Up</a><a class="link" href="index.php">Back</a></p>
        </form>
        <form action="" method="post">
            <p>Forgot your password? <br> <input type="email" name="email" placeholder="Enter Email for Recovery" required class="text_field"> <input type="submit" value="Get Password" name="forgot_password" class="link"></p>
        </form>
    </div>

    <script>
        setTimeout(() => {
            const box = document.getElementById('messages');
            if (box) {
                box.style.display = 'none';
            }
        }, 8000);
    </script>
</body>

</html>
