<?php 
protect_page(); 
//print_r_pre($_POST);
// get values from url
$sportsID = mysql_escape_string($_GET['sid']); 
$eventSeasonID = mysql_escape_string($_GET['esid']);
$teamPlayerID = mysql_escape_string($_GET['tpid']);
$teamSeasonID = mysql_escape_string($_GET['tsid']);



if(isset($_POST['add_player'])){
	$teamSeasonID = $_POST['teamSeasonID'];
	$number = $_POST['number'];
	
	$positionCatID = $_POST['positionCatID'];
	$createTimestamp = date("Y-m-d H:i:s");
	
	if(!empty($_POST['lname'])){
		$fname=$_POST['fname'];
		$mname=$_POST['mname'];
		$lname=$_POST['lname'];
		$dob=$_POST['dob'];
		$cID=$_POST['cID'];
		$sex=$_POST['sex'];
		$height=$_POST['height'];
		$number=$_POST['number'];
		$positionsCat=$_POST['positionCatID'];
		$createTimestamp = date("Y-m-d H:i:s");

		$sql=
		"INSERT INTO `person`(
			`genderID`, 
		    `firstName`, 
		    `middleName`,
		    `lastName`, 
		    `dob`,
		    `countryID`, 
		    `height`,
		    `verified`,
		    `createUserID`, 
		    `createTimestamp`
		    ) VALUES(
		    '$sex',
		    '$fname',
		    '$mname',
		    '$lname',
		    '$dob',
		    '$cID',
		    '$height',
		    0,
		    '$session_user_id',
		    '$createTimestamp'
		    )";
		$result=mysql_query($sql);
		$pID=mysql_insert_id();
	} else{
		$pID = $_POST['personID'];	
	}
	// check if player already exists
	$sql_check_teamPlayer= "SELECT * FROM `teamPlayers` 
	WHERE `personID` = $pID AND `teamSeasonID` = $teamSeasonID";
	// echo $sql_check_teamPlayer; // debug
	$result=mysql_query($sql_check_teamPlayer);
	if (mysql_fetch_row($result)){

		echo "This player already exists in this team";

	} else {
		$sql_add_player = 
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
				'$number',
				'$pID',
				'$positionCatID',
				0,
				'$session_user_id',
				'$createTimestamp')";
		
		$result=mysql_query($sql_add_player);

		// end add player to database process
		// if successfully updated. 

		if($result){
			echo "successfully added player";
		} else {
			echo "ERROR";
		}
	}
}

// start update players to database process
// check if form button = submit 
if(isset($_POST['update_player'])){
	$teamSeasonID = $_POST['teamSeasonID'];
	$number = $_POST['number'];
	$personID = $_POST['personID'];
	$positionCatID = $_POST['positionCatID'];
	$modifyTimestamp = date("Y-m-d H:i:s");

	$sql_edit_player = 
	"UPDATE `teamPlayers` SET 
	`teamSeasonID`='$teamSeasonID',
	`number`='$number',
	`personID`='$personID',
	`positionCatID`='$positionCatID',
	`modifyUserID`='$session_user_id',
	`modifyTimestamp`='$modifyTimestamp' 
	WHERE ID = $teamPlayerID";

	// echo $sql_edit_player;
	$result=mysql_query($sql_edit_player);

	// end add player to database process
	// if successfully updated. 

	if($result){

	echo "<a href=\"index.php?page=players&view=detail&sid=$sportsID&tsid=$teamSeasonID&tpid=$teamPlayerID\">Successfully updated player, back to detail view.</a>";

	} else {
			echo "ERROR";
	}
}
// end update players to database process


