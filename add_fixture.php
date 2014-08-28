<?php

  $date = $_POST['date'];
  $time = $_POST['time'];
  $eventSeasonID = $_POST['eventSeasonID'];
  $homeTeamID = $_POST['homeTeamID'];
  $awayTeamID = $_POST['awayTeamID'];
  $createTimestamp = date("Y-m-d H:i:s");

  $hteamID = $_POST['hteamID'];
  $ateamID = $_POST['ateamID'];

  if (!empty($_POST['hteamID'])) {

      $addHome=1;
      echo $hteamID = $_POST['hteamID'];

  }elseif(!empty($_POST['htname'])){

    $htname=$_POST['htname'];

    $result = mysql_query(
      "INSERT INTO `teams`(
        `name`, `countryID`, `sportID`, `createUserID`, `createTimestamp`
        ) 
    VALUES (
      '$htname','$cID','$sportsID','$session_user_id','$createTimestamp')");
    if ($result) {

      $addHome=1;
      $hteamID=mysql_insert_id();
      // echo "Added $tname";

    } else {
      echo "ERROR: add new team name";
    }
  }

  if ($addHome==1) {
      $sql_add_team = 
      "INSERT INTO `teamSeason`
          (`teamID`, `eventSeasonID`, `managerID`, `captainID`, `kitID`,`sponsorID`,`verified`,`createUserID`, `createTimestamp`) 
      VALUES  ('$hteamID','$eventSeasonID','250','250', '8','8',0,'$session_user_id','$createTimestamp')";

      $result=mysql_query($sql_add_team);

      if($result){
        $homeTeamID=mysql_insert_id();
        // echo "successfully added team";

      } else {

        echo "ERROR: add new teamSeason";
      }
  }


  if (!empty($_POST['ateamID'])) {

      $addAway=1;
      echo $ateamID = $_POST['ateamID'];

  }elseif(!empty($_POST['atname'])){
    $atname=$_POST['atname'];

    $sql = 
    $result = mysql_query(
      "INSERT INTO `teams`(
        `name`, `countryID`, `sportID`, `createUserID`, `createTimestamp`
        ) 
    VALUES (
      '$atname','$cID','$sportsID','$session_user_id','$createTimestamp')");
    if ($result) {

      $addAway=1;
      $ateamID=mysql_insert_id();
      // echo "Added $tname";

    } else {
      echo "ERROR: add new team name";
    }
  }

  if ($addAway==1) {
    $sql_add_team = 
      "INSERT INTO `teamSeason`
          (`teamID`, `eventSeasonID`, `managerID`, `captainID`, `kitID`,`sponsorID`,`verified`,`createUserID`, `createTimestamp`) 
      VALUES  ('$ateamID','$eventSeasonID','250','250', '8','8',0,'$session_user_id','$createTimestamp')";

      $result=mysql_query($sql_add_team);

      if($result){
        $awayTeamID=mysql_insert_id();
        // echo "successfully added team";

      } else {

        echo "ERROR: add new teamSeason";
      }
  }

  // check that homeTeamID and awayTeamID are not the same
  if($homeTeamID==$awayTeamID){
    echo "Home Team and Away team cannot be the same! . $homeTeamID $awayTeamID";
  } else {
    // check if fixture already exists
    $sql_check_fixture = "SELECT * FROM `fixtures` 
    WHERE `date` = $date AND 
    `eventSeasonID` = $eventSeasonID AND 
    `homeTeamID` = $homeTeamID AND
    `awayTeamID` = $awayTeamID";

    $result=mysql_query($sql_check_fixture);
    if(mysql_fetch_row($result)) {

      echo "This fixture already exists!";

    } else {

      $sql_add_fixture = 
      "INSERT INTO `fixtures`
              (`date`, `time`, `eventSeasonID`, `homeTeamID`, `awayTeamID`,`fixtureStatusID`,`verified`,`createUserID`, `createTimestamp`)
      VALUES  ('$date','$time','$eventSeasonID','$homeTeamID','$awayTeamID',1,0,'$session_user_id','$createTimestamp')
      ";

      $result=mysql_query($sql_add_fixture);

      // if successfully added

      if($result){

        echo "successfully added fixture";
      } else {

        echo "ERROR";
      }

    }
  }

?>