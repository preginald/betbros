<?php
include 'core/init.php';
protect_page();
include 'includes/overall/header.php';
?>
<h1>Bet Tracker</h1>
<?php 


$sql = "SELECT * FROM betTracker WHERE userID = $session_user_id" ;
$result = mysql_query($sql);



// echo $row['ID'] . " " . $row['name'] . '<br>';

echo "<table border='0'>
<tr>
<th>Bet ID</th>
<th>userID</th>
<th>sportID</th>
<th>datetime</th>
</tr>";

while($row = mysql_fetch_array($result)) {

  echo "<tr>";
  echo "<td>" . $row['ID'] . "</td>";
  echo "<td>" . $row['userID'] . "</td>";
  echo "<td>" . $row['sportID'] . "</td>";
  echo "<td>" . $row['datetime'] . "</td>";
  echo "</tr>";
  }
echo "</table>";
?>

<br>
<br>

<form name="form1" method="post" action="selectSport.php">
<h2>Add new bet</h2>
<select> name="betType"
	<?php bet_type_dropDown() ?></select>
<input type="submit" name="Submit" value="Add">
</form>


<?php include 'includes/overall/footer.php'; ?>