<script type="text/javascript" src="js/teams.js"></script>
<?php 
protect_page(); 
//print_r_pre($_POST);

// get values from url
$sportsID = mysql_real_escape_string($_GET['sid']); 
$esID = $eventSeasonID = mysql_real_escape_string($_GET['esid']);
$teamSeasonID = mysql_real_escape_string($_GET['tsid']);
$eventName = "(" . strtoupper($row['calpha']) . ") " . date("Y",strtotime($row['sdate']))."-".date("y",strtotime($row['edate']))." ".$row['spnname']." ". $row['ename'];

$cID=esID_cID($eventSeasonID);

// query to get list of sports names for heading
$sql_heading = "SELECT * FROM `sports` WHERE `sportTypeID` = 2";
$result_heading = mysql_query($sql_heading);


if(isset($_POST['add_teamSeason'])){
	$teamID = $_POST['teamID'];
	$eventSeasonID = $_POST['eventSeasonID'];
	$managerID = $_POST['managerID'];
	$captainID = $_POST['captainID'];
	$kitID = $_POST['kitID'];
	$sponsorID = $_POST['sponsorID'];
	$createTimestamp = date("Y-m-d H:i:s");

	if(!empty($_POST['tname'])){
		$tname=$_POST['tname'];

		$sql = 
		$result = mysql_query(
			"INSERT INTO `teams`(
				`name`, `countryID`, `sportID`, `createUserID`, `createTimestamp`
				) 
		VALUES (
			'$tname','$cID','$sportsID','$session_user_id','$createTimestamp')");
		if ($result) {
			$teamID=mysql_insert_id();
			// echo "Added $tname";
		} else {
			echo "ERROR: add new team name";
		}
	}

	if (!empty($_POST['mlname'])) {
		$msex	=	$_POST['msex'];
		$mfname =	$_POST['mfname'];
		$mmname	=	$_POST['mmname'];
		$mlname =	$_POST['mlname'];
		$mdob	=	$_POST['mdob'];
		$mheight=	$_POST['mheight'];
		$mcID	=	$_POST['mcID'];

		$result = mysql_query(
			"INSERT INTO `person`(
				`genderID`,`firstName`,`middleName`,`lastName`,`dob`,`height`, `countryID`,`verified`,`createUserID`, `createTimestamp`
				) 
		VALUES (
			'$msex','$mfname','$mmname','$mlname','$mdob','$mheight','$mcID',0,'$session_user_id','$createTimestamp')");
		if ($result) {
			$managerID=mysql_insert_id();
			// echo "Added $mname";
		} else {
			echo "ERROR: add new manager name";
		}
	}

	if (!empty($_POST['clname'])) {
		$csex	=	$_POST['csex'];
		$cfname =	$_POST['cfname'];
		$cmname	=	$_POST['cmname'];
		$clname =	$_POST['clname'];
		$cdob	=	$_POST['cdob'];
		$cheight=	$_POST['cheight'];
		$ccID	=	$_POST['ccID'];

		$cnumber=	$_POST['cnumber'];
		$cpcID	=	$_POST['cpositionCatID'];		

		$result = mysql_query(
			"INSERT INTO `person`(
				`genderID`,`firstName`,`middleName`,`lastName`,`dob`,`height`, `countryID`,`verified`, `createUserID`, `createTimestamp`
				) 
		VALUES (
			'$csex','$cfname','$cmname','$clname','$cdob','$cheight','$ccID',0,'$session_user_id','$createTimestamp')");
		if ($result) {
			$cpID = $captainID = mysql_insert_id();
			$add_captain=1;
			// echo "Added $cname";
		} else {
			echo "ERROR: add new team captain name";
		}
	}	

	// check if event already exists
	$sql_check_team= "SELECT * FROM `teamSeason` WHERE `teamID` = $teamID";

	$sql_check_team;
	$result=mysql_query($sql_check_team);
	if (mysql_fetch_row($result)) {

		echo "This team already exists in this event";

	} else {

		$sql_add_team = 
		"INSERT INTO `teamSeason`
				(`teamID`, `eventSeasonID`, `managerID`, `captainID`, `kitID`,`sponsorID`,`verified`,`createUserID`, `createTimestamp`) 
		VALUES  ('$teamID','$eventSeasonID','$managerID','$captainID', '$kitID','$sponsorID',0,'$session_user_id','$createTimestamp')";

		$result=mysql_query($sql_add_team);

		// end add event to database process
		// if successfully updated. 

		if($result){
			$teamSeasonID=mysql_insert_id();
			echo "successfully added team";

			if ($add_captain==1) {
				$result=mysql_query(
				"INSERT INTO `teamPlayers`(
				`teamSeasonID`, 
				`number`, 
				`personID`, 
				`positionCatID`, 
				`verified`,
				`createUserID`, 
				`createTimestamp`) 
				VALUES (
					'$teamSeasonID',
					'$cnumber',
					'$cpID',
					'$cpcID',
					0,
					'$session_user_id',
					'$createTimestamp')");

				if($result){
					echo "successfully added team player";
				} else {
					echo "ERROR: could not add team player";
				}
			}

		} else {

			echo "ERROR";
		}
	}
}

