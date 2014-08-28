<?php
require_once 'core/init.php'; 
//print_r($_GET);

if (isset($_GET['option']) && $_GET['option'] == 'add_label') {
	$name = $_GET['name'];
	$desc = $_GET['desc'];
	$stakingID = $_GET['stakingID'];
	$bank = $_GET['bank'];
	$max = $_GET['max'];

	$createTimestamp = date("Y-m-d H:i:s");

	switch ($stakingID) {
		case '1':
			$param1 = 0;
			$param2 = 0;
			$param3 = 0;
			$param4 = 0;
			break;
		
		case '2':
			$param1 = (isset($_GET['fxdParam1'])) ? $_GET['fxdParam1'] : 0;
			$param2 = (isset($_GET['fxdParam2'])) ? $_GET['fxdParam2'] : 0;
			$param3 = (isset($_GET['fxdParam3'])) ? $_GET['fxdParam3'] : 0;
			$param4 = (isset($_GET['fxdParam4'])) ? $_GET['fxdParam4'] : 0;
			break;
		
		case '3':
			$param1 = (isset($_GET['wlpParam1'])) ? $_GET['wlpParam1'] : 0;
			$param2 = (isset($_GET['wlpParam2'])) ? $_GET['wlpParam2'] : 0;
			$param3 = (isset($_GET['wlpParam3'])) ? $_GET['wlpParam3'] : 0;
			$param4 = (isset($_GET['wlpParam4'])) ? $_GET['wlpParam4'] : 0;
			break;
		

	}

	$result = mysql_query("SELECT name FROM labels WHERE name = '$name' AND createUserID = '$session_user_id'");
	if(mysql_num_rows($result) == 0 && ($bank<=$max)) {

		$sqlUS=
		"INSERT INTO `user_staking` (`stakingID`,`param1`,`param2`,`param3`,`param4`) 
		VALUES ($stakingID,'$param1','$param2','$param3','$param4')";
		$result = mysql_query($sqlUS);
		if ($result) {
			// echo "success";
			$usID = mysql_insert_id();
		}else{
			echo "ERROR: user_staking insert";
		}


		$sql=
		"INSERT INTO `labels`(`name`,`description`,`stakingID`,`usID`, `active`, `bank`, `createUserID`) 
		VALUES ('$name','$desc',$stakingID,$usID ,'1','$bank','$session_user_id')";
		$result = mysql_query($sql);
		if($result){
			echo "SUCCESS";
		} else {
			echo "ERROR: New label insert";
		}		

	} else {
	    echo "Label $name already exist in labels";
	}
}