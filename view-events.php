<?php
include 'core/init.php';
protect_page();
admin_protect();
include 'includes/overall/header.php';

$ID=$_GET['id']; // Gets value of ID sent from address bar

$sql = "SELECT events.ID, events.name, countries.name, sports.ID, sports.name
FROM events
JOIN countries
ON events.countryID=countries.id
JOIN sports
ON events.sportID=sports.ID
WHERE sports.ID='$ID'
ORDER BY
countries.name, events.name
";





$result = mysql_query($sql);

// Get event name from event table with page heading
$sql2 = "SELECT name FROM sports WHERE ID='$ID'";
$result2 = mysql_query($sql2);
$fixtureHeading= mysql_result($result2, 0);

echo "<h1>" . $fixtureHeading . " Events</h1>";


echo "<table border='0'>
<tr>
<th>ID</th>
<th>Event</th>
<th>Country</th>
<th></th>
</tr>";



while($row = mysql_fetch_array($result)) {

  echo "<tr>";
  echo "<td>" . $row['0'] . "</td>";
  // echo "<td>" . $row['name'] . "</td>";

  echo "<td><a href=" . "event-info.php?id=" . $row['ID'] . ">" . $row['1'] . "</a></td>";

  echo "<td>" . $row['2'] . "</td>";
  echo "<td><a href=" . "edit-events.php?id=". $row['ID'] . ">update</a></td>";
  echo "</tr>";
  }
echo "</table>";

?>

<br>
<br>

<h2>Add new event</h2>
<form name="form1" method="post" action="add-events-db.php" class="ajax">
<input type="text" name="name" placeholder="Event name" >
<!-- <select name="countryID"><?php country_dropDown() ?></select>-->
<input type="text" name="countryID" value="235" >
<select name="sportsID"><?php sports_dropdown() ?></select>
<input type="submit" name="Submit" value="Add">
</form>


<script src="js/contact.js"></script>
<?php include 'includes/overall/footer.php'; ?>