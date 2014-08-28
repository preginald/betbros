<?php
include 'core/init.php';
protect_page();
admin_protect();
include 'includes/overall/header.php';
?>

<h1>View Jockeys</h1>

<?php include 'includes/admin-horse-menu.php';

$sql = "SELECT jockeys.ID, jockeys.firstName, jockeys.lastName, countries.name 
FROM jockeys 
JOIN countries 	
ON jockeys.countryID=countries.id
ORDER BY
countries.name, jockeys.lastName
";
;

$result = mysql_query($sql);


echo "<table>
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
  echo "<td>" . $row['3'] . "</td>";
  echo "<td align='center'><a href=" . "edit-jockeys.php?id=". $row['ID'] . ">update</a></td>";
  echo "</tr>";
  }
echo "</table>";

?>

<br>
<br>

<h2>Add new jockeys</h2>
<form action="add-jockeys-db.php" method="post" class="ajax">
<input type="text" name="firstName" placeholder="First Name">
<input type="text" name="lastName" placeholder="Last Name">
<select name="countryID"><?php country_dropDown() ?></select>
<input type="submit" name="Submit" value="Add">
</form>



<script src="js/contact.js"></script>
<?php include 'includes/overall/footer.php'; ?>