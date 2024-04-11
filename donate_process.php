<?php
include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit(); // Exit after redirection
}

if (isset($_POST['donate']) && isset($_POST['ngo'])) {
    $ngos = $_POST['ngo'];

    foreach ($ngos as $ngo_name) {
        // Send message to NGO about donation in the background
        $message_content = "A book has been successfully donated to your NGO.";
        $timestamp = date('Y-m-d H:i:s');

        // Retrieve the NGO's ID based on their name
        $query = "SELECT Id FROM users_info WHERE user_type = 'NGO' AND name = '$name'";
        $result = mysqli_query($conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $ngo_id = $row['Id'];

            // Insert the message into the database
            $insert_query = "INSERT INTO messages (sender_id, receiver_id, message_content, timestamp) 
                             VALUES ('$Id', '$nane', '$message_content', '$timestamp')";
            mysqli_query($conn, $insert_query);
        }
    }
}

// Redirect back to the dashboard with a success message
header('location:dashboard.php?donation_success=1');
exit();
?>

