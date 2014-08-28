<form action="" method="POST">
<input type="text" name="term" size="50" placeholder="Enter Search Term"> <b>Results:</b> <select name="results">
    <option>10</option>
    <option>20</option>
    <option>50</option>
</select><br>
<input type="submit" value="Search">
</form>

<?php 

$sql_search = mysql_query("SELECT horses.ID, horses.name, horses.birth, horseGender.name, horseOwner.lastName, horseTrainer.lastName, countries.name
	FROM horses
	JOIN horseGender
	ON horses.genderID=horseGender.ID
	JOIN horseOwner
	ON horses.OwnerID=horseOwner.ID
	JOIN horseTrainer
	ON horses.trainerID=horseTrainer.ID
	JOIN countries
	ON horses.countryID=countries.id WHERE horses.name LIKE '%$_POST[term]%' LIMIT 0,$_POST[results]");

?>
<table id="sortable" class="bordered">
<thead>
<tr>
<th>ID</th>
<th>Horse</th>
<th>Birth</th>
<th>Sex</th>
<th>Owner</th>
<th>Trainer</th>
<th>Country</th>
<th>Update</th>
</tr>
</thead>

<?php

// start display horse list table

while($row = mysql_fetch_array($sql_search)) {

  echo "<tr>";
  echo "<td>" . $row['ID'] . "</td>";
  echo "<td>" . $row['1'] . "</td>";
  echo "<td>" . $row['2'] . " (" . (date("Y") - $row['2']) . ")" . "</td>";
  echo "<td>" . $row['3'] . "</td>";
  echo "<td>" . $row['4'] . "</td>";
  echo "<td>" . $row['5'] . "</td>";
  echo "<td>" . $row['6'] . "</td>";
  echo "<td align='center'><a href=" . "admin.php?horses=edit&horsesid=". $row['ID'] . ">update</a></td>";
  echo "</tr>";
  }
echo "</table>";

// end display horse list table