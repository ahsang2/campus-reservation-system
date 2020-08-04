<?php
ob_start();
require_once('header.php');

if(isset($_POST['cancelid'])) {
    

    $id = $_POST['cancelid'];
    $query = "DELETE FROM Reservation WHERE ResID = '$id'";
    $link->query($query) or die ('Unable to delete reservation: '. mysqli_error($link));
    $_SESSION['status'] = "Reservation canceled!";
     $_SESSION['emotion'] = "error";
    header('Location: view_reservations.php');
}
/*
if(isset($_POST['resid'])) {
    
    $locid = $_POST['locid'];
    $resid = $_POST['resid'];
    $st = $_POST['stime'];
    $et = $_POST['etime'];


    $q1 = "SELECT StartDateTime, EndDateTime FROM Reservation WHERE ResID = '$resid'";
    $result = $link->query($q1);
    $row = $result->fetch_row();
    $newStart = date_create($row[0]);
    $newEnd = date_create($row[1]);
    date_modify($newStart, '+1 day');
    date_modify($newEnd, '+1 day');
    $sf = date_format($newStart, 'Y-m-d H:i:s');
    $ef = date_format($newEnd, 'Y-m-d H:i:s');
 
    $query = "UPDATE Reservation SET StartDateTime = '$sf', EndDateTime = '$ef' WHERE ResID = '$resid' ";
    $query_run = $link->query($query) or die ('Unable to update the reservation: '. mysqli_error($link));
    if($query_run) {
        $_SESSION['status'] = "Your reservation was updated!";
         $_SESSION['emotion'] = "success";
    }
    else {
        $_SESSION['status'] = "Unable to update";
        $_SESSION['emotion'] = "warning";
    }
    
    
    header('Location: view_reservations.php');
}*/

if(isset($_POST['resid'])) {
    $resid = $_POST['resid'];
    $sd = $_POST['sd'];
    $ed = $_POST['ed'];
    

    $q1 = "SELECT StartDateTime, EndDateTime FROM Reservation WHERE ResID = '$resid'";
    $result = $link->query($q1);
    $row = $result->fetch_row();
    
    $oldStart = date_create($row[0]);
    $oldEnd = date_create($row[1]);
    $newStart = date_create($sd);
    $newEnd = date_create($ed);
    
    if($oldStart = $newStart && $oldEnd == $newEnd) {
            $_SESSION['status'] = "Same reservation time kept!";
             $_SESSION['emotion'] = "success";
             header('Location: view_reservations.php');
             exit();
        }
        
    $sf = date_format($newStart, 'Y-m-d H:i:s');
    $ef = date_format($newEnd, 'Y-m-d H:i:s');
    $NetId = $_SESSION['NetID'];
    
    $q0 = "SELECT * FROM Reservation WHERE StartDateTime='".$sf."' AND EndDateTime='".$ef."' AND NetID='".$NetId."'";
    $result0 = $link->query($q0) or die ('Unable to execute query: '. mysqli_error($link));
    $row = $result0->fetch_row();

    if (mysqli_num_rows($result0) != 0) {
    $_SESSION['status'] = "You already have a reservation at this time";
    $_SESSION['emotion'] = "warning";
    header('Location: view_reservations.php');
    exit();
    }
 
    $query = "UPDATE Reservation SET StartDateTime = '$sf', EndDateTime = '$ef' WHERE ResID = '$resid' ";
    $query_run = $link->query($query) or die ('Unable to update the reservation: '. mysqli_error($link));
    if($query_run) {
        
        
        $_SESSION['status'] = "Your reservation was updated!";
        $_SESSION['emotion'] = "success";
         
    }
    else {
        $_SESSION['status'] = "Unable to update";
        $_SESSION['emotion'] = "warning";
    }
    
    
    header('Location: view_reservations.php');
}
?>