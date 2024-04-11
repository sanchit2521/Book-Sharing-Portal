<?php
require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;
$dompdf = new Dompdf;
include 'config.php';

if(isset($_GET['order_id'])){
  $order_id = $_GET['order_id'];
  
  // Fetch order details
  $query = "SELECT co.*, o.* FROM `confirm_order` co JOIN `orders` o ON co.order_id = o.id WHERE co.order_id = '$order_id'";
  $result = mysqli_query($conn, $query) or die('query failed');
  
  if(mysqli_num_rows($result) > 0){
    $row = mysqli_fetch_assoc($result);
    $fetch_order = $row; // Assigning the fetched row to $fetch_order
    $fetch_details = $row; // Assigning the fetched row to $fetch_details
  }
}

$html = '
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Invoice</title>
  <style>
    .invoice .section-top{
      justify-content: center;
      text-align: center;
    }
    .invoice-title{
      margin: auto;
      font-weight: bold;
    }
    .logo{
      margin: auto;
    }
    .logo a{
      display: flex;
      cursor: pointer;
    }
    .logo a span {
      color: brown;
      font-weight: bold;
      padding-right: 5px;
      font-size: 30px;
    }
    .logo a .me {
      color: black;
      font-weight: 500;
    }
    .invoice .section-mid{
      display: flex;
      justify-content: space-between; 
    }
    hr{
      color: rgba(0,0,0,0.5);
    }
    tbody th{
      text-align: center;
    }
    .section-bott .colspan{
      margin: 10px 0 0 0;
    }
  </style>
</head>
<body>
  <div class="invoice">
    <div class="section-top">
      <div class="logo">
        <a><span>Book Sharing Portal &</span></a>
      </div>
      <div class="invoice-title">Invoice Details</div>
    </div>
    <hr>
    <table>
      <tr>
        <th class="details">
          <div class="section-mid-one">
            <h3>SHIPPING ADDRESS:</h3>
            <div class="buyer-details">
              <p class="buyer-name">To, '.$fetch_order['name'].' </p>
              <p class="buyer-add"> Address, '.$fetch_order['address'].'</p>
              <p class="buyer-area"> city, '.$fetch_order['city'].'</p>
              <p class="buyer-city"> state, '.$fetch_order['state'].'</p>
              <p class="buyer-STATE"> Country, '.$fetch_order['country'].'</p>
              <p class="buyer-STATE"> Pincode, '.$fetch_order['pincode'].'</p>
            </div>
          </div>
        </th>
        <th class="details">
          <div class="section-mid-one">
            <h3>SOLD BY:</h3>
            <div class="buyer-details">
              <p class="buyer-name">By,  Book Sharing Portal</p>
              <p class="buyer-add">Address</p>
              <p class="buyer-area">Area</p>
              <p class="buyer-city">city</p>
              <p class="buyer-STATE">state</p>
            </div>
          </div>
        </th>
        <th class="details">
          <div class="section-mid-one">
            <h3>Details:</h3>
            <div class="buyer-details">
              <p class="buyer-name">Invoice Date: '.$fetch_order['date'].'</p>
              <p class="buyer-add">Order ID: '.$fetch_order['order_id'].' </p>
              <p class="buyer-area">Order Date: '.$fetch_order['order_date'].'</p>
              <p class="buyer-city">From: Read Me</p>
              <p class="buyer-STATE">Payment Method: '.$fetch_order['payment_method'].' </p>
            </div>
          </div>
        </th>
      </tr>
    </table>
  </div>
  <hr>
  <div class="section-bott" style="padding: 0 86px;">
    <table style="width: 100%;">
      <thead>
        <th>S.No.</th>
        <th>BOOK NAME</th>
        <th>QTY</th>
        <th>UNIT PRICE</th>
        <th>Total</th>
      </thead>
      <tbody>';

// Fetch order items and calculate total price
$select_book = mysqli_query($conn, "SELECT * FROM `orders` WHERE id = '$order_id'") or die('query failed');
$s = 1;
$total_price = 0;
while($fetch_book = mysqli_fetch_assoc($select_book)){
  $total_price += $fetch_book['sub_total'];
  $html .= '<tr>
              <td>'.$s.'</td>
              <td>'.$fetch_book['book'].'</td>
              <td>'.$fetch_book['quantity'].'</td>
              <td>'.$fetch_book['unit_price'].'</td>
              <td>'.$fetch_book['sub_total'].'</td>
            </tr>';
  $s++;
}

$html .= '<tr>
            <td colspan="4">NET TOTAL</td>
            <td>'.$total_price.'</td>
          </tr>';

$html .= '</tbody>
    </table>
  </div>
  <hr />
  <div>
    <div class="sign">Book Sharing Portal</div>
  </div>
</div>
</body>
</html>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('invoice',array('Attachment'=>0));
?>
