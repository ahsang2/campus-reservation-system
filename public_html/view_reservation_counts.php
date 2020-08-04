<?php
include 'header.php';
echo "<div class='panel panel-default'>
  <div class='panel-heading title'><h3 class='panel-title'>All Locations</h3></div>

  <table style='padding: 25px' class='table'>";

echo "<tr><th>Location</th><th>Number of Reservations</th></tr>";
$query = "SELECT l.LocID as Location, COUNT(r.ResID) as cnt FROM Reservation r right JOIN Location l ON r.LocID = l.LocID GROUP BY l.LocID ORDER BY COUNT(r.ResID) DESC";

$result = $link->query($query);

while ($row = $result->fetch_row()) {
    echo "<tr><td>$row[0]</td><td>$row[1]</td></tr>";
}

echo "</table></div>";
?>