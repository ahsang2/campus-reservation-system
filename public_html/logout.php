<?php
require_once 'header.php';
setTabTitle("Logged Out!");

if (isset($_SESSION['NetID']) && $_SESSION['NetID'] != "") {
    $_SESSION['NetID'] = "";
    echo '<meta http-equiv="refresh" content="0;url=http://reservation.web.illinois.edu/logout.php">';
    close();
    exit();
}

?>
<div class="alert alert-success" role="alert" style="margin:20px">
  <h4 class="alert-heading">Successfully Logged Out!</h4>
  <hr>
  <p class="mb-0">Closing out of all your open tabs is a good idea</p>
</div>
<?php
close();
?>