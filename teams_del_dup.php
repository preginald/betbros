<?php
require_once 'core/init.php';
//print_r($_GET);
/*
* Team Deduplicator v0.2
* Finds duplicate team names and removes them
* keeping only one unique team
* 
* 1. Get ID from teamSeason for duplicate teamID
* 		"SELECT ID FROM `teamSeason` WHERE `teamID` = $dupID" 
* 2. ----- Get duplicate tsIDs ------------- redundant step
* 		"SELECT `ID` FROM `teamSeason` WHERE `teamID` = 379 AND `eventSeasonID` = 98 AND `ID` != 410"
* 3. Replace duplicate homeTeamID with original $tsID for selected teamSeasonID
* 		"UPDATE `fixtures` SET `homeTeamID`= 410 WHERE `homeTeamID` = 545 AND `eventSeasonID` = 98"
* 4. Replace duplicate awayTeamID with original $tsID for selected teamSeasonID
* 		"UPDATE `fixtures` SET `awayTeamID`= 410 WHERE `awayTeamID` = 545 AND `eventSeasonID` = 98"
* 5. Delete duplicate teamSeason
* 		"DELETE FROM `yourpcma_betbros`.`teamSeason` WHERE `teamSeason`.`ID` = 545"
* 6. On success reload teams table.
*/

$option = $_GET['option'];
$tsID = $_GET['tsID'];

function get_tID_esID($tsID){
	$s = "SELECT teamID, eventSeasonID FROM `teamSeason` WHERE `ID` = $tsID ORDER BY `ID` DESC";
	$r = mysql_query($s);
	return mysql_fetch_assoc($r);
}

function dedup($tID, $esID,$tsID){
	$s = "SELECT `ID` FROM `teamSeason` WHERE `teamID` = $tID AND `eventSeasonID` = $esID AND `ID` != $tsID";
	$r = mysql_query($s);
	while ($row = mysql_fetch_assoc($r)) {
		$tsIDdup = $row['ID'];
		replace_htID($esID,$tsID,$tsIDdup);
		replace_atID($esID,$tsID,$tsIDdup);
		delete_dup($tsIDdup);
	}
}

function replace_htID($esID,$tsID,$tsIDdup){
	$s = "UPDATE `fixtures` SET `homeTeamID`= $tsID WHERE `homeTeamID` = $tsIDdup AND `eventSeasonID` = $esID";
	$r = mysql_query($s);
}

function replace_atID($esID,$tsID,$tsIDdup){
	$s = "UPDATE `fixtures` SET `awayTeamID`= $tsID WHERE `awayTeamID` = $tsIDdup AND `eventSeasonID` = $esID";
	$r = mysql_query($s);
}

function delete_dup($tsIDdup){
	$s = "DELETE FROM `teamSeason` WHERE `ID` = $tsIDdup";
	$r = mysql_query($s);
}

if ($option == "something") {
	$tID_esID = get_tID_esID($tsID);
	$tID = $tID_esID['teamID'];
	echo $esID = $tID_esID['eventSeasonID'];

	dedup($tID, $esID,$tsID);
}


// dedup with only tID and dupID (duplicate team ID)

function replace_dupID_teamSeason($tID, $dupID){
	$s = "UPDATE `teamSeason` SET `teamID`= $tID WHERE `teamID` = $dupID";
	$r = mysql_query($s);
	if ($r) {
		return true;
	}
}

function check_dup_exist($dupID){
	$s = "SELECT ID FROM `teamSeason` WHERE `teamID` = $dupID";
	$r = mysql_query($s);
	if (mysql_num_rows($r) == 0) {
		return true;
	}
}


function delete_dup_team($dupID){
	$s = "DELETE FROM `teams` WHERE `ID` = $dupID";
	$r = mysql_query($s);
	if ($r) {
		return true;
	}
}

if ($option == "deleteCopyExec"){
	$json = array('status' => 'ERROR');
	$tID = sanitize($_GET['tID']);
	$dupID = sanitize($_GET['dupID']);

	if (replace_dupID_teamSeason($tID, $dupID) && check_dup_exist($dupID)) {
		if (delete_dup_team($dupID)) {
			$json['status'] = "SUCCESS";
			$json['tID'] = $tID;
			$json['dupID'] = $dupID;
			echo json_encode($json);
		}
	}
}