if(isset($_GET['page']) && $_GET['page']=="teams" 
	|| isset($_GET['page']) && $_GET['page']=="players"
	&& isset($_GET['view']) && $_GET['view']=="list")  {

	echo "<h2>Squad " . '<a href="index.php?page=players&view=add&sid='. "$sportsID". '&tsid='. "$teamSeasonID" . '"><img alt="add" style="width: 24px; height: 24px;" title="add" src="images/add-record.png"></a></h2>';
	// start display players view=list
	$sql=
	"SELECT 
	tp.ID AS ID,
	tp.teamSeasonID AS tsid,
	t.name AS tname,
	es.startDate AS sdate,
	es.endDate AS edate,
	tp.number AS num,
	p.firstName AS pfname,
	p.lastName AS plname,
	pc.abbr AS pcabbr,
	tp.createTimestamp AS crtime

	FROM teamPlayers AS tp

	INNER JOIN teamSeason AS ts
	ON tp.teamSeasonID=ts.ID

	INNER JOIN teams AS t
	ON t.ID=ts.teamID

	INNER JOIN eventSeason AS es
	ON es.ID=ts.eventSeasonID

	INNER JOIN events AS e
	ON e.ID=es.eventsID

	INNER JOIN person AS p
	ON p.ID=tp.personID

	INNER JOIN positionsCat AS pc
	ON pc.ID=tp.positionCatID

	WHERE tp.teamSeasonID = $teamSeasonID

	ORDER BY num ASC 
	";

	$result = mysql_query($sql);

	?>

	<table class="bordered">
	<thead>
	<tr>
	<th>ID</th>
	<th>No.</th>
	<th>Player</th>
	<th>Pos.</th>
	<th>Date Created</th>
	<th>Update</th>
	</tr>
	</thead>

	<?php
	while($row=mysql_fetch_assoc($result)){
		// print_r($row);
		?>

				  <tr>
				  <td><?php echo $row['ID'] ?></td>
				  <td><?php echo $row['num'] ?></td>
				  <td><a href="index.php?page=players&view=detail&tpid=<?php echo $row['ID'] ?>"><?php echo $row['pfname'] ." ". $row['plname'] ?></a></td>
				  <td><?php echo $row['pcabbr'] ?></td>
				  <td><?php echo $row['crtime'] ?></td>
				  <td><a href="index.php?page=players&view=edit&sid=<?php echo $sportsID ?>&tsid=<?php echo $teamSeasonID ?>&tpid=<?php echo $row['ID'] ?>">Update</a></td>
				  </tr>
		<?php 
				  }

				  echo "</table>";
	// end display players view=list
}

// start display players view=add
// start input form
if((isset($_GET['page']) && $_GET['page']=="players") && (isset($_GET['view']) && $_GET['view']=="add")
	||
	(isset($_GET['page']) && $_GET['page']=="teams") && (isset($_GET['addplayer']) && $_GET['addplayer']=="1")
	){ ?>
	<h1>Add new player</h1>

	<form name="add-teamSeasonPlayer" method="post" action="<?=$PHP_SELF?>" >
	<div>
	<span class="small">Team Name</span>
	<select name="teamSeasonID"><?php teams_season_dropDown() ?></select>
	</div>
	<br/>

	<div>
	<h3>Step 1: Select person</h3>
	<span class="small">Person</span>
	<select name="personID"><?php person_dropDown() ?></select><br/>
	</div>
	<br/>

	<div>
	<h3>Step 2: Or add new person:</h3>
	<select name="sex"><?php person_gender_dropdown() ?></select>
	<input type="text" name="fname" placeholder="First Name" >
	<input type="text" name="mname" placeholder="Middle Name">
	<input type="text" name="lname" placeholder="Last Name" ><br/>
	<input type="date" name="dob" placeholder="Date of birth">
	<input type="number" name="height" placeholder="Height">
	<select name="cID" required><?php country_dropDown() ?></select><br/>
	</div>
	<br/>

	<div>
	<h3>Step 3: Add player's team info:</h3>
	<input type="number" name="number" placeholder="Team Player Number" required>
	<select name="positionCatID" required><?php position_cat_dropDown() ?></select>
	</div>
	<input type="submit" name="add_player" value="Add">
	</form>

	<?php 
	// end input form
	// end display players view=add

	// start add players to database process
	// check if form button = submit 
}
// end add event process

// start hide/show last 5 added players
if(isset($_GET['history'])){
	echo "<hr>";
	echo "<h2>Last 5 players added</h2>";

	$sql=
	"SELECT 
	tp.ID AS ID,
	t.name AS tname,
	es.startDate AS sdate,
	es.endDate AS edate,
	tp.number AS num,
	p.ID AS pid,
	p.firstName AS pfname,
	p.lastName AS plname,
	pc.abbr AS pcabbr,
	tp.createTimestamp AS crtime
	FROM teamPlayers AS tp

	INNER JOIN teamSeason AS ts
	ON tp.teamSeasonID=ts.ID

	INNER JOIN teams AS t
	ON t.ID=ts.teamID

	INNER JOIN eventSeason AS es
	ON es.ID=ts.eventSeasonID

	INNER JOIN events AS e
	ON e.ID=es.eventsID

	INNER JOIN person AS p
	ON p.ID=tp.personID

	INNER JOIN positionsCat AS pc
	ON pc.ID=tp.positionCatID

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
	<th>No.</th>
	<th>Player</th>
	<th>Pos.</th>
	<th>Date Created</th>
	</tr>
	</thead>

	<?php
	while($row=mysql_fetch_assoc($result)){
		// print_r($row);
		?>

	  <tr>
	  <td><?php echo $row['ID'] ?></td>
	  <td><a href="index.php?page=players&esid=<?php echo $row['esid'] ?>"><?php echo date("Y",strtotime($row['sdate']))."-".date("y",strtotime($row['edate']))." ". $row['tname'] ?></a></td>
	  <td><?php echo $row['num'] ?></a></td>
	  <td><?php echo $row['pfname'] ." ". $row['plname'] ?></td>
	  <td><?php echo $row['pcabbr'] ?></td>
	  <td><?php echo $row['crtime'] ?></td>
	  </tr>
		<?php 
	}

	echo "</table>";
}
// end display player view=list

