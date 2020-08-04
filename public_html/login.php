<?php
require_once("header.php");
setTabTitle("Login");

if (isset($_SESSION['NetID']) && $_SESSION['NetID'] != '') {
        echo '<div class="alert alert-danger" role="alert" style="margin:20px">
  <h4 class="alert-heading">You are already Logged In</h4>
  <hr>
  <p class="mb-0"><a href="create_reservation.php">Make a reservation</a> or <a href="view_reservations.php">view your reservations</a></p>
    </div>';
    close();
    exit();
}

if (isset($_GET['login_failure'])) {
            echo '<div class="alert alert-danger" role="alert" style="margin:20px">
  <p class="mb-0">'.$_GET['login_failure'].
    '</p></div>';
}

echo '
<div style="margin:20px">
<h4>Login</h4>
<hr>
<form action="login_followup.php" method="post">
  <div class="form-group">
    <label for="NetID">NetID</label>
    <input name="NetID" type="text" class="form-control" id="NetID" placeholder="NetID">
  </div>
  
    <div class="form-group">
    <label for="Password">Password</label>
    <input name="Password" type="password" class="form-control" id="password" placeholder="Password">
  </div>
  
  <button type="submit" class="btn btn-primary">Login</button>
  
  </form>
  </div>
';
close();
?>