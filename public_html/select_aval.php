<?php
ob_start();
require_once('header.php');
setTabTitle("Availability Form");
echo '<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>';
echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css'>";
echo "<link rel='stylesheet' href='selection.css'>";

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

if((isset($_POST['curdur']))) {
    $cd = date_create($_POST['curdur']);
    $cdd = date_format($cd, 'F j, Y');
    

    echo ' <form style="margin:20px" action="select_aval.php" method="post"> <div class = "form-group"> <h4>Select available times to attend a gym  </h4> </div>';
    
    $d = $_POST['curdur'];
    $query = "SELECT DISTINCT StartTime, EndTime FROM Slot WHERE DATE(StartTime) = STR_TO_DATE('".$d."','%Y-%m-%d') AND LocID LIKE '%ARC%' ORDER BY StartTime";
    $result = $link->query($query);

            
        echo "<table style='margin:2px' class='table table-striped' >";
        echo "
  <thead>
    <tr>
      <th scope='col'>Openings for ARC on $cdd </th>
      <th></th>
    </tr>
  </thead>
  <tbody>";
  
  
  
  while($row = $result->fetch_row()) {
   
    $sd = date_create($row[0]);
    $startDate = date_format($sd, 'g:i A');
    
    $ed = date_create($row[1]);
    $endDate = date_format($ed, 'g:i A');
    $q123 = "SELECT COUNT(*) FROM Availability WHERE NetID = '".$_SESSION['NetID']."' AND Type = 'gym' AND StartTime = '". $row[0] ."' ";
    $qres123 = $link->query($q123);
    $selected = $qres123->fetch_row();
    if($selected[0] != 0) {
        echo "<tr>";
    echo "<td>" . 
       
       '<div class="pretty p-icon p-curve p-smooth">
        
        <input type="checkbox" value="' . $row[0] . '" name="gymtimee[]" checked >
        <div class="state p-success">
           <label>   ' . $startDate . ' - ' . $endDate . '   </label>
        </div>
    </div>'
         . "</td>";

    echo '</tr>';
    }
    else {
        echo "<tr>";
    echo "<td>" . 
       
       '<div class="pretty p-icon p-curve p-smooth">
        
        <input type="checkbox" value="' . $row[0] . '" name="gymtimee[]" >
        <div class="state p-success">
           <label>   ' . $startDate . ' - ' . $endDate . '   </label>
        </div>
    </div>'
         . "</td>";

    echo '</tr>';
    }
    
    
  }
  
   $temp = date_create($d);
   $var = date_format($temp, 'Y-m-d H:i:s');
   
  echo '</tbody></table> <input name="theday" type="hidden" id="theday" value="' . $var . '" > <button type="submit" class="btn btn-outline-dark">Update</button> </form>';
}
else if (isset($_POST['gymtimee'])) {
    $NetId = $_SESSION['NetID'];

  
   $qqq = "SELECT COUNT(ResID) FROM Reservation WHERE NetID = '".$NetId."' AND DATE(StartDateTime) = STR_TO_DATE('".$_POST['theday']."','%Y-%m-%d') AND StartDateTime > NOW() AND LocID LIKE '%ARC%' "; //OR LocID LIKE '%CRCE%')";
    $qres = $link->query($qqq);
    $res = $qres->fetch_row();
    $times = $_POST['gymtimee'];

    if(count($times) < $res[0]) {
        
        $_SESSION['status2'] = "Your don't have enough availability to match your reservations.";
        $_SESSION['emotion2'] = "warning";
        header('Location: availability.php');
        exit();
    }
    
    
    $q1 = "DELETE FROM Availability WHERE NetID='".$_SESSION['NetID']."'";
    $link->query($q1);
    
    foreach($times as $key => $value) {
        
        $newStart = date_create($value);
        $sf = date_format($newStart, 'Y-m-d H:i:s');
   
       
        $newEnd = date_create(date('Y-m-d H:i:s',strtotime('+1 hour',strtotime($value))));
        $ef = date_format($newEnd, 'Y-m-d H:i:s');

        
        $query = "INSERT INTO Availability ".
        "(NetID, StartTime, EndTime, Type) ".
        "VALUES ('$NetId', '$sf', '$ef', 'gym')";
      
        $link->query($query) or die ('Unable to execute query: '. mysqli_error($link));
        
    }
    
    $_SESSION['status2'] = "Your availability was updated!";
    $_SESSION['emotion2'] = "success";
    header('Location: availability.php');

}
else {
    echo  
        '<form style="margin:20px" action="select_aval.php" method="post">
        <input name="curdur" type="hidden" id="curdur" value="' . $_POST['theday'] . '" >
        <button name="del" id="del" type="submit" class="btn btn-outline-dark">Back</button>
        </form>
        ' ;
        
  
        
    echo '
    <div class="alert alert-danger" role="alert" style="margin:20px">
    <h4 class="alert-heading">Oh no! </h4>
    <hr>
    <p class="mb-0">Please go back and select avalability slots or manage your reservations</p> </div>
    ';
    
    echo '<form style="margin:20px" action="view_reservations.php">
        <button name="man" id="man" type="submit" class="btn btn-outline-secondary">Manage Reservations</button>
        </form>';
}

close();
?>