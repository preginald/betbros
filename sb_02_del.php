<?php require_once 'core/init.php'; ?>

<?php

function delete_selection($sbID){
	$timeStamp = date("Y-m-d H:i:s");

	$s = "UPDATE `selectionBoard_02` SET `active`=0,`modifyTimestamp`= '$timeStamp' WHERE `ID` = $sbID";
	$r = mysql_query($s);
	if($r){
		return "SUCCESS";
	} else {
		return "ERROR";
	}

}

?>

<?php 

$sbID = sanitize($_GET['sbID']);

echo delete_selection($sbID);