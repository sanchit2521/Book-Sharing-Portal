<?php
include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit(); // Exit after redirection
}

$message = array(); // Initialize an array to store messages

if (isset($_POST['add_books'])) {
    // Escape user inputs for security
    $btitle = mysqli_real_escape_string($conn, $_POST['btitle']);
    $bauthor = mysqli_real_escape_string($conn, $_POST['bauthor']);
    $blocation = mysqli_real_escape_string($conn, $_POST['blocation']);
    $blanguage = mysqli_real_escape_string($conn, $_POST['blanguage']);
    $bdimensions = mysqli_real_escape_string($conn, $_POST['bdimensions']);
    $bdesc = mysqli_real_escape_string($conn, $_POST['bdesc']);
    $bedition = mysqli_real_escape_string($conn, $_POST['bedition']);
    $bpublication = mysqli_real_escape_string($conn, $_POST['bpublication']);
    $bpages = mysqli_real_escape_string($conn, $_POST['bpages']);
    $price = $_POST['price'];
    $category = mysqli_real_escape_string($conn, $_POST['Category']);

    // File handling
    $img = $_FILES["image"]["name"];
    $img_temp_name = $_FILES["image"]["tmp_name"];
    $img_file = "./added_books/" . $img;

    // Validate input
    if (empty($btitle) || empty($bauthor) || empty($blocation) || empty($price) || empty($category) || empty($bdesc) || empty($img)) {
        $message[] = 'Please fill in all the fields';
    } else {
        // Insert record
        $add_book = mysqli_query($conn, "INSERT INTO book_info (`title`, `author`, `location`, `language`, `dimensions`, `Review`, `Edition`, `Publication`, `Pages`, `Price`, `category_name`, `image`) 
            VALUES ('$btitle', '$bauthor', '$blocation', '$blanguage', '$bdimensions', '$bdesc', '$bedition', '$bpublication', '$bpages', '$price', '$category', '$img')");

        if ($add_book) {
            move_uploaded_file($img_temp_name, $img_file);
            $message[] = 'New Book Added Successfully';
        } else {
            $message[] = 'Failed to add book';
        }
    }
}

if (isset($_POST['update_product'])) {
   // Escape user inputs for security
   $update_p_id = $_POST['update_p_id'];
   $update_title = mysqli_real_escape_string($conn, $_POST['update_title']);
   $update_author = mysqli_real_escape_string($conn, $_POST['update_author']);
   $update_location = mysqli_real_escape_string($conn, $_POST['update_location']);
   $update_language = mysqli_real_escape_string($conn, $_POST['update_language']);
   $update_dimensions = mysqli_real_escape_string($conn, $_POST['update_dimensions']);
   $update_review = mysqli_real_escape_string($conn, $_POST['update_review']);
   $update_edition = mysqli_real_escape_string($conn, $_POST['update_edition']);
   $update_publication = mysqli_real_escape_string($conn, $_POST['update_publication']);
   $update_pages = mysqli_real_escape_string($conn, $_POST['update_pages']);
   $update_price = $_POST['update_price'];
   $update_category = mysqli_real_escape_string($conn, $_POST['update_category']);

   // File handling
   $update_image = $_FILES['update_image']['name'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_folder = './added_books/' . $update_image;
   $update_old_image = $_POST['update_old_image'];

   // Validate input
   if (empty($update_title) || empty($update_author) || empty($update_location) || empty($update_language) || empty($update_dimensions) || empty($update_review) || empty($update_edition) || empty($update_publication) || empty($update_pages) || empty($update_price) || empty($update_category)) {
       $message[] = 'Please fill in all the fields';
   } else {
       // Update record
       mysqli_query($conn, "UPDATE `book_info` SET Title = '$update_title', Author='$update_author', Location='$update_location', Language='$update_language', Dimensions='$update_dimensions', Review ='$update_review', Edition='$update_edition', Publication='$update_publication', Pages='$update_pages', Price = '$update_price', category_name='$update_category' WHERE book_id = '$update_p_id'") or die('query failed');

       if (!empty($update_image)) {
           if ($update_image_size > 2000000) {
               $message[] = 'Image file size is too large';
           } else {
               mysqli_query($conn, "UPDATE `book_info` SET image = '$update_image' WHERE book_id = '$update_p_id'") or die('query failed');
               move_uploaded_file($update_image_tmp_name, $update_folder);
               unlink('uploaded_img/' . $update_old_image);
           }
       }

       $message[] = 'Book updated successfully';
   }
}


if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `book_info` WHERE book_id = '$delete_id'") or die('query failed');
    header('location:add_books.php');
    exit(); // Exit after redirection
}

// Display messages
if (isset($message)) {
    foreach ($message as $msg) {
        echo '<div class="message" id="messages"><span>' . $msg . '</span></div>';
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
  <title>Add Books</title>
</head>

<body>
  <?php
  include './admin_header.php'
  ?>
  <?php
  if (isset($message)) {
    foreach ($message as $message) {
      echo '
        <div class="message" id="messages"><span>' . $message . '</span>
        </div>
        ';
    }
  }
  ?>
  
<a class="update_btn" style="position: fixed ; z-index:100;" href="total_books.php">See All Books</a>
  <div class="container_box">
    <form action="" method="POST" enctype="multipart/form-data">
      <h3>Add Books To <a href="index.php"><span>Book Sharing Portal </span></a></h3>
      <input type="text" name="btitle" placeholder="Enter book Name" class="text_field ">
      <input type="text" name="bauthor" placeholder="Enter Author name" class="text_field">
      <input type="text" name="blocation" placeholder="Enter location of book" class="text_field">
      <input type="text" name="blanguage" placeholder="Enter language of book" class="text_field">
      <input type="text" name="bdimensions" placeholder="Enter dimensions" class="text_field">
      <textarea name="bdesc" placeholder="Enter book description" id="" class="text_field" cols="18" rows="5"></textarea>
      <input type="number" name="bedition" placeholder="Enter edition of book" class="text_field">
      <input type="text" name="bpublication" placeholder="Enter publication of book" class="text_field">
      <input type="number" name="bpages" placeholder="Enter pages of book" class="text_field">
      <input type="number" min="0" name="price" class="text_field" placeholder="enter product price">
      <select name="Category" id="" required class="text_field">
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
         </select>
      <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="text_field">
      <input type="submit" value="Add Book" name="add_books" class="btn text_field">
    </form>
  </div>

  <section class="edit-product-form">

<?php
   if(isset($_GET['update'])){
      $update_id = $_GET['update'];
      $update_query = mysqli_query($conn, "SELECT * FROM `book_info` WHERE book_id = '$update_id'") or die('query failed');
      if(mysqli_num_rows($update_query) > 0){
         while($fetch_update = mysqli_fetch_assoc($update_query)){
?>
<form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['book_id']; ?>">
    <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
    <img src="./added_books/<?php echo $fetch_update['image']; ?>" alt="">
    <input type="text" name="update_title" value="<?php echo $fetch_update['Title']; ?>" class="box" >
    <input type="text" name="update_author" value="<?php echo $fetch_update['Author']; ?>" class="box" required placeholder="Enter Author Name">
    <input type="text" name="update_location" value="<?php echo $fetch_update['Location']; ?>" class="box" required placeholder="Enter location of book">
    <input type="text" name="update_language" value="<?php echo $fetch_update['Language']; ?>" class="box" required placeholder="Enter language of book">
    <input type="text" name="update_dimensions" value="<?php echo $fetch_update['Dimensions']; ?>" class="box" required placeholder="Enter dimensions">
    <textarea name="update_review" class="text_field" required placeholder="Enter book description"><?php echo $fetch_update['Review']; ?></textarea>
    <input type="number" name="update_edition" value="<?php echo $fetch_update['Edition']; ?>" class="box" required placeholder="Enter edition of book">
    <input type="text" name="update_publication" value="<?php echo $fetch_update['Publication']; ?>" class="box" required placeholder="Enter publication of book">
    <input type="number" name="update_pages" value="<?php echo $fetch_update['Pages']; ?>" class="box" required placeholder="Enter pages of book">
    <input type="number" min="0" name="update_price" value="<?php echo $fetch_update['Price']; ?>" class="box" placeholder="Enter product price">
    <select name="update_category" class="text_field" required>
        <option value="Arts"<?php if($fetch_update['category_name'] == "Arts") echo " selected"; ?>>Arts</option>
        <option value="Pure Science"<?php if($fetch_update['category_name'] == "Pure Science") echo " selected"; ?>>Pure Science</option>
        <option value="CLAT"<?php if($fetch_update['category_name'] == "CLAT") echo " selected"; ?>>CLAT</option>
        <option value="MPSC"<?php if($fetch_update['category_name'] == "MPSC") echo " selected"; ?>>MPSC</option>
        <option value="Agri"<?php if($fetch_update['category_name'] == "Agri") echo " selected"; ?>>Agri</option>
        <option value="Pharmacy"<?php if($fetch_update['category_name'] == "Pharmacy") echo " selected"; ?>>Pharmacy</option>
        <option value="LAW"<?php if($fetch_update['category_name'] == "LAW") echo " selected"; ?>>LAW</option>
        <option value="Medical"<?php if($fetch_update['category_name'] == "Medical") echo " selected"; ?>>Medical</option>
        <option value="Engineering"<?php if($fetch_update['category_name'] == "Engineering") echo " selected"; ?>>Engineering</option>
        <option value="UPSC"<?php if($fetch_update['category_name'] == "UPSC") echo " selected"; ?>>UPSC</option>
        <option value="Non-fiction"<?php if($fetch_update['category_name'] == "Non-fiction") echo " selected"; ?>>Non-fiction</option>
        <option value="Fiction"<?php if($fetch_update['category_name'] == "Fiction") echo " selected"; ?>>Fiction</option>
        <option value="Upto 10th"<?php if($fetch_update['category_name'] == "Upto 10th") echo " selected"; ?>>Upto 10th</option>
        <option value="GATE"<?php if($fetch_update['category_name'] == "GATE") echo " selected"; ?>>GATE</option>
        <option value="CAT"<?php if($fetch_update['category_name'] == "CAT") echo " selected"; ?>>CAT</option>
        <option value="CET"<?php if($fetch_update['category_name'] == "CET") echo " selected"; ?>>CET</option>
        <option value="NEET"<?php if($fetch_update['category_name'] == "NEET") echo " selected"; ?>>NEET</option>
        <option value="JEE"<?php if($fetch_update['category_name'] == "JEE") echo " selected"; ?>>JEE</option>
        <option value="HSC"<?php if($fetch_update['category_name'] == "HSC") echo " selected"; ?>>HSC</option>
        <option value="SSC"<?php if($fetch_update['category_name'] == "SSC") echo " selected"; ?>>SSC</option>
    </select>
    <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png" class="text_field">
    <input type="submit" value="update" name="update_product" class="delete_btn">
    <input type="reset" value="cancel" id="close-update" class="update_btn">
</form>

<?php
      }
   }
   }else{
      echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
   }
?>

</section>
  <section class="show-products">

   <div class="box-container">

      <?php
         $select_book = mysqli_query($conn, "SELECT * FROM book_info ORDER BY date DESC LIMIT 2;") or die('query failed');
         if(mysqli_num_rows($select_book) > 0){
            while($fetch_book = mysqli_fetch_assoc($select_book)){
      ?>
      <div class="box">
         <img class="books_images" src="added_books/<?php echo $fetch_book['image']; ?>" alt="">
         <div class="name">Author: <?php echo $fetch_book['Author']; ?></div>
         <div class="name">Name: <?php echo $fetch_book['Title']; ?></div>
         <div class="price">Price: ₹ <?php echo $fetch_book['Price']; ?>/-</div>
         <a href="add_books.php?update=<?php echo $fetch_book['book_id']; ?>" class="update_btn">update</a>
         <a href="add_books.php?delete=<?php echo $fetch_book['book_id']; ?>" class="delete_btn" onclick="return confirm('delete this product?');">delete</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>
   </div>

</section>

<section class="edit-product-form">

   <?php
      if(isset($_GET['update'])){
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `book_info` WHERE book_id = '$update_id'") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['book_id']; ?>">
      <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
      <img src="./added_books/<?php echo $fetch_update['image']; ?>" alt="">
      <input type="text" name="update_title" value="<?php echo $fetch_update['Title']; ?>" class="box" required placeholder="Enter Book Name">
      <input type="text" name="update_author" value="<?php echo $fetch_update['Author']; ?>" class="box" required placeholder="Enter Author Name">
      <select name="update_category" value="<?php echo $fetch_update['category_name']; ?> required class="text_field">
            <option value="Adventure">Adventure</option>
            <option value="Magic">Magic</option>
            <option value="knowledge">knowledge</option>
         </select>
      <input type="text" name="update_description" value="<?php echo $fetch_update['Review']; ?>" class="box" required placeholder="enter product description">
      <input type="number" name="update_price" value="<?php echo $fetch_update['Price']; ?>" min="0" class="box" required placeholder="enter product price">
      <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
      <input type="submit" value="update" name="update_product" class="delete_btn" >
      <input type="reset" value="cancel" id="close-update" class="update_btn" >
   </form>
   <?php
         }
      }
      }else{
         echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
      }
   ?>

</section>

<script src="./js/admin.js"></script>
<script>
setTimeout(() => {
  const box = document.getElementById('messages');

  // 👇️ hides element (still takes up space on page)
  box.style.display = 'none';
}, 8000);
</script>
</body>

</html>