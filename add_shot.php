<?php 
  $teamPlayerID= $_POST['teamPlayerID'];
  $periodID=$_POST['periodID'];

  $time="00:". $_POST['minute'] . ":" . $_POST['second'];
  $incidentID=$_POST['incidentID'];
  $bodyPartID=$_POST['bodyPartID'];
  $fromID=$_POST['fromID'];

  $createTimestamp = date("Y-m-d H:i:s");

  $sql_add_shot = 
  "INSERT INTO `shot`
  (`fixtureID`, `teamPlayerID`, `bodyPartID`, `fromID`,`incidentID`,`periodID`,`time`,`createUserID`, `createTimestamp`) 
  VALUES 
  ('$fixtureID','$teamPlayerID','$bodyPartID','$fromID','$incidentID','$periodID','$time','$session_user_id','$createTimestamp')";


  $result=mysql_query($sql_add_shot);

  // end add player to database process
  // if successfully updated. 

  if($result){
    $createTimestamp = date("Y-m-d H:i:s");
    echo kickoff_check_insert($fixtureID,$session_user_id,$createTimestamp);

    echo "successfully added shot <br />";

    //prececure to insert or update score to related fixtureID
    //first we have to check if fixtureID exists in table

    $sql_check_result_exist = "SELECT EXISTS(SELECT 1 FROM `fixtureResults` WHERE `fixtureID` = $fixtureID)";
    $result_check_result_exist = mysql_query($sql_check_result_exist);
    $row_check_results_exists = $result_check_result_exist
    if(mysql_fetch_row($result_check_result_exist)==0) {
      // echo "$fixtureID does not exist in fixtureResults table. Do insert result to table.";
      
      $htscore=total_home_goals($fixtureID,$htid);
      $atscore=total_away_goals($fixtureID,$atid);
      $createTimestamp = date("Y-m-d H:i:s");
      echo $sql_insert_score=
      "INSERT INTO `fixtureResults`(`fixtureID`, `htscore`, `atscore`, `createUserID`, `createTimestamp`) 
      VALUES ('$fixtureID','$htscore','$atscore','$session_user_id','$createTimestamp')";
      $result=mysql_query($sql_insert_score);

      if($result){
        echo "successfully added score";
      } else {
        echo "error";
      }

    } else {
      // echo "$fixtureID does exist in fixtureResults table. Do update result to table.";

      $htscore=total_home_goals($fixtureID,$htid,$atid);
      $atscore=total_away_goals($fixtureID,$htid,$atid);
      $createTimestamp = date("Y-m-d H:i:s");
      echo $sql_update_score=
      "UPDATE `fixtureResults` SET `htscore`='$htscore',`atscore`='$atscore',`modifyUserID`='$session_user_id',`modifyTimestamp`='$createTimestamp' WHERE `fixtureID` = '$fixtureID'";
      $result=mysql_query($sql_update_score);

      if($result){
        echo "successfully updated score";
      } else {
        echo "error";
      }
    }
  } else {
    echo "ERROR";
  }
  ?>