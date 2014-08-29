<?php

function email($to, $subject, $body) {

	mail($to, $subject, $body, 'From: hello@phpacademy.org');

}



function logged_in_redirect() {

	if (logged_in() === true) {

		header('Location: index.php');

		exit();

	}

}


function protect_page() {

	if (logged_in() === false) {

		header('Location: protected.php');

		exit();

	}

}



function admin_protect() {

	global $user_data;

	if (has_access($user_data['user_id'], 1) === false) {

		header('Location: index.php');

		exit();

	}

}



function array_sanitize(&$item) {

	$item = htmlentities(strip_tags(mysql_real_escape_string($item)));

}



function sanitize($data) {

	return htmlentities(strip_tags(mysql_real_escape_string($data)));

}



function output_errors($errors) {

	return '<ul><li>' . implode('</li><li>', $errors) . '</li></ul>';

}

function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

function curPageURLshort() {
  $pageURL = $_SERVER["REQUEST_URI"];
 return $pageURL;
}

function print_r_pre($row){
	echo "<pre>";
	print_r($row);
	echo "</pre>";
}

function truncate($string, $length, $stopanywhere=false) {
    //truncates a string to a certain char length, stopping on a word if not specified otherwise.
    if (strlen($string) > $length) {
        //limit hit!
        $string = substr($string,0,($length -3));
        if ($stopanywhere) {
            //stop anywhere
            $string .= '...';
        } else{
            //stop on a word.
            $string = substr($string,0,strrpos($string,' ')).'...';
        }
    }
    return $string;
}

function user_timezone_offset(){
	global $user_data;
	$zone_id = user_timezone($user_data['user_id']);

	$s = "SELECT tz.gmt_offset
		FROM `timezone` tz JOIN `zone` z
		ON tz.zone_id=z.zone_id
		WHERE tz.time_start < UNIX_TIMESTAMP(UTC_TIMESTAMP()) AND z.zone_id=$zone_id
		ORDER BY tz.time_start DESC LIMIT 1";
	$r = mysql_query($s);
	return mysql_result($r, 0);
}

function read_datetime($datetime){
	$time = strtotime($datetime);
	$time = $time+user_timezone_offset();
	return date("D d-M g:i A", $time);	
}


function write_datetime($datetime){
	$time = strtotime($datetime);
	$time = $time-user_timezone_offset();
	return date("Y-m-d H:i:s", $time);	
}

function high_low_datetime($datetime){
	$array = array();
	$time = strtotime($datetime);
	$time = $time-user_timezone_offset();
	$low = $time-172800;
	$high = $time+172800;
	$array['low'] = date("Y-m-d H:i:s", $low);
	$array['high'] = date("Y-m-d H:i:s", $high);
	return $array;
}

// dropdown functions

function bet_lines_dropdown(){

	$dropDown = mysql_query("SELECT * FROM betLines");

	while ($record = mysql_fetch_array($dropDown)) {

		echo '<option value="' . $record['ID'] . '">' . $record['name'] . '</option>';

	}

}

function bk_dropdown($bkID=0){
        echo '<option value="">Select bookie</option>';
	$dropDown = mysql_query("SELECT * FROM bookies ORDER BY name ASC");
	while ($record = mysql_fetch_assoc($dropDown)) {
		//print_r($record);
		echo '<option value="' . $record['ID'] . '">' . $record['name'] . '</option>';
	}
}

