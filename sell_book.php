<?php
include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit;
}

$message = '';

// Fetch user information from users_info table
$user_query = "SELECT * FROM users_info WHERE Id = '$user_id'";
$user_result = mysqli_query($conn, $user_query);
$user_row = mysqli_fetch_assoc($user_result);

// Check if user information exists
if (!$user_row) {
    $message = 'User information not found';
}

if (isset($_POST['sell_book'])) {
    // Validate and sanitize inputs
    function sanitize($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $title = sanitize($_POST['Title']);
    $author = sanitize($_POST['Author']);
    $location = sanitize($_POST['Location']);
    $language = sanitize($_POST['Language']);
    $dimensions = sanitize($_POST['Dimensions']);
    $review = sanitize($_POST['Review']);
    $edition = sanitize($_POST['Edition']);
    $publication = sanitize($_POST['Publication']);
    $pages = (int)$_POST['Pages'];
    $price = (int)$_POST['Price'];
    $category = sanitize($_POST['category_name']);

    // Check if all required fields are filled
    if (empty($title) || empty($author) || empty($location) || empty($price) || empty($category) || empty($review)) {
        $message = 'Please fill in all required fields';
    } else {
        // Insert book details into the database
        $image = $_FILES["image"]["name"];
        $image_tmp_name = $_FILES["image"]["tmp_name"];
        $image_folder = "./added_books/" . $image;

        if (move_uploaded_file($image_tmp_name, $image_folder)) {
            // Image uploaded successfully
            $insert_query = "INSERT INTO book_info (Title, Author, Location, Language, Dimensions, Review, Edition, Publication, Pages, Price, category_name, Image, added_by) 
                            VALUES ('$title', '$author', '$location', '$language', '$dimensions', '$review', '$edition', '$publication', $pages, $price, '$category', '$image', '$user_id')";

            if (mysqli_query($conn, $insert_query)) {
                $message = 'Book added successfully';
                // Redirect user to prevent form resubmission
                header('Location: index.php');
                exit;
            } else {
                $message = 'Failed to add book';
            }
        } else {
            $message = 'Failed to upload image';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sell Book</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        h2 {
            text-align: center;
        }
        form {
            width: 50%;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="number"], select, textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2>Sell a Book</h2>
    <!-- Display error/success message -->
    <?php if (!empty($message)) : ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <!-- Display user information -->
    <?php if ($user_row) : ?>
        <div>
            <h3>User Information</h3>
            <p>Name: <?php echo $user_row['name']; ?></p>
            <p>Surname: <?php echo $user_row['surname']; ?></p>
            <p>Email: <?php echo $user_row['email']; ?></p>
        </div>
    <?php endif; ?>

    <!-- Form for selling a book -->
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="Title">Title:</label>
        <input type="text" id="Title" name="Title" required><br>

        <label for="Author">Author:</label>
        <input type="text" id="Author" name="Author" required><br>

        <label for="Location">Location:</label>
        <input type="text" id="Location" name="Location"><br>

        <label for="Language">Language:</label>
        <input type="text" id="Language" name="Language"><br>

        <label for="Dimensions">Dimensions:</label>
        <input type="text" id="Dimensions" name="Dimensions"><br>

        <label for="Review">Review:</label>
        <textarea id="Review" name="Review"></textarea><br>

        <label for="Edition">Edition:</label>
        <input type="text" id="Edition" name="Edition"><br>

        <label for="Publication">Publication:</label>
        <input type="text" id="Publication" name="Publication"><br>

        <label for="Pages">Pages:</label>
        <input type="number" id="Pages" name="Pages"><br>

        <label for="Price">Price:</label>
        <input type="number" id="Price" name="Price"><br>

        <label for="category_name">Category:</label>
        <select id="category_name" name="category_name">
            <option value="Arts">Arts</option>
            <option value="Pure Science">Pure Science</option>
            <option value="CLAT">CLAT</option>
            <option value="MPSC">MPSC</option>
            <option value="Agri">Agri</option>
            <option value="Pharmacy">Pharmacy</option>
            <option value="LAW">LAW</option>
            <option value="Medical">Medical</option>
            <option value="Engineering">Engineering</option>
            <option value="UPSC">UPSC</option>
            <option value="Non-fiction">Non-fiction</option>
            <option value="Fiction">Fiction</option>
            <option value="Upto 10th">Upto 10th</option>
            <option value="GATE">GATE</option>
            <option value="CAT">CAT</option>
            <option value="CET">CET</option>
            <option value="NEET">NEET</option>
            <option value="JEE">JEE</option>
            <option value="HSC">HSC</option>
            <option value="SSC">SSC</option>
        </select><br>

        <label for="image">Book Image:</label>
        <input type="file" id="image" name="image" accept="image/*" required><br>

        <input type="submit" name="sell_book" value="Sell Book">
    </form>
</body>
</html>




