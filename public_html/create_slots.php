<?php
require_once('header.php');

$query = "SELECT LocId, OpeningTime, ClosingTime FROM Location";
$result = $link->query($query);

while ($row = $result->fetch_row()) {
    $curr_time = date_create($row[1]);
    $end_time = date_create($row[2]);
    
    $new_date = new DateTime(date("Y-m-d H:i:s"));
    $new_date->add(new DateInterval('P7D'));
    
    while ($curr_time < $end_time) {
        $new_date->setTime(date_format($curr_time, 'H'), date_format($curr_time, 'i'));
        $start_session = date_format(clone $new_date, "Y-m-d H:i:s");
        $type_start = gettype($start_session);
        $curr_time->add(new DateInterval('PT1H'));
        $new_date->setTime(date_format($curr_time, 'H'), date_format($curr_time, 'i'));
        $end_session = date_format(clone $new_date, "Y-m-d H:i:s");
        $type_end = gettype($start_session);
        
        $new_query = "INSERT INTO Slot VALUES ('$start_session', '$row[0]', '$end_session', 0, 50)";
        
        $link->query($new_query) or die ('Unable to execute query: '. mysqli_error($link));
    }
}

close();
?>