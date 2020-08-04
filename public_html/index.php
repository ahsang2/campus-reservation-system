<?php 
require_once("header.php");
setTabTitle("Reservation Home");

echo '';
echo "<link rel='stylesheet' href='home.css'>";
$logged;
$_SESSION['home'];

if (!isset($_SESSION['NetID']) || $_SESSION['NetID'] == '') {
    $logged = false;
}
else {
    $logged = true;
}

if(isset($_SESSION['home'])) {
    unset($_SESSION['home']);
}


echo '

    <div class="section">

        <h1> On-Campus Reservation System </h1>
        
        <div class="video-container">
            <div class="color-overlay"></div>
            <video poster="https://mdbootstrap.com/img/Photos/Others/background.jpg" playsinline autoplay muted loop>
            <source src="https://mdbootstrap.com/img/video/animation.mp4" type="video/mp4">
            </video>
        
        </div>
        ';
        
    if(!$logged) {
        echo '<p1> This website aims to allow for the safe use of indoor spaces as college campus reopen. <br>To access our website <a href="login.php">login</a> or <a href="create_user.php">create a new account</a>. </br> </p1>';
        
        $_SESSION['home'] = "yes";
    }
    else {
        $query = "SELECT FirstName FROM Student WHERE NetID = '" . $_SESSION['NetID'] . "'";
        
        $result = $link->query($query);
        
        $name = '';
        
        while ($row = $result->fetch_row()) {
            $name = $row[0];
        }
       echo '<p1> Welcome, '. $name .'  ! <br> Use the navigation bar above to <a href="create_reservation.php">make</a>/<a href="view_reservations.php">view</a> reservations or <a href="campus_stats.php">get information</a> on the crowdedness of locations.</br></p1>';
    }
        
        
    
        
        
        
    echo '</div>';
                            
                            
                            
                    
        echo '<p2> <center>Due to the high risk of the spread of coronavirus in gyms across America, a system is necessary to ensure the number of people using a gym does not breech social distancing protocols. The University of Illinois has committed to the reopening of both the ARC and CRCE beginning August 10th, 2020.</center></p2>';


echo '<center><img src="ARC_19_ACP_Wintergarden-01.jpg" style=style="border:10px;margin:10px";width="200"; height="150"></center>';
echo '<center><img src="UIUC-ARC-Center-3-Champaign-IL.jpg" style="border:10px;margin:10px";width="200"; height="150"></center>';





close();
exit(); 
?>
