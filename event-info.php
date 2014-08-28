<?php
include 'core/init.php';
protect_page();
admin_protect();
include 'includes/overall/header.php';

$eventID=$_GET['id']; // Gets value of ID sent from address bar

$sql = "SELECT * FROM teams WHERE eventID='$eventID'";
$result = mysql_query($sql);
$rows=mysql_fetch_array($result);


// Get event name from event table for page heading
$sql2 = "SELECT name FROM events WHERE ID='$eventID'";
$result2 = mysql_query($sql2);
$eventHeading= mysql_result($result2, 0);

echo "<h1>" . $eventHeading . "</h1>";

include 'includes/admin-menu.php'; 
echo "<br><br>";



echo "<table>
<tr>
<th>ID</th>
<th>Team</th>
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