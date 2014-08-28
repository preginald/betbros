<?php
include 'core/init.php';
protect_page();
admin_protect();
include 'includes/overall/header.php';
?>

<h1>View Sports</h1>
<?php include 'includes/admin-menu.php'; ?>
<br><br>
<?php 

$sql = "SELECT sports.ID, sports.name, sportType.ID, sportType.name, contestantType.ID, contestantType.name
		FROM sports
		JOIN sportType
		ON sports.sportTypeID=sportType.ID
		JOIN contestantType
		ON sports.contestantTypeID=contestantType.ID
		";
$result = mysql_query($sql);



// echo $row['ID'] . " " . $row['name'] . '<br>';

echo '<table class="bordered">
<tr>
<th>ID</th>
<th></th>
<th></th>
<th></th>
<th></th>
<th></th>
<th></th>
<th></th>
</tr>';

while($row = mysql_fetch_array($result)) {
	// Display table if sportTypeID=2 (team sport) AND contestantTypeID=2 (head 2 head) 
  $sportTypeID = $row['2'];
  $contestantTypeID = $row['4'];
  $sportID = $row['4'];
  // echo $sportTypeID . " " . $contestantTypeID . "<br>";
  if ($sportTypeID == 2 AND $contestantTypeID == 2) {
  	echo "<tr>";
    echo "<td>" . $row['0'] . "</td>";
    echo "<td>" . $row['1'] . "</td>";
    echo "<td>" . $row['3'] . "</td>";
    echo "<td>" . $row['5'] . "</td>";
    echo "<td><a href=" . "view-events.php?id=". $row['0'] . ">events</a></td>";  
    echo "<td><a href=" . "view-teams.php?id=". $row['0'] . ">teams</a></td>";  
    echo "<td><a href=" . "view-fixtures.php?id=". $row['0'] . ">fixtures</a></td>";
    echo "<td><a href=" . "edit-sports.php?id=". $row['0'] . ">update</a></td>";
    echo "</tr>";
    } elseif ($sportTypeID == 1 AND $contestantTypeID == 3 AND $sportID != 1) { // horse racing
    	echo "<tr>";
    	echo "<td>" . $row['0'] . "</td>";
	    echo "<td>" . $row['1'] . "</td>";
	    echo "<td>" . $row['3'] . "</td>";
	    echo "<td>" . $row['5'] . "</td>";
	    echo "<td><a href=" . "view-events.php?id=". $row['0'] . ">racecourses</a></td>";  
	    echo "<td><a href=" . "view-teams.php?id=". $row['0'] . ">teams</a></td>";  
	    echo "<td><a href=" . "racecards.php?id=". $row['0'] . ">racecards</a></td>";
	    echo "<td><a href=" . "edit-sports.php?id=". $row['0'] . ">update</a></td>";
	    echo "</tr>";
    } elseif ($sportTypeID == 1 AND $contestantTypeID == 2) { // eg Tennis
    	echo "<tr>";
    	echo "<td>" . $row['0'] . "</td>";
	    echo "<td>" . $row['1'] . "</td>";
	    echo "<td>" . $row['3'] . "</td>";
	    echo "<td>" . $row['5'] . "</td>";
	    echo "<td><a href=" . "view-events.php?id=". $row['0'] . ">events</a></td>";  
	    echo "<td><a href=" . "view-teams.php?id=". $row['0'] . "></a></td>";  
	    echo "<td><a href=" . "view-fixtures.php?id=". $row['0'] . ">fixtures</a></td>";
	    echo "<td><a href=" . "edit-sports.php?id=". $row['0'] . ">update</a></td>";
	    echo "</tr>";
    }
}
echo "</table>";

?>

<br>
<br>

<form name="form1" method="post" action="add-sports-db.php">
<h2>Add new sport</h2>
<input type="text" name="name" >
<input type="submit" name="Submit" value="Add">
</form>



<?php include 'includes/overall/footer.php'; ?>