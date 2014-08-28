<?php 

print_r($_POST); // debug

if(isset($_POST['bla'])) {
$date = $_POST['date'];
$time = $_POST['time'];
$racecoursesID = $_POST['racecoursesID'];
$name = $_POST['raceName'];
$classID = $_POST['classID'];
$goingID = $_POST['goingID'];
$distance = $_POST['distance'];
$prize = $_POST['prize'];

$sql_update_racecard = 
"UPDATE `racecards` SET `date`='$date', `time`='$time', `name`='$name', `raceClassID`='$classID', 
`goingID`='$goingID', `distance`='$distance', `prize`='$prize' WHERE ID = '$racecardID'
";

$result=mysql_query($sql_update_racecard);

}

?>