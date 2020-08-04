<?php
ob_start();
require_once('header.php');
session_start();

 $shnetid = $_SESSION['NetID'];
 
class Schedule {
    
    
    public $starttime = [];
    public $endtime = [];
    public $resids = [];
    public $w1 = [];
    public $w2 = [];
    public $w3 = [];
    public $news = [];
    public $newe = [];
  
    public function addstart($state) {
        array_push($this->starttime, $state);
    }
    public function addend($state) {
        array_push($this->endtime, $state);
    }
    public function addres($state) {
        array_push($this->resids, $state);
    }
    public function addw1($state) {
        array_push($this->w1, $state);
    }
    public function addw2($state) {
        array_push($this->w2, $state);
    }
    public function addw3($state) {
        array_push($this->w3, $state);
    }
    public function newsa($state) {
        array_push($this->news, $state);
    }
    public function newea($state) {
        array_push($this->newe, $state);
    }
}

    $sch1 = new Schedule;

    
    $bytime = "SELECT COUNT(ResID) FROM Reservation WHERE TIME(StartDateTime) BETWEEN '07:00:00' AND '11:00:00' AND NetID <> '$shnetid'";
    $num = $link->query($bytime);
    $morning = $num->fetch_row()[0];
    $bytime2 = "SELECT COUNT(ResID) FROM Reservation WHERE TIME(StartDateTime) BETWEEN '11:00:01' AND '15:00:00' AND NetID <> '$shnetid'";
    $num2 = $link->query($bytime2);
    $afternoon = $num2->fetch_row()[0];
    $bytime3 = "SELECT COUNT(ResID) FROM Reservation WHERE TIME(StartDateTime) BETWEEN '15:00:01' AND '24:00:00' AND NetID <> '$shnetid'";
    $num3 = $link->query($bytime3);
    $evening = $num3->fetch_row()[0];

    $total = $morning + $afternoon + $evening;
    $morning =  ($total - $morning)/$total;
    $afternoon = ($total - $afternoon)/$total;
    $evening = ($total -$evening)/$total;
 
 
    $d =  $_SESSION['thedate'];
    $query = "SELECT DISTINCT StartTime, EndTime FROM Availability WHERE DATE(StartTime) = STR_TO_DATE('".$d."','%Y-%m-%d') AND Type LIKE 'gym' AND NetID = '".$shnetid."' ORDER BY StartTime";
    $result = $link->query($query);
 
    while($row = $result->fetch_row()) {

    $sd = date_create($row[0]);
    $ed = date_create($row[1]);
  
    $sch1->addstart($sd);
    $sch1->addend($ed);
    /*
    $qqq2 = "SELECT COUNT(*) FROM Availability WHERE NetID = '".$shnetid."' AND StartTime = '".$row[0]."' AND EndTime = '".$row[1]."' AND Type = 'gym' "; //OR LocID LIKE '%CRCE%')";
    $qres2 = $link->query($qqq2);
    $res2 = $qres2->fetch_row();
    
    if($res2[0] != 0) {
        $sch1->addaval(true);
    }
    else {
        $sch1->addaval(false);
    }*/
   
    }
    
    $q2 = "SELECT ResID FROM Reservation WHERE NetID = '".$shnetid."' AND DATE(StartDateTime) = STR_TO_DATE('".$d."','%Y-%m-%d') AND StartDateTime > NOW() AND LocID LIKE '%ARC%' ORDER BY ResID";
    $result1 = $link->query($q2);
    $i = 0;

    while($row = $result1->fetch_row()) {

        $i++;
        $sch1->addres($row[0]);
    }
    
    if($i == 0) {
        $_SESSION['status2'] = "Your don't have any ARC reservations on this day";
        $_SESSION['emotion2'] = "info";
        header('Location: availability.php');
        exit();
    }
    
  
    $qmain = "SELECT StartTime, EndTime, LocID, CurrCapacity, MaxCapacity FROM Slot NATURAL JOIN (SELECT * FROM Availability WHERE NetID = '$shnetid' and Type = 'gym') AS avals WHERE DATE(StartTime) = STR_TO_DATE('".$d."','%Y-%m-%d') AND TIME(StartTime) BETWEEN '07:00:00' AND '11:00:00' AND LocID LIKE '%ARC%' ORDER BY LocID, StartTime";
    $qr = $link->query($qmain);
    
    while($row = $qr->fetch_row()) {
        $ssel1 =  date_format(date_create($row[0]), 'Y-m-d H:i:s');
        $esel1 =  date_format(date_create($row[1]), 'Y-m-d H:i:s');
        
        $qsel = "SELECT COUNT(*) FROM Reservation WHERE NetID = '$shnetid' AND LocID = '$row[2]' AND StartDateTime = '$ssel1' AND EndDateTime = '$esel1'";
        $zoo = (($link->query($qsel))->fetch_row())[0];
        
        if($row[2] == "ARC1") {
           
            $sch1->addw1((1 - ($row[3]-$zoo)/$row[4])*$morning);
        }
        else if($row[2] == "ARC2") {
            $sch1->addw2((1 - ($row[3]-$zoo)/$row[4])*$morning);
        }
        else if($row[2] == "ARC3") {
            $sch1->addw3((1 - ($row[3]-$zoo)/$row[4])*$morning);
        }
        
    }
    
    $qmain2 = "SELECT StartTime, EndTime, LocID, CurrCapacity, MaxCapacity FROM Slot NATURAL JOIN (SELECT * FROM Availability WHERE NetID = '$shnetid' and Type = 'gym') AS avals WHERE DATE(StartTime) = STR_TO_DATE('".$d."','%Y-%m-%d') AND TIME(StartTime) BETWEEN '11:00:01' AND '15:00:00' AND LocID LIKE '%ARC%' ORDER BY LocID, StartTime";
    $qr2 = $link->query($qmain2);
    
    while($row = $qr2->fetch_row()) {
        $ssel1 =  date_format(date_create($row[0]), 'Y-m-d H:i:s');
        $esel1 =  date_format(date_create($row[1]), 'Y-m-d H:i:s');
        $qsel = "SELECT COUNT(*) FROM Reservation WHERE NetID = '$shnetid' AND LocID = '$row[2]' AND StartDateTime = '$ssel1' AND EndDateTime = '$esel1'";
        $zoo = (($link->query($qsel))->fetch_row())[0];
        if($row[2] == "ARC1") {
            $sch1->addw1((1 - ($row[3]-$zoo)/$row[4]) *$afternoon);
        }
        else if($row[2] == "ARC2") {
            $sch1->addw2((1 - ($row[3]-$zoo)/$row[4]) *$afternoon);
        }
        else if($row[2] == "ARC3") {
            $sch1->addw3((1 - ($row[3]-$zoo)/$row[4]) *$afternoon);
        }
        
    }
    
    $qmain3 = "SELECT StartTime, EndTime, LocID, CurrCapacity, MaxCapacity FROM Slot NATURAL JOIN (SELECT * FROM Availability WHERE NetID = '$shnetid' and Type = 'gym') AS avals WHERE DATE(StartTime) = STR_TO_DATE('".$d."','%Y-%m-%d') AND TIME(StartTime) BETWEEN '15:00:01' AND '24:00:00' AND LocID LIKE '%ARC%' ORDER BY LocID, StartTime";
    $qr3 = $link->query($qmain3);
    
    while($row = $qr3->fetch_row()) {
        $ssel1 =  date_format(date_create($row[0]), 'Y-m-d H:i:s');
        $esel1 =  date_format(date_create($row[1]), 'Y-m-d H:i:s');
        $qsel = "SELECT COUNT(*) FROM Reservation WHERE NetID = '$shnetid' AND LocID = '$row[2]' AND StartDateTime = '$ssel1' AND EndDateTime = '$esel1'";
        $zoo = (($link->query($qsel))->fetch_row())[0];

        if($row[2] == "ARC1") {
           
        
            $sch1->addw1((1 - ($row[3]-$zoo)/$row[4])*$evening);
        }
        else if($row[2] == "ARC2") {
            $sch1->addw2((1 - ($row[3]-$zoo)/$row[4])*$evening);
        }
        else if($row[2] == "ARC3") {
            $sch1->addw3((1 - ($row[3]-$zoo)/$row[4])*$evening);
        }
        
    }
    

    for ($x = 0; $x < count($sch1->resids); $x++) {
        $var = $sch1->resids[$x];
        $qresid = "SELECT LocID FROM Reservation WHERE ResID = '$var'";
        $resultresid = ($link->query($qresid))->fetch_row();
        
        
        if($resultresid[0] == "ARC1") {
            $temp = $sch1->w1;
            rsort($temp);
            $key = array_search($temp[0], $sch1->w1);
        }
        else if($resultresid[0] == "ARC2") {
            $temp = $sch1->w2;
             rsort($temp);
         $key = array_search($temp[0], $sch1->w2);
        }
        else if($resultresid[0] == "ARC3") {
            $temp = $sch1->w3;
            rsort($temp);
            $key = array_search($temp[0], $sch1->w3);
        }
        
        
       
        
        $sch1->w1[(int)$key] = -1;
        $sch1->w2[(int)$key] = -1;
        $sch1->w3[(int)$key] = -1;
       
        //echo date_format($sch1->starttime[$key], 'Y-m-d H:i:s');
        
       $sch1->newsa($sch1->starttime[(int)$key]);
       $sch1->newea($sch1->endtime[(int)$key]);
        
    }
  

?>