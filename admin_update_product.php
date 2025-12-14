<?php

include 'connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   /*header('location:login.php');*/
};

if(isset($_POST['update_product'])){

   $update_id = $_POST['update_id'];
   $name = mysqli_real_escape_string($con, $_POST['name']);
   $price = mysqli_real_escape_string($con, $_POST['price']);
   $detail = mysqli_real_escape_string($con, $_POST['detail']);

   $update_success = mysqli_query($con, "UPDATE `products` SET name = '$name', price = '$price', detail = '$detail' WHERE id = '$update_id'") or die('query failed');

   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folter = 'uploaded_img/'.$image;
   $old_image = $_POST['update_image'];

   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'image file size is too large!';
      }else{
         mysqli_query($con, "UPDATE `products` SET image = '$image' WHERE id = '$update_id'") or die('query failed');
         move_uploaded_file($image_tmp_name, $image_folter);
         unlink('uploaded_img/'.$old_image);
      }
   }

   if(empty($message)){
      $message[] = 'product updated successfully!';
   }
}


   



?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update product</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin.css">

</head>
<body>
   
   <?php
if(isset($message)){
   foreach($message as $msg){
      echo '<div class="message"><span>'.$msg.'</span> <i class="fas fa-times" onclick="this.parentElement.remove();"></i></div>';
   }
}
?>

   
<?php @include 'admin_header.php'; ?>

<section class="update-product">

<?php
if (isset($_GET['update'])) {
   $update_id = $_GET['update'];
   $select_products = mysqli_query($con, "SELECT * FROM `products` WHERE id = '$update_id'") or die('query failed');

   if(mysqli_num_rows($select_products) > 0){
      while($fetch_products = mysqli_fetch_assoc($select_products)){
?>

<form action="" method="post" enctype="multipart/form-data">
   <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" class="image"  alt="">
   <input type="hidden" value="<?php echo $fetch_products['id']; ?>" name="update_id">
   <input type="hidden" value="<?php echo $fetch_products['image']; ?>" name="update_image">
   <input type="text" class="box" value="<?php echo $fetch_products['name']; ?>" required placeholder="update product name" name="name">
   <input type="number" min="0" class="box" value="<?php echo $fetch_products['price']; ?>" required placeholder="update product price" name="price">
   <textarea name="detail" class="box" required placeholder="update product details" cols="30" rows="10"><?php echo $fetch_products['detail']; ?></textarea>
   <input type="file" accept="image/jpg, image/jpeg, image/png" class="box" name="image">
   <input type="submit" value="update product" name="update_product" class="btn">
   <a href="admin_product.php" class="option-btn">go back</a>
</form>

<?php
      }
   } else {
      echo '<p class="empty">No product found with this ID!</p>';
   }

} else {
   echo '<p class="empty">No product selected for update!</p>';
}
?>


</section>













<script src="js/admin.js"></script>

</body>
</html>