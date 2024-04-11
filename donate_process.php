<!-- donate_process.php -->
<?php
include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit(); // Make sure to exit after redirecting
}

// Get the selected NGO name
$ngo_name = $_GET['name']; // If using GET method
// OR
// $ngo_name = $_POST['ngo_name']; // If using POST method

// Query the database to get NGO email
$query = "SELECT id, email FROM users_info WHERE user_type = 'NGO' AND name = '$ngo_name'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if ($row) {
    $ngo_id = $row['id'];
    $ngo_email = $row['email'];

    // Compose the email message
    $subject = "Book Donation Received";
    $message = "Dear NGO,\n\n";
    $message .= "We are pleased to inform you that you have received a book donation.\n\n";
    $message .= "Thank you for your continuous efforts in making a difference in our community.\n\n";
    $message .= "Sincerely,\nYour Organization";

    // Send the email
    $headers = "From: your_organization@example.com";
    $mail_sent = mail($ngo_email, $subject, $message, $headers);

    // Check if the email was sent successfully
    if ($mail_sent) {
        echo "Email sent successfully to $ngo_email";
    } else {
        echo "Failed to send email";
    }
} else {
    echo "NGO not found or invalid NGO name.";
}
?>