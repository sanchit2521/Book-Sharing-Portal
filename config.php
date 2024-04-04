<?php
try {
    $conn = new mysqli('localhost', 'root', '', 'bookflix', 4306);
} catch (mysqli_sql_exception $e) {
    die('Connection Failed: ' . $e->getMessage());
}
?>
