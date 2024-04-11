<?php
session_start();
include 'config.php';
require 'vendor/autoload.php'; // Include PHPMailer autoload.php file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP; // Add this line to import the SMTP class

$user_id = $_SESSION['user_id'];
$conn_oid = ""; // Initialize $conn_oid variable

if (!isset($user_id)) {
    header('location:login.php');
    exit();
}

if (isset($_POST['checkout'])) {
    $name = mysqli_real_escape_string($conn, $_POST['firstname']);
    $number = $_POST['number'];
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $pincode = mysqli_real_escape_string($conn, $_POST['pincode']);
    $full_address = mysqli_real_escape_string($conn, $_POST['address'] . ', ' . $_POST['city'] . ', ' . $_POST['state'] . ', ' . $_POST['country'] . ' - ' . $_POST['pincode']);
    $placed_on = date('d-M-Y');

    $cart_total = 0;
    $cart_products = array();
    $added_by = ""; // Initialize added_by variable

    // Check if the form fields are empty
    if (empty($name) || empty($email) || empty($number) || empty($address) || empty($city) || empty($state) || empty($country) || empty($pincode)) {
        $message[] = 'Please fill all the required fields.';
    } else {
        // Fetch cart items for the user
        $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('Query failed');
        if (mysqli_num_rows($cart_query) > 0) {
            while ($cart_item = mysqli_fetch_assoc($cart_query)) {
                // Get cart item details
                $cart_products[] = $cart_item['name'] . ' #' . $cart_item['book_id'] . ',(' . $cart_item['quantity'] . ') ';
                $quantity = $cart_item['quantity'];
                $unit_price = $cart_item['price'];
                $cart_books = $cart_item['name'];
                $sub_total = ($cart_item['price'] * $cart_item['quantity']);
                $cart_total += $sub_total;
                // Get added_by value
                $added_by = $cart_item['added_by'];

                // Insert order details into orders table
                mysqli_query($conn, "INSERT INTO `orders`(user_id,id,address,city,state,country,pincode,book,quantity,unit_price,sub_total, added_by) VALUES('$user_id','$conn_oid','$address','$city','$state','$country','$pincode','$cart_books','$quantity','$unit_price','$sub_total', '$added_by')") or die('Query failed');
            }
        }

        // Get total books
        $total_books = implode(' ', $cart_products);

        // Check if the order already exists
        $order_query = mysqli_query($conn, "SELECT * FROM `confirm_order` WHERE name = '$name' AND number = '$number' AND email = '$email' AND payment_method = '$method' AND address = '$address' AND total_books = '$total_books' AND total_price = '$cart_total'") or die('Query failed');
        if (mysqli_num_rows($order_query) > 0) {
            $message[] = 'Order already placed!';
        } else {
            // Insert order details into confirm_order table
            $result = mysqli_query($conn, "INSERT INTO `confirm_order`(user_id, name, number, email, payment_method, address,total_books, total_price, order_date, added_by) VALUES('$user_id','$name', '$number', '$email','$method', '$full_address', '$total_books', '$cart_total', '$placed_on', '$added_by')") or die('Query failed');

            // Retrieve the last inserted ID
            $conn_oid = $conn->insert_id;

            $_SESSION['id'] = $conn_oid; // Store the ID in the session if needed

            // Insert order details into orders table
            foreach ($cart_products as $cart_book) {
                mysqli_query($conn, "INSERT INTO `orders`(user_id,id,address,city,state,country,pincode,book,quantity,unit_price,sub_total, added_by) VALUES('$user_id','$conn_oid','$address','$city','$state','$country','$pincode','$cart_book','$quantity','$unit_price','$sub_total', '$added_by')") or die('Query failed');
            }

            // Success message
            $message[] = 'Order placed successfully!';
            // Clear cart items
            mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('Query failed');
        }
    }

    // Send email to user who added the book
    if (isset($added_by)) {
        // Retrieve email of the user who added the book
        $added_by_email_query = mysqli_query($conn, "SELECT email FROM users_info WHERE Id = '$added_by'");
        if ($added_by_email_query && mysqli_num_rows($added_by_email_query) > 0) {
            $added_by_email_row = mysqli_fetch_assoc($added_by_email_query);
            $added_by_email = $added_by_email_row['email'];

            // Initialize PHPMailer
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.office365.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'khemnarst21.comp@coeptech.ac.in'; // Your email address
                $mail->Password = 'Sainath@1234'; // Your email password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Recipient
                $mail->setFrom('khemnarst21.comp@coeptech.ac.in', 'Book Sharing Portal');
                $mail->addAddress($added_by_email); // Add a recipient

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Your Book Sale Confirmation';
                $mail->Body    = 'Dear User,<br><br>Your book has been sold on the website successfully.<br><br>Thank you for using our platform.<br><br>Regards,<br>Book Sharing Portal';

                $mail->send();
                echo 'Email has been sent successfully';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }
}
?>













<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Checkout</title>
  <style>
    body {
      font-family: Arial;
      font-size: 17px;
      padding: 8px;
      overflow-x: hidden;
    }

    * {
      box-sizing: border-box;
    }

    .row {
      display: -ms-flexbox;
      /* IE10 */
      display: flex;
      -ms-flex-wrap: wrap;
      /* IE10 */
      flex-wrap: wrap;
      margin: 0 -16px;
      padding: 30px;
    }

    .col-25 {
      -ms-flex: 25%;
      /* IE10 */
      flex: 25%;
    }

    .col-50 {
      -ms-flex: 50%;
      /* IE10 */
      flex: 50%;
    }

    .col-75 {
      -ms-flex: 75%;
      /* IE10 */
      flex: 75%;
    }

    .col-25,
    .col-50,
    .col-75 {
      padding: 0 16px;
    }

    .container {
      background-color: #f2f2f2;
      padding: 5px 20px 15px 20px;
      border: 1px solid lightgrey;
      border-radius: 3px;
    }

    input[type=text],
    select {
      width: 100%;
      margin-bottom: 20px;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 3px;
    }

    label {
      margin-bottom: 10px;
      display: block;
      color: black;
    }

    .icon-container {
      margin-bottom: 20px;
      padding: 7px 0;
      font-size: 24px;
    }

    .btn {
      background-color: rgb(28 146 197);
      color: white;
      padding: 12px;
      margin: 10px 0;
      border: none;
      width: 100%;
      border-radius: 3px;
      cursor: pointer;
      font-size: 17px;
    }

    .btn:hover {
      background-color: rgb(6 157 21);
      letter-spacing: 1px;
      font-weight: 600;
    }

    a {
      color: #rgb(28 146 197);
    }

    hr {
      border: 1px solid lightgrey;
    }

    span.price {
      float: right;
      color: grey;
    }

    @media (max-width: 800px) {
      .row {
        flex-direction: column-reverse;
        padding: 0;
      }

      .col-25 {
        margin-bottom: 20px;
      }
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

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://kit.fontawesome.com/493af71c35.js" crossorigin="anonymous"></script>
  
</head>

<body>
  <?php include 'index_header.php'; ?>

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

  <h1 style="text-align: center; margin-top:15px;  color:rgb(9, 152, 248);">Place Your Order Here</h1>
  <p style="text-align: center; ">Just One Step away from getting your books</p>
  <div class="row">
    <div class="col-75">
      <div class="container">
        <form action="" method="POST">

          <div class="row">
            <div class="col-50">
              <h3>Billing Address</h3>
              <label for="fname"><i class="fa fa-user"></i> Full Name</label>
              <input type="text" id="fname" name="firstname" placeholder="Enter your name">
              <label for="email"><i class="fa fa-envelope"></i> Email</label>
              <input type="text" id="email" name="email" placeholder="example@gmail.com">
              <label for="email"><i class="fa fa-envelope"></i> Number</label>
              <input type="text" id="email" name="number" placeholder="+91987456123">
              <label for="adr"><i class="fa fa-address-card-o"></i> Address</label>
              <input type="text" id="adr" name="address" placeholder="Mumbai 60">
              <label for="city"><i class="fa fa-institution"></i> City</label>
              <input type="text" id="city" name="city" placeholder="Mumbai">
              <label for="city"><i class="fa fa-institution"></i> State</label>
              <input type="text" id="city" name="state" placeholder="Maharashtra">

              <div style="padding: 0px;" class="row">
                <div class="col-50">
                  <label for="state">Country</label>
                  <input type="text" id="state" name="country" placeholder="india">
                </div>
                <div class="col-50">
                  <label for="zip">Pincode</label>
                  <input type="text" id="zip" name="pincode" placeholder="400060">
                </div>
              </div>
            </div>

            <div class="col-50">
              <div class="col-25">
                <div class="container">
                  <h4>Books In Cart</h4>
                  <?php
                  $grand_total = 0;
                  $select_cart = mysqli_query($conn, "SELECT * FROM `cart`") or die('query failed');
                  if (mysqli_num_rows($select_cart) > 0) {
                    while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                      $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
                      $grand_total += $total_price;
                  ?>
                      <p> <a href="book_details.php?details=<?php echo $fetch_cart['book_id']; ?>"><?php echo $fetch_cart['name']; ?></a><span class="price">(<?php echo '₹ ' . $fetch_cart['price'] . '/-' . ' x ' . $fetch_cart['quantity']; ?>)</span> </p>
                  <?php
                    }
                  } else {
                    echo '<p class="empty">your cart is empty</p>';
                  }
                  ?>

                  <hr>
                  <p>Grand total : <span class="price" style="color:black">₹ <b><?php echo $grand_total; ?>/-</b></span></p>
                </div>
              </div>
              <div style="margin: 20px;">
                <h3>Payment </h3>
                <label for="fname">Accepted Payment Gateways</label>
                <div class="icon-container">
                  <i class="fa fa-cc-visa" style="color:navy;"></i>
                  <i class="fa-brands fa-cc-amazon-pay"></i>
                  <i class="fa-brands fa-google-pay" style="color:red;"></i>
                  <i class="fa fa-cc-paypal" style="color:#3b7bbf;"></i>
                </div>
                <div class="inputBox">
                  <label for="method">Choose Payment Method :</label>
                  <select name="method" id="method">
                    <option value="cash on delivery">Cash on delivery</option>
                    <option value="Debit card">Debit card</option>
                    <option value="Amazon Pay">Amazon Pay</option>
                    <option value="Paypal">Paypal</option>
                    <option value="Google Pay">Google Pay</option>
                  </select>
                </div>
              </div>
            </div>

          </div>
          <label>
            <input type="checkbox" checked="checked" name="sameadr"> Shipping address same as billing
          </label>
          <input type="submit" name="checkout" value="Continue to checkout" class="btn">
        </form>
      </div>
    </div>
  </div>
  <?php include 'index_footer.php'; ?>
  <script>
    setTimeout(() => {
      const box = document.getElementById('messages');

      // 👇️ hides element (still takes up space on page)
      box.style.display = 'none';
    }, 5000);
  </script>
</body>

</html>