if(isset($_GET['addteam']) && $_GET['addteam']=="1"){ ?>
	<h2>Add new team</h2>

	<form name="add-eventTeam" method="post" action="<?=$PHP_SELF?>" >
	<div>
	<span class="small">Event</span>
	<select name="eventSeasonID"><?php event_season_dropDown($sportsID,$eventSeasonID) ?></select>
	</div>

	<br/>

	<h3>Step 1: Select existing team name</h3>
	<div>
	<select name="teamID"><?php teams_dropDown_cID($teamID,$sportsID,$cID) ?></select>
	Or add new team name <input type="text" name="tname" placeholder="Team Name">
	</div>

	<br/>


	<h3>Step 2: Select Team Manager:</h3>
	<div>
	<select name="managerID"><?php person_dropDown() ?></select>
	Or add new manager
	<select name="msex"><?php person_gender_dropdown() ?></select>
	<input type="text" name="mfname" placeholder="First Name" >
	<input type="text" name="mmname" placeholder="Middle Name">
	<input type="text" name="mlname" placeholder="Last Name" ><br/>
	<input type="date" name="mdob" placeholder="Date of birth">
	<input type="number" name="mheight" placeholder="Height">
	<select name="mcID" ><?php country_dropDown() ?></select>
	</div>

	<br/>

	<h3>Step 3: Select Team Captain:</h3>
	<div>
	<span class="small">Captain</span>
	<select name="captainID"><?php person_dropDown() ?></select>
	Or add new captain
	<select name="csex"><?php person_gender_dropdown() ?></select>
	<input type="text" name="cfname" placeholder="First Name" >
	<input type="text" name="cmname" placeholder="Middle Name">
	<input type="text" name="clname" placeholder="Last Name" ><br/>
	<input type="date" name="cdob" placeholder="Date of birth">
	<input type="number" name="cheight" placeholder="Height">
	<select name="ccID" ><?php country_dropDown() ?></select>
	<h3>Step 4: Captain's team info:</h3>
	<input type="number" name="cnumber" placeholder="Team Player Number" >
	<select name="cpositionCatID" ><?php position_cat_dropDown() ?></select>
	</div>

	<br/>

	<h3>Step 5: Select Team Kit Sponsor:</h3>
	<div>
	<span class="small">Kit</span>
	<select name="kitID"><?php brands_dropDown() ?></select>
	</div>

	<br/>

	<h3>Step 6: Select Team Sponsor:</h3>
	<div>
	<span class="small">Sponsor</span>
	<select name="sponsorID"><?php brands_dropDown() ?></select>
	</div>

	<input type="submit" name="add_teamSeason" value="Add">

	</form>

	<?php 
} 


