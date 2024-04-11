<?php
// Assuming you have already established a database connection, let's proceed.

// Function to display books available for bidding
function displayBooks($conn) {
    $sql = "SELECT * FROM books WHERE bidding_end > NOW()";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<h2>" . $row['title'] . "</h2>";
            echo "<p>Author: " . $row['author'] . "</p>";
            echo "<img src='" . $row['image'] . "' alt='Book Cover'>";
            echo "<p>Description: " . $row['description'] . "</p>";
            echo "<p>Price: $" . $row['price'] . "</p>";
            echo "<p>Bidding End: " . $row['bidding_end'] . "</p>";
            echo "<a href='bid.php?book_id=" . $row['id'] . "'>Bid</a>";
            echo "</div>";
        }
    } else {
        echo "No books available for bidding.";
    }
}

// Function to add a new book
function addBook($conn, $title, $author, $image, $description, $price, $bidding_end) {
    $sql = "INSERT INTO books (title, author, image, description, price, bidding_end) VALUES ('$title', '$author', '$image', '$description', '$price', '$bidding_end')";
    if ($conn->query($sql) === TRUE) {
        echo "Book added successfully.<br>";
        // Start bidding process immediately after adding the book
        echo "Bidding started!";
    } else {
        echo "Error adding book: " . $conn->error;
    }
}

// Check if the form for adding a book is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $author = $_POST["author"];
    $image = $_POST["image"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $bidding_end = date('Y-m-d H:i:s', strtotime($_POST["bidding_end"]));

    // Add the book
    addBook($conn, $title, $author, $image, $description, $price, $bidding_end);
}

// Display available books
echo "<h1>Books Available for Bidding</h1>";
displayBooks($conn);

// Form to add a new book
?>
<h2>Add a New Book</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label>Title:</label><br>
    <input type="text" name="title" required><br>
    <label>Author:</label><br>
    <input type="text" name="author" required><br>
    <label>Image URL:</label><br>
    <input type="text" name="image" required><br>
    <label>Description:</label><br>
    <textarea name="description" required></textarea><br>
    <label>Price:</label><br>
    <input type="number" name="price" step="0.01" required><br>
    <label>Bidding End:</label><br>
    <input type="datetime-local" name="bidding_end" required><br>
    <input type="submit" value="Add Book">
</form>
