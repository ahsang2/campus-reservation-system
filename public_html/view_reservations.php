<?php
require_once('header.php');
setTabTitle("Your Reservations");
echo '<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>';

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

if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
    echo '<script> swal({
  title: "'.$_SESSION['status'].'",
  text: " ",
  icon: "'.$_SESSION['emotion'].'",
  buttons: false,
  timer: 2222,
});
</script>';

echo '<script> swal-overlay {
  background-color: rgba(43, 165, 137, 0.45);
} </script>';

unset($_SESSION['status']);

unset($_SESSION['emotion']);

}





echo "<div class='panel panel-default' style='padding: 25px'>";

echo "<div class='panel-heading title'><h3 class='panel-title'>Your Reservations</h3> </div>";

if (!isset($_GET['past']) || $_GET['past'] == '') {
    # don't include past reservations
    if (!isset($_GET['search']) || $_GET['search'] == '') {
        # No search term
        $query = "SELECT LocName as Location, StartDateTime as StartTime, EndDateTime as EndTime, ResID, LocID FROM Reservation r NATURAL JOIN Location l WHERE endDateTime > NOW() AND NetID='".$_SESSION['NetID']."' ORDER BY startTime, Location";
    } else {
        # Search term
        $search_param = "(UPPER(LocName) LIKE UPPER('%".$_GET['search']."%') OR UPPER(LocID) LIKE UPPER('%".$_GET['search']."%') OR UPPER(DAYNAME(StartDateTime)) LIKE UPPER('%".$_GET['search']."%') OR UPPER(MONTHNAME(StartDateTime)) LIKE UPPER('%".$_GET['search']."%'))";
        $query = "SELECT LocName as Location, StartDateTime as StartTime, EndDateTime as EndTime, ResID, LocID FROM Reservation r NATURAL JOIN Location l WHERE $search_param AND endDateTime > NOW() AND NetID='".$_SESSION['NetID']."' ORDER BY startTime, Location";
    }
    $editmode = true;
} else {
    
    if (!isset($_GET['search']) || $_GET['search'] == '') {
        # No search term
        $query = "SELECT LocName as Location,  StartDateTime as StartTime, EndDateTime as EndTime FROM Reservation r NATURAL JOIN Location l WHERE NetID='".$_SESSION['NetID']."' ORDER BY startTime, Location";
    } else {
        # Search term
        $search_param = "(UPPER(LocName) LIKE UPPER('%".$_GET['search']."%') OR UPPER(LocID) LIKE UPPER('%".$_GET['search']."%') OR UPPER(DAYNAME(StartDateTime)) LIKE UPPER('%".$_GET['search']."%') OR UPPER(MONTHNAME(StartDateTime)) LIKE UPPER('%".$_GET['search']."%'))";
        $query = "SELECT LocName as Location, StartDateTime as StartTime, EndDateTime as EndTime, ResID, LocID FROM Reservation r NATURAL JOIN Location l WHERE $search_param AND NetID='".$_SESSION['NetID']."' ORDER BY startTime, Location";
    }
    $editmode = false;
}

echo '<br>
<form action="'.$_SERVER['REQUEST_URI'].'" method="get">
<div class="form-group">
    <label for="search">Search Terms</label>
    <input type="text" id="search" name="search" class="form-control" placeholder="Ikenberry, ARC, Sunday, August..." aria-label="Username" aria-describedby="basic-addon1" value="'.$_GET['search'].'">
    <small id="searchHelp" class="form-text text-muted">Search by location, location nickname, day of week, or month! (Capitalizations do not matter)</small>
    </div>
<div class="input-group mb-3">
<div class="form-check">
    <input name="past" id="past" type="checkbox" class="form-check-input" id="exampleCheck1" '.((isset($_GET['past']) && $_GET['past'] != '') ? 'checked' : '').'>
    <label class="form-check-label" for="exampleCheck1">Include Past Reservations</label>
</div></div>
<button type="submit" class="btn btn-primary">Search</button>
</form>';

echo "  <br><table class='table table-striped'>";
echo "
  <thead>
    <tr>
      <th scope='col'>Location</th>
      <th scope='col'>Start Time</th>
      <th scope='col'>End Time</th>
      ";
      if (!isset($_GET['past']) || $_GET['past'] == '') {
        echo "<th scope='col'></th>
        <th scope='col'></th>";
      }
echo "    </tr>
  </thead>
  <tbody>";

$result = $link->query($query);

while ($row = $result->fetch_row()) {
    $sd = date_create($row[1]);
    $ed = date_create($row[2]);
    $dfs = date_format($sd, 'D, M j \a\t g:i A');
    $dfe = date_format($ed, 'D, M j \a\t g:i A');

    
    echo "<tr>";
    echo "<td>" . $row[0] . "</td>";
    echo "<td>" . $dfs . "</td>";
    echo "<td>" . $dfe . "</td>";
    if($editmode == true) {

        $var = $row[3];
        echo "<td>" . 
        '<form style="margin:20px" action="updateslot.php" method="post">
        <input name="resid" type="hidden" id="resid" value="' . $var . '" >
        <input name="LocID" type="hidden" id="LocID" value="' . $row[4] . '" >
        <button type="submit" class="btn btn-outline-secondary">Change Time</button>
        </form>'  
        . "</td>";
        
        
        echo "<td>" . 
        '<form style="margin:20px" action="modify_res.php" method="post">
        <input name="cancelid" type="hidden" id="cancelid" value="' . $var . '" >
        <button name="del" id="del" type="submit" class="btn btn-outline-danger">Cancel</button>
        </form>'  
        . "</td>";
        
      
}

    
    
    echo "</tr>";
}

echo "</tbody></table></div>";



close();
?>