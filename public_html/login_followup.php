<?php
require_once("header.php");
setTabTitle("Logging In...");

$Netid = $_POST['NetID'] ?? '';
$Pass = $_POST['Password'] ?? '';

if ($Netid == '') {
    echo '<meta http-equiv="refresh" content="0;url=http://reservation.web.illinois.edu/login.php?login_failure=NetID%20cannot%20be%20empty">';
    close();
    exit();
}

if ($Pass == '') {
    echo '<meta http-equiv="refresh" content="0;url=http://reservation.web.illinois.edu/login.php?login_failure=Pass%20cannot%20be%20empty">';
    close();
    exit();
}

$Pass = saltAndHash($Pass);

$query = "SELECT NetId FROM Student
            WHERE NetId='$Netid' AND UserPassword='$Pass'";
            
$result = $link->query($query);
        
        $name = '';
        
        while ($row = $result->fetch_row()) {
            $name = $row[0];
        }
        
        if ($name == '') {
            echo '<meta http-equiv="refresh" content="0;url=http://reservation.web.illinois.edu/login.php?login_failure=Incorrect%20NetID%20and%20password%20combiantion">';
            close();
             exit();
        }

$_SESSION['NetID'] = $name;

echo '<meta http-equiv="refresh" content="0;url=http://reservation.web.illinois.edu/login_success.php">';

close();
?>