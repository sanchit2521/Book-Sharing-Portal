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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate to NGO</title>
    <style>
        .container {
    max-width: 500px;
    margin: 50px auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f9f9f9;
    }

    h2 {
        text-align: center;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }

    input[type="checkbox"] {
        margin-right: 5px;
    }

    button {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    button:hover {
        background-color: #45a049;
    }

    </style>
</head>
<body>
    <?php include 'admin_header.php'; ?>

    <div class="container">
        <h2>Select NGO to Donate</h2>
        <form action="donate_process.php" method="POST">
            <?php
            include 'config.php';

            $sql = "SELECT * FROM users_info WHERE user_type='NGO'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<label><input type="checkbox" name="ngo[]" value="' . $row['Id'] . '"> ' . $row['name'] . '</label><br>';
                }
            } else {
                echo "No NGOs found.";
            }

            mysqli_close($conn);
            ?>
            <button href = "donate_process.php" type="submit" name="donate">Donate to Selected NGOs</button>
        </form>
    </div>
</body>
</html>
