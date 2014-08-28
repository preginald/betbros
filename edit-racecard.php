<?php

$racecardID = $_GET['rcid'];

// procedure to update form data to table 
if(isset($_POST['submit'])) {
	$race = $_POST['race'];
	$date = $_POST['date'];
	$time = $_POST['time'];
	$racecoursesID = $_POST['racecoursesID'];
	$name = $_POST['raceName'];
	$classID = $_POST['classID'];
	$goingID = $_POST['goingID'];
	$distance = $_POST['distance'];
	$prize = $_POST['prize'];

	$sql_update_racecard = 
	"UPDATE `racecards` SET `racecoursesID`='$racecoursesID',`date`='$date', `time`='$time', `name`='$name', `raceClassID`='$classID', 
	`goingID`='$goingID', `distance`='$distance', `prize`='$prize' WHERE ID = '$racecardID'
	";

	$result=mysql_query($sql_update_racecard);

}

if(!empty($racecardID)) {

	// echo $racecardID; // debugging

	$sql_sr = "SELECT * FROM racecards WHERE ID = $racecardID";
	$result_sr = mysql_query($sql_sr);
	$rows_sr =  mysql_fetch_assoc($result_sr);
}

$race = $rows_sr['race'];
$date = $rows_sr['date'];
$time = $rows_sr['time'];
$racecoursesID = $rows_sr['racecoursesID'];
$name = $rows_sr['name'];
$runners = $rows_sr['runners'];
$classID = $rows_sr['raceClassID'];
$goingID = $rows_sr['goingID'];
$distance = $rows_sr['distance'];
$prize = $rows_sr['prize'];

?>


<h2>Edit racecard</h2>
<form name="form1" method="post" action="" >
<input type="number" name="race" min="1" style="width:3em" placeholder="#" value="<?php echo $race ?>" >
<input type="date" name="date" value="<?php echo $date ?>" >
<input type="time" name="time" value="<?php echo $time ?>" >
<select name="racecoursesID"><?php racecourses_dropdown() ?></select>
<input type="text" name="raceName" placeholder="Race name" value="<?php echo $name ?>" >
<input type="number" name="runners" min="0" style="width:3em" placeholder="Runners" value="<?php echo $runners ?>" >
<select name="classID"><?php raceClass_dropdown() ?></select>
<select name="goingID"><?php going_dropdown() ?></select>
<input type="number" name="distance" min="0" style="width:5em" placeholder="Distance" value="<?php echo $distance ?>" >
<input type="number" name="prize" min="0" style="width:7em" placeholder="Prize" value="<?php echo $prize ?>" >
<input type="submit" name="submit" value="Update">
</form>

<!--
if (has_access($user_data['user_id'], 1) === true) {

// display the add button

} 
-->