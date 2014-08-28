<?php 
include 'core/init.php';

$option = $_GET['option'];
$json = array('option' => $option,'status' => 'ERROR');

if (isset($_GET['option']) && $_GET['option'] == 'team') {
  $tname = $_GET['tname'];
  $side = $_GET['side'];
  $json['tname'] = $tname;
  $json = teamLookupSQL($tname,$side,$sID=2);
}

echo $json;


function eventLookupSQL($ID){
  $s = "SELECT e.name FROM `teamSeason` ts INNER JOIN `eventSeason` es
        ON ts.eventSeasonID=es.ID
        INNER JOIN `events` e
        ON es.eventsID = e.ID
        WHERE ts.teamID = $ID
        LIMIT 1
        ";
  $r = mysql_fetch_assoc(mysql_query($s));

  return $r['name'];
  // $r = ($r == 1) ? mysql_result(mysql_query($s), 0) : "";
  //return ($r != "") ? $r : "";
}

function teamLookupSQL($tname,$side,$sID=2){
  $s = "SELECT t.ID, t.name, r.name r FROM `teams` t 
  INNER JOIN region r
  ON t.countryID=r.ID 
  WHERE sportID = $sID 
  AND ( t.name LIKE  '%".$tname."%' 
  OR t.sname LIKE  '%".$tname."%' 
  OR t.lname LIKE  '%".$tname."%' )
  ORDER BY t.name LIMIT 10";
  $r = mysql_query($s);
  if ($r) {
    while ($row = mysql_fetch_assoc($r)) {?>
      <a href="#" class="list-group-item <?= $side ?>" data-ID="<?= $row['ID'] ?>">
        <h4 class="list-group-item-heading"><?= $row['name'] ?></h4>
        <p class="list-group-item-text">
          <span class="label label-default"><?= $row['r'] ?></span>
          <span class="label label-default"><?= eventLookupSQL( $row['ID'] ) ?></span>
        </p>
      </a>
    <?php
    }
    return $rows;
  } else {
    return $json['sql'] = $s;    
  }
}