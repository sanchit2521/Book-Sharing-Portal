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
$user_query = "SELECT name FROM users_info WHERE Id = '$user_id'";
$user_result = mysqli_query($conn, $user_query);
$user_row = mysqli_fetch_assoc($user_result);

// Check if user information exists
if (!$user_row) {
    $message = 'User information not found';
}

if (isset($_POST['donate_book'])) {
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
    $user_name = $user_row['name']; // Get user's name

    // Check if all required fields are filled
    if (empty($title) || empty($author) || empty($location)) {
        $message = 'Please fill in all required fields';
    } else {
        // Insert book details into the database
        $image = $_FILES["image"]["name"];
        $image_tmp_name = $_FILES["image"]["tmp_name"];
        $image_folder = "./donated_books/" . $image;

        if (move_uploaded_file($image_tmp_name, $image_folder)) {
            // Image uploaded successfully
            $insert_query = "INSERT INTO donated_books (title, author, location, image_path, name) 
                            VALUES ('$title', '$author', '$location', '$image', '$user_name')";

            if (mysqli_query($conn, $insert_query)) {
                $message = 'Book donated successfully';
                // Redirect user to prevent form resubmission
                //header('Location: index.php');
                //exit;
            } else {
                $message = 'Failed to donate book';
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
    <title>Donate Book</title>
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
        input[type="text"], input[type="file"] {
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
    <h2>Donate a Book</h2>
    <!-- Display error/success message -->
    <?php if (!empty($message)) : ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

   

    <!-- Form for donating a book -->
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="Title">Title:</label>
        <input type="text" id="Title" name="Title" required><br>

        <label for="Author">Author:</label>
        <input type="text" id="Author" name="Author" required><br>

        <label for="Location">Location:</label>
        <input type="text" id="Location" name="Location" required><br>

        <label for="image">Book Image:</label>
        <input type="file" id="image" name="image" accept="image/*" required><br>

        <input type="submit" name="donate_book" value="Donate Book">
    </form>
</body>
</html>
