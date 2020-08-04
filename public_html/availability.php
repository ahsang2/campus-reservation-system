<?php
ob_start();
require_once('header.php');
setTabTitle("Recommendations");
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

if (isset($_SESSION['status2']) && $_SESSION['status2'] != '') {
    echo '<script> swal({
  title: "'.$_SESSION['status2'].'",
  text: " ",
  icon: "'.$_SESSION['emotion2'].'",
  buttons: false,
  timer: 2222,
});
</script>';

echo '<script> swal-overlay {
  background-color: rgba(43, 165, 137, 0.45);
} </script>';

unset($_SESSION['status2']);

unset($_SESSION['emotion2']);

}

$netid = $_SESSION['NetID'];

if(isset($_POST['resid']) && $_POST['resid'] == "default") {
    $idarray = $_SESSION['uniqueresids'];
    $startarray = $_SESSION['uniquestimes'];
    $endarray = $_SESSION['uniqueetimes'];
    
   
    $q0 = "SELECT * FROM Reservation WHERE StartDateTime='".$sf."' AND EndDateTime='".$ef."' AND NetID='".$NetId."'";
    $result0 = $link->query($q0) or die ('Unable to execute query: '. mysqli_error($link));
    $row = $result0->fetch_row();

    if (mysqli_num_rows($result0) != 0) {
    $_SESSION['status'] = "You have a conflicting reservation, please update your availability.";
    $_SESSION['emotion'] = "warning";
    header('Location: view_reservations.php');
    exit();
    }
    
    for ($x1 = 0; $x1 < count($idarray); $x1++) {
     $resid = $idarray[$x1];
     $sf =  date_format($startarray[$x1], 'Y-m-d H:i:s');
     $ef =  date_format($endarray[$x1], 'Y-m-d H:i:s');
     
    $query = "UPDATE Reservation SET StartDateTime = '$sf', EndDateTime = '$ef' WHERE ResID = '$resid' ";
    $query_run = $link->query($query) or die ('Unable to update the reservation: '. mysqli_error($link));
    if(!$query_run) {
        $_SESSION['status'] = "Unable to switch to this schedule, please update your availability.";
        $_SESSION['emotion'] = "warning";
        header('Location: view_reservations.php');
        unset($_SESSION['uniqueresids']);
        unset($_SESSION['uniquestimes']);
        unset($_SESSION['uniqueetimes']);
        exit();
    }
    
    }
    
    $_SESSION['status'] = "Your reservation was updated!";
    $_SESSION['emotion'] = "success";

       
        unset($_SESSION['uniqueresids']);
        unset($_SESSION['uniquestimes']);
        unset($_SESSION['uniqueetimes']);
        header('Location: view_reservations.php');
        exit();
}


