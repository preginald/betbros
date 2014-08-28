<?php
include 'core/init.php';
protect_page();
admin_protect();
include 'includes/overall/header.php';
?>

<h1>Edit Team</h1>
<?php include 'includes/admin-menu.php'; ?>
<br><br>
<?php 
$id=$_GET['id']; // Gets value of ID sent from address bar

$sql = "SELECT * FROM teams WHERE ID='$id'";
$result = mysql_query($sql);
$rows=mysql_fetch_array($result);
print_r($rows);
?>

<form method="post" action="update-teams-db.php" class="ajax">
<input name="name" type="text" value="<? echo $rows['name']; ?>">
<input name="countryID" type="text" value="<? echo $rows['countryID']; ?>">
<input name="sportID" type="text" value="<? echo $rows['sportID']; ?>">
<input name="eventID" type="text" value="<? echo $rows['eventID']; ?>">
<input name="id" type="hidden" value="<? echo $id; ?>">
<input type="submit" name="Submit" value="Update">
</form>

<?php include 'includes/overall/footer.php'; ?>