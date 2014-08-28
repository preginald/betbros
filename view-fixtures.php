<?php
include 'core/init.php';
protect_page();
admin_protect();
include 'includes/overall/header.php';



$ID=$_GET['id']; // Gets value of ID sent from address bar

$sql = "SELECT fixtures.ID, fixtures.date, fixtures.time, sports.ID, sports.name, events.name, teams.name, t2.name
FROM fixtures 
JOIN sports
ON fixtures.sportID=sports.ID
JOIN events
ON fixtures.eventID=events.ID
JOIN teams
ON fixtures.homeTeamID=teams.ID
JOIN teams t2
ON fixtures.awayTeamID=t2.ID
WHERE sports.ID='$ID'
ORDER BY events.name, fixtures.date, fixtures.time
";

$result = mysql_query($sql);

// Get event name from event table with page heading
$sql2 = "SELECT name FROM sports WHERE ID='$ID'";
$result2 = mysql_query($sql2);
$fixtureHeading= mysql_result($result2, 0);

echo "<h1>" . $fixtureHeading . " Fixtures</h1>";

// include 'includes/admin-fixtures-menu.php'; 

echo "<table>
<tr>
<th>ID</th>
<th>Date</th>
<th>Event</th>
<th></th>
</tr>";

while($row = mysql_fetch_array($result)) {
	$mysqldate = $row['date'];
	$audate = date('D d-M', strtotime($mysqldate));

	$mysqltime = $row['time'];
	$time = date('g:i a', strtotime($mysqltime));

  echo "<tr>";
  echo "<td>" . $row['0'] . "</td>";
  // echo "<td>" . $row['date'] . " ". $row['time'] . "</td>";
  echo "<td>" . $audate . " ". $time . "</td>";
  echo "<td>" . $row['5'] . "</td>";
  echo "<td>" . $row['6'] . " v " . $row['7'] . "</td>";
  echo "</tr>";
  }
echo "</table>";

$today = date("Y-m-d");

?>

<br>
<br>

<h2>Add new fixture</h2>
<form name="form1" method="post" action="add-fixtures-db.php" class="ajax">
<input type="date" name="date" value="<?php echo $today ?>">
<input type="time" name="time" >
<select name="eventID"><?php fixture_event_dropDown() ?></select>
<select name="homeTeamID"><?php teams_dropDown() ?></select>
<select name="awayTeamID"><?php teams_dropDown() ?></select>
<input type="hidden" name="sportID" value="<? echo $ID; ?>">
<input type="submit" name="Submit" value="Add">
</form>



<script src="js/contact.js"></script>
<?php include 'includes/overall/footer.php'; ?>