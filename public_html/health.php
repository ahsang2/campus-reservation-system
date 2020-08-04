<?php
ob_start();
require_once('header.php');
setTabTitle("Contact Tracing");
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

 $netid = $_SESSION['NetID'];
 
if(isset($_POST['health']) && $_POST['health'] != '') {
    $h = $_POST['health'];
    $query = "UPDATE Student SET HealthCondition = '$h' WHERE NetID = '$netid' ";
    $link->query($query) or die ('Unable to execute query: '. mysqli_error($link));
    $_SESSION['tested'] = "yes";
    $_SESSION['emotion'] = "success";
    unset($_POST['health']);
}

if (isset($_SESSION['tested']) && $_SESSION['tested'] == 'yes') {
    echo '<script> swal({
  title: "Thank you! Your test has been reported.",
  text: " ",
  icon: "'.$_SESSION['emotion'].'",
  buttons: false,
  timer: 2222,
});
</script>';

unset($_SESSION['tested']);

unset($_SESSION['emotion']);

}



echo "<div class='panel panel-default' style='padding: 25px'>";

echo "<div class='panel-heading title'><h3 class='panel-title'>Viewing Your Past Reservations</h3> <br> <h5 class='panel-title'>This is a list of your reservations (in the last 2 weeks) which had another individual who tested positive for covid-19. </h5></br></div>";


echo "  <br><table class='table table-striped'>";
echo "
  <thead>
    <tr>
      <th scope='col'>Location</th>
      <th scope='col'>Date</th>
      <th scope='col'>Start Time</th>
      <th scope='col'>End Time</th>
      ";
      
echo "    </tr>
  </thead>
  <tbody>";
  
$twoweeksback = date("Y-m-d H:i:s", strtotime("-14 days"));
$q2 = "SELECT LocName, yourres.StartDateTime, yourres.EndDateTime FROM (SELECT * FROM Reservation WHERE NetID = '$netid' AND EndDateTime < NOW() AND StartDateTime > '$twoweeksback') AS yourres JOIN (SELECT * FROM Reservation NATURAL JOIN Student WHERE HealthCondition = 'Bad' AND NetID <> '$netid') as positiveres ON (yourres.LocID = positiveres.LocID AND yourres.StartDateTIme = positiveres.StartDateTime) JOIN Location l ON (yourres.LocID = l.LocID)";
$result3 = $link->query($q2);

while ($row = $result3->fetch_row()) {
    $sd = date_create($row[1]);
    $ed = date_create($row[2]);
    $dfs = date_format($sd, 'D, M j');
    $dfs1 = date_format($sd, 'g:i A');
    $dfe = date_format($ed, 'g:i A');

    
    echo "<tr>";
    echo "<td>" . $row[0] . "</td>";
    echo "<td>" . $dfs . "</td>";
    echo "<td>" . $dfs1 . "</td>";
    echo "<td>" . $dfe . "</td>";
 

        $var = $row[3];
        
        
      


    
    
    echo "</tr>";
}

echo "</tbody></table></div>";



echo "</div>";
    
    



//reporting footer buttons
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
        <div id="footer">';


     echo "<table style='margin:10px' class='table table-striped'>";
        echo "
    <thead>
    <tr>
      <th scope='col'>Report your covid-19 test</th>
      <th scope='col'></th>

      </tr>
    </thead>
     <tbody>";
  
    echo  "<td>" .
        '<form style="margin:20px" action="health.php" method="post">
        <input name="health" type="hidden" id="health" value="Bad" >
        <button type="submit" class="btn btn-outline-secondary">Positive Test</button>
        </form>'  . "</td>" 
       ;
        
        echo "<td>" .
        '<form style="margin:20px" action="health.php" method="post">
        <input name="health" type="hidden" id="health" value="Good" >
        <button type="submit" class="btn btn-outline-secondary">Negative Test</button>
        </form>'  ."</td>" 
       ;
       
       echo '</tr>';
//end reporting buttons        
        
    echo '</div>
    ';
   

 

    



       





























   


close();
?>