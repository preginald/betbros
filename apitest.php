<?php 
require_once 'core/init.php'; 
require_once 'includes/head.php'; 


// jSON URL which should be requested
$json_url = 'http://football-api.com/api/?Action=fixtures&APIKey=6999cef2-0972-84d9-68a7ee628547&comp_id=1204&from_date=15.08.2014&to_date=30.08.2015&OutputType=JSON';

// jSON String for request
$json_string = '';

// Initializing curl
$ch = curl_init( $json_url );

// Configuring curl options
$options = array(
CURLOPT_RETURNTRANSFER => true,
CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
CURLOPT_POSTFIELDS => $json_string
);

// Setting curl options
curl_setopt_array( $ch, $options );

// Getting results
$result = curl_exec($ch); // Getting jSON result string
$result = json_decode($result);
$matches = $result->matches;

echo "<pre>";
// print_r($matches);
echo "</pre>";

foreach ($matches as $key => $value) {
	$match_id = $value->match_id;
	$match_static_id = $value->match_static_id;
	$match_comp_id = $value->match_comp_id;
	$match_formatted_date = $value->match_formatted_date;
	$match_status = $value->match_status;
	$match_time = $value->match_time;
	$match_localteam_id = $value->match_localteam_id;
	$match_localteam_name = $value->match_localteam_name;
	$match_localteam_score = $value->match_localteam_score;
	$match_visitorteam_id = $value->match_visitorteam_id;
	$match_visitorteam_name = $value->match_visitorteam_name;
	$match_visitorteam_score = $value->match_visitorteam_score;
	$match_ht_score = $value->match_ht_score;
	$match_ft_score = $value->match_ft_score;
	$match_et_score = $value->match_et_score;
	$match_events[] = $value->match_events;

	$fsID = ($match_status == "FT") ? 4 : 1;

	if ($fsID == 4) {
		$ht = score_api($match_ht_score);
		$ft = score_api($match_ft_score);
		$htscoreht = $ht[0];
		$atscoreht = $ht[1];
		$htscore = $ft[0];
		$atscore = $ft[1];
	}

	$fixture = fixture_exist_check($match_id);

	if ($fixture == false) {
		//echo "Match ID {$match_id} does not exist in fixture table ";

		// prepare api data to be added to fixture table
		$date = convert_date($match_formatted_date);
		$datetime = api_to_timestamp($date,$match_time);
		$esID = get_esID_api($match_comp_id,$date);
		$htID = get_tsID_api($match_localteam_id,$esID);
		$atID = get_tsID_api($match_visitorteam_id,$esID);
		$userID = 6;
		
		// insert fixture
		$fixID = insert_fixture_api($match_id,$datetime,$esID,$htID,$atID,$fsID,$userID);

		// insert fixture scores if final
		if (is_numeric($fixID) && $fsID == 4) {
			$fix_score_ID = insert_score_api($fixID,$htscore,$atscore,$htscoreht,$atscoreht,$userID);
		}

	} elseif ($fixture['fixtureStatusID'] == 1 && $fsID == 4) {
		$fixID = $fixture['ID'];
		// update fixture status to final and insert scores
		if (update_fixture_api($fixID,$fsID,$userID)) {
			insert_score_api($fixID,$htscore,$atscore,$htscoreht,$atscoreht,$userID);
		}
	} else {
		//echo "Match ID {$match_id} does exist in fixture table";
	}

	//echo "<br/>";
}


function score_api($data){
	$search = array("[","]");
	$data = str_replace($search, "", $data);
	return explode("-", $data);
}

function fixture_exist_check($ID){
	$s = "SELECT * FROM `fixtures` WHERE `api_match_id` = $ID LIMIT 1";
	$r = mysql_query($s);
	if (mysql_num_rows($r)) {
		return mysql_fetch_assoc($r);
	} else {
		return false;
	}
}

function convert_date($date){
   $date = explode(".", $date);
   $date = $date[2]."-".$date[1]."-".$date[0];
   return $date;
}

function api_to_timestamp($date,$time){
	$pieces = array($date,$time);
	return implode(" ", $pieces);
}

function get_esID_api($match_comp_id,$date){
	$s = "SELECT es.ID FROM eventSeason es
			JOIN `events` e 
			ON e.ID = es.eventsID
			WHERE e.api_id = $match_comp_id
			AND es.startDate <= '$date' 
			AND es.endDate >= '$date'";
	$r = mysql_query($s);

	if (mysql_num_rows($r)) {
		return mysql_result($r, 0);
	}
}

function get_tsID_api($id,$esID){
	$s = "SELECT ts.ID from teamSeason ts
		JOIN teams t
		ON ts.teamID = t.ID
		WHERE t.api_id = $id
		AND ts.eventSeasonID = $esID";
	$r = mysql_query($s);
	if (mysql_num_rows($r)) {
		return mysql_result($r, 0);
	}
}

function insert_fixture_api($match_id,$datetime,$esID,$htID,$atID,$fsID,$userID){
	$s = "INSERT INTO `fixtures`(`api_match_id`, `datetime`, `eventSeasonID`, `homeTeamID`, `awayTeamID`, `fixtureStatusID`, `createUserID`) 
		VALUES ($match_id,'$datetime',$esID,$htID,$atID,$fsID,$userID)";
	$r = mysql_query($s);
	if ($r) {
		return mysql_insert_id();
	} else {
		return "error";
	}
}

function insert_score_api($fixID,$htscore,$atscore,$htscoreht,$atscoreht,$userID){
	$s = "INSERT INTO `fixtureResults`(`fixtureID`, `htscore`, `atscore`, `htscoreht`, `atscoreht`, `createUserID`) 
		VALUES ($fixID,$htscore,$atscore,$htscoreht,$atscoreht,$userID)";
	$r = mysql_query($s);
	if ($r) {
		return mysql_insert_id();
	} else {
		return "error";
	}
}

function update_fixture_api($fixID,$fsID,$userID){
	$date = date('Y-m-d H:i:s');
	$s = "UPDATE `fixtures` SET `fixtureStatusID`=$fsID,`modifyUserID`=$userID,`modifyTimestamp`='$date' WHERE `ID` = $fixID";
	$r = mysql_query($s);
	if ($r) {
		return true;
	} else {
		return false;
	}
}