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
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .ebook {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .ebook h3 {
            margin-top: 0;
        }
        .ebook img {
            max-width: 100%;
            height: auto;
            margin-top: 10px;
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
        <h2>E-Books</h2>
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
