<?php
include 'core/init.php';
protect_page();
admin_protect();
include 'includes/overall/header.php';
?>

<h1>Edit Events</h1>
<?php include 'includes/admin-menu.php'; ?>
<br><br>
<?php 
$id=$_GET['id']; // Gets value of ID sent from address bar

$sql = "SELECT * FROM events WHERE ID='$id'";
$result = mysql_query($sql);
$rows=mysql_fetch_array($result);
print_r($rows);
?>

<form name="form1" method="post" action="update-events-db.php">
<input name="name" type="text" value="<? echo $rows['name']; ?>">
<input name="countryID" type="text" value="<? echo $rows['countryID']; ?>">
<input name="sportID" type="text" value="<? echo $rows['sportID']; ?>">
<input name="id" type="hidden" value="<? echo $id; ?>">
<input type="submit" name="Submit" value="Update">
</form>
<p>Australia=14, South Africa= 206, UK=235</p>
<?php include 'includes/overall/footer.php'; ?>