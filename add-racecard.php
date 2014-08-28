<?php

if(isset($_POST["date"]) && isset($_POST["time"]) && isset($_POST["racecoursesID"]) && isset($_POST["raceName"])) {
	echo "adding racecard";
	$race = $_POST['race'];
	$date = $_POST['date'];
	$time = $_POST['time'];
	$racecoursesID = $_POST['racecoursesID'];
	$name = $_POST['raceName'];
	$runners = $_POST['runners'];
	$classID = $_POST['classID'];
	$goingID = $_POST['goingID'];
	$distance = $_POST['distance'];
	$prize = $_POST['prize'];

// need to perform a check to see if a similar racecard already exists for the same day, time and track

$sql_add_racecard = 
"INSERT INTO `racecards`(`racecoursesID`,`race`, `date`, `time`, `name`, `runners` , `raceClassID`, `goingID`, `distance`, `prize`) 
VALUES ('$racecoursesID','$race','$date','$time','$name','$runners','$classID','$goingID','$distance','$prize')
";

$result=mysql_query($sql_add_racecard);

}

?>
<h2>Add new racecard</h2>
<form name="form1" method="post" action="">
<input type="number" name="race" min="1" style="width:3em" placeholder="#" required>
<input type="date" name="date" value="<?php echo $date ?>" required>
<input type="time" name="time" required>
<select required name="racecoursesID"><?php racecourses_dropdown() ?></select>
<input type="text" name="raceName" placeholder="Race name" >
<input type="number" name="runners" min="0" style="width:3em" placeholder="Runners" >
<select required name="classID"><?php raceClass_dropdown() ?></select>
<select required name="goingID"><?php going_dropdown() ?></select>
<input type="number" name="distance" min="0" style="width:5em" placeholder="Distance" >
<input type="number" name="prize" min="0" style="width:7em" placeholder="Prize" >
<input type="submit" name="Submit" value="Add">
</form>



<!--
if (has_access($user_data['user_id'], 1) === true) {

// display the add button

} 
-->