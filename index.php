<?php
include 'config.php';

error_reporting(0);
session_start();

$user_id = $_SESSION['user_id'];




if (isset($_POST['buy_now'])) {
    $book_id = $_POST['book_id'];
    $book_name = $_POST['book_name'];
    $book_image = $_POST['book_image'];
    $added_by = $_POST['added_by']; // Assuming 'added_by' is sent along with other book details

    // Fetch price and added_by from book_info table based on book_id
    $book_info_query = $conn->query("SELECT Price, added_by FROM book_info WHERE book_id = '$book_id'");
    if ($book_info_query->num_rows > 0) {
        $book_info = $book_info_query->fetch_assoc();
        $book_price = $book_info['Price'];
        $added_by = $book_info['added_by']; // Update $added_by with the value from the database

        // Check if the book already exists in the cart
        $select_book = $conn->query("SELECT * FROM cart WHERE book_id = '$book_id' AND user_id = '$user_id'");
        if ($select_book->num_rows > 0) {
            // If the book is already in the cart, increase the quantity by 1
            $update_quantity_query = "UPDATE cart SET quantity = quantity + 1 WHERE book_id = '$book_id' AND user_id = '$user_id'";
            $conn->query($update_quantity_query) or die('Update quantity query failed');
            $message[] = 'Quantity updated successfully';
        } else {
            // If the book is not in the cart, add it with quantity = 1
            $book_quantity = 1;
            $total_price = $book_price * $book_quantity;
            // Add 'added_by' column to cart table and include it in the insert query
            $insert_query = "INSERT INTO cart (`user_id`, `book_id`, `name`, `price`, `image`, `quantity`, `total`, `added_by`) 
                            VALUES ('$user_id', '$book_id', '$book_name', '$book_price', '$book_image', '$book_quantity', '$total_price', '$added_by')";
            $conn->query($insert_query) or die('Add to cart query failed');
            $message[] = 'Book added to cart successfully';
        }
    } else {
        $message[] = 'Failed to add book to cart. Book price or added_by information not found.';
    }

    // Redirect to the cart page after adding the book to the cart
    header('Location: cart.php');
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/hello.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">

    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet" />
    <title>Book Sharing portal</title>

    <style>
        img {
            border: none;
        }
        .message {
  position: sticky;
  top: 0;
  margin: 0 auto;
  width: 61%;
  background-color: #fff;
  padding: 6px 9px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  z-index: 100;
  gap: 0px;
  border: 2px solid rgb(68, 203, 236);
  border-top-right-radius: 8px;
  border-bottom-left-radius: 8px;
}
.message span {
  font-size: 22px;
  color: rgb(240, 18, 18);
  font-weight: 400;
}
.message i {
  cursor: pointer;
  color: rgb(3, 227, 235);
  font-size: 15px;
}
    </style>
</head>

<body>
    <?php include 'index_header.php' ?>
    <?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo '
        <div class="message" id= "messages"><span>' . $message . '</span>
        </div>
        ';
        }
    }
    ?>


    <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="d-block w-100 " src="https://source.unsplash.com/2200x800/?books" alt="First slide">
            </div>

            <div class="carousel-item">
                <img class="d-block w-100" src="https://source.unsplash.com/2200x800/?novel books" alt="Second slide">
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="https://source.unsplash.com/2200x800/?pyshological books" alt="Third slide">
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div> -->

    <section id="New">
    <div class="container px-5 mx-auto">
        <h2 class="m-8 font-extrabold text-4xl text-center border-t-2 " style="color: rgb(0, 167, 245);">
            New Arrived
        </h2>
    </div>
    </section>
    <section class="show-products">
        <div class="box-container">
            <?php
            $select_book = mysqli_query($conn, "SELECT * FROM `book_info` ORDER BY `date` DESC LIMIT 8") or die('query failed');
            if (mysqli_num_rows($select_book) > 0) {
                while ($fetch_book = mysqli_fetch_assoc($select_book)) {
                    // Fetch user details for each book
                    $added_by = $fetch_book['added_by'];
                    $select_user = mysqli_query($conn, "SELECT `name`, `surname`, `user_type` FROM `users_info` WHERE `Id` = '$added_by'");
                    $user_details = mysqli_fetch_assoc($select_user);
            ?>
                    <div class="box" style="width: 255px; height:355px;">
                        <a href="book_details.php?details=<?php echo $fetch_book['book_id']; ?>&title=<?php echo $fetch_book['Title']; ?>">
                            <img style="height: 200px;width: 125px;margin: auto;" class="books_images" src="added_books/<?php echo $fetch_book['image']; ?>" alt="">
                        </a>
                        <div style="text-align:left ;">
                            <div style="font-weight: 500; font-size:18px; text-align: left; overflow: auto;" class="Title"> <?php echo $fetch_book['Title']; ?></div>
                        </div>
                        <div class="Price">Price: ₹ <?php echo $fetch_book['Price']; ?>/-</div>
                        <?php if ($user_details) { ?>
                            <div class="AddedBy">Added By: <?php echo $user_details['name'] . ' ' . $user_details['surname'] . ' (' . $user_details['user_type'] . ')'; ?></div>
                        <?php } ?>
                        <form action="" method="POST">
                            <input class="hidden_input" type="hidden" name="book_name" value="<?php echo $fetch_book['Title'] ?>">
                            <input class="hidden_input" type="hidden" name="book_id" value="<?php echo $fetch_book['book_id'] ?>">
                            <input class="hidden_input" type="hidden" name="book_image" value="<?php echo $fetch_book['image'] ?>">
                            <input class="hidden_input" type="hidden" name="book_Price" value="<?php echo $fetch_book['Price'] ?>">
                            <button type="submit" name="buy_now"><img src="./images/cart2.png"> BUY NOW</button>
                            <button onclick="myFunction()" name="add_to_cart">
                                <a href="show_info.php?details=<?php echo $fetch_book['book_id']; ?>&name=<?php echo $fetch_book['Title']; ?>" class="update_btn">Know More</a>
                            </button>
                        </form>
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">No products added yet!</p>';
            }
            ?>
        </div>
    </section>



    
    
    <section id="Arts">
        <div class="container px-5 mx-auto">
            <h2 class="text-gray-400 m-8 font-extrabold text-4xl text-center border-t-2 text-red-800" style="color: rgb(0, 167, 245);" >
                Arts
            </h2>
        </div>
    </section>
    <section class="show-products">
        <div class="box-container">
            <?php
            $select_book = mysqli_query($conn, "SELECT * FROM `book_info` where category_name='Arts'") or die('query failed');
            if (mysqli_num_rows($select_book) > 0) {
                while ($fetch_book = mysqli_fetch_assoc($select_book)) {
                    $added_by = $fetch_book['added_by'];
                    $select_user = mysqli_query($conn, "SELECT `name`, `surname`, `user_type` FROM `users_info` WHERE `Id` = '$added_by'");
                    $user_details = mysqli_fetch_assoc($select_user);
            ?>

                    <div class="box" style="width: 255px;height: 355px;">
                        <a href="book_details.php?details=<?php echo $fetch_book['book_id'];
                                                            echo '-Title=', $fetch_book['Title']; ?>"> <img style="height: 200px;width: 125px;margin: auto;" class="books_images" src="added_books/<?php echo $fetch_book['image']; ?>" alt=""></a>
                        <div style="text-align:left ;">

                            <div style="font-weight: 500; font-size:18px; text-align: center; " class="Title"> <?php echo $fetch_book['Title']; ?></div>
                        </div>
                        <div class="Price">Price: ₹ <?php echo $fetch_book['Price']; ?>/-</div>
                        <?php if ($user_details) { ?>
                            <div class="AddedBy">Added By: <?php echo $user_details['name'] . ' ' . $user_details['surname'] . ' (' . $user_details['user_type'] . ')'; ?></div>
                        <?php } ?>
                        <!-- <button name="add_cart"><img src="./images/cart2.png" alt=""></button> -->
                        <form action="" method="POST">
                            <input class="hidden_input" type="hidden" name="book_name" value="<?php echo $fetch_book['Title'] ?>">
                            <input class="hidden_input" type="hidden" name="book_image" value="<?php echo $fetch_book['image'] ?>">
                            <input class="hidden_input" type="hidden" name="book_Price" value="<?php echo $fetch_book['Price'] ?>">
                            <button name="buy_now"><img src="./images/cart2.png" > BUY NOW</button>
                            <button onclick="myFunction()" name="add_to_cart"><img src="">
                                <a href="show_info.php?details=<?php echo $fetch_book['book_id']; ?>&name=<?php echo $fetch_book['Title']; ?>" class="update_btn">Know More</a>
                            
                        
                        </form>
                        <!-- <button name="add_to_cart" ><img src="./images/cart2.png" alt="Add to cart"></button> -->
                        <!-- <input type="submit" name="add_cart" value="cart"> -->
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>
    </section>
   
    <section id="Pure Science">
        <div class="container px-5 mx-auto">
            <h2 class="text-gray-400 m-8 font-extrabold text-4xl text-center border-t-2 text-red-800"style="color: rgb(0, 167, 245);">
            Pure Science
            </h2>
        </div>
    </section>
    <section class="show-products">
        <div class="box-container">

            <?php
            $select_book = mysqli_query($conn, "SELECT * FROM `book_info` where category_name='Pure Science'") or die('query failed');
            if (mysqli_num_rows($select_book) > 0) {
                while ($fetch_book = mysqli_fetch_assoc($select_book)) {
                    $added_by = $fetch_book['added_by'];
                    $select_user = mysqli_query($conn, "SELECT `name`, `surname`, `user_type` FROM `users_info` WHERE `Id` = '$added_by'");
                    $user_details = mysqli_fetch_assoc($select_user);
            ?>

                    <div class="box" style="width: 255px;height: 355px;">
                        <a href="book_details.php?details=<?php echo $fetch_book['book_id'];
                                                            echo '-Title=', $fetch_book['Title']; ?>"> <img style="height: 200px;width: 125px;margin: auto;" class="books_images" src="added_books/<?php echo $fetch_book['image']; ?>" alt=""></a>
                        <div style="text-align:left ;">

                            <div style="font-weight: 500; font-size:18px; text-align: center;" class="Title"> <?php echo $fetch_book['Title']; ?></div>
                        </div>
                        <div class="Price">Price: ₹ <?php echo $fetch_book['Price']; ?>/-</div>
                        <?php if ($user_details) { ?>
                            <div class="AddedBy">Added By: <?php echo $user_details['name'] . ' ' . $user_details['surname'] . ' (' . $user_details['user_type'] . ')'; ?></div>
                        <?php } ?>
                        <!-- <button name="add_cart"><img src="./images/cart2.png" alt=""></button> -->
                        <form action="" method="POST">
                            <input class="hidden_input" type="hidden" name="book_name" value="<?php echo $fetch_book['Title'] ?>">
                            <input class="hidden_input" type="hidden" name="book_image" value="<?php echo $fetch_book['image'] ?>">
                            <input class="hidden_input" type="hidden" name="book_Price" value="<?php echo $fetch_book['Price'] ?>">
                            <button name="buy_now"><img src="./images/cart2.png" > BUY NOW</button>
                            <button onclick="myFunction()" name="add_to_cart"><img src="">
                                <a href="show_info.php?details=<?php echo $fetch_book['book_id']; ?>&name=<?php echo $fetch_book['Title']; ?>" class="update_btn">Know More</a>
                            
                        </form>
                        <!-- <button name="add_to_cart" ><img src="./images/cart2.png" alt="Add to cart"></button> -->
                        <!-- <input type="submit" name="add_cart" value="cart"> -->
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>
    </section>
    <section id="CLAT">

        <div class="container px-5 mx-auto">
            <h2 class="text-gray-400 m-8 font-extrabold text-4xl text-center border-t-2 text-red-800" style="color: rgb(0, 167, 245);">
            CLAT
            </h2>
        </div>
    </section>
    <section class="show-products">
        <div class="box-container">

            <?php
            $select_book = mysqli_query($conn, "SELECT * FROM `book_info` Where category_name='CLAT'") or die('query failed');
            if (mysqli_num_rows($select_book) > 0) {
                while ($fetch_book = mysqli_fetch_assoc($select_book)) {
                    $added_by = $fetch_book['added_by'];
                    $select_user = mysqli_query($conn, "SELECT `name`, `surname`, `user_type` FROM `users_info` WHERE `Id` = '$added_by'");
                    $user_details = mysqli_fetch_assoc($select_user);
            ?>

                    <div class="box" style="width: 255px;height: 355px;">
                        <a href="book_details.php?details=<?php echo $fetch_book['book_id'];
                                                            echo '-Title=', $fetch_book['Title']; ?>"> <img style="height: 200px;width: 125px;margin: auto;" class="books_images" src="added_books/<?php echo $fetch_book['image']; ?>" alt=""></a>
                        <div style="text-align:left ;">

                            <div style="font-weight: 500; font-size:18px; text-align: center;" class="Title"> <?php echo $fetch_book['Title']; ?></div>
                        </div>
                        <div class="Price">Price: ₹ <?php echo $fetch_book['Price']; ?>/-</div>
                        <?php if ($user_details) { ?>
                            <div class="AddedBy">Added By: <?php echo $user_details['name'] . ' ' . $user_details['surname'] . ' (' . $user_details['user_type'] . ')'; ?></div>
                        <?php } ?>
                        <!-- <button name="add_cart"><img src="./images/cart2.png" alt=""></button> -->
                        <form action="" method="POST">
                            <input class="hidden_input" type="hidden" name="book_name" value="<?php echo $fetch_book['Title'] ?>">
                            <input class="hidden_input" type="hidden" name="book_image" value="<?php echo $fetch_book['image'] ?>">
                            <input class="hidden_input" type="hidden" name="book_Price" value="<?php echo $fetch_book['Price'] ?>">
                            <button name="buy_now"><img src="./images/cart2.png" > BUY NOW</button>
                            <button onclick="myFunction()" name="add_to_cart"><img src="">
                                <a href="show_info.php?details=<?php echo $fetch_book['book_id']; ?>&name=<?php echo $fetch_book['Title']; ?>" class="update_btn">Know More</a>
                            
                        </form>
                        <!-- <button name="add_to_cart" ><img src="./images/cart2.png" alt="Add to cart"></button> -->
                        <!-- <input type="submit" name="add_cart" value="cart"> -->
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>
    </section>

    <section id="MPSC">
        </div>

        <div class="container px-5 mx-auto">
            <h2 class="text-gray-400 m-8 font-extrabold text-4xl text-center border-t-2 text-red-800" style="color: rgb(0, 167, 245);" >
                MPSC
            </h2>
        </div>
    </section>
    <section class="show-products">
        <div class="box-container">

            <?php
            $select_book = mysqli_query($conn, "SELECT * FROM `book_info` where category_name='MPSC'") or die('query failed');
            if (mysqli_num_rows($select_book) > 0) {
                while ($fetch_book = mysqli_fetch_assoc($select_book)) {
                    $added_by = $fetch_book['added_by'];
                    $select_user = mysqli_query($conn, "SELECT `name`, `surname`, `user_type` FROM `users_info` WHERE `Id` = '$added_by'");
                    $user_details = mysqli_fetch_assoc($select_user);
            ?>

                    <div class="box" style="width: 255px;height: 355px;">
                        <a href="book_details.php?details=<?php echo $fetch_book['book_id'];
                                                            echo '-Title=', $fetch_book['Title']; ?>"> <img style="height: 200px;width: 125px;margin: auto;" class="books_images" src="added_books/<?php echo $fetch_book['image']; ?>" alt=""></a>
                        <div style="text-align:left ;">

                            <div style="font-weight: 500; font-size:18px; text-align: center; " class="Title"> <?php echo $fetch_book['Title']; ?></div>
                        </div>
                        <div class="Price">Price: ₹ <?php echo $fetch_book['Price']; ?>/-</div>
                        <?php if ($user_details) { ?>
                            <div class="AddedBy">Added By: <?php echo $user_details['name'] . ' ' . $user_details['surname'] . ' (' . $user_details['user_type'] . ')'; ?></div>
                        <?php } ?>
                        <!-- <button name="add_cart"><img src="./images/cart2.png" alt=""></button> -->
                        <form action="" method="POST">
                            <input class="hidden_input" type="hidden" name="book_name" value="<?php echo $fetch_book['Title'] ?>">
                            <input class="hidden_input" type="hidden" name="book_image" value="<?php echo $fetch_book['image'] ?>">
                            <input class="hidden_input" type="hidden" name="book_Price" value="<?php echo $fetch_book['Price'] ?>">
                            <button name="buy_now"><img src="./images/cart2.png" > BUY NOW</button>
                            <button onclick="myFunction()" name="add_to_cart"><img src="">
                                <a href="show_info.php?details=<?php echo $fetch_book['book_id']; ?>&name=<?php echo $fetch_book['Title']; ?>" class="update_btn">Know More</a>
                            
                        
                        </form>
                        <!-- <button name="add_to_cart" ><img src="./images/cart2.png" alt="Add to cart"></button> -->
                        <!-- <input type="submit" name="add_cart" value="cart"> -->
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>
    </section>



    <section id="Agri">
        </div>

        <div class="container px-5 mx-auto">
            <h2 class="text-gray-400 m-8 font-extrabold text-4xl text-center border-t-2 text-red-800" style="color: rgb(0, 167, 245);" >
                Agri
            </h2>
        </div>
    </section>
    <section class="show-products">
        <div class="box-container">

            <?php
            $select_book = mysqli_query($conn, "SELECT * FROM `book_info` where category_name='Agri'") or die('query failed');
            if (mysqli_num_rows($select_book) > 0) {
                while ($fetch_book = mysqli_fetch_assoc($select_book)) {
                    $added_by = $fetch_book['added_by'];
                    $select_user = mysqli_query($conn, "SELECT `name`, `surname`, `user_type` FROM `users_info` WHERE `Id` = '$added_by'");
                    $user_details = mysqli_fetch_assoc($select_user);
            ?>

                    <div class="box" style="width: 255px;height: 355px;">
                        <a href="book_details.php?details=<?php echo $fetch_book['book_id'];
                                                            echo '-Title=', $fetch_book['Title']; ?>"> <img style="height: 200px;width: 125px;margin: auto;" class="books_images" src="added_books/<?php echo $fetch_book['image']; ?>" alt=""></a>
                        <div style="text-align:left ;">

                            <div style="font-weight: 500; font-size:18px; text-align: center; " class="Title"> <?php echo $fetch_book['Title']; ?></div>
                        </div>
                        <div class="Price">Price: ₹ <?php echo $fetch_book['Price']; ?>/-</div>
                        <?php if ($user_details) { ?>
                            <div class="AddedBy">Added By: <?php echo $user_details['name'] . ' ' . $user_details['surname'] . ' (' . $user_details['user_type'] . ')'; ?></div>
                        <?php } ?>
                        <!-- <button name="add_cart"><img src="./images/cart2.png" alt=""></button> -->
                        <form action="" method="POST">
                            <input class="hidden_input" type="hidden" name="book_name" value="<?php echo $fetch_book['Title'] ?>">
                            <input class="hidden_input" type="hidden" name="book_image" value="<?php echo $fetch_book['image'] ?>">
                            <input class="hidden_input" type="hidden" name="book_Price" value="<?php echo $fetch_book['Price'] ?>">
                            <button name="buy_now"><img src="./images/cart2.png" > BUY NOW</button>
                            <button onclick="myFunction()" name="add_to_cart"><img src="">
                                <a href="show_info.php?details=<?php echo $fetch_book['book_id']; ?>&name=<?php echo $fetch_book['Title']; ?>" class="update_btn">Know More</a>
                            
                        
                        </form>
                        <!-- <button name="add_to_cart" ><img src="./images/cart2.png" alt="Add to cart"></button> -->
                        <!-- <input type="submit" name="add_cart" value="cart"> -->
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>
    </section>





    <section id="Pharmacy">
        </div>

        <div class="container px-5 mx-auto">
            <h2 class="text-gray-400 m-8 font-extrabold text-4xl text-center border-t-2 text-red-800" style="color: rgb(0, 167, 245);" >
            Pharmacy
            </h2>
        </div>
    </section>
    <section class="show-products">
        <div class="box-container">

            <?php
            $select_book = mysqli_query($conn, "SELECT * FROM `book_info` where category_name='Pharmacy'") or die('query failed');
            if (mysqli_num_rows($select_book) > 0) {
                while ($fetch_book = mysqli_fetch_assoc($select_book)) {
                    $added_by = $fetch_book['added_by'];
                    $select_user = mysqli_query($conn, "SELECT `name`, `surname`, `user_type` FROM `users_info` WHERE `Id` = '$added_by'");
                    $user_details = mysqli_fetch_assoc($select_user);
            ?>

                    <div class="box" style="width: 255px;height: 355px;">
                        <a href="book_details.php?details=<?php echo $fetch_book['book_id'];
                                                            echo '-Title=', $fetch_book['Title']; ?>"> <img style="height: 200px;width: 125px;margin: auto;" class="books_images" src="added_books/<?php echo $fetch_book['image']; ?>" alt=""></a>
                        <div style="text-align:left ;">

                            <div style="font-weight: 500; font-size:18px; text-align: center; " class="Title"> <?php echo $fetch_book['Title']; ?></div>
                        </div>
                        <div class="Price">Price: ₹ <?php echo $fetch_book['Price']; ?>/-</div>
                        
                        <?php if ($user_details) { ?>
                            <div class="AddedBy">Added By: <?php echo $user_details['name'] . ' ' . $user_details['surname'] . ' (' . $user_details['user_type'] . ')'; ?></div>
                        <?php } ?><!-- <button name="add_cart"><img src="./images/cart2.png" alt=""></button> -->
                        <form action="" method="POST">
                            <input class="hidden_input" type="hidden" name="book_name" value="<?php echo $fetch_book['Title'] ?>">
                            <input class="hidden_input" type="hidden" name="book_image" value="<?php echo $fetch_book['image'] ?>">
                            <input class="hidden_input" type="hidden" name="book_Price" value="<?php echo $fetch_book['Price'] ?>">
                            <button name="buy_now"><img src="./images/cart2.png" > BUY NOW</button>
                            <button onclick="myFunction()" name="add_to_cart"><img src="">
                                <a href="show_info.php?details=<?php echo $fetch_book['book_id']; ?>&name=<?php echo $fetch_book['Title']; ?>" class="update_btn">Know More</a>
                            
                        
                        </form>
                        <!-- <button name="add_to_cart" ><img src="./images/cart2.png" alt="Add to cart"></button> -->
                        <!-- <input type="submit" name="add_cart" value="cart"> -->
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>
    </section>






    <section id="LAW">
        </div>

        <div class="container px-5 mx-auto">
            <h2 class="text-gray-400 m-8 font-extrabold text-4xl text-center border-t-2 text-red-800" style="color: rgb(0, 167, 245);" >
                LAW
            </h2>
        </div>
    </section>
    <section class="show-products">
        <div class="box-container">

            <?php
            $select_book = mysqli_query($conn, "SELECT * FROM `book_info` where category_name='LAW'") or die('query failed');
            if (mysqli_num_rows($select_book) > 0) {
                while ($fetch_book = mysqli_fetch_assoc($select_book)) {
                    $added_by = $fetch_book['added_by'];
                    $select_user = mysqli_query($conn, "SELECT `name`, `surname`, `user_type` FROM `users_info` WHERE `Id` = '$added_by'");
                    $user_details = mysqli_fetch_assoc($select_user);
            ?>

                    <div class="box" style="width: 255px;height: 355px;">
                        <a href="book_details.php?details=<?php echo $fetch_book['book_id'];
                                                            echo '-Title=', $fetch_book['Title']; ?>"> <img style="height: 200px;width: 125px;margin: auto;" class="books_images" src="added_books/<?php echo $fetch_book['image']; ?>" alt=""></a>
                        <div style="text-align:left ;">

                            <div style="font-weight: 500; font-size:18px; text-align: center; " class="Title"> <?php echo $fetch_book['Title']; ?></div>
                        </div>
                        <div class="Price">Price: ₹ <?php echo $fetch_book['Price']; ?>/-</div>
                        <?php if ($user_details) { ?>
                            <div class="AddedBy">Added By: <?php echo $user_details['name'] . ' ' . $user_details['surname'] . ' (' . $user_details['user_type'] . ')'; ?></div>
                        <?php } ?>
                        <!-- <button name="add_cart"><img src="./images/cart2.png" alt=""></button> -->
                        <form action="" method="POST">
                            <input class="hidden_input" type="hidden" name="book_name" value="<?php echo $fetch_book['Title'] ?>">
                            <input class="hidden_input" type="hidden" name="book_image" value="<?php echo $fetch_book['image'] ?>">
                            <input class="hidden_input" type="hidden" name="book_Price" value="<?php echo $fetch_book['Price'] ?>">
                            <button name="buy_now"><img src="./images/cart2.png" > BUY NOW</button>
                            <button onclick="myFunction()" name="add_to_cart"><img src="">
                                <a href="show_info.php?details=<?php echo $fetch_book['book_id']; ?>&name=<?php echo $fetch_book['Title']; ?>" class="update_btn">Know More</a>
                            
                        
                        </form>
                        <!-- <button name="add_to_cart" ><img src="./images/cart2.png" alt="Add to cart"></button> -->
                        <!-- <input type="submit" name="add_cart" value="cart"> -->
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>
    </section>





    <section id="Medical">
        </div>

        <div class="container px-5 mx-auto">
            <h2 class="text-gray-400 m-8 font-extrabold text-4xl text-center border-t-2 text-red-800" style="color: rgb(0, 167, 245);" >
            Medical
            </h2>
        </div>
    </section>
    <section class="show-products">
        <div class="box-container">

            <?php
            $select_book = mysqli_query($conn, "SELECT * FROM `book_info` where category_name='Medical'") or die('query failed');
            if (mysqli_num_rows($select_book) > 0) {
                while ($fetch_book = mysqli_fetch_assoc($select_book)) {
                    $added_by = $fetch_book['added_by'];
                    $select_user = mysqli_query($conn, "SELECT `name`, `surname`, `user_type` FROM `users_info` WHERE `Id` = '$added_by'");
                    $user_details = mysqli_fetch_assoc($select_user);
            ?>

                    <div class="box" style="width: 255px;height: 355px;">
                        <a href="book_details.php?details=<?php echo $fetch_book['book_id'];
                                                            echo '-Title=', $fetch_book['Title']; ?>"> <img style="height: 200px;width: 125px;margin: auto;" class="books_images" src="added_books/<?php echo $fetch_book['image']; ?>" alt=""></a>
                        <div style="text-align:left ;">

                            <div style="font-weight: 500; font-size:18px; text-align: center; " class="Title"> <?php echo $fetch_book['Title']; ?></div>
                        </div>
                        <div class="Price">Price: ₹ <?php echo $fetch_book['Price']; ?>/-</div>
                        <?php if ($user_details) { ?>
                            <div class="AddedBy">Added By: <?php echo $user_details['name'] . ' ' . $user_details['surname'] . ' (' . $user_details['user_type'] . ')'; ?></div>
                        <?php } ?>
                        <!-- <button name="add_cart"><img src="./images/cart2.png" alt=""></button> -->
                        <form action="" method="POST">
                            <input class="hidden_input" type="hidden" name="book_name" value="<?php echo $fetch_book['Title'] ?>">
                            <input class="hidden_input" type="hidden" name="book_image" value="<?php echo $fetch_book['image'] ?>">
                            <input class="hidden_input" type="hidden" name="book_Price" value="<?php echo $fetch_book['Price'] ?>">
                            <button name="buy_now"><img src="./images/cart2.png" > BUY NOW</button>
                            <button onclick="myFunction()" name="add_to_cart"><img src="">
                                <a href="show_info.php?details=<?php echo $fetch_book['book_id']; ?>&name=<?php echo $fetch_book['Title']; ?>" class="update_btn">Know More</a>
                            
                        
                        </form>
                        <!-- <button name="add_to_cart" ><img src="./images/cart2.png" alt="Add to cart"></button> -->
                        <!-- <input type="submit" name="add_cart" value="cart"> -->
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>
    </section>






    <section id="Engineering">
        </div>

        <div class="container px-5 mx-auto">
            <h2 class="text-gray-400 m-8 font-extrabold text-4xl text-center border-t-2 text-red-800" style="color: rgb(0, 167, 245);" >
            Engineering
            </h2>
        </div>
    </section>
    <section class="show-products">
        <div class="box-container">

            <?php
            $select_book = mysqli_query($conn, "SELECT * FROM `book_info` where category_name='Engineering'") or die('query failed');
            if (mysqli_num_rows($select_book) > 0) {
                while ($fetch_book = mysqli_fetch_assoc($select_book)) {
                    $added_by = $fetch_book['added_by'];
                    $select_user = mysqli_query($conn, "SELECT `name`, `surname`, `user_type` FROM `users_info` WHERE `Id` = '$added_by'");
                    $user_details = mysqli_fetch_assoc($select_user);
            ?>

                    <div class="box" style="width: 255px;height: 355px;">
                        <a href="book_details.php?details=<?php echo $fetch_book['book_id'];
                                                            echo '-Title=', $fetch_book['Title']; ?>"> <img style="height: 200px;width: 125px;margin: auto;" class="books_images" src="added_books/<?php echo $fetch_book['image']; ?>" alt=""></a>
                        <div style="text-align:left ;">

                            <div style="font-weight: 500; font-size:18px; text-align: center; " class="Title"> <?php echo $fetch_book['Title']; ?></div>
                        </div>
                        <div class="Price">Price: ₹ <?php echo $fetch_book['Price']; ?>/-</div>
                        <?php if ($user_details) { ?>
                            <div class="AddedBy">Added By: <?php echo $user_details['name'] . ' ' . $user_details['surname'] . ' (' . $user_details['user_type'] . ')'; ?></div>
                        <?php } ?>
                        <!-- <button name="add_cart"><img src="./images/cart2.png" alt=""></button> -->
                        <form action="" method="POST">
                            <input class="hidden_input" type="hidden" name="book_name" value="<?php echo $fetch_book['Title'] ?>">
                            <input class="hidden_input" type="hidden" name="book_image" value="<?php echo $fetch_book['image'] ?>">
                            <input class="hidden_input" type="hidden" name="book_Price" value="<?php echo $fetch_book['Price'] ?>">
                            <button name="buy_now"><img src="./images/cart2.png" > BUY NOW</button>
                            <button onclick="myFunction()" name="add_to_cart"><img src="">
                                <a href="show_info.php?details=<?php echo $fetch_book['book_id']; ?>&name=<?php echo $fetch_book['Title']; ?>" class="update_btn">Know More</a>
                            
                        
                        </form>
                        <!-- <button name="add_to_cart" ><img src="./images/cart2.png" alt="Add to cart"></button> -->
                        <!-- <input type="submit" name="add_cart" value="cart"> -->
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>
    </section>




    <section id="UPSC">
        </div>

        <div class="container px-5 mx-auto">
            <h2 class="text-gray-400 m-8 font-extrabold text-4xl text-center border-t-2 text-red-800" style="color: rgb(0, 167, 245);" >
                UPSC
            </h2>
        </div>
    </section>
    <section class="show-products">
        <div class="box-container">

            <?php
            $select_book = mysqli_query($conn, "SELECT * FROM `book_info` where category_name='UPSC'") or die('query failed');
            if (mysqli_num_rows($select_book) > 0) {
                while ($fetch_book = mysqli_fetch_assoc($select_book)) {
                    $added_by = $fetch_book['added_by'];
                    $select_user = mysqli_query($conn, "SELECT `name`, `surname`, `user_type` FROM `users_info` WHERE `Id` = '$added_by'");
                    $user_details = mysqli_fetch_assoc($select_user);
            ?>

                    <div class="box" style="width: 255px;height: 355px;">
                        <a href="book_details.php?details=<?php echo $fetch_book['book_id'];
                                                            echo '-Title=', $fetch_book['Title']; ?>"> <img style="height: 200px;width: 125px;margin: auto;" class="books_images" src="added_books/<?php echo $fetch_book['image']; ?>" alt=""></a>
                        <div style="text-align:left ;">

                            <div style="font-weight: 500; font-size:18px; text-align: center; " class="Title"> <?php echo $fetch_book['Title']; ?></div>
                        </div>
                        <div class="Price">Price: ₹ <?php echo $fetch_book['Price']; ?>/-</div>
                        <?php if ($user_details) { ?>
                            <div class="AddedBy">Added By: <?php echo $user_details['name'] . ' ' . $user_details['surname'] . ' (' . $user_details['user_type'] . ')'; ?></div>
                        <?php } ?>
                        <!-- <button name="add_cart"><img src="./images/cart2.png" alt=""></button> -->
                        <form action="" method="POST">
                            <input class="hidden_input" type="hidden" name="book_name" value="<?php echo $fetch_book['Title'] ?>">
                            <input class="hidden_input" type="hidden" name="book_image" value="<?php echo $fetch_book['image'] ?>">
                            <input class="hidden_input" type="hidden" name="book_Price" value="<?php echo $fetch_book['Price'] ?>">
                            <button name="buy_now"><img src="./images/cart2.png" > BUY NOW</button>
                            <button onclick="myFunction()" name="add_to_cart"><img src="">
                                <a href="show_info.php?details=<?php echo $fetch_book['book_id']; ?>&name=<?php echo $fetch_book['Title']; ?>" class="update_btn">Know More</a>
                            
                        
                        </form>
                        <!-- <button name="add_to_cart" ><img src="./images/cart2.png" alt="Add to cart"></button> -->
                        <!-- <input type="submit" name="add_cart" value="cart"> -->
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>
    </section>





    <section id="Non-fiction">
        </div>

        <div class="container px-5 mx-auto">
            <h2 class="text-gray-400 m-8 font-extrabold text-4xl text-center border-t-2 text-red-800" style="color: rgb(0, 167, 245);" >
                Non-fiction
            </h2>
        </div>
    </section>
    <section class="show-products">
        <div class="box-container">

            <?php
            $select_book = mysqli_query($conn, "SELECT * FROM `book_info` where category_name='Non-fiction'") or die('query failed');
            if (mysqli_num_rows($select_book) > 0) {
                while ($fetch_book = mysqli_fetch_assoc($select_book)) {
                    $added_by = $fetch_book['added_by'];
                    $select_user = mysqli_query($conn, "SELECT `name`, `surname`, `user_type` FROM `users_info` WHERE `Id` = '$added_by'");
                    $user_details = mysqli_fetch_assoc($select_user);
            ?>

                    <div class="box" style="width: 255px;height: 355px;">
                        <a href="book_details.php?details=<?php echo $fetch_book['book_id'];
                                                            echo '-Title=', $fetch_book['Title']; ?>"> <img style="height: 200px;width: 125px;margin: auto;" class="books_images" src="added_books/<?php echo $fetch_book['image']; ?>" alt=""></a>
                        <div style="text-align:left ;">

                            <div style="font-weight: 500; font-size:18px; text-align: center; " class="Title"> <?php echo $fetch_book['Title']; ?></div>
                        </div>
                        <div class="Price">Price: ₹ <?php echo $fetch_book['Price']; ?>/-</div>
                        <?php if ($user_details) { ?>
                            <div class="AddedBy">Added By: <?php echo $user_details['name'] . ' ' . $user_details['surname'] . ' (' . $user_details['user_type'] . ')'; ?></div>
                        <?php } ?>
                        <!-- <button name="add_cart"><img src="./images/cart2.png" alt=""></button> -->
                        <form action="" method="POST">
                            <input class="hidden_input" type="hidden" name="book_name" value="<?php echo $fetch_book['Title'] ?>">
                            <input class="hidden_input" type="hidden" name="book_image" value="<?php echo $fetch_book['image'] ?>">
                            <input class="hidden_input" type="hidden" name="book_Price" value="<?php echo $fetch_book['Price'] ?>">
                            <button name="buy_now"><img src="./images/cart2.png" > BUY NOW</button>
                            <button onclick="myFunction()" name="add_to_cart"><img src="">
                                <a href="show_info.php?details=<?php echo $fetch_book['book_id']; ?>&name=<?php echo $fetch_book['Title']; ?>" class="update_btn">Know More</a>
                            
                        
                        </form>
                        <!-- <button name="add_to_cart" ><img src="./images/cart2.png" alt="Add to cart"></button> -->
                        <!-- <input type="submit" name="add_cart" value="cart"> -->
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>
    </section>




    <section id="Fiction">
        </div>

        <div class="container px-5 mx-auto">
            <h2 class="text-gray-400 m-8 font-extrabold text-4xl text-center border-t-2 text-red-800" style="color: rgb(0, 167, 245);" >
                Fiction
            </h2>
        </div>
    </section>
    <section class="show-products">
        <div class="box-container">

            <?php
            $select_book = mysqli_query($conn, "SELECT * FROM `book_info` where category_name='Fiction'") or die('query failed');
            if (mysqli_num_rows($select_book) > 0) {
                while ($fetch_book = mysqli_fetch_assoc($select_book)) {
                    $added_by = $fetch_book['added_by'];
                    $select_user = mysqli_query($conn, "SELECT `name`, `surname`, `user_type` FROM `users_info` WHERE `Id` = '$added_by'");
                    $user_details = mysqli_fetch_assoc($select_user);
            ?>

                    <div class="box" style="width: 255px;height: 355px;">
                        <a href="book_details.php?details=<?php echo $fetch_book['book_id'];
                                                            echo '-Title=', $fetch_book['Title']; ?>"> <img style="height: 200px;width: 125px;margin: auto;" class="books_images" src="added_books/<?php echo $fetch_book['image']; ?>" alt=""></a>
                        <div style="text-align:left ;">

                            <div style="font-weight: 500; font-size:18px; text-align: center; " class="Title"> <?php echo $fetch_book['Title']; ?></div>
                        </div>
                        <div class="Price">Price: ₹ <?php echo $fetch_book['Price']; ?>/-</div>
                        <?php if ($user_details) { ?>
                            <div class="AddedBy">Added By: <?php echo $user_details['name'] . ' ' . $user_details['surname'] . ' (' . $user_details['user_type'] . ')'; ?></div>
                        <?php } ?>
                        <!-- <button name="add_cart"><img src="./images/cart2.png" alt=""></button> -->
                        <form action="" method="POST">
                            <input class="hidden_input" type="hidden" name="book_name" value="<?php echo $fetch_book['Title'] ?>">
                            <input class="hidden_input" type="hidden" name="book_image" value="<?php echo $fetch_book['image'] ?>">
                            <input class="hidden_input" type="hidden" name="book_Price" value="<?php echo $fetch_book['Price'] ?>">
                            <button name="buy_now"><img src="./images/cart2.png" > BUY NOW</button>
                            <button onclick="myFunction()" name="add_to_cart"><img src="">
                                <a href="show_info.php?details=<?php echo $fetch_book['book_id']; ?>&name=<?php echo $fetch_book['Title']; ?>" class="update_btn">Know More</a>
                            
                        
                        </form>
                        <!-- <button name="add_to_cart" ><img src="./images/cart2.png" alt="Add to cart"></button> -->
                        <!-- <input type="submit" name="add_cart" value="cart"> -->
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>
    </section>



    <section id="Upto 10th">
        </div>

        <div class="container px-5 mx-auto">
            <h2 class="text-gray-400 m-8 font-extrabold text-4xl text-center border-t-2 text-red-800" style="color: rgb(0, 167, 245);" >
                upto 10th
            </h2>
        </div>
    </section>
    <section class="show-products">
        <div class="box-container">

            <?php
            $select_book = mysqli_query($conn, "SELECT * FROM `book_info` where category_name='upto 10th'") or die('query failed');
            if (mysqli_num_rows($select_book) > 0) {
                while ($fetch_book = mysqli_fetch_assoc($select_book)) {
                    $added_by = $fetch_book['added_by'];
                    $select_user = mysqli_query($conn, "SELECT `name`, `surname`, `user_type` FROM `users_info` WHERE `Id` = '$added_by'");
                    $user_details = mysqli_fetch_assoc($select_user);
            ?>

                    <div class="box" style="width: 255px;height: 355px;">
                        <a href="book_details.php?details=<?php echo $fetch_book['book_id'];
                                                            echo '-Title=', $fetch_book['Title']; ?>"> <img style="height: 200px;width: 125px;margin: auto;" class="books_images" src="added_books/<?php echo $fetch_book['image']; ?>" alt=""></a>
                        <div style="text-align:left ;">

                            <div style="font-weight: 500; font-size:18px; text-align: center; " class="Title"> <?php echo $fetch_book['Title']; ?></div>
                        </div>
                        <div class="Price">Price: ₹ <?php echo $fetch_book['Price']; ?>/-</div>
                        <?php if ($user_details) { ?>
                            <div class="AddedBy">Added By: <?php echo $user_details['name'] . ' ' . $user_details['surname'] . ' (' . $user_details['user_type'] . ')'; ?></div>
                        <?php } ?>
                        <!-- <button name="add_cart"><img src="./images/cart2.png" alt=""></button> -->
                        <form action="" method="POST">
                            <input class="hidden_input" type="hidden" name="book_name" value="<?php echo $fetch_book['Title'] ?>">
                            <input class="hidden_input" type="hidden" name="book_image" value="<?php echo $fetch_book['image'] ?>">
                            <input class="hidden_input" type="hidden" name="book_Price" value="<?php echo $fetch_book['Price'] ?>">
                            <button name="buy_now"><img src="./images/cart2.png" > BUY NOW</button>
                            <button onclick="myFunction()" name="add_to_cart"><img src="">
                                <a href="show_info.php?details=<?php echo $fetch_book['book_id']; ?>&name=<?php echo $fetch_book['Title']; ?>" class="update_btn">Know More</a>
                            
                        
                        </form>
                        <!-- <button name="add_to_cart" ><img src="./images/cart2.png" alt="Add to cart"></button> -->
                        <!-- <input type="submit" name="add_cart" value="cart"> -->
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>
    </section>




    <section id="GATE">
        </div>

        <div class="container px-5 mx-auto">
            <h2 class="text-gray-400 m-8 font-extrabold text-4xl text-center border-t-2 text-red-800" style="color: rgb(0, 167, 245);" >
                GATE
            </h2>
        </div>
    </section>
    <section class="show-products">
        <div class="box-container">

            <?php
            $select_book = mysqli_query($conn, "SELECT * FROM `book_info` where category_name='GATE'") or die('query failed');
            if (mysqli_num_rows($select_book) > 0) {
                while ($fetch_book = mysqli_fetch_assoc($select_book)) {
                    $added_by = $fetch_book['added_by'];
                    $select_user = mysqli_query($conn, "SELECT `name`, `surname`, `user_type` FROM `users_info` WHERE `Id` = '$added_by'");
                    $user_details = mysqli_fetch_assoc($select_user);
            ?>

                    <div class="box" style="width: 255px;height: 355px;">
                        <a href="book_details.php?details=<?php echo $fetch_book['book_id'];
                                                            echo '-Title=', $fetch_book['Title']; ?>"> <img style="height: 200px;width: 125px;margin: auto;" class="books_images" src="added_books/<?php echo $fetch_book['image']; ?>" alt=""></a>
                        <div style="text-align:left ;">

                            <div style="font-weight: 500; font-size:18px; text-align: center; " class="Title"> <?php echo $fetch_book['Title']; ?></div>
                        </div>
                        <div class="Price">Price: ₹ <?php echo $fetch_book['Price']; ?>/-</div>
                        <?php if ($user_details) { ?>
                            <div class="AddedBy">Added By: <?php echo $user_details['name'] . ' ' . $user_details['surname'] . ' (' . $user_details['user_type'] . ')'; ?></div>
                        <?php } ?>
                        <!-- <button name="add_cart"><img src="./images/cart2.png" alt=""></button> -->
                        <form action="" method="POST">
                            <input class="hidden_input" type="hidden" name="book_name" value="<?php echo $fetch_book['Title'] ?>">
                            <input class="hidden_input" type="hidden" name="book_image" value="<?php echo $fetch_book['image'] ?>">
                            <input class="hidden_input" type="hidden" name="book_Price" value="<?php echo $fetch_book['Price'] ?>">
                            <button name="buy_now"><img src="./images/cart2.png" > BUY NOW</button>
                            <button onclick="myFunction()" name="add_to_cart"><img src="">
                                <a href="show_info.php?details=<?php echo $fetch_book['book_id']; ?>&name=<?php echo $fetch_book['Title']; ?>" class="update_btn">Know More</a>
                            
                        
                        </form>
                        <!-- <button name="add_to_cart" ><img src="./images/cart2.png" alt="Add to cart"></button> -->
                        <!-- <input type="submit" name="add_cart" value="cart"> -->
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>
    </section>




    <section id="CAT">
        </div>

        <div class="container px-5 mx-auto">
            <h2 class="text-gray-400 m-8 font-extrabold text-4xl text-center border-t-2 text-red-800" style="color: rgb(0, 167, 245);" >
                CAT
            </h2>
        </div>
    </section>
    <section class="show-products">
        <div class="box-container">

            <?php
            $select_book = mysqli_query($conn, "SELECT * FROM `book_info` where category_name='CAT'") or die('query failed');
            if (mysqli_num_rows($select_book) > 0) {
                while ($fetch_book = mysqli_fetch_assoc($select_book)) {
                    $added_by = $fetch_book['added_by'];
                    $select_user = mysqli_query($conn, "SELECT `name`, `surname`, `user_type` FROM `users_info` WHERE `Id` = '$added_by'");
                    $user_details = mysqli_fetch_assoc($select_user);
            ?>

                    <div class="box" style="width: 255px;height: 355px;">
                        <a href="book_details.php?details=<?php echo $fetch_book['book_id'];
                                                            echo '-Title=', $fetch_book['Title']; ?>"> <img style="height: 200px;width: 125px;margin: auto;" class="books_images" src="added_books/<?php echo $fetch_book['image']; ?>" alt=""></a>
                        <div style="text-align:left ;">

                            <div style="font-weight: 500; font-size:18px; text-align: center; " class="Title"> <?php echo $fetch_book['Title']; ?></div>
                        </div>
                        <div class="Price">Price: ₹ <?php echo $fetch_book['Price']; ?>/-</div>
                        <?php if ($user_details) { ?>
                            <div class="AddedBy">Added By: <?php echo $user_details['name'] . ' ' . $user_details['surname'] . ' (' . $user_details['user_type'] . ')'; ?></div>
                        <?php } ?>
                        <!-- <button name="add_cart"><img src="./images/cart2.png" alt=""></button> -->
                        <form action="" method="POST">
                            <input class="hidden_input" type="hidden" name="book_name" value="<?php echo $fetch_book['Title'] ?>">
                            <input class="hidden_input" type="hidden" name="book_image" value="<?php echo $fetch_book['image'] ?>">
                            <input class="hidden_input" type="hidden" name="book_Price" value="<?php echo $fetch_book['Price'] ?>">
                            <button name="buy_now"><img src="./images/cart2.png" > BUY NOW</button>
                            <button onclick="myFunction()" name="add_to_cart"><img src="">
                                <a href="show_info.php?details=<?php echo $fetch_book['book_id']; ?>&name=<?php echo $fetch_book['Title']; ?>" class="update_btn">Know More</a>
                            
                        
                        </form>
                        <!-- <button name="add_to_cart" ><img src="./images/cart2.png" alt="Add to cart"></button> -->
                        <!-- <input type="submit" name="add_cart" value="cart"> -->
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>
    </section>



    <section id="CET">
        </div>

        <div class="container px-5 mx-auto">
            <h2 class="text-gray-400 m-8 font-extrabold text-4xl text-center border-t-2 text-red-800" style="color: rgb(0, 167, 245);" >
                CET
            </h2>
        </div>
    </section>
    <section class="show-products">
        <div class="box-container">

            <?php
            $select_book = mysqli_query($conn, "SELECT * FROM `book_info` where category_name='CET'") or die('query failed');
            if (mysqli_num_rows($select_book) > 0) {
                while ($fetch_book = mysqli_fetch_assoc($select_book)) {
                    $added_by = $fetch_book['added_by'];
                    $select_user = mysqli_query($conn, "SELECT `name`, `surname`, `user_type` FROM `users_info` WHERE `Id` = '$added_by'");
                    $user_details = mysqli_fetch_assoc($select_user);
            ?>

                    <div class="box" style="width: 255px;height: 355px;">
                        <a href="book_details.php?details=<?php echo $fetch_book['book_id'];
                                                            echo '-Title=', $fetch_book['Title']; ?>"> <img style="height: 200px;width: 125px;margin: auto;" class="books_images" src="added_books/<?php echo $fetch_book['image']; ?>" alt=""></a>
                        <div style="text-align:left ;">

                            <div style="font-weight: 500; font-size:18px; text-align: center; " class="Title"> <?php echo $fetch_book['Title']; ?></div>
                        </div>
                        <div class="Price">Price: ₹ <?php echo $fetch_book['Price']; ?>/-</div>
                        <?php if ($user_details) { ?>
                            <div class="AddedBy">Added By: <?php echo $user_details['name'] . ' ' . $user_details['surname'] . ' (' . $user_details['user_type'] . ')'; ?></div>
                        <?php } ?>
                        <!-- <button name="add_cart"><img src="./images/cart2.png" alt=""></button> -->
                        <form action="" method="POST">
                            <input class="hidden_input" type="hidden" name="book_name" value="<?php echo $fetch_book['Title'] ?>">
                            <input class="hidden_input" type="hidden" name="book_image" value="<?php echo $fetch_book['image'] ?>">
                            <input class="hidden_input" type="hidden" name="book_Price" value="<?php echo $fetch_book['Price'] ?>">
                            <button name="buy_now"><img src="./images/cart2.png" > BUY NOW</button>
                            <button onclick="myFunction()" name="add_to_cart"><img src="">
                                <a href="show_info.php?details=<?php echo $fetch_book['book_id']; ?>&name=<?php echo $fetch_book['Title']; ?>" class="update_btn">Know More</a>
                            
                        
                        </form>
                        <!-- <button name="add_to_cart" ><img src="./images/cart2.png" alt="Add to cart"></button> -->
                        <!-- <input type="submit" name="add_cart" value="cart"> -->
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>
    </section>





    <section id="NEET">
        </div>

        <div class="container px-5 mx-auto">
            <h2 class="text-gray-400 m-8 font-extrabold text-4xl text-center border-t-2 text-red-800" style="color: rgb(0, 167, 245);" >
                NEET
            </h2>
        </div>
    </section>
    <section class="show-products">
        <div class="box-container">

            <?php
            $select_book = mysqli_query($conn, "SELECT * FROM `book_info` where category_name='NEET'") or die('query failed');
            if (mysqli_num_rows($select_book) > 0) {
                while ($fetch_book = mysqli_fetch_assoc($select_book)) {
                    $added_by = $fetch_book['added_by'];
                    $select_user = mysqli_query($conn, "SELECT `name`, `surname`, `user_type` FROM `users_info` WHERE `Id` = '$added_by'");
                    $user_details = mysqli_fetch_assoc($select_user);
            ?>

                    <div class="box" style="width: 255px;height: 355px;">
                        <a href="book_details.php?details=<?php echo $fetch_book['book_id'];
                                                            echo '-Title=', $fetch_book['Title']; ?>"> <img style="height: 200px;width: 125px;margin: auto;" class="books_images" src="added_books/<?php echo $fetch_book['image']; ?>" alt=""></a>
                        <div style="text-align:left ;">

                            <div style="font-weight: 500; font-size:18px; text-align: center; " class="Title"> <?php echo $fetch_book['Title']; ?></div>
                        </div>
                        <div class="Price">Price: ₹ <?php echo $fetch_book['Price']; ?>/-</div>
                        <?php if ($user_details) { ?>
                            <div class="AddedBy">Added By: <?php echo $user_details['name'] . ' ' . $user_details['surname'] . ' (' . $user_details['user_type'] . ')'; ?></div>
                        <?php } ?>
                        <!-- <button name="add_cart"><img src="./images/cart2.png" alt=""></button> -->
                        <form action="" method="POST">
                            <input class="hidden_input" type="hidden" name="book_name" value="<?php echo $fetch_book['Title'] ?>">
                            <input class="hidden_input" type="hidden" name="book_image" value="<?php echo $fetch_book['image'] ?>">
                            <input class="hidden_input" type="hidden" name="book_Price" value="<?php echo $fetch_book['Price'] ?>">
                            <button name="buy_now"><img src="./images/cart2.png" > BUY NOW</button>
                            <button onclick="myFunction()" name="add_to_cart"><img src="">
                                <a href="show_info.php?details=<?php echo $fetch_book['book_id']; ?>&name=<?php echo $fetch_book['Title']; ?>" class="update_btn">Know More</a>
                            
                        
                        </form>
                        <!-- <button name="add_to_cart" ><img src="./images/cart2.png" alt="Add to cart"></button> -->
                        <!-- <input type="submit" name="add_cart" value="cart"> -->
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>
    </section>




    <section id="JEE">
        </div>

        <div class="container px-5 mx-auto">
            <h2 class="text-gray-400 m-8 font-extrabold text-4xl text-center border-t-2 text-red-800" style="color: rgb(0, 167, 245);" >
                JEE
            </h2>
        </div>
    </section>
    <section class="show-products">
        <div class="box-container">

            <?php
            $select_book = mysqli_query($conn, "SELECT * FROM `book_info` where category_name='JEE'") or die('query failed');
            if (mysqli_num_rows($select_book) > 0) {
                while ($fetch_book = mysqli_fetch_assoc($select_book)) {
                    $added_by = $fetch_book['added_by'];
                    $select_user = mysqli_query($conn, "SELECT `name`, `surname`, `user_type` FROM `users_info` WHERE `Id` = '$added_by'");
                    $user_details = mysqli_fetch_assoc($select_user);
            ?>

                    <div class="box" style="width: 255px;height: 355px;">
                        <a href="book_details.php?details=<?php echo $fetch_book['book_id'];
                                                            echo '-Title=', $fetch_book['Title']; ?>"> <img style="height: 200px;width: 125px;margin: auto;" class="books_images" src="added_books/<?php echo $fetch_book['image']; ?>" alt=""></a>
                        <div style="text-align:left ;">

                            <div style="font-weight: 500; font-size:18px; text-align: center; " class="Title"> <?php echo $fetch_book['Title']; ?></div>
                        </div>
                        <div class="Price">Price: ₹ <?php echo $fetch_book['Price']; ?>/-</div>
                        <?php if ($user_details) { ?>
                            <div class="AddedBy">Added By: <?php echo $user_details['name'] . ' ' . $user_details['surname'] . ' (' . $user_details['user_type'] . ')'; ?></div>
                        <?php } ?>
                        <!-- <button name="add_cart"><img src="./images/cart2.png" alt=""></button> -->
                        <form action="" method="POST">
                            <input class="hidden_input" type="hidden" name="book_name" value="<?php echo $fetch_book['Title'] ?>">
                            <input class="hidden_input" type="hidden" name="book_image" value="<?php echo $fetch_book['image'] ?>">
                            <input class="hidden_input" type="hidden" name="book_Price" value="<?php echo $fetch_book['Price'] ?>">
                            <button name="buy_now"><img src="./images/cart2.png" > BUY NOW</button>
                            <button onclick="myFunction()" name="add_to_cart"><img src="">
                                <a href="show_info.php?details=<?php echo $fetch_book['book_id']; ?>&name=<?php echo $fetch_book['Title']; ?>" class="update_btn">Know More</a>
                            
                        
                        </form>
                        <!-- <button name="add_to_cart" ><img src="./images/cart2.png" alt="Add to cart"></button> -->
                        <!-- <input type="submit" name="add_cart" value="cart"> -->
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>
    </section>




    <section id="HSC">
        </div>

        <div class="container px-5 mx-auto">
            <h2 class="text-gray-400 m-8 font-extrabold text-4xl text-center border-t-2 text-red-800" style="color: rgb(0, 167, 245);" >
                HSC
            </h2>
        </div>
    </section>
    <section class="show-products">
        <div class="box-container">

            <?php
            $select_book = mysqli_query($conn, "SELECT * FROM `book_info` where category_name='HSC'") or die('query failed');
            if (mysqli_num_rows($select_book) > 0) {
                while ($fetch_book = mysqli_fetch_assoc($select_book)) {
                    $added_by = $fetch_book['added_by'];
                    $select_user = mysqli_query($conn, "SELECT `name`, `surname`, `user_type` FROM `users_info` WHERE `Id` = '$added_by'");
                    $user_details = mysqli_fetch_assoc($select_user);
            ?>

                    <div class="box" style="width: 255px;height: 355px;">
                        <a href="book_details.php?details=<?php echo $fetch_book['book_id'];
                                                            echo '-Title=', $fetch_book['Title']; ?>"> <img style="height: 200px;width: 125px;margin: auto;" class="books_images" src="added_books/<?php echo $fetch_book['image']; ?>" alt=""></a>
                        <div style="text-align:left ;">

                            <div style="font-weight: 500; font-size:18px; text-align: center; " class="Title"> <?php echo $fetch_book['Title']; ?></div>
                        </div>
                        <div class="Price">Price: ₹ <?php echo $fetch_book['Price']; ?>/-</div>
                        <?php if ($user_details) { ?>
                            <div class="AddedBy">Added By: <?php echo $user_details['name'] . ' ' . $user_details['surname'] . ' (' . $user_details['user_type'] . ')'; ?></div>
                        <?php } ?>
                        <!-- <button name="add_cart"><img src="./images/cart2.png" alt=""></button> -->
                        <form action="" method="POST">
                            <input class="hidden_input" type="hidden" name="book_name" value="<?php echo $fetch_book['Title'] ?>">
                            <input class="hidden_input" type="hidden" name="book_image" value="<?php echo $fetch_book['image'] ?>">
                            <input class="hidden_input" type="hidden" name="book_Price" value="<?php echo $fetch_book['Price'] ?>">
                            <button name="buy_now"><img src="./images/cart2.png" > BUY NOW</button>
                            <button onclick="myFunction()" name="add_to_cart"><img src="">
                                <a href="show_info.php?details=<?php echo $fetch_book['book_id']; ?>&name=<?php echo $fetch_book['Title']; ?>" class="update_btn">Know More</a>
                            
                        
                        </form>
                        <!-- <button name="add_to_cart" ><img src="./images/cart2.png" alt="Add to cart"></button> -->
                        <!-- <input type="submit" name="add_cart" value="cart"> -->
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>
    </section>



    <section id="SSC">
        </div>

        <div class="container px-5 mx-auto">
            <h2 class="text-gray-400 m-8 font-extrabold text-4xl text-center border-t-2 text-red-800" style="color: rgb(0, 167, 245);" >
                SSC
            </h2>
        </div>
    </section>
    <section class="show-products">
        <div class="box-container">

            <?php
            $select_book = mysqli_query($conn, "SELECT * FROM `book_info` where category_name='SSC'") or die('query failed');
            if (mysqli_num_rows($select_book) > 0) {
                while ($fetch_book = mysqli_fetch_assoc($select_book)) {
                    $added_by = $fetch_book['added_by'];
                    $select_user = mysqli_query($conn, "SELECT `name`, `surname`, `user_type` FROM `users_info` WHERE `Id` = '$added_by'");
                    $user_details = mysqli_fetch_assoc($select_user);
            ?>

                    <div class="box" style="width: 255px;height: 355px;">
                        <a href="book_details.php?details=<?php echo $fetch_book['book_id'];
                                                            echo '-Title=', $fetch_book['Title']; ?>"> <img style="height: 200px;width: 125px;margin: auto;" class="books_images" src="added_books/<?php echo $fetch_book['image']; ?>" alt=""></a>
                        <div style="text-align:left ;">

                            <div style="font-weight: 500; font-size:18px; text-align: center; " class="Title"> <?php echo $fetch_book['Title']; ?></div>
                        </div>
                        <div class="Price">Price: ₹ <?php echo $fetch_book['Price']; ?>/-</div>
                        <?php if ($user_details) { ?>
                            <div class="AddedBy">Added By: <?php echo $user_details['name'] . ' ' . $user_details['surname'] . ' (' . $user_details['user_type'] . ')'; ?></div>
                        <?php } ?>
                        <!-- <button name="add_cart"><img src="./images/cart2.png" alt=""></button> -->
                        <form action="" method="POST">
                            <input class="hidden_input" type="hidden" name="book_name" value="<?php echo $fetch_book['Title'] ?>">
                            <input class="hidden_input" type="hidden" name="book_image" value="<?php echo $fetch_book['image'] ?>">
                            <input class="hidden_input" type="hidden" name="book_Price" value="<?php echo $fetch_book['Price'] ?>">
                            <button name="buy_now"><img src="./images/cart2.png" > BUY NOW</button>
                            <button onclick="myFunction()" name="add_to_cart"><img src="">
                                <a href="show_info.php?details=<?php echo $fetch_book['book_id']; ?>&name=<?php echo $fetch_book['Title']; ?>" class="update_btn">Know More</a>
                            
                        
                        </form>
                        <!-- <button name="add_to_cart" ><img src="./images/cart2.png" alt="Add to cart"></button> -->
                        <!-- <input type="submit" name="add_cart" value="cart"> -->
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>
    </section>


    <?php include 'index_footer.php'; ?>

    <script>
        setTimeout(() => {
            const box = document.getElementById('messages');

            // 👇️ hides element (still takes up space on page)
            box.style.display = 'none';
        }, 8000);
    </script>


</body>

</html>