<?php
include 'core/init.php';
protect_page();
admin_protect();
include 'includes/overall/header.php';
?>

<h1>Add Sport to list</h1>
<?php include 'includes/sports-admin-menu.php'; ?>
<br><br>


<form name="form1" method="post" action="add-sports-db.php">
Enter new sport: <input type="text" name="name" >
<input type="submit" name="Submit" value="Add">
</form>

<?php include 'includes/overall/footer.php'; ?>