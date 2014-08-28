<?php
include 'core/init.php';
protect_page();
admin_protect();
include 'includes/overall/header.php';
?>

<h1>Edit Sports</h1>
<?php include 'includes/admin-menu.php'; ?>
<br><br>
<?php 
$id=$_GET['id']; // Gets value of ID sent from address bar

$sql = "SELECT * FROM sports WHERE ID='$id'";
$result = mysql_query($sql);
$rows=mysql_fetch_array($result);

?>

<form name="form1" method="post" action="update-sports-db.php">
<input name="name" type="text" id="name" value="<? echo $rows['name']; ?>">
<input name="id" type="hidden" id="id" value="<? echo $id; ?>">
<input type="submit" name="Submit" value="Update">
</form>

<?php include 'includes/overall/footer.php'; ?>