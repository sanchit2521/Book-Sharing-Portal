<?php
include 'config.php';
error_reporting(0);
session_start();

$user_id = $_SESSION['user_id'];

// Fetch book details based on the book ID from the URL query parameter
if (isset($_GET['details'])) {
    $book_id = $_GET['details'];
    $get_book = mysqli_query($conn, "SELECT * FROM `book_info` WHERE book_id = '$book_id'") or die('query failed');
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
    <title>Book Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
        }

        .book-details {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .book-details img {
            max-width: 200px;
            margin-right: 20px;
        }

        .book-details h3 {
            margin: 0;
            color: #333;
        }

        .book-details p {
            margin: 5px 0;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Display book details -->
        <h2>Book Details</h2>
        <?php if (isset($fetch_book)) : ?>
            <div class="book-details">
                <img src="added_books/<?php echo $fetch_book['image']; ?>" alt="<?php echo $fetch_book['Title']; ?>">
                <div>
                    <h3>Title: <?php echo $fetch_book['Title']; ?></h3>
                    <p>Author: <?php echo $fetch_book['Author']; ?></p>
                    <p>Description: <?php echo $fetch_book['Review']; ?></p>
                    <p>Language: <?php echo $fetch_book['Language']; ?></p>
                    <p>Dimensions: <?php echo $fetch_book['Dimensions']; ?></p>
                    <p>Edition: <?php echo $fetch_book['Edition']; ?></p>
                    <p>Publication: <?php echo $fetch_book['Publication']; ?></p>
                    <p>Pages: <?php echo $fetch_book['Pages']; ?></p>
                    <p>Price: ₹ <?php echo $fetch_book['Price']; ?>/-</p>
                </div>
            </div>
        <?php else : ?>
            <p>No book details found.</p>
        <?php endif; ?>
    </div>
</body>

</html>

