<?php include 'core/init.php';

$option = $_GET['option'];

$cID = $_GET['cID'];
$eID = $_GET['eID'];
$e = $_GET['e'];
$esID = $_GET['esID'];
$tsID = $_GET['tsID'];
$tID = $_GET['tID'];
$t = $_GET['t'];
$sname = $_GET['sname'];
$lname = $_GET['lname'];

$startDate = $_GET['eventStartDate'];
$endDate = $_GET['eventEndDate'];

function event_by_country_dropDown($eventID,$cID,$sportsID){

	$dropDown = mysql_query(
		"SELECT 
		e.ID AS ID,
		e.name AS name,
		UPPER (c.alpha_2) AS c

		FROM events e	

		INNER JOIN countries AS c
		ON c.id=e.countryID

		WHERE `sportID`=$sportsID AND e.countryID = $cID 

		ORDER BY c.alpha_2, e.name");
	echo '<option value="">Select event</option>';
	while ($record = mysql_fetch_array($dropDown)) {
		echo '<option value="' . $record['ID'] . '" '.($eventID == $record['ID'] ? 'selected' : '').'>' . $record['name'] . '</option>';
	}
	echo '<option value="new">New Event</option>';
}

function event_by_season_dropDown($eID, $sID, $esID){

	$dropDown = mysql_query("SELECT 
			es.ID AS ID, 
			es.eventsID AS eid,
			e.name AS ename, 
			spn.name AS spnname, 
			es.startDate AS sdate, 
			es.endDate AS edate, 
			c.name AS cname, 
			c.alpha_2 AS calpha, 
			s.ID AS sid
			FROM eventSeason AS es
			INNER JOIN events AS e
			ON es.eventsID=e.ID
			INNER JOIN brands AS spn
			ON es.sponsorID=spn.ID
			INNER JOIN countries AS c
			ON e.countryID=c.id
			INNER JOIN sports AS s
			ON e.sportID=s.ID WHERE s.ID=$sID AND e.ID = $eID
			ORDER BY es.startDate DESC
			");
	echo '<option value="">Select Season</option>';
	while ($record = mysql_fetch_array($dropDown)) {
		$year = (date("Y",strtotime($record['sdate'])) == date("Y",strtotime($record['edate']))) ? date("Y",strtotime($record['sdate'])) : date("Y",strtotime($record['sdate']))."-".date("y",strtotime($record['edate']));
		echo '<option value="' . $record['ID'] . '" '.($esID == $record['ID'] ? 'selected' : '').'>' . $year . '</option>';
	}
	echo '<option value="new">Add New Season</option>';
}

if ($option == "event") {
	event_by_country_dropDown($eventID,$cID,2);
}

if ($option == "eventSeason") {
	event_by_season_dropDown($eID, 2, $esID);
}

if ($option == "view_e") {
	$s = "SELECT * FROM `events` WHERE sportID = 2 AND ID = $eID LIMIT 1";
	$r = mysql_query($s);
	$row = mysql_fetch_assoc($r);
	echo json_encode($row);
}


if ($option == "view_es") {
	$s = "SELECT es.ID, e.name, es.startDate, es.endDate FROM `eventSeason` es
		INNER JOIN events e	ON es.eventsID = e.ID WHERE es.ID = $esID LIMIT 1";
	$r = mysql_query($s);
	$row = mysql_fetch_assoc($r);
	echo json_encode($row);
}

if ($option == "add_e"){
	?>
	<form role="form">
	  <div class="form-group">
	    <label for="eventName">Event Name</label>
	    <input type="text" class="form-control" id="eventName" placeholder="Enter Event">
	  </div>
	  <button type="button" class="btn btn-default" id="addevent">Add</button>
	</form>
	<div >
		<ul class="list-inline" id="view_e_list">
		</ul>
	</div>
	<?php
}

if ($option == "add_es"){
	?>
	<div class="panel panel-default">
	  <div class="panel-body">
		<form role="form">
		  <div class="form-group">
		    <label for="eventStartDate">Event Start</label>
		    <input type="date" class="form-control" id="eventStartDate">
		  </div>
		  <div class="form-group">
		    <label for="eventEndDate">Event End</label>
		    <input type="date" class="form-control" id="eventEndDate">
		  </div>
		  <button type="button" class="btn btn-default" id="addeventSeason">Add</button>
		</form>
	  </div>
	</div>

	<?php
}

if ($option == "eventNameLookup") {
	//$s = "SELECT name FROM events WHERE countryID = $cID AND name LIKE %".$e."%";
	$s = "SELECT `name` FROM `events` WHERE countryID = $cID AND `name` LIKE  '%".$e."%'";
	$r = mysql_query($s);
	$rows = array();
	while ($row = mysql_fetch_assoc($r)) {
		$rows[] = $row;
	}
	echo json_encode($rows);
}

if ($option == "addEventSQL") {
	$json = array('status' => 'ERROR', 'ID' => '0');
	$s = "INSERT INTO `events`(`name`, `countryID`, `sportID`, `verified`, `createUserID`) 
		VALUES ('$e',$cID,2,0,$session_user_id)";
	$r = mysql_query($s);
	if ($r) {
		$json['status'] = "OK";
		$json['ID'] = mysql_insert_id();
		$json['e'] = $e;
	} else {
		$json['sql'] = $s;
	}
	echo json_encode($json);
}

if ($option == "addEventSeasonSQL") {
	$json = array('status' => 'ERROR', 'ID' => '0');
	$s = "INSERT INTO `eventSeason`(`eventsID`,`startDate`,`endDate`,`sponsorID`,`verified`,createUserID)
		VALUES ($eID,'$startDate','$endDate',8,0,$session_user_id)";
	$r = mysql_query($s);
	if ($r) {
		$json['status'] = "OK";
		$json['ID'] = mysql_insert_id();
		$json['e'] = $e;
	} else {
		$json['sql'] = $s;
	}
	echo json_encode($json);	
}

if ($option == "addTeamSeasonSQL") {
	$json = array('status' => 'ERROR', 'ID' => '0');
	if ($tID == "") { $tID = checkInsertTeam($t,$sname,$lname,$cID,2,$session_user_id); }
	$tsID = tsExistCheckSQL($tID,$esID);
	if ($tsID){
		$json['status'] = "EXISTS";
		$json['tsID'] = $tsID;
	} else {
		$s = "INSERT INTO `teamSeason`(`teamID`,`eventSeasonID`,`managerID`,`captainID`,`kitID`,`sponsorID`,`verified`,`createUserID`)
			VALUES ($tID,$esID,250,250,8,8,0,$session_user_id)";
		$r = mysql_query($s);
		if ($r) {
			$json['status'] = "OK";
			$json['t'] = $t;
			$json['sname'] = $sname;
			$json['lname'] = $lname;
			$json['ID'] = mysql_insert_id();
		} else {
			$json['sql'] = $s;
		}
	}
	
	echo json_encode($json);
}

if ($option == "teamLookup") {
	$json = array('status' => 'ERROR');
	//$tID = getTeamID($tsID);
	$s = "SELECT `api_id`,`name`, `sname`, `lname`, `countryID` FROM `teams` WHERE `ID` = $tID LIMIT 0,1";
	$r = mysql_query($s);
	$row = mysql_fetch_assoc($r);

	//$json['sql'] = $s;
	$json['status'] = "OK";
	$json['tID'] = $tID;
	$json['name'] = $row['name'];
	$json['sname'] = $row['sname'];
	$json['lname'] = $row['lname'];
	$json['cID'] = $row['countryID'];

	//echo json_encode($json);
	?>
		<form role="form" id="teamUpdateForm">
		<div class="row">
		  	<div class="col-lg-2">
				<input type="text" name="name" class="form-control" placeholder="Team Name" value="<?= $row['name'] ?>">
			</div>
			<div class="col-lg-2">
				<input type="text" name="sname" class="form-control" placeholder="Short Name" value="<?= $row['sname'] ?>">
			</div>

			<div class="col-lg-2">
				<input type="text" name="lname" class="form-control" placeholder="Long Name" value="<?= $row['lname'] ?>">
			</div>

			<div class="col-lg-2">
				<input type="text" name="api_id" class="form-control" placeholder="API ID" value="<?= $row['api_id'] ?>">
			</div>

			<div class="col-lg-2">
				<select class="form-control"  name="cID" ><?php region_dropDown($row['countryID']) ; ?></select>
			</div>

			<div class="col-lg-2 pull-right">
				<ul class="list-inline">
				<li><button class="btn btn-default" id="btn-updateTeam" type="submit"><i class="fa fa-floppy-o"></i></button></li>
				<li><button class="btn btn-default" id="btn-updateTeamCancel" type="submit"><i class="fa fa-times"></i></button></li>			
			</div>
		</div>

		<input type="hidden" name="option" value="teamUpdateSQL">
		<input type="hidden" name="tID" id="tID" value="<?= $tID ?>">
	</form>
	<?php
}

if ($option == "deleteCopyForm") {
	$tID = sanitize($_GET['tID']);
	$tname = sanitize($_GET['tname']);
	?>
		<form role="form" id="deleteCopyForm">
		<div class="row">
<!-- 		  	<div class="col-lg-2">
				<h4><small><?= $tID ?> </small><?= $tname ?></h4>
			</div> -->
			<div class="col-lg-6">
				<div class="input-group">
					<span class="input-group-addon"><?= $tID ?> </small><?= $tname ?></span>
					<input type="text" name="search" class="form-control" placeholder="Search Duplicate" value="<?= $tname ?>">
					<span class="input-group-btn">
						<button class="btn btn-default dup-search" type="button"><i class="fa fa-search-plus"></i></button>
					</span>
				</div><!-- /input-group -->
			</div>

			<div class="col-lg-2 pull-right">
				<ul class="list-inline">
				<li><button class="btn btn-default" id="btn-updateTeamCancel" type="submit"><i class="fa fa-times"></i></button></li>			
			</div>
		</div>

		<input type="hidden" name="option" value="teamUpdateSQL">
		<input type="hidden" name="tID" id="tID" value="<?= $tID ?>">
	</form>
	<?php
}

if ($option == "deleteCopySearch") {
	$tID = sanitize($_GET['tID']);
	$tname = sanitize($_GET['tname']);
	$s = "SELECT `ID` FROM `teams` WHERE `ID` != $tID AND `name` LIKE '$tname'";
	$r = mysql_query($s);
	if (mysql_fetch_row($r)) {
		$dupID = mysql_result($r, 0);
		?>
		Duplicate ID <span class="dupID"><?= $dupID ?></span> found. Would you like to delete it?
		<button class="btn btn-default" id="btn-deleteDuplicates" type="submit"><i class="fa fa-check"></i></button>
		<button class="btn btn-default" id="btn-updateTeamCancel" type="submit"><i class="fa fa-times"></i></button><?php
	}
}

if ($option == "deleteCopyExec") {
	$tID = sanitize($_GET['tID']);
	$tname = sanitize($_GET['tname']);
	$dupID = sanitize($_GET['dupID']);
	print_r($_GET);
}

function getTeamID($tsID){
	$s = "SELECT teamID  FROM `teamSeason` WHERE `ID` = $tsID";
	$r = mysql_query($s);
	if (mysql_fetch_row($r)) {
		return mysql_result($r, 0);
	} else {
		return $s;
	}
}

if ($option == "teamUpdateSQL") {
	$json = array('status' => 'ERROR', 'ID' => '0');
	//name=Ch.+Odessa&sname=&lname=Chornomorets+Odesa&cID=233&option=teamUpdateSQL&tID=904
	$name = sanitize($_GET['name']);
	$sname = sanitize($_GET['sname']);
	$lname = sanitize($_GET['lname']);
	$cID = sanitize($_GET['cID']);
	$tID = sanitize($_GET['tID']);
	$api_id = sanitize($_GET['api_id']);

	$s = "UPDATE `teams` SET 
	`name` = '$name',
	`sname` = '$sname',
	`lname` = '$lname',
	`countryID` = '$cID',
	`modifyUserID` = '$session_user_id',
	`api_id` = $api_id,
	 `modifyTimestamp` = NOW() 
	 WHERE `ID` = $tID;";
	 $json['sql'] == $s;
	 $r = mysql_query($s);
	 if ($r) {
	 	$json['status'] == "OK";
	 } else {
	 	$json['sql'] == $s;
	 }

	 echo json_encode($json);
}

function checkInsertTeam($t,$sname,$lname,$cID,$sID,$session_user_id){
	// Before creating new team check if it exists.
	// if it exists then return the ID.
	// if it does not exist then create team. 
	$tID = tExistCheckSQL($t);
	if ($tID) {
		return $tID;
	} else {
		return insertTeamSQL($t,$sname,$lname,$cID,$sID,$session_user_id);
	}

}

function tExistCheckSQL($t){
	$s = "SELECT `ID` FROM `teams` WHERE `name` = '$t'";
	$r = mysql_query($s);
	if (mysql_num_rows($r)){
		return mysql_result($r,0);
	}
}

function insertTeamSQL($t,$sname,$lname,$cID,$sID,$session_user_id){
	$s = "INSERT INTO `teams`(`name`,`sname`,`lname`, `countryID`, `sportID`, `createUserID`) 
		VALUES ('$t','$sname','$lname',$cID,$sID,$session_user_id)";
	$r = mysql_query($s);
	if ($r) {
		return mysql_insert_id();
	} else {
		return '$s';
	}
}

function tsExistCheckSQL($tID,$esID){
	$s = "SELECT * FROM `teamSeason` WHERE `teamID` = $tID AND `eventSeasonID` = $esID";
	$r = mysql_query($s);
	if (mysql_num_rows($r)){
		return mysql_result($r,0);
	}
}