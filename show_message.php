<?php
include 'config.php';

session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['admin_id'])) {
    header('location: login.php');
    exit(); // Make sure to exit after redirecting
}

// Retrieve the admin's ID
$admin_id = $_SESSION['admin_id'];

// Function to retrieve the NGO's ID based on user ID
function getNgoId($user_id, $conn) {
    $query = "SELECT Id FROM users_info WHERE Id = '$user_id' AND user_type = 'NGO'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['Id'];
    } else {
        return null;
    }
}

if (isset($_POST['donate'])) {
    // Process the form submission
    $selected_ngos = $_POST['Id']; // Array of selected NGO IDs

    // Retrieve the book details from the donated_books table
    $book_id = $_POST['book_id']; // Assuming you have a hidden form field for book ID
    $sql_book = "SELECT * FROM donated_books WHERE don_id='$book_id'";
    $result_book = mysqli_query($conn, $sql_book);
    $book_info = mysqli_fetch_assoc($result_book);

    // Book details
    $title = $book_info['title'];
    $author = $book_info['author'];
    $location = $book_info['location'];

    // Send message to admin with book details
    $admin_message = "Book donated successfully to selected NGOs.\n\nBook Details:\nTitle: $title\nAuthor: $author\nLocation: $location";
    $sql_admin_message = "INSERT INTO messages (sender_id, receiver_id, message_content) VALUES ('$admin_id', '$admin_id', '$admin_message')";
    mysqli_query($conn, $sql_admin_message);

    // Send message to selected NGOs with book details
    foreach ($selected_ngos as $user_id) {
        $ngo_id = getNgoId($user_id, $conn);
        if($ngo_id) {
            // Send message to NGO with book details
            $message = "You have received a book donation from the admin.\n\nBook Details:\nTitle: $title\nAuthor: $author\nLocation: $location";
            $sql_message = "INSERT INTO messages (sender_id, receiver_id, message_content) VALUES ('$admin_id', '$ngo_id', '$message')";
            mysqli_query($conn, $sql_message);
        }
    }

    // Redirect back to the donation page or to a confirmation page
    header('location: admin_index.php');
    exit(); // Make sure to exit after redirecting
}
?>
