<?php
protect_page(); 
// football events page

$sql = 
"SELECT events.ID, events.name, countries.name, sports.ID, sports.name
FROM events
JOIN countries
ON events.countryID=countries.id
JOIN sports
ON events.sportID=sports.ID
WHERE sports.ID='$sportsID'
ORDER BY countries.name, events.name
";

$result = mysql_query($sql);

?>

<table class="bordered">
	<thead>
	<tr>
	<th>ID</th>
	<th></th>
	<th></th>
	<th></th>
	</tr>
	</thead>

<?php 

while($row = mysql_fetch_array($result)) {

  echo "<tr>";
  echo "<td>" . $row['0'] . "</td>";
  echo "<td><a href=" . "event-info.php?id=" . $row['ID'] . ">" . $row['1'] . "</a></td>";
  echo "<td>" . $row['2'] . "</td>";
  echo "<td><a href=" . "edit-events.php?id=". $row['ID'] . ">update</a></td>";
  echo "</tr>";

  }

echo "</table>";

?>