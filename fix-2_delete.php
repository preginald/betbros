<?php require_once 'core/init.php'; ?>

<?php

function delete_fixture($fixID){

	$s = "DELETE FROM `fixtures` WHERE `ID` = $fixID";
	$r = mysql_query($s);
	if($r){
		return "SUCCESS";
	} else {
		return "ERROR";
	}

}

?>

<?php 

$fixID = sanitize($_GET['fixID']);

$s = "SELECT * FROM `selectionBoard_02` WHERE `fixtureID` = $fixID";
$r = mysql_query($s);
$v = mysql_num_rows($r);

// if fixID exists in someone's selection board then we cannot delete
// fixture so it will return "ERROR" else we will perform the delete_fixture
// function and return the appropriate return string.
echo ($v) ? "ERROR" : delete_fixture($fixID);
