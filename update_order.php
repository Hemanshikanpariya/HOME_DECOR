<?php

@include 'connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   // header('location:login.php');
}

// Update logic
if (isset($_POST['update_order'])) {
   $order_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];

   mysqli_query($con, "UPDATE `orders` SET payment_status = '$update_payment' WHERE id = '$order_id'") or die('Query failed');

   header('location:admin_order.php');
   exit();
}

// Get order details
if (isset($_GET['id'])) {
   $order_id = $_GET['id'];
   $select_order = mysqli_query($con, "SELECT * FROM `orders` WHERE id = '$order_id'") or die('Query failed');

   if (mysqli_num_rows($select_order) > 0) {
      $order = mysqli_fetch_assoc($select_order);
   } else {
      header('location:admin_order.php');
      exit();
   }
} else {
   header('location:admin_order.php');
   exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Update Order</title>
   <link rel="stylesheet" href="css/update_order.css">
</head>
<body>

<?php @include 'admin_header.php'; ?>

<section class="form-container">
   <form action="" method="post">
      <h3>Update Order Status</h3>
      <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">

      <p>Order ID: <strong><?php echo $order['id']; ?></strong></p>
      <p>User: <strong><?php echo $order['name']; ?></strong></p>
      <p>Email: <strong><?php echo $order['email']; ?></strong></p>
      <p>Total Price: <strong>$<?php echo $order['total_price']; ?></strong></p>
      <p>Current Status: <strong style="color:<?php echo $order['payment_status'] == 'completed' ? 'green' : 'red'; ?>">
         <?php echo $order['payment_status']; ?></strong></p>

      <select name="update_payment" class="select-box" required>
         <option value="" disabled selected>Change payment status</option>
         <option value="pending">Pending</option>
         <option value="completed">Completed</option>
      </select>

      <input type="submit" name="update_order" value="Update" class="btn yellow-btn">
      <a href="admin_order.php" class="btn maroon-btn">Cancel</a>
   </form>
</section>

</body>
</html>
