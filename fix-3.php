<?php

$sportsID = mysql_real_escape_string($_GET['id']);

// First check if selection already exists in selection board first
if(isset($_GET['action']) && $_GET['action']=="add"){

    $sql4="SELECT 1 FROM `selectionBoard` 
    WHERE `userID` = '$userID'
    AND `fixtureID` = '$_GET[fixtureID]'
    AND `marketID` = '$_GET[marketID]'
    AND `selectionID` = '$_GET[selectionID]'";

    $result = mysql_query($sql4);

    if(mysql_fetch_row($result)) {

      //echo "selection already exists";
      echo '<script language="javascript">';
      echo 'alert("Oops, your selection already exists in the Selection Board or the Selection Bin.")';
      echo '</script>';

      } 
      
      else {

        $sql3="INSERT INTO selectionBoard (userID, fixtureID, marketID, selectionID)
        VALUES ('$userID', '$_GET[fixtureID]','$_GET[marketID]','$_GET[selectionID]')";
        $result=mysql_query($sql3);

        }
  }

    $sql = "SELECT fixtures.ID, fixtures.date, fixtures.time, sports.ID, sports.name, 
    events.name, t1.name, t2.name, t1.ID, t2.ID, countries.alpha_2

    FROM fixtures 

    INNER JOIN sports ON fixtures.sportID=sports.ID
    INNER JOIN events ON fixtures.eventID=events.ID
    INNER JOIN countries ON events.countryID = countries.id
    INNER JOIN teams t1 ON fixtures.homeTeamID=t1.ID
    INNER JOIN teams t2 ON fixtures.awayTeamID=t2.ID
    
    WHERE sports.ID='$sportsID'

    ORDER BY fixtures.date, fixtures.time
  ";

    $result = mysql_query($sql);
    
  echo '<table class="bordered">

  <tr>

  <th>ID</th>

  <th>Date</th>

  <th>Event</th>

  <th>Home</th>

  <th>v</th>

  <th>Away</th>

  </tr>';


  while($row = mysql_fetch_array($result)) {

    $mysqldate = $row['date'];
  	$audate = date('D d-M', strtotime($mysqldate));
  	$mysqltime = $row['time'];
  	$time = date('g:i a', strtotime($mysqltime));

    echo "<tr>";

    echo "<td>" . $row['0'] . "</td>";

    echo "<td>" . $audate . " ". $time . "</td>";

    echo "<td>" . $row['10'] . " " . $row['5'] . "</td>";  // Event

    echo "<td><a href=index.php?page=sports&id=2&fixtureID=" .  $row['0'] . "&marketID=1" . "&selectionID=1" . "&action=add>" . $row['6'] . "</a></td>";  // Home team selection

    echo "<td><a href=index.php?page=sports&id=2&fixtureID=" .  $row['0'] . "&marketID=1" . "&selectionID=3" . "&action=add>" . "draw" . "</a></td>";  // Draw selection

    echo "<td><a href=index.php?page=sports&id=2&fixtureID=" .  $row['0'] . "&marketID=1" . "&selectionID=2" . "&action=add>" . $row['7'] . "</a></td>";  // Away team selection

    echo "</tr>";

    }

  echo "</table>";

?>