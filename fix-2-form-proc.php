<?php
include 'core/init.php';
//print_r($_POST);

// form processors
if(isset($_POST['option']) && $_POST['option'] == 'add_sel'){
  $fixtureID = $eventID = sanitize($_POST['eventID']);
  $marketID = sanitize($_POST['mID']);
  $selectionID = sanitize($_POST['selID']);
  $odds = sanitize($_POST['odds']);
  $bookieID = sanitize($_POST['bookieID']);
  $lID = sanitize($_POST['lID']);
  $sID = sanitize($_POST['sID']);

  $return =  add_selection($session_user_id,$fixtureID,$marketID,$selectionID,$lID,$odds,$sID,$eventID,$bookieID);
  echo $return['oddsStatus'];
}


if(isset($_POST['option']) && $_POST['option'] == 'add_odds'){
  $eventID = sanitize($_POST['eventID']);
  $marketID = sanitize($_POST['mID']);
  $selectionID = sanitize($_POST['selID']);
  $odds = sanitize($_POST['odds']);
  $sID = sanitize($_POST['sID']);
  $bookieID = sanitize($_POST['bookieID']);

  $oddsStatus = add_odds($odds,$sID,$eventID,$marketID,$selectionID,$bookieID,$session_user_id);
  $returnArray = array(
    'oddsStatus'  =>  $oddsStatus
    );
  echo json_encode($returnArray);

}

if(isset($_POST['option']) && $_POST['option'] == 'add_bet'){
  $fixtureID = $eventID = sanitize($_POST['eventID']);
  $marketID = sanitize($_POST['mID']);
  $selectionID = sanitize($_POST['selID']);
  $odds = sanitize($_POST['odds']);
  $bookieID = sanitize($_POST['bookieID']);
  $lID = sanitize($_POST['lID']);
  $stake = sanitize($_POST['stake']);
  $sID = sanitize($_POST['sID']);

  $return =  add_selection($session_user_id,$fixtureID,$marketID,$selectionID,$lID,$odds,$sID,$eventID,$bookieID);
  $oddsStatus = $return['oddsStatus'];
  $sbID = $return['sbID'];

  $returnArray = array(
    'oddsStatus'  =>  $oddsStatus,
    'sbID'    =>  $sbID,
    );

  echo json_encode($returnArray);

}

if (isset($_POST['option']) && $_POST['option'] == 'getbookie') {
  print_r($_POST);
  $bookieID = sanitize($_POST['bookieID']);
  echo mysql_result(mysql_query("SELECT b.name b FROM `bookies` b WHERE b.ID = $bookieID"), 0);
}

if(isset($_POST['option']) && $_POST['option'] == 'eventCheck'){
  $date = $_POST['date'];
  $eID = $_POST['eID'];
  $json = array('status' => 'ERROR', 'ID' => '0');
  $s = "SELECT es.ID, e.name  FROM `eventSeason` es 
        INNER JOIN `events` e ON es.eventsID = e.ID  
        WHERE `startDate` < '$date' AND `endDate` > '$date' AND eventsID = $eID";
  $r = mysql_fetch_assoc(mysql_query($s));
  if ($r) {
    $json['status'] = "OK";
    $json['esID'] = $r['ID'];
    $json['ename'] = $r['name'];
  } else {
    $json['sql'] = $s;
  }
  echo json_encode($json);  
}