<?php
include 'core/init.php';
protect_page();
admin_protect();
include 'includes/overall/header.php';
?>

<h1>View Horse Owners</h1>
<?php include 'includes/admin-horse-menu.php';

$sql = "SELECT * FROM horseOwner";

$result = mysql_query($sql);


echo "<table border='0'>
<tr>
<th>ID</th>
<th>First Name</th>
<th>Last Name</th>
<th>Country</th>
<th>Update</th>
</tr>";


while($row = mysql_fetch_array($result)) {

  echo "<tr>";
  echo "<td>" . $row['ID'] . "</td>";
  echo "<td>" . $row['firstName'] . "</td>";
  echo "<td>" . $row['lastName'] . "</td>";
  echo "<td>" . $row['countryID'] . "</td>";
  echo "<td align='center'><a href=" . "edit-owners.php?id=". $row['ID'] . ">update</a></td>";
  echo "</tr>";
  }
echo "</table>";

?>

<br>
<br>

<h2>Add new trainer</h2>
<form name="form1" method="post" action="add-owner-db.php">
<input type="text" name="firstName" >
<input type="text" name="lastName" >
<select name="countryID"><?php country_dropDown() ?></select>
<input type="submit" name="Submit" value="Add">
</form>



<?php include 'includes/overall/footer.php'; ?>