function bookie_dropdown($bkID=0,$user_id){
	$dropDown = mysql_query("SELECT bk.ID ID, ubk.ID ubkID, bk.name name 
							FROM `bookies` bk 
							INNER JOIN userBookies ubk
							ON ubk.bookieID = bk.ID
							WHERE ubk.userID = $user_id AND ubk.active = 1
							ORDER BY bk.name ASC");
	echo '<option value="">Select bookie</option>';
	while ($record = mysql_fetch_assoc($dropDown)) {
		//print_r($record);
		echo '<option value="' . $record['ubkID'] . '" '.($bkID == $record['ubkID'] ? 'selected' : '').'>' . $record['name'] . '</option>';
	}
}

//	INNER JOIN userBookies AS ubk
//	ON bt.bookieID=ubk.ID

//	INNER JOIN bookies AS bk
//	ON ubk.bookieID=bk.ID

function bet_type_dropdown(){

	$dropDown = mysql_query("SELECT * FROM betType");

	while ($record = mysql_fetch_array($dropDown)) {

		echo '<option value="' . $record['ID'] . '">' . $record['name'] . '</option>';

	}

}



function sports_dropdown(){

	$dropDown = mysql_query("SELECT * FROM sports");
	echo '<option value=0>Select sport</option>';
	while ($record = mysql_fetch_array($dropDown)) {

		echo '<option value="' . $record['ID'] . '">' . $record['name'] . '</option>';

	}

}

function person_gender_dropdown(){
	global $genderID;
	$dropDown = mysql_query("SELECT * FROM personGender");
	echo '<option value=3>Select gender</option>';
	while ($record = mysql_fetch_array($dropDown)) {

		echo '<option value="' . $record['ID'] . '" '.($genderID == $record['ID'] ? 'selected' : '').'>' . $record['name'] . '</option>';

	}

}



function horse_dropdown($hID){
	$dropDown = mysql_query(
		"SELECT 
		h.ID ID,
		h.name name,
		YEAR(h.birth) year,
		LEFT(g.name, 1) s,
		UPPER(c.alpha_2) c 
		FROM horses h
		INNER JOIN horseGender g
		ON h.genderID=g.ID
		INNER JOIN countries c
		ON h.CountryID=c.ID
		ORDER BY h.name");
	echo '<option value="">Select horse</option>';
	while ($record = mysql_fetch_assoc($dropDown)) {

		echo '<option value="' . $record['ID'] . '" '.($hID == $record['ID'] ? 'selected' : '').'>' 
		. $record['name'] . " ("
		.  $record['c'] . ") "
		. $record['s']  . " "
		. $record['year'] . " "
		. '</option>';
	}
}

function horse_sex_dropdown(){
	global $genderID;
	$dropDown = mysql_query("SELECT * FROM horseGender");
	echo '<option value=0>Select gender</option>';
	while ($record = mysql_fetch_array($dropDown)) {

		echo '<option value="' . $record['ID'] . '" '.($genderID == $record['ID'] ? 'selected' : '').'>' . $record['name'] . '</option>';

	}

}

function bet_status($ID){
	$dropDown = mysql_query("SELECT * FROM betStatus");

	while ($record = mysql_fetch_array($dropDown)) {

		echo '<option value="' . $record['ID'] . '" '.($ID == $record['ID'] ? 'selected' : '').'>' . $record['name'] . '</option>';
	}

}

function horse_race_status($hrsID=0){
	$dropDown = mysql_query("SELECT * FROM horseStatus");
	echo '<option value="">Select status</option>';
	echo '<option value="1">To Run</option>';
	echo '<option value="2">Non-runner</option>';
	echo '<option value="15">Winner</option>';
	echo '<option value="16">Placed</option>';
	echo '<option value="14">Finished Race</option>';
	echo '<option value="">----------------</option>';
	while ($record = mysql_fetch_array($dropDown)) {

		echo '<option value="' . $record['ID'] . '" '.($hrsID == $record['ID'] ? 'selected' : '').'>' . $record['name'] . '</option>';

	}

}

function horse_race_status_2($hrsID=0){
	$dropDown = array(1 => "To Run",15 => "Winner",16 => "Placed",14 => "Finished Race");
	echo '<option value="">Select status</option>';
	foreach ($dropdown as $key => $value) {

		echo '<option value="' . $key . '" '.($hrsID == $key ? 'selected' : '').'>' . $value . '</option>';

	}

}

function timezone_dropDown($ID){
	$dropDown = mysql_query("SELECT zone_id ID, zone_name zn FROM zone z ORDER BY zone_name ASC"); 
	echo '<option value="">Select Timezone</option>';
	while ($record = mysql_fetch_array($dropDown)) {
		echo '<option value="' . $record['ID'] . '" '.($ID == $record['ID'] ? 'selected' : '').'>' . $record['zn'] . '</option>';
	}
}


function country_dropDown(){
	global $countryID;
	$dropDown = mysql_query("SELECT * FROM countries WHERE id != 250 ORDER BY name"); 
	echo '<option value="">Select Region</option>';
	echo '<option value="250">NA</option>';
	while ($record = mysql_fetch_array($dropDown)) {
		echo '<option value="' . $record['id'] . '" '.($countryID == $record['0'] ? 'selected' : '').'>' . $record['name'] . '</option>';
	}
}

function region_dropDown($ID=0){
	$dropDown = mysql_query("SELECT * FROM region WHERE id != 250 ORDER BY name"); 
	echo '<option value="">Select Region</option>';
	while ($record = mysql_fetch_array($dropDown)) {
		echo '<option value="' . $record['id'] . '" '.($ID == $record['0'] ? 'selected' : '').'>' . $record['name'] . '</option>';
	}
}

function country_football_dropdown(){
	$dropDown = mysql_query("SELECT DISTINCT

			c.name AS cname,
            c.id AS cid
            
			FROM eventSeason AS es
			INNER JOIN events AS e
			ON es.eventsID=e.ID
			INNER JOIN countries AS c
			ON e.countryID=c.id
			INNER JOIN sports AS s
			ON e.sportID=s.ID
            
            WHERE s.ID=2 AND c.id != 250
            
            ORDER BY c.name"); 
	echo '<option value="">Select Region</option>';
	//echo '<option value="250">NA</option>';
	while ($record = mysql_fetch_array($dropDown)) {
		echo '<option value="' . $record['cid'] . '" '.($countryID == $record['0'] ? 'selected' : '').'>' . $record['cname'] . '</option>';
	}	

}

function jockey_dropdown($jID){
	$dropDown = mysql_query(
		"SELECT
		j.ID ID, 
		p.firstName jfname,
		p.lastName jlname,
		UPPER(c.alpha_2) c

		FROM jockeys j

		INNER JOIN person p
		ON j.personID=p.ID
		INNER JOIN countries c
		ON p.countryID=c.ID

		ORDER BY jlname"
		);
	echo '<option value=0>Select jockey</option>';
	while ($record = mysql_fetch_array($dropDown)) {
		echo '<option value="' . $record['ID'] . '" '.($jID == $record['ID'] ? 'selected' : '').'>' 
		. $record['jlname'] .", " 
		. $record['jfname'] ." ("
		. $record['c'] .")"
		. '</option>';

	}

}

function horse_owner_dropDown(){
	global $OwnerID;
	$dropDown = mysql_query(
		"SELECT
		o.ID AS ID, 
		p.firstName AS ofname,
		p.lastName AS olname

		FROM horseOwner AS o

		INNER JOIN person AS p
		ON o.personID=p.ID"
		);
	echo '<option value=0>Select owner</option>';
	while ($record = mysql_fetch_array($dropDown)) {

		echo '<option value="' . $record['ID'] . '" '.($OwnerID == $record['ID'] ? 'selected' : '').'>' . $record['olname'] .", " . $record['ofname'] . '</option>';

	}

}



function horse_trainer_dropDown(){
	global $TrainerID;
	$dropDown = mysql_query(
		"SELECT 
		t.ID AS ID, 
		p.firstName AS tfname,
		p.lastName AS tlname

		FROM 

		horseTrainer AS t

		INNER JOIN person AS p
		ON t.personID=p.ID"
		);
	echo '<option value=0>Select trainer</option>';
	while ($record = mysql_fetch_assoc($dropDown)) {

		echo '<option value="' . $record['ID'] . '" '.($TrainerID == $record['ID'] ? 'selected' : '').'>' . $record['tlname'] .", " . $record['tfname'] . '</option>';

	}

}

function event_dropDown($eventID,$sportsID){

	$dropDown = mysql_query(
		"SELECT 
		e.ID AS ID,
		e.name AS name,
		c.name cname,
		UPPER (c.alpha_2) AS c

		FROM events e	

		INNER JOIN countries AS c
		ON c.id=e.countryID

		WHERE `sportID`=$sportsID

		ORDER BY c.alpha_2, e.name");
	echo '<option value="">Select event</option>';
	while ($record = mysql_fetch_array($dropDown)) {
		//echo '<option value="' . $record['ID'] . '" '.($eventID == $record['ID'] ? 'selected' : '').'>(' . $record['c'] .") ". $record['name'] . '</option>';
		echo '<option value="' . $record['ID'] . '" '.($eventID == $record['ID'] ? 'selected' : '').'>' . $record['cname'] ." ". $record['name'] . '</option>';

	}
}

function event_season_dropDown($sportsID,$eventSeasonID){

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
			ON e.sportID=s.ID WHERE s.ID=$sportsID
			ORDER BY c.name ASC
			");
	echo '<option value=0>Select event</option>';
	while ($record = mysql_fetch_array($dropDown)) {
		//echo '<option value="' . $record['ID'] . '" '.($eventSeasonID == $record['ID'] ? 'selected' : '').'>' . "(" . strtoupper($record['calpha']) . ") " . date("Y",strtotime($record['sdate']))."-".date("y",strtotime($record['edate']))." ".$record['spnname']." ". $record['ename'] . '</option>';
		$year = (date("Y",strtotime($record['sdate'])) == date("Y",strtotime($record['edate']))) ? date("Y",strtotime($record['sdate'])) : date("Y",strtotime($record['sdate']))."-".date("y",strtotime($record['edate']));
		echo '<option value="' . $record['ID'] . '" '.($eventSeasonID == $record['ID'] ? 'selected' : '').'>' . $record['cname'] . " " . $year ." ".$record['spnname']." ". $record['ename'] . '</option>';

	}
}


function sponsor_dropDown(){
	global $sponsorID;
	$dropDown = mysql_query("SELECT * FROM sponsors WHERE ID != 1 ORDER BY name");
	echo '<option value="1">Select sponsor</option>';
	echo '<option value="1">NA</option>';
	while ($record = mysql_fetch_array($dropDown)) {
		echo '<option value="' . $record['ID'] . '" '.($sponsorID == $record['ID'] ? 'selected' : '').'>' . $record['name'] . '</option>';
	}
}

function brands_dropDown($sponsorID=8){
	$dropDown = mysql_query("SELECT * FROM brands WHERE ID != 8 ORDER BY name");
	echo '<option value="8">Select sponsor brand</option>';
	echo '<option value="8">NA</option>';
	while ($record = mysql_fetch_array($dropDown)) {
		echo '<option value="' . $record['ID'] . '" '.($sponsorID == $record['ID'] ? 'selected' : '').'>' . $record['name'] . '</option>';
	}
}

function brand_dropDown(){
	global $kitID;
	$dropDown = mysql_query("SELECT * FROM brands ORDER BY name ASC");
	echo '<option value=1>Select brand</option>';
	while ($record = mysql_fetch_array($dropDown)) {

		echo '<option value="' . $record['ID'] . '" '.($kitID == $record['ID'] ? 'selected' : '').'>' . $record['name'] . '</option>';

	}
}

function fixture_event_dropDown(){

	$dropDown = mysql_query("SELECT events.ID, events.name, countries.alpha_2 FROM events JOIN countries ON events.countryID=countries.id");

	while ($record = mysql_fetch_array($dropDown)) {

		echo '<option value="' . $record['0'] . '">'. $record['2'] . " " . $record['1'] . '</option>';
	}
}


function teams_dropDown(){
	global $teamID;
	global $sportsID;
	$dropDown = mysql_query("SELECT * FROM teams WHERE `sportID`=$sportsID ORDER BY name");
	echo '<option value=1>Select team</option>';
	while ($record = mysql_fetch_array($dropDown)) {

		echo '<option value="' . $record['ID'] . '" '.($teamID == $record['ID'] ? 'selected' : '').'>' . $record['name'] . '</option>';

	}
}

function esID_cID($esID){
	$row=mysql_fetch_row(mysql_query(
		"SELECT e.countryID cID
		FROM `eventSeason` es 
		INNER JOIN events e
		ON es.eventsID = e.ID
		WHERE es.ID = $esID"));
	return $row['0'];
}

function teams_dropDown_cID($teamID=0,$sportsID,$cID){
	$dropDown = mysql_query("SELECT * FROM teams WHERE `sportID`=$sportsID AND `countryID`=$cID ORDER BY name");
	echo '<option value="">Select team</option>';
	while ($record = mysql_fetch_array($dropDown)) {

		echo '<option value="' . $record['ID'] . '" '.($teamID == $record['ID'] ? 'selected' : '').'>' . $record['name'] . '</option>';

	}
}

function teams_season_dropDown(){
	global $teamSeasonID;
	global $eventSeasonID;
	global $sportsID;

	if(empty($teamSeasonID) && (!empty($eventSeasonID))) { $WHERE = "WHERE ts.eventSeasonID = $eventSeasonID"; }
	if(empty($eventSeasonID) && (!empty($teamSeasonID))) { $WHERE = "WHERE ts.ID = $teamSeasonID"; }

	$dropDown = mysql_query(
		"SELECT
		ts.ID AS ID,
		t.ID AS tid,
		ts.ID AS esid,
		t.name AS tname,
		e.name AS ename,
		es.startDate AS sdate,
		es.endDate AS edate,
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
		INNER JOIN countries AS c
		ON c.id=e.countryID
		$WHERE
                order by t.name");
	echo '<option value=0>Select team</option>';
	while ($record = mysql_fetch_array($dropDown)) {
		echo '<option value="' . $record['ID'] . '" '.($teamSeasonID == $record['ID'] ? 'selected' : '').'>' . $record['tname']." ".date("Y",strtotime($record['sdate']))."-".date("y",strtotime($record['edate'])) . '</option>';

	}
}

function home_team_season_dropDown($htid){
	global $tpid;
	$dropDown = mysql_query(
	"SELECT DISTINCT tp.ID AS tpid,
	tp.number AS num,
	p.firstName AS pfname,
	p.lastName AS plname,
	p.dob AS pdob,

	fix.homeTeamID AS fixhtid

	FROM fixtures AS fix

	INNER JOIN teamSeason AS ts
	ON fix.homeTeamID=ts.ID

	INNER JOIN teamPlayers AS tp
	ON tp.teamSeasonID=ts.ID

	INNER JOIN person AS p
	ON tp.personID=p.ID

	WHERE fix.homeTeamID=$htid

	ORDER BY tp.number
	");

	echo '<option value="">Select home team player</option>';
	while ($record = mysql_fetch_array($dropDown)) {

		echo '<option value="' . $record['tpid'] . '" '.($tpid == $record['0'] ? 'selected' : '').'>' . "(" . $record['num'] .") ". $record['plname'] .", " . $record['pfname'] . '</option>';

	}
}

function away_team_season_dropDown($atid){
global $tpid;
	$dropDown = mysql_query(
	"SELECT DISTINCT tp.ID AS tpid,
	tp.number AS num,
	p.firstName AS pfname,
	p.lastName AS plname,
	p.dob AS pdob,

	fix.awayTeamID AS fixatid

	FROM fixtures AS fix

	INNER JOIN teamSeason AS ts
	ON fix.awayTeamID=ts.ID

	INNER JOIN teamPlayers AS tp
	ON tp.teamSeasonID=ts.ID

	INNER JOIN person AS p
	ON tp.personID=p.ID

	WHERE fix.awayTeamID=$atid

	ORDER BY tp.number
	");

	echo '<option value="">Select away team player</option>';
	while ($record = mysql_fetch_array($dropDown)) {

		echo '<option value="' . $record['tpid'] . '" '.($tpid == $record['0'] ? 'selected' : '').'>' . "(" . $record['num'] .") ". $record['plname'] .", " . $record['pfname'] . '</option>';

	}
}

function home_team_player_from_lineup_dropDown($fixid,$htid){
	global $tpid;
	$dropDown = mysql_query(
	"SELECT
	fixl.fixtureID AS fixid,
	fixl.teamPlayerID AS tpid,
	tp.number AS num,
	tp.personID AS pid,
	tp.teamSeasonID AS tsid,
	p.firstName AS pfname,
	p.lastName AS plname

	FROM fixtureLineup AS fixl

	INNER JOIN teamPlayers AS tp
	ON fixl.teamPlayerID=tp.ID

	INNER JOIN person AS p
	ON tp.personID=p.ID

	WHERE fixl.fixtureID=$fixid
	AND tp.teamSeasonID=$htid

	ORDER BY tp.number
	");

	echo '<option value="">Select home team player</option>';
	while ($record = mysql_fetch_array($dropDown)) {

		echo '<option value="' . $record['tpid'] . '" '.($tpid == $record['0'] ? 'selected' : '').'>' . "(" . $record['num'] .") ". $record['plname'] .", " . $record['pfname'] . '</option>';

	}
}

function away_team_player_from_lineup_dropDown($fixid,$atid){
	global $tpid;
	$dropDown = mysql_query(
	"SELECT
	fixl.fixtureID AS fixid,
	fixl.teamPlayerID AS tpid,
	tp.number AS num,
	tp.personID AS pid,
	tp.teamSeasonID AS tsid,
	p.firstName AS pfname,
	p.lastName AS plname

	FROM fixtureLineup AS fixl

	INNER JOIN teamPlayers AS tp
	ON fixl.teamPlayerID=tp.ID

	INNER JOIN person AS p
	ON tp.personID=p.ID

	WHERE fixl.fixtureID=$fixid
	AND tp.teamSeasonID=$atid

	ORDER BY tp.number
	");

	echo '<option value="">Select away team player</option>';
	while ($record = mysql_fetch_array($dropDown)) {

		echo '<option value="' . $record['tpid'] . '" '.($tpid == $record['0'] ? 'selected' : '').'>' . "(" . $record['num'] .") ". $record['plname'] .", " . $record['pfname'] . '</option>';

	}
}

function second_dropDown(){
	echo '<option value="">Select</option>';
	for ($x=0; $x<60; $x++)
	{
		echo '<option value="' . $x . '" >' . $x . '</option>';
	}
}

function minute_dropDown(){
	echo '<option value="">Select</option>';
	for ($x=0; $x<60; $x++)
	{
		$y = $x + 45;
		echo '<option value="' . $x . '" >' . $x ." / ". $y. '</option>';
	}
}

function lineup_status_dropDown(){
	global $lineupStatusID;
	$dropDown = mysql_query("SELECT `ID`, `name` FROM `lineupStatus`");
	echo '<option value="">Select status</option>';
	while ($record = mysql_fetch_array($dropDown)) {

		echo '<option value="' . $record['ID'] . '" '.($lineupStatusID == $record['ID'] ? 'selected' : '').'>' . $record['name'] . '</option>';

	}

}

function bodyPart_dropDown(){
	global $bodyPartID;
	$dropDown = mysql_query("SELECT `ID`, `name` FROM `bodyPart`");
	echo '<option value="">Select body part</option>';
	while ($record = mysql_fetch_array($dropDown)) {

		echo '<option value="' . $record['ID'] . '" '.($bodyPartID == $record['ID'] ? 'selected' : '').'>' . $record['name'] . '</option>';

	}
}

function incident_dropDown($sportsID){
	global $incidentID;
	$dropDown = mysql_query("SELECT `ID`, `name` FROM `incident` WHERE `sportID`=$sportsID");
	echo '<option value="">Select incident</option>';
	while ($record = mysql_fetch_array($dropDown)) {

		echo '<option value="' . $record['ID'] . '" '.($incidentID == $record['ID'] ? 'selected' : '').'>' . $record['name'] . '</option>';

	}
}

function from_dropDown(){
	global $fromID;
	$dropDown = mysql_query("SELECT `ID`, `name` FROM `from`");
	echo '<option value="">Select from</option>';
	while ($record = mysql_fetch_array($dropDown)) {

		echo '<option value="' . $record['ID'] . '" '.($fromID == $record['ID'] ? 'selected' : '').'>' . $record['name'] . '</option>';

	}

}

function position_cat_dropDown(){
	global $positionCatID;
	global $sportsID;
	$dropDown = mysql_query("SELECT * FROM positionsCat WHERE sportID=$sportsID");
	echo '<option value="">Select position</option>';
	while ($record = mysql_fetch_array($dropDown)) {

		echo '<option value="' . $record['ID'] . '" '.($positionCatID == $record['ID'] ? 'selected' : '').'>' . $record['abbr'] . '</option>';

	}
}

function period_dropDown($sportsID){
	$dropDown = mysql_query("SELECT * FROM period WHERE sportID=$sportsID ORDER BY orderBy");
	echo '<option value="">Select period</option>';
	while ($record = mysql_fetch_array($dropDown)) {

		echo '<option value="' . $record['ID'] . '" '.($periodID == $record['ID'] ? 'selected' : '').'>' . $record['name'] . '</option>';

	}
}


function kit_dropDown(){
	global $kitID;
	$dropDown = mysql_query("SELECT * FROM brands");
	echo '<option value=1>Select kit</option>';
	while ($record = mysql_fetch_array($dropDown)) {

		echo '<option value="' . $record['ID'] . '" '.($kitID == $record['ID'] ? 'selected' : '').'>' . $record['name'] . '</option>';

	}
}

function person_dropDown(){
	global $personID;
	$dropDown = mysql_query("SELECT * FROM person WHERE ID != 250 ORDER BY lastName ASC");
	echo '<option value="250">Select person</option>';
	echo '<option value="250">NA</option>';
	while ($record = mysql_fetch_array($dropDown)) {

		echo '<option value="' . $record['ID'] . '" '.($personID == $record['ID'] ? 'selected' : '').'>' . $record['lastName'] .", ". $record['firstName'] ." ". $record['dob'] . '</option>';

	}
}

function racecourses_dropdown(){
	global $racecoursesID;
	$dropDown = mysql_query("SELECT racecourses.ID, racecourses.name, countries.alpha_2
							FROM racecourses 
							INNER JOIN countries ON racecourses.countryID = countries.id
							ORDER BY racecourses.name");
	echo '<option value="">Select venue</option>';
	while ($record = mysql_fetch_array($dropDown)) {

		echo '<option value="' . $record['0'] . '" '.($racecoursesID == $record['0'] ? 'selected' : '').'>'. $record['1'] .' ('. $record['2'].')'. '</option>';

	}
}

function raceClass_dropdown(){
	global $classID;
	$dropDown = mysql_query("SELECT * FROM raceClass");
	echo '<option value="">Select class</option>';
	while ($record = mysql_fetch_array($dropDown)) {

		echo '<option value="' . $record['ID'] . '" '.($classID == $record['ID'] ? 'selected' : '').'>' . $record['name'] . '</option>';

	}
}

// $goingID = 1;
function going_dropdown(){
	global $goingID;
	// echo $goingID;
	$dropDown = mysql_query("SELECT * FROM going");
	echo '<option value="">Select going</option>';
	while ($record = mysql_fetch_array($dropDown)) {
		echo '<option value="' . $record['ID'] . '" '.($goingID == $record['ID'] ? 'selected' : '').'>' . $record['name'] . '</option>';
	}
}

function labels_dropdown($labelID=1,$userID){
	
	$dropDown = mysql_query("SELECT * FROM labels WHERE active = 1 OR `select` = 1 AND createUserID IN (0,$userID) ORDER BY name ASC");
	echo '<option value="">Select</option>';
	//echo '<option value="1">default</option>';
	while ($record = mysql_fetch_array($dropDown)) {
		echo '<option value="' . $record['ID'] . '" '.($labelID == $record['ID'] ? 'selected' : '').'>' . $record['name'] . '</option>';
	}
}

function staking_dropdown($stakingID=1){
	
	$dropDown = mysql_query("SELECT * FROM staking WHERE active = 1 ORDER BY ID ASC");
	//echo '<option value="">Select</option>';
	//echo '<option value="1">default</option>';
	while ($record = mysql_fetch_array($dropDown)) {
		echo '<option value="' . $record['ID'] . '" '.($stakingID == $record['ID'] ? 'selected' : '').'>' . $record['name'] . '</option>';
	}
}

function user_bank_dropdown($ubnk,$user_id){
	
	$dropDown = mysql_query("SELECT ubnk.userID uID, bnk.ID bnkID, bnk.name bnkname, ubnk.username bnkuname, ubnk.active bnkactive 
                FROM user_bank ubnk
                INNER JOIN users u ON u.user_id = ubnk.userID
                INNER JOIN bank bnk ON bnk.ID = ubnk.bankID
                WHERE ubnk.userID = $user_id");
	echo '<option value="">Select</option>';
	//echo '<option value="1">default</option>';
	while ($record = mysql_fetch_array($dropDown)) {
		echo '<option value="' . $record['bnkID'] . '" '.($ubnk == $record['bnkID'] ? 'selected' : '').'>' . $record['bnkname'] . '</option>';
	}
}

function user_label_dropdown($session_user_id,$active,$select){
	$result = labels_list($session_user_id,$active,$select);
	//echo '<option value="">Select</option>';
	while ($row = mysql_fetch_assoc($result)) {
		?>
		<option value="<?= $row['lID'] ?>"><?= $row['label'] ?></option>
		<?php
	}
}




// from thydzik.com/php-factorial-and-combination-functions/

function factorial($n) {

    if ($n <= 1) {

        return 1;

    } else {

        return factorial($n - 1) * $n;

    }

}

 

function combinations($n, $k) {

    //note this defualts to 0 if $n < $k

    if ($n < $k) {

        return 0;

    } else {

        return factorial($n)/(factorial($k)*factorial(($n - $k)));

    }
}


function trixie($selections,$doubles,$trebles) {

	$totalBets = (combinations($selections,$doubles))+(combinations($selections,$trebles));

	$totalLines = ($doubles*(combinations($selections,$doubles)))+($trebles*(combinations($selections,$trebles)));

	echo "Total Bets:". $totalBets;

	echo "<br>";

	echo "Total Lines:". $totalLines;
}

// functions that generate dynamic urls

function fixture_home_away_url($fixid){
	$sql=
	"SELECT 
	fix.ID AS ID,
	fix.homeTeamID AS htid,
	fix.awayTeamID AS atid
	FROM `fixtures` AS fix
	WHERE fix.ID = $fixid";

	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);

	$home_away_URL= "&htid=" . $row['htid'] . "&atid=" . $row['atid'];
	return $home_away_URL;

	;
}

// logic functions that query tables

function shot_select($fixtureID){

	$sql=
	"SELECT
	sht.ID AS shid,
	prd.name AS prdname,
	sht.time AS stime,

	i.name AS iname,

	p.firstName AS pfname,
	p.lastName AS plname,

	bp.name AS bpname,

	f.name AS fname

	FROM shot AS sht

	INNER JOIN period AS prd
	ON sht.periodID=prd.ID

	INNER JOIN incident AS i
	ON sht.incidentID=i.ID

	INNER JOIN teamPlayers AS tp
	ON sht.teamPlayerID=tp.ID

	INNER JOIN person AS p
	ON tp.personID=p.ID

	INNER JOIN bodyPart AS bp
	ON sht.bodyPartID=bp.ID

	INNER JOIN `from` AS f
	ON sht.fromID=f.ID

	WHERE sht.fixtureID=$fixtureID";

	$result = mysql_query($sql);

	return $result;
}

function total_home_goals($fixtureID,$htid,$atid){
	$sql = 
	"SELECT
	sht.ID AS shid,
	prd.name AS prdname,
	sht.time AS stime,

	i.name AS iname,

	p.firstName AS pfname,
	p.lastName AS plname,

	tp.teamSeasonID AS tsid,
	t.name AS tname,

	bp.name AS bpname,

	f.name AS fname

	FROM shot AS sht

	INNER JOIN period AS prd
	ON sht.periodID=prd.ID

	INNER JOIN incident AS i
	ON sht.incidentID=i.ID

	INNER JOIN teamPlayers AS tp
	ON sht.teamPlayerID=tp.ID

	INNER JOIN teamSeason AS ts
	ON tp.teamSeasonID=ts.ID

	INNER JOIN teams AS t
	ON ts.teamID=t.ID

	INNER JOIN person AS p
	ON tp.personID=p.ID

	INNER JOIN bodyPart AS bp
	ON sht.bodyPartID=bp.ID

	INNER JOIN `from` AS f
	ON sht.fromID=f.ID

	WHERE (
		sht.fixtureID=$fixtureID
		AND tp.teamSeasonID=$htid
		AND sht.incidentID=1)
	OR (
		sht.fixtureID=$fixtureID
		AND tp.teamSeasonID=$atid
		AND sht.incidentID=14)";

	$result = mysql_query($sql);
	$num_rows = mysql_num_rows($result);

	return $num_rows;
}

function total_away_goals($fixtureID,$htid,$atid){
	$sql = 
	"SELECT
	sht.ID AS shid,
	prd.name AS prdname,
	sht.time AS stime,

	i.name AS iname,

	p.firstName AS pfname,
	p.lastName AS plname,

	tp.teamSeasonID AS tsid,
	t.name AS tname,

	bp.name AS bpname,

	f.name AS fname

	FROM shot AS sht

	INNER JOIN period AS prd
	ON sht.periodID=prd.ID

	INNER JOIN incident AS i
	ON sht.incidentID=i.ID

	INNER JOIN teamPlayers AS tp
	ON sht.teamPlayerID=tp.ID

	INNER JOIN teamSeason AS ts
	ON tp.teamSeasonID=ts.ID

	INNER JOIN teams AS t
	ON ts.teamID=t.ID

	INNER JOIN person AS p
	ON tp.personID=p.ID

	INNER JOIN bodyPart AS bp
	ON sht.bodyPartID=bp.ID

	INNER JOIN `from` AS f
	ON sht.fromID=f.ID

	WHERE (
		sht.fixtureID=$fixtureID 
		AND tp.teamSeasonID=$atid
		AND sht.incidentID=1)
	OR (
		sht.fixtureID=$fixtureID 
		AND tp.teamSeasonID=$htid
		AND sht.incidentID=14)
	";

	$result = mysql_query($sql);
	$num_rows = mysql_num_rows($result);

	return $num_rows;
}

function ft_score($fixtureID){
	$sql = "SELECT * FROM `fixtureResults` WHERE `fixtureID` = $fixtureID";
	$result = mysql_query($sql);
	if ($result) {
		$row = mysql_fetch_array($result);
	} else { 
		$row=array("0" , "0");}
	return $row;
}
// half time goals functions
function ht_hgoals($fixtureID){
	$ft_score = ft_score($fixtureID);
	return $ft_score['htscoreht'];
}

function ht_agoals($fixtureID){
	$ft_score = ft_score($fixtureID);
	return $ft_score['atscoreht'];
}

// full time goals function
function ft_hgoals($fixtureID){
	$ft_score = ft_score($fixtureID);
	return $ft_score['htscore'];
}

function ft_agoals($fixtureID){
	$ft_score = ft_score($fixtureID);
	return $ft_score['atscore'];
}

function fixture_ftr($htscore,$atscore){
	if($htscore < $atscore){
		$result= "away";
	} elseif($htscore > $atscore) {
		$result= "home";
	} else $result= "draw";
	return $result;
}

function fixture_ftrID($htscore,$atscore){
	if($htscore < $atscore){
		$result= 2;
	} elseif($htscore > $atscore) {
		$result= 1;
	} else $result= 3;
	return $result;
}

function selection_display_old($selID,$t1name,$t2name){
	if($selID==1){$selval= "home" ." (". $t1name .")";
	} elseif($selID==2){$selval= "away" ." (". $t2name . ")";
	} else $selval= "draw" ; 
	return $selval;
}

function selection_display($mID,$selID,$t1name,$t2name){
	$selval=0;
	switch ($mID) {

		case '1': // win draw lose market
			if($selID==1){$selval= "home" ." (". $t1name .")";
			} elseif($selID==2){$selval= "away" ." (". $t2name . ")";
			} else $selval= "draw" ; 
			break;
		
		case '6': // double chance market
			if($selID==4){$selval= $t1name ." And Draw";
			} elseif($selID==5){$selval= $t2name ." And Draw";
			} else $selval= $t1name ." And " . $t2name; 
			break;

		case '7': // BTTS market
			if ($selID==7) {
				$selval = "Yes";
			} else {$selval = "No";}
			break;

		case '8': // Total goals market
			if ($selID==9) {
				$selval = "Over 2.5";
			} elseif ($selID==10) {
				$selval = "Under 2.5";
			} elseif ($selID==13) {
				$selval = "Over 2.0";
			} elseif ($selID==14) {
				$selval = "Under 2.0";
			} elseif ($selID==15) {
				$selval = "Over 3.0";
			} elseif ($selID==16) {
				$selval = "Under 3.0";
			} elseif ($selID==17) {
				$selval = "Over 3.5";
			} elseif ($selID==18) {
				$selval = "Under 3.5";
			}  

			break;

		case '9': // Total goals market
			if($selID==1){$selval= "home" ." (". $t1name .")";
			} elseif($selID==2){
				$selval= "away" ." (". $t2name . ")";
			} 
			break;
                        
		case '10': // win draw lose market
			if($selID==1){$selval= "home" ." (". $t1name .")";
			} elseif($selID==2){$selval= "away" ." (". $t2name . ")";
			} else $selval= "draw" ; 
			break;
		default:
			# code...
			break;
	}
	return $selval;
}

function sb_table($sb_where, $sb_order, $fixtureID=0){
	$sb_where .= ($fixtureID==0) ? '' : " sb.fixtureID = $fixtureID";
	$sql =
	"SELECT 
	sb.ID sbID, 
	sb.userID, 
	sb.fixtureID fixID,
	sb.marketID mID,

	s.id sID,
	s.name sname,

	e.name ename,

	es.ID esID,

	fix.fixtureStatusID fixstID,

	t1.name t1name, 
	t1.ID htid,
	ts1.ID ts1id,

	t2.name t2name,
	t2.ID atid,
	ts2.ID ts2id,

	m.name mname,
	m.abbr mabbr,

	sel.ID selID,
	sel.name selname,

	sb.active,
	l.ID lID,
	l.name label,
	sb.createTimestamp dt

	FROM selectionBoard_02 sb

	INNER JOIN markets m
	ON m.ID = sb.marketID 

	INNER JOIN selection sel
	ON sel.ID = sb.selectionID 

	INNER JOIN fixtures fix
	ON sb.fixtureID=fix.ID

	INNER JOIN eventSeason es
	ON fix.eventSeasonID=es.ID

	INNER JOIN events e
	ON es.eventsID=e.ID

	INNER JOIN sports s
	ON e.sportID=s.ID

	INNER JOIN teamSeason ts1 
	ON fix.homeTeamID=ts1.ID

	INNER JOIN teams t1 
	ON ts1.teamID=t1.ID

	INNER JOIN teamSeason ts2 
	ON fix.awayTeamID=ts2.ID

	INNER JOIN teams t2 
	ON ts2.teamID=t2.ID

	INNER JOIN labels l
	ON sb.labelID = l.ID

	WHERE $sb_where
	ORDER BY $sb_order";

	return mysql_query($sql);

}

function sb_table_distinct($sb_where, $sb_order){
	$sql =
	"SELECT DISTINCT
	sb.ID sbID,
	sb.fixtureID fixID,


	s.id AS sID,
	s.name AS sname,

	e.name AS ename,

	es.ID AS esID,

	fix.fixtureStatusID AS fixstID,
	fix.date date,
	fix.time time,

	t1.name AS t1name, 
	t1.ID AS htid,
	ts1.ID AS ts1id,

	t2.name AS t2name,
	t2.ID AS atid,
	ts2.ID AS ts2id

	FROM selectionBoard_02 AS sb

	INNER JOIN markets AS m
	ON m.ID = sb.marketID 

	INNER JOIN selection AS sel
	ON sel.ID = sb.selectionID 

	INNER JOIN fixtures AS fix
	ON sb.fixtureID=fix.ID

	INNER JOIN eventSeason AS es
	ON fix.eventSeasonID=es.ID

	INNER JOIN events AS e
	ON es.eventsID=e.ID

	INNER JOIN sports AS s
	ON e.sportID=s.ID

	INNER JOIN teamSeason ts1 
	ON fix.homeTeamID=ts1.ID

	INNER JOIN teams t1 
	ON ts1.teamID=t1.ID

	INNER JOIN teamSeason ts2 
	ON fix.awayTeamID=ts2.ID

	INNER JOIN teams t2 
	ON ts2.teamID=t2.ID

	INNER JOIN labels l
	ON sb.labelID = l.ID

	WHERE $sb_where
	ORDER BY $sb_order";

	return mysql_query($sql);

}

function bt_table($bt_where,$orderby="bt.ID DESC"){
	$sql = 
	"SELECT 
	bt.ID AS btID,
	bt.userID AS userID,
	bt.date AS date,
	bt.time AS time,
	bt.labelID AS btlID,
	l.name AS btlname,

	bt.betLinesID AS btlines,
	bl.name AS blname,

	bt.betStatusID AS bstID,
	bst.name AS bstname,

	bt.odds AS odds,
	bk.name AS bkname,

	bt.stake AS stake,
	bt.returns AS returns,
	bt.PL AS PL

	FROM `betTracker` AS bt

	INNER JOIN betStatus AS bst
	ON bt.betStatusID=bst.ID

	INNER JOIN betLines AS bl
	ON bt.betLinesID=bl.ID

	INNER JOIN userBookies AS ubk
	ON bt.bookieID=ubk.ID

	INNER JOIN bookies AS bk
	ON ubk.bookieID=bk.ID

	INNER JOIN betDetail bd 
	ON bd.betTrackerID = bt.ID 

	INNER JOIN selectionBoard_02 sb 
	ON bd.selectionBoardID = sb.ID

	INNER JOIN labels l
	ON bt.labelID = l.ID

	WHERE $bt_where

	ORDER BY $orderby" ;

	$result = mysql_query($sql);
	return $result;
}

function bd_table($bd_where){
	$sql = 
	"SELECT 
	bd.ID AS bdID,
	bd.betTrackerID AS btID,

	bd.betLinesID AS blID,
	bl.name AS blname,

	sb.ID AS sbID,

	fix.ID AS fixID,
	fix.fixtureStatusID AS fixstID,
	fixst.name AS fixstname,
	fix.eventSeasonID AS esID,

	e.sportID AS sID,
	e.name AS ename,

	fix.homeTeamID AS htID,
	ht.name AS htname,

	fix.awayTeamID AS atID,
	at.name AS atname,

	mk.ID AS mkID,
	mk.name AS mkname,

	sl.ID AS slID,
	sl.name AS slname,

	bst.ID AS bstID,
	bst.name AS bstname,

	bd.odds AS odds,

	bd.stake AS stake,
	bd.returns AS returns,

	bd.PL AS PL

	FROM `betDetail` AS bd

	INNER JOIN betLines AS bl
	ON bd.betLinesID=bl.ID

	INNER JOIN selectionBoard_02 AS sb
	ON bd.selectionBoardID=sb.ID

	INNER JOIN fixtures AS fix
	ON sb.fixtureID=fix.ID

	INNER JOIN fixtureStatus AS fixst
	ON fix.fixtureStatusID=fixst.ID

	INNER JOIN eventSeason AS es
	ON fix.eventSeasonID=es.ID

	INNER JOIN events AS e
	ON es.eventsID=e.ID

	INNER JOIN teamSeason AS hts
	ON fix.homeTeamID=hts.ID

	INNER JOIN teamSeason AS ats
	ON fix.awayTeamID=ats.ID

	INNER JOIN teams AS ht
	ON hts.teamID=ht.ID

	INNER JOIN teams AS at
	ON ats.teamID=at.ID

	INNER JOIN markets AS mk
	ON sb.marketID=mk.ID

	INNER JOIN selection AS sl
	ON sb.selectionID=sl.ID

	INNER JOIN betStatus AS bst
	ON bd.betStatusID=bst.ID

	WHERE $bd_where
	ORDER BY bd.ID";

	$result = mysql_query($sql);

	return $result;
}

function bd_table_while($btID){
	$bd_where = "bd.betTrackerID = $btID";

	$result = bd_table($bd_where);

	while($row = mysql_fetch_array($result)) {
	// print_r($row);

	$fixtureID = $row['fixID'];
	$fixst = $row['fixstID'];
	$htid = $row['htID'];
	$atid = $row['atID'];

	echo "<tr>";

	echo "<td>" . $row['bdID'] . "</td>"; // Bet detail ID

	echo "<td></td>";

	echo "<td>" . $row['blname'] . "</td>"; // Bet type eg. single, double, yankee...

	echo "<td>" .fixture_list_heading($row['sID'],$row['esID']) . "</td>"; // Event eg. Prem League

	echo "<td><a href=\"index.php?page=fixtures&view=detail&sid=2&esid={$row['esID']}&fixid={$row['fixID']}&htid={$row['htID']}&atid={$row['atID']}&addshot=0&addmatchtime=0&addlineup=0". score_url() ."\">" . fixture_detail_heading($row['fixID'],1) . "</a></td>"; // Event detail

	echo "<td>" . $row['mkname'] . "</td>"; // Market eg. Win draw lose

	echo "<td>" . $row['slname'] . "</td>"; // Bet selection eg. home, away, draw...

	echo "<td>" . $row['odds'] . "</td>"; // Odds

	echo "<td></td>"; // Place odds

	echo "<td>" . $row['stake'] . "</td>"; // Stake amount

	echo "<td>$" . ($row['returns']) . "</td>"; // Expected return

	echo "<td>" . matchStatus($fixst,$fixtureID) . "</td>"; // Result eg. home, draw, etc.	

	echo "<td>" . $row['bstname'] . "</td>"; // Bet Status	

	echo "<td>$" . $row['PL'] . "</td>"; // Profit / Loss... must find a way to calculste this value depending on bet status.

	echo "</tr>";

	}
}

function cm_table($cm_where){
	
	$sql = 
	"SELECT 
	cm.ID cmID,
	cm.body cm, 
	cm.createTimestamp dt,
	cm.createUserID uID,
	u.username uname 
	
	FROM `cm_fixture` cm
    
    INNER JOIN users u
    ON cm.createUserID = u.user_id
	
	WHERE cm.replyID = '' AND cm.fixtureID = $cm_where";
	
	return mysql_query($sql);

}

function cmr_table($cmr_where){
	
	$sql = 
	"SELECT 
	cm.ID cmID,
	cm.replyID replyID,
	cm.body cm, 
	cm.createTimestamp dt,
	cm.createUserID uID,
	u.username uname 
	
	FROM `cm_fixture` cm
    
    INNER JOIN users u
    ON cm.createUserID = u.user_id
	
	WHERE cm.replyID != '' AND cm.replyID = $cmr_where";
	
	return mysql_query($sql);

}


function matchStatus($fixst,$fixtureID){
	$hgoals = ft_hgoals($fixtureID); 
	$agoals = ft_agoals($fixtureID);
	return ($fixst == 1) ? "Pending" : fixture_ftr($hgoals,$agoals)."<br/>" . $hgoals ." - ". $agoals;
}

function matchStatus_v2($fixst,$fixtureID){
	$hgoals = ft_hgoals($fixtureID); 
	$agoals = ft_agoals($fixtureID);
	return ($fixst == 1) ? "vs" : $hgoals ." - ". $agoals;
}

function matchStatus_v3($fixst,$fixtureID){
	$hgoals = ft_hgoals($fixtureID); 
	$agoals = ft_agoals($fixtureID);
	$ht_hgoals = ht_hgoals($fixtureID);
	$ht_agoals = ht_agoals($fixtureID);
	return ($fixst == 1) ? "" : " ($ht_hgoals - $ht_agoals, $hgoals - $agoals)";
}

function bd_table_while_tiny($btID){
	$bd_where = "bd.betTrackerID = $btID";

	$result = bd_table($bd_where);

	while($row = mysql_fetch_array($result)) {
	// print_r($row);

	$fixtureID = $row['fixID'];
	$fixst = $row['fixstID'];
	$htid = $row['htID'];
	$atid = $row['atID'];

	$hgoals = ft_hgoals($fixtureID); 
	$agoals = ft_agoals($fixtureID);

	$matchStatus = ($fixst == 1) ? "Pending" : fixture_ftr($hgoals,$agoals)."<br/>" . $hgoals ." - ". $agoals;

	echo "<tr>";

	echo "<td></td>";

	echo "<td></td>";

	echo "<td>" . $row['bdID'] . "</td>"; // Bet detail ID

	echo "<td>" . truncate(fixture_list_heading($row['sID'],$row['esID']), 20, $stopanywhere=false) . "</td>"; // Event eg. Prem League

	echo "<td><a href=\"index.php?page=fixtures&view=detail&sid=2&esid={$row['esID']}&fixid={$row['fixID']}&htid={$row['htID']}&atid={$row['atID']}&addshot=0&addmatchtime=0&addlineup=0". score_url() ."\">" . fixture_detail_heading($row['fixID'],1) . "</a></td>"; // Event detail

	echo "<td>" . $row['mkname'] . " (" . $row['slname'] .") @ " . $row['odds'] . "</td>"; // Market eg. Win draw lose

	//echo "<td>" . $row['slname'] . "</td>"; // Bet selection eg. home, away, draw...

	//echo "<td>" . $row['odds'] . "</td>"; // Odds

	//echo "<td>" . $row['stake'] . "</td>"; // Stake amount

	//echo "<td>$" . ($row['returns']) . "</td>"; // Expected return

	echo "<td>" . $matchStatus . " / " . $row['bstname'] . "</td>"; // Result eg. home, draw, etc.	

	//echo "<td>" . $row['bstname'] . "</td>"; // Bet Status	

	//echo "<td>$" . $row['PL'] . "</td>"; // Profit / Loss... must find a way to calculste this value depending on bet status.

	echo "</tr>";

	}
}

function bd_sbType($btID){
	$sql = 
	"SELECT sbType FROM betDetail WHERE betTrackerID = $btID";
	return mysql_result(mysql_query($sql),0);
}

function bd_bt_inner($btID){
	$return = '';

	$sbType =  bd_sbType($btID);
	
	switch ($sbType) {
	
		case 1:
		
			
			break;
			
		case 2:
		
			$sql =
			"SELECT 
			
			bd.odds odds,
			sb.fixtureID fixID,
			m.abbr m,
			sel.name selName,
			l.name lname

			FROM betDetail bd 

			INNER JOIN selectionBoard_02 sb
			ON bd.selectionBoardID = sb.ID
			
			INNER JOIN markets m
			ON sb.marketID = m.ID

			INNER JOIN selection sel
			ON sb.selectionID = sel.ID

			INNER JOIN labels l
			ON sb.labelID = l.ID

			WHERE betTrackerID = $btID";

			$result = mysql_query($sql);

			while ($row=mysql_fetch_assoc($result)) {
				$return .= $row['lname'];
				$return .= ' ';
				$return .= fixture_detail_nodate($row['fixID'],1) . ' ';
				$return .= ' ';
				$return .= $row['m'] . ' ';
				$return .= $row['selName'];
				$return .= ' @ ' . $row['odds'];
				$return .= '<br/>';
			}
			break;	
	}

	return $return;
}

function odds_insert($odds,$sID,$eID,$mID,$selID,$bkID,$uID){
	$createTimestamp = timestamp();
	
	$result = mysql_query(
		"SELECT ID FROM odds 
		WHERE sID = $sID 
		AND eventID = $eID 
		AND marketID = $mID 
		AND selectionID = $selID
		AND bookieID = $bkID
		AND createUserID = $uID");
	if(mysql_num_rows($result) == 0) {
		$sql = "INSERT INTO `odds`(
			`odds`, `sID`, `eventID`, `marketID`, `selectionID`, `bookieID`, `createUserID`, `createTimestamp`) 
			VALUES ($odds,$sID,$eID,$mID,$selID,$bkID,$uID,'$createTimestamp')";
		$result=mysql_query($sql);
		if($result){
			//echo "added odds";
		} else {
			echo "ERROR: inserting odds.";
		}
	} else {
		// do other stuff...
	}
}

function get_odds($sID,$eID,$mID,$selID){
	//echo "SELECT MAX(`odds`) FROM odds WHERE sID = $sID AND eventID = $eID AND marketID = $mID AND selectionID = $selID";
	$s = "SELECT `odds`, bookieID FROM odds WHERE sID = $sID AND eventID = $eID AND marketID = $mID 
		AND selectionID = $selID ";
	$r = mysql_query($s);
	if (mysql_num_rows($r) > 0) {
		return mysql_result($r, 0);
	}
	
}

function show_odds($sID,$eID,$mID,$selID){
	$odds = get_odds($sID,$eID,$mID,$selID);
	if(!empty($odds)){
		return $odds;
	} else {
		return "add";
	}
}

function get_odds_bookie($sID,$eID,$mID,$selID){

	return mysql_fetch_assoc(mysql_query(
		"SELECT MAX(o.odds) o, b.name b 
		FROM odds o 
		INNER JOIN userBookies ubk
		ON o.bookieID = ubk.ID
		INNER JOIN bookies b 
		ON ubk.bookieID = b.ID 
		WHERE o.sID = $sID AND o.eventID = $eID AND o.marketID = $mID 
		AND o.selectionID = $selID "), 0);
}

function get_odds_bookieID($sID,$eID,$mID,$selID){

	return mysql_fetch_assoc(mysql_query(
		"SELECT MAX(o.odds) o, o.bookieID bkID 
		FROM odds o 
		WHERE o.sID = $sID AND o.eventID = $eID AND o.marketID = $mID 
		AND o.selectionID = $selID "), 0);
}

function show_odds_bookie($sID,$eID,$mID,$selID){
	//$odds = get_odds_bookie($sID,$eID,$mID,$selID);
	$row = get_odds_bookie($sID,$eID,$mID,$selID);
	$odds = $row['0'] .' @ '. $row['1'];
	if(!empty($row['0'])){
		return $odds;
	} else {
		return "add";
	}
}

function get_odds_bookie_list($sID,$eID,$mID,$selID){

	$sql = "SELECT DISTINCT o.odds o, b.name b 
		FROM odds o 
		INNER JOIN userBookies ubk
		ON o.bookieID = ubk.ID
		INNER JOIN bookies b 
		ON ubk.bookieID = b.ID 
		WHERE o.sID = $sID AND o.eventID = $eID AND o.marketID = $mID 
		AND o.selectionID = $selID ";
	$result = mysql_query($sql);
	echo '<ul>';
	while ($row = mysql_fetch_assoc($result)) {?>
		<li class="odds-modal"><a href="#" data-toggle="modal" data-target="#odds-modal" eID="<?= $eID ?>" mID="<?= $mID ?>" selID="<?= $selID ?>"><?= $row['o'] ?> @ <?= $row['b'] ?></a></li>
	<?php
	}
	echo '</ul>';
}

function labels_table($userID,$active=0){
	$where = "l.createUserID = $userID";
	$where .= ($active != 0) ? " AND l.active = $active" : "";
	$sql = 
	"SELECT l.ID lID, l.name name,l.description `desc`, l.active active, l.createTimestamp dt, l.bank bank, st.name st
	FROM labels l
	INNER JOIN staking st
	ON l.stakingID=st.ID
	WHERE $where";
	return mysql_query($sql);
	//return $sql;
}


function labels_list_v1($userID,$active=0){
	$where = "sb.userID = $userID";
	$where .= ($active != 0) ? " AND sb.active = $active" : "";
	$sql = 
	"SELECT DISTINCT l.ID lID, l.name label
	FROM `selectionBoard_02` sb 
	INNER JOIN labels l ON sb.labelID = l.ID WHERE $where OR l.ID = 1";

	return mysql_query($sql);
}

function labels_list($userID,$active=0,$select=0){
	$where = "l.createUserID = $userID";
	$where .= ($active != 0) ? " AND l.active = $active" : "";
	$where .= ($select != 0) ? " AND l.select = $select" : "";
	$sql = 
	"SELECT l.ID lID, l.name label
	FROM  labels l
	WHERE $where OR l.ID = 1";

	return mysql_query($sql);
}

function labels_list_v2($userID,$active=0){
	$where = "l.createUserID = $userID";
	$where .= ($active != 0) ? " AND l.active = $active" : "";
	$sql = 
	"SELECT l.ID lID, l.name label
	FROM  labels l
	WHERE $where OR l.ID = 1";

	return mysql_query($sql);
}

function bt_count($fixtureID,$session_user_id=0){
	$WHERE = "sb.fixtureID = $fixtureID";
	$WHERE .= ($session_user_id==0) ? "" : " AND bt.userID = $session_user_id";
	$sql =
	"SELECT COUNT(bd.ID)

	FROM `betDetail` AS bd

	INNER JOIN `betTracker` bt
	ON bd.betTrackerID = bt.ID

	INNER JOIN `selectionBoard_02` sb
	ON bd.selectionBoardID = sb.ID

	WHERE $WHERE";
	$result = mysql_query($sql);
	return mysql_result($result, 0);
}

function sb_count($fixtureID,$session_user_id=0){
	$WHERE = "sb.fixtureID = $fixtureID";
	$WHERE .= " AND sb.active = 1 ";
	$WHERE .= ($session_user_id==0) ? "" : " AND sb.userID = $session_user_id";
	
	$sql =
	"SELECT COUNT(sb.ID)

	FROM `selectionBoard_02` sb

	WHERE $WHERE";
	$result = mysql_query($sql);
	return mysql_result($result, 0);
}

function cm_count($fixtureID,$session_user_id=0){
	$WHERE = "fixtureID = $fixtureID";
	$WHERE .= ($session_user_id==0) ? "" : " AND sb.userID = $session_user_id";
	$sql =
	"SELECT COUNT(ID)

	FROM `cm_fixture`

	WHERE $WHERE";
	$result = mysql_query($sql);
	return mysql_result($result, 0);
}

function URL_replace($url,$string,$variable=0){
	if (isset($_GET[$string])) {
        $string=$_GET[$string];
        $URL = str_replace("&$string=$$string","&$string=$variable",$url);
    } else {
    	$URL = $url . "&$string=$variable";
    }

    return $URL;
}

function URL_replace_long($url,$needle,$new=0){
	if (!strstr($url, $needle)) {
		$URL = $url . $new;
	} else {
		$replace = strstr($url, $needle,1);
		$URL = $replace . $new;
	}

    return $URL;
}

function bd_sb_list($where){
	
	$sql = 
	"SELECT DISTINCT 
	bd.selectionBoardID sbID, 
	bd.sbType sbType, 
	bd.betStatusID betStatusID
	
	FROM `betDetail` bd
	
	INNER JOIN betTracker bt
	ON bd.betTrackerID = bt.ID
	
	WHERE $where";
	return mysql_query($sql);
	
}

function bd_sb_list_details($sbID,$sbType){
	
	$sb_suffix = "0" . $sbType;
	
	SWITCH ($sbType) {
	
		case 1:
			$eventID = "sb.racecardID eventID";
			$sel_field = "sb.selectionID selname";
			$sel_join = "";
			
			break;

		case 2:
			$eventID = "sb.fixtureID eventID";
			$sel_field = "sel.name selname";
			$sel_join = "INNER JOIN selection sel ON sb.selectionID = sel.ID";
			
			break;			
	}
	
	$sql = 
	"SELECT 
	sb.ID sbID,
	l.name label,
	bs.ID bsID,
	bs.name bsname,
	bd.odds odds,
	bd.podds podds,
	m.abbr mabbr,
	$sel_field,
	$eventID
	

	FROM `betDetail` bd 

	INNER JOIN selectionBoard_$sb_suffix sb
	ON bd.selectionBoardID = sb.ID
	
	INNER JOIN labels l
	ON sb.labelID = l.ID
	
	INNER JOIN betStatus bs
	ON bd.betStatusID = bs.ID
	
	INNER JOIN markets m
	ON sb.marketID = m.ID
	
	$sel_join

	WHERE sb.ID = $sbID";

	return mysql_fetch_assoc(mysql_query($sql));
}

function eventType($sbType,$eventID){

	switch ($sbType){
		
		case 1:
			return $eventID;
			break;
			
		case 2:
			return fixture_detail_nodate(($eventID),1);
			break;
	}
}

// functions that generate dynamic headings

function racecard_location($rcID){

	$sql=
	"SELECT 
	rc.ID AS rcID,

	rc.race AS race,
	c.alpha_2 AS calpha,
	rcs.name AS rcs


	FROM `racecards` AS rc

	INNER JOIN racecourses AS rcs
	ON rc.racecoursesID=rcs.ID

	INNER JOIN countries AS c
	ON rcs.countryID=c.ID

	WHERE rc.ID = $rcID
	";

	$result=mysql_query($sql);
	$row=mysql_fetch_assoc($result);

	$rc_location= "Race " . $row['race'] . " " . $row['rcs'] . " (" .$row['calpha'] . ")";

	return $rc_location;
}

function racecard_name($rcID){
	$sql = 
	"SELECT 
	rc.ID AS rcID, 
	rc.date AS date, 
	rc.time AS time, 
	rc.name AS name, 
	rclass.name AS rclass, 
	going.name AS going, 
	rc.distance AS distance, 
	rc.prize AS prize

	FROM racecards AS rc

	INNER JOIN racecourses AS rcs ON rc.racecoursesID = rcs.ID
	INNER JOIN raceClass AS rclass ON rc.raceClassID = rclass.ID
	INNER JOIN going ON rc.goingID = going.ID

	WHERE rc.ID = $rcID
	";

	$result = mysql_query($sql);
	$row=mysql_fetch_assoc($result);

	$rc_name= date('D d-M', strtotime($row['date'])) ." ". $time = date('g:i a', strtotime($row['time'])) ." ". $row['name'];

	return $rc_name;
}

function rc_name($rcID){
	$sql = 
	"SELECT 
	rc.ID AS rcID, 
	rc.date AS date, 
	rc.time AS time, 
	rc.name AS name, 
	rclass.name AS rclass, 
	going.name AS going, 
	rc.distance AS distance, 
	rc.prize AS prize

	FROM racecards AS rc

	INNER JOIN racecourses AS rcs ON rc.racecoursesID = rcs.ID
	INNER JOIN raceClass AS rclass ON rc.raceClassID = rclass.ID
	INNER JOIN going ON rc.goingID = going.ID

	WHERE rc.ID = $rcID
	";

	$result = mysql_query($sql);
	$row=mysql_fetch_assoc($result);

	return date('D d-M', strtotime($row['date'])) ." ". $time = date('g:i a', strtotime($row['time'])) ."<br/>". $row['name'];

}

function rc_name_td($rcID){

	return '<td><a href=index.php?page=racecards&rid=' . $rcID . '>' . rc_name($rcID) . '</a></td>';

}

function team_season_heading($teamSeasonID){

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

	$team_season_heading = date("Y",strtotime($row['5']))."-".date("y",strtotime($row['6']))." ".$row['3'];

	return $team_season_heading;
}

function fixture_list_heading($sportsID,$eventSeasonID){
	if(!empty($sportsID) && !empty($eventSeasonID)){
		$where="WHERE s.ID='$sportsID' AND es.ID='$eventSeasonID'";
	}
	
	$sql = 
	"SELECT 
	es.ID AS ID, 
	es.ID AS esID,
	es.eventsID AS eid,
	e.name AS ename, 
	spn.name AS spnname, 
	es.startDate AS sdate, 
	es.endDate AS edate, 
	c.name AS cname, 
	UPPER (c.alpha_2) AS calpha, 
	s.ID AS sid
	FROM eventSeason AS es
	INNER JOIN events AS e
	ON es.eventsID=e.ID
	INNER JOIN brands AS spn
	ON es.sponsorID=spn.ID
	INNER JOIN countries AS c
	ON e.countryID=c.id
	INNER JOIN sports AS s
	ON e.sportID=s.ID
	$where
	ORDER BY es.startDate DESC
	";

	$result = mysql_query($sql);
	$row=mysql_fetch_assoc($result);

	$heading = "(" . $row['calpha'] . ") " . date("Y",strtotime($row['sdate']))."-".date("y",strtotime($row['edate']))." ".$row['spnname']." ". $row['ename'];
	return $heading;
}

function eventSeason($sportsID,$eventSeasonID){
	if(!empty($sportsID) && !empty($eventSeasonID)){
		$where="WHERE s.ID='$sportsID' AND es.ID='$eventSeasonID'";
	}
	
	$sql = 
	"SELECT 
	es.ID AS ID, 
	es.ID AS esID,
	es.eventsID AS eid,
	e.name AS ename, 
	spn.name AS spnname, 
	es.startDate AS sdate, 
	es.endDate AS edate, 
	c.name AS cname, 
	UPPER (c.alpha_2) AS calpha, 
	s.ID AS sid
	FROM eventSeason AS es
	INNER JOIN events AS e
	ON es.eventsID=e.ID
	INNER JOIN brands AS spn
	ON es.sponsorID=spn.ID
	INNER JOIN countries AS c
	ON e.countryID=c.id
	INNER JOIN sports AS s
	ON e.sportID=s.ID
	$where
	ORDER BY es.startDate DESC
	";

	$result = mysql_query($sql);
	$row=mysql_fetch_assoc($result);

	$esID = $row['esID'];

	$heading = "(" . $row['calpha'] . ") " . date("Y",strtotime($row['sdate']))."-".date("y",strtotime($row['edate']))."<br/><b>".$row['spnname']." ". $row['ename']. "</b>";
	return '<div class="fevent"><a href="index.php?page=events&view=detail&sid=' .$sportsID. '&esid=' .$esID. '&teamslist=1&fixlist=1&filter=default">' .$heading. '</a></div>';
}

function eventSeasonHeading($sportsID,$eventSeasonID){
	if(!empty($sportsID) && !empty($eventSeasonID)){
		$where="WHERE s.ID='$sportsID' AND es.ID='$eventSeasonID'";
	}
	
	$sql = 
	"SELECT 
	es.ID AS ID, 
	es.ID AS esID,
	es.eventsID AS eid,
	e.name AS ename, 
	spn.name AS spnname, 
	es.startDate AS sdate, 
	es.endDate AS edate, 
	c.name AS cname, 
	UPPER (c.alpha_2) AS calpha, 
	s.ID AS sid
	FROM eventSeason AS es
	INNER JOIN events AS e
	ON es.eventsID=e.ID
	INNER JOIN brands AS spn
	ON es.sponsorID=spn.ID
	INNER JOIN countries AS c
	ON e.countryID=c.id
	INNER JOIN sports AS s
	ON e.sportID=s.ID
	$where
	ORDER BY es.startDate DESC
	";

	$result = mysql_query($sql);
	$row=mysql_fetch_assoc($result);

	$esID = $row['esID'];

	//$heading = "(" . $row['calpha'] . ") " . date("Y",strtotime($row['sdate']))."-".date("y",strtotime($row['edate']))."<br/><b>".$row['spnname']." ". $row['ename']. "</b>";
	$heading = $row['spnname']." ". $row['ename'];
	return '<h2><a href="index.php?page=events&view=detail&sid=' .$sportsID. '&esid=' .$esID. '&teamslist=1&fixlist=1&filter=default">' .$heading. '</a></h2>';
}

function eventSeasonHeading_v2($esID){
	$sql = 
	"SELECT 
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
	ON e.sportID=s.ID
	WHERE es.ID = $esID
	";

	$result = mysql_query($sql);
	$row=mysql_fetch_assoc($result);

	$year = (date("Y",strtotime($row['sdate'])) == date("Y",strtotime($row['edate']))) ? date("Y",strtotime($row['sdate'])) : date("Y",strtotime($row['sdate']))."-".date("y",strtotime($row['edate']));

	return $year." ".$row['spnname']." ". $row['ename'];	
}

function fixture_detail($fixID,$format=0){
	if ($format==1) {
		$formatOpen="<br/><strong>";
		$formatClose="</strong>";
	} else {
		$formatOpen=$formatClose="";
	}

	// sql for heading
	$sql = 
	"SELECT 
	fix.ID,
	e.ID as eid,
	s.ID as sid,
	fix.date date, 
	fix.time time,

	fix.eventSeasonID esID,

	e.name as ename,
	spn.name as spnname,
	es.startDate AS sdate,
	es.endDate AS edate,

	t1.ID AS t1id,
	t1.name AS t1name,

	t2.ID AS t2id,
	t2.name AS t2name,

	c.name AS cname,
	c.alpha_2 AS ccode

	FROM fixtures AS fix

	INNER JOIN eventSeason AS es
	ON es.ID=fix.eventSeasonID

	INNER JOIN events AS e
	ON e.ID=es.eventsID

	INNER JOIN brands AS spn
	ON spn.ID=es.sponsorID

	INNER JOIN sports AS s
	ON s.ID=e.sportID

	INNER JOIN teamSeason AS ts1
	ON fix.homeTeamID=ts1.ID

	INNER JOIN teams AS t1
	ON ts1.teamID=t1.ID

	INNER JOIN teamSeason AS ts2
	ON fix.awayTeamID=ts2.ID

	INNER JOIN teams AS t2
	ON ts2.teamID=t2.ID

	INNER JOIN countries AS c
	ON c.id=e.countryID

	WHERE fix.ID=$fixID
	";

	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);

	$mysqldate = $row['date'];
	$audate = date('D d-M', strtotime($mysqldate));
	$mysqltime = $row['time'];
	$time = date('g:i a', strtotime($mysqltime));

	$heading = '<td><a href="index.php?page=fixtures&view=detail&sid=2&esid='.$row['esID'].'&fixid='.$fixID.'&htid=' .$row['t1id'].'&atid='.$row['t2id'] . score_url () . '">' . $audate . $time . $formatOpen .$row['t1name'] . " v " . $row['t2name'] . "$formatClose".'</a></td>';

	return $heading;
}

function fixture_detail_heading($fixID,$format=0){
	if ($format==1) {
		$formatOpen="<br/><strong>";
		$formatClose="</strong>";
	} else {
		$formatOpen=$formatClose="";
	}

	// sql for heading
	$sql = 
	"SELECT 
	fix.ID,
	e.ID as eid,
	s.ID as sid,
	fix.date, 
	fix.time,
	e.name as ename,
	spn.name as spnname,
	es.startDate AS sdate,
	es.endDate AS edate,

	t1.ID AS t1id,
	t1.name AS t1name,

	t2.ID AS t2id,
	t2.name AS t2name,

	c.name AS cname,
	c.alpha_2 AS ccode

	FROM fixtures AS fix

	INNER JOIN eventSeason AS es
	ON es.ID=fix.eventSeasonID

	INNER JOIN events AS e
	ON e.ID=es.eventsID

	INNER JOIN brands AS spn
	ON spn.ID=es.sponsorID

	INNER JOIN sports AS s
	ON s.ID=e.sportID

	INNER JOIN teamSeason AS ts1
	ON fix.homeTeamID=ts1.ID

	INNER JOIN teams AS t1
	ON ts1.teamID=t1.ID

	INNER JOIN teamSeason AS ts2
	ON fix.awayTeamID=ts2.ID

	INNER JOIN teams AS t2
	ON ts2.teamID=t2.ID

	INNER JOIN countries AS c
	ON c.id=e.countryID

	WHERE fix.ID=$fixID
	";

	$result = mysql_query($sql);
	$row = mysql_fetch_row($result);

	$mysqldate = $row['3'];
	$audate = date('D d-M', strtotime($mysqldate));
	$mysqltime = $row['4'];
	$time = date('g:i a', strtotime($mysqltime));

	$heading = "$audate $time $formatOpen" . $row['10'] . " v " . $row['12'] . "$formatClose";

	return $heading;
}

function fixture_detail_nodate($fixID,$format=0){
	if ($format==1) {
		$formatOpen="<strong>";
		$formatClose="</strong>";
	} else {
		$formatOpen=$formatClose="";
	}

	// sql for heading
	$sql = 
	"SELECT 
	fix.ID fixID,
	fix.fixtureStatusID fixst,
	e.ID as eid,
	s.ID as sid,
	fix.date, 
	fix.time,
	e.name as ename,
	spn.name as spnname,
	es.startDate AS sdate,
	es.endDate AS edate,

	t1.ID AS t1id,
	t1.name AS t1name,

	t2.ID AS t2id,
	t2.name AS t2name,

	c.name AS cname,
	c.alpha_2 AS ccode

	FROM fixtures AS fix

	INNER JOIN eventSeason AS es
	ON es.ID=fix.eventSeasonID

	INNER JOIN events AS e
	ON e.ID=es.eventsID

	INNER JOIN brands AS spn
	ON spn.ID=es.sponsorID

	INNER JOIN sports AS s
	ON s.ID=e.sportID

	INNER JOIN teamSeason AS ts1
	ON fix.homeTeamID=ts1.ID

	INNER JOIN teams AS t1
	ON ts1.teamID=t1.ID

	INNER JOIN teamSeason AS ts2
	ON fix.awayTeamID=ts2.ID

	INNER JOIN teams AS t2
	ON ts2.teamID=t2.ID

	INNER JOIN countries AS c
	ON c.id=e.countryID

	WHERE fix.ID=$fixID
	";

	$result = mysql_query($sql);
	$row = mysql_fetch_row($result);

	//$heading = "$formatOpen" . $row['1'] . " v " . $row['0'] . "$formatClose";
	$heading = "$formatOpen" . $row['11'] ." ". matchStatus_v2($row['1'],$row['0']) ." ". $row['13'] . "$formatClose";

	return $heading;
}

function fixture_home_heading($fixID){

	// sql for heading
	$sql = 
	"SELECT 
	fix.ID,
	e.ID as eid,
	s.ID as sid,

	t1.ID AS t1id,
	t1.name AS t1name,

	t2.ID AS t2id,
	t2.name AS t2name

	FROM fixtures AS fix

	INNER JOIN eventSeason AS es
	ON es.ID=fix.eventSeasonID

	INNER JOIN events AS e
	ON e.ID=es.eventsID

	INNER JOIN brands AS spn
	ON spn.ID=es.sponsorID

	INNER JOIN sports AS s
	ON s.ID=e.sportID

	INNER JOIN teamSeason AS ts1
	ON fix.homeTeamID=ts1.ID

	INNER JOIN teams AS t1
	ON ts1.teamID=t1.ID

	INNER JOIN teamSeason AS ts2
	ON fix.awayTeamID=ts2.ID

	INNER JOIN teams AS t2
	ON ts2.teamID=t2.ID

	INNER JOIN countries AS c
	ON c.id=e.countryID

	WHERE fix.ID=$fixID
	";

	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);

	$heading = $row['t1name'];

	return $heading;
}

function fixture_away_heading($fixID){

	// sql for heading
	$sql = 
	"SELECT 
	fix.ID,
	e.ID as eid,
	s.ID as sid,

	t1.ID AS t1id,
	t1.name AS t1name,

	t2.ID AS t2id,
	t2.name AS t2name

	FROM fixtures AS fix

	INNER JOIN eventSeason AS es
	ON es.ID=fix.eventSeasonID

	INNER JOIN events AS e
	ON e.ID=es.eventsID

	INNER JOIN brands AS spn
	ON spn.ID=es.sponsorID

	INNER JOIN sports AS s
	ON s.ID=e.sportID

	INNER JOIN teamSeason AS ts1
	ON fix.homeTeamID=ts1.ID

	INNER JOIN teams AS t1
	ON ts1.teamID=t1.ID

	INNER JOIN teamSeason AS ts2
	ON fix.awayTeamID=ts2.ID

	INNER JOIN teams AS t2
	ON ts2.teamID=t2.ID

	INNER JOIN countries AS c
	ON c.id=e.countryID

	WHERE fix.ID=$fixID
	";

	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);

	$heading = $row['t2name'];

	return $heading;
}

// functions that generate calculated results

// function info
function accu_ew_stake($lineCounter,$return_carryover,$mID,$stake){
	if ($lineCounter==1) {
		if ($mID==5) { // eachway market
			$return = $stake*2;
		}else{
			$return = $stake;
		}
	} elseif ($lineCounter>1) {
		$return = $return_carryover;
	}
	return $return;
}

//this function only updates single betDetail
function horse_bet_update($selid,$hsID){
	
	if ($hsID==15) {
		echo "selection " . $selid . " is a winner <br />";

		$sql_win_bets=
		"SELECT 
		bd.ID AS bdID,
		bd.betTrackerID AS btID,

		bd.selectionBoardID AS sbID,
		sb.racecardID AS rcID,
		sb.marketID AS mID,
		sb.selectionID AS selID,

		bd.sbType AS sbtype,
		bd.betStatusID AS bstatID,
		bd.returns AS returns

		FROM `betDetail` AS bd

		INNER JOIN selectionBoard_01 AS sb
		ON bd.selectionBoardID = sb.ID

		WHERE 
		bd.sbType = 1 AND 
		bd.betStatusID = 4 AND
		bd.betLinesID =1 AND
		sb.selectionID = $selid AND
		(
			sb.marketID = 3 OR
			sb.marketID = 4 OR
			sb.marketID = 5
		)";

		$result_win_bets = mysql_query($sql_win_bets);
		while($row_win_bets = mysql_fetch_assoc($result_win_bets)) {
			$bdID = $row_win_bets['bdID'];
			$btID = $row_win_bets['btID'];
			$PL = $row_win_bets['returns'];

			$update_betDetail=
			"UPDATE  `betDetail` SET  `betStatusID` =  '1', `PL` =  $PL 
			WHERE  `betDetail`.`ID` = $bdID";

			$result=mysql_query($update_betDetail);
			if($result){ 
				//updated betTracker */
			} else {
				$return = "ERROR";
			}
		}
	} elseif ($hsID==16) { // horse placed
		echo "selection " . $selid . " is placed <br />";

		//procedure for horse placed AND place market = profit
		$sql_place_bets=
		"SELECT 
		bd.ID AS bdID,

		bd.selectionBoardID AS sbID,
		sb.racecardID AS rcID,
		sb.marketID AS mID,
		sb.selectionID AS selID,

		bd.sbType AS sbtype,
		bd.betStatusID AS bstatID,
		bd.returns AS returns

		FROM `betDetail` AS bd

		INNER JOIN selectionBoard_01 AS sb
		ON bd.selectionBoardID = sb.ID

		WHERE 
		bd.sbType = 1 AND 
		bd.betStatusID = 4 AND
		sb.selectionID = $selid AND
		sb.marketID = 4
		";

		$result_place_bets = mysql_query($sql_place_bets);
		while($row_place_bets = mysql_fetch_assoc($result_place_bets)) {
			$bdid = $row_place_bets['bdID'];
			$PL = $row_place_bets['returns'];

			$update_bet=
			"UPDATE  `betDetail` SET  `betStatusID` =  '1', `PL` =  $PL 
			WHERE  `betDetail`.`ID` = $bdid";

			$result=mysql_query($update_bet);
			if($result){
				$return = "successfully updated betDetailID $bdid with horseStatusID of $hsID<br/>";
			} else {
				$return = "ERROR";
			}
		}

		//procedure for horse placed AND eachway market = part profit
		$sql_place_bets=
		"SELECT 
		bd.ID AS bdID,

		bd.ID AS bdID,

		bd.selectionBoardID AS sbID,
		sb.racecardID AS rcID,
		sb.marketID AS mID,
		sb.selectionID AS selID,

		bd.sbType AS sbtype,
		bd.betStatusID AS bstatID,
		bd.podds AS podds,
		bd.stake AS stake

		FROM `betDetail` AS bd

		INNER JOIN selectionBoard_01 AS sb
		ON bd.selectionBoardID = sb.ID

		WHERE 
		bd.sbType = 1 AND 
		bd.betStatusID = 4 AND
		sb.selectionID = $selid AND
		sb.marketID = 5
		";

		$result_place_bets = mysql_query($sql_place_bets);
		while($row_place_bets = mysql_fetch_assoc($result_place_bets)) {
			$bdid = $row_place_bets['bdID'];
			$podds = $row_place_bets['podds'];
			$stake = $row_place_bets['stake'];
			$PL = (($stake/2)*$podds)-($stake/2);

			$update_bet=
			"UPDATE  `betDetail` SET  `betStatusID` =  '1', `PL` =  $PL 
			WHERE  `betDetail`.`ID` = $bdid";

			$result=mysql_query($update_bet);
			if($result){
				$return = "successfully updated betDetailID $bdid with horseStatusID of $hsID<br/>";
			} else {
				$return = "ERROR";
			}
		}

		//procedure for horse placed AND win market = loss
		$sql_place_bets=
		"SELECT 
		bd.ID AS bdID,

		bd.selectionBoardID AS sbID,
		sb.racecardID AS rcID,
		sb.marketID AS mID,
		sb.selectionID AS selID,

		bd.sbType AS sbtype,
		bd.betStatusID AS bstatID,
		bd.stake AS stake

		FROM `betDetail` AS bd

		INNER JOIN selectionBoard_01 AS sb
		ON bd.selectionBoardID = sb.ID

		WHERE 
		bd.sbType = 1 AND 
		bd.betStatusID = 4 AND
		sb.selectionID = $selid AND
		sb.marketID = 3
		";

		$result_place_bets = mysql_query($sql_place_bets);
		while($row_place_bets = mysql_fetch_assoc($result_place_bets)) {
			$bdid = $row_place_bets['bdID'];
			$PL = 0 - $row_place_bets['stake'];

			$update_bet=
			"UPDATE  `betDetail` SET  `betStatusID` =  '2', `PL` =  $PL 
			WHERE  `betDetail`.`ID` = $bdid";

			$result=mysql_query($update_bet);
			if($result){
				$return = "successfully updated betDetailID $bdid with horseStatusID of $hsID<br/>";
			} else {
				$return = "ERROR";
			}
		}

	} elseif ($hsID==14) {
		echo "selection " . $selid . " is did not win or place <br />";

		$sql_win_bets=
		"SELECT 
		bd.ID AS bdID,

		bd.selectionBoardID AS sbID,
		sb.racecardID AS rcID,
		sb.marketID AS mID,
		sb.selectionID AS selID,

		bd.sbType AS sbtype,
		bd.betStatusID AS bstatID,
		bd.stake AS stake,
		bd.returns AS returns

		FROM `betDetail` AS bd

		INNER JOIN selectionBoard_01 AS sb
		ON bd.selectionBoardID = sb.ID

		WHERE 
		bd.sbType = 1 AND 
		bd.betStatusID = 4 AND
		sb.selectionID = $selid AND
		(sb.marketID = 3 OR
		sb.marketID = 4 OR
		sb.marketID = 5)
		";

		$result_win_bets = mysql_query($sql_win_bets);
		while($row_win_bets = mysql_fetch_assoc($result_win_bets)) {
			$bdid = $row_win_bets['bdID'];
			$PL = 0 - $row_win_bets['stake'];

			$update_bet=
			"UPDATE  `betDetail` SET  `betStatusID` =  '2', `PL` =  $PL 
			WHERE  `ID` = $bdid";

			$result=mysql_query($update_bet);
			if($result){
				$return = "successfully updated betDetailID $bdid with horseStatusID of $hsID<br/>";
			} else {
				$return = "ERROR";
			}
		}
	}
	return $return; 
}

//this function only updates accu betDetail
function horse_accu_bet_update($bdID,$key,$f_stake=0,$return_carryover=0){

	if ($key==0) {
		$stake=$f_stake;
	} elseif ($key>0) {
		$stake=$return_carryover;
	}

	$sql=
	"SELECT 
	hr.horseStatusID AS hrsID,
	sb.marketID AS mID,
	bd.odds AS odds,
	bd.podds AS podds

	FROM `betDetail` AS bd

	INNER JOIN selectionBoard_01 AS sb
	ON bd.selectionBoardID=sb.ID

	INNER JOIN horseRaces AS hr
	ON sb.selectionID = hr.ID

	WHERE 
	bd.sbType = 1 AND
	bd.ID =$bdID";

	$result = mysql_query($sql);
	$row = mysql_fetch_row($result);

	$hsID=$row['0'];
	$mID=$row['1'];
	$odds=$row['2'];
	$podds=$row['3'];
	//$return=$hsID;
	
	if ($hsID==15) {
		//echo "selection " . $selid . " is a winner <br />";
		$betStatusID=1;

		//$PL = $row_win_bets['returns'];
		if ($mID==3) { // win market
			if($return_carryover>0){
				$PL=$return_carryover=$return_carryover*$odds;
			}else{
				$return_carryover=$f_stake*$odds;
				$PL=$return_carryover;
			}
		} elseif ($mID==4) { // place market
			if($return_carryover>0){
				$PL=$return_carryover=$return_carryover*$podds;
			}else{
				$return_carryover=$f_stake*$podds;
				$PL=$return_carryover;
			}
		} elseif ($mID==5) { // each way market
			if($return_carryover>0){
				$PL=$return_carryover=(($return_carryover/2)*$odds)+(($return_carryover/2)*$podds);
			}else{
				$PL=$return_carryover=(($stake/2)*$odds)+(($stake/2)*$podds);
				
			}
		}

	}elseif ($hsID==16) { // horse placed and did not win
		if ($mID==4) { // place market
			$betStatusID=1;
			if($return_carryover>0){
				$PL=$return_carryover=$return_carryover*$podds;
			}else{
				$PL=$return_carryover=$stake*$podds;
			}
		} elseif ($mID==5) { // each way market
			$betStatusID=1;
			if($return_carryover>0){
				$PL=$return_carryover=(($return_carryover/2)*$podds);
			}else{
				$PL=$return_carryover=(($stake/2)*$podds);
			}
		} elseif ($mID==3) { // win market
			$betStatusID=2;
			$PL= $return_carryover=0;
		}
	}elseif ($hsID==14) { // horse did not win or place
		$betStatusID=2;
		$PL=$return_carryover=0;
	}

	$update_bet=
	"UPDATE  `betDetail` SET  `betStatusID` =  $betStatusID, `stake` = $stake,`PL` =  $PL 
	WHERE  `betDetail`.`ID` = $bdID";

	$result=mysql_query($update_bet);
	if($result){
		echo "successfully updated betDetailID $bdid with horseStatusID of $hsID<br/>";
	} else {
		echo "ERROR<br/>";
	}
	
	return $return_carryover; 
}

function single_bt_update($btID,$stake,$returns,$bdSID){
	$PL = $returns - $stake;
	
	$sql=
	"UPDATE `betTracker` 
	SET 
	`betStatusID` = $bdSID,
	`stake`		= $stake,
	`returns`	= $returns,
	`PL`		= $PL 
	WHERE 
	`ID`=$btID";

	$result=mysql_query($sql);

	if($result){
		//echo "successfully updated btID $btID <br />";
	} else {
		echo "btID $btID update ERROR<br/>";
	}					
}

function accu_bet_tracker_update($btID){
	//Update betTracker with betStatus and total PL
	//Count how many related bets have lost or betStatus=2
	$lCount= mysql_result(mysql_query(
    	"SELECT COUNT(`betTrackerID`) AS wCount
    	FROM `betDetail` 
    	WHERE `betTrackerID`=$btID AND `betStatusID`=2"),0);
	if ($lCount>0) { // there is at least 1 lost bet in ACCU
					
		$stake = mysql_result(mysql_query(
					"SELECT 
					`stake` FROM `betTracker` 
					WHERE `ID`=$btID"),0);
		$PL = 0 - $stake;
		$sql_update_bt =
    	"UPDATE `betTracker` 
		SET 
		`betStatusID`=2,
		`PL` = $PL
    	WHERE `ID`=$btID";
    	$result=mysql_query($sql_update_bt);
    	if($result){
    		//echo "OK";
    	}else{
    		echo "ERROR";
    	}
	}else{
		
		//count total bets in this ACCU
		$bCount= mysql_result(mysql_query(
	    	"SELECT COUNT(`betTrackerID`) AS bCount
	    	FROM `betDetail` 
	    	WHERE `betTrackerID`=$btID"),0);

		//count total WIN bets in this ACCU
	    $wCount= mysql_result(mysql_query(
	    	"SELECT COUNT(`betTrackerID`) AS wCount
	    	FROM `betDetail` 
	    	WHERE `betTrackerID`=$btID AND `betStatusID`=1"),0);

	    //count total VOID bets in this ACCU
	    $vCount= mysql_result(mysql_query(
	    	"SELECT COUNT(`betTrackerID`) AS wCount
	    	FROM `betDetail` 
	    	WHERE `betTrackerID`=$btID AND `betStatusID`=3"),0);
	    if ($bCount==$wCount+$vCount) {
	    	$tPL= mysql_result(mysql_query(
	    		"SELECT 
				SUM(`PL`) AS tPL
	    		FROM `betDetail` 
	    		WHERE `betTrackerID`=$btID AND `betStatusID`=1"),0);
			$returns= mysql_result(mysql_query(
	    		"SELECT 
				MAX(`returns`) AS returns
	    		FROM `betDetail` 
	    		WHERE `betTrackerID`=$btID AND `betStatusID`=1"),0);
	    	$sql_update_bt =
	    	"UPDATE `betTracker` 
			SET 
			`betStatusID`=1,
			`returns`=$returns,
			`PL`=$tPL  
	    	WHERE `ID`=$btID";
	    	$result=mysql_query($sql_update_bt);
	    	if($result){
	    		//echo "OK";
	    	}else{
	    		$return = "ERROR";
	    	}
	    }
	}
	if (isset($return)) {
		return $return;
	}
}

function bd_statusID($btID){

	return mysql_result(mysql_query(
		"SELECT `betStatusID` FROM `betDetail` WHERE `betTrackerID` = $btID"),0);
}

function final_fixtureIDs(){
	//returns list of pending fixtureIDs
	$sql = 
	"SELECT sb.fixtureID,
	f.fixtureStatusID

	FROM betDetail bd

	INNER JOIN selectionBoard_02 sb
	ON bd.selectionBoardID = sb.ID

	INNER JOIN fixtures f
	ON sb.fixtureID = f.ID

	WHERE sbType = 2
	AND betStatusID = 4
	AND betLinesID = 1
	AND f.fixtureStatusID IN (4,5)";
	return mysql_query($sql);
}

function football_betDetail_update($fixtureID){
	//check if football match is final
	//first return fixtureStatusID for related $fixtureID
	$result=mysql_query(
		"SELECT 
		sb.selectionID selID,
		sb.marketID mID,
		f.fixtureStatusID fsID

		FROM `selectionBoard_02` sb

		INNER JOIN fixtures f
		ON sb.fixtureID=f.ID
		WHERE sb.fixtureID = $fixtureID");

	while($row=mysql_fetch_assoc($result)){
		$fsID = $row['fsID'];
		$selID= $row['selID'];
		$mID = $row['mID'];

		if ($fsID==4) {
			//echo "$fixtureID is final so do betDetail update<br/>";
			switch ($mID) {
				case '1': // full time result
					win_draw_win($fixtureID,$selID);
					break;

				case '6': // full time double chance
					double_chance($fixtureID,$selID);
					break;

				case '7': // BTTS 
					btts($fixtureID,$selID);
					break;

				case '8': // Total goals
					total_goals($fixtureID,$selID);
					break;

				case '9': // Draw not bet
					draw_no_bet($fixtureID,$selID);
					break;

				default:
					# code...
					break;
			}
		}elseif ($fsID==5) {
			echo "$fixtureID is Void<br/>";
		}else {
			echo "$fixtureID is 1:Pending, 2:In Play or 3: Half Time<br/>";
		}
	}
}

function bd_pending($where){
	$sql=
	"SELECT 
	bd.ID AS bdID,
	bd.betTrackerID AS btID,
	bd.selectionBoardID AS sbID,

	sb.marketID AS mID,
	sb.selectionID AS selID,

	bd.sbType AS sbtype,
	bd.betStatusID AS bstatID,
	bd.odds AS odds,
	bd.stake AS stake

	FROM `betDetail` AS bd

	INNER JOIN selectionBoard_02 AS sb
	ON bd.selectionBoardID = sb.ID

	WHERE $where";
	
	return mysql_query($sql);
}


function each_way($win_odds=0,$place_odds=0,$stake=0){ 
	return ($win_odds*$stake)+($place_odds*$stake);
}

function unique_rcID($IDs){
	$query = mysql_query(
			"SELECT rc.ID AS rcID 
			FROM `selectionBoard_01` AS sb
			INNER JOIN racecards AS rc
			ON sb.racecardID=rc.ID
			WHERE 
			sb.ID in ($IDs)");
	$row_rcID = array();
	while($result = mysql_fetch_array($query)){
		$row_rcID[] = $result['rcID'];
	}
	//print_r_pre($row_rcID);
	//echo count(array_unique($row_rcID)) . "<br/>";
	//echo count($row_rcID);

	if(count(array_unique($row_rcID))<count(($row_rcID))){
		$return = TRUE;
	}else{
		$return = FALSE;
	}
	return $return;
}

function unique_fixID($IDs){
	$query = mysql_query(
			"SELECT sb.fixtureID AS fixID 
			FROM `selectionBoard_02` AS sb
			WHERE 
			sb.ID in ($IDs)");
	$row = array();
	while($result = mysql_fetch_array($query)){
		$row[] = $result['fixID'];
	}
	//print_r_pre($row_rcID);
	//echo count(array_unique($row_rcID)) . "<br/>";
	//echo count($row_rcID);

	if(count(array_unique($row))<count(($row))){
		$return = TRUE;
	}else{
		$return = FALSE;
	}
	return $return;
}

function kickoff_check_insert($fixtureID,$session_user_id){
	$result=mysql_query(
	"SELECT 1 FROM matchTime AS mtch
	WHERE mtch.fixtureID=$fixtureID 
	AND mtch.incidentID=9");

	if (mysql_fetch_row($result)) {
		// Kick Off status for $fixtureID already exists
	}else{
		$sql="INSERT INTO `matchTime`(
			`fixtureID`, 
			`incidentID`, 
			`periodID`, 
			`time`, 
			`createUserID`
			) 
		VALUES (
			'$fixtureID',
			'9',
			'1',
			'00:00:00',
			'$session_user_id'
			)";
		$result=mysql_query($sql);
		if($result){
			return "SUCCESS";
		} else {
			return "ERROR: Kick Off insert";
		}
	}
}

function halftime_check_insert($fixtureID,$session_user_id){
	$result=mysql_query(
	"SELECT 1 FROM matchTime AS mtch
	WHERE mtch.fixtureID=$fixtureID 
	AND mtch.incidentID=15");

	if (mysql_fetch_row($result)) {
		// Kick Off status for $fixtureID already exists
	}else{
		$sql="INSERT INTO `matchTime`(
			`fixtureID`, 
			`incidentID`, 
			`periodID`, 
			`time`, 
			`createUserID`
			) 
		VALUES (
			'$fixtureID',
			'15',
			'3',
			'00:00:00',
			'$session_user_id'
			)";
		$result=mysql_query($sql);
		if($result){
			return "SUCCESS";
		} else {
			return "ERROR: Half time insert";
		}
	}
}

function fulltime_check_insert($fixtureID,$session_user_id){
	$result=mysql_query(
	"SELECT 1 FROM matchTime AS mtch
	WHERE mtch.fixtureID=$fixtureID 
	AND mtch.incidentID=16");

	if (mysql_fetch_row($result)) {
		// Kick Off status for $fixtureID already exists
	}else{
		$sql="INSERT INTO `matchTime`(
			`fixtureID`, 
			`incidentID`, 
			`periodID`, 
			`time`, 
			`createUserID`
			) 
		VALUES (
			'$fixtureID',
			'16',
			'3',
			'00:00:00',
			'$session_user_id'
			)";
		$result=mysql_query($sql);
		if($result){
			return "SUCCESS";
		} else {
			return "ERROR: Full time insert";
		}
	}
}

function score_check_insert($fixtureID,$session_user_id,$htscore,$atscore,$htscoreht,$atscoreht){
	$result=mysql_query(
	"SELECT * FROM fixtureResults AS fr
	WHERE fr.fixtureID=$fixtureID");

	if (mysql_fetch_row($result)) {
            $sql="UPDATE `fixtureResults` SET
                `fixtureID`=$fixtureID,
		`htscore`=$htscore,
		`atscore`=$atscore,
		`htscoreht`=$htscoreht,
		`atscoreht`=$atscoreht,
		`modifyUserID`=$session_user_id,
		`modifyTimestamp`=$session_user_id 
		WHERE `fixtureID` = $fixtureID";
		$result=mysql_query($sql);

		if($result){

			return "SUCCESS";

		} else {

			return "ERROR: Fixture result update";         

		}

	}else{

		$sql="INSERT INTO `fixtureResults`(
			`fixtureID`, 
			`htscore`, 
			`atscore`, 
                        `htscoreht`, 
			`atscoreht`, 
			`createUserID`
			) 
		VALUES (
			'$fixtureID',
			'$htscore',
			'$atscore',
			'$htscoreht',
			'$atscoreht',
			'$session_user_id'
			)";
		
		$result=mysql_query($sql);

		if($result){

			return "SUCCESS";

		} else {

			return "ERROR: Fixture result insert";
		}
	}
}

function update_fixture_status($fixtureID){

// START check match end procedure
	$result=mysql_query(
		"SELECT 1 FROM matchTime AS mtch
		WHERE mtch.fixtureID=$fixtureID AND 
		mtch.incidentID=16");

	if (mysql_fetch_row($result)) {
		$sql="UPDATE  `fixtures` SET  `fixtureStatusID` =  '4' 
		WHERE `ID` = $fixtureID";
		$result=mysql_query($sql);
		if($result){
			// echo "successfully updated score";
			football_betDetail_update($fixtureID);
			} else {
				return "ERROR: Fixture status update";
			}
	}else{
		// echo "Match pending";
	}
// END check match end procedure


}


function count_peer_tips_01($rcID,$mID,$selID){
	$result=mysql_query(
		"SELECT COUNT(*) AS peer
		FROM `selectionBoard_01` 
		WHERE racecardID=$rcID
		AND marketID=$mID
		AND selectionID=$selID
		AND tip=1");
	$row=mysql_fetch_array($result);
	return $row['0'];
}

function count_peer_tips_02($fixID,$mID,$selID){
	$result=mysql_query(
		"SELECT COUNT(*) AS peer
		FROM `selectionBoard_02` 
		WHERE fixtureID=$fixID
		AND marketID=$mID
		AND selectionID=$selID
		AND tip=1");
	$row=mysql_fetch_array($result);
	return $row['0'];
}

function count_bd($userID=0,$marketID=0,$selID=0,$betStatusID=0,$label=0,$sdate=0,$edate=0){
	$where = "";
	$where .= ($userID != 0) ? "bt.userID = $userID" : "bt.userID != 0";
	$where .= ($marketID != 0) ? " AND sb.marketID = $marketID" : "";
	$where .= ($selID != 0) ? " AND sb.selectionID = $selID" : "";
	$where .= ($betStatusID != 0) ? " AND bt.betStatusID = $betStatusID" : "";
	//$where .= ($labelID != 0) ? " AND sb.labelID = $labelID" : "";
	$where .= ($sdate != 0) ? " AND bt.date >= $sdate" : "";
	$where .= ($edate != 0) ? " AND bt.date <= $edate" : "";

	$sql = 
	"SELECT COUNT(DISTINCT(sb.ID)) c

	FROM `betDetail` bd

	INNER JOIN betTracker bt
	ON bd.betTrackerID = bt.ID

	INNER JOIN selectionBoard_02 sb
	ON bd.selectionBoardID = sb.ID

	WHERE $where";

	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	return $row[0];
	//return $sql;

}

function score_url(){
	$url = "&addshot=0&addmatchtime=0&addlineup=0&addscore=0";
	return $url;
}

function selection_check($fixtureID,$mID,$selID,$userID,$string){

	$sql = 
	"SELECT *
	FROM betDetail bd

	INNER JOIN selectionBoard_02 sb
	ON bd.selectionBoardID=sb.ID

	WHERE fixtureID = $fixtureID
	AND userID = $userID
	AND marketID = $mID
	AND selectionID = $selID";

	$result = mysql_query($sql);
	if(mysql_num_rows($result) == 0) {
		return '<span id="noselect">' . $string . '</span>';
	} else {
		return '<span id="pending">' . $string . '</span>';
	}
}

// -------------- new bt bd update functions
function bt_update_s1(){
	$result1 = bt_query(4);
	while($row1 = mysql_fetch_assoc($result1)){
		//print_r_pre($row1);echo "<br/>";
		$btID = $row1['btID'];
		
		$debug_fields = "btID = $btID ";
		
		$result2 = bd_query($btID);
		while($row2 = mysql_fetch_assoc($result2)){
			//print_r_pre($row2);echo "<br/>";
			$bdID = $row2['bdID'];
			$sbID = $row2['sbID'];
			$sbType = $row2['sbType'];

			
			$debug_fields .= "bdID = $bdID ";
			$debug_fields .= "sbID = $sbID ";
			$debug_fields .= "sbType = $sbType ";
			
			$result3 = sb_query($sbID,$sbType);
			while($row3 = mysql_fetch_assoc($result3)){
				//print_r_pre($row3);echo "<br/>";
				$eventID = $row3['eventID'];
				$statusID = $row3['statusID'];
				$status = $row3['status'];
				$mID = $row3['mID'];
				$selID = $row3['selID'];
				
				$debug_fields .= "eventID = $eventID ";
				$debug_fields .= "mID = $mID ";
				$debug_fields .= "selID = $selID ";
				$debug_fields .= "statusID = $statusID ";
				$debug_fields .= " ($status) ";
				$debug_fields .= "<br/>";
				
				$bsID = bd_status_checker($sbType,$eventID,$statusID,$mID,$selID);
				
				//01 statusID: 14 => lost, 15 => won, 16 => placed.
				//02 statusID: 1 => pending, 4 => final, 5 => void.
				//if event is final then calculate bet status.
				
				bd_status_update_sql($bsID, $bdID);
				
				$debug_fields .= $bsID;
				$debug_fields .= "<br/>";
				
				//echo "$bdID, $bsID |";
				//echo $debug_fields;
			}
		}
	}
}

function bt_query($bsID){

	$sql = "SELECT ID btID, betLinesID blID, stake FROM `betTracker` WHERE betStatusID = $bsID";
	return mysql_query($sql);

}

function bd_query($btID){
	$sql = "SELECT ID bdID, selectionBoardID sbID, sbType 
	FROM `betDetail` bd 
	WHERE betTrackerID = $btID AND betStatusID = 4";
	return mysql_query($sql);
}

function sb_query($sbID,$sbType){
	$where = "sb.ID = $sbID";
	switch($sbType){
		case 1:
			//echo "horse";
			$select = "hr.ID eventID, ";
			$select .= "sb.marketID mID, ";
			$select .= "sb.selectionID selID, ";
			$select .= "hr.horseStatusID statusID, ";
			$select .= "hs.name status ";
			$from = " `selectionBoard_01` sb ";
			$join = "INNER JOIN horseRaces hr ON sb.selectionID = hr.ID ";
			$join .= "INNER JOIN horseStatus hs ON hr.horseStatusID = hs.ID ";
			break;
			
		case 2:
			//echo "football";
			$select = "f.ID eventID, ";
			$select .= "sb.marketID mID, ";
			$select .= "sb.selectionID selID, ";
			$select .= "f.fixtureStatusID statusID, ";
			$select .= "fs.name status ";
			$from = "`selectionBoard_02` sb ";
			$join = "INNER JOIN fixtures f ON sb.fixtureID = f.ID ";
			$join .= "INNER JOIN fixtureStatus fs ON f.fixtureStatusID = fs.ID ";
		
			break;
	}
	
	$sql = "SELECT $select FROM $from $join WHERE $where";
	return mysql_query($sql);
}

function bd_status_checker($sbType,$eventID,$statusID,$mID,$selID){
//echo "sbType: $sbType, fixID: $eventID, statusID: $statusID, mID: $mID, selID: $selID";
	switch ($sbType){
	
		case 1: //horse racing
			$rcID = $eventID;
			$hsID = $statusID; //horse status ID
			
			switch ($hsID){
			
				case 1: // Yet to run
					//echo "do nothing because horse is yet to run";
					$bsID = 4;
					break;
					
				case 2: // Non runner
					$bsID = 3;
					// change bet status to push
					break;
					
				case 14: // Finished race or lost
					$bsID = 2;
					// change bet status to lose
					break;
					
				case 15: // Winner
					if($mID == 3 || 4 || 5){
					$bsID = 1;
					}				
					break;
					
				case 16: // Horse placed. Win bet will lose. Place and EW bet will win
					if($mID == 3){
						$bsID = 2;
					} elseif($mID == 4){ // 4 = Place
						$bsID = 1;
					}  elseif($mID == 5) {  // 5 = EW
						$bsID = 1;
					}
					
					break;
			}
			break;
			
		case 2: //football
			$fixtureID = $eventID;
			$fsID = $statusID;
			if ($fsID==4) {
				//echo "$fixtureID is final so do betDetail update<br/>";
				switch ($mID) {
					case '1': // full time result
						$bsID = win_draw_win($fixtureID,$selID,$mID);
						break;

					case '6': // full time double chance
						$bsID = double_chance($fixtureID,$selID,$mID);
						break;

					case '7': // BTTS 
						$bsID = btts($fixtureID,$selID,$mID);
						break;

					case '8': // Total goals
						$bsID = total_goals($fixtureID,$selID,$mID);
						break;

					case '9': // Draw not bet
						$bsID = draw_no_bet($fixtureID,$selID,$mID);
						break;

                    case '10': // half time result
						$bsID = win_draw_win_ht($fixtureID,$selID,$mID);
						break;

                    case '11': // asian handicap
						$bsID = asian_handicap($fixtureID,$selID,$mID);
						break;

					default:
						echo "missing marketID";
						break;
						
						
				}
			}elseif ($fsID==5) {
				//echo "$fixtureID is Void<br/>";
				$bsID = 3;
			}else {
				//echo "$fixtureID is 1:Pending, 2:In Play or 3: Half Time<br/>";
				$bsID = 4;
			}
			break;
	}
	
	return $bsID;
	
}

function bd_status_update_sql($bsID, $bdID){

	$sql = "UPDATE  `betDetail` SET  `betStatusID` =  $bsID
	WHERE  `betDetail`.`ID` = $bdID";
	
	$result = mysql_query($sql);

	if($result){ 
		//echo "updated betDetail";
	} else {
		$return = "ERROR";
	}
	//echo "$sql <br/>";
}

function win_draw_win($fixtureID,$selID){

	// return fulltime scores
	$hgoals = ft_hgoals($fixtureID); 
	$agoals = ft_agoals($fixtureID);
	$tgoals = $hgoals+$agoals;

	switch ($selID) {
		case '1': // home team to win
			if($hgoals>$agoals){
				//echo "home team win procedure";
				$bsID = 1; //betStatusID = 1 or win
			} else {
				//sql returns all betDetails with losing selections
				$bsID = 2; //betStatusID = 2 or lose
			}
			break;

		case '2': // away team to win
			//echo "away team win procedure";
			if($agoals>$hgoals){ //away score is greater than home
				//echo "away score is greater than home";
				$bsID = 1; //betStatusID = 1 or win
			} else {
				//echo "away score is not greater than home";
				$bsID = 2; //betStatusID = 2 or lose
			}
			break;

		case '3': // draw
			//echo "draw procedure";
			if($hgoals==$agoals){ //away score is same as home
				$bsID = 1; //betStatusID = 1 or win
			} else {
				$bsID = 2; //betStatusID = 2 or lose
			}
			break;

		default:
			# code...
			break;
	}
	
	return $bsID;
	
}

function win_draw_win_ht($fixtureID,$selID){

	// return half time scores
	$hgoals = ht_hgoals($fixtureID); 
	$agoals = ht_agoals($fixtureID);
	$tgoals = $hgoals+$agoals;

	switch ($selID) {
		case '19': // home team to win
			if($hgoals>$agoals){
				//echo "home team win procedure";
				$bsID = 1; //betStatusID = 1 or win
			} else {
				//sql returns all betDetails with losing selections
				$bsID = 2; //betStatusID = 2 or lose
			}
			break;

		case '20': // away team to win
			//echo "away team win procedure";
			if($agoals>$hgoals){ //away score is greater than home
				//echo "away score is greater than home";
				$bsID = 1; //betStatusID = 1 or win
			} else {
				//echo "away score is not greater than home";
				$bsID = 2; //betStatusID = 2 or lose
			}
			break;

		case '21': // draw
			//echo "draw procedure";
			if($hgoals==$agoals){ //away score is same as home
				$bsID = 1; //betStatusID = 1 or win
			} else {
				$bsID = 2; //betStatusID = 2 or lose
			}
			break;

		default:
			# code...
			break;
	}
	
	return $bsID;
	
}

function double_chance($fixtureID,$selID){

	// return fulltime scores
	$hgoals = ft_hgoals($fixtureID); 
	$agoals = ft_agoals($fixtureID);
	$tgoals = $hgoals+$agoals;

	switch ($selID) {
		case '4': // home or draw: ie away win
			if($hgoals>=$agoals){
				$bsID = 1; //betStatusID = 1 or win
			} else {
				$bsID = 2; //betStatusID = 2 or lose
			}
			break;

		case '5': // away or draw: ie home win
			//echo "away team win procedure";
			if($agoals>=$hgoals){ //away score is greater than home
				//echo "away score is greater than or equal to home";
				$bsID = 1; //betStatusID = 1 or win
			} else {
				$bsID = 2; //betStatusID = 2 or lose
			}
			break;

		case '6': // home or away: ie !draw
			//echo "draw procedure";
			if($hgoals!=$agoals){ //away score is same as home
				$bsID = 1; //betStatusID = 1 or win
			} else {
				$bsID = 2; //betStatusID = 2 or lose
			}
			break;
			
		default:
			# code...
			break;
	}
	
	return $bsID;
}

function btts($fixtureID,$selID){

	// return fulltime scores
	$hgoals = ft_hgoals($fixtureID); 
	$agoals = ft_agoals($fixtureID);
	$tgoals = $hgoals+$agoals;

	switch ($selID) {

		case '7': // BTTS YES
			//echo "BTTS Procedure";
			if(($hgoals>0) && ($agoals>0)){ 

				//echo "Both teams scored";
				$bsID = 1; //betStatusID = 1 or win
			} else {
				// sql returns all betDetails with losing selections
				$bsID = 2; //betStatusID = 2 or lose
			}
			break;

		case '8': // BTTS NO
			//echo "draw procedure";
			if(($hgoals==0) && ($agoals==0)){ //both teams didn't score
				$bsID = 1; //betStatusID = 1 or win
			} else {
				// sql returns all betDetails with losing selections
				$bsID = 2; //betStatusID = 2 or lose
			}
			
			break;

		default:
			# code...
			break;
	}
	
	return $bsID;
	
}

function total_goals($fixtureID,$selID){
	//echo $fixtureID." ".$selID;
	// return fulltime scores
	$hgoals = ft_hgoals($fixtureID); 
	$agoals = ft_agoals($fixtureID);
	$tgoals = $hgoals+$agoals;
	//echo $selID;
	switch ($selID) {

		case '9': // Over 2.5 
			//echo "draw procedure";
			if($tgoals>2.5){ //home + away goals over 2.5

				$bsID = 1; //betStatusID = 1 or win
			} else {
				// sql returns all betDetails with losing selections
				$bsID = 2; //betStatusID = 2 or lose
			}
			break;

		case '10': // Under 2.5
			//echo "draw procedure";
			if($tgoals<2.5){ //away score is same as home

				$bsID = 1; //betStatusID = 1 or win
			} else {
				// sql returns all betDetails with losing selections
				$bsID = 2; //betStatusID = 2 or lose
			}
			break;

		case '13': // Over 2.0 
			//echo "draw procedure";
			if($tgoals>=3){ //home + away goals over 3

				$bsID = 1; //betStatusID = 1 or win
			} elseif($tgoals == 2){
				$bsID = 3; //betStatusID = 3 or push 
			} else {
				// sql returns all betDetails with losing selections
				$bsID = 2; //betStatusID = 2 or lose
			}
			break;

		case '14': // Under 2.0
			//echo "draw procedure";
		//echo $tgoals;
			if($tgoals<=1){ //home + away goals less than or equal to 1
				$bsID = 1; //betStatusID = 1 or win
			} elseif($tgoals == 2){
				$bsID = 3; //betStatusID = 3 or push 
			} else {
				// sql returns all betDetails with losing selections
				$bsID = 2; //betStatusID = 2 or lose
			}
			break;

		case '17': // Over 3.5 
			//echo "draw procedure";
			if($tgoals>3.5){ //home + away goals over 2.5

				$bsID = 1; //betStatusID = 1 or win
			} else {
				// sql returns all betDetails with losing selections
				$bsID = 2; //betStatusID = 2 or lose
			}
			break;

		case '18': // Under 3.5
			//echo "draw procedure";
			if($tgoals<3.5){ //away score is same as home

				$bsID = 1; //betStatusID = 1 or win
			} else {
				// sql returns all betDetails with losing selections
				$bsID = 2; //betStatusID = 2 or lose
			}
			break;

		case '15': // Over 3.0 
			//echo "draw procedure";
			if($tgoals>=4){ //home + away goals over 4
				$bsID = 1; //betStatusID = 1 or win
			} elseif($tgoals == 3){
				$bsID = 3; //betStatusID = 3 or push 
			} else {
				// sql returns all betDetails with losing selections
				$bsID = 2; //betStatusID = 2 or lose
			}
			break;

		case '16': // Under 3.0
			//echo "draw procedure";
			if($tgoals<=2){
				//away score is same as home
				$bsID = 1; //betStatusID = 1 or win
			} elseif($tgoals == 3){
				$bsID = 3; //betStatusID = 3 or push 
			} else {
				// sql returns all betDetails with losing selections
				$bsID = 2; //betStatusID = 2 or lose
			}
			break;			

		default:
			# code...
			break;
	}
	
	return $bsID;
	
}

function draw_no_bet($fixtureID,$selID){

	$hgoals = ft_hgoals($fixtureID); 
	$agoals = ft_agoals($fixtureID);
	$tgoals = $hgoals+$agoals;

	switch ($selID) {

		case '1': // home draw no bet
			//echo "home draw no bet procedure";
			if($hgoals>$agoals){

				$bsID = 1; //betStatusID = 1 or win
			} elseif($hgoals==$agoals){ //away score is same as home
				$bsID = 3; //betStatusID = 3 or void/push;
			} else {
				// sql returns all betDetails with losing selections
				$bsID = 2; //betStatusID = 2 or lose
			}
			
			break;

		case '2': // away draw no bet
			//echo "away team win procedure";
			if($agoals>$hgoals){ //away score is greater than home
				//echo "away score is greater than home";
				$bsID = 1; //betStatusID = 1 or win
			} elseif($hgoals==$agoals){ //away score is same as home
				$bsID = 3; //betStatusID = 3 or void/push;
			} else {
				// sql returns all betDetails with losing selections
				$bsID = 2; //betStatusID = 2 or lose
			}
			
			break;

		default:
			# code...
			break;
	}
	
	return $bsID;
	
}

function asian_handicap($fixtureID,$selID){

	// return fulltime scores
	$hgoals = ft_hgoals($fixtureID); 
	$agoals = ft_agoals($fixtureID);
	$hdiff = $hgoals-$agoals;
	$adiff = $agoals-$hgoals;

	switch ($selID) {

		case '64': // Home - 2.00
			if ($hdiff > 3) {
				$bsID = 1;
			} elseif ($hdiff == 2) {
				$bsID = 3; // push
			} else {
				$bsID = 2;
			}
			break;

		case '63': // Home - 1.75

			if ($hdiff > 3 ) {
				$bsID = 1;
			} elseif ($hdiff == 2) {
				$bsID = 5; // win half
			} else {
				$bsID = 2;
			}
			break;

		case '62': // Home - 1.50

			if($hdiff > 2){
				$bsID = 1; // win
			} else {
				$bsID = 2; // lose
			}
			break;

		case '61': // Home - 1.25

			if ($hdiff > 2 ) {
				$bsID = 1;
			} elseif ($hdiff == 1) {
				$bsID = 6; // lose half
			} else {
				$bsID = 2;
			}
			break;

		case '60': // Home - 1.00
			if ($hdiff > 2) {
				$bsID = 1;
			} elseif ($hdiff == 1) {
				$bsID = 3; // push
			} else {
				$bsID = 2;
			}
			break;

		case '59': // Home - 0.75

			if ($hdiff > 2 ) {
				$bsID = 1;
			} elseif ($hdiff == 1) {
				$bsID = 5; // win half
			} else {
				$bsID = 2;
			}
			break;

		case '58': // Home - 0.50

			if($hdiff > 1){
				$bsID = 1; // win
			} else {
				$bsID = 2; // lose
			}
			break;

		case '57': // Home - 0.25

			if ($hdiff > 1 ) {
				$bsID = 1;
			} elseif ($hdiff == 0) {
				$bsID = 6; // lose half
			} else {
				$bsID = 2;
			}
			break;

		case '56': // Home 0

			if ($hdiff > 1 ) {
				$bsID = 1;
			} elseif ($hdiff == 0) {
				$bsID = 3; // push
			} else {
				$bsID = 2;
			}
			break;		

		case '55': // Home + 0.25
			if ($hdiff > 1 ) {
				$bsID = 1;
			} elseif ($hdiff == 0) {
				$bsID = 5; // win half
			} else {
				$bsID = 2;
			}
			break;

		case '54': // Home + 0.50

			if($hdiff >= 0){
				$bsID = 1; // win
			} else {
				$bsID = 2; // lose
			}
			break;

		case '53': // Home + 0.75

			if ($hdiff >= 0 ) {
				$bsID = 1;
			} elseif ($hdiff == -1) {
				$bsID = 6; // lose half
			} else {
				$bsID = 2;
			}
			break;

		case '52': // Home + 1.00
			if ($hdiff >= 0) {
				$bsID = 1;
			} elseif ($hdiff == -1) {
				$bsID = 3; // push
			} else {
				$bsID = 2;
			}
			break;

		case '51': // Home + 1.25
			if ($hdiff >=0 ) {
				$bsID = 1;
			} elseif ($hdiff ==  -1) {
				$bsID = 5; // win half
			} else {
				$bsID = 2;
			}
			break;

		case '50': // Home + 1.50

			if($hdiff >= -1){
				$bsID = 1; // win
			} else {
				$bsID = 2; // lose
			}
			break;

		case '49': // Home + 1.75

			if ($hdiff >= -1 ) {
				$bsID = 1;
			} elseif ($hdiff == -2) {
				$bsID = 6; // lose half
			} else {
				$bsID = 2;
			}
			break;

		case '48': // Home + 2.00
			if ($hdiff >= -1) {
				$bsID = 1;
			} elseif ($hdiff == -2) {
				$bsID = 3; // push
			} else {
				$bsID = 2;
			}
			break;

			// Away Asian Handicap

		case '97': // Away - 2.00
			if ($hdiff > 3) {
				$bsID = 1;
			} elseif ($hdiff == 2) {
				$bsID = 3; // push
			} else {
				$bsID = 2;
			}
			break;

		case '96': // Away - 1.75

			if ($hdiff > 3 ) {
				$bsID = 1;
			} elseif ($hdiff == 2) {
				$bsID = 5; // win half
			} else {
				$bsID = 2;
			}
			break;

		case '95': // Away - 1.50

			if($hdiff > 2){
				$bsID = 1; // win
			} else {
				$bsID = 2; // lose
			}
			break;

		case '94': // Away - 1.25

			if ($hdiff > 2 ) {
				$bsID = 1;
			} elseif ($hdiff == 1) {
				$bsID = 6; // lose half
			} else {
				$bsID = 2;
			}
			break;

		case '93': // Away - 1.00
			if ($hdiff > 2) {
				$bsID = 1;
			} elseif ($hdiff == 1) {
				$bsID = 3; // push
			} else {
				$bsID = 2;
			}
			break;

		case '92': // Away - 0.75

			if ($hdiff > 2 ) {
				$bsID = 1;
			} elseif ($hdiff == 1) {
				$bsID = 5; // win half
			} else {
				$bsID = 2;
			}
			break;

		case '91': // Away - 0.50

			if($hdiff > 1){
				$bsID = 1; // win
			} else {
				$bsID = 2; // lose
			}
			break;

		case '90': // Away - 0.25

			if ($hdiff > 1 ) {
				$bsID = 1;
			} elseif ($hdiff == 0) {
				$bsID = 6; // lose half
			} else {
				$bsID = 2;
			}
			break;

		case '89': // Away 0

			if ($hdiff > 1 ) {
				$bsID = 1;
			} elseif ($hdiff == 0) {
				$bsID = 3; // push
			} else {
				$bsID = 2;
			}
			break;		

		case '88': // Away + 0.25
			if ($hdiff > 1 ) {
				$bsID = 1;
			} elseif ($hdiff == 0) {
				$bsID = 5; // win half
			} else {
				$bsID = 2;
			}
			break;

		case '87': // Away + 0.50

			if($hdiff >= 0){
				$bsID = 1; // win
			} else {
				$bsID = 2; // lose
			}
			break;

		case '86': // Away + 0.75

			if ($hdiff >= 0 ) {
				$bsID = 1;
			} elseif ($hdiff == -1) {
				$bsID = 6; // lose half
			} else {
				$bsID = 2;
			}
			break;

		case '85': // Away + 1.00
			if ($hdiff >= 0) {
				$bsID = 1;
			} elseif ($hdiff == -1) {
				$bsID = 3; // push
			} else {
				$bsID = 2;
			}
			break;

		case '84': // Away + 1.25
			if ($hdiff >=0 ) {
				$bsID = 1;
			} elseif ($hdiff ==  -1) {
				$bsID = 5; // win half
			} else {
				$bsID = 2;
			}
			break;

		case '83': // Away + 1.50

			if($hdiff >= -1){
				$bsID = 1; // win
			} else {
				$bsID = 2; // lose
			}
			break;

		case '82': // Away + 1.75

			if ($hdiff >= -1 ) {
				$bsID = 1;
			} elseif ($hdiff == -2) {
				$bsID = 6; // lose half
			} else {
				$bsID = 2;
			}
			break;

		case '81': // Away + 2.00
			if ($hdiff >= -1) {
				$bsID = 1;
			} elseif ($hdiff == -2) {
				$bsID = 3; // push
			} else {
				$bsID = 2;
			}
			break;

		default:
			# code...
			break;
	}
	
	return $bsID;
	
}

function bd_query_s2($btID,$orderby=0){

	$orderby = ($orderby == 0) ? '' : 'ORDER BY' . $orderby;

	$sql = "SELECT ID bdID, lineNo, betStatusID bsID, odds, podds, stake  
	FROM `betDetail` bd 
	WHERE betTrackerID = $btID
	$orderby";
	return mysql_query($sql);
}

function return_calc($bsID,$odds,$podds,$stake){
	$array = array();
	
	switch ($bsID){
	
	case 1: // win
		$array[] = $returns = $stake * ($odds + $podds); // returns
		$array[] = $returns - $stake; // PL
		break;
		
	case 2: // lose
		$array[]  = $returns = 0; // returns
		$array[] = $returns - $stake; // PL
		break;
		
	case 3: // push
		$array[] = $returns = $stake; // returns
		$array[] = $returns - $stake; // PL
		break;
		
	case 4: // pending
		$array[] = 0; // returns
		$array[] = 0; // PL
		break;

	case 5: // win half
		$array[] = $returns = (($stake / 2) * ($odds + $podds)) + ($stake / 2); // returns
		$array[] = $returns - $stake; // PL
		break;
		
	case 6: // lose half
		$array[]  = $returns = $stake / 2; // returns
		$array[] = $returns - $stake; // PL
		break;	
	}
	
	return $array;
	
}

function bd_update_s2_sql($returns, $PL, $bdID){

	$sql = "UPDATE  `betDetail` SET  `returns` =  $returns, `PL` =  $PL
	WHERE  `betDetail`.`ID` = $bdID";
	
	$result = mysql_query($sql);

	if($result){ 
		//echo "updated betDetail";
	} else {
		$return = "ERROR";
	}
	//echo "$sql <br/>";
}

function accu_bd_update_s2_sql($stake, $returns, $PL, $bdID){

	$sql = "UPDATE  `betDetail` SET  `stake` =  $stake,`returns` =  $returns, `PL` =  $PL
	WHERE  `betDetail`.`ID` = $bdID";
	
	$result = mysql_query($sql);

	if($result){ 
		//echo "updated betDetail";
	} else {
		$return = "ERROR";
	}
	//echo "$sql <br/>";
}

function bt_update_s2_sql($returns, $PL, $bsID, $btID){

	$sql = "UPDATE  `betTracker` SET  `returns` =  $returns, `PL` =  $PL, `betStatusID` = $bsID
	WHERE  `betTracker`.`ID` = $btID";
	
	$result = mysql_query($sql);

	if($result){ 
		//echo "updated betDetail";
	} else {
		$return = "ERROR";
	}
	//echo "$sql <br/>";
}

function acca_bt_update_s2_sql($combined_odds,$returns, $PL, $bsID, $btID){

	$sql = "UPDATE  `betTracker` SET  `odds` = $combined_odds, `returns` =  $returns, `PL` =  $PL, `betStatusID` = $bsID
	WHERE  `betTracker`.`ID` = $btID";
	
	$result = mysql_query($sql);

	if($result){ 
		//echo "updated betDetail";
	} else {
		$return = "ERROR";
	}
	//echo "$sql <br/>";
}

function single_bet_update($btID){
	$result2 = bd_query_s2($btID);
	while($row2 = mysql_fetch_assoc($result2)){
		//print_r_pre($row2);echo "<br/>";
		$bdID = $row2['bdID'];
		$bsID = $row2['bsID'];
		$odds = $row2['odds'];
		$podds = $row2['podds'];
		$stake = $row2['stake'];	

		$debug_fields = "bdID = $bdID ";
		$debug_fields .= "bsID = $bsID ";
		
		$debug_fields .= "odds = $odds ";
		$debug_fields .= "podds = $podds ";
		$debug_fields .= "stake = $stake ";
		
		$return_calc = return_calc($bsID,$odds,$podds,$stake);
							
		$returns = $return_calc[0];
		
		$PL = $return_calc[1];
		
		$debug_fields .= "returns = $returns ";
		$debug_fields .= "PL = $PL ";
		
		bd_update_s2_sql($returns, $PL, $bdID);
		bt_update_s2_sql($returns, $PL, $bsID, $btID);
		//echo $debug_fields;
	}
}

function check_accu_bd_status($btID,$bsID){
	$result = mysql_query("SELECT ID FROM `betDetail` bd WHERE betTrackerID = $btID AND betStatusID = $bsID");
	if(mysql_num_rows($result) == 0) {
		 // row not found, do stuff...
	} else {
		return $bsID;
	}
}

function acca_bd_update($btID,$bt_stake){
	$carryover = 0;
	$combined_odds = 0;
	$result = bd_query_s2($btID," lineNo ASC");
	while($row = mysql_fetch_assoc($result)){
		$bdID = $row['bdID'];
		$lineNo = $row['lineNo'];
		$bsID = $row['bsID'];
		$odds = $row['odds'];
		$podds = $row['podds'];
		
		$stake = ($lineNo == 1) ? $bt_stake : $carryover;
				
		if($bsID == 1) {
			$carryover = $returns = ($odds + $podds)*$stake;
			$PL = $returns - $stake;
			
		} elseif($bsID == 2) {
			$returns = 0;
			$PL = $returns - $stake;
		} elseif ($bsID == 3) {
			$carryover = $returns = $stake;
			$PL = $returns - $stake;
		} elseif ($bsID == 5) {
			$carryover = $returns = (($stake / 2) * ($odds + $podds)) + ($stake / 2); // returns
			$PL = $returns - $stake; // PL
		} elseif ($bsID == 6) {
			$carryover  = $returns = $stake / 2; // returns
			$PL = $returns - $stake; // PL
		}
		
		accu_bd_update_s2_sql($stake, $returns, $PL, $bdID);
	}
	$PL = $returns - $bt_stake;
	$combined_odds = $returns / $bt_stake;
	acca_bt_update_s2_sql($combined_odds,$returns, $PL, 1, $btID);
}

function bt_update_s2(){
	$result1 = bt_query(4);
	while($row1 = mysql_fetch_assoc($result1)){
		//print_r_pre($row1);echo "<br/>";
		$btID = $row1['btID'];
		$blID = $row1['blID'];
		$bt_stake = $row1['stake'];
		$debug_fields = "btID = $btID ";
		$debug_fields .= "blID = $blID ";
		
		
		switch ($blID){
		
			case 1:
				//single bet
				single_bet_update($btID);
				break;
				
			case 2:
				//accu bet
				if(check_accu_bd_status($btID,4) == 4){
					//echo "There is a pending bet in this acca";
				} elseif (check_accu_bd_status($btID,2) == 2) {
					//echo "There is a losing bet in this acca";
					$returns = 0;
					$PL = $returns - $bt_stake;
					acca_bd_update($btID,$bt_stake);
					bt_update_s2_sql($returns, $PL, 2, $btID);
					
				} else{
					//echo "win or void";
					acca_bd_update($btID,$bt_stake);
				}
				break;
				
			case 3:
				//trixie bet
				break;
		
		}
	}
	
}
// -------------- new bt bd update functions

function betStatus($bsID,$option=0){
	$s = "SELECT name FROM `betStatus` WHERE ID = $bsID";
	$r = mysql_query($s);
	if (mysql_num_rows($r) > 0) {
		return mysql_result($r, 0);
	}
}

function version(){

	return mysql_result(mysql_query("SELECT v.number FROM `release` r INNER JOIN version v ON r.versionID = v.ID ORDER BY v.ID DESC"), 0);

}

function get_rdetail($rnID){

	return mysql_result(mysql_query("SELECT details FROM `release` WHERE ID = $rnID"), 0);

}

function get_sbID($userID,$fixID,$mID,$selID){

  return mysql_result(mysql_query("SELECT `ID` FROM `selectionBoard_02` 
  WHERE `userID` = '$userID'
  AND `fixtureID` = '$fixID'
  AND `marketID` = '$mID'
  AND `selectionID` = '$selID'"), 0);
}

function get_bk_stake($bkID,$uID=0){
	return mysql_result(mysql_query("SELECT SUM(stake) FROM `betTracker` WHERE bookieID = $bkID AND userID = $uID"),0);
}

function get_tbk_stake($uID=0){
	$total = 0;
    $sql = "SELECT ID FROM userBookies WHERE userID = $uID";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
        $bkID = $row['ID'];
        $total += get_bk_stake($bkID,$uID);
    }
    return $total;
}

function get_bk_returns($bkID,$uID=0){
	return mysql_result(mysql_query("SELECT SUM(returns) FROM `betTracker` WHERE bookieID = $bkID AND userID = $uID"),0);
}

function get_tbk_returns($uID=0){
	$total = 0;
    $sql = "SELECT ID FROM userBookies WHERE userID = $uID";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
        $bkID = $row['ID'];
        $total += get_bk_returns($bkID,$uID);
    }
    return $total;
}

function get_bk_PL($bkID,$uID=0){
	return mysql_result(mysql_query("SELECT SUM(PL) FROM `betTracker` WHERE bookieID = $bkID AND userID = $uID"),0);
}

function get_tbk_PL($uID=0){
	$total = 0;
    $sql = "SELECT ID FROM userBookies WHERE userID = $uID";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
        $bkID = $row['ID'];
        $total += get_bk_PL($bkID,$uID);
    }
    return $total;
}

function get_book_trans($bkID,$uID=0){
	$in = mysql_result(mysql_query("SELECT SUM(`in`) FROM `book_trans` WHERE bookieID = $bkID AND createUserID = $uID"),0);
    $out = mysql_result(mysql_query("SELECT SUM(`out`) FROM `book_trans` WHERE bookieID = $bkID AND createUserID = $uID"),0);
    return $in - $out;        
}

function get_tbk_trans($uID=0){
	$total = 0;
    $sql = "SELECT ID FROM userBookies WHERE userID = $uID";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
        $bkID = $row['ID'];
        $total += get_book_trans($bkID,$uID);
    }
    return $total;
}



function get_user_bank($ubnkID){
    return mysql_result(mysql_query("SELECT bnk.name FROM user_bank ubnk INNER JOIN bank bnk ON ubnk.bankID = bnk.ID WHERE ubnk.ID = $ubnkID"),0);
}

function get_user_bookie($ubkID){
    return mysql_result(mysql_query("SELECT bk.name FROM userBookies ubk INNER JOIN bookies bk ON ubk.bookieID = bk.ID WHERE ubk.ID = $ubkID"),0);
}

function get_bank_bal($bnkID,$uID=0){
	$in = mysql_result(mysql_query("SELECT SUM(`in`) FROM `bank_trans` WHERE bankID = $bnkID AND createUserID = $uID"),0);
	$out = mysql_result(mysql_query("SELECT SUM(`out`) FROM `bank_trans` WHERE bankID = $bnkID AND createUserID = $uID"),0);
    return $in - $out;
}

function get_total_bank_bal($uID){
    $total = 0;
    $sql = "SELECT bankID FROM user_bank WHERE userID = $uID";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
        $bnkID = $row['bankID'];
        $total += get_bank_bal($bnkID, $uID);
    }
    return $total;
}

function get_book_bal($bkID,$uID){
    $total = 0;
    $total += get_bk_PL($bkID,$uID);
    $total += get_book_trans($bkID,$uID);
    return $total;
}

function get_total_book_bal($uID){
    return get_tbk_PL($uID) + get_tbk_trans($uID);
}

function get_bank_label($uID){
	return mysql_result(mysql_query("SELECT SUM(bank) FROM `labels` WHERE active = 1 AND createUserID = $uID"),0);
}

function get_bank_label_value($lID){
	return mysql_result(mysql_query("SELECT bank FROM `labels` WHERE ID = $lID"), 0);
}

function get_label_sum($lID,$uID=0){
	return mysql_fetch_assoc(mysql_query("SELECT 
									SUM(bt.stake) lstake,
									SUM(bt.returns) lreturns,
									SUM(bt.PL) lpl
									FROM `betTracker` bt 
									INNER JOIN betDetail bd
									ON bd.betTrackerID = bt.ID
									INNER JOIN selectionBoard_02 sb
									ON bd.selectionBoardID = sb.ID
									WHERE sb.labelID = $lID AND sb.userID = $uID"),0);
}

function get_stakingID($lID){
	return mysql_result(mysql_query("SELECT stakingID FROM `labels` WHERE ID = $lID"), 0);
}

function get_betStatusID_last($userID){
	return mysql_result(mysql_query("SELECT betStatusID FROM `betTracker` WHERE userID = $userID 
									ORDER BY ID DESC LIMIT 1"), 0);
}

function get_usID($lID){
	return mysql_result(mysql_query("SELECT usID FROM `labels` WHERE ID = $lID"), 0);
}

function get_staking_param($usID){
	return mysql_fetch_assoc(mysql_query("SELECT param1,param2,param3,param4 FROM `user_staking` WHERE ID = $usID"), 0);
}

function get_PL_label($lID){
	return mysql_result(mysql_query(
		"SELECT SUM(PL) FROM betDetail bd
		INNER JOIN selectionBoard_02 sb
		ON bd.selectionBoardID = sb.ID
		WHERE sb.labelID = $lID"
		),0);
}

function get_market($mID){
	return mysql_result(mysql_query("SELECT name FROM `markets` WHERE ID = $mID"), 0);
}

function get_market_abbr($mID){
	return mysql_result(mysql_query("SELECT abbr FROM `markets` WHERE ID = $mID"), 0);
}

function nice_date($datetime,$format){

	return date($format,strtotime($datetime));
}

function x_date($plusminus,$dmy){
	$insert = "$plusminus $dmy";
	return date('Y-m-d', strtotime($insert));
}

function timestamp(){
	return date("Y-m-d H:i:s");
}


function get_betStatus_class($bstID){
	switch ($bstID) {
		case '1':
			return "win";
			break;
		case '2':
			return "lose";
			break;		
		case '3':
			return "push";
			break;
		case '4':
			return "pending";
			break;			
	}
}

function get_betStatus_class_v2($bstID){
	switch ($bstID) {
		case '1':
			return "success";
			break;
		case '2':
			return "danger";
			break;		
		case '3':
			return "warning";
			break;
		case '4':
			return "info";
			break;			
	}
}

function get_bt_stats($bt_where){
	$sRate = $gPLm = $minodds = $avgodds = $avgPL = $gPL = $tStake = $rPL = $stake = $todds = $tBets = $tBetsW = $tBetsL = $tBetsPn = $tBetsPu = $tBetsSingle = $tBetsACCU = '';
	$result_bt = bt_table($bt_where);

	$gReturns = 0;
	while($row = mysql_fetch_assoc($result_bt)) {
		$bstID = $row['bstID'];
		$stake = number_format($row['stake'],2);
		$odds = $row['odds'];
		$returns = number_format($row['returns'],2);
		$bkname =$row['bkname'];
		$blname =$row['blname'];
		$btlID =$row['btlID'];
		$btlname =$row['btlname'];

		$date = $row['date'];
		$time = $row['time'];

		$bstname = $row['bstname'];

		$PL = number_format($row['PL'],2);
		$ROI = number_format(($PL / $stake)*100,1);
		$gReturns += number_format($returns,2);
		
		$rPL = number_format($rPL + $PL,2);

		$tStake += $stake;
		$todds += $odds;
		$tBets++ ;

		
		$tBetsW += ($bstID == 1) ? 1 : 0;
		$tBetsL += ($bstID == 2) ? 1 : 0;
		$tBetsPn += ($bstID == 4) ? 1 : 0;
		$tBetsPu += ($bstID == 3) ? 1 : 0;
		
		$tBetsSingle = ($row['btlines'] == 1) ? $tBetsSingle + 1: $tBetsSingle + 0;
		$tBetsACCU = ($row['btlines'] == 2) ? $tBetsACCU + 1: $tBetsACCU + 0;


		$btID = $row['btID'];

	}

	if ($tStake) {
		$gPL = $rPL;
		$gPLm = round(($gPL/$gReturns)*100,2);
		$sRate = (($tBetsW != ($tBets-$tBetsPu-$tBetsPn))) ? number_format(($tBetsW/($tBets-$tBetsPu-$tBetsPn))*100,2) : 100;
		$avgPL = round($gPL/$tBets,2);
		$avgodds = round($todds/$tBets,3);
		$minodds = number_format(1/($sRate/100),3);
	}

	return array(
		'gReturns' => $gReturns, 
		'tStake' => number_format((double)$tStake,2), 
		'gPL' => $gPL, 
		'avgPL' => $avgPL, 
		'avgodds' => $avgodds, 
		'minodds' => $minodds, 
		'gPLm' => $gPLm, 
		'tBets' => $tBets, 
		'tBetsW' => $tBetsW, 
		'tBetsL' => $tBetsL, 
		'tBetsPn' => $tBetsPn, 
		'tBetsPu' => $tBetsPu, 
		'sRate' => $sRate, 
		);

}

function get_teamsID($name){
	$result = mysql_query("SELECT `ID` FROM `teams` WHERE `name` LIKE '$name'");
	if (mysql_num_rows($result) > 0) {
		return mysql_result($result, 0);
	} else {
		return '0';
	}
}

function get_cID($esID){
	return mysql_result(mysql_query("SELECT e.countryID FROM `eventSeason` es INNER JOIN events e ON e.ID = es.eventsID WHERE es.ID =  $esID"),0);
}

function get_tsID($tID,$esID){
	$sql = "SELECT `ID` FROM `teamSeason` WHERE `teamID` = $tID AND eventSeasonID = $esID";
	$result = mysql_query($sql);
	if (mysql_num_rows($result) > 0) {
		return mysql_result($result, 0);
	} else {
		return '0';
	}
}

function check_selection_exist($session_user_id,$fixtureID,$marketID,$selectionID,$lID=0){
	$sql="SELECT 1 FROM `selectionBoard_02` 
	WHERE `userID` = '$session_user_id'
	AND `fixtureID` = '$fixtureID'
	AND `marketID` = '$marketID'
	AND `selectionID` = '$selectionID'";

	$result = mysql_query($sql);
	return mysql_fetch_row($result);
}

function get_selection_ID($session_user_id,$fixtureID,$marketID,$selectionID,$lID=0){
	$s="SELECT ID FROM `selectionBoard_02` 
	WHERE `userID` = '$session_user_id'
	AND `fixtureID` = '$fixtureID'
	AND `marketID` = '$marketID'
	AND `selectionID` = '$selectionID'";
	$r = mysql_query($s);
	return mysql_result($r, 0);
}

function add_team($name,$cID,$sID,$createUserID){

	$sql="INSERT INTO teams (name,countryID,sportID,createUserID) VALUES ('$name',$cID,$sID,$createUserID)";
		$result=mysql_query($sql);		
	if($result){
		return mysql_insert_id();
	} else {
		return "error";
	}
}

function add_teamSeason($teamID,$esID,$managerID=250,$captainID=250,$kitID=8,$sponsorID=8,$verified=0,$createUserID){

	$sql="INSERT INTO teamSeason (teamID,eventSeasonID,managerID,captainID,kitID,sponsorID,verified,createUserID) 
		VALUES ($teamID,$esID,$managerID,$captainID,$kitID,$sponsorID,$verified,$createUserID)";
		$result=mysql_query($sql);		
	if($result){
		return mysql_insert_id();
	} else {
		return "error";
	}
}

function add_fix($date,$time,$esID,$htsID,$atsID,$fixstID=1,$verified=0,$createUserID){
	$datetime = $date . ' ' . $time;
	$datetime = write_datetime($datetime);

	$array = high_low_datetime($datetime);
	$low = $array['low'];
	$high = $array['high'];

	$sql="INSERT INTO fixtures (`datetime`, `eventSeasonID`, `homeTeamID`, `awayTeamID`, `fixtureStatusID`, `verified`, `createUserID`) 
		VALUES ('$datetime', $esID, $htsID, $atsID,$fixstID,$verified,$createUserID)";
	if (fix_exist_check($datetime,$esID,$htsID,$atsID)) {
		return 'exists';
	} else {
		$result=mysql_query($sql);		
		if($result){
			return mysql_insert_id();
		} else {
			return $sql;
		}		
	}
}

function fix_exist_check($datetime,$esID,$htsID,$atsID){
	$array = high_low_datetime($datetime);
	$low = $array['low'];
	$high = $array['high'];	

	$s = "SELECT * FROM fixtures
		WHERE eventSeasonID = $esID 
		AND homeTeamID = $htsID
		AND awayTeamID = $atsID
		AND datetime between '$low' and '$high'";
	$r = mysql_query($s);
	if (mysql_num_rows($r)) {
		return true;
	} else {
		return false;
	}
}


function add_selection($session_user_id,$fixtureID,$marketID,$selectionID,$lID,$odds,$sID,$eventID,$bookieID){

	if(check_selection_exist($session_user_id,$fixtureID,$marketID,$selectionID)) {
		$sbID = get_selection_ID($session_user_id,$fixtureID,$marketID,$selectionID,$lID=0);
	}else{
		$sql="INSERT INTO selectionBoard_02 (userID, fixtureID, marketID,selectionID,labelID)
		VALUES ('$session_user_id', '$fixtureID','$marketID','$selectionID',$lID)";
		$result=mysql_query($sql);
		if ($result) {
			$sbID = mysql_insert_id();
		} else {
			return $sql;
		}
	}
	$oddsStatus = add_odds($odds,$sID,$eventID,$marketID,$selectionID,$bookieID,$session_user_id);
	return array("sbID" => $sbID, "oddsStatus" => $oddsStatus);	
}


function check_odds_exist($odds,$sID,$eventID,$marketID,$selectionID,$bookieID,$session_user_id){

	$sql="SELECT 1 FROM `odds` 
	WHERE `odds` = '$odds'
	AND `sID` = '$sID'
	AND `eventID` = '$eventID'
	AND `marketID` = '$marketID'
	AND `selectionID` = '$selectionID'
	AND `bookieID` = '$bookieID'
	AND `createUserID` = '$session_user_id'";

	$result = mysql_query($sql);

	return mysql_fetch_row($result);	
}


function add_odds($odds,$sID,$eventID,$marketID,$selectionID,$bookieID,$session_user_id){

	if(check_odds_exist($odds,$sID,$eventID,$marketID,$selectionID,$bookieID,$session_user_id)) {
		return "SUCCESS";
	}else{
		$sql="INSERT INTO odds (odds,sID,createUserID, eventID, marketID,selectionID,bookieID)
		VALUES ($odds,$sID,'$session_user_id', '$eventID','$marketID','$selectionID',$bookieID)";
		$result=mysql_query($sql);
		
		if($result){
			return "SUCCESS";
		} else {
			return $sql;
		}	
	}
}

//function get_odds_v2($fID, $mID, $selID, $uID){
//	echo $sql = "SELECT o.odds, o.bookieID FROM `odds` o WHERE o.eventID =  $fID AND o.marketID = $mID AND o.selectionID = $selID AND o.createUserID = $uID";
//	return mysql_result(mysql_query($sql),0);
//}

function nav($url,$array,$get,$id=0){
	$return = '<ul class="hl">';
	foreach ($array as $key => $value) {
		$return .= '<li>';
	  if (!isset($id)) {
	    $URL = $url . "&$get=$key";
	    $return .= '<a href="' . $URL  . '">' . $value .'</a>';
	   } elseif (isset($id) && $id != $key) {
	    $URL = str_replace("&$get=$id","&$get=$key",$url);
	    $return .= '<a href="' . $URL  . '">' . $value .'</a>';
	  } else {
	    $return .= "<b>$value</b>";
	  }
	  $return .= '</li>';
	}
	$return .= '</ul>';
	return $return;
}

function update_sb_labelID($labelID,$sbID){
	$s = "UPDATE `selectionBoard_02` SET `labelID` = $labelID WHERE `ID` = $sbID";
	$r = mysql_query($s);
	return ($r) ? "SUCCESS" : "ERROR";
}

function update_bt_labelID($labelID,$btID){
	$s = "UPDATE `betTracker` SET `labelID` = $labelID WHERE `ID` = $btID";
	$r = mysql_query($s);
	return ($r) ? "SUCCESS" : "ERROR";
}

?>