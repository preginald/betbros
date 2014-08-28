<?php
include 'core/init.php';
protect_page();
admin_protect();
include 'includes/overall/header.php';
?>

<h1>View and Edit Sports</h1>
<?php include 'includes/sports-admin-menu.php'; ?>
<br><br>
<h2>List of Sports</h2>
<?php 
$result = mysql_query("SELECT * FROM sports");



// echo $row['ID'] . " " . $row['name'] . '<br>';

echo "<table border='0'>
<tr>
<th>ID</th>
<th>Sport</th>
</tr>";

while($row = mysql_fetch_array($result)) {

  echo "<tr>";
  echo "<td>" . $row['ID'] . "</td>";
  echo "<td>" . $row['name'] . "</td>";
  echo "</tr>";
  }
echo "</table>";



?>

<?php include 'includes/overall/footer.php'; ?>