if(isset($_POST['Date']) && $_POST['Date'] != '') {
    $NetId = $_SESSION['NetID'];

    $qqq = "SELECT COUNT(ResID) FROM Reservation WHERE NetID = '".$NetId."' AND DATE(StartDateTime) = STR_TO_DATE('".$_POST['Date']."','%Y-%m-%d') AND StartDateTime > NOW() AND LocID LIKE '%ARC%' "; //OR LocID LIKE '%CRCE%')";
    $qres = $link->query($qqq);
    $res = $qres->fetch_row();
    $qqq2 = "SELECT COUNT(*) FROM Availability WHERE NetID = '".$NetId."' AND DATE(StartTime) = STR_TO_DATE('".$_POST['Date']."','%Y-%m-%d') AND Type = 'gym' "; //OR LocID LIKE '%CRCE%')";
    $qres2 = $link->query($qqq2);
    $res2 = $qres2->fetch_row();


    if($res2[0] < $res[0]) {
        $_SESSION['status2'] = "Please update your availability before continuing";
        $_SESSION['emotion2'] = "warning";
        header('Location: availability.php');
        exit();
    }
    
    echo'   
        <form style="margin:20px" action="availability.php" >
        
        <div class="form-group">
            <label for="emp"><h6>Our recommended reservation times for </h6></label>
            <select id="emp" class="form-control" name="emp">';
            
            $query = "SELECT DISTINCT DATE(StartDateTime) FROM Reservation WHERE NetID = '$netid' AND DATE(StartDateTime) = STR_TO_DATE('".$_POST['Date']."','%Y-%m-%d')";
            
            $result = $link->query($query);
            
            while ($row = $result->fetch_row()) {
                $d = date_format(date_create($row[0]), "l F j, Y");
                echo '<option value="'.$row[0].'">'.$d.'</option>';
            }
                
echo'    </select>
        
        <br>
        
        <button type="submit" class="btn btn-dark">Choose Another Date</button>
        </div>
    </form><br>
    ';

//recommendation part
    $_SESSION['thedate'] = $_POST['Date'];
    require_once('schedule.php');
    $matches = true;
   // $sch1->addval(true);
  //  $sch1->addval(true);
   // echo $shnetid;
    
   // $newStart = date_create($value);
   
   
   
   echo "  <table  class='table table-striped'>";
echo "
  <thead>
    <tr>
      <th scope='col'>Location</th>
      <th scope='col'>Current Time</th>
      <th scope='col'>Suggested Time</th>
      <th scope='col'></th>
    </tr>
  </thead>
  <tbody>";




    
    for ($x = 0; $x < count($sch1->resids); $x++) {
        $var = $sch1->resids[$x];
        $queryd = "SELECT StartDateTime, EndDateTime, LocName FROM Reservation NATURAL JOIN Location WHERE ResID = '$var'";
        $resd = $link->query($queryd);
        while ($row = $resd->fetch_row()) { 
            $sd = date_create($row[0]);
            $ed = date_create($row[1]);
            
            if($sd != $sch1->news[$x] || $ed != $sch1->newe[$x]) {
                $matches = false;
            }
            
            $dfs = date_format($sd, 'g:i A');
            $dfe = date_format($ed, 'g:i A');
        
        //echo date_format($sch1->news[$x], 'Y-m-d H:i:s');
     //  echo " ";
     //  echo date_format($sch1->newe[$x], 'Y-m-d H:i:s');
       
            echo "<tr>";
            echo "<td>" . $row[2] . "</td>";
            echo "<td>";
            echo ''.$dfs.' - '.$dfe.'';
            echo "</td>";
            echo "<td>";
            echo ''.date_format($sch1->news[$x], 'g:i A').' - '.date_format($sch1->newe[$x], 'g:i A').'';
            echo "</td>";
        }

    
     

}

     
    echo "</tbody></table></div>";
    
    
        
     $_SESSION['uniqueresids'] = $sch1->resids;

    $_SESSION['uniquestimes'] = $sch1->news;

    $_SESSION['uniqueetimes'] = $sch1->newe;
    
   if(!$matches) {
       echo  
        '<form style="margin:20px" action="availability.php" method="post">
        <input name="resid" type="hidden" id="resid" value="default" >
        <button type="submit" class="btn btn-outline-dark">Switch to this schedule</button>
        </form>';
   }
   else {
       echo'   
<style> 
        #footer { 
            position: fixed; 
            padding: 10px 10px 0px 10px; 
            bottom: 0; 
            width: 100%; 
            /* Height of the footer*/  
            height: 200px; 
            background: snow; 
        } 
    </style> 
        <div id="footer">
        
        <form style="margin:20px" action="select_aval.php" method="post">
        <h6> Your schedule configuration is the best according to your availability... </h6>
        <div class="form-group">
            <label for="Date"><h6>Want to modify your availability?</h6></label>
            <select id="curdur" class="form-control" name="curdur">';
    $d = $_POST['Date'];
    $query = "SELECT DISTINCT DATE(StartDateTime) AS d FROM Reservation WHERE NetID = '$netid' AND DATE(StartDateTime) = STR_TO_DATE('".$d."','%Y-%m-%d') AND endDateTime > NOW() ORDER BY d";
            
            $result = $link->query($query);
            
            while ($row = $result->fetch_row()) {
                $d = date_format(date_create($row[0]), "l F j, Y");
                echo '<option value="'.$row[0].'">'.$d.'</option>';
            }
                
echo'    </select>
        
        <br>
        
        <button type="submit" class="btn btn-primary">Select Date</button>
        </div>
    </form><br>
    </div>
    ';
   }
  
    
    
    unset($_SESSION['thedate']);
    
    
}
else if(!isset($_POST['curdur']) || $_POST['curdur'] == '') {
    echo'   
        <form style="margin:20px" action="availability.php" method="post">
        
        <div class="form-group">
            <label for="Date"><h6>Find the safest reservation times on </h6></label>
            <select id="Date" class="form-control" name="Date">';
    
            $query = "SELECT DISTINCT DATE(StartDateTime) AS d FROM Reservation WHERE NetID = '$netid' AND LocID LIKE '%ARC%' AND endDateTime > NOW() ORDER BY d";
            
            $result = $link->query($query);
            
            while ($row = $result->fetch_row()) {
                $d = date_format(date_create($row[0]), "l F j, Y");
                echo '<option value="'.$row[0].'">'.$d.'</option>';
            }
                
echo'    </select>
        
        <br>
        
        <button type="submit" class="btn btn-dark">View</button>
        </div>
    </form><br>
    ';
    
    
    
echo'   
<style> 
        #footer { 
            position: fixed; 
            padding: 10px 10px 0px 10px; 
            bottom: 0; 
            width: 100%; 
            /* Height of the footer*/  
            height: 200px; 
            background: snow; 
        } 
    </style> 
        <div id="footer">
        <form style="margin:20px" action="select_aval.php" method="post">
        <div class="form-group">
            <label for="Date"><h6>Want to modify your availability?</h6></label>
            <select id="curdur" class="form-control" name="curdur">';
    
            $query = "SELECT DISTINCT DATE(StartDateTime) AS d FROM Reservation WHERE NetID = '$netid' AND endDateTime > NOW() ORDER BY d";
            
            $result = $link->query($query);
            
            while ($row = $result->fetch_row()) {
                $d = date_format(date_create($row[0]), "l F j, Y");
                echo '<option value="'.$row[0].'">'.$d.'</option>';
            }
                
echo'    </select>
        
        <br>
        
        <button type="submit" class="btn btn-primary">Select Date</button>
        </div>
    </form><br>
    </div>
    ';
}


close();
?>