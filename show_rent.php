<?php
require 'config.php';
require 'vendor/autoload.php'; // Path to PHPMailer autoload.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

if (isset($_POST['rent_book'])) {
    // Assuming you have a form with a hidden input field to store the book ID
    $book_id = $_POST['book_id'];

    // Get user information based on rented_by
    $user_id = $_SESSION['user_id'];
    $user_query = mysqli_query($conn, "SELECT * FROM users_info WHERE Id = '$user_id'");
    $user_info = mysqli_fetch_assoc($user_query);

    if ($user_info) {
        $user_email = $user_info['email'];

        // Compose the email message
        $subject = "Book Rental Confirmation";
        $message = "Dear " . $user_info['name'] . ",\n\n";
        $message .= "Thank you for renting the book from our portal.\n\n";
        $message .= "Book Details:\n";
        $message .= "Title: " . $_POST['title'] . "\n";
        $message .= "Author: " . $_POST['author'] . "\n";
        // Add more book details as needed
        $message .= "\nPlease return the book on time.\n\n";
        $message .= "Regards,\nBook Sharing Portal";

        // Send the email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.office365.com'; // Your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'khemnarst21.comp@coeptech.ac.in'; // Your email address
            $mail->Password = 'Sainath@1234'; // Your email password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587; // Check your SMTP port

            //Recipients
            $mail->setFrom('khemnarst21.comp@coeptech.ac.in', 'Book Sharing Portal');
            $mail->addAddress($user_email, $user_info['name']); // Add recipient

            // Content
            $mail->isHTML(false); // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->send();
            echo 'Email sent successfully to ' . $user_email;
        } catch (Exception $e) {
            echo "Failed to send email: {$mail->ErrorInfo}";
        }
    } else {
        echo "User not found or invalid user ID.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/register.css">
    <link rel="stylesheet" href="./css/index_book.css">
    <title>Rented Books</title>
</head>

<body>

    <section class="show-products">
        <div class="box-container">
            <?php
            $select_rented_books = mysqli_query($conn, "SELECT * FROM rented_books") or die('Query failed');
            if (mysqli_num_rows($select_rented_books) > 0) {
                while ($fetch_rented_book = mysqli_fetch_assoc($select_rented_books)) {
                    // Get user information based on rented_by
                    $user_id = $fetch_rented_book['rented_by'];
                    $user_query = mysqli_query($conn, "SELECT * FROM users_info WHERE Id = '$user_id'");
                    $user_info = mysqli_fetch_assoc($user_query);

            ?>
                    <div class="box">
                        <div class="name"></div>
                        <img src="./rented_books/<?php echo $fetch_rented_book['image']; ?>" alt="Rented Book Image" width="150">
                        <div class="name">Title: <?php echo $fetch_rented_book['title']; ?></div>
                        <div class="name">Author: <?php echo $fetch_rented_book['author']; ?></div>
                        <div class="name">Location: <?php echo $fetch_rented_book['location']; ?></div>
                        <div class="name">Rented By: <?php echo $user_info['name'] . ' ' . $user_info['surname']; ?></div>
                        <form method="post" action="">
                            <input type="hidden" name="book_id" value="<?php echo $fetch_rented_book['id']; ?>">
                            <input type="hidden" name="title" value="<?php echo $fetch_rented_book['title']; ?>">
                            <input type="hidden" name="author" value="<?php echo $fetch_rented_book['author']; ?>">
                            <button type="submit" name="rent_book" style="background-color: #007bff; color: #fff; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer;">Take it on rent</button>
                        </form>
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">No rented books found!</p>';
            }
            ?>
        </div>
    </section>

</body>

</html>
