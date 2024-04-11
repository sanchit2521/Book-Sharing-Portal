<?php
include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
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
    <title>Donation Details</title>
</head>

<body>
    <?php include 'admin_header.php'; ?>

    <section class="show-products">
        <div class="box-container">
            <?php
            $select_donations = mysqli_query($conn, "SELECT * FROM donated_books") or die('query failed');
            if (mysqli_num_rows($select_donations) > 0) {
                while ($fetch_donation = mysqli_fetch_assoc($select_donations)) {
                    // Get user information based on user_id
                    $user_name = $fetch_donation['name'];
                    $user_query = mysqli_query($conn, "SELECT * FROM users_info WHERE name = '$user_name'");
                    $user_info = mysqli_fetch_assoc($user_query);

            ?>
                    <div class="box">
                        <div class="name"></div>
                        <img src="./donated_books/<?php echo $fetch_donation['image_path']; ?>" alt="Donation Image" width="150">
                        <div class="name">Donation ID: <?php echo $fetch_donation['don_id']; ?></div>
                        <div class="name">Title: <?php echo $fetch_donation['title']; ?></div>
                        <div class="name">Author: <?php echo $fetch_donation['author']; ?></div>
                        <div class="name">Location: <?php echo $fetch_donation['location']; ?></div>
                        <div class="name">Donated By: <?php echo $user_info['name'] . ' ' . $user_info['surname']; ?></div>
                        <button class="name" style="background-color: #007bff; color: #fff; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer;" onclick="window.location.href='allocate_ngo.php';">Allocate it to NGO</button>
                    </div>
                    
            <?php
                }
            } else {
                echo '<p class="empty">No donations found!</p>';
            }
            ?>
        </div>
    </section>

</body>

</html>
