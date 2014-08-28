<script type="text/javascript">
$(document).ready(function(){
$("#name").autocomplete({source:'autocomplete_horse.php', minLength:3});
});
</script>

<?php

$raceID=mysql_escape_string($_GET['rid']); // Gets value of ID sent from address bar

// First check if selection already exists in selection board first
if(isset($_GET['action']) && $_GET['action']=="addsel"){
  echo "adding selection";
  
  $rid = mysql_escape_string($_GET['rid']);
  $marketID = mysql_escape_string($_GET['marketid']);
  $selectionID = mysql_escape_string($_GET['selectionid']);
  $horseID = mysql_escape_string($_GET['hid']);

  $sql4="SELECT 1 FROM `selectionBoard_01` 
  WHERE `userID` = '$session_user_id'
  AND `racecardID` = '$rid'
  AND `marketID` = '$marketID'
  AND `horseID` = '$horseID'";

  $result = mysql_query($sql4);

  if(mysql_fetch_row($result)) {

    //echo "selection already exists";
    echo '<script language="javascript">';
    echo 'alert("Oops, your selection already exists in the Selection Board or the Selection Bin.")';
    echo '</script>';
  }else{

    $sql3="INSERT INTO selectionBoard_01 (userID, racecardID, marketID,selectionID,horseID)
    VALUES ('$session_user_id', '$rid','$marketID','$selectionID','$horseID')";
    $result=mysql_query($sql3);
  }
}

if(isset($_POST['update-racedetails'])){
  //print_r_pre($_POST);
  $win_count=(array_count_values($_POST['status']));
  if ($win_count[15] > 1) {
    $error = "ERROR: Can't have more than 1 winner in a race!<br/>";
  } else{
    $error = "";
    // loop through rows and update horseRaces table
    foreach ($_POST["id"] as $selid ) {
      $hsID=$_POST["status"][$selid];
      $update="UPDATE `horseRaces` SET `horseStatusID` = '$hsID' WHERE `ID`= $selid";

      mysql_query($update) or die (mysql_error());

      horse_bet_update($selid,$hsID);
    }
  }
}

if(isset($_POST['add-racedetail'])){
  // Required field names
  $required = array('stall', 'horseID', 'jockeysID');

  // Loop over field names, make sure each one exists and is not empty
  $error = false;
  foreach($required as $field) {
    if (empty($_POST[$field])) {
      $error = true;
    }
  }

  if ($error) {
    echo "All fields are required.";
    echo $_POST['stall'];

    } else {

    echo "Adding horse and jockey...";

    $stall = $_POST['stall'];
    $draw = $_POST['draw'];
    $horseID = $_POST['horseID'];
    $jockeysID = $_POST['jockeysID'];
    $weight = $_POST['weight'];

    $sql_add_racedetail = 
    "INSERT INTO `horseRaces`(`racecardID`, `stall`, `draw`, `horseID`, `jockeysID`, `weight`, `horseStatusID`) 
    VALUES ('$raceID','$stall','$draw','$horseID','$jockeysID','$weight', '1')
    ";

    $result=mysql_query($sql_add_racedetail);

  }
}

// query to list racecards related to selected raceID

$sql = 
"SELECT racecards.ID, racecourses.name, racecards.date, racecards.time, 
racecards.name, raceClass.name, going.name, racecards.distance, racecards.prize

FROM racecards

INNER JOIN racecourses ON racecards.racecoursesID = racecourses.ID
INNER JOIN raceClass ON racecards.raceClassID = raceClass.ID
INNER JOIN going ON racecards.goingID = going.ID

WHERE racecards.ID = $raceID
";

$result = mysql_query($sql);

// Get event name from event table with page heading

$result2 = mysql_query($sql);

while($row2 = mysql_fetch_array($result2)) {

  $mysqldate = $row2['3'];
  $audate = date('D d-M', strtotime($mysqldate));
  $mysqltime = $row2['time'];
  $time = date('g:i A', strtotime($mysqltime));
  $racecoursename = $row2['1'];

}

?>

<h1><?php echo "$audate $time $racecoursename" ?> <a href="index.php?page=racecards&rid=<?php echo "$raceID" ?>&view=default&racedetails=add"><img alt="add" style="width: 24px; height: 24px;" title="add" src="images/add-record.png"></a></h1> <a href="index.php?page=racecards-detail&rid=<?php echo $raceID ?>&view=edit">update</a>

<table class="bordered">
<tr>
<th>ID</th>
<th>Date</th>
<th>Event</th>
<th>Race Name</th>
<th>Class</th>
<th>Going</th>
<th>Distance</th>
<th>Prize</th>
</tr>

