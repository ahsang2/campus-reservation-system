<?php
require_once('header.php');
setTabTitle("Added Reservation!");

if (!isset($_SESSION['NetID']) || $_SESSION['NetID'] == '') {
    echo '
    <div class="alert alert-danger" role="alert" style="margin:20px">
    <h4 class="alert-heading">You must be logged in</h4>
    <hr>
    <p class="mb-0"><a href="login.php">Login here</a> or <a href="create_user.php">create a new account</a> to create a reservation</p>
    ';
    close();
    exit();
}


$NetId = $_SESSION['NetID'];
$LocId = $_GET['LocID'] ?? '';
$StartDateTime = $_GET['SDate'] ?? '1000-01-01 00:00:00';
$EndDateTime = $_GET['EDate'] ?? '1000-01-01 00:00:00';

# Check if full
$query = "SELECT CurrCapacity, MaxCapacity FROM Slot WHERE LocID='".$LocId."' AND StartTime='".$StartDateTime."'";

$result = $link->query($query) or die ('Unable to execute query: '. mysqli_error($link));

$row = $result->fetch_row();

$currCap = $row[0];

if ($row[0] == $row[1]) {
    echo '<div class="alert alert-danger" role="alert" style="margin:20px">
    <h4 class="alert-heading">Reservation Error</h4>
    <p class="mb-0">This reservation timeslot has reached max capacity. <a href="create_reservation.php">Go back</a> and book another timeslot</p></div>';
    close();
    exit();
}


# Check if user already has reservation during that time
//$query = "SELECT * FROM Reservation WHERE LocID='".$LocId."' AND StartDateTime='".$StartDateTime."' AND NetID='".$NetId."'";
$query = "SELECT * FROM Reservation WHERE StartDateTime='".$StartDateTime."' AND EndDateTime = '".$EndDateTime."' AND NetID='".$NetId."'";
$result = $link->query($query) or die ('Unable to execute query: '. mysqli_error($link));

$row = $result->fetch_row();

if (mysqli_num_rows($result) != 0) {
    echo '<div class="alert alert-danger" role="alert" style="margin:20px">
        <h4 class="alert-heading">Reservation Error</h4>
        <p class="mb-0">You already have a reservation for this timeslot. <a href="create_reservation.php">Go back</a> and book another timeslot or <a href="view_reservations.php">view</a> your reservations</p></div>';
    close();
    exit();
}

# Update capacity
//$query = "UPDATE Slot SET currCapacity=".($currCap+1)." WHERE LocID='".$LocId."' AND StartTime='".$StartDateTime."'";

//$result = $link->query($query) or die ('Unable to execute query: '. mysqli_error($link));

# Insert new reservation
$query = "INSERT INTO Reservation ".
        "(ResID, NetID, LocID, StartDateTime, EndDateTime) ".
        "VALUES (NULL, '$NetId', '$LocId', '$StartDateTime', '$EndDateTime')";
        
$link->query($query) or die ('Unable to execute query: '. mysqli_error($link));
?>
<div class="alert alert-success" role="alert" style="margin:20px">
  <h4 class="alert-heading">Successfully Made Reservation!</h4>
  <hr>
  <p class="mb-0">Thank you for making our campus a safer place! You can view your reservations <a href="view_reservations.php">here</a></p>
</div>
<?

close();
?>
