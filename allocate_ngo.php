<?php
session_start();
include 'config.php';
require 'vendor/autoload.php'; // Include PHPMailer autoload.php file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP; // Add this line to import the SMTP class

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit(); // Make sure to exit after redirecting
}

// Initialize PHPMailer
$mail = new PHPMailer(true); // true enables exceptions

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['donate_process'])) {
    // Check if at least one NGO is selected
    if (!empty($_POST['ngo'])) {
        // Loop through each selected NGO
        foreach ($_POST['ngo'] as $ngo_id) {
            // Fetch NGO information including email
            $sql = "SELECT * FROM users_info WHERE Id='$ngo_id' AND user_type='NGO'";
            $result = mysqli_query($conn, $sql);
            if ($result && mysqli_num_rows($result) > 0) {
                $ngo_info = mysqli_fetch_assoc($result);
                $ngo_email = $ngo_info['email'];

                // Send email to the NGO
                $mail->isSMTP();
                $mail->Host = 'smtp.office365.com';  // Gmail SMTP server address
                $mail->SMTPAuth = true;           // Enable SMTP authentication
                $mail->Username = 'khemnarst21.comp@coeptech.ac.in'; // Your Gmail email address
                $mail->Password = 'Sainath@1234';         // Your Gmail password
                $mail->SMTPSecure = 'tls';        // Enable TLS encryption
                $mail->Port = 587;                // TCP port to connect to
                
                $mail->setFrom('khemnarst21.comp@coeptech.ac.in', 'Book Sharing Portal');
                $mail->addAddress($ngo_email);
                $mail->Subject = "Donation Received";
                $mail->Body = "Dear NGO,\n\nWe are pleased to inform you that a donation has been received.\n\nThank you for your continued efforts.\n\nRegards,\nBook Sharing Portal";

                // Send email
                if ($mail->send()) {
                    echo "Email sent successfully to $ngo_email";
                } else {
                    echo "Failed to send email to $ngo_email: " . $mail->ErrorInfo;
                }
            } else {
                echo "NGO not found or not of correct type.";
            }
        }
    } else {
        echo "No NGOs selected for donation.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate to NGO</title>
    <style>
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        h2 {
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="checkbox"] {
            margin-right: 5px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

    </style>
</head>
<body>
    <div class="container">
        <h2>Select NGO to Donate</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="donateForm">
            <?php
            $sql = "SELECT * FROM users_info WHERE user_type='NGO'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<label><input type="checkbox" name="ngo[]" value="' . $row['Id'] . '"> ' . $row['name'] . '</label><br>';
                }
            } else {
                echo "No NGOs found.";
            }
            ?>
            <button type="submit" name="donate_process">Donate</button>
        </form>
    </div>

</body>
</html>
