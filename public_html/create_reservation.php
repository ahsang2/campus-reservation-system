<?php
require_once('header.php');
setTabTitle("Create New Reservation");

echo '<title>Make Reservation</title>';

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

if(isset($_SESSION['home'])) {
    unset($_SESSION['home']);
}

#SELECT DISTINCT DATE(StartTime) FROM (SELECT StartTime FROM Slot WHERE StartTime > NOW()) AS OnlyFuture
echo "<div class='panel panel-default' style='padding: 25px'>";

echo "<div class='panel-heading title'><h3 class='panel-title'>Make a Reservation</h3> </div>";

    echo '<form style="margin:20px" action="create_reservation.php" method="get">
        <div class="form-group">
            <label for="LocID">Location</label>
            <select id="LocID" class="form-control" name="LocID">';
    
            $locationsQuery = "SELECT LocID, LocName FROM Location";
            
            $result = $link->query($locationsQuery);
            
            while ($row = $result->fetch_row()) {
                echo '<option value="'.$row[0].'">'.$row[1].'</option>';
            }
                
echo'    </select>
        </div>
        <div class="form-group">
            <label for="Date">Date</label>
            <select id="Date" class="form-control" name="Date">';
    
            $query = "SELECT DISTINCT DATE(StartTime) FROM (SELECT StartTime FROM Slot WHERE StartTime > NOW()) AS OnlyFuture";
            
            $result = $link->query($query);
            
            while ($row = $result->fetch_row()) {
                $d = date_format(date_create($row[0]), "l F j, Y");
                echo '<option value="'.$row[0].'">'.$d.'</option>';
            }
                
echo'    </select>
        
        <br>
        
        <button type="submit" class="btn btn-primary">Load Slots</button>
    </form><br>
    ';
    
if (isset($_GET['LocID']) && $_GET['LocID'] != '') {
        
    
        $query = "SELECT StartTime, EndTime, CurrCapacity, MaxCapacity FROM Slot WHERE DATE(StartTime) = STR_TO_DATE('".$_GET["Date"]."','%Y-%m-%d') AND LocID='".$_GET['LocID']."' AND EndTime > NOW()";
            $var9 = date_format(date_create($_GET['Date']), "l F j, Y");
            echo '<br><h3> Time slots for '.$var9.' </h3></br>';
            $result = $link->query($query);
        echo "<table style='margin:10px' class='table table-striped'>";
        echo "
  <thead>
    <tr>
      <th scope='col'>Start Time</th>
      <th scope='col'>End Time</th>
      <th scope='col'>Capacity</th>
      <th scope='col'>Max Capacity</th>
      <th></th>
    </tr>
  </thead>
  <tbody>";
  
  while($row = $result->fetch_row()) {
    $sd = date_create($row[0]);
    $startDate = date_format($sd, 'g:i A');
    
    $ed = date_create($row[1]);
    $endDate = date_format($ed, 'g:i A');
    
    echo "<tr>";
    echo "<td>" . $startDate . "</td>";
    echo "<td>" . $endDate . "</td>";
    echo "<td>" . $row[2] . "</td>";
    echo "<td>" . $row[3] . "</td>";
    if ($row[2] == $row[3]) {
        echo "<td></td>";
    } else {
        $clean1 = str_replace(":","%3A", str_replace(" ","%20",$row[0]));
        $clean2 = str_replace(":","%3A", str_replace(" ","%20",$row[1]));
        $url = "http://reservation.web.illinois.edu/reservation_added.php?LocID=".$_GET['LocID']."&SDate=".$clean1."&EDate=".$clean2;
        echo "<td><button type='button' class='btn btn-primary' onclick=\"window.location.href='$url'\">Book</button></td>";
    }
    echo '</tr>';
  }
    
  echo '</tbody></table>';
}
close();        
?>