if(isset($_GET['history']) && $_GET['history']=="1"){
	echo "<hr>";
	echo "<h2>Last 5 teams added</h2>";


	$sql=
	"SELECT
	ts.ID AS ID,
	t.ID AS tid,
	ts.ID AS esid,
	t.name AS tname,
	e.name AS ename,
	es.startDate AS sdate,
	es.endDate AS edate,
	man.firstName AS manfname,
	man.lastName AS manlname,
	cap.firstName AS capfname,
	cap.lastName AS caplname,

	kit.name AS kname,
	spn.name AS tspnname,

	c.name AS cname,
	c.alpha_2 AS calpha,
	ts.createTimestamp AS crtime
	FROM teamSeason AS ts
	INNER JOIN teams AS t
	ON ts.teamID=t.ID
	INNER JOIN eventSeason AS es
	ON es.ID=ts.eventSeasonID
	INNER JOIN events AS e
	ON e.ID = es.eventsID
	INNER JOIN person AS man
	ON man.ID=ts.managerID
	INNER JOIN person AS cap
	ON cap.ID=ts.captainID

	INNER JOIN brands AS kit 
	ON ts.kitID=kit.ID

	INNER JOIN brands AS spn
	ON ts.sponsorID=spn.ID
	
	INNER JOIN countries AS c
	ON c.id=e.countryID
	ORDER BY crtime DESC 
	LIMIT 5
	";

	$result = mysql_query($sql);

	?>

	<table class="bordered">
	<thead>
	<tr>
	<th>ID</th>
	<th>Team</th>
	<th>Event</th>
	<th>Manager</th>
	<th>Captain</th>
	<th>Kit</th>
	<th>Sponsor</th>
	<th>Country</th>
	<th>Date Created</th>
	</tr>
	</thead>

	<?php
	while($row=mysql_fetch_assoc($result)){
		// print_r($row);
		?>

		<tr>
		<td><?php echo $row['ID'] ?></td>
		<td><a href="index.php?page=players&esid=<?php echo $row['esid'] ?>"><?php echo $row['tname'] ?></a></td>
		<td><a href="index.php?page=events&view=detail&sid=<?php echo $sportsID;?>&esid=<?php echo $row['ID'] ?>"><?php echo "(" . strtoupper($row['calpha']) . ") " . date("Y",strtotime($row['sdate']))."-".date("y",strtotime($row['edate']))." ".$row['spnname']." ". $row['ename'] ?></a></td>
		<td><?php echo $row['manfname'] ." ". $row['manlname'] ?></td>
		<td><?php echo $row['capfname'] ." ". $row['caplname'] ?></td>
		<td><?php echo $row['kname'] ?></td>
		<td><?php echo $row['tspnname'] ?></td>
		<td><?php echo $row['cname'] ?></td>
		<td><?php echo $row['crtime'] ?></td>
		</tr>
		<?php
	}
	echo "</table>";
}

if((isset($_GET['view']) && $_GET['view']=="list") || isset($_GET['teamslist']) && $_GET['teamslist']=="1"){
	?>
	<div id="teams_list" data-esID="<?= $esID ?>"></div>
	<?php
}

// start display teamSeason view=detail
if(($_GET['page']=="teams") && (isset($_GET['view']) && $_GET['view']=="detail")){

	// sql query to display selected teamSeason

	$sql=
	"SELECT
	ts.ID AS ID,
	t.ID AS tid,
	ts.ID AS esid,
	t.name AS tname,
	e.name AS ename,
	es.startDate AS sdate,
	es.endDate AS edate,
	man.firstName AS manfname,
	man.lastName AS manlname,
	cap.firstName AS capfname,
	cap.lastName AS caplname,

	kit.name AS kname,
	spn.name AS tspnname,

	c.name AS cname,
	c.alpha_2 AS calpha,
	ts.createTimestamp AS crtime
	FROM teamSeason AS ts
	INNER JOIN teams AS t
	ON ts.teamID=t.ID
	INNER JOIN eventSeason AS es
	ON es.ID=ts.eventSeasonID
	INNER JOIN events AS e
	ON e.ID = es.eventsID
	INNER JOIN person AS man
	ON man.ID=ts.managerID
	INNER JOIN person AS cap
	ON cap.ID=ts.captainID

	INNER JOIN brands AS kit 
	ON ts.kitID=kit.ID

	INNER JOIN brands AS spn
	ON ts.sponsorID=spn.ID
	
	INNER JOIN countries AS c
	ON c.id=e.countryID
	WHERE ts.ID = $teamSeasonID";

	$result=mysql_query($sql);
	$row=mysql_fetch_row($result);

	// print_r($row); // debug
	// display team detail heading
	echo "<h1>".team_season_heading($teamSeasonID)."</h1> ";

	echo "<h2>Fixture</h2>";

	include "players.php";
}

?>

<script>

</script>