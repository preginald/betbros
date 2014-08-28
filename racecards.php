<?php

$today = date('Y-m-d', strtotime('now')); 
$yesterday = date('Y-m-d', strtotime('yesterday')); 

if(isset($_GET['view']) && $_GET['view']=="add"){

  include 'add-racecard.php'; 
}

if(isset($_GET['view']) && $_GET['view']=="edit"){

  $racecardID = mysql_escape_string($_GET['rcid']);
  // echo $racecardID; // debugging
  if(empty($racecardID)) {

    echo "racecard.ID not found"; // error message when racecardID not found in url for GET variable

  } else {

    include 'edit-racecard.php';

  }
}

if(isset($_GET['rid'])) {

  include 'racecards-detail.php';
  // echo "include racecards-detail.php"; // debugging
}

echo '<h1>Horse Racecards <a href="index.php?page=racecards&view=add"><img alt="add" style="width: 24px; height: 24px;" title="add" src="images/add-record.png"></a></h1>';

$sql = 
"SELECT 
rc.ID AS rcid, 
rcourse.name AS rcourse, 
rc.name AS rname,
rc.date AS date,
rc.time AS time,

class.name AS class,
rc.runners AS runners, 
going.name AS going, 

rc.distance AS distance, 
rc.prize AS prize

FROM racecards AS rc

INNER JOIN racecourses AS rcourse ON rc.racecoursesID = rcourse.ID
INNER JOIN raceClass AS class ON rc.raceClassID = class.ID
INNER JOIN going ON rc.goingID = going.ID

WHERE rc.date >= '$yesterday'
";

$result = mysql_query($sql);

?>

<table class="bordered">
<tr>
<th>ID</th>
<th>Date</th>
<th>Event</th>
<th>Race Name</th>
<th>Runners</th>
<th>Class</th>
<th>Going</th>
<th>Distance</th>
<th>Prize</th>
<th width="40px">Action</th>
</tr>

<?php 
while($row = mysql_fetch_assoc($result)) {
  $prize = $row['prize'];
  $prize = number_format($prize,0);
  $mysqldate = $row['date'];
  $audate = date('D d-M', strtotime($mysqldate));
  $raceNo = $raceNo++;
  $mysqltime = $row['time'];
  $time = date('g:i a', strtotime($mysqltime));

  echo "<tr>";
  echo "<td>" . $row['rcid'] . "</td>";
  echo "<td>" . $audate . "</td>";
  echo "<td>" . $time . " " . racecard_location($row['rcid']) . "</td>"; // venue
  echo '<td><a href=index.php?page=racecards&rid=' . $row['rcid'] . '&view=default>' . $row['rname'] . '</a></td>'; // race name   
  echo "<td>" . $row['runners'] . "</td>"; // runners
  echo "<td>" . $row['class'] . "</td>"; // class
  echo "<td>" . $row['going'] . "</td>"; // going
  echo "<td>" . $row['distance'] . "</td>"; // distance
  // echo "<td>" . $row['8'] . "</td>"; unformated Prize numberic field
  echo "<td>" ."$" . $prize . "</td>" ;
  ?>

  <td><a href="index.php?page=racecards&view=edit&rcid=<?php echo $row['rcid'] ?>"><img alt="update" style="width: 32px; height: 32px;" title="update" src="images/update.png"></a></td>

  </tr>

<?php 
  }
echo "</table>";

$today = date("Y-m-d");

/*
if (has_access($user_data['user_id'], 1) === true) {

echo 'This input form only displays if you have administrator access.';
echo '<h2>Add new racecard</h2>';
echo '<form name="form1" method="post" action="add-fixtures-db.php" class="ajax">';
echo '<input type="date" name="date" value="' . $today . '">';
echo '<input type="time" name="time" >';
echo '<select name="eventID">' . fixture_event_dropDown() . '</select>';
echo '<select name="homeTeamID">' . teams_dropDown() . '</select>';
echo '<select name="awayTeamID">' . teams_dropDown() . '</select>';
echo '<input type="hidden" name="sportID" value="' . $ID . '">';
echo '<input type="submit" name="Submit" value="Add">';
echo '</form>';

} 
*/
?>