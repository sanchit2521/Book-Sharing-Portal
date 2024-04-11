<?php
include 'config.php';

session_start();

$ngo_id = $_SESSION['user_id'];

if (!isset($ngo_id)) {
    header('location:login.php');
    exit(); // Exit after redirection
}

$query = "SELECT * FROM messages WHERE receiver_id = '$ngo_id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="message">';
        echo '<p>' . $row['message_content'] . '</p>';
        echo '<p class="timestamp">' . $row['timestamp'] . '</p>';
        echo '</div>';
    }
} else {
    echo "No messages.";
}

mysqli_close($conn);
?>