// end hide/show last 5 added players


// start display player view=edit
// start input form
if(isset($_GET['view']) && $_GET['view']=="edit"){

	$sql=
	"SELECT 
	tp.ID AS ID,
	tp.teamSeasonID AS tsid,
	t.name AS tname,
	es.startDate AS sdate,
	es.endDate AS edate,
	tp.number AS num,
	p.ID AS pid,
	p.firstName AS pfname,
	p.lastName AS plname,
	pc.ID AS pcid,
	pc.abbr AS pcabbr,
	tp.createTimestamp AS crtime,
	s.ID AS sid

	FROM teamPlayers AS tp

	INNER JOIN teamSeason AS ts
	ON tp.teamSeasonID=ts.ID

	INNER JOIN teams AS t
	ON t.ID=ts.teamID

	INNER JOIN eventSeason AS es
	ON es.ID=ts.eventSeasonID

	INNER JOIN events AS e
	ON e.ID=es.eventsID

	INNER JOIN person AS p
	ON p.ID=tp.personID

	INNER JOIN positionsCat AS pc
	ON pc.ID=tp.positionCatID

	INNER JOIN sports AS s
	ON s.ID=e.sportID	

	WHERE tp.ID = $teamPlayerID";

	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);

	// print_r($row); // debug

	$personID = $row['pid'];
	$positionCatID = $row['pcid'];
	$teamSeasonID = $row['tsid'];
	$sportsID = $row['sid'];

	?>
	<h1>Edit player</h1>

	<form name="edit-teamSeasonPlayer" method="post" action="<?=$PHP_SELF?>" >

	<div>
	<span class="small">Team Name</span>
	<select name="teamSeasonID"><?php teams_season_dropDown() ?></select>
	</div>

	<div>
	<span class="small">Number</span>
	<input type="number" name="number" placeholder="number" value="<?php echo $row['num'] ?>">
	</div>

	<div>
	<span class="small">Person</span>
	<select name="personID"><?php person_dropDown() ?></select>
	</div>

	<div>
	<span class="small">Position</span>
	<select name="positionCatID"><?php position_cat_dropDown() ?></select>
	</div>
	<input type="submit" name="update_player" value="Update">
	</form>

	<?php 
}
// end input form
// end display player view=edit

// start display players view=detail
if($_GET['page']=="players" && $_GET['view']=="detail"){ 
	$sql=
	"SELECT 
	tp.ID AS ID,
	tp.teamSeasonID AS tsid,
	t.name AS tname,
	es.startDate AS sdate,
	es.endDate AS edate,
	tp.number AS num,
	p.firstName AS pfname,
	p.lastName AS plname,
	pc.abbr AS pcabbr,
	tp.createTimestamp AS crtime
	FROM teamPlayers AS tp

	INNER JOIN teamSeason AS ts
	ON tp.teamSeasonID=ts.ID

	INNER JOIN teams AS t
	ON t.ID=ts.teamID

	INNER JOIN eventSeason AS es
	ON es.ID=ts.eventSeasonID

	INNER JOIN events AS e
	ON e.ID=es.eventsID

	INNER JOIN person AS p
	ON p.ID=tp.personID

	INNER JOIN positionsCat AS pc
	ON pc.ID=tp.positionCatID

	WHERE tp.ID = $teamPlayerID 
	";
	$result = mysql_query($sql);

	?>

	<table class="bordered">
	<thead>
	<tr>
	<th>No.</th>
	<th>Player</th>
	<th>Pos.</th>
	<th>Date Created</th>
	<th>Update</th>
	</tr>
	</thead>

	<?php
	while($row=mysql_fetch_assoc($result)){
		// print_r($row);
		?>

		<tr>	
		<td><?php echo $row['num'] ?></a></td>
		<td><?php echo $row['pfname'] ." ". $row['plname'] ?></td>
		<td><?php echo $row['pcabbr'] ?></td>
		<td><?php echo $row['crtime'] ?></td>
		<td><a href="index.php?page=players&view=edit&sid=<?php echo $sportsID ?>&tsid=<?php echo $teamSeasonID ?>&tpid=<?php echo $row['ID'] ?>">Update</a></td>
		</tr>
		<?php 
	}
	echo "</table>";
}
// end display players view=detail
?>