<?php 
while($row = mysql_fetch_array($result)) {

  $mysqldate = $row['date'];

  $prize = $row['8'];

  $prize = number_format($prize,0);

  $audate = date('D d-M', strtotime($mysqldate));

  $raceNo = $raceNo++;

  $mysqltime = $row['time'];

  $time = date('g:i a', strtotime($mysqltime));



  echo "<tr>";

  echo "<td>" . $row['0'] . "</td>"; // race ID

  // echo "<td>" . $row['date'] . " ". $row['time'] . "</td>";

  echo "<td>  $audate <br> $time </td>"; // race date and time

  echo "<td>" . racecard_location($row['0']) . "</td>"; // Race venue

  echo "<td>" . $row['4'] . "</td>"; // Race name

  echo "<td>" . $row['5'] . "</td>"; // Class

  echo "<td>" . $row['6'] . "</td>"; // Going

  echo "<td>" . $row['7'] . "</td>"; // Distance

  // echo "<td>" . $row['8'] . "</td>"; unformated Prize numberic field

  echo "<td>$ $prize </td>" ;

  echo "</tr>";

  }

echo "</table>";

$today = date("Y-m-d");

if (isset($_GET['view']) && $_GET['view']=="default") {
  $sql3 = 
  "SELECT 
  hr.ID AS hrid, 
  hr.stall AS hrstall, 
  hr.draw AS hrdraw, 
  hr.position AS pos,
  h.ID AS hid,
  h.Name AS hname, 
  c.alpha_2 AS calpha, 
  hg.name AS hgname, 
  h.birth AS hbirth, 

  j.firstName AS jfname, 
  j.lastName AS jlname, 
  
  t.firstName AS tfname, 
  t.lastName AS tlname, 
  
  o.firstName AS ofname, 
  o.lastName AS olname, 
  
  hs.name AS status, 
  r.ID AS rid

  FROM horseRaces AS hr

  INNER JOIN horses AS h ON hr.HorseID = h.ID
  INNER JOIN countries AS c ON h.CountryID = c.id
  INNER JOIN horseGender AS hg ON h.GenderID = hg.ID

  INNER JOIN jockeys AS jo ON hr.jockeysID = jo.ID
  INNER JOIN horseTrainer AS ht ON h.TrainerID = ht.ID
  INNER JOIN horseOwner AS ho ON h.OwnerID = ho.ID
  
  INNER JOIN person AS j ON jo.personID = j.ID
  INNER JOIN person AS o ON ho.personID = o.ID
  INNER JOIN person AS t ON ht.personID = t.ID

  INNER JOIN horseStatus AS hs ON hr.horseStatusID = hs.ID
  INNER JOIN racecards AS r ON hr.racecardID = r.ID

  WHERE r.ID = $raceID

  ORDER BY hr.stall
  ";

  $result3 = mysql_query($sql3);

  // check if racecards-detail is in add mode

  if(isset($_GET['racedetails']) && $_GET['racedetails']=="add"){
    ?>
    <h2>Add race detail</h2>
    <form name="add-racedetail" method="post" action="">

    <input type="number" name="stall" min="0" style="width:3em" placeholder="Stall" >
    <input type="number" name="draw" min="0" style="width:3em" placeholder="Draw" >
    <select name="horseID"><?php horse_dropdown(0) ?></select>
    <!-- <input type="text" id="horseID" name="horseID" /> -->
    <select name="jockeysID"><?php jockey_dropdown() ?></select>
    <input type="number" name="weight" min="0" style="width:3em" placeholder="Weight" >
    <input type="hidden" value="<?php echo $raceID ?>" name="racecardID" />

    <input type="submit" name="add-racedetail" value="Add">
    </form>
    <?php 
  }

  ?>
  <br><br><table class="bordered">

  <tr>

  <th>ID</th>

  <th>Stall</th>

  <th>Draw</th>

  <th>Horse</th>

  <th>Single Bets</th>

  <th>Birth (Age)</th>

  <th>Jockey</th>

  <th>Trainer</th>
   
  <th>Owner</th>

  <th>Status (Position)</th>

  </tr>


  <?php
  while($row3 = mysql_fetch_assoc($result3)) {

    $raceNo = $raceNo++;

    $mysqltime = $row['time'];

    $time = date('g:i a', strtotime($mysqltime));

    ?>
    <tr>
    <td><?php echo $row3['hrid'] ?></td>
    <td><?php echo $row3['hrstall'] ?></td>
    <td><?php echo $row3['hrdraw'] ?></td>
    <td><?php echo $row3['hname'] . " (" . $row3['calpha'] . ") " ?></td>
    <td><a href="index.php?page=racecards&rid=<?php echo $raceID ?>&marketid=3&selectionid=<?php echo $row3['hrid'] ?>&hid=<?php echo $row3['hid'] ?>&action=addsel">win</a> <a href="index.php?page=racecards&rid=<?php echo $raceID ?>&marketid=4&selectionid=<?php echo $row3['hrid'] ?>&hid=<?php echo $row3['hid'] ?>&action=addsel">place</a> <a href="index.php?page=racecards&rid=<?php echo $raceID ?>&marketid=5&selectionid=<?php echo $row3['hrid'] ?>&hid=<?php echo $row3['hid'] ?>&action=addsel">e/w</a></td>
    <td><?php echo $row3['hbirth'] .  " (" . (date("Y") - $row3['hbirth']) . ")" ?></td>
    <td><?php echo $row3['jfname'] . " " . $row3['jlname'] ?></td>
    <td><?php echo $row3['tfname'] . " " . $row3['tlname'] ?></td>
    <td><?php echo $row3['ofname'] . " " . $row3['olname'] ?></td>
    <td><?php echo $row3['status'] . " (" . $row3['pos'] ?>)</td>  
    </tr>

    <?php
    }

    echo "</table>";
}

