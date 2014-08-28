<?php

	$teamPlayerID=$_POST['teamPlayerID'];
	$lineupStatusID=$_POST['lineupStatusID'];
	$createTimestamp = date("Y-m-d H:i:s");

	if(empty($_POST['teamPlayerID'])){
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
			$pID = $_POST['pID'];	
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
				$teamPlayerID = mysql_insert_id();
				echo "successfully added player";
			} else {
				echo "ERROR";
			}
		}
	}

	$sql_add_player_to_lineup = 
	"INSERT INTO `fixtureLineup`
	(`fixtureID`, `teamPlayerID`, `lineupStatusID`, `createUserID`, `createTimestamp`) 
	VALUES 
	('$fixtureID','$teamPlayerID','$lineupStatusID','$session_user_id','$createTimestamp')";


	$result=mysql_query($sql_add_player_to_lineup);

	// end add player to database process
	// if successfully updated. 

	if($result){

		echo "successfully added player to lineup";
	
	} else {

		echo "ERROR";
	}

?>