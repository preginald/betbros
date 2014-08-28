<?php require_once 'core/init.php'; ?>
<?php
//print_r($_GET);

$sportsID = 2;
$esID = sanitize($_GET['esID']);
$fixtureID = sanitize($_GET['fixID']);
$htid = sanitize($_GET['htID']);
$atid = sanitize($_GET['atID']);

$htscore = sanitize($_GET['htscore']);
$atscore = sanitize($_GET['atscore']);
$htscoreht = sanitize($_GET['htscoreht']);
$atscoreht = sanitize($_GET['atscoreht']);

kickoff_check_insert($fixtureID,$session_user_id);
halftime_check_insert($fixtureID,$session_user_id);
fulltime_check_insert($fixtureID,$session_user_id);
$status = score_check_insert($fixtureID,$session_user_id,$htscore,$atscore,$htscoreht,$atscoreht);
update_fixture_status($fixtureID);

if($status == "SUCCESS"){
	echo $htscore . " - " . $atscore;
}