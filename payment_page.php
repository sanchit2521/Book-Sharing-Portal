<?php
include 'config.php';
error_reporting(0);
session_start();

$user_id = $_SESSION['user_id'];

// Retrieve book details based on book_id passed in URL
if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];
    $get_book = mysqli_query($conn, "SELECT * FROM `book_info` WHERE bid = '$book_id'") or die('query failed');
    if (mysqli_num_rows($get_book) > 0) {
        $fetch_book = mysqli_fetch_assoc($get_book);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
</head>
<body>
    <div>
        <h2>Book Details</h2>
        <?php if(isset($fetch_book)): ?>
        <div>
            <img src="./added_books/<?php echo $fetch_book['image']; ?>" alt="<?php echo $fetch_book['name']; ?>">
            <h3>Name: <?php echo $fetch_book['name']; ?></h3>
            <p>Description: <?php echo $fetch_book['description']; ?></p>
            <p>Price: ₹ <?php echo $fetch_book['price']; ?>/-</p>
            <!-- Add your payment options/buttons here -->
            <form action="payment_process.php" method="post">
                <!-- Include necessary hidden fields for payment processing -->
                <input type="hidden" name="book_id" value="<?php echo $fetch_book['bid']; ?>">
                <input type="hidden" name="book_name" value="<?php echo $fetch_book['name']; ?>">
                <input type="hidden" name="book_price" value="<?php echo $fetch_book['price']; ?>">
                <!-- Add payment related inputs/buttons here -->
                <button type="submit" name="pay_now">Pay Now</button>
            </form>
        </div>
        <?php else: ?>
        <p>Book details not found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
