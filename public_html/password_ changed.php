<?php
require_once('header.php');
setTabTitle("Changed Password!");

$Netid = $_POST['NetID'] ?? '';
$Pass = $_POST['Password'] ?? '';

$query = "SELECT * FROM Student WHERE NetID='$Netid'";

$result = $link->query($query) or die ('Unable to execute query: '. mysqli_error($link));

if (!$result->fetch_row()) {
    echo "";
        echo "<div class=\"alert alert-danger\" role=\"alert\" style=\"margin:20px\">
  <h4 class=\"alert-heading\">NetID must exist</h4>
  <hr>
  <p class=\"mb-0\">User with NetID $Netid does not exists. Please <a href=\"create_user.php\">create a user</a> instead</p>
</div>";
    close();
    exit();
}

$Pass = saltAndHash($Pass);

$checkPassSame = "SELECT * FROM Student WHERE NetID='$Netid' AND UserPassword='$Pass'";

$result = $link->query($checkPassSame) or die ('Unable to execute query: '. mysqli_error($link));

if ($result->fetch_row()) {
    echo "";
        echo "<div class=\"alert alert-danger\" role=\"alert\" style=\"margin:20px\">
  <h4 class=\"alert-heading\">Password must not be the same</h4>
  <hr>
  <p class=\"mb-0\">User with NetID '$Netid' already has this password. Please <a href=\"login.php\">login</a> instead</p>
</div>";

    close();
    exit();
}

$updateQuery = "UPDATE Student SET UserPassword='$Pass' WHERE NetID='$Netid'";

$link->query($updateQuery) or die ('Unable to execute query: '. mysqli_error($link));

echo '<div class="alert alert-success" role="alert" style="margin:20px">
  <h4 class="alert-heading">Changed Password!</h4>
  <hr>
  <p class="mb-0">Thank you for changing your password! Have fun safely going to places around campus and make sure to <a href="login.php">login</a>!</p>
</div>';

close();
?>