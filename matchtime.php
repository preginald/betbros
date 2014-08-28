<?php
protect_page(); 
//print_r_pre($_POST);

$fixtureID = mysql_real_escape_string($_GET['fixid']);

if(isset($_POST['add_matchtime'])){
	$periodID=$_POST['periodID'];
	$time="00:". $_POST['minute'] . ":" . $_POST['second'];
	$incidentID=$_POST['incidentID'];

	$createTimestamp = date("Y-m-d H:i:s");

	//Check if $incidentID already exists for $fixtureID

	$result=mysql_query(
	"SELECT 1 FROM matchTime AS mtch
	WHERE mtch.fixtureID=$fixtureID AND 
	mtch.incidentID=$incidentID");

	if (mysql_fetch_row($result)) {
		echo "Exists";
	}else{
		echo kickoff_check_insert($fixtureID, $session_user_id,$createTimestamp);
		// START insert matchtime incident
		$sql="INSERT INTO `matchTime`(
			`fixtureID`, 
			`incidentID`, 
			`periodID`, 
			`time`, 
			`createUserID`, 
			`createTimestamp`) 
		VALUES (
			'$fixtureID',
			'$incidentID',
			'$periodID',
			'$time',
			'$session_user_id',
			'$createTimestamp')";
		$result=mysql_query($sql);
		if($result){
			// echo "successfully updated score";
		} else {
			echo "ERROR: Matchtime insert";
		}
		// END insert matchtime incident
	}
}

// START check match end procedure
	$result=mysql_query(
		"SELECT 1 FROM matchTime AS mtch
		WHERE mtch.fixtureID=$fixtureID AND 
		mtch.incidentID=16");

	if (mysql_fetch_row($result)) {
		echo "Match ended";
		$sql="UPDATE  `fixtures` SET  `fixtureStatusID` =  '4' 
		WHERE `ID` = $fixtureID";
		$result=mysql_query($sql);
		if($result){
			// echo "successfully updated score";
			football_betDetail_update($fixtureID);
			} else {
				echo "ERROR: Fixture status update";
			}
	}else{
		echo "Match pending";
	}
// END check match end procedure

//-----------------------------------------------------------

if(isset($_GET['addmatchtime']) && $_GET['addmatchtime']=="1"){
	echo "Add matchtime";
	?>
	<form method="post" action="" >

	
	<select name="periodID" required><?php period_dropDown($sportsID) ?></select>
	
	<span class="small">Minute</span>
	<select name="minute" required><?php minute_dropDown() ?></select>	

	<span class="small">Second</span>
	<select name="second" required><?php second_dropDown() ?></select>

	<select name="incidentID" required>
		<option value="">Select event</option>
		<option value="15">Half Time</option>
		<option value="16">Full Time</option>
	</select>

	<input type="submit" name="add_matchtime" value="Add">
	</form>
	<?php 
}

//if(isset($_GET['addmatchtime']) && ($_GET['addmatchtime']=="1" || $_GET['matchtime']=="view")){

	echo "<h2>Match Times</h2>";

	$sql=
	"SELECT
	prd.name AS prdname,
	mtch.time AS stime,

	i.name AS iname

	FROM matchTime AS mtch

	INNER JOIN period AS prd
	ON mtch.periodID=prd.ID

	INNER JOIN incident AS i
	ON mtch.incidentID=i.ID

	WHERE mtch.fixtureID=$fixtureID
	ORDER BY prd.orderby,stime ASC";

	$result = mysql_query($sql);

	?>

	<table class="bordered">
	<thead>
	<tr>
	<th>Half</th>
	<th>Time</th>
	<th></th>
	<th></th>
	</tr>
	</thead>

	<?php
	while($row=mysql_fetch_assoc($result)){
		?>
		<tr>
		<td><?php echo $row['prdname'] ?></td>
		<td><?php echo $row['stime'] ?></td>
		<td><?php echo "<strong>" . $row['iname'] ."</strong>" ?></td>
		<td>update delete</td>
		<?php
	}

	echo "</table>";


?>