if (isset($_GET['view']) && $_GET['view']=="edit") {
  $sql3 = 
  "SELECT 
  hr.ID AS hrid, 
  hr.stall AS hrstall, 
  hr.draw AS hrdraw, 
  hr.position AS pos,
  hr.horseStatusID AS hrsid,

  h.ID AS hid,
  h.Name AS hname, 
  c.alpha_2 AS calpha, 
  hg.name AS hgname, 
  h.birth AS hbirth, 

  j.firstName AS jfname, 
  j.lastName AS jlname, 
  
  t.firstName AS tfname, 
  t.lastName AS tlname, 
  
  o.firstName AS ofname, 
  o.lastName AS olname, 
  
  hs.name AS status, 
  r.ID AS rid

  FROM horseRaces AS hr

  INNER JOIN horses AS h ON hr.HorseID = h.ID
  INNER JOIN countries AS c ON h.CountryID = c.id
  INNER JOIN horseGender AS hg ON h.GenderID = hg.ID

  INNER JOIN jockeys AS jo ON hr.jockeysID = jo.ID
  INNER JOIN horseTrainer AS ht ON h.TrainerID = ht.ID
  INNER JOIN horseOwner AS ho ON h.OwnerID = ho.ID
  
  INNER JOIN person AS j ON jo.personID = j.ID
  INNER JOIN person AS o ON ho.personID = o.ID
  INNER JOIN person AS t ON ht.personID = t.ID

  INNER JOIN horseStatus AS hs ON hr.horseStatusID = hs.ID
  INNER JOIN racecards AS r ON hr.racecardID = r.ID

  WHERE r.ID = $raceID

  ORDER BY hr.stall
  ";

  $result3 = mysql_query($sql3);

  ?>
  <br><br><table class="bordered">

  <tr>

  <th>ID</th>

  <th>Stall</th>

  <th>Draw</th>

  <th>Horse</th>

  <th>Single Bets</th>

  <th>Birth (Age)</th>

  <th>Jockey</th>

  <th>Trainer</th>
   
  <th>Owner</th>

  <th>Status (Position)</th>

  </tr>

  <form method="post">
  <?php
  while($row3 = mysql_fetch_assoc($result3)) {

    $raceNo = $raceNo++;

    $mysqltime = $row['time'];

    $time = date('g:i a', strtotime($mysqltime));

    ?>
    <tr>
    <td><?php echo $row3['hrid'] ?></td>
    <td><?php echo $row3['hrstall'] ?></td>
    <td><?php echo $row3['hrdraw'] ?></td>
    <td><?php echo $row3['hname'] . " (" . $row3['calpha'] . ") " ?></td>
    <td><a href="index.php?page=racecards&rid=<?php echo $raceID ?>&marketid=3&selectionid=<?php echo $row3['hrid'] ?>&hid=<?php echo $row3['hid'] ?>&action=addsel">win</a> <a href="index.php?page=racecards&rid=<?php echo $raceID ?>&marketid=4&selectionid=<?php echo $row3['hrid'] ?>&hid=<?php echo $row3['hid'] ?>&action=addsel">place</a> <a href="index.php?page=racecards&rid=<?php echo $raceID ?>&marketid=5&selectionid=<?php echo $row3['hrid'] ?>&hid=<?php echo $row3['hid'] ?>&action=addsel">e/w</a></td>
    <td><?php echo $row3['hbirth'] .  " (" . (date("Y") - $row3['hbirth']) . ")" ?></td>
    <td><?php echo $row3['jfname'] . " " . $row3['jlname'] ?></td>
    <td><?php echo $row3['tfname'] . " " . $row3['tlname'] ?></td>
    <td><?php echo $row3['ofname'] . " " . $row3['olname'] ?></td>
    <td><select required name="status[<?php echo $row3['hrid'] ?>]"><?php horse_race_status($row3['hrsid']) ?></select></td>  
    <input type="hidden" name="id[]" value="<?php echo $row3['hrid'] ?>">
    </tr>

  <?php
    }

  echo "</table>";
  echo '<input type = "submit" name="update-racedetails" value="update">';
  echo $error;
  echo '</form>';
}

?>