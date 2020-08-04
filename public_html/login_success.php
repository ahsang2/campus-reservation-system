<?php
require_once('header.php');
ob_start();
//setTabTitle("Logged In!");

if(isset($_SESSION['home']) && $_SESSION['home'] == "yes") {
    echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
    exit();
}

?>
<div class="alert alert-success" role="alert" style="margin:20px">
  <h4 class="alert-heading">Successfully Logged In!</h4>
  <hr>
  <p class="mb-0"><a href="create_reservation.php">Make a reservation</a> or <a href="view_reservations.php">view your reservations</a></p>
</div>
<?php
close();
?>