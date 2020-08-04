<?php
ob_start();
require_once('header.php');
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

if(isset($_POST['resid'])) {

    $resid = $_POST['resid'];


    echo '<form style="margin:20px" action="create_reservation.php" method="get">
        <div class="form-group">
            <label for="LocID">Location</label>
            <select id="LocID" class="form-control" name="LocID">';
    
            $locationsQuery = "SELECT LocID, LocName FROM Location NATURAL JOIN Reservation WHERE ResID = '$resid'";
            
            $result = $link->query($locationsQuery);
            
            while ($row = $result->fetch_row()) {
                echo '<option value="'.$row[0].'">'.$row[1].'</option>';
            }
                
echo'    </select>
        </div>
        <div class="form-group">
            <label for="Date">Date</label>
            <select id="Date" class="form-control" name="Date">';
    
            $query = "SELECT DISTINCT DATE(StartDateTime) FROM Reservation WHERE ResID = '$resid'";
            
            $result = $link->query($query);
            
            while ($row = $result->fetch_row()) {
                $d = date_format(date_create($row[0]), "l F j, Y");
                echo '<option value="'.$row[0].'">'.$d.' </option>';
            }
                
echo'    </select>
        
        <br>

    </form><br>
    ';
    
    
if (isset($_POST['LocID']) && $_POST['LocID'] != '') {
    
        
        $query0 = "SELECT DATE(StartDateTime), StartDateTime, EndDateTime FROM Reservation WHERE ResID = '$resid'";
        $result0 = $link->query($query0);

        while ($row = $result0->fetch_row()) {
                $d = $row[0];
                $curs = date_create($row[1]);
                $cure = date_create($row[2]);
            }
            
            
        
       
        $query = "SELECT StartTime, EndTime, CurrCapacity, MaxCapacity FROM Slot WHERE DATE(StartTime) = STR_TO_DATE('".$d."','%Y-%m-%d') AND LocID='".$_POST['LocID']."' AND CurrCapacity <> MaxCapacity";
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
        if($sd == $curs && $ed == $cure) {
           echo "<td>" . 
        '<form style="margin:20px" action="modify_res.php" method="post">
        <input name="resid" type="hidden" id="resid" value="' . $resid . '" >
        <input name="LocID" type="hidden" id="LocID" value="' . $row[4] . '" >
        <input name="sd" type="hidden" id="sd" value= "' . $row[0] . '">
        <input name="ed" type="hidden" id="ed" value="' . $row[1] . '" >
        <button type="submit" class="btn btn-outline-dark">Current</button>
        </form>'  
        . "</td>";
        }
        else {
            echo "<td>" . 
        '<form style="margin:20px" action="modify_res.php" method="post">
        <input name="resid" type="hidden" id="resid" value="' . $resid . '" >
        <input name="LocID" type="hidden" id="LocID" value="' . $row[4] . '" >
        <input name="sd" type="hidden" id="sd" value= "' . $row[0] . '">
        <input name="ed" type="hidden" id="ed" value="' . $row[1] . '" >
        <button type="submit" class="btn btn-outline-primary">Choose</button>
        </form>'  
        . "</td>";
        }
         
    }
    echo '</tr>';
  }
    
  echo '</tbody></table>';
}
       





























   
}

close();
?>