<?php
require_once('header.php');
setTabTitle("User Stats");

echo '<style>
.green {
  width: 51px;
  height: 51px;
  border-style: solid;
  border-width: 1px;
  background-color: #2ecc71;
  display: inline-block; 
  overflow: hidden; 
}

.yellow {
  width: 51px;
  height: 51px;
  border-style: solid;
  border-width: 1px;
  background-color: #f1c40f;
  display: inline-block; 
  overflow: hidden; 
}


.red {
  width: 51px;
  height: 51px;
  border-style: solid;
  border-width: 1px;
  background-color: #e74c3c;
  display: inline-block; 
  overflow: hidden; 
}


.weekday {
  width: 47px;
  height: 20px;
  display: inline-block;
  text-align: center;
  overflow: hidden; 
}

.location-text {
    width: 250px;
    height: 20px;
    display: inline-block;
    text-align: center;
    overflow: hidden; 
}

.location-label {
    width: 250px;
    height: 50px;
    display: inline-block;
    text-align: center;
    overflow: hidden; 
}

</style>';

# Build array
$daysOfWeek = ["Sunday"=>0, "Monday"=>0, "Tuesday"=>0, "Wednesday"=>0, "Thursday"=>0, "Friday"=>0, "Saturday"=>0];
$locations = [];

$locationsQuery = "SELECT LocName FROM Location ORDER BY LocName";

$result = $link->query($locationsQuery) or die ('Unable to execute query: '. mysqli_error($link));

while ($row = $result->fetch_row()) {
    $locations[$row[0]] = $daysOfWeek;
}

$curr_user = $_SESSION['NetID'];

$query = "SELECT DISTINCT DAYNAME(StartDateTime), COUNT(LocID), LocName FROM Reservation NATURAL JOIN Location WHERE NetID = '$curr_user' GROUP BY DAYNAME(StartDateTime), LocID";

$result = $link->query($query) or die ('Unable to execute query: '. mysqli_error($link));

while ($row = $result->fetch_row()) {
    $locations[$row[2]][$row[0]] = (int)$row[1];
}

while ( list($key, $val) = each($locations) ) {
    $max = floatval(max($locations[$key]));
        
    if ($max > 0) {
        while ( list($loc, $num) = each($val) ) {
            $locations[$key][$loc] = floatval($num)/$max;
        }
    }
}
unset($val);
unset($key);
unset($loc);

echo "
<span class='location-text'></span>
<span class='weekday'>Sun</span>
<span class='weekday'>Mon</span>
<span class='weekday'>Tue</span>
<span class='weekday'>Wed</span>
<span class='weekday'>Thu</span>
<span class='weekday'>Fri</span>
<span class='weekday'>Sat</span><br>";

foreach ($locations as $key => $value) {
    echo "<span class='location-label'>".$key."</span>";
    foreach ($value as $day => $freq) {
        if ($freq > 0.6) {
            echo "<span class='red'></span>";
        } else if ($freq < 0.3) {
            echo "<span class='green'></span>";
        } else {
            echo "<span class='yellow'></span>";
        }
    }
    echo "<br><br>";
}

close();
?>