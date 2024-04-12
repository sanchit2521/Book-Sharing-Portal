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

if (isset($_POST['rent_book'])) {
    // Validate and sanitize inputs
    function sanitize($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $title = sanitize($_POST['title']);
    $author = sanitize($_POST['author']);
    $image = $_FILES["image"]["name"];
    $image_tmp_name = $_FILES["image"]["tmp_name"];
    $image_folder = "./rented_books/" . $image;
    $rent_amount = (int)$_POST['rent_amount'];
    $duration_days = (int)$_POST['duration_days'];
    $location = sanitize($_POST['location']);
    $description = sanitize($_POST['description']);

    // Check if all required fields are filled
    if (empty($title) || empty($author) || empty($image) || empty($rent_amount) || empty($duration_days) || empty($location) || empty($description)) {
        $message = 'Please fill in all required fields';
    } else {
        if (move_uploaded_file($image_tmp_name, $image_folder)) {
            // Image uploaded successfully
            $insert_query = "INSERT INTO rented_book (title, author, image, rent_amount, duration_days, location, description, rented_by) 
                            VALUES ('$title', '$author', '$image', $rent_amount, $duration_days, '$location', '$description', '$user_id')";

            if (mysqli_query($conn, $insert_query)) {
                $message = 'Book rented successfully';
                // Redirect user to prevent form resubmission
                header('Location: index.php');
                exit;
            } else {
                $message = 'Failed to rent book';
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
    <title>Rent a Book</title>
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
        input[type="file"] {
            margin-top: 5px;
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
    <h2>Rent a Book</h2>
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

    <!-- Form for renting a book -->
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required><br>

        <label for="author">Author:</label>
        <input type="text" id="author" name="author" required><br>

        <label for="image">Book Image:</label>
        <input type="file" id="image" name="image" accept="image/*" required><br>

        <label for="rent_amount">Rent Amount:</label>
        <input type="number" id="rent_amount" name="rent_amount" required><br>

        <label for="duration_days">Duration (in Days):</label>
        <input type="number" id="duration_days" name="duration_days" required><br>

        <label for="location">Location:</label>
        <input type="text" id="location" name="location" required><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br>

        <input type="submit" name="rent_book" value="Rent Book">
    </form>
</body>
</html>
