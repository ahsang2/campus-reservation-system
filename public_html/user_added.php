<?php
require_once('header.php');
setTabTitle("Added User!");

$Netid = $_POST['NetID'] ?? '';
$FirstName = $_POST['FirstName'] ?? '';
$LastName = $_POST['LastName'] ?? '';
$Dorm = $_POST['Dorm'] ?? '';
$Pass = $_POST['Password'] ?? '';
$Pass1 = $_POST['Password1'] ?? '';

if ($Pass != $Pass1) {
    echo '<div class="alert alert-danger" role="alert" style="margin:20px">
  <h4 class="alert-heading">Passwords do not match</h4>
  <hr>
  <p class="mb-0"><a href="create_user.php">Go back</a> and ensure you typed the same password twice</p>
</div>';
    close();
    exit();
}

$query = "SELECT * FROM Student WHERE NetID='$Netid'";

$result = $link->query($query) or die ('Unable to execute query: '. mysqli_error($link));

if ($result->fetch_row()) {
    echo "";
        echo "<div class=\"alert alert-danger\" role=\"alert\" style=\"margin:20px\">
  <h4 class=\"alert-heading\">NetID already exists</h4>
  <hr>
  <p class=\"mb-0\">User with NetID $Netid already exists. Please <a href=\"login.php\">login</a> instead</p>
</div>";
    close();
    exit();
}

$Pass = saltAndHash($Pass);

$query = "INSERT INTO Student ".
       "(FirstName, LastName, NetID, HealthCondition, Dorm, UserPassword) ".
       "VALUES ('$FirstName', '$LastName', '$Netid', 'Good', '$Dorm','$Pass')";

$link->query($query) or die ('Unable to execute query: '. mysqli_error($link));

echo '<div class="alert alert-success" role="alert" style="margin:20px">
  <h4 class="alert-heading">Created Account!</h4>
  <hr>
  <p class="mb-0">Thank you for creating an account! Have fun safely going to places around campus and make sure to <a href="login.php">login</a>!</p>
</div>';

close();
?>
