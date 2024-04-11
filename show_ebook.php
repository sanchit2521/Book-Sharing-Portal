<?php
include 'config.php';

// Fetch e-book data from the database
$query = "SELECT * FROM ebooks";
$result = mysqli_query($conn, $query);

$ebooks = array();
while ($row = mysqli_fetch_assoc($result)) {
    $ebooks[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Books</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px; /* Updated max-width */
            margin: 55px auto 0; /* Adjusted margin */
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: grid; /* Use grid layout */
            grid-template-columns: repeat(auto-fit, minmax(17rem, 1fr)); /* Adjusted grid template columns */
            gap: 1.5rem; /* Added gap between items */
        }

        .ebook {
            text-align: center;
            padding: 10px;
            border-radius: .5rem;
            border: 2px solid rgb(9, 218, 255);
        }

        .ebook h3 {
            margin-top: 0;
        }

        .ebook img {
            height: 150px;
            display: inline-block;
        }

        .ebook a {
            display: block;
            padding: 5px 10px;
            background-color: #4caf50;
            color: #fff;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .ebook a:hover {
            background-color: #45a049;
        }


    </style>
</head>
<body>
    <div class="container">
        <!-- <h2>E-Books</h2> -->
        <?php foreach ($ebooks as $ebook): ?>
        <div class="ebook">
            <img src="ebooks/<?php echo $ebook['image']; ?>" alt="E-book Cover">
            <h3><?php echo $ebook['title']; ?></h3>
            <p>Author: <?php echo $ebook['author']; ?></p>
            <a href="ebooks/<?php echo $ebook['file_path']; ?>" download>Download</a>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
