<?php
include 'core/init.php';
protect_page();
admin_protect();
include 'includes/overall/header.php';
?>

<h1>Edit Jockeys</h1>
<?php include 'includes/admin-horse-menu.php';

// Gets values from ID sent from address bar
$id=$_GET['id']; 

$sql = "SELECT * FROM jockeys WHERE ID='$id'";
$result = mysql_query($sql);
$rows=mysql_fetch_array($result);
?>

<form method="post" action="update-jockeys-db.php" class="ajax">
<input name="firstName" type="text" value="<? echo $rows['firstName']; ?>">
<input name="lastName" type="text" value="<? echo $rows['lastName']; ?>">
<input name="countryID" type="text" value="<? echo $rows['countryID']; ?>">
<input name="id" type="hidden" value="<? echo $id; ?>">
<input type="submit" name="Submit" value="Update">
</form>
<p>Australia=14, South Africa= 206, UK=235</p>

<script src="js/contact.js"></script>
<?php include 'includes/overall/footer.php'; ?>