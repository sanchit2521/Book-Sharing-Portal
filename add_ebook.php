<?php
include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit(); // Exit after redirection
}

$message = array(); // Initialize an array to store messages

if (isset($_POST['add_ebook'])) {
    // Escape user inputs for security
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);

    // File handling for image
    $img = $_FILES["image"]["name"];
    $img_temp_name = $_FILES["image"]["tmp_name"];
    $img_file = "./ebooks/" . $img;

    // File handling for e-book file
    $file_name = $_FILES["file"]["name"];
    $file_temp_name = $_FILES["file"]["tmp_name"];
    $file_path = "./ebooks/" . $file_name;

    // Validate input
    if (empty($title) || empty($author) || empty($file_name || empty($img))) {
        $message[] = 'Please fill in all the fields';
    } else {
        // Move uploaded image file
        if (move_uploaded_file($img_temp_name, $img_file)) {
            // Move uploaded e-book file
            if (move_uploaded_file($file_temp_name, $file_path)) {
                // Insert record
                $add_ebook = mysqli_query($conn, "INSERT INTO ebooks (`title`, `author`, `file_path`, `image`) 
                    VALUES ('$title', '$author', '$file_name', '$img')");

                if ($add_ebook) {
                    $message[] = 'New E-book Added Successfully';
                } else {
                    $message[] = 'Failed to add e-book';
                }
            } else {
                $message[] = 'Failed to move e-book file';
            }
        } else {
            $message[] = 'Failed to move image file';
        }
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
    <title>Add E-books</title>
    <style>
        

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container_box {
            width: 400px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .text_field {
        width: calc(90% - 10px); /* Increased horizontal length */
        margin-bottom: 10px;
        padding: 10px;
        border: 1px solid #6c757d; /* Changed border color to a dark gray */
        border-radius: 5px;
        font-size: 16px;
        }


        .btn {
            background-color: #4caf50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .message {
            background-color: #f44336;
            color: white;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .update_btn {
            background-color: #008CBA;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            margin-bottom: 20px;
        }

        .update_btn:hover {
            background-color: #005580;
        }

    </style>
</head>

<body>
    <?php include './admin_header.php'; ?>
    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '
                <div class="message" id="messages"><span>' . $msg . '</span>
                </div>
            ';
        }
    }
    ?>

    <a class="update_btn" style="position: fixed; z-index:100;" href="show_ebook.php">See All E-books</a>
    <div class="container_box">
        <form action="" method="POST" enctype="multipart/form-data">
            <h3>Add E-books To <a href="index.php"><span>Book Sharing Portal </span></a></h3>
            <label for="title">E-book Title:</label>
            <input type="text" id="title" name="title" placeholder="Enter E-book Title" class="text_field">

            <label for="author">Author Name:</label>
            <input type="text" id="author" name="author" placeholder="Enter Author name" class="text_field">

            <label for="image">E-book Image:</label>
            <input type="file" id="image" name="image" class="text_field" accept="image/*">

            <label for="file">E-book File:</label>
            <input type="file" id="file" name="file" accept=".pdf, .doc, .docx" class="text_field">

            <input type="submit" value="Add E-book" name="add_ebook" class="btn text_field">


        </form>
    </div>
</body>

</html>
