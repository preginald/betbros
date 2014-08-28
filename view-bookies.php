<?php
include 'core/init.php';
protect_page();
admin_protect();
include 'includes/overall/header.php';
?>

<h1>View Bookie List</h1>
<?php include 'includes/admin-menu.php'; ?>
<br><br>
<h2>List of Bookies</h2>
<?php 

$sql = "SELECT * FROM bookies";
$result = mysql_query($sql);



// echo $row['ID'] . " " . $row['name'] . '<br>';

echo "<table border='0'>
<tr>
<th>ID</th>
<th>Bookie</th>
<th>Update</th>
</tr>";

while($row = mysql_fetch_array($result)) {

  echo "<tr>";
  echo "<td>" . $row['ID'] . "</td>";
  echo "<td>" . $row['name'] . "</td>";
  echo "<td align='center'><a href=" . "edit-sports.php?id=". $row['ID'] . ">update</a></td>";
  echo "</tr>";
  }
echo "</table>";

?>

<br>
<br>

<form name="form1" method="post" action="add-bookies-db.php">
<h2>Add new bookie</h2>
<input type="text" name="name" >
<input type="submit" name="Submit" value="Add">
</form>



<?php include 'includes/overall/footer.php'; ?>