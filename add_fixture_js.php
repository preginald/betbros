<?php
require_once 'core/init.php'; 

  //print_r($_POST);

  $date = $_POST['date'];
  $time = $_POST['time'];
  $esID = $_POST['esID'];
  $ht = $_POST['ht'];
  $at = $_POST['at'];
  $htID = $_POST['htID'];
  $atID = $_POST['atID'];

  $sID = 2;

  /* 
  * When creating a new fixture we need to enter the eventSeasonID $esID, 
  * home and away teamSeason.ID $tsID
  * The $tsID is obtained using get_tsID($tID) passing the home or away $tID.
  * The $tID is obtained in a couple of ways:
  * If user enters an existing team then the $tID is supplied in a hidden field.
  * We do validation to make sure the team name matches the $tID. 
  * If they match then we get_tsID(). 
  * If they don't match then we do add_team(), get new team.ID and get_tsID(tID).
  * If user enters a new team then we add_team(), get new team.ID and get_tsID(tID).
  * When we have home and away $tsID we can add_fix().
  * 
  * add_fix($date, $time, $esID, $htsID, $atsID,fixstID,$verified,$createUserID)
  */

  // step 1. Get teamSeasonID from $htID
  $get_htID = get_teamsID($ht);

  if ($get_htID != $htID) {
    $cID = get_cID($esID);
    $htID =  add_team($ht,$cID,$sID,$session_user_id);
    $htsID = add_teamSeason($htID,$esID,250,250,8,8,0,$session_user_id);
  } else {
    $get_tsID = get_tsID($htID,$esID);
    // if get_tsID returns 0 that means this team does not exist in teamSeason so create a new teamSeason record
    $htsID = ($get_tsID == 0) ? add_teamSeason($htID,$esID,250,250,8,8,0,$session_user_id) : $get_tsID;
  }

  // step 2. Get teamSeasonID from $atID
  $get_atID = get_teamsID($at);
  
  if ($get_atID != $atID) {
    $cID = get_cID($esID);
    $atID =  add_team($at,$cID,$sID,$session_user_id);
    $atsID = add_teamSeason($atID,$esID,250,250,8,8,0,$session_user_id);
  } else {
    $get_tsID = get_tsID($atID,$esID);
    // if get_tsID returns 0 that means this team does not exist in teamSeason so create a new teamSeason record
    $atsID = ($get_tsID == 0) ? add_teamSeason($atID,$esID,250,250,8,8,0,$session_user_id) : $get_tsID;
  }

  // step 3. Add fixture
  $fixID = add_fix($date, $time, $esID, $htsID, $atsID,1,0,$session_user_id);

  if ($fixID > 0) {
    echo "SUCCESS";
  } elseif ($fixID == "exists") {
    echo "EXISTS";
  }else {
    echo $fixID;
  }

?>