<?php
include 'core/init.php';
protect_page();
admin_protect();
include 'includes/overall/header.php';
?>

<h1>View Horses</h1>
<?php include 'includes/admin-horse-menu.php';

$sql = "SELECT horses.ID, horses.name, horses.birth, horseGender.name, horseOwner.lastName, horseTrainer.lastName, countries.name
FROM horses
JOIN horseGender
ON horses.genderID=horseGender.ID
JOIN horseOwner
ON horses.OwnerID=horseOwner.ID
JOIN horseTrainer
ON horses.trainerID=horseTrainer.ID
JOIN countries
ON horses.countryID=countries.id";

$result = mysql_query($sql);


echo "<table border='0'>
<tr>
<th>ID</th>
<th>Horse</th>
<th>Birth</th>
<th>Sex</th>
<th>Owner</th>
<th>Trainer</th>
<th>Country</th>
<th>Update</th>
</tr>";



while($row = mysql_fetch_array($result)) {

  echo "<tr>";
  echo "<td>" . $row['ID'] . "</td>";
  echo "<td>" . $row['1'] . "</td>";
  echo "<td>" . $row['2'] . " (" . (date("Y") - $row['2']) . ")" . "</td>";
  echo "<td>" . $row['3'] . "</td>";
  echo "<td>" . $row['4'] . "</td>";
  echo "<td>" . $row['5'] . "</td>";
  echo "<td>" . $row['6'] . "</td>";
  echo "<td align='center'><a href=" . "edit-horses.php?id=". $row['ID'] . ">update</a></td>";
  echo "</tr>";
  }
echo "</table>";


echo $nowYear;

?>

<br>
<br>

<h2>Add new horse</h2>
<form name="form1" method="post" action="add-horses-db.php">
<input type="text" name="name" placeholder="Horse name">
<input type="number" name="birth" min="2006" max="2013" placeholder="Birth year">
<select name="sexID"><?php horse_sex_dropDown() ?></select>
<select name="horseOwnerID"><?php horse_owner_dropDown() ?></select>
<select name="horseTrainerID"><?php horse_trainer_dropDown() ?></select>
<select name="countryID"><?php country_dropDown() ?></select>
<input type="submit" name="Submit" value="Add">
</form>



<?php include 'includes/overall/footer